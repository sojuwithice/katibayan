<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Event;
use App\Models\Notification;

class SKAnalyticsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Calculate role badge and age for SK user
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'SK-Member';
        $age = $user->date_of_birth 
            ? Carbon::parse($user->date_of_birth)->age 
            : 'N/A';

        // Get youth demographics data - FILTERED BY SAME BARANGAY
        $demographicsData = $this->getYouthDemographics();
        
        // Get youth population data - FILTERED BY SAME BARANGAY
        $populationData = $this->getYouthPopulation();
        
        // Get youth age group data - FILTERED BY SAME BARANGAY
        $ageGroupData = $this->getYouthAgeGroups();
        
        // ✅ FIX: Define notifications before passing to view
        $notifications = Notification::where('recipient_role', 'sk')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('sk-analytics', compact(
            'user', 
            'roleBadge', 
            'age', 
            'demographicsData', 
            'populationData', 
            'ageGroupData',
            'notifications'
        ));
    }

    private function getYouthDemographics()
    {
        $skUser = Auth::user();
        
        // Get all youth users from the SAME BARANGAY as the SK user
        $youthUsers = User::where('role', 'kk')
                          ->where('account_status', 'approved')
                          ->where('barangay_id', $skUser->barangay_id)
                          ->get();

        $classifications = [
            'In-School Youth',
            'Out-of-School Youth', 
            'Working Youth',
            'Person with disabilities',
            'Indigenous'
        ];

        $demographics = [
            'male' => [],
            'female' => [],
            'labels' => $classifications,
            'total_count' => $youthUsers->count(),
            'barangay_filter' => $skUser->barangay_id
        ];

        // Initialize counts
        foreach ($classifications as $classification) {
            $demographics['male'][$classification] = 0;
            $demographics['female'][$classification] = 0;
        }

        // Count users by classification and sex
        foreach ($youthUsers as $user) {
            $classification = $user->youth_classification;
            $sex = $user->sex;

            if (in_array($classification, $classifications)) {
                if ($sex === 'male') {
                    $demographics['male'][$classification]++;
                } elseif ($sex === 'female') {
                    $demographics['female'][$classification]++;
                }
            }
        }

        // Convert to arrays for the chart
        $demographics['male_data'] = array_values($demographics['male']);
        $demographics['female_data'] = array_values($demographics['female']);

        return $demographics;
    }

    private function getYouthPopulation()
    {
        $skUser = Auth::user();
        
        // Get all youth users from the SAME BARANGAY as the SK user
        $youthUsers = User::where('role', 'kk')
                          ->where('account_status', 'approved')
                          ->where('barangay_id', $skUser->barangay_id)
                          ->get();

        // Count by sex
        $maleCount = $youthUsers->where('sex', 'male')->count();
        $femaleCount = $youthUsers->where('sex', 'female')->count();
        $totalCount = $youthUsers->count();

        return [
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'total_count' => $totalCount,
            'barangay_filter' => $skUser->barangay_id
        ];
    }

    private function getYouthAgeGroups()
    {
        $skUser = Auth::user();
        
        // Get all youth users from the SAME BARANGAY as the SK user
        $youthUsers = User::where('role', 'kk')
                          ->where('account_status', 'approved')
                          ->where('barangay_id', $skUser->barangay_id)
                          ->get();

        // Count by age groups
        $ageGroups = [
            'child' => 0, // 15-17
            'core' => 0,  // 18-24
            'adult' => 0  // 25-30
        ];

        foreach ($youthUsers as $user) {
            if ($user->date_of_birth) {
                $age = Carbon::parse($user->date_of_birth)->age;
                if ($age >= 15 && $age <= 17) {
                    $ageGroups['child']++;
                } elseif ($age >= 18 && $age <= 24) {
                    $ageGroups['core']++;
                } elseif ($age >= 25 && $age <= 30) {
                    $ageGroups['adult']++;
                }
            }
        }

        return [
            'child_count' => $ageGroups['child'],
            'core_count' => $ageGroups['core'],
            'adult_count' => $ageGroups['adult'],
            'total_count' => $youthUsers->count(),
            'barangay_filter' => $skUser->barangay_id
        ];
    }
}