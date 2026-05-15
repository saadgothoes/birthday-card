<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ClientPasswordReset;
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
        return view('client.dashboard');
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
            'email' => 'required|email|exists:users,email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // Only allow forgot password if user has changed password at least once
        if (!$user->hasChangedPassword()) {
            return back()->withErrors(['email' => 'Password reset is not available for new accounts. Please contact support.']);
        }

        // Generate reset token
        $token = Password::createToken($user);

        // Create reset URL
        $resetUrl = route('client.password.reset', [
            'token' => $token,
            'email' => $user->email
        ]);

        // Send custom email
        try {
            Mail::to($user->email)->send(new ClientPasswordReset($resetUrl, $user));
            return back()->with('status', 'Password reset link sent to your email!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Unable to send reset link. Please try again.']);
        }
    }
}
