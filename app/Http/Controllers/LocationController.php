<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;

class LocationController extends Controller
{
    public function getProvinces($region_id)
    {
        try {
            $provinces = Province::where('region_id', $region_id)->get();
            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        }
    }

    public function getCities($province_id)
    {
        try {
            $cities = City::where('province_id', $province_id)->get();
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch cities'], 500);
        }
    }

    public function getBarangays($city_id)
    {
        try {
            $barangays = Barangay::where('city_id', $city_id)->get();
            return response()->json($barangays);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch barangays'], 500);
        }
    }

    
}