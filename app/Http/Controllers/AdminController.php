<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCredentialsMail;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show all SK users for admin review.
     * KK users are not shown because they auto-approve.
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

    // 1. Role prefix
    $rolePrefix = strtoupper(substr($user->role, 0, 2)); // 'SK'

    // 2. Birthdate in Ymd
    $birthdate = $user->date_of_birth
        ? Carbon::parse($user->date_of_birth)->format('Ymd')
        : now()->format('Ymd');

    // 3. Initials from full name

    $fullName = trim($user->given_name . ' ' . ($user->middle_name ?? '') . ' ' . $user->last_name);
    $names = explode(' ', $fullName);
    $initials = '';
    foreach ($names as $n) {
        if (!empty($n)) {
            $initials .= strtoupper(substr($n, 0, 1));
        }
    }

    // Combine role + birthdate + initials
    $accountNumber = $rolePrefix . $birthdate . $initials;


    // 5. Generate random password: role + 4 digits
    $plainPassword = $rolePrefix . rand(1000, 9999);

    // 6. Save account number and hashed password
    $user->account_number = $accountNumber;
    $user->password = bcrypt($plainPassword);
    $user->account_status = 'approved';
    $user->save();

    // 7. Send credentials email
    Mail::to($user->email)->send(new AccountCredentialsMail($user, $accountNumber, $plainPassword));

    return back()->with('success', 'SK user approved and credentials sent.');
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
}
