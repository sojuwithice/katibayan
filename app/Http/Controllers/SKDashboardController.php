<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Event;
use App\Models\Notification;
use App\Models\CertificateRequest;

class SKDashboardController extends Controller
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
        
        // Get events for reminders - FILTERED BY SAME BARANGAY
        $remindersData = $this->getEventsForReminders();

        // ✅ NEW: Get monthly events data
        $monthlyEventsData = $this->getMonthlyEventsData();

        // ✅ FIX: Define notifications before passing to view
        $notifications = Notification::where('recipient_role', 'sk')
            ->where('user_id', $user->id) // only notifications meant for this SK user
            ->orderBy('created_at', 'desc')
            ->get();

        // ==========================================================
        // ✅ ADDED: SK COMMITTEE FETCHING LOGIC
        // ==========================================================

        // 1. Fetch ALL users with role='sk' in the same barangay
        $allSkOfficials = User::where('barangay_id', $user->barangay_id)
                              ->where('role', 'sk') 
                              ->get();

        // 2. Identify CHAIRPERSON (Check sk_role for "chair" keyword)
        $skChairperson = $allSkOfficials->first(function ($official) {
            return stripos($official->sk_role, 'chair') !== false;
        });

        // 3. Identify MEMBERS (Everyone else excluding Chair)
        $skMembers = $allSkOfficials->filter(function ($official) use ($skChairperson) {
             return $official->id !== ($skChairperson->id ?? null);
        })->sortBy(function($member) {
            // Sort Priority: Secretary -> Treasurer -> Kagawad
            $role = strtolower($member->sk_role ?? '');
            if (str_contains($role, 'sec')) return 1;
            if (str_contains($role, 'treas')) return 2;
            return 3;
        });

        return view('sk-dashboard', compact(
            'user', 
            'roleBadge', 
            'age', 
            'demographicsData', 
            'populationData', 
            'ageGroupData',
            'remindersData',
            'monthlyEventsData',
            'notifications',
            // ✅ Pass the new variables to the view
            'skChairperson',
            'skMembers'
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

    private function getEventsForReminders()
    {
        $skUser = Auth::user();
        $today = Carbon::today();
        
        // Get events from the SAME BARANGAY as the SK user
        $events = Event::where('barangay_id', $skUser->barangay_id)
                      ->where('event_date', '>=', $today)
                      ->where('is_launched', true)
                      ->orderBy('event_date', 'asc')
                      ->orderBy('event_time', 'asc')
                      ->get();

        $todayEvents = [];
        $upcomingEvents = [];

        foreach ($events as $event) {
            $eventDate = Carbon::parse($event->event_date);
            
            // Format the date for display
            $formattedDate = $eventDate->format('m/d/Y');
            
            // Format time for display
            $formattedTime = $event->event_time;
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $event->event_time)) {
                $formattedTime = Carbon::createFromFormat('H:i:s', $event->event_time)->format('h:i A');
            } elseif (preg_match('/^\d{2}:\d{2}$/', $event->event_time)) {
                $formattedTime = Carbon::createFromFormat('H:i', $event->event_time)->format('h:i A');
            }
            
            $eventData = [
                'id' => $event->id,
                'title' => $event->title,
                'date' => $formattedDate,
                'time' => $formattedTime,
                'location' => $event->location,
                'category' => $event->category,
                'full_date_time' => $eventDate->format('F j, Y') . ' | ' . $formattedTime,
            ];

            if ($eventDate->isToday()) {
                $todayEvents[] = $eventData;
            } else {
                $upcomingEvents[] = $eventData;
            }
        }

        return [
            'today' => $todayEvents,
            'upcoming' => $upcomingEvents,
            'barangay_filter' => $skUser->barangay_id
        ];
    }

    /**
     * Get monthly events data for the current year
     */
    private function getMonthlyEventsData()
    {
        $skUser = Auth::user();
        
        // Get events count by month for the current year, filtered by barangay
        $monthlyEvents = Event::where('barangay_id', $skUser->barangay_id)
            ->whereYear('event_date', date('Y'))
            ->selectRaw('MONTH(event_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Initialize all months with 0 events
        $allMonths = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];
        
        $eventsData = array_fill(0, 12, 0);
        
        // Fill in actual event counts
        foreach ($monthlyEvents as $month => $data) {
            $eventsData[$month - 1] = $data->count; // month is 1-12, array index is 0-11
        }

        return [
            'labels' => $allMonths,
            'events' => $eventsData,
            'barangay_filter' => $skUser->barangay_id,
            'year' => date('Y')
        ];
    }

    public function showCertificateRequests()
    {
        $events = Event::withCount('certificateRequests')->get();
        return view('sk.certificate-request', compact('events'));
    }

    
}