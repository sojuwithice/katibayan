<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCredentialsMail;
use Carbon\Carbon;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show all SK users for admin review.
     */
    public function dashboard()
    {
        // Fetch only SK users for approval
        $skUsers = User::where('role', 'sk')->get();

        return view('admin-dashboard', compact('skUsers'));
    }

    /**
     * Approve SK user account.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'sk') {
            return back()->with('error', 'Only SK accounts require admin approval.');
        }

        if ($user->account_status !== 'pending') {
            return back()->with('error', 'User is not pending.');
        }

        // Use the RegisterController's method to handle password generation and email
        $registerController = new RegisterController();
        $result = $registerController->sendSKCredentials($user);

        if ($result) {
            return back()->with('success', 'SK user approved and credentials sent.');
        } else {
            return back()->with('error', 'Failed to send credentials email.');
        }
    }

    /**
     * Reject SK user account.
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'rejected';
        $user->save();

        return back()->with('success', 'User rejected.');
    }

    /**
     * Show admin login page
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $credentials = $request->only('username', 'password');

    // Use the admin guard
    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('admin.dashboard')
            ->with('success', 'Welcome back, Admin!');
    }

    return back()->withErrors(['Invalid username or password.']);
}


public function logout()
{
    \Illuminate\Support\Facades\Auth::guard('admin')->logout();

    return redirect()->route('admin.login')
        ->with('success', 'You have been logged out.');
}

}
