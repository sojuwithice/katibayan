<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('loginpage'); 
    }

    // Handle login
    public function login(Request $request)
{
    $credentials = $request->validate([
        'account_number' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        //Role-based redirect
        if ($user->role === 'admin') {
            return redirect()->intended('/admin-dashboard')
                ->with('success', 'Welcome, Admin!');
        } elseif ($user->role === 'sk') {
            return redirect()->intended('/sk-dashboard')
                ->with('success', 'Logged in successfully.');
        } elseif ($user->role === 'kk') {
            return redirect()->intended('/dashboard')
                ->with('success', 'Logged in successfully.');
        }

        // fallback (if other roles appear later)
        return redirect()->intended('/')
            ->with('success', 'Logged in successfully.');
    }

    return back()->withErrors([
        'account_number' => 'Invalid account number or password.',
    ])->withInput();
}



    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}
