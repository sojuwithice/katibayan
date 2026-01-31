<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CertificateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use DateTime; // Use DateTime instead of Carbon
use App\Models\Event;
use App\Models\ProgramRegistration;

class YouthProfileController extends Controller
{
    public function index()
    {
        // Get authenticated user data for topbar
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate age from date_of_birth using DateTime
        $age = 'N/A';
        if ($user->date_of_birth) {
            $birthDate = new DateTime($user->date_of_birth);
            $today = new DateTime();
            $age = $birthDate->diff($today)->y;
        }

        // Determine role badge based on actual enum values
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        // Get all approved users from the same barangay
        $users = User::with(['region', 'province', 'city', 'barangay'])
                    ->where('account_status', 'approved')
                    ->where('barangay_id', $user->barangay_id)
                    ->orderBy('last_name')
                    ->get();

        // ✅ Count certificate requests by users in the same barangay
        $certificateRequestsCount = CertificateRequest::whereIn('user_id', function ($query) use ($user) {
                $query->select('id')
                      ->from('users')
                      ->where('barangay_id', $user->barangay_id)
                      ->where('account_status', 'approved');
            })
            ->count();

        // Return to the youth-profilepage view with all data
        return view('youth-profilepage', compact('users', 'certificateRequestsCount', 'user', 'age', 'roleBadge'));
    }

    public function show($id)
    {
        // Get current logged-in user for topbar
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return redirect()->route('login');
        }

        // Get the youth user to display
        $youth = User::with(['region', 'province', 'city', 'barangay'])
                    ->where('id', $id)
                    ->where('account_status', 'approved')
                    ->where('barangay_id', $currentUser->barangay_id) // Ensure same barangay
                    ->firstOrFail();

        // Calculate age using DateTime
        $age = 'N/A';
        if ($youth->date_of_birth) {
            $birthDate = new DateTime($youth->date_of_birth);
            $today = new DateTime();
            $age = $birthDate->diff($today)->y;
        }
        
        // Calculate current user age for topbar
        $currentUserAge = 'N/A';
        if ($currentUser->date_of_birth) {
            $userBirthDate = new DateTime($currentUser->date_of_birth);
            $today = new DateTime();
            $currentUserAge = $userBirthDate->diff($today)->y;
        }
        
        $roleBadge = $currentUser->role === 'sk' ? 'SK Member' : 'KK Member';

        // Get youth progress (based on events attended)
        $totalEvents = Event::where('barangay_id', $currentUser->barangay_id)
                          ->where('status', 'completed')
                          ->count();
                          
        $attendedEvents = DB::table('attendances')
                          ->where('user_id', $id)
                          ->where('status', 'present')
                          ->count();
        
        $progressPercentage = $totalEvents > 0 ? round(($attendedEvents / $totalEvents) * 100) : 0;

        // Get evaluated programs count
        $evaluatedPrograms = DB::table('evaluations')
                              ->where('user_id', $id)
                              ->count();

        // Get events and programs attended by this youth
        $attendedEventsList = DB::table('attendances')
                               ->join('events', 'attendances.event_id', '=', 'events.id')
                               ->where('attendances.user_id', $id)
                               ->where('attendances.status', 'present')
                               ->select('events.title', 'attendances.created_at as date')
                               ->orderBy('attendances.created_at', 'desc')
                               ->get()
                               ->map(function($event) {
                                   $date = new DateTime($event->date);
                                   return [
                                       'date' => $date->format('F d, Y'),
                                       'title' => $event->title,
                                       'month_year' => $date->format('F Y')
                                   ];
                               });

        // Get program registrations
        $programRegistrations = ProgramRegistration::where('user_id', $id)
                                                   ->with('program')
                                                   ->get();

        // Group events by month
        $eventsByMonth = [];
        foreach ($attendedEventsList as $event) {
            $monthYear = $event['month_year'];
            if (!isset($eventsByMonth[$monthYear])) {
                $eventsByMonth[$monthYear] = [];
            }
            $eventsByMonth[$monthYear][] = $event;
        }

        // Check if youth is registered voter
        $isRegisteredVoter = $youth->sk_voter === 'yes';

        // Determine youth classification
        $youthClassification = $this->getYouthClassification($youth->education, $youth->work_status);

        // Format date of birth using DateTime
        $formattedDOB = 'N/A';
        if ($youth->date_of_birth) {
            $dob = new DateTime($youth->date_of_birth);
            $formattedDOB = $dob->format('F d, Y');
        }

        // If no events found, add some dummy data for display
        if (empty($eventsByMonth)) {
            $currentDate = new DateTime();
            $lastMonth = clone $currentDate;
            $lastMonth->modify('-1 month');
            
            $eventsByMonth = [
                $currentDate->format('F Y') => [
                    [
                        'date' => $currentDate->format('F 13, Y'),
                        'title' => 'International Day Against Drug Abuse and Illicit Trafficking',
                        'month_year' => $currentDate->format('F Y')
                    ],
                    [
                        'date' => $currentDate->format('F 09, Y'),
                        'title' => 'Leadership Training and Orientation',
                        'month_year' => $currentDate->format('F Y')
                    ]
                ],
                $lastMonth->format('F Y') => [
                    [
                        'date' => $lastMonth->format('F 10, Y'),
                        'title' => 'Youth Environmental Summit',
                        'month_year' => $lastMonth->format('F Y')
                    ],
                    [
                        'date' => $lastMonth->format('F 05, Y'),
                        'title' => 'Tree Planting Activity',
                        'month_year' => $lastMonth->format('F Y')
                    ]
                ]
            ];
        }

        return view('view-youth-profile', [
            'youth' => $youth,
            'currentUser' => $currentUser,
            'currentUserAge' => $currentUserAge,
            'roleBadge' => $roleBadge,
            'progressPercentage' => $progressPercentage,
            'evaluatedPrograms' => $evaluatedPrograms,
            'eventsByMonth' => $eventsByMonth,
            'programRegistrations' => $programRegistrations,
            'isRegisteredVoter' => $isRegisteredVoter,
            'youthClassification' => $youthClassification,
            'formattedDOB' => $formattedDOB,
            'age' => $age,
            'attendedEventsCount' => $attendedEvents,
            'totalEventsCount' => $totalEvents
        ]);
    }

    /**
     * Determine youth classification based on education and work status
     */
    private function getYouthClassification($education, $workStatus)
    {
        $education = strtolower($education ?? '');
        $workStatus = strtolower($workStatus ?? '');
        
        if (str_contains($education, 'student') || str_contains($workStatus, 'student')) {
            return 'In-school Youth';
        } elseif ($workStatus === 'unemployed' || 
                 str_contains($education, 'high school') ||
                 str_contains($education, 'elementary')) {
            return 'Out-of-school Youth';
        } else {
            return 'Core Youth';
        }
    }
}