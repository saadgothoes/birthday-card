<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ClientPasswordReset;
use App\Models\BirthdayCard;
use App\Models\User;
use App\Models\MusicTrack;
use App\Support\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ClientAuthController extends Controller
{
    public function loginPage()
    {
        if (Auth::check() && Auth::user()->isClient() && Auth::user()->status === 'active') {
            return redirect()->route('client.cards');
        }

        return view('client.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->isClient() && Auth::user()->status === 'active') {
                $request->session()->regenerate();
                return redirect()->route('client.cards');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'Your account is disabled or access not allowed.']);
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    /**
     * Clients now create their own accounts — the Super Admin no longer
     * provisions them. A fresh account starts with no plan at all: it can
     * build a card, but the QR step stays locked until a subscription
     * request is approved.
     */
    public function registerPage()
    {
        if (Auth::check() && Auth::user()->isClient() && Auth::user()->status === 'active') {
            return redirect()->route('client.cards');
        }

        return view('client.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:190|unique:users,email',
            'phone'    => 'required|string|max:20',
            'city'     => 'required|string|max:100',
            'age'      => 'required|integer|min:1|max:120',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);

        $user = User::create([
            'name'             => $data['name'],
            'email'            => $data['email'],
            'phone'            => $data['phone'],
            'city'             => $data['city'],
            'age'              => $data['age'],
            'password'         => $data['password'],
            // Self-chosen from the start, so there is no generated password to
            // keep around and nothing to nag them about changing.
            'plain_password'   => null,
            'password_changed' => true,
            'role'             => 'client',
            'status'           => 'active',
            'subscription_status' => User::SUB_NONE,
            'card_limit'       => SubscriptionPlans::FREE_CARD_LIMIT,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('client.cards')
            ->with('success', 'Welcome! Your account is ready — build your first card.');
    }

    /**
     * The wizard, opened on one specific card.
     *
     * `?card=` names the card being edited. Without it the client is sent to
     * the card hub rather than being dropped into whatever draft happened to
     * be newest — that implicit behaviour was what made a "new" card come up
     * pre-filled with the previous card's data.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $card = null;
        if ($request->filled('card')) {
            $card = BirthdayCard::where('user_id', $user->id)
                ->whereKey($request->query('card'))
                ->first();
        }

        if (! $card) {
            return redirect()->route('client.cards');
        }

        // Every save in this tab is tagged with this card, so no step can
        // write into a different one.
        session(['active_card_id' => $card->id]);
        $card->forceFill(['last_opened_at' => now()])->save();

        // The QR step previews the four designs against the card's own share
        // link, so the address has to be settled before that step is reached,
        // not at publish time — otherwise the previews would encode one URL
        // and the generated code another.
        BirthdayCardController::ensureSlug($card);

        return view('client.dashboard', [
            'card' => $card,
            'musicTracks' => MusicTrack::where('is_active', true)->latest()->get(),
            'plans' => SubscriptionPlans::all(),
            'hasSubscription' => $user->hasActiveSubscription(),
            'pendingRequest' => $user->pendingSubscriptionRequest(),
            'planLabel' => $user->planLabel(),
            'cardLimit' => $user->cardLimit(),
            'cardsUsed' => $user->cardsUsed(),
            'cardsRemaining' => $user->cardsRemaining(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('client.login');
    }

    // Settings/Profile page
    public function settings()
    {
        return view('client.settings');
    }

    // Profile page
    public function profile()
    {
        return view('client.profile');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => $request->password,
            'password_changed' => true,
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    // Forgot password form
    public function forgotPassword()
    {
        return view('client.forgot-password');
    }

    // Send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(fn($query) => $query
                    ->where('role', 'client')),
            ],
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'client')
            ->firstOrFail();

        if ($user->status !== 'active') {
            return back()->withErrors([
                'email' => 'Your account is currently disabled. Please contact support to reactivate it before resetting your password.',
            ])->withInput();
        }

        $token = Password::createToken($user);
        $resetUrl = route('client.password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        try {
            Mail::to($user->email)->send(new ClientPasswordReset($resetUrl, $user));

            return back()->with('status', 'Password reset link sent to your email!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Unable to send reset link. Please try again.']);
        }
    }

    public function resetPasswordPage(string $token, Request $request)
    {
        return view('client.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(fn($query) => $query
                    ->where('role', 'client')
                    ->where('status', 'active')),
            ],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                if (!$user->isClient()) {
                    return;
                }

                $user->forceFill([
                    'password' => $password,
                    'password_changed' => true,
                    'plain_password' => null,
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('client.login')->with('status', 'Password reset successfully!')
            : back()->withErrors(['email' => [__($status)]])->withInput($request->only('email'));
    }
}
