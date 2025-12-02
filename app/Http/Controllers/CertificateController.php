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

        // Get user info for profile
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : 'N/A';
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'Member';

        // --- Notifications for Dropdown (Same as Dashboard) ---
        $generalNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadNotificationCount = $generalNotifications->where('is_read', 0)->count();

        // --- Evaluation Progress (Same as Dashboard) ---
        
        // Count attended events
        $attendedEventsCount = Attendance::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Count registered programs
        $registeredProgramsCount = ProgramRegistration::where('user_id', $user->id)
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Total activities (events + programs)
        $totalActivities = $attendedEventsCount + $registeredProgramsCount;

        // Count evaluated events
        $evaluatedEventsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('event_id')
            ->whereHas('event', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Count evaluated programs
        $evaluatedProgramsCount = Evaluation::where('user_id', $user->id)
            ->whereNotNull('program_id')
            ->whereHas('program', fn($q) => $q->where('barangay_id', $user->barangay_id))
            ->count();

        // Total evaluated activities
        $evaluatedActivities = $evaluatedEventsCount + $evaluatedProgramsCount;

        // Get unevaluated events for notifications
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

        // Get unevaluated programs for notifications
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

        // Prepare unevaluated activities for display
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

        // --- Total Notification Count for Badge ---
        $totalNotificationCount = $unreadNotificationCount + $unevaluatedActivities->count();

        return view('certificatepage', [
            'user' => $user,
            'age' => $age,
            'roleBadge' => $roleBadge,
            'generalNotifications' => $generalNotifications,
            'unevaluatedActivities' => $unevaluatedActivities,
            'notificationCount' => $totalNotificationCount,
        ]);
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