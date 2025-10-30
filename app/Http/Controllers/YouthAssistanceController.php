<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class YouthAssistanceController extends Controller
{
    public function index()
    {
        try {
            // Get the logged-in user's barangay_id
            $currentUserBarangayId = Auth::user()->barangay_id;
            
            if (!$currentUserBarangayId) {
                Log::error('User does not have barangay_id assigned');
                return back()->with('error', 'Your account is not assigned to a barangay.');
            }

            // Get users from the same barangay who need assistance
            $youthInNeed = User::where('barangay_id', $currentUserBarangayId)
                ->where('account_status', 'approved')
                ->where(function($query) {
                    $query->where('work_status', 'Unemployed')
                          ->orWhere('youth_classification', 'Out-of-School Youth')
                          ->orWhere('youth_classification', 'Working Youth')
                          ->orWhereIn('education', ['Elementary Level', 'Elementary Graduate'])
                          ->orWhere('civil_status', 'Single Parent')
                          ->orWhere('youth_classification', 'like', '%PWD%');
                })
                ->orderBy('last_name')
                ->orderBy('given_name')
                ->get();

            // Count by category - only users from same barangay
            $pwdCount = User::where('barangay_id', $currentUserBarangayId)
                ->where('account_status', 'approved')
                ->where('youth_classification', 'like', '%PWD%')
                ->count();

            $oosyCount = User::where('barangay_id', $currentUserBarangayId)
                ->where('account_status', 'approved')
                ->where('youth_classification', 'Out-of-School Youth')
                ->count();

            $unemployedCount = User::where('barangay_id', $currentUserBarangayId)
                ->where('account_status', 'approved')
                ->where('work_status', 'Unemployed')
                ->count();

            $singleParentCount = User::where('barangay_id', $currentUserBarangayId)
                ->where('account_status', 'approved')
                ->where('civil_status', 'Single Parent')
                ->count();

            // Debug logging
            Log::info("Youth Assistance - Barangay ID: {$currentUserBarangayId}, Total in need: {$youthInNeed->count()}");
            Log::info("Counts - PWD: {$pwdCount}, OOSY: {$oosyCount}, Unemployed: {$unemployedCount}, Single Parent: {$singleParentCount}");

            return view('youth-assistance', compact(
                'youthInNeed',
                'pwdCount',
                'oosyCount',
                'unemployedCount',
                'singleParentCount'
            ));
        } catch (\Exception $e) {
            Log::error('Youth Assistance Index Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load youth assistance data.');
        }
    }

    public function filter(Request $request)
    {
        try {
            $currentUserBarangayId = Auth::user()->barangay_id;
            $category = $request->category;
            $search = $request->search;

            if (!$currentUserBarangayId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not assigned to a barangay.'
                ], 400);
            }

            $query = User::where('barangay_id', $currentUserBarangayId)
                ->where('account_status', 'approved');

            // Apply category filter
            if ($category && $category !== 'all') {
                switch ($category) {
                    case 'pwd':
                        $query->where('youth_classification', 'like', '%PWD%');
                        break;
                    case 'oosy':
                        $query->where('youth_classification', 'Out-of-School Youth');
                        break;
                    case 'unemployed':
                        $query->where('work_status', 'Unemployed');
                        break;
                    case 'single-parent':
                        $query->where('civil_status', 'Single Parent');
                        break;
                    case 'low-education':
                        $query->whereIn('education', ['Elementary Level', 'Elementary Graduate']);
                        break;
                    case 'working-youth':
                        $query->where('youth_classification', 'Working Youth');
                        break;
                }
            } else {
                // Show all assistance-needed users
                $query->where(function($q) {
                    $q->where('work_status', 'Unemployed')
                      ->orWhere('youth_classification', 'Out-of-School Youth')
                      ->orWhere('youth_classification', 'Working Youth')
                      ->orWhereIn('education', ['Elementary Level', 'Elementary Graduate'])
                      ->orWhere('civil_status', 'Single Parent')
                      ->orWhere('youth_classification', 'like', '%PWD%');
                });
            }

            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('last_name', 'like', "%{$search}%")
                      ->orWhere('given_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%")
                      ->orWhere('purok_zone', 'like', "%{$search}%");
                });
            }

            $youthInNeed = $query->orderBy('last_name')->orderBy('given_name')->get();

            Log::info("Filter results - Barangay: {$currentUserBarangayId}, Category: {$category}, Count: {$youthInNeed->count()}");

            return response()->json([
                'success' => true,
                'youth' => $youthInNeed,
                'total' => $youthInNeed->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Youth Assistance Filter Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to filter youth data.'
            ], 500);
        }
    }
}