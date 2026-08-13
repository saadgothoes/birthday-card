<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ClientPasswordReset;
use App\Models\BirthdayCard;
use App\Models\User;
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
            return redirect()->route('client.dashboard');
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
                return redirect()->route('client.dashboard');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'Your account is disabled or access not allowed.']);
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function dashboard()
    {
        $card = BirthdayCard::where('user_id', Auth::id())
            ->where('is_published', false)
            ->latest()
            ->first();

        return view('client.dashboard', ['card' => $card]);
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
                Rule::exists('users', 'email')->where(fn ($query) => $query
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
                Rule::exists('users', 'email')->where(fn ($query) => $query
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
