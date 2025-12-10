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

        if (isset($data['role']) && $data['role'] === 'sk') {
            $rules['oath_certificate'] = 'required|file|mimes:pdf,png,jpg,jpeg|max:5120';
        } elseif (isset($data['role']) && $data['role'] === 'kk') {
            $rules['barangay_indigency'] = 'required|file|mimes:pdf,png,jpg,jpeg|max:5120';
        }

        return Validator::make($data, $rules);
    }

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

        if ($request->hasFile('oath_certificate')) {
            $data['oath_certificate_temp'] = $request->file('oath_certificate')->store('temp', 'public');
            Log::info('Oath certificate stored temporarily', ['path' => $data['oath_certificate_temp']]);
        }
        if ($request->hasFile('barangay_indigency')) {
            $data['barangay_indigency_temp'] = $request->file('barangay_indigency')->store('temp', 'public');
            Log::info('Barangay indigency stored temporarily', ['path' => $data['barangay_indigency_temp']]);
        }

        $request->session()->put('pending_registration', $data);
        Log::info('Data stored in session, redirecting to captcha');

        return redirect()->route('register.captcha');
    }

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
        Log::info('Processing registration data from session', ['role' => $data['role'] ?? 'none']);

        try {
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
            Log::info('User created successfully', [
                'user_id' => $user->id, 
                'email' => $user->email,
                'role' => $user->role,
                'account_status' => $user->account_status
            ]);

            if (isset($data['role']) && $data['role'] === 'sk') {
                SkOfficial::create([
                    'user_id' => $user->id,
                    'oath_certificate_path' => $data['oath_certificate_path'] ?? null,
                    'status' => 'pending',
                ]);
                Log::info('SK official record created with pending status');
                
                // Store role in session for success page
                session(['registration_role' => 'sk']);
                
            } elseif (isset($data['role']) && $data['role'] === 'kk') {
                KKMember::create([
                    'user_id' => $user->id,
                    'barangay_indigency_path' => $data['barangay_indigency_path'] ?? null,
                    'status' => 'approved',
                ]);
                Log::info('KK member record created with approved status');
                
                // Store role in session for success page - THIS IS THE FIX
                session(['registration_role' => 'kk']);
            }

            session()->forget('pending_registration');
            Log::info('Session cleaned up, redirecting to success', ['role_in_session' => session('registration_role')]);

            // Store email in session for success page
            session(['registration_email' => $data['email'] ?? '']);
            
            return redirect()->route('registration.success');

        } catch (\Exception $e) {
            Log::error('Error during user creation: ' . $e->getMessage());
            session()->forget('pending_registration');
            return redirect()->route('register')->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Show registration success page
     */
    public function showSuccessPage()
    {
        // Get role from session
        $role = session('registration_role');
        $email = session('registration_email');
        
        Log::info('Showing success page', ['role' => $role, 'email' => $email]);
        
        // Clear session data after displaying
        session()->forget(['registration_role', 'registration_email']);
        
        return view('registration-success', compact('role', 'email'));
    }

    /**
     * CREATE USER - Default password will be AUTO-ENCRYPTED by User model setter
     */
    protected function create(array $data)
    {
        $prefix = strtoupper($data['role']);
        $birthdate = date('Ymd', strtotime($data['date_of_birth']));
        
        $fullName = $data['given_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name'];
        $names = explode(' ', $fullName);
        $initials = '';
        foreach ($names as $n) {
            if (!empty($n)) {
                $initials .= strtoupper(substr($n, 0, 1));
            }
        }

        $accountNumber = $prefix . $birthdate . $initials;

        if ($data['role'] === 'kk') {
            // Generate plain password for KK
            $plainPassword = $prefix . rand(1000, 9999);
            $passwordHash = Hash::make($plainPassword);
            $accountStatus = 'approved';
        } else {
            // SK gets no password until approved
            $plainPassword = null;
            $passwordHash = Hash::make(Str::random(32));
            $accountStatus = 'pending';
        }

        Log::info('Creating user with auto-encrypted default password:', [
            'role' => $data['role'],
            'account_number' => $accountNumber,
            'email' => $data['email'],
            'plain_password_for_email' => $plainPassword
        ]);

        // Create user - default_password will be AUTO-ENCRYPTED by User model
        $user = User::create([
            'role' => $data['role'],
            'account_number' => $accountNumber,
            'last_name' => $data['last_name'],
            'given_name' => $data['given_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'suffix' => $data['suffix'] ?? null,
            'region_id' => $data['region_id'],
            'province_id' => $data['province_id'],
            'city_id' => $data['city_id'],
            'barangay_id' => $data['barangay_id'],
            'purok_zone' => $data['purok_zone'],
            'zip_code' => $data['zip_code'],
            'date_of_birth' => $data['date_of_birth'],
            'sex' => $data['sex'],
            'email' => $data['email'],
            'contact_no' => $data['contact_no'],
            'civil_status' => $data['civil_status'],
            'education' => $data['education'],
            'work_status' => $data['work_status'],
            'youth_classification' => $data['youth_classification'],
            'sk_voter' => $data['sk_voter'],
            'account_status' => $accountStatus,
            'password' => $passwordHash,
            'default_password' => $plainPassword, // Will be auto-encrypted by User model
        ]);

        Log::info('User created. Default password in database is encrypted:', [
            'user_id' => $user->id,
            'default_password_stored' => substr($user->getRawOriginal('default_password') ?? '', 0, 50) . '...',
            'is_encrypted' => strlen($user->getRawOriginal('default_password') ?? '') > 50
        ]);

        // Send email with plain password (email is encrypted in transit)
        if ($data['role'] === 'kk') {
            try {
                Mail::to($user->email)->send(
                    new AccountCredentialsMail($user, $accountNumber, $plainPassword)
                );
                Log::info('Email sent with plain password to KK user', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
            }
        }

        return $user;
    }

    /**
     * Method to generate and send credentials when SK user is approved by admin
     */
    public function sendSKCredentials(User $user)
    {
        if ($user->role !== 'sk') {
            Log::error('Attempted to send SK credentials to non-SK user', ['user_id' => $user->id]);
            return false;
        }

        $prefix = strtoupper($user->role);
        $accountNumber = $user->account_number;
        $plainPassword = $prefix . rand(1000, 9999);

        Log::info('Generating SK credentials after admin approval:', [
            'user_id' => $user->id,
            'email' => $user->email,
            'account_number' => $accountNumber,
            'new_password' => $plainPassword
        ]);

        try {
            // Update user - default_password will be AUTO-ENCRYPTED by User model
            $user->update([
                'password' => Hash::make($plainPassword),
                'default_password' => $plainPassword, // Will be auto-encrypted
                'account_status' => 'approved'
            ]);

            $skOfficial = SkOfficial::where('user_id', $user->id)->first();
            if ($skOfficial) {
                $skOfficial->update(['status' => 'approved']);
                Log::info('SK official record approved', ['user_id' => $user->id]);
            }

            Mail::to($user->email)->send(
                new AccountCredentialsMail($user, $accountNumber, $plainPassword)
            );
            
            Log::info('SK credentials email sent after admin approval', [
                'user_id' => $user->id,
                'email' => $user->email,
                'password_sent' => $plainPassword
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SK credentials email: ' . $e->getMessage());
            return false;
        }
    }

    public function approveSkUser(Request $request, User $user)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->role !== 'sk') {
            return response()->json(['error' => 'User is not an SK official'], 400);
        }

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