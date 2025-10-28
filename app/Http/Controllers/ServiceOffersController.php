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

        // Get services and org chart for user's barangay
        $services = Service::forBarangay($user->barangay_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $organizationalChart = OrganizationalChart::forBarangay($user->barangay_id)
            ->orderBy('created_at', 'desc')
            ->first();

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
            'barangayName'
        ));
    }

    public function serviceoffers(): View
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Get barangay name
        $barangay = Barangay::find($user->barangay_id);
        $barangayName = $barangay ? $barangay->name : 'Your Barangay';

        // Get active services and org chart for user's barangay
        $services = Service::forBarangay($user->barangay_id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $organizationalChart = OrganizationalChart::forBarangay($user->barangay_id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();

        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'Youth-Member';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        return view('serviceoffers', compact(
            'user', 
            'roleBadge', 
            'age',
            'services',
            'organizationalChart',
            'barangayName'
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

        // Handle image upload
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

    public function storeOrganizationalChart(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'chart_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Deactivate previous charts
        OrganizationalChart::where('barangay_id', $user->barangay_id)
            ->update(['is_active' => false]);

        // Handle image upload
        $imagePath = $request->file('chart_image')->store('organizational-charts', 'public');

        $organizationalChart = OrganizationalChart::create([
            'barangay_id' => $user->barangay_id,
            'user_id' => $user->id,
            'image_path' => $imagePath,
            'original_name' => $request->file('chart_image')->getClientOriginalName(),
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Organizational chart uploaded successfully!',
            'chart' => $organizationalChart
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
}