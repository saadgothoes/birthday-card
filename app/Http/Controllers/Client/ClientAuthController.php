<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
    public function loginPage()
    {
        if (Auth::check() && Auth::user()->isClient()) {
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
            if (Auth::user()->isClient()) {
                $request->session()->regenerate();
                return redirect()->route('client.dashboard');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'Access not allowed.']);
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
}