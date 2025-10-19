<?php
// app/Http/Controllers/ProgramController.php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Barangay;
use App\Models\User;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProgramController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get barangay name with proper relationship
        $barangay = Barangay::find($user->barangay_id);
        
        // Calculate age properly - FIXED
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age; // This gives the actual age in whole years
        }
        
        // Set role badge
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';
        
        return view('create-program', compact('user', 'barangay', 'age', 'roleBadge'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_time' => 'required',
                'category' => 'required|string|max:255',
                'location' => 'required|string',
                'description' => 'nullable|string',
                'display_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'published_by' => 'required|string|max:255',
                'registration_type' => 'required|in:create,link',
                'link_source' => 'nullable|url|required_if:registration_type,link',
                'registration_title' => 'nullable|string',
                'registration_description' => 'nullable|string|required_if:registration_type,create',
                'registration_open_date' => 'nullable|date|required_if:registration_type,create',
                'registration_open_time' => 'nullable|required_if:registration_type,create',
                'registration_close_date' => 'nullable|date|required_if:registration_type,create',
                'registration_close_time' => 'nullable|required_if:registration_type,create',
                'custom_fields' => 'nullable|string', // For custom fields JSON
            ]);

            // Handle file upload
            if ($request->hasFile('display_image')) {
                $imagePath = $request->file('display_image')->store('programs', 'public');
                $validated['display_image'] = $imagePath;
            }

            // Convert time format
            $validated['event_time'] = $this->convertTimeTo24Hour($validated['event_time']);

            if ($validated['registration_type'] === 'create') {
                if (!empty($validated['registration_open_time'])) {
                    $validated['registration_open_time'] = $this->convertTimeTo24Hour($validated['registration_open_time']);
                }
                if (!empty($validated['registration_close_time'])) {
                    $validated['registration_close_time'] = $this->convertTimeTo24Hour($validated['registration_close_time']);
                }

                // Store custom fields as JSON
                if ($request->has('custom_fields')) {
                    $validated['custom_fields'] = $request->custom_fields;
                }
            } else {
                // If registration type is link, set create registration fields to null
                $validated['registration_title'] = null;
                $validated['registration_description'] = null;
                $validated['registration_open_date'] = null;
                $validated['registration_open_time'] = null;
                $validated['registration_close_date'] = null;
                $validated['registration_close_time'] = null;
                $validated['custom_fields'] = null;
            }

            // Add user and barangay info
            $validated['user_id'] = Auth::id();
            $validated['barangay_id'] = Auth::user()->barangay_id;

            // Create the program
            $program = Program::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Program created successfully!',
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating program: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display program details for modal (JSON response)
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $programId = (int)$id;
            
            // Only show programs from the same barangay
            $program = Program::where('id', $programId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$program) {
                return response()->json(['error' => 'Program not found'], 404);
            }

            // Build response data safely
            $responseData = [
                'id' => $program->id,
                'title' => $program->title,
                'description' => $program->description,
                'event_date' => $program->event_date ? Carbon::parse($program->event_date)->format('Y-m-d') : null,
                'event_time' => $program->event_time,
                'location' => $program->location,
                'category' => $program->category,
                'published_by' => $program->published_by,
                'registration_type' => $program->registration_type,
                'link_source' => $program->link_source,
                'registration_title' => $program->registration_title,
                'registration_description' => $program->registration_description,
                'registration_open_date' => $program->registration_open_date,
                'registration_open_time' => $program->registration_open_time,
                'registration_close_date' => $program->registration_close_date,
                'registration_close_time' => $program->registration_close_time,
                'barangay_id' => $program->barangay_id,
                'custom_fields' => $program->custom_fields ? json_decode($program->custom_fields, true) : [],
            ];

            // Safely handle display_image URL
            if ($program->display_image) {
                try {
                    // Check if file exists in storage
                    if (Storage::disk('public')->exists($program->display_image)) {
                        $responseData['display_image'] = asset('storage/' . $program->display_image);
                    } else {
                        $responseData['display_image'] = null;
                    }
                } catch (\Exception $e) {
                    $responseData['display_image'] = null;
                }
            } else {
                $responseData['display_image'] = null;
            }

            return response()->json($responseData);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store program registration from modal form - FIXED VERSION
     */
    public function storeRegistration(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'program_id' => 'required|exists:programs,id',
                'registration_data' => 'required|json', // All custom fields stored as JSON
            ]);

            // Check if program exists and belongs to user's barangay
            $program = Program::where('id', $validated['program_id'])
                            ->where('barangay_id', $user->barangay_id)
                            ->first();

            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found or not available in your barangay.'
                ], 404);
            }

            // Check if user already registered for this program
            $existingRegistration = ProgramRegistration::where('program_id', $program->id)
                                                    ->where('user_id', $user->id)
                                                    ->first();

            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already registered for this program.'
                ], 409);
            }

            // Check registration period if it's a create registration type
            if ($program->registration_type === 'create') {
                $now = Carbon::now();
                
                if ($program->registration_open_date && $program->registration_open_time) {
                    $registrationOpen = Carbon::parse($program->registration_open_date . ' ' . $program->registration_open_time);
                    if ($now->lt($registrationOpen)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Registration has not opened yet. It opens on ' . $registrationOpen->format('M j, Y g:i A')
                        ], 400);
                    }
                }

                if ($program->registration_close_date && $program->registration_close_time) {
                    $registrationClose = Carbon::parse($program->registration_close_date . ' ' . $program->registration_close_time);
                    if ($now->gt($registrationClose)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Registration has closed. It closed on ' . $registrationClose->format('M j, Y g:i A')
                        ], 400);
                    }
                }
            }

            // Generate reference ID
            $referenceId = 'PROG-REG-' . time() . '-' . rand(1000, 9999);

            // Create registration with only the data that exists in your database
            $registration = ProgramRegistration::create([
                'program_id' => $program->id,
                'user_id' => $user->id,
                'reference_id' => $referenceId,
                'registration_data' => $validated['registration_data'], // Store all data as JSON
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration submitted successfully!',
                'reference_id' => $referenceId,
                'registration' => [
                    'id' => $registration->id,
                    'program_title' => $program->title,
                    'submitted_at' => $registration->created_at->format('M j, Y g:i A')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's program registrations
     */
    public function getUserRegistrations(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $registrations = ProgramRegistration::with('program')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registration) {
                    return [
                        'id' => $registration->id,
                        'reference_id' => $registration->reference_id,
                        'program_title' => $registration->program->title,
                        'status' => $registration->status,
                        'submitted_at' => $registration->created_at->format('M j, Y g:i A'),
                        'program_date' => $registration->program->event_date ? Carbon::parse($registration->program->event_date)->format('M j, Y') : 'TBA',
                        'registration_data' => $registration->registration_data ? json_decode($registration->registration_data, true) : []
                    ];
                });

            return response()->json([
                'success' => true,
                'registrations' => $registrations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations'
            ], 500);
        }
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        
        // Check if user owns the program
        if ($program->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        $barangay = Barangay::find($user->barangay_id);
        
        // Calculate age properly - FIXED
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age; // This gives the actual age in whole years
        }
        
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        return view('programs.edit', compact('program', 'user', 'barangay', 'age', 'roleBadge'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);
            
            // Check if user owns the program
            if ($program->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_time' => 'required',
                'category' => 'required|string|max:255',
                'location' => 'required|string',
                'description' => 'nullable|string',
                'display_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'published_by' => 'required|string|max:255',
                'registration_type' => 'required|in:create,link',
                'link_source' => 'nullable|url|required_if:registration_type,link',
                'registration_title' => 'nullable|string',
                'registration_description' => 'nullable|string|required_if:registration_type,create',
                'registration_open_date' => 'nullable|date|required_if:registration_type,create',
                'registration_open_time' => 'nullable|required_if:registration_type,create',
                'registration_close_date' => 'nullable|date|required_if:registration_type,create',
                'registration_close_time' => 'nullable|required_if:registration_type,create',
                'custom_fields' => 'nullable|string',
            ]);

            // Handle file upload
            if ($request->hasFile('display_image')) {
                // Delete old image if exists
                if ($program->display_image) {
                    Storage::disk('public')->delete($program->display_image);
                }
                
                $imagePath = $request->file('display_image')->store('programs', 'public');
                $validated['display_image'] = $imagePath;
            }

            // Convert time format
            $validated['event_time'] = $this->convertTimeTo24Hour($validated['event_time']);

            if ($validated['registration_type'] === 'create') {
                if (!empty($validated['registration_open_time'])) {
                    $validated['registration_open_time'] = $this->convertTimeTo24Hour($validated['registration_open_time']);
                }
                if (!empty($validated['registration_close_time'])) {
                    $validated['registration_close_time'] = $this->convertTimeTo24Hour($validated['registration_close_time']);
                }

                // Store custom fields as JSON
                if ($request->has('custom_fields')) {
                    $validated['custom_fields'] = $request->custom_fields;
                }
            } else {
                // If registration type is link, set create registration fields to null
                $validated['registration_title'] = null;
                $validated['registration_description'] = null;
                $validated['registration_open_date'] = null;
                $validated['registration_open_time'] = null;
                $validated['registration_close_date'] = null;
                $validated['registration_close_time'] = null;
                $validated['custom_fields'] = null;
            }

            $program->update($validated);

            return response()->json([
                'success' => true, 
                'message' => 'Program updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);
            
            // Check if user owns the program
            if ($program->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            // Delete associated image
            if ($program->display_image) {
                Storage::disk('public')->delete($program->display_image);
            }

            $program->delete();

            return response()->json([
                'success' => true, 
                'message' => 'Program deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $user = Auth::user();
        $programs = Program::where('barangay_id', $user->barangay_id)
                          ->with('user')
                          ->latest()
                          ->get();

        $barangay = Barangay::find($user->barangay_id);
        
        // Calculate age properly - FIXED
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age; // This gives the actual age in whole years
        }
        
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        return view('programs.index', compact('programs', 'user', 'barangay', 'age', 'roleBadge'));
    }

    /**
     * Get program registrations for youth registration page
     */
    public function getProgramRegistrations($programId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $program = Program::where('id', $programId)
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();

            $registrations = ProgramRegistration::with(['user' => function($query) {
                    $query->select('id', 'given_name', 'middle_name', 'last_name', 'suffix', 'email', 'contact_no', 'date_of_birth', 'barangay_id');
                }])
                ->with(['user.barangay' => function($query) {
                    $query->select('id', 'name');
                }])
                ->where('program_id', $programId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($registration) {
                    $age = null;
                    if ($registration->user->date_of_birth) {
                        $age = Carbon::parse($registration->user->date_of_birth)->age;
                    }
                    
                    // Get all registration data including custom fields
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    
                    return [
                        'id' => $registration->id,
                        'reference_id' => $registration->reference_id,
                        'user_name' => $registration->user->given_name . ' ' . 
                                      ($registration->user->middle_name ? $registration->user->middle_name . ' ' : '') . 
                                      $registration->user->last_name . 
                                      ($registration->user->suffix ? ' ' . $registration->user->suffix : ''),
                        'email' => $registration->user->email,
                        'contact_no' => $registration->user->contact_no,
                        'age' => $age,
                        'barangay' => $registration->user->barangay->name ?? 'N/A',
                        'status' => $registration->status,
                        'registered_at' => $registration->created_at->format('M d, Y g:i A'),
                        'registration_data' => $registrationData,
                        'custom_fields' => isset($registrationData['custom_fields']) ? $registrationData['custom_fields'] : []
                    ];
                });

            return response()->json([
                'success' => true,
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'event_date' => $program->event_date,
                    'event_time' => $program->event_time,
                    'category' => $program->category,
                    'total_registrations' => $registrations->count()
                ],
                'registrations' => $registrations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations: ' . $e->getMessage()
            ], 500);
        }
    }

    private function convertTimeTo24Hour($timeString)
    {
        return date('H:i:s', strtotime($timeString));
    }
}