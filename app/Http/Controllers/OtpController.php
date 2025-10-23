<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $email = $request->email;
        $otp = rand(100000, 999999);

        // store temporarily for 5 minutes
        Cache::put('otp_' . $email, $otp, now()->addMinutes(5));

        Mail::raw("Your verification code is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Your OTP Code');
        });

        return response()->json(['success' => true]);
    }

    public function verifyOtp(Request $request)
    {
        $email = $request->email;
        $inputOtp = $request->otp;
        $cachedOtp = Cache::get('otp_' . $email);

        if ($cachedOtp && $cachedOtp == $inputOtp) {
    Cache::forget('otp_' . $email);
    return response()->json(['success' => true]);
}

return response()->json(['success' => false, 'error' => 'Invalid or expired OTP']);

    }
}
