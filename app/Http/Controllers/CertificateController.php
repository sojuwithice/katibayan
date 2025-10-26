<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Event;        
use App\Models\CertificateRequest; 
use App\Models\Notification;
use App\Models\CertificateSchedule; 
use App\Models\Announcement;        
use Carbon\Carbon;                  
use Illuminate\Support\Facades\Log; 

class CertificateController extends Controller
{
    public function acceptRequests(Request $request)
    {
        
        $validated = $request->validate(['event_id' => 'required|integer|exists:events,id']);

        CertificateRequest::where('event_id', $validated['event_id'])
            ->update(['status' => 'accepted']);

        return response()->json(['message' => 'Requests accepted']);
    }

    public function setSchedule(Request $request)
{
    Log::info('setSchedule method called.'); 
    Log::info('Request data:', $request->all()); 

    // --- Validation ---
    $validated = $request->validate([
        'event_id' => 'required|integer|exists:events,id',
        'date' => 'required|date_format:Y-m-d', 
        'time' => 'required|string',
        'location' => 'required|string|max:255',
    ]);
    Log::info('Validation passed:', $validated);

    try {
       
        $event = Event::findOrFail($validated['event_id']);
        Log::info('Event found:', $event->toArray()); 

        $schedule = CertificateSchedule::updateOrCreate(
            ['event_id' => $event->id], 
            [
                'release_date' => $validated['date'],
                'release_time' => $validated['time'],
                'location' => $validated['location'],
            ]
        );
        Log::info('CertificateSchedule saved/updated:', $schedule->toArray()); 

        // --- Gumawa ng Announcement ---
        // --- Gumawa ng Announcement ---
        if (isset($event->barangay_id)) {
            $announcement = Announcement::updateOrCreate(
                [
                    'barangay_id' => $event->barangay_id,
                    'type' => 'certificate_schedule',
                    'title' => 'Certificate Claiming Schedule: ' . $event->title
                ],
                [
                    'title' => 'Certificate Claiming Schedule: ' . $event->title,
                    'message' => "Claiming for '{$event->title}' is set on "
                        . Carbon::parse($validated['date'])->format('F j, Y') 
                        . " at {$validated['time']} ({$validated['location']}). Please bring a valid ID.",
                    'type' => 'certificate_schedule', 
                    'expires_at' => Carbon::parse($validated['date'])->endOfDay(), // <--- ITO NA YUNG TAMA
                ]
            );
            Log::info('Announcement saved/updated:', $announcement->toArray()); 
        }

        // --- Send Notifications ---
        $participants = CertificateRequest::where('event_id', $event->id)
            ->where('status', 'accepted')
            ->pluck('user_id');

        foreach ($participants as $uid) {
            Notification::create([
                'user_id' => $uid,
                'title' => 'Certificate Claiming Schedule Set',
                'message' => "You can now claim your certificate for '{$event->title}' on "
                    . Carbon::parse($validated['date'])->format('F j, Y') 
                    . " at {$validated['time']} in {$validated['location']}.",
                'is_read' => 0,
                'type' => 'certificate_schedule'
            ]);
        }

        // ✅ ——— NEW LOGIC ———
        CertificateRequest::where('event_id', $validated['event_id'])
            ->where('status', 'accepted')
            ->update(['status' => 'ready_for_pickup']);
        Log::info('Statuses updated to ready_for_pickup');

        return response()->json(['message' => 'Schedule set, users notified, and requests updated to ready_for_pickup']);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An unexpected error occurred. Please check the logs.'], 500);
    }
}



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