<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class VerificationController extends Controller
{
    public function sendOtp(Request $request)
    {
        $otp = rand(100000, 999999);
        Session::put('otp_code', $otp);
        Session::put('otp_email', $request->email);

        Mail::raw("Your OTP code is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Your Verification Code');
        });

        return response()->json(['success' => true]);
    }

    public function verifyOtp(Request $request)
    {
        $inputCode = implode('', $request->code);
        if (
            Session::get('otp_code') == $inputCode &&
            Session::get('otp_email') == $request->email
        ) {
            Session::forget(['otp_code', 'otp_email']);
            return response()->json(['verified' => true]);
        }
        return response()->json(['verified' => false]);
    }
}
