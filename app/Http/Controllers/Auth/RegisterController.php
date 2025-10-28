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
use Spatie\PdfToText\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

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

        // File rules for multiple uploads
        if (isset($data['role'])) {
            if ($data['role'] === 'sk') {
                $rules['oath_certificate'] = 'required|array';
                $rules['oath_certificate.*'] = 'file|mimes:pdf,png,jpg,jpeg|max:5120';
            } elseif ($data['role'] === 'kk') {
                $rules['barangay_indigency'] = 'required|array';
                $rules['barangay_indigency.*'] = 'file|mimes:pdf,png,jpg,jpeg|max:5120';
            }
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

            // Confirm checkboxes
            'certify_info' => 'required|accepted',
            'certify_final' => 'required|accepted',
            'confirm_submission' => 'required|accepted',
        ];

        // File rules for multiple uploads
        if (isset($data['role'])) {
            if ($data['role'] === 'sk') {
                $rules['oath_certificate'] = 'required|array';
                $rules['oath_certificate.*'] = 'file|mimes:pdf,png,jpg,jpeg|max:5120';
            } elseif ($data['role'] === 'kk') {
                $rules['barangay_indigency'] = 'required|array';
                $rules['barangay_indigency.*'] = 'file|mimes:pdf,png,jpg,jpeg|max:5120';
            }
        }

        return Validator::make($data, $rules);
    }


    /**
     * Step 1: Preview route — validate, store files temporarily and keep data in session,
     * then redirect to captcha page.
     */
    /**
     * Step 1: Preview route — validate, store files temporarily and keep data in session,
     * then redirect to captcha page.
     */
    public function preview(Request $request)
    {
        Log::info('Preview method called', ['data' => $request->except(['_token', 'oath_certificate', 'barangay_indigency'])]);

        $validator = $this->previewValidator($request->all());
        if ($validator->fails()) {
            Log::error('Validation failed in preview', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors below.');
        }

        $data = $request->except(['_token', 'oath_certificate', 'barangay_indigency', 'certify_info', 'certify_final', 'confirm_submission']);
        $documentText = '';
        
        // Define binary paths
        $pdftotextBinary = '/opt/homebrew/bin/pdftotext';
        // FIXED: Changed this path to match your Homebrew setup
        $tesseractBinary = '/opt/homebrew/bin/tesseract'; 

        // Handle SK multiple files
        if ($request->role === 'sk' && $request->hasFile('oath_certificate')) {
            $data['oath_certificate_temp'] = [];
            foreach ($request->file('oath_certificate') as $file) {
                $path = $file->store('temp', 'public');
                $data['oath_certificate_temp'][] = $path;
                Log::info('Oath certificate stored temporarily', ['path' => $path]);

                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext === 'pdf') {
                    $documentText .= ' ' . (new Pdf($pdftotextBinary))
                                          ->setPdf(storage_path('app/public/' . $path))
                                          ->text();
                } else {
                    $documentText .= ' ' . (new TesseractOCR(storage_path('app/public/' . $path)))
                        ->executable($tesseractBinary) // Use the correct variable
                        ->run();
                }
            }
        }

        // Handle KK multiple files
        if ($request->role === 'kk' && $request->hasFile('barangay_indigency')) {
            $data['barangay_indigency_temp'] = [];
            foreach ($request->file('barangay_indigency') as $file) {
                $path = $file->store('temp', 'public');
                $data['barangay_indigency_temp'][] = $path;
                Log::info('Barangay indigency stored temporarily', ['path' => $path]);

                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext === 'pdf') {
                    $documentText .= ' ' . (new Pdf($pdftotextBinary))
                                          ->setPdf(storage_path('app/public/' . $path))
                                          ->text();
                } else {
                    $documentText .= ' ' . (new TesseractOCR(storage_path('app/public/' . $path)))
                        ->executable($tesseractBinary) // Use the correct variable
                        ->run();
                }
            }
        }

        // Flexible Matching Logic
        $cleanString = function($str) {
            $str = preg_replace('/[^a-zA-Z0-9]/', '', $str);
            return strtoupper($str);
        };

        $normalizedDocText = $cleanString($documentText);

        $givenName = $data['given_name'];
        $lastName = $data['last_name'];
        $barangayName = (Barangay::find($data['barangay_id'])->name ?? '');

        $normGivenName = $cleanString($givenName);
        $normLastName = $cleanString($lastName);
        $normBarangayName = $cleanString($barangayName);

        $givenNameMatches = !empty($normGivenName) && str_contains($normalizedDocText, $normGivenName);
        $lastNameMatches = !empty($normLastName) && str_contains($normalizedDocText, $normLastName);
        $barangayMatches = !empty($normBarangayName) && str_contains($normalizedDocText, $normBarangayName);

        if (empty($normBarangayName)) {
            $barangayMatches = true; 
            Log::warning('Barangay name is empty, skipping barangay match validation.');
        }
        
        if (!$givenNameMatches || !$lastNameMatches || !$barangayMatches) {
            
            Log::warning('Document does not match Step 1 data (Partial Check Failed)', [
                'found_given_name' => $givenNameMatches,
                'found_last_name' => $lastNameMatches,
                'found_barangay' => $barangayMatches,
                'checking_for_given' => $normGivenName,
                'checking_for_last' => $normLastName,
                'checking_for_barangay' => $normBarangayName,
                'doc_text_snippet_normalized' => substr($normalizedDocText, 0, 400) . '...'
            ]);
            
            return redirect()->back()->withErrors([
                'document' => 'Uploaded document does not match your Step 1 information. Please ensure your Given Name, Last Name, and Barangay are visible in the document.'
            ])->withInput();
        }
        
        $request->session()->put('pending_registration', $data);
        Log::info('Data stored in session, document verified, redirecting to captcha');

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
        
        if (app()->environment('local')) {
            Log::info('Local environment detected - bypassing reCAPTCHA verification');
        } else {
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

        $data = session('pending_registration');
        Log::info('Processing registration data from session');

        try {
            // *** START: FIXED FILE HANDLING ***
            
            // FIXED: Handle array of SK files
            if (!empty($data['oath_certificate_temp'])) {
                $finalPaths = [];
                // Loop through the array of temp paths
                foreach ($data['oath_certificate_temp'] as $tempPath) {
                    $finalPath = 'documents/sk/' . basename($tempPath);
                    Storage::disk('public')->move($tempPath, $finalPath);
                    $finalPaths[] = $finalPath;
                }
                // Store all paths as a single comma-separated string
                $data['oath_certificate_path'] = implode(',', $finalPaths);
                unset($data['oath_certificate_temp']);
                Log::info('Moved oath certificates to final location', ['paths' => $data['oath_certificate_path']]);
            }

            // FIXED: Handle array of KK files
            if (!empty($data['barangay_indigency_temp'])) {
                $finalPaths = [];
                // Loop through the array of temp paths
                foreach ($data['barangay_indigency_temp'] as $tempPath) {
                    $finalPath = 'documents/kk/' . basename($tempPath);
                    Storage::disk('public')->move($tempPath, $finalPath);
                    $finalPaths[] = $finalPath;
                }
                // Store all paths as a single comma-separated string
                $data['barangay_indigency_path'] = implode(',', $finalPaths);
                unset($data['barangay_indigency_temp']);
                Log::info('Moved barangay indigency files to final location', ['paths' => $data['barangay_indigency_path']]);
            }

            // *** END: FIXED FILE HANDLING ***

            // Before creating user, check barangay SK Chair approval
            if ($data['role'] === 'kk') {
                $hasApprovedChair = User::where('role', 'sk')
                    ->where('barangay_id', $data['barangay_id'])
                    ->where('account_status', 'approved')
                    ->exists();

                if (!$hasApprovedChair) {
                    Log::warning('KK registration blocked - No approved SK Chair found for barangay', [
                        'barangay_id' => $data['barangay_id'],
                        'email' => $data['email']
                    ]);

                    // FIXED: Delete the files we just moved
                    if (!empty($data['barangay_indigency_path'])) {
                        Storage::disk('public')->delete(explode(',', $data['barangay_indigency_path']));
                        Log::info('Deleted moved files for KK user due to no SK chair');
                    }

                    session()->forget('pending_registration');
                    return redirect()->route('register')->with('error', 'Registration failed. The SK Chair in your barangay has not been approved yet.');
                }
            }

            // Create the user if valid
            $user = $this->create($data);
            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);


            // Create role-specific models
            if (isset($data['role']) && $data['role'] === 'sk') {
                SkOfficial::create([
                    'user_id' => $user->id,
                    'oath_certificate_path' => $data['oath_certificate_path'] ?? null,
                ]);
                Log::info('SK official record created');
            } elseif (isset($data['role']) && $data['role'] === 'kk') {
                KKMember::create([
                    'user_id' => $user->id,
                    'barangay_indigency_path' => $data['barangay_indigency_path'] ?? null,
                ]);
                Log::info('KK member record created');
            }

            session()->forget('pending_registration');
            Log::info('Session cleaned up, redirecting to success');

            return redirect()->route('registration.success')->with('success', 'Registration submitted. Your account is pending approval.');

        } catch (\Exception $e) {
            Log::error('Error during user creation: ' . $e->getMessage());
            
            // FIXED: Attempt to clean up any files that were moved before the error
            if(isset($data['oath_certificate_path'])) {
                 Storage::disk('public')->delete(explode(',', $data['oath_certificate_path']));
            }
            if(isset($data['barangay_indigency_path'])) {
                 Storage::disk('public')->delete(explode(',', $data['barangay_indigency_path']));
            }

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

    // Default password setup
    $plainPassword = null;
    $passwordHash = null;
    $defaultPassword = null;

    // Determine account status logic
    $accountStatus = 'pending';

    if ($data['role'] === 'kk') {
        // Check if there's an approved SK chair in the same barangay
        $hasApprovedChair = User::where('role', 'sk')
            ->where('barangay_id', $data['barangay_id'])
            ->where('account_status', 'approved')
            ->exists();

        if ($hasApprovedChair) {
            // Approve KK automatically and send credentials
            $accountStatus = 'approved';
            $plainPassword = $prefix . rand(1000, 9999);
            $passwordHash = Hash::make($plainPassword);
            $defaultPassword = $plainPassword;
        } else {
            // Pending if SK Chair not yet approved
            $accountStatus = 'pending';
            $passwordHash = Hash::make(Str::random(32)); // placeholder password
        }
    } else {
        // SK always pending until admin approves
        $accountStatus = 'pending';
        $passwordHash = Hash::make(Str::random(32));
    }

    // Log password generation for debugging
    Log::info('PASSWORD DEBUG - Before user creation:', [
        'role' => $data['role'],
        'generated_password' => $plainPassword,
        'account_number' => $accountNumber,
        'email' => $data['email']
    ]);

    // Create the user
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

        // Account
        'account_status' => $accountStatus,
        'password' => $passwordHash,
        'default_password' => $defaultPassword,
    ]);

    // Debug: Verify stored data
    Log::info('PASSWORD DEBUG - After user creation:', [
        'role' => $user->role,
        'stored_default_password' => $user->default_password,
        'account_status' => $user->account_status
    ]);

    // Send email ONLY if KK is auto-approved
    if ($data['role'] === 'kk' && $accountStatus === 'approved') {
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
    } elseif ($data['role'] === 'kk' && $accountStatus === 'pending') {
        Log::info('KK registration pending — SK chair not yet approved', [
            'email' => $user->email,
            'barangay_id' => $user->barangay_id
        ]);
    } else {
        // SK users: no password until admin approves
        Log::info('SK user created - no password generated (pending admin approval)', [
            'user_id' => $user->id,
            'email' => $user->email,
            'account_number' => $accountNumber
        ]);
    }

    return $user;
}


    /**
     * Method to generate and send credentials when SK user is approved
     */
    public function sendSKCredentials(User $user)
    {
        // Generate new password for SK user upon approval
        $prefix = strtoupper($user->role); // SK
        $accountNumber = $user->account_number;
        $plainPassword = $prefix . rand(1000, 9999);

        Log::info('Generating SK credentials after approval:', [
            'user_id' => $user->id,
            'email' => $user->email,
            'account_number' => $accountNumber,
            'new_generated_password' => $plainPassword
        ]);

        try {
            // Update user with new password AND default_password
            $user->update([
                'password' => Hash::make($plainPassword),
                'default_password' => $plainPassword, // This sets the default_password field
                'account_status' => 'approved'
            ]);

            // Send email with credentials
            Mail::to($user->email)->send(
                new AccountCredentialsMail($user, $accountNumber, $plainPassword)
            );
            
            Log::info('SK credentials email sent after approval', [
                'user_id' => $user->id,
                'email' => $user->email,
                'account_number' => $accountNumber,
                'password_sent' => $plainPassword,
                'default_password_in_db' => $user->fresh()->default_password // Verify it's stored
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SK credentials email: ' . $e->getMessage());
            return false;
        }
    }

    protected function createSkOfficial(User $user, Request $request)
    {
        $oathCertificatePath = $request->file('oath_certificate')->store('documents/sk', 'public');

        SkOfficial::create([
            'user_id' => $user->id,
            'oath_certificate_path' => $oathCertificatePath,
        ]);
    }

    protected function createKkOfficial(User $user, Request $request)
    {
        $barangayIndigencyPath = $request->file('barangay_indigency')->store('documents/kk', 'public');

        KKMember::create([
            'user_id' => $user->id,
            'barangay_indigency_path' => $barangayIndigencyPath,
        ]);
    }
}