<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $key = Str::lower($request->input('account_number')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'account_number' => "Too many login attempts. Try again in {$seconds} seconds.",
            ]);
        }

        $credentials = [
            'username' => $request->input('account_number'),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            return redirect()->intended('/admin-dashboard')->with('success', 'Welcome back, Admin!');
        }

        RateLimiter::hit($key);
        return back()->withErrors(['account_number' => 'Invalid account number or password.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}
