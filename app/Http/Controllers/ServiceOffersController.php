<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\View\View;
use App\Models\Service;
use App\Models\OrganizationalChart;
use App\Models\Barangay;
use App\Models\AssistanceSetting;


class ServiceOffersController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Get barangay name
        $barangay = Barangay::find($user->barangay_id);
        $barangayName = $barangay ? $barangay->name : 'Your Barangay';

        // Get services and org chart
        $services = Service::forBarangay($user->barangay_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // NOTE: Ito ay kukuha ng ISANG record, kaya sa Blade, gamitin ang @if($organizationalChart)
        $organizationalChart = OrganizationalChart::forBarangay($user->barangay_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch Assistance Settings for this barangay
        $assist = AssistanceSetting::where('barangay_id', $user->barangay_id)->first();

        $assistance_description = $assist?->description;
        $assistance_fb_link = $assist?->fb_link;
        $assistance_msgr_link = $assist?->msgr_link;


        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'SK-Member';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        return view('sk-services-offer', compact(
            'user', 
            'roleBadge', 
            'age',
            'services',
            'organizationalChart',
            'barangayName',
            'assistance_description',
            'assistance_fb_link',
            'assistance_msgr_link'
        ));
    }


    public function serviceoffers(): View
{
    $user = Auth::user();
    
    if (!$user) {
        abort(403, 'Unauthorized');
    }

    $barangay = Barangay::find($user->barangay_id);
    $barangayName = $barangay ? $barangay->name : 'Your Barangay';

    $services = Service::forBarangay($user->barangay_id)
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();

    // **FIX DITO: Palitan ang variable name sa PLURAL**
    $organizationalCharts = OrganizationalChart::forBarangay($user->barangay_id)
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get(); // Ito ay tama na naka ->get()

    // Assistance settings
    $assist = AssistanceSetting::where('barangay_id', $user->barangay_id)->first();

    $assistance_description = $assist?->description;
    $assistance_fb_link = $assist?->fb_link;
    $assistance_msgr_link = $assist?->msgr_link;


    $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'Youth-Member';
    $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';

    return view('serviceoffers', compact(
        'user', 
        'roleBadge', 
        'age',
        'services',
        'organizationalCharts', 
        'barangayName',
        'assistance_description',
        'assistance_fb_link',
        'assistance_msgr_link'
    ));
}


    public function storeService(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services_offered' => 'nullable|string',
            'location' => 'nullable|string',
            'how_to_avail' => 'nullable|string',
            'contact_info' => 'nullable|string',
        ]);

        // FIX APPLIED: Check if file exists before trying to store it. (Although validation is 'required', 
        // this guards against possible future changes).
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        $service = Service::create([
            'user_id' => $user->id,
            'barangay_id' => $user->barangay_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'services_offered' => $request->services_offered ? json_encode(explode(',', $request->services_offered)) : null,
            'location' => $request->location,
            'how_to_avail' => $request->how_to_avail,
            'contact_info' => $request->contact_info,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service added successfully!',
            'service' => $service
        ]);
    }

    public function updateService(Request $request, $id)
    {
        $user = Auth::user();
        $service = Service::where('id', $id)
            ->where('barangay_id', $user->barangay_id)
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services_offered' => 'nullable|string',
            'location' => 'nullable|string',
            'how_to_avail' => 'nullable|string',
            'contact_info' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $service->image = $request->file('image')->store('services', 'public');
        }

        $service->update([
            'title' => $request->title,
            'description' => $request->description,
            'services_offered' => $request->services_offered ? json_encode(explode(',', $request->services_offered)) : null,
            'location' => $request->location,
            'how_to_avail' => $request->how_to_avail,
            'contact_info' => $request->contact_info,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully!',
            'service' => $service
        ]);
    }

    public function deleteService($id)
    {
        $user = Auth::user();
        $service = Service::where('id', $id)
            ->where('barangay_id', $user->barangay_id)
            ->firstOrFail();

        // Delete image
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully!'
        ]);
    }

    /**
     * Store Organizational Chart image(s) and deactivate old ones.
     */
    public function storeOrganizationalChart(Request $request)
    {
        $user = Auth::user();
        
        // Validation para sa multiple file upload
        $request->validate([
            'chart_images' => 'required|array|min:1', 
            'chart_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Deactivate lahat ng dating charts para sa barangay na ito
        OrganizationalChart::where('barangay_id', $user->barangay_id)
            ->update(['is_active' => false]);
            
        $uploadedCharts = [];

        // FIX APPLIED: Gamitin ang $request->file('chart_images') na nagbabalik ng array
        // at i-check kung ito ay valid na array.
        $files = $request->file('chart_images');
        
        if (is_array($files)) {
            foreach ($files as $file) {
                // Tiyaking valid ang file bago i-store
                if ($file && $file->isValid()) {
                    $imagePath = $file->store('organizational-charts', 'public');

                    $organizationalChart = OrganizationalChart::create([
                        'barangay_id' => $user->barangay_id,
                        'user_id' => $user->id,
                        'image_path' => $imagePath,
                        'original_name' => $file->getClientOriginalName(), 
                        'is_active' => true, // I-set bilang active ang bagong files
                    ]);
                    $uploadedCharts[] = $organizationalChart;
                }
            }
        }
        
        $count = count($uploadedCharts);
        $message = $count > 1 
            ? 'Organizational charts uploaded successfully! (' . $count . ' images stored)' 
            : 'Organizational chart uploaded successfully!';

        return response()->json([
            'success' => true,
            'message' => $message,
            'charts' => $uploadedCharts 
        ]);
    }

    public function getServiceDetails($id)
    {
        $user = Auth::user();
        $service = Service::where('id', $id)
            ->where('barangay_id', $user->barangay_id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'service' => $service
        ]);
    }

    public function toggleServiceStatus($id)
    {
        $user = Auth::user();
        $service = Service::where('id', $id)
            ->where('barangay_id', $user->barangay_id)
            ->firstOrFail();

        $service->update([
            'is_active' => !$service->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service status updated successfully!',
            'service' => $service
        ]);
    }

    public function updateAssistanceInfo(Request $request)
    {
        $user = Auth::user();

        // 1. Validate the incoming request data, matching the form field names
        $request->validate([
            'assistance_description' => 'nullable|string',
            'assistance_fb_link' => 'nullable|url|max:255',
            'assistance_msgr_link' => 'nullable|url|max:255',
        ]);

        // 2. Find or create the AssistanceSetting record and use the request data
        $assistanceSetting = AssistanceSetting::updateOrCreate(
            ['barangay_id' => $user->barangay_id], // Condition to find the record
            [
                'description' => $request->assistance_description,
                'fb_link' => $request->assistance_fb_link,
                'msgr_link' => $request->assistance_msgr_link,
            ] 
        );

        // 3. Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Assistance information updated successfully!',
            'data' => $assistanceSetting
        ]);
    }


    public function updateOrganizationalChart(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Validation (for single or multiple files)
        $request->validate([
            'chart_images' => 'required|array|min:1', 
            'chart_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        
        try {
            // 2. Hanapin ang Luma/Target na chart
            $oldChart = OrganizationalChart::where('id', $id)
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();

            // 3. Tanggalin ang file ng lumang chart sa storage
            if ($oldChart->image_path) {
                Storage::disk('public')->delete($oldChart->image_path);
            }

            // 4. Tanggalin ang record ng lumang chart sa database
            $oldChart->delete();

            $uploadedCharts = [];

            // 5. I-upload ang Bago
            $files = $request->file('chart_images');
            
            if (is_array($files)) {
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $imagePath = $file->store('organizational-charts', 'public');

                        $newChart = OrganizationalChart::create([
                            'barangay_id' => $user->barangay_id,
                            'user_id' => $user->id,
                            'image_path' => $imagePath,
                            'original_name' => $file->getClientOriginalName(), 
                            'is_active' => true,
                        ]);
                        $uploadedCharts[] = $newChart;
                    }
                }
            }

            $count = count($uploadedCharts);
            $message = $count > 1 
                ? 'Organizational charts successfully replaced! (' . $count . ' new images)' 
                : 'Organizational chart successfully replaced!';

            return response()->json([
                'success' => true,
                'message' => $message,
                'charts' => $uploadedCharts 
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Organizational chart to update not found or unauthorized.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during update: ' . $e->getMessage()
            ], 500);
        }
    }
}