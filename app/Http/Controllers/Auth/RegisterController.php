<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KKMember;
use App\Models\SkOfficial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register'); // Points to resources/views/register.blade.php
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

        // Create user
        $user = $this->create($request->all());

        // Handle file uploads based on role
        if ($request->role === 'sk') {
            $this->createSkOfficial($user, $request);
        } elseif ($request->role === 'kk') {
            $this->createKkOfficial($user, $request);
        }

        return redirect()->route('registration.success');
    }

    protected function validator(array $data)
    {
        $rules = [
            'last_name' => 'required|string|max:100',
            'given_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'address' => 'required|string',
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

        // Add file validation based on role
        if ($data['role'] === 'sk') {
            $rules['oath_certificate'] = 'required|file|mimes:pdf|max:5120';
        } elseif ($data['role'] === 'kk') {
            $rules['barangay_indigency'] = 'required|file|mimes:pdf|max:5120';
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        return User::create([
            'role' => $data['role'],
            'last_name' => $data['last_name'],
            'given_name' => $data['given_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'suffix' => $data['suffix'] ?? null,
            'address' => $data['address'],
            'date_of_birth' => $data['date_of_birth'],
            'sex' => $data['sex'],
            'email' => $data['email'],
            'contact_no' => $data['contact_no'],
            'civil_status' => $data['civil_status'],
            'education' => $data['education'],
            'work_status' => $data['work_status'],
            'youth_classification' => $data['youth_classification'],
            'sk_voter' => $data['sk_voter'],
            'password' => Hash::make('defaultPassword123'), // default since admin assigns later
        ]);
    }

    protected function createSkOfficial(User $user, Request $request)
    {
        $oathCertificatePath = $request->file('oath_certificate')->store('documents/sk');

        SkOfficial::create([
            'user_id' => $user->id,
            'oath_certificate_path' => $oathCertificatePath, // fixed column name
        ]);
    }

    protected function createKkOfficial(User $user, Request $request)
    {
        $barangayIndigencyPath = $request->file('barangay_indigency')->store('documents/kk');

        KKMember::create([
            'user_id' => $user->id,
            'barangay_indigency_path' => $barangayIndigencyPath, // fixed column name
        ]);
    }
}
