<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\Notification;
use App\Models\Event;
use App\Models\Program; // <-- Importante 'to
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class CertificateRequestController extends Controller
{
    /**
     * Store new certificate request
     * (Walang binago rito, tama na 'yung logic mo)
     */
    public function store(Request $request)
    {
        // Step 1: Validate. Dapat isa sa kanila ay meron.
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'program_id' => 'nullable|exists:programs,id',
        ]);

        // Check kung parehong empty
        if (empty($validated['event_id']) && empty($validated['program_id'])) {
            return response()->json(['message' => 'An event_id or program_id is required.'], 422);
        }

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['message' => 'You must be logged in to request a certificate.'], 401);
        }

        $user = Auth::user();
        
        // Step 2: Alamin kung event o program
        $isEvent = !empty($validated['event_id']);
        $activityId = $isEvent ? $validated['event_id'] : $validated['program_id'];
        $activityColumn = $isEvent ? 'event_id' : 'program_id';

        // Step 3: Hanapin kung may existing request na
        $existing = CertificateRequest::where('user_id', $userId)
            ->where($activityColumn, $activityId) // Dynamic column check
            ->first();

        // Step 4: Logic para sa 1-week cooldown at 2-request limit
        if ($existing) {
            // RULE #1: Check kung naabot na 'yung 2-request limit
            if ($existing->request_count >= 2) {
                return response()->json([
                    'message' => 'You have reached the maximum (2) requests for this certificate.'
                ], 429); // 429 = Too Many Requests
            }

            // RULE #2: Check kung 1 week na ang nakalipas
            $lastRequestTime = $existing->updated_at;
            $cooldownDate = Carbon::now()->subDays(7);

            if ($lastRequestTime->isAfter($cooldownDate)) {
                return response()->json([
                    'message' => 'You can only request again 7 days after your last request.'
                ], 429);
            }

            // --- Pwede na siya mag-request ulit (re-request) ---
            try {
                DB::transaction(function () use ($existing, $user) {
                    
                    $existing->update([
                        'status' => 'requesting', // Ibalik sa 'requesting'
                        'request_count' => $existing->request_count + 1,
                    ]);
                    
                    $nameParts = array_filter([$user->given_name, $user->middle_name, $user->last_name]);
                    $userName = !empty($nameParts) ? implode(' ', $nameParts) : 'A user';

                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Certificate Re-Request',
                        'message' => $userName . ' requested a certificate again.', // Ginawang generic
                        'type' => 'certificate_request',
                        'recipient_role' => 'sk',
                        'is_read' => false,
                    ]);
                });
                
                return response()->json([
                    'message' => 'Request submitted successfully!',
                    'new_request_count' => $existing->request_count
                ]);

            } catch (Exception $e) {
                return response()->json(['message' => 'An error occurred. Please try again.', 'error' => $e->getMessage()], 500);
            }

        } else {
            // --- Ito 'yung 1st time request ---
            try {
                DB::transaction(function () use ($userId, $activityColumn, $activityId, $user) {

                    CertificateRequest::create([
                        'user_id' => $userId,
                        $activityColumn => $activityId, // Dynamic na ise-set 'event_id' man o 'program_id'
                        'status' => 'requesting',
                        'request_count' => 1,
                    ]);

                    $nameParts = array_filter([$user->given_name, $user->middle_name, $user->last_name]);
                    $userName = !empty($nameParts) ? implode(' ', $nameParts) : 'A user';

                    Notification::create([
                        'user_id' => $userId,
                        'title' => 'New Certificate Request',
                        'message' => $userName . ' requested a certificate.', // Ginawang generic
                        'type' => 'certificate_request',
                        'recipient_role' => 'sk',
                        'is_read' => false,
                    ]);
                });

                return response()->json([
                    'message' => 'Request submitted successfully!',
                    'new_request_count' => 1
                ]);
            } catch (Exception $e) {
                return response()->json(['message' => 'An error occurred. Please try again.', 'error' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Display a listing of all certificate requests grouped by activity.
     * // BINAGO: Idinagdag 'yung 'event_date' sa parehong queries
     */
    public function index()
    {
        // 1. Kunin lahat ng request para sa Events
        $eventRequests = CertificateRequest::with(['event' => function ($query) {
            $query->select('id', 'title', 'event_date'); // <-- IDAGDAG 'event_date'
        }])
            ->whereNotNull('event_id')
            ->select('event_id', DB::raw('COUNT(*) as total_requests'))
            ->groupBy('event_id')
            ->get()
            ->map(function ($item) {
                // I-format para maging generic
                return [
                    'activity_id' => $item->event_id,
                    'activity_type' => 'event',
                    'title' => $item->event ? $item->event->title : 'Event Not Found',
                    'date' => $item->event ? $item->event->event_date : null, // <-- IDAGDAG ITO
                    'total_requests' => $item->total_requests,
                ];
            });

        // 2. Kunin lahat ng request para sa Programs
        $programRequests = CertificateRequest::with(['program' => function ($query) {
            // Ginamit 'yung 'title' at 'event_date', base sa model mo
            $query->select('id', 'title', 'event_date'); // <-- IDAGDAG 'event_date'
        }])
            ->whereNotNull('program_id')
            ->select('program_id', DB::raw('COUNT(*) as total_requests'))
            ->groupBy('program_id')
            ->get()
            ->map(function ($item) {
                // I-format para maging generic
                return [
                    'activity_id' => $item->program_id,
                    'activity_type' => 'program',
                    'title' => $item->program ? $item->program->title : 'Program Not Found',
                    'date' => $item->program ? $item->program->event_date : null, // <-- IDAGDAG ITO
                    'total_requests' => $item->total_requests,
                ];
            });

        // 3. Pagsamahin sila
        $requests = $eventRequests->merge($programRequests)->sortByDesc('total_requests');

        // 4. I-pasa sa view
        return view('certificate-request', compact('requests'));
    }
    /**
     * Show the list of users who requested for a specific activity.
     * // BAGONG GAWA: Tumatanggap na ng $type ('event' o 'program') at $id
     */
    /**
     * Show the list of users who requested for a specific activity.
     * // BINAGO: Kukunin na 'yung buong activity object (pati image/description)
     * // at ipapasa ito sa view bilang '$activity'
     */
    public function showList($type, $id)
    {
        $activity = null;
        $activityColumn = '';

        // 1. Alamin kung event o program AT kunin lahat ng kailangang data
        if ($type === 'event') {
            $activityColumn = 'event_id';
            // Kunin lahat ng fields na kailangan ng view mo
            $activity = Event::select('id', 'title', 'description', 'event_date', 'image') // 'image' ang para sa event
                ->find($id);

        } elseif ($type === 'program') {
            $activityColumn = 'program_id';
            // Kunin lahat ng fields na kailangan ng view mo
            $activity = Program::select('id', 'title', 'description', 'event_date', 'display_image') // 'display_image' ang para sa program
                ->find($id);
            
        } else {
            // Kung mali 'yung type
            abort(404, 'Invalid activity type specified.');
        }

        if (!$activity) {
            abort(404, 'Activity not found.');
        }

        // 2. Kunin 'yung requests base sa tamang column
        $requests = CertificateRequest::with('user')
            ->where($activityColumn, $id)
            ->get();
            
        // 3. I-pasa sa view 'yung BUONG $activity object at 'yung $requests
        return view('certificate-request-list', compact('requests', 'activity'));
    }
}