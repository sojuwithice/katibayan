<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Barangay;
use App\Models\SkContactDetail; // <-- Pinalitan na

class SKController extends Controller
{
    public function showServiceOfferPage(Request $request)
    {
        $user = Auth::user();
        $barangay = Barangay::find($user->barangay_id); 
        
        if (!$barangay) {
            abort(404, 'Barangay not found for this user.');
        }

        // --- BINAGO DITO ---
        // Kunin yung settings gamit yung bagong relationship
        $contacts = $barangay->skContactDetail; 
        // --- END ---
        
        // ... Iba mong data ...

        return view('sk-services-offer', [
            'user' => $user,
            'barangayName' => $barangay->name,
            // ... iba pang data
            
            // --- BINAGO DITO ---
            'assistance_description' => $contacts->assistance_description,
            'assistance_fb_link' => $contacts->assistance_fb_link,
            'assistance_msgr_link' => $contacts->assistance_msgr_link,
        ]);
    }

    public function updateAssistanceInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assistance_description' => 'nullable|string|max:1000',
            'assistance_fb_link' => 'nullable|url:http,https|max:500',
            'assistance_msgr_link' => 'nullable|url:http,https|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::user();
            $barangay = Barangay::find($user->barangay_id);

            if (!$barangay) {
                return response()->json(['success' => false, 'message' => 'Barangay not found.'], 404);
            }

            // --- BINAGO DITO ---
            // Gamitin yung bagong model
            SkContactDetail::updateOrCreate(
                ['barangay_id' => $barangay->id], // Condition
                [ 
                    'assistance_description' => $request->assistance_description,
                    'assistance_fb_link' => $request->assistance_fb_link,
                    'assistance_msgr_link' => $request->assistance_msgr_link,
                ] // Data
            );
            // --- END ---

            return response()->json([
                'success' => true, 
                'message' => 'Assistance info updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveAssistanceInfo(Request $request)
{
    $request->validate([
        'assistance_description' => 'nullable|string',
        'assistance_fb_link' => 'nullable|url',
        'assistance_msgr_link' => 'nullable|url',
    ]);

    $user = Auth::user();
    $user->assistance_description = $request->assistance_description;
    $user->assistance_fb_link = $request->assistance_fb_link;
    $user->assistance_msgr_link = $request->assistance_msgr_link;
    $user->save();

    return response()->json(['success' => true]);
}

public function showSKCommittee()
{
    $user = Auth::user();
    $barangayId = $user->barangay_id;

    // SK CHAIRPERSON
    $chair = User::where('barangay_id', $barangayId)
        ->where('role', 'sk-chairperson')
        ->first();

    // SK OFFICERS (Treasurer, Secretary)
    $officers = User::where('barangay_id', $barangayId)
        ->whereIn('role', ['sk-treasurer', 'sk-secretary'])
        ->get();

    // SK KAGAWAD + Committees
    $kagawad = User::where('barangay_id', $barangayId)
        ->where('role', 'sk-kagawad')
        ->with('committees') // OPTIONAL kung may committees table
        ->get();

    return view('sk-committee', [
        'chair' => $chair,
        'officers' => $officers,
        'kagawad' => $kagawad
    ]);
}

}