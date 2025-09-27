<?php

use App\Models\User;
use App\Mail\AccountCredentialsMail;
use Illuminate\Support\Facades\Mail;

class KkMemberController extends Controller
{
    public function createAccount($id)
    {
        // Kunin yung KK member
        $user = User::findOrFail($id);

        // 1. Generate account number: [role][birthdate in Ymd]
        $rolePrefix = strtoupper(substr($user->role, 0, 2)); // example: 'KK'
        $birthdate = $user->birthdate->format('Ymd'); // make sure birthdate is Carbon instance
        $accountNumber = $rolePrefix . $birthdate;

        // 2. Generate random password: [role][6 random digits]
        $plainPassword = $rolePrefix . rand(100000, 999999);

        // 3. Save account number and hashed password
        $user->account_number = $accountNumber;
        $user->password = bcrypt($plainPassword);
        $user->status = 'approved'; // optional
        $user->save();

        // 4. Send email with credentials
        Mail::to($user->email)->send(new AccountCredentialsMail($user, $accountNumber, $plainPassword));

        return redirect()->back()->with('success', 'Account created and email sent!');
    }
}
