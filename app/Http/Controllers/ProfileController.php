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
            // If no user is logged in, redirect to login or show guest view
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
        
        // Get location names manually (safer approach)
        $regionName = $user->region_id ? $this->getRegionName($user->region_id) : null;
        $provinceName = $user->province_id ? $this->getProvinceName($user->province_id) : null;
        $cityName = $user->city_id ? $this->getCityName($user->city_id) : null;
        $barangayName = $user->barangay_id ? $this->getBarangayName($user->barangay_id) : null;
        
        return view('profilepage', compact(
            'user', 
            'age', 
            'roleBadge',
            'regionName',
            'provinceName', 
            'cityName',
            'barangayName'
        ));
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