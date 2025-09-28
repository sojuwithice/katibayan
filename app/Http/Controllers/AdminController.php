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

    // Generate account number + password here
    $birthdate = $user->date_of_birth
        ? Carbon::parse($user->date_of_birth)->format('Ymd')
        : now()->format('Ymd');

    $accountNumber = 'SK' . $birthdate;
    $plainPassword = 'SK' . rand(1000, 9999);

    $user->account_number = $accountNumber;
    $user->password = bcrypt($plainPassword);
    $user->account_status = 'approved';
    $user->save();

    // Send credentials email once
    Mail::to($user->email)->send(
        new AccountCredentialsMail($user, $accountNumber, $plainPassword)
    );

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
