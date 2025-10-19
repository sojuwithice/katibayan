<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class YouthAssistanceController extends Controller
{
    public function index()
    {
        // Get the logged-in user's barangay_id
        $currentUserBarangayId = Auth::user()->barangay_id;
        
        // Get users from the same barangay who need assistance
        $youthInNeed = User::where('barangay_id', $currentUserBarangayId)
            ->where(function($query) {
                $query->where('work_status', 'Unemployed')
                      ->orWhere('youth_classification', 'Out-of-School Youth')
                      ->orWhere('youth_classification', 'Working Youth')
                      ->orWhere('education', 'Elementary Level')
                      ->orWhere('education', 'Elementary Graduate')
                      ->orWhere('civil_status', 'Single Parent');
            })
            ->where('account_status', 'approved')
            ->get();

        // Count by category
        $pwdCount = User::where('barangay_id', $currentUserBarangayId)
            ->where('youth_classification', 'like', '%PWD%')
            ->where('account_status', 'approved')
            ->count();

        $oosyCount = User::where('barangay_id', $currentUserBarangayId)
            ->where('youth_classification', 'Out-of-School Youth')
            ->where('account_status', 'approved')
            ->count();

        $unemployedCount = User::where('barangay_id', $currentUserBarangayId)
            ->where('work_status', 'Unemployed')
            ->where('account_status', 'approved')
            ->count();

        $singleParentCount = User::where('barangay_id', $currentUserBarangayId)
            ->where('civil_status', 'Single Parent')
            ->where('account_status', 'approved')
            ->count();

        return view('youth-assistance', compact(
            'youthInNeed',
            'pwdCount',
            'oosyCount',
            'unemployedCount',
            'singleParentCount'
        ));
    }

    public function filter(Request $request)
    {
        $currentUserBarangayId = Auth::user()->barangay_id;
        $category = $request->category;
        $search = $request->search;

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
            }
        } else {
            // Show all assistance-needed users
            $query->where(function($q) {
                $q->where('work_status', 'Unemployed')
                  ->orWhere('youth_classification', 'Out-of-School Youth')
                  ->orWhere('youth_classification', 'Working Youth')
                  ->orWhere('education', 'Elementary Level')
                  ->orWhere('education', 'Elementary Graduate')
                  ->orWhere('civil_status', 'Single Parent');
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

        $youthInNeed = $query->get();

        return response()->json([
            'youth' => $youthInNeed,
            'total' => $youthInNeed->count()
        ]);
    }
}