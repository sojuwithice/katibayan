<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Program;
use App\Models\CertificateRequest;
use App\Models\Notification;
use App\Models\CertificateSchedule;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\ProgramRegistration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    /**
     * Display the certificate page with all necessary data
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Get all dashboard data including notifications
        $dashboardData = $this->getDashboardData($user);
        
        return view('certificatepage', $dashboardData);
    }

    /**
     * Shared method to get dashboard data including notifications
     * This matches the DashboardController structure
     */
    private function getDashboardData($user): array
    {
        // --- Basic User Info ---
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'Member';

        // --- Get Notifications (Same as Dashboard) ---
        $notificationData = $this->getUserNotifications($user);
        
        // --- Current date/time ---
        $today = Carbon::today();
        $now = Carbon::now();

        // ====================================================
        // GET UPCOMING EVENTS (Only FUTURE launched events)
        // ====================================================
        $upcomingEvents = Event::where('is_launched', true)
            ->where('barangay_id', $user->barangay_id)
            ->where(function($query) use ($today, $now) {
                $query->where('event_date', '>', $today)
                    ->orWhere(function($q) use ($today, $now) {
                        $q->where('event_date', $today)
                          ->where('event_time', '>', $now->format('H:i:s'));
                    });
            })
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(4)
            ->get()
            ->map(function($event) use ($now) {
                return $this->formatEventData($event, $now);
            })
            ->filter(function($event) {
                return $event['is_future'];
            });

        // ====================================================
        // GET UPCOMING PROGRAMS (Only FUTURE programs)
        // ====================================================
        $upcomingPrograms = Program::where('barangay_id', $user->barangay_id)
            ->where(function($query) use ($today, $now) {
                $query->where('event_date', '>', $today)
                    ->orWhere(function($q) use ($today, $now) {
                        $q->where('event_date', $today)
                          ->where('event_time', '>', $now->format('H:i:s'));
                    });
            })
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(4)
            ->get()
            ->map(function($program) use ($now) {
                return $this->formatProgramData($program, $now);
            })
            ->filter(function($program) {
                return $program['is_future'];
            });

        // ====================================================
        // PREPARE SLIDER ITEMS
        // ====================================================
        $sliderItems = $this->prepareSliderItems($upcomingEvents, $upcomingPrograms, $user);

        // ====================================================
        // EVALUATION PROGRESS
        // ====================================================
        $progressData = $this->getEvaluationProgress($user);

        // Upcoming Events & Programs for Display (sidebar)
        $displayItems = $this->prepareDisplayItems($upcomingEvents, $upcomingPrograms);

        // Attendance Percentage
        $attendanceData = $this->getAttendanceData($user);

        // Announcements
        $announcements = Announcement::where('barangay_id', $user->barangay_id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->get();

        // Merge all data
        return array_merge([
            'user' => $user,
            'age' => $age,
            'roleBadge' => $roleBadge,
            
            // Attendance
            'attendedCount' => $attendanceData['attendedCount'],
            'totalEvents' => $attendanceData['totalEvents'],
            'attendancePercentage' => $attendanceData['attendancePercentage'],
            
            // Evaluation
            'totalActivities' => $progressData['totalActivities'],
            'evaluatedActivities' => $progressData['evaluatedActivities'],
            'activitiesToEvaluate' => $progressData['activitiesToEvaluate'],
            
            // Display
            'displayItems' => $displayItems,
            'announcements' => $announcements,

            // SLIDER ITEMS
            'sliderItems' => $sliderItems,
            'upcomingEvents' => $upcomingEvents,
            'upcomingPrograms' => $upcomingPrograms,
        ], $notificationData);
    }

    /**
     * Shared method to get user notifications
     * This is the SAME method as in DashboardController
     */
    private function getUserNotifications($user): array
    {
        // --- General Notifications ---
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

        // --- Unevaluated Activities for notifications ---
        $unevaluatedEvents = Event::where('barangay_id', $user->barangay_id)
            ->where('is_launched', true)
            ->whereHas('attendances', function($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('attended_at');
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['attendances' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('event_date', 'desc')
            ->get();

        $unevaluatedPrograms = Program::where('barangay_id', $user->barangay_id)
            ->whereHas('programRegistrations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereDoesntHave('evaluations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['programRegistrations' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('event_date', 'desc')
            ->get();

        $unevaluatedActivities = collect();
        
        foreach ($unevaluatedEvents as $event) {
            $unevaluatedActivities->push([
                'id' => $event->id,
                'type' => 'event',
                'title' => $event->title,
                'attendance' => $event->attendances->first(),
                'created_at' => $event->attendances->first()->created_at ?? $event->created_at
            ]);
        }
        
        foreach ($unevaluatedPrograms as $program) {
            $unevaluatedActivities->push([
                'id' => $program->id,
                'type' => 'program',
                'title' => $program->title,
                'registration' => $program->programRegistrations->first(),
                'created_at' => $program->programRegistrations->first()->created_at ?? $program->created_at
            ]);
        }

        $totalNotificationCount = $unreadNotificationCount + $unevaluatedActivities->count();

        return [
            'unevaluatedEvents' => $unevaluatedEvents,
            'unevaluatedActivities' => $unevaluatedActivities,
            'notificationCount' => $totalNotificationCount,
            'generalNotifications' => $generalNotifications,
        ];
    }

    /**
     * Format event data for slider
     */
    private function formatEventData($event, $now)
    {
        $eventDate = Carbon::parse($event->event_date);
        $eventDateTime = $eventDate->copy();
        
        if ($event->event_time) {
            try {
                $timeParts = explode(':', $event->event_time);
                $eventDateTime->setTime((int)$timeParts[0], (int)($timeParts[1] ?? 0), (int)($timeParts[2] ?? 0));
            } catch (\Exception $e) {
                Log::warning("Invalid time format for event {$event->id}: {$event->event_time}");
            }
        }
        
        return [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'event_date' => $event->event_date,
            'event_time' => $event->event_time,
            'location' => $event->location,
            'category' => $event->category,
            'image' => $event->image,
            'is_launched' => $event->is_launched,
            'status' => $event->status,
            'type' => 'event',
            'formatted_date' => $eventDate->format('M d'),
            'formatted_time' => $event->event_time ? Carbon::parse($event->event_time)->format('g:i A') : 'TBA',
            'date_object' => $eventDate,
            'datetime_object' => $eventDateTime,
            'is_future' => $eventDateTime->gt($now)
        ];
    }

    /**
     * Format program data for slider
     */
    private function formatProgramData($program, $now)
    {
        $programDate = Carbon::parse($program->event_date);
        $programDateTime = $programDate->copy();
        
        if ($program->event_time) {
            try {
                $timeParts = explode(':', $program->event_time);
                $programDateTime->setTime((int)$timeParts[0], (int)($timeParts[1] ?? 0), (int)($timeParts[2] ?? 0));
            } catch (\Exception $e) {
                Log::warning("Invalid time format for program {$program->id}: {$program->event_time}");
            }
        }
        
        return [
            'id' => $program->id,
            'title' => $program->title,
            'description' => $program->description,
            'event_date' => $program->event_date,
            'event_end_date' => $program->event_end_date,
            'event_time' => $program->event_time,
            'location' => $program->location,
            'category' => $program->category,
            'display_image' => $program->display_image,
            'registration_type' => $program->registration_type,
            'link_source' => $program->link_source,
            'type' => 'program',
            'formatted_date' => $programDate->format('M d'),
            'formatted_time' => $program->event_time ? Carbon::parse($program->event_time)->format('g:i A') : 'TBA',
            'date_object' => $programDate,
            'datetime_object' => $programDateTime,
            'is_future' => $programDateTime->gt($now)
        ];
    }

    /**
     * Prepare slider items
     */
    private function prepareSliderItems($upcomingEvents, $upcomingPrograms, $user)
    {
        $sliderItems = collect();
        
        // Always add welcome slide as first
        $sliderItems->push([
            'type' => 'welcome',
            'data' => [
                'title' => 'Welcome',
                'user_name' => $user->given_name
            ],
            'sort_date' => Carbon::now()->subYears(100) // Very old, always first
        ]);
        
        // Add upcoming events to slider
        foreach ($upcomingEvents as $event) {
            $sliderItems->push([
                'type' => 'event',
                'data' => $event,
                'sort_date' => $event['datetime_object']
            ]);
        }
        
        // Add upcoming programs to slider
        foreach ($upcomingPrograms as $program) {
            $sliderItems->push([
                'type' => 'program',
                'data' => $program,
                'sort_date' => $program['datetime_object']
            ]);
        }
        
        // Sort by date (closest first)
        $sliderItems = $sliderItems->sortBy('sort_date');

        // If no upcoming events/programs, add a "no events" slide
        if ($upcomingEvents->count() === 0 && $upcomingPrograms->count() === 0) {
            $sliderItems->push([
                'type' => 'no_events',
                'data' => [
                    'title' => 'No Upcoming Activities',
                    'message' => 'Check back later for new events and programs!'
                ],
                'sort_date' => Carbon::now()->addYears(100) // Very future, always last
            ]);
        }

        // Limit to 5 slides max (including welcome and no_events)
        return $sliderItems->take(5);
    }

    /**
     * Get evaluation progress data
     */
    private function getEvaluationProgress($user)
    {
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $registeredProgramsCount = ProgramRegistration::where('user_id', $user->id)
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $totalActivities = $attendedEventsCount + $registeredProgramsCount;

        $evaluatedEventsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('event_id')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $evaluatedProgramsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('program_id')
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $evaluatedActivities = $evaluatedEventsCount + $evaluatedProgramsCount;
        $activitiesToEvaluate = max(0, $totalActivities - $evaluatedActivities);

        return [
            'totalActivities' => $totalActivities,
            'evaluatedActivities' => $evaluatedActivities,
            'activitiesToEvaluate' => $activitiesToEvaluate,
        ];
    }

    /**
     * Get attendance data
     */
    private function getAttendanceData($user)
    {
        $today = Carbon::today();
        
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        $totalPastEvents = Event::where('is_launched', true)
            ->where('event_date', '<', $today)
            ->where('barangay_id', $user->barangay_id)
            ->count();

        $attendancePercentage = $totalPastEvents > 0 ? round(($attendedEventsCount / $totalPastEvents) * 100) : 0;

        return [
            'attendedCount' => $attendedEventsCount,
            'totalEvents' => $totalPastEvents,
            'attendancePercentage' => $attendancePercentage,
        ];
    }

    /**
     * Prepare display items for events/programs list
     */
    private function prepareDisplayItems($upcomingEvents, $upcomingPrograms)
    {
        $holidays = [
            '2025-01-01' => 'New Year\'s Day', '2025-04-09' => 'Araw ng Kagitingan', 
            '2025-04-17' => 'Maundy Thursday', '2025-04-18' => 'Good Friday', 
            '2025-05-01' => 'Labor Day', '2025-06-06' => 'Eid\'l Fitr',
            '2025-06-12' => 'Independence Day', '2025-08-25' => 'National Heroes Day', 
            '2025-11-30' => 'Bonifacio Day', '2025-12-25' => 'Christmas Day', 
            '2025-12-30' => 'Rizal Day'
        ];
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $displayHolidays = [];
        
        foreach ($holidays as $date => $name) {
            try {
                $holidayDate = Carbon::parse($date);
                $holidayMonth = $holidayDate->month;
                if ($holidayDate->year == $currentYear && ($holidayMonth == $currentMonth || $holidayMonth == $currentMonth + 1)) {
                    $displayHolidays[] = [
                        'date' => $holidayDate, 
                        'name' => $name, 
                        'month' => $holidayDate->format('M'), 
                        'day' => $holidayDate->format('d'), 
                        'is_holiday' => true
                    ];
                }
            } catch (\Exception $e) {
                Log::error("Error parsing holiday date: $date - " . $e->getMessage());
            }
        }

        $allUpcomingItems = collect();

        // Add events
        foreach ($upcomingEvents as $event) {
            $eventDate = null;
            if ($event['date_object'] instanceof Carbon) {
                $eventDate = $event['date_object'];
            } elseif (is_string($event['event_date'])) {
                try {
                    $eventDate = Carbon::parse($event['event_date']);
                } catch (\Exception $e) {
                    Log::error("Error parsing event date string for event ID {$event['id']}: '{$event['event_date']}' - " . $e->getMessage());
                    continue;
                }
            }

            if ($eventDate) {
                $allUpcomingItems->push([
                    'type' => 'event', 
                    'date' => $eventDate, 
                    'month' => $eventDate->format('M'), 
                    'day' => $eventDate->format('d'), 
                    'title' => $event['title'], 
                    'status' => 'Upcoming Event', 
                    'is_holiday' => false
                ]);
            }
        }

        // Add programs
        foreach ($upcomingPrograms as $program) {
            $programDate = null;
            if ($program['date_object'] instanceof Carbon) {
                $programDate = $program['date_object'];
            } elseif (is_string($program['event_date'])) {
                try {
                    $programDate = Carbon::parse($program['event_date']);
                } catch (\Exception $e) {
                    Log::error("Error parsing program date string for program ID {$program['id']}: '{$program['event_date']}' - " . $e->getMessage());
                    continue;
                }
            }

            if ($programDate) {
                $allUpcomingItems->push([
                    'type' => 'program', 
                    'date' => $programDate, 
                    'month' => $programDate->format('M'), 
                    'day' => $programDate->format('d'), 
                    'title' => $program['title'], 
                    'status' => 'Upcoming Program', 
                    'is_holiday' => false
                ]);
            }
        }

        // Add holidays
        foreach ($displayHolidays as $holiday) {
            $allUpcomingItems->push([
                'type' => 'holiday', 
                'date' => $holiday['date'], 
                'month' => $holiday['month'], 
                'day' => $holiday['day'], 
                'title' => $holiday['name'], 
                'status' => 'Holiday', 
                'is_holiday' => true
            ]);
        }

        return $allUpcomingItems->sortBy('date')->take(6);
    }

    /**
     * (INAYOS PARA TUMANGGAP NG event_id O program_id)
     */
    public function acceptRequests(Request $request)
    {
        // 1. I-validate. Dapat isa sa kanila ay meron.
        $validated = $request->validate([
            'event_id' => 'nullable|integer|exists:events,id',
            'program_id' => 'nullable|integer|exists:programs,id',
        ]);

        // 2. Check kung parehong empty
        if (empty($validated['event_id']) && empty($validated['program_id'])) {
            return response()->json(['message' => 'An event_id or program_id is required.'], 422);
        }
        
        // 3. Alamin kung ano 'yung column na gagamitin
        $isEvent = !empty($validated['event_id']);
        $activityId = $isEvent ? $validated['event_id'] : $validated['program_id'];
        $activityColumn = $isEvent ? 'event_id' : 'program_id';

        // 4. I-update 'yung database
        CertificateRequest::where($activityColumn, $activityId)
            ->where('status', 'requesting') // I-update lang 'yung 'requesting'
            ->update(['status' => 'accepted']);

        return response()->json(['message' => 'Requests accepted']);
    }

    /**
     * (INAYOS PARA TUMANGGAP NG event_id O program_id)
     */
    public function setSchedule(Request $request)
    {
        Log::info('setSchedule method called.'); 
        Log::info('Request data:', $request->all()); 

        // --- 1. Validation (Inayos) ---
        $validated = $request->validate([
            'event_id' => 'nullable|integer|exists:events,id',
            'program_id' => 'nullable|integer|exists:programs,id',
            'date' => 'required|date_format:Y-m-d', 
            'time' => 'required|string',
            'location' => 'required|string|max:255',
        ]);

        // Check kung parehong empty
        if (empty($validated['event_id']) && empty($validated['program_id'])) {
            return response()->json(['message' => 'An event_id or program_id is required.'], 422);
        }
        
        // Alamin kung ano 'yung column na gagamitin
        $isEvent = !empty($validated['event_id']);
        $activityId = $isEvent ? $validated['event_id'] : $validated['program_id'];
        $activityColumn = $isEvent ? 'event_id' : 'program_id';
        
        Log::info('Validation passed:', $validated);

        try {
            
            // --- 2. Hanapin 'yung Activity (Event man o Program) ---
            $activity = $isEvent
                ? Event::findOrFail($activityId)
                : Program::findOrFail($activityId);
            
            Log::info('Activity found:', $activity->toArray()); 

            // Gagamitin natin 'to para sa schedule details
            $scheduleText = "Claiming for '{$activity->title}' is set on "
                        . Carbon::parse($validated['date'])->format('F j, Y') 
                        . " at {$validated['time']} ({$validated['location']}). Please bring a valid ID.";

            // --- Gagamit tayo ng Transaction para sigurado ---
            DB::transaction(function () use ($activity, $activityColumn, $activityId, $validated, $scheduleText, $isEvent) {

                // --- 3. I-save 'yung Schedule (Inayos) ---
                // (PAALALA: Dapat 'yung 'certificate_schedules' table mo ay may 'program_id' column din na nullable)
                CertificateSchedule::updateOrCreate(
                    [$activityColumn => $activity->id], // Hanapin gamit 'yung tamang column
                    [
                        'event_id' => $isEvent ? $activity->id : null, // Eksplisit na i-set
                        'program_id' => !$isEvent ? $activity->id : null, // Eksplisit na i-set
                        'release_date' => $validated['date'],
                        'release_time' => $validated['time'],
                        'location' => $validated['location'],
                    ]
                );
                Log::info('CertificateSchedule saved/updated.'); 

                // --- 4. Gumawa ng Announcement (Okay na 'to) ---
                if (isset($activity->barangay_id)) {
                    Announcement::updateOrCreate(
                        [
                            'barangay_id' => $activity->barangay_id,
                            'type' => 'certificate_schedule',
                            'title' => 'Certificate Claiming Schedule: ' . $activity->title
                        ],
                        [
                            'title' => 'Certificate Claiming Schedule: ' . $activity->title,
                            'message' => $scheduleText,
                            'type' => 'certificate_schedule', 
                            'expires_at' => Carbon::parse($validated['date'])->endOfDay(),
                        ]
                    );
                    Log::info('Announcement saved/updated.'); 
                }

                // --- 5. Send Notifications (Inayos) ---
                $participants = CertificateRequest::where($activityColumn, $activity->id)
                    ->where('status', 'accepted')
                    ->pluck('user_id');

                foreach ($participants as $uid) {
                    Notification::create([
                        'user_id' => $uid,
                        'title' => 'Certificate Claiming Schedule Set',
                        'message' => "You can now claim your certificate for '{$activity->title}' on "
                            . Carbon::parse($validated['date'])->format('F j, Y') 
                            . " at {$validated['time']} in {$validated['location']}.",
                        'is_read' => 0,
                        'type' => 'certificate_schedule'
                    ]);
                }

                // --- 6. I-update 'yung status ng Requests (Inayos) ---
                CertificateRequest::where($activityColumn, $activityId)
                    ->where('status', 'accepted')
                    ->update(['status' => 'ready_for_pickup']);
                
                Log::info('Statuses updated to ready_for_pickup');
            }); // End ng transaction

            return response()->json(['message' => 'Schedule set, users notified, and requests updated to ready_for_pickup']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in setSchedule: ' . $e->getMessage()); // Mas magandang i-log 'yung error
            return response()->json(['message' => 'An unexpected error occurred. Please check the logs.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * HINDI MUNA NATIN 'TO GINALAW.
     * Ito ay para sa route na '/certificate-request-list/{event_id}'
     * na iba sa route na '/certificate-request/{type}/{id}'
     */
    public function showCertificateRequests($event_id)
    {
        $event = Event::with('certificateSchedule')->findOrFail($event_id);

        $requests = CertificateRequest::with('user') 
            ->where('event_id', $event_id)
            ->get();

        $requests->each(function ($req) {
             if ($req->user) {
                 $req->user->name = trim("{$req->user->given_name} {$req->user->middle_name} {$req->user->last_name} {$req->user->suffix}");
                 $req->user->age = $req->user->date_of_birth ? Carbon::parse($req->user->date_of_birth)->age : 'N/A';
             } else {
                 $req->user = (object)['account_number' => 'N/A', 'name' => 'Unknown User', 'age' => 'N/A', 'purok' => 'N/A'];
             }
        });

        return view('certificate-request-list', compact('event', 'requests'));
    }

    /**
     * HINDI NA RIN NATIN 'TO GINALAW.
     * Mukhang tama na 'yung logic nito.
     */
    public function claimCertificate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificate_requests,id'
        ]);

        $cert = \App\Models\CertificateRequest::find($request->id);
        $cert->update(['status' => 'claimed']);

        return response()->json(['success' => true]);
    }
}