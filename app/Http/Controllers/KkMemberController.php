<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\AccountCredentialsMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class KkMemberController extends Controller
{
    public function createAccount($id)
    {
        $user = User::findOrFail($id);

        // 1. Role prefix
        $rolePrefix = strtoupper(substr($user->role, 0, 2)); // 'KK'

        // 2. Birthdate in Ymd
        $birthdate = $user->birthdate
            ? Carbon::parse($user->birthdate)->format('Ymd')
            : now()->format('Ymd');

        // 3. Initials from full name
        $names = explode(' ', $user->name);
        $initials = '';
        foreach ($names as $n) {
            if (!empty($n)) {
                $initials .= strtoupper(substr($n, 0, 1));
            }
        }

        // 4. Combine role + birthdate + initials
        $accountNumber = $rolePrefix . $birthdate . $initials;

        // 5. Generate random password: role + 6 random digits
        $plainPassword = $rolePrefix . rand(100000, 999999);

        // 6. Save account number and hashed password
        $user->account_number = $accountNumber;
        $user->password = bcrypt($plainPassword);
        $user->status = 'approved'; // optional
        $user->save();

        // 7. Send email with credentials using fresh() to ensure updated data
        Mail::to($user->email)->send(
            new AccountCredentialsMail($user->fresh(), $accountNumber, $plainPassword)
        );

        // 8. Optional debug logs
        \Log::info('Generated KK account number: '.$accountNumber);
        \Log::info('User account number in DB: '.$user->account_number);

        return redirect()->back()->with('success', 'Account created and email sent!');
    }
}
