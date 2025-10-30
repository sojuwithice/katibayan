<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCredentialsMail;
use App\Http\Controllers\Auth\RegisterController;

class AdminController extends Controller
{
    /**
     * Show the main admin dashboard page.
     * (Dito mo ilalagay yung mga stats, etc. sa future)
     */
    public function dashboard()
    {
        // For now, it just returns the dashboard view.
        return view('admindashb');
    }

    /**
     * ITO ANG BAGO:
     * Show the user management page with all pending accounts.
     */
    public function userManagement()
    {
        // Kinuha natin yung logic mula sa lumang dashboard function
        $skUsers = User::whereIn('role', ['sk', 'kk'])->get(); // Kunin na natin pareho SK at KK

        // Dapat ang view na tinatawag ay 'user-management'
        return view('user-management', compact('skUsers'));
    }

    /**
     * Approve user account.
     */
    public function approve($id)
{
    $user = User::findOrFail($id);

    if ($user->account_status !== 'pending') {
        return back()->with('error', 'This account is not pending for approval.');
    }

    // Handle SK user approval
    if ($user->role === 'sk') {
        $registerController = new RegisterController();
        $result = $registerController->sendSKCredentials($user);

        if ($result) {
            return back()->with('success', 'SK user approved and credentials sent.');
        } else {
            return back()->with('error', 'Failed to send credentials email for SK user.');
        }
    }

    // Handle KK (Youth) user approval
    if ($user->role === 'kk') {
        // Check if there is at least one approved SK Chair in the same barangay
        $hasApprovedChair = User::where('role', 'sk')
            ->where('barangay_id', $user->barangay_id)
            ->where('account_status', 'approved')
            ->exists();

        if (!$hasApprovedChair) {
            return back()->with('error', 'Youth cannot be approved yet. The SK Chair for this barangay has not been approved.');
        }

        // Proceed if SK Chair exists
        $user->account_status = 'approved';
        $user->save();

        return back()->with('success', 'KK member has been approved successfully.');
    }

    return back()->with('error', 'Invalid user role for approval.');
}


    /**
     * Reject user account.
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'rejected';
        $user->save();

        return back()->with('success', 'User account has been rejected.');
    }
}