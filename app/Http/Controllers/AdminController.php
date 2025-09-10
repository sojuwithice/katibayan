<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show all SK users for admin review.
     */
    public function dashboard()
    {
        // Fetch only SK users
        $skUsers = User::where('role', 'sk')->get();

        // Blade file is directly in /resources/views/admin-dashboard.blade.php
        return view('admin-dashboard', compact('skUsers'));
    }

    /**
     * Approve SK user account.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'approved'; // ✅ make sure it's a string
        $user->save();

        return back()->with('success', 'User accepted successfully.');
    }

    /**
     * Reject SK user account.
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'rejected'; // ✅ string
        $user->save();

        return back()->with('success', 'User rejected.');
    }
}
