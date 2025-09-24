<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;
use App\Models\Purok;

class LocationController extends Controller
{
    public function getProvinces($region_id)
    {
        return response()->json(Province::where('region_id', $region_id)->get());
    }

    public function getCities($province_id)
    {
        return response()->json(City::where('province_id', $province_id)->get());
    }

    public function getBarangays($city_id)
    {
        return response()->json(Barangay::where('city_id', $city_id)->get());
    }
    public function getPuroks($barangayId)
{
    $puroks = Purok::where('barangay_id', $barangayId)->get();
    return response()->json($puroks);
}

}

