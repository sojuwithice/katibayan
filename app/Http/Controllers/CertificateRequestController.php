<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\Notification;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon; // <-- IDAGDAG MO ITO

class CertificateRequestController extends Controller
{
    /**
     * Store new certificate request
     * (INAYOS NATIN 'YUNG BUONG FUNCTION NA 'TO)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['message' => 'You must be logged in to request a certificate.'], 401);
        }

        $eventId = $validated['event_id'];
        $user = Auth::user(); // Kunin 'yung user object

        // Hanapin kung may existing request na
        $existing = CertificateRequest::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        // (BAGO) Logic para sa 1-week cooldown at 2-request limit
        if ($existing) {
            // RULE #1: Check kung naabot na 'yung 2-request limit
            if ($existing->request_count >= 2) {
                return response()->json([
                    'message' => 'You have reached the maximum (2) requests for this certificate.'
                ], 429); // 429 = Too Many Requests
            }

            // RULE #2: Check kung 1 week na ang nakalipas
            $lastRequestTime = $existing->updated_at; // Gagamitin natin 'updated_at'
            $cooldownDate = Carbon::now()->subDays(7);

            if ($lastRequestTime->isAfter($cooldownDate)) {
                // Kung 'yung huling request ay NANGYARI sa loob ng huling 7 araw
                return response()->json([
                    'message' => 'You can only request again 7 days after your last request.'
                ], 429);
            }

            // --- Pwede na siya mag-request ulit (re-request) ---
            try {
                DB::transaction(function () use ($existing, $user) {
                    
                    // I-update 'yung status at bilangin 'yung request
                    $existing->update([
                        'status' => 'requesting', // Ibalik sa 'requesting'
                        'request_count' => $existing->request_count + 1, // Idagdag 'yung bilang
                    ]);
                    
                    $nameParts = array_filter([$user->given_name, $user->middle_name, $user->last_name]);
                    $userName = !empty($nameParts) ? implode(' ', $nameParts) : 'A user';

                    // Gumawa ng BAGONG notification para sa admin
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Certificate Re-Request', // Palitan 'yung title
                        'message' => $userName . ' requested a certificate again for an event.',
                        'type' => 'certificate_request',
                        'recipient_role' => 'sk',
                        'is_read' => false,
                    ]);
                });
                
                // Ibalik 'yung bagong request count
                return response()->json([
                    'message' => 'Request submitted successfully!',
                    'new_request_count' => $existing->request_count // Ipadala pabalik sa JS
                ]);

            } catch (Exception $e) {
                return response()->json(['message' => 'An error occurred. Please try again.', 'error' => $e->getMessage()], 500);
            }

        } else {
            // --- Ito 'yung 1st time request ---
            try {
                DB::transaction(function () use ($userId, $eventId, $user) {

                    CertificateRequest::create([
                        'user_id' => $userId,
                        'event_id' => $eventId,
                        'status' => 'requesting',
                        'request_count' => 1, // Ito 'yung 1st request
                    ]);

                    $nameParts = array_filter([$user->given_name, $user->middle_name, $user->last_name]);
                    $userName = !empty($nameParts) ? implode(' ', $nameParts) : 'A user';

                    Notification::create([
                        'user_id' => $userId,
                        'title' => 'New Certificate Request',
                        'message' => $userName . ' requested a certificate for an event.',
                        'type' => 'certificate_request',
                        'recipient_role' => 'sk',
                        'is_read' => false,
                    ]);
                });

                return response()->json([
                    'message' => 'Request submitted successfully!',
                    'new_request_count' => 1 // 1st request
                ]);
            } catch (Exception $e) {
                return response()->json(['message' => 'An error occurred. Please try again.', 'error' => $e->getMessage()], 500);
            }
        }
    }

    public function index()
    {
        $requests = CertificateRequest::with(['event' => function ($query) {
            $query->select('id', 'title', DB::raw("DATE_FORMAT(event_date, '%Y-%m-%d') as event_date"));
        }])
            ->select('event_id')
            ->selectRaw('COUNT(*) as total_requests')
            ->groupBy('event_id')
            ->get();

        return view('certificate-request', compact('requests'));
    }

    public function showList($event_id)
    {
        $requests = CertificateRequest::with('user')
            ->where('event_id', $event_id)
            ->get();

        // ✅ Fix: select proper event_date column
        $event = Event::select('id', 'title', DB::raw("DATE_FORMAT(event_date, '%Y-%m-%d') as event_date"))
            ->find($event_id);

        return view('certificate-request-list', compact('requests', 'event'));
    }
}