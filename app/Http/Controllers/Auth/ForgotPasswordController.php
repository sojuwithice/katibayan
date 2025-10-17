<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Make sure you have a User model
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendOtpMail; // We will create this Mailable next

class ForgotPasswordController extends Controller
{
    /**
     * Send a 6-digit OTP to the user's email.
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->first()], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();
            $otp = random_int(100000, 999999); // Generate a 6-digit OTP

            // Store OTP and email in session for verification later
            $request->session()->put('otp', $otp);
            $request->session()->put('otp_email', $request->email);
            $request->session()->put('otp_generated_at', now());

            // Send the OTP to the user's email
            Mail::to($user->email)->send(new SendOtpMail($otp));

            return response()->json(['success' => true, 'message' => 'OTP sent successfully.']);

        } catch (\Exception $e) {
            // Log the error for debugging
            // Log::error('OTP Sending Error: '.$e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to send OTP. Please try again later.'], 500);
        }
    }

    /**
     * Verify the provided OTP.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->first()], 422);
        }

        // Check if OTP has expired (e.g., 5 minutes validity)
        $generatedAt = $request->session()->get('otp_generated_at');
        if (!$generatedAt || now()->diffInMinutes($generatedAt) > 5) {
            $request->session()->forget(['otp', 'otp_email', 'otp_generated_at']);
            return response()->json(['success' => false, 'error' => 'OTP has expired. Please request a new one.']);
        }

        // Check if the OTP and email match what's in the session
        if ($request->otp == $request->session()->get('otp') && $request->email == $request->session()->get('otp_email')) {
            // OTP is correct, mark it as verified in the session
            $request->session()->put('password_reset_verified', true);
            return response()->json(['success' => true, 'message' => 'OTP verified successfully.']);
        }

        return response()->json(['success' => false, 'error' => 'The OTP you entered is incorrect.'], 400);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        // First, check if the OTP was actually verified
        if (!$request->session()->get('password_reset_verified', false)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please verify OTP first.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed', // 'confirmed' checks for 'password_confirmation' field
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Check if the email matches the one from the OTP verification step
        if ($request->email != $request->session()->get('otp_email')) {
             return response()->json(['success' => false, 'message' => 'An error occurred. Please start over.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clean up session data after successful reset
        $request->session()->forget(['otp', 'otp_email', 'otp_generated_at', 'password_reset_verified']);

        return response()->json(['success' => true, 'message' => 'Your password has been reset successfully!']);
    }
}