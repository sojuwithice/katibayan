<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KKMember;
use App\Models\SkOfficial;
use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\AccountCredentialsMail;
use Illuminate\Support\Facades\Mail;
use App\Models\City;
use App\Models\Barangay;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $regions = Region::all();
        return view('register', compact('regions')); 
    }

    protected function validator(array $data)
    {
        $rules = [
            'last_name' => 'required|string|max:100',
            'given_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            
            // Location fields
            'region_id' => 'required|exists:regions,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'barangay_id' => 'required|exists:barangays,id',
            'purok_zone' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',

            'date_of_birth' => 'required|date',
            'sex' => 'required|in:male,female',
            'email' => 'required|string|email|max:255|unique:users',
            'contact_no' => 'required|string|max:20',
            'civil_status' => 'required|string',
            'education' => 'required|string',
            'work_status' => 'required|string',
            'youth_classification' => 'required|string',
            'sk_voter' => 'required|in:Yes,No',
            'role' => 'required|in:sk,kk',
        ];

        // File rules by role
        if (isset($data['role']) && $data['role'] === 'sk') {
            $rules['oath_certificate'] = 'required|file|mimes:pdf,png,jpg,jpeg|max:5120';
        } elseif (isset($data['role']) && $data['role'] === 'kk') {
            $rules['barangay_indigency'] = 'required|file|mimes:pdf,png,jpg,jpeg|max:5120';
        }

        return Validator::make($data, $rules);
    }

    protected function previewValidator(array $data)
    {
        $rules = [
            'last_name' => 'required|string|max:100',
            'given_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            
            'region_id' => 'required|exists:regions,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'barangay_id' => 'required|exists:barangays,id',
            'purok_zone' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',

            'date_of_birth' => 'required|date',
            'sex' => 'required|in:male,female',
            'email' => 'required|string|email|max:255|unique:users',
            'contact_no' => 'required|string|max:20',
            'civil_status' => 'required|string',
            'education' => 'required|string',
            'work_status' => 'required|string',
            'youth_classification' => 'required|string',
            'sk_voter' => 'required|in:Yes,No',
            'role' => 'required|in:sk,kk',
            'certify_info' => 'required|accepted',
            'certify_final' => 'required|accepted',
            'confirm_submission' => 'required|accepted',
        ];

        // File rules by role - required for preview
        if (isset($data['role']) && $data['role'] === 'sk') {
            $rules['oath_certificate'] = 'required|file|mimes:pdf,png,jpg,jpeg|max:5120';
        } elseif (isset($data['role']) && $data['role'] === 'kk') {
            $rules['barangay_indigency'] = 'required|file|mimes:pdf,png,jpg,jpeg|max:5120';
        }

        return Validator::make($data, $rules);
    }

    /**
     * Step 1: Preview route — validate, store files temporarily and keep data in session,
     * then redirect to captcha page.
     */
    public function preview(Request $request)
    {
        Log::info('Preview method called', ['data' => $request->except(['_token', 'oath_certificate', 'barangay_indigency'])]);

        // Use the preview validator which includes checkbox validation
        $validator = $this->previewValidator($request->all());
        
        if ($validator->fails()) {
            Log::error('Validation failed in preview', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors below.');
        }

        // Store inputs except files and checkboxes
        $data = $request->except(['_token', 'oath_certificate', 'barangay_indigency', 'certify_info', 'certify_final', 'confirm_submission']);

        // Store uploaded files temporarily in public disk under temp/
        if ($request->hasFile('oath_certificate')) {
            $data['oath_certificate_temp'] = $request->file('oath_certificate')->store('temp', 'public');
            Log::info('Oath certificate stored temporarily', ['path' => $data['oath_certificate_temp']]);
        }
        if ($request->hasFile('barangay_indigency')) {
            $data['barangay_indigency_temp'] = $request->file('barangay_indigency')->store('temp', 'public');
            Log::info('Barangay indigency stored temporarily', ['path' => $data['barangay_indigency_temp']]);
        }

        // Save to session with flash data to persist across redirects
        $request->session()->put('pending_registration', $data);
        Log::info('Data stored in session, redirecting to captcha');

        // Redirect to captcha page
        return redirect()->route('register.captcha');
    }

    /**
     * Show captcha page (only if session has pending_registration)
     */
    public function showCaptcha()
    {
        if (!session()->has('pending_registration')) {
            Log::error('No pending registration in session');
            return redirect()->route('register')->with('error', 'Session expired. Please re-submit the form.');
        }

        $siteKey = config('services.recaptcha.site_key');
        
        if (!$siteKey) {
            Log::error('reCAPTCHA site key not configured');
            return redirect()->route('register')->with('error', 'System configuration error. Please try again later.');
        }

        Log::info('Showing captcha page');
        return view('registration-captcha', compact('siteKey'));
    }

    /**
     * Complete registration: verify captcha with Google, finalize files, create user and role
     */
    public function complete(Request $request)
    {
        Log::info('Complete method called');

        if (!session()->has('pending_registration')) {
            Log::error('No pending registration in session for complete method');
            return redirect()->route('register')->with('error', 'Session expired. Please re-submit the form.');
        }

        $token = $request->input('g-recaptcha-response');
        
        // DEVELOPMENT MODE: Bypass reCAPTCHA for local testing
        if (app()->environment('local')) {
            Log::info('Local environment detected - bypassing reCAPTCHA verification');
            // Skip reCAPTCHA verification in local development
        } else {
            // PRODUCTION: Verify reCAPTCHA
            if (!$token) {
                Log::error('No reCAPTCHA token provided');
                return redirect()->route('register.captcha')->withErrors('Please complete the captcha.');
            }

            try {
                $secret = config('services.recaptcha.secret');
                
                if (!$secret) {
                    Log::error('reCAPTCHA secret key not configured');
                    return redirect()->route('register.captcha')->withErrors('System configuration error. Please contact administrator.');
                }

                $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

                if (!$resp->successful()) {
                    Log::error('reCAPTCHA API request failed', ['status' => $resp->status()]);
                    return redirect()->route('register.captcha')->withErrors('Captcha service unavailable. Please try again.');
                }

                $body = $resp->json();
                Log::info('reCAPTCHA verification response', $body);

                if (!($body['success'] ?? false)) {
                    Log::error('reCAPTCHA verification failed', $body);
                    return redirect()->route('register.captcha')->withErrors('Captcha verification failed. Please try again.');
                }
            } catch (\Exception $e) {
                Log::error('reCAPTCHA verification error: ' . $e->getMessage());
                Log::info('Proceeding despite reCAPTCHA error for testing');
            }
        }

        // Continue with registration process...
        $data = session('pending_registration');
        Log::info('Processing registration data from session');

        try {
            // Move temp files to final location
            if (!empty($data['oath_certificate_temp'])) {
                $temp = $data['oath_certificate_temp'];
                $final = 'documents/sk/' . basename($temp);
                Storage::disk('public')->move($temp, $final);
                $data['oath_certificate_path'] = $final;
                unset($data['oath_certificate_temp']);
                Log::info('Moved oath certificate to final location', ['from' => $temp, 'to' => $final]);
            }
            if (!empty($data['barangay_indigency_temp'])) {
                $temp = $data['barangay_indigency_temp'];
                $final = 'documents/kk/' . basename($temp);
                Storage::disk('public')->move($temp, $final);
                $data['barangay_indigency_path'] = $final;
                unset($data['barangay_indigency_temp']);
                Log::info('Moved barangay indigency to final location', ['from' => $temp, 'to' => $final]);
            }

            // Create the user
            $user = $this->create($data);
            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);

            // Create role-specific models
            if (isset($data['role']) && $data['role'] === 'sk') {
                SkOfficial::create([
                    'user_id' => $user->id,
                    'oath_certificate_path' => $data['oath_certificate_path'] ?? null,
                    'status' => 'pending', // NEW: SK officials start as pending
                ]);
                Log::info('SK official record created with pending status');
            } elseif (isset($data['role']) && $data['role'] === 'kk') {
                KKMember::create([
                    'user_id' => $user->id,
                    'barangay_indigency_path' => $data['barangay_indigency_path'] ?? null,
                    'status' => 'approved', // KK members are auto-approved
                ]);
                Log::info('KK member record created with approved status');
            }

            // Cleanup session
            session()->forget('pending_registration');
            Log::info('Session cleaned up, redirecting to success');

            // Redirect to success page with different messages based on role
            $role = $data['role'] ?? 'user';
            
            if ($role === 'sk') {
                return redirect()->route('registration.success')
                    ->with('success', 'Registration submitted successfully. Your SK Chairperson account is pending admin approval. You will receive credentials once approved.');
            } else {
                return redirect()->route('registration.success')
                    ->with('success', 'Registration submitted. Your KK account has been created. Check your email for login credentials.');
            }

        } catch (\Exception $e) {
            Log::error('Error during user creation: ' . $e->getMessage());
            session()->forget('pending_registration');
            return redirect()->route('register')->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    protected function create(array $data)
    {
        // Role prefix
        $prefix = strtoupper($data['role']); // SK or KK

        // Birthdate in Ymd
        $birthdate = date('Ymd', strtotime($data['date_of_birth']));

        // Get initials from name
        $fullName = $data['given_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name'];
        $names = explode(' ', $fullName);
        $initials = '';
        foreach ($names as $n) {
            if (!empty($n)) {
                $initials .= strtoupper(substr($n, 0, 1));
            }
        }

        // Combine role + birthdate + initials
        $accountNumber = $prefix . $birthdate . $initials;

        // Handle password generation based on role
        if ($data['role'] === 'kk') {
            // KK: Generate password immediately and send email
            $plainPassword = $prefix . rand(1000, 9999);
            $passwordHash = Hash::make($plainPassword);
            $defaultPassword = $plainPassword;
            $accountStatus = 'approved'; // KK auto-approved
        } else {
            // SK: No password until approved by admin
            $plainPassword = null;
            $passwordHash = Hash::make(Str::random(32)); // Temporary random password
            $defaultPassword = null;
            $accountStatus = 'pending'; // SK needs admin approval
        }

        // Log password generation for debugging
        Log::info('PASSWORD DEBUG - Before user creation:', [
            'role' => $data['role'],
            'generated_password' => $plainPassword,
            'account_number' => $accountNumber,
            'email' => $data['email'],
            'account_status' => $accountStatus
        ]);

        // Create user
        $user = User::create([
            'role' => $data['role'],
            'account_number' => $accountNumber,
            'last_name' => $data['last_name'],
            'given_name' => $data['given_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'suffix' => $data['suffix'] ?? null,

            // Location
            'region_id' => $data['region_id'],
            'province_id' => $data['province_id'],
            'city_id' => $data['city_id'],
            'barangay_id' => $data['barangay_id'],
            'purok_zone' => $data['purok_zone'],
            'zip_code' => $data['zip_code'],

            // Personal
            'date_of_birth' => $data['date_of_birth'],
            'sex' => $data['sex'],
            'email' => $data['email'],
            'contact_no' => $data['contact_no'],
            'civil_status' => $data['civil_status'],
            'education' => $data['education'],
            'work_status' => $data['work_status'],
            'youth_classification' => $data['youth_classification'],
            'sk_voter' => $data['sk_voter'],

            // Account status & password
            'account_status' => $accountStatus, // 'approved' for KK, 'pending' for SK
            'password' => $passwordHash,
            'default_password' => $defaultPassword,
        ]);

        // Debug: Verify what was stored in database
        $freshUser = User::find($user->id);
        Log::info('PASSWORD DEBUG - After user creation:', [
            'role' => $freshUser->role,
            'stored_default_password' => $freshUser->default_password,
            'account_status' => $freshUser->account_status
        ]);

        // Send email ONLY for KK users (auto-approved)
        if ($data['role'] === 'kk') {
            try {
                Mail::to($user->email)->send(
                    new AccountCredentialsMail($user, $accountNumber, $plainPassword)
                );
                Log::info('Account credentials email sent to KK user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'account_number' => $accountNumber,
                    'password_sent_in_email' => $plainPassword
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send email to KK user: ' . $e->getMessage());
            }
        } else {
            // SK users - log that no password generated yet
            Log::info('SK user created - no password generated (pending admin approval)', [
                'user_id' => $user->id,
                'email' => $user->email,
                'account_number' => $accountNumber
            ]);
        }

        return $user;
    }

    /**
     * Method to generate and send credentials when SK user is approved by admin
     */
    public function sendSKCredentials(User $user)
    {
        // Only process SK users
        if ($user->role !== 'sk') {
            Log::error('Attempted to send SK credentials to non-SK user', ['user_id' => $user->id]);
            return false;
        }

        // Generate new password for SK user upon approval
        $prefix = strtoupper($user->role); // SK
        $accountNumber = $user->account_number;
        $plainPassword = $prefix . rand(1000, 9999);

        Log::info('Generating SK credentials after admin approval:', [
            'user_id' => $user->id,
            'email' => $user->email,
            'account_number' => $accountNumber,
            'new_generated_password' => $plainPassword
        ]);

        try {
            // Update user with new password AND default_password
            $user->update([
                'password' => Hash::make($plainPassword),
                'default_password' => $plainPassword,
                'account_status' => 'approved'
            ]);

            // Also update SkOfficial status
            $skOfficial = SkOfficial::where('user_id', $user->id)->first();
            if ($skOfficial) {
                $skOfficial->update(['status' => 'approved']);
                Log::info('SK official record approved', ['user_id' => $user->id]);
            }

            // Send email with credentials
            Mail::to($user->email)->send(
                new AccountCredentialsMail($user, $accountNumber, $plainPassword)
            );
            
            Log::info('SK credentials email sent after admin approval', [
                'user_id' => $user->id,
                'email' => $user->email,
                'account_number' => $accountNumber,
                'password_sent' => $plainPassword,
                'default_password_in_db' => $user->fresh()->default_password
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SK credentials email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Admin method to approve SK users
     */
    public function approveSkUser(Request $request, User $user)
    {
        // Validate admin permissions
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if user is SK
        if ($user->role !== 'sk') {
            return response()->json(['error' => 'User is not an SK official'], 400);
        }

        // Send credentials
        $result = $this->sendSKCredentials($user);

        if ($result) {
            return response()->json(['success' => 'SK user approved and credentials sent']);
        } else {
            return response()->json(['error' => 'Failed to send credentials'], 500);
        }
    }

    protected function createSkOfficial(User $user, Request $request)
    {
        $oathCertificatePath = $request->file('oath_certificate')->store('documents/sk', 'public');

        SkOfficial::create([
            'user_id' => $user->id,
            'oath_certificate_path' => $oathCertificatePath,
            'status' => 'pending',
        ]);
    }

    protected function createKkOfficial(User $user, Request $request)
    {
        $barangayIndigencyPath = $request->file('barangay_indigency')->store('documents/kk', 'public');

        KKMember::create([
            'user_id' => $user->id,
            'barangay_indigency_path' => $barangayIndigencyPath,
            'status' => 'approved',
        ]);
    }
}