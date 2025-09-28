<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KKMember;
use App\Models\SkOfficial;
use App\Models\User;
use App\Models\Region; // ✅ import Region so we can fetch it
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Mail\AccountCredentialsMail;
use Illuminate\Support\Facades\Mail;
use App\Models\City;
use App\Models\Barangay;


class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        // ✅ Pass regions to the register view
        $regions = Region::all();
        return view('register', compact('regions')); 
    }

    public function register(Request $request)
    {
        // Validate input
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user with default status = pending
        $user = $this->create($request->all());

        // Handle file uploads based on role
        if ($request->role === 'sk') {
            $this->createSkOfficial($user, $request);
        } elseif ($request->role === 'kk') {
            $this->createKkOfficial($user, $request);
        }

        return redirect()->route('registration.success')
            ->with('success', 'Registration submitted. Your account is pending approval.');
    }

    protected function validator(array $data)
    {
        $rules = [
            'last_name' => 'required|string|max:100',
            'given_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            
            // ✅ Replace address with location fields
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
            'password' => 'nullable',
        ];

        // File rules by role
        if (isset($data['role']) && $data['role'] === 'sk') {
            $rules['oath_certificate'] = 'required|file|mimes:pdf|max:5120';
        } elseif (isset($data['role']) && $data['role'] === 'kk') {
            $rules['barangay_indigency'] = 'required|file|mimes:pdf|max:5120';
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
{
    // 1️⃣ Generate account number: ROLE + BIRTHDATE (YYYYMMDD)
    $prefix = strtoupper($data['role']); // SK or KK
    $birthdate = date('Ymd', strtotime($data['date_of_birth']));
    $accountNumber = $prefix . $birthdate;

    // 2️⃣ Generate password: ROLE + 4 random digits
    $plainPassword = $prefix . rand(1000, 9999);

    // 3️⃣ Create user in DB
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
        'account_status' => $data['role'] === 'kk' ? 'approved' : 'pending',
        'password' => Hash::make($plainPassword),
    ]);

    // 4️⃣ Send email only for KK (auto-approved)
    if ($data['role'] === 'kk') {
        Mail::to($user->email)->send(
            new AccountCredentialsMail($user, $accountNumber, $plainPassword)
        );
    }

    return $user;
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
