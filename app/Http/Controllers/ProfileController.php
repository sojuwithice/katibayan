<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('loginpage');
        }
        
        // Calculate age from date_of_birth
        $age = 'N/A';
        if ($user->date_of_birth) {
            try {
                $age = Carbon::parse($user->date_of_birth)->age;
            } catch (\Exception $e) {
                $age = 'N/A';
            }
        }
        
        // Format role badge
        $roleBadge = strtoupper($user->role) . '-Member';
        
        return view('profilepage', compact('user', 'age', 'roleBadge'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validate the input - make fields sometimes required (only when present)
        $validatedData = $request->validate([
            'last_name' => 'sometimes|required|string|max:255',
            'given_name' => 'sometimes|required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => 'sometimes|required|date',
            'sex' => 'sometimes|required|in:male,female',
            'contact_no' => 'sometimes|required|string|max:20',
            'civil_status' => 'sometimes|required|string',
            'education' => 'sometimes|required|string',
            'work_status' => 'sometimes|required|string',
            'youth_classification' => 'sometimes|required|string',
            'sk_voter' => 'sometimes|required|in:Yes,No',
            'purok_zone' => 'sometimes|required|string|max:100',
            'zip_code' => 'sometimes|required|string|max:10',
        ]);

        try {
            // Update only the fields that are present in the request
            $user->fill($validatedData);
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Helper methods to get location names safely
     */
    private function getRegionName($regionId)
    {
        try {
            $region = \App\Models\Region::find($regionId);
            return $region ? $region->name : null;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function getProvinceName($provinceId)
    {
        try {
            $province = \App\Models\Province::find($provinceId);
            return $province ? $province->name : null;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function getCityName($cityId)
    {
        try {
            $city = \App\Models\City::find($cityId);
            return $city ? $city->name : null;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function getBarangayName($barangayId)
    {
        try {
            $barangay = \App\Models\Barangay::find($barangayId);
            return $barangay ? $barangay->name : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}