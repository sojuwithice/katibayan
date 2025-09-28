<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        
        // Get the default password from the database
        $defaultPassword = $user->default_password ?? 'Password not set';
        
        return view('profilepage', compact('user', 'age', 'roleBadge', 'defaultPassword'));
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

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        // Check if new password is different from current password
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'New password must be different from current password.'
            ], 422);
        }

        try {
            // ✅ Update the password using save()
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error changing password: ' . $e->getMessage()
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
      public function updateAvatar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = Auth::user();

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Process and store the new avatar
             $avatarFile = $request->file('avatar');
            
            // Generate unique filename
            $filename = 'avatar_' . Auth::id() . '_' . time() . '.' . $avatarFile->getClientOriginalExtension();
            $avatarPath = $avatarFile->storeAs('avatars', $filename, 'public');

            // Update user record
            $user->avatar = $avatarPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'avatar_url' => asset('storage/' . $avatarPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove user avatar (reset to default)
     */
    public function removeAvatar(Request $request)
    {
        try {
            $user = Auth::user();

            // Delete current avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Set avatar to null in database
            $user->avatar = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully!',
                'avatar_url' => asset('images/default-avatar.png')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process and store avatar image with optimization
     */
    private function processAndStoreAvatar($avatarFile)
    {
        // Create avatars directory if it doesn't exist
        $avatarDirectory = 'avatars';
        if (!Storage::disk('public')->exists($avatarDirectory)) {
            Storage::disk('public')->makeDirectory($avatarDirectory);
        }

        // Generate unique filename
        $filename = 'avatar_' . Auth::id() . '_' . time() . '.jpg';

        // Full path
        $fullPath = $avatarDirectory . '/' . $filename;

        // Optimize and resize image
        $image = Image::make($avatarFile);

        // Resize to optimal dimensions (square)
        $image->fit(300, 300, function ($constraint) {
            $constraint->upsize();
        });

        // Convert to JPEG and optimize quality
        $image->encode('jpg', 85);

        // Store the image
        Storage::disk('public')->put($fullPath, $image->stream());

        return $fullPath;
    }

    /**
     * Get user profile data (if needed for AJAX)
     */
    public function getProfileData()
    {
        $user = Auth::user();
        
        return response()->json([
            'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png'),
            'user' => $user
        ]);
    }

}
