<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Login Page
    public function loginPage()
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // Login Logic
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->isSuperAdmin()) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'You are not authorized as Super Admin.']);
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // Update settings
    public function updateSettings(Request $request)
    {
        $request->validate([
            'default_subscription_fee' => 'required|numeric|min:0',
        ]);

        Auth::user()->update([
            'default_subscription_fee' => $request->default_subscription_fee,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Settings updated successfully.');
    }

    // BG Owner Page
    public function bgOwner()
    {
        return view('admin.bg-owner');
    }

    // Verify BG Owner PIN
    public function verifyBgOwnerPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6',
        ]);

        if (Auth::user()->bg_owner_pin === $request->pin) {
            // Store in session that PIN is verified
            session(['bg_owner_verified' => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid PIN']);
    }
}
