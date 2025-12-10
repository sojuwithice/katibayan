<?php
namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Barangay;
use App\Models\User;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        
        $barangay = Barangay::find($user->barangay_id);
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age;
        }
        
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';
        
        return view('create-program', compact('user', 'barangay', 'age', 'roleBadge'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            Log::info('=== PROGRAM STORE REQUEST START ===');
            Log::info('Raw request data:', $request->all());
            Log::info('Event end date from request:', [
                'value' => $request->event_end_date,
                'type' => gettype($request->event_end_date),
                'is_null' => is_null($request->event_end_date),
                'is_empty' => empty($request->event_end_date)
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_end_date' => 'nullable|date|after_or_equal:event_date',
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

            Log::info('After validation - event_end_date:', [
                'value' => $validated['event_end_date'] ?? 'NOT_SET',
                'type' => isset($validated['event_end_date']) ? gettype($validated['event_end_date']) : 'NOT_SET'
            ]);

            // Handle file upload
            if ($request->hasFile('display_image')) {
                $imagePath = $request->file('display_image')->store('programs', 'public');
                $validated['display_image'] = $imagePath;
            }

            // Convert time format
            $validated['event_time'] = $this->convertTimeTo24Hour($validated['event_time']);

            // CRITICAL FIX: Handle registration times conversion
            if ($validated['registration_type'] === 'create') {
                if (!empty($validated['registration_open_time'])) {
                    $validated['registration_open_time'] = $this->convertTimeTo24Hour($validated['registration_open_time']);
                }
                if (!empty($validated['registration_close_time'])) {
                    $validated['registration_close_time'] = $this->convertTimeTo24Hour($validated['registration_close_time']);
                }

                // Store custom fields as JSON - UPDATED
                if ($request->has('custom_fields')) {
                    $validated['custom_fields'] = $this->parseCustomFields($request);
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

            // CRITICAL FIX: Calculate number_of_days based on start and end dates
            if (!empty($validated['event_end_date'])) {
                $startDate = Carbon::parse($validated['event_date']);
                $endDate = Carbon::parse($validated['event_end_date']);
                $validated['number_of_days'] = $startDate->diffInDays($endDate) + 1; // Inclusive of both dates
                Log::info('Number of days calculated:', [
                    'start_date' => $validated['event_date'],
                    'end_date' => $validated['event_end_date'],
                    'number_of_days' => $validated['number_of_days']
                ]);
            } else {
                $validated['number_of_days'] = 1; // Default to 1 day if no end date
                Log::info('Number of days set to default 1 - no end date provided');
            }

            // Add user and barangay info
            $validated['user_id'] = Auth::id();
            $validated['barangay_id'] = Auth::user()->barangay_id;

            // NEW: Add program status (active by default)
            $validated['status'] = 'active';

            // Log final data before creation
            Log::info('Final data before program creation:', $validated);

            // Create the program
            $program = Program::create($validated);

            // Log for debugging
            Log::info('Program created successfully', [
                'program_id' => $program->id,
                'event_date' => $program->event_date,
                'event_end_date' => $program->event_end_date,
                'number_of_days' => $program->number_of_days,
                'status' => $program->status
            ]);

            Log::info('=== PROGRAM STORE REQUEST END ===');

            return response()->json([
                'success' => true,
                'message' => 'Program created successfully!',
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'event_date' => $program->event_date,
                    'event_end_date' => $program->event_end_date,
                    'number_of_days' => $program->number_of_days,
                    'status' => $program->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating program: ' . $e->getMessage());
            Log::error('Request data: ', $request->all());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error creating program: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display program details for modal (JSON response) - UPDATED with end date
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'message' => 'Please log in to view program details'
                ], 401);
            }

            $programId = (int)$id;
            
            Log::info('Fetching program details', [
                'program_id' => $programId,
                'user_id' => $user->id,
                'user_barangay_id' => $user->barangay_id
            ]);
            
            $program = Program::where('id', $programId)
                        ->where('barangay_id', $user->barangay_id)
                        ->first();
            
            if (!$program) {
                Log::warning('Program not found or access denied', [
                    'program_id' => $programId,
                    'user_barangay_id' => $user->barangay_id
                ]);
                return response()->json([
                    'error' => 'Program not found',
                    'message' => 'The program you are looking for does not exist or you do not have access to it.'
                ], 404);
            }

            Log::info('Program found', ['program_title' => $program->title]);

            // Build response data safely with null checks
            $responseData = [
                'id' => $program->id,
                'title' => $program->title ?? 'No Title',
                'description' => $program->description ?? 'No description available.',
                'event_date' => $program->event_date ? Carbon::parse($program->event_date)->format('Y-m-d') : null,
                'event_end_date' => $program->event_end_date ? Carbon::parse($program->event_end_date)->format('Y-m-d') : null,
                'event_time' => $program->event_time ?? null,
                'location' => $program->location ?? 'Location not specified',
                'category' => $program->category ?? 'Uncategorized',
                'published_by' => $program->published_by ?? 'Unknown',
                'registration_type' => $program->registration_type ?? 'create',
                'link_source' => $program->link_source ?? null,
                'registration_title' => $program->registration_title ?? null,
                'registration_description' => $program->registration_description ?? null,
                'registration_open_date' => $program->registration_open_date ? Carbon::parse($program->registration_open_date)->format('Y-m-d') : null,
                'registration_open_time' => $program->registration_open_time ?? null,
                'registration_close_date' => $program->registration_close_date ? Carbon::parse($program->registration_close_date)->format('Y-m-d') : null,
                'registration_close_time' => $program->registration_close_time ?? null,
                'number_of_days' => $program->number_of_days ?? 1,
                'status' => $program->status ?? 'active',
                'ended_at' => $program->ended_at ? Carbon::parse($program->ended_at)->format('Y-m-d H:i:s') : null,
                'barangay_id' => $program->barangay_id,
                'custom_fields' => [],
            ];

            // Safely handle custom_fields JSON
            try {
                if ($program->custom_fields) {
                    $customFields = json_decode($program->custom_fields, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $responseData['custom_fields'] = $customFields;
                    } else {
                        Log::warning('Invalid JSON in custom_fields', [
                            'program_id' => $program->id,
                            'custom_fields' => $program->custom_fields
                        ]);
                        $responseData['custom_fields'] = [];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error parsing custom_fields JSON: ' . $e->getMessage());
                $responseData['custom_fields'] = [];
            }

            // Safely handle display_image URL
            try {
                if ($program->display_image) {
                    if (Storage::disk('public')->exists($program->display_image)) {
                        $responseData['display_image'] = asset('storage/' . $program->display_image);
                    } else {
                        Log::warning('Program image not found in storage', [
                            'program_id' => $program->id,
                            'image_path' => $program->display_image
                        ]);
                        $responseData['display_image'] = null;
                    }
                } else {
                    $responseData['display_image'] = null;
                }
            } catch (\Exception $e) {
                Log::error('Error handling display_image: ' . $e->getMessage());
                $responseData['display_image'] = null;
            }

            Log::info('Successfully built program response data');

            return response()->json($responseData);
            
        } catch (\Exception $e) {
            Log::error('Error fetching program details: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Unable to load program details. Please try again later.'
            ], 500);
        }
    }

    /**
     * Store program registration - COMPLETELY FIXED TIME COMPARISON
     */
    public function storeRegistration(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            Log::info('=== STARTING REGISTRATION PROCESS ===');
            Log::info('User ID: ' . $user->id);
            
            $validated = $request->validate([
                'program_id' => 'required|exists:programs,id',
                'registration_data' => 'required|json',
            ]);

            Log::info('Program ID: ' . $validated['program_id']);

            // Check if program exists and belongs to user's barangay
            $program = Program::where('id', $validated['program_id'])
                            ->where('barangay_id', $user->barangay_id)
                            ->first();

            if (!$program) {
                Log::warning('Program not found or not in barangay', [
                    'program_id' => $validated['program_id'],
                    'user_barangay' => $user->barangay_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found or not available in your barangay.'
                ], 404);
            }

            // NEW: Check if program is ended
            if ($program->status === 'ended') {
                Log::warning('Program has ended', [
                    'program_id' => $program->id,
                    'program_title' => $program->title
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'This program has ended. Registration is no longer available.'
                ], 400);
            }

            Log::info('Program found: ' . $program->title);

            // Check if user already registered for this program
            $existingRegistration = ProgramRegistration::where('program_id', $program->id)
                                                    ->where('user_id', $user->id)
                                                    ->first();

            if ($existingRegistration) {
                Log::warning('User already registered', [
                    'user_id' => $user->id,
                    'program_id' => $program->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'You have already registered for this program.'
                ], 409);
            }

            // FIXED: SIMPLIFIED registration period checking
            if ($program->registration_type === 'create') {
                $now = Carbon::now();
                
                Log::info('=== REGISTRATION PERIOD CHECK ===');
                Log::info('Current server time: ' . $now->toDateTimeString());
                Log::info('Current timezone: ' . $now->timezoneName);

                // Check registration open time - SIMPLIFIED APPROACH
                if ($program->registration_open_date && $program->registration_open_time) {
                    try {
                        // Create datetime string and parse it directly
                        $openDateTimeString = $program->registration_open_date . ' ' . $program->registration_open_time;
                        $registrationOpen = Carbon::parse($openDateTimeString);
                        
                        Log::info('Registration opens at: ' . $registrationOpen->toDateTimeString());
                        Log::info('Current time: ' . $now->toDateTimeString());
                        Log::info('Time difference (minutes): ' . $now->diffInMinutes($registrationOpen, false));
                        
                        if ($now->lt($registrationOpen)) {
                            $timeUntilOpen = $now->diffForHumans($registrationOpen, ['syntax' => Carbon::DIFF_RELATIVE_TO_NOW]);
                            return response()->json([
                                'success' => false,
                                'message' => 'Registration has not opened yet. It opens on ' . $registrationOpen->format('M j, Y g:i A') . ' (' . $timeUntilOpen . ')'
                            ], 400);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error parsing registration open datetime: ' . $e->getMessage());
                        Log::error('Open date: ' . $program->registration_open_date);
                        Log::error('Open time: ' . $program->registration_open_time);
                        Log::error('Stack trace: ' . $e->getTraceAsString());
                        // Continue with registration if there's an error parsing dates
                        Log::warning('Continuing registration despite date parsing error');
                    }
                } else {
                    Log::info('No registration open date/time specified, allowing registration');
                }

                // Check registration close time - SIMPLIFIED APPROACH
                if ($program->registration_close_date && $program->registration_close_time) {
                    try {
                        // Create datetime string and parse it directly
                        $closeDateTimeString = $program->registration_close_date . ' ' . $program->registration_close_time;
                        $registrationClose = Carbon::parse($closeDateTimeString);
                        
                        Log::info('Registration closes at: ' . $registrationClose->toDateTimeString());
                        Log::info('Current time: ' . $now->toDateTimeString());
                        Log::info('Time difference (minutes): ' . $now->diffInMinutes($registrationClose, false));
                        
                        if ($now->gt($registrationClose)) {
                            $timeSinceClose = $now->diffForHumans($registrationClose, ['syntax' => Carbon::DIFF_RELATIVE_TO_NOW]);
                            return response()->json([
                                'success' => false,
                                'message' => 'Registration has closed. It closed on ' . $registrationClose->format('M j, Y g:i A') . ' (' . $timeSinceClose . ')'
                            ], 400);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error parsing registration close datetime: ' . $e->getMessage());
                        Log::error('Close date: ' . $program->registration_close_date);
                        Log::error('Close time: ' . $program->registration_close_time);
                        Log::error('Stack trace: ' . $e->getTraceAsString());
                        // Continue with registration if there's an error parsing dates
                        Log::warning('Continuing registration despite date parsing error');
                    }
                } else {
                    Log::info('No registration close date/time specified, allowing registration');
                }
                
                Log::info('=== REGISTRATION PERIOD CHECK PASSED ===');
            }

            // Generate reference ID
            $referenceId = 'PROG-' . strtoupper(uniqid());

            // Parse and enhance registration data
            $registrationData = json_decode($validated['registration_data'], true);
            
            Log::info('Original registration data:', $registrationData);

            // Enhanced registration data structure
            $enhancedRegistrationData = [
                'user_profile' => [
                    'full_name' => $user->given_name . ' ' . 
                                  ($user->middle_name ? $user->middle_name . ' ' : '') . 
                                  $user->last_name . 
                                  ($user->suffix ? ' ' . $user->suffix : ''),
                    'email' => $user->email,
                    'contact_no' => $user->contact_no,
                    'age' => $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : null,
                    'barangay' => $user->barangay->name ?? 'N/A',
                    'user_id' => $user->id
                ],
                'custom_fields' => $registrationData['custom_fields'] ?? [],
                'program_details' => [
                    'program_id' => $program->id,
                    'program_title' => $program->title,
                    'program_category' => $program->category,
                    'program_status' => $program->status
                ],
                'submitted_at' => Carbon::now()->toDateTimeString(),
                'registration_id' => $referenceId
            ];

            Log::info('Enhanced registration data prepared');

            // Create registration with enhanced data
            $registration = ProgramRegistration::create([
                'program_id' => $program->id,
                'user_id' => $user->id,
                'reference_id' => $referenceId,
                'registration_data' => json_encode($enhancedRegistrationData),
                'status' => 'registered'
            ]);

            Log::info('Registration created successfully with ID: ' . $registration->id);

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
            Log::error('Error submitting registration: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
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
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    
                    return [
                        'id' => $registration->id,
                        'reference_id' => $registration->reference_id,
                        'program_title' => $registration->program->title,
                        'program_status' => $registration->program->status ?? 'active',
                        'status' => $registration->status,
                        'submitted_at' => $registration->created_at->format('M j, Y g:i A'),
                        'program_date' => $registration->program->event_date ? Carbon::parse($registration->program->event_date)->format('M j, Y') : 'TBA',
                        'registration_data' => $registrationData,
                        'user_profile' => $registrationData['user_profile'] ?? []
                    ];
                });

            return response()->json([
                'success' => true,
                'registrations' => $registrations
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching user registrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations'
            ], 500);
        }
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        
        if ($program->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        $barangay = Barangay::find($user->barangay_id);
        
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age;
        }
        
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        return view('programs.edit', compact('program', 'user', 'barangay', 'age', 'roleBadge'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);
            
            if ($program->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            Log::info('=== PROGRAM UPDATE REQUEST START ===');
            Log::info('Update request data:', $request->all());

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_end_date' => 'nullable|date|after_or_equal:event_date',
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

                // Store custom fields as JSON - UPDATED
                if ($request->has('custom_fields')) {
                    $validated['custom_fields'] = $this->parseCustomFields($request);
                }
            } else {
                $validated['registration_title'] = null;
                $validated['registration_description'] = null;
                $validated['registration_open_date'] = null;
                $validated['registration_open_time'] = null;
                $validated['registration_close_date'] = null;
                $validated['registration_close_time'] = null;
                $validated['custom_fields'] = null;
            }

            // FIXED: Calculate number_of_days based on start and end dates
            if (!empty($validated['event_end_date'])) {
                $startDate = Carbon::parse($validated['event_date']);
                $endDate = Carbon::parse($validated['event_end_date']);
                $validated['number_of_days'] = $startDate->diffInDays($endDate) + 1;
                Log::info('Number of days calculated for update:', [
                    'start_date' => $validated['event_date'],
                    'end_date' => $validated['event_end_date'],
                    'number_of_days' => $validated['number_of_days']
                ]);
            } else {
                $validated['number_of_days'] = 1;
                Log::info('Number of days set to default 1 for update');
            }

            // Keep existing status if not provided
            if (!isset($validated['status'])) {
                $validated['status'] = $program->status;
            }

            $program->update($validated);

            Log::info('Program updated successfully', [
                'program_id' => $program->id,
                'event_date' => $program->event_date,
                'event_end_date' => $program->event_end_date,
                'number_of_days' => $program->number_of_days,
                'status' => $program->status
            ]);

            Log::info('=== PROGRAM UPDATE REQUEST END ===');

            return response()->json([
                'success' => true, 
                'message' => 'Program updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating program: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating program: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * NEW: End a program
     */
    public function endProgram(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $program = Program::where('id', $id)
                ->where('barangay_id', $user->barangay_id)
                ->first();

            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found or you do not have permission to end it.'
                ], 404);
            }

            // Check if user is the program creator or SK official
            if ($program->user_id !== $user->id && !in_array($user->role, ['sk', 'sk_chairperson'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to end this program.'
                ], 403);
            }

            // Validate request
            $validated = $request->validate([
                'reason' => 'nullable|string|max:500',
                'end_date' => 'nullable|date|after_or_equal:event_date',
                'notify_participants' => 'boolean'
            ]);

            // Update program status
            $program->status = 'ended';
            $program->ended_at = Carbon::now();
            $program->end_reason = $validated['reason'] ?? null;
            
            // If end_date is provided, update event_end_date
            if (!empty($validated['end_date'])) {
                $program->event_end_date = $validated['end_date'];
                
                // Recalculate number of days
                $startDate = Carbon::parse($program->event_date);
                $endDate = Carbon::parse($validated['end_date']);
                $program->number_of_days = $startDate->diffInDays($endDate) + 1;
            } elseif (!$program->event_end_date) {
                // If no end date was set, set it to today
                $program->event_end_date = Carbon::today();
            }
            
            $program->save();

            Log::info('Program ended successfully', [
                'program_id' => $program->id,
                'ended_by' => $user->id,
                'ended_at' => $program->ended_at,
                'end_reason' => $program->end_reason
            ]);

            // If notify_participants is true, send notifications to registrants
            if ($validated['notify_participants'] ?? false) {
                $this->notifyParticipantsProgramEnded($program, $user);
            }

            return response()->json([
                'success' => true,
                'message' => 'Program has been ended successfully.',
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'status' => $program->status,
                    'ended_at' => $program->ended_at->format('M j, Y g:i A'),
                    'event_end_date' => $program->event_end_date ? Carbon::parse($program->event_end_date)->format('M j, Y') : null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error ending program: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error ending program: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * NEW: Reactivate a program
     */
    public function reactivateProgram($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $program = Program::where('id', $id)
                ->where('barangay_id', $user->barangay_id)
                ->first();

            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found or you do not have permission to reactivate it.'
                ], 404);
            }

            // Check if user is the program creator or SK official
            if ($program->user_id !== $user->id && !in_array($user->role, ['sk', 'sk_chairperson'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to reactivate this program.'
                ], 403);
            }

            // Update program status
            $program->status = 'active';
            $program->ended_at = null;
            $program->end_reason = null;
            $program->save();

            Log::info('Program reactivated successfully', [
                'program_id' => $program->id,
                'reactivated_by' => $user->id,
                'reactivated_at' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Program has been reactivated successfully.',
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'status' => $program->status,
                    'event_date' => $program->event_date,
                    'event_end_date' => $program->event_end_date
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error reactivating program: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error reactivating program: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * NEW: Check if program can be ended
     */
    public function canEndProgram($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $program = Program::where('id', $id)
                ->where('barangay_id', $user->barangay_id)
                ->first();

            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found'
                ], 404);
            }

            $canEnd = false;
            $reasons = [];

            // Check if user has permission
            if ($program->user_id === $user->id || in_array($user->role, ['sk', 'sk_chairperson'])) {
                $canEnd = true;
            }

            // Check if program is already ended
            if ($program->status === 'ended') {
                $reasons[] = 'Program is already ended.';
            }

            // Check if program has passed its end date
            if ($program->event_end_date && Carbon::parse($program->event_end_date)->lt(Carbon::now())) {
                $reasons[] = 'Program has already passed its scheduled end date.';
            }

            return response()->json([
                'success' => true,
                'can_end' => $canEnd,
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'status' => $program->status,
                    'event_date' => $program->event_date,
                    'event_end_date' => $program->event_end_date,
                    'user_id' => $program->user_id
                ],
                'reasons' => $reasons,
                'user_permission' => $program->user_id === $user->id || in_array($user->role, ['sk', 'sk_chairperson'])
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking if program can be ended: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking program status'
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);
            
            if ($program->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            if ($program->display_image) {
                Storage::disk('public')->delete($program->display_image);
            }

            $program->delete();

            return response()->json([
                'success' => true, 
                'message' => 'Program deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting program: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get active programs (not ended)
        $programs = Program::where('barangay_id', $user->barangay_id)
                          ->where('status', '!=', 'ended')
                          ->with('user')
                          ->latest()
                          ->get();

        // Get ended programs separately for display
        $endedPrograms = Program::where('barangay_id', $user->barangay_id)
                              ->where('status', 'ended')
                              ->with('user')
                              ->latest('ended_at')
                              ->get();

        $barangay = Barangay::find($user->barangay_id);
        
        $age = null;
        if ($user->date_of_birth) {
            $birthdate = Carbon::parse($user->date_of_birth);
            $age = $birthdate->age;
        }
        
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        return view('programs.index', compact('programs', 'endedPrograms', 'user', 'barangay', 'age', 'roleBadge'));
    }

    /**
     * Get program registrations for youth registration page - ENHANCED VERSION
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
                    
                    // Get all registration data including enhanced user profile and custom fields
                    $registrationData = $registration->registration_data ? json_decode($registration->registration_data, true) : [];
                    $userProfile = $registrationData['user_profile'] ?? [];
                    $customFields = $registrationData['custom_fields'] ?? [];
                    
                    return [
                        'id' => $registration->id,
                        'reference_id' => $registration->reference_id,
                        'user_name' => $userProfile['full_name'] ?? 
                                      $registration->user->given_name . ' ' . 
                                      ($registration->user->middle_name ? $registration->user->middle_name . ' ' : '') . 
                                      $registration->user->last_name . 
                                      ($registration->user->suffix ? ' ' . $registration->user->suffix : ''),
                        'email' => $userProfile['email'] ?? $registration->user->email,
                        'contact_no' => $userProfile['contact_no'] ?? $registration->user->contact_no,
                        'age' => $userProfile['age'] ?? $age,
                        'barangay' => $userProfile['barangay'] ?? ($registration->user->barangay->name ?? 'N/A'),
                        'status' => $registration->status,
                        'registered_at' => $registration->created_at->format('M d, Y g:i A'),
                        'registration_data' => $registrationData,
                        'custom_fields' => $customFields,
                        'user_profile' => $userProfile
                    ];
                });

            return response()->json([
                'success' => true,
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'event_date' => $program->event_date,
                    'event_end_date' => $program->event_end_date,
                    'event_time' => $program->event_time,
                    'category' => $program->category,
                    'status' => $program->status,
                    'total_registrations' => $registrations->count()
                ],
                'registrations' => $registrations
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching program registrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FIXED: Get daily attendance for a registration - PROPERLY HANDLES JSON DECODING
     */
    public function getDailyAttendance($registrationId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $registration = ProgramRegistration::with(['program', 'user'])
                ->where('id', $registrationId)
                ->firstOrFail();
                
            // Check if program belongs to user's barangay
            if ($registration->program->barangay_id !== $user->barangay_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
            
            // FIXED: Handle attendance_days properly - check if it's already an array or needs decoding
            $attendanceDays = [];
            
            if ($registration->attendance_days) {
                // Check if it's already an array (due to Laravel's JSON casting)
                if (is_array($registration->attendance_days)) {
                    $attendanceDays = $registration->attendance_days;
                } 
                // Check if it's a string that needs decoding
                elseif (is_string($registration->attendance_days)) {
                    try {
                        $decoded = json_decode($registration->attendance_days, true);
                        $attendanceDays = is_array($decoded) ? $decoded : [];
                    } catch (\Exception $e) {
                        Log::warning('Error decoding attendance_days JSON', [
                            'registration_id' => $registration->id,
                            'attendance_days' => $registration->attendance_days,
                            'error' => $e->getMessage()
                        ]);
                        $attendanceDays = [];
                    }
                }
            }
            
            // If it's an array with numeric keys, convert to day_x format
            if (is_array($attendanceDays) && isset($attendanceDays[0])) {
                $convertedAttendance = [];
                foreach ($attendanceDays as $index => $value) {
                    $convertedAttendance["day_" . ($index + 1)] = (bool)$value;
                }
                $attendanceDays = $convertedAttendance;
            }
            
            // Get day names from program custom fields or default
            $totalDays = $registration->program->number_of_days ?? 1;
            $dayNames = [];
            
            // Try to get day names from program custom metadata
            if ($registration->program->custom_fields) {
                try {
                    $customFields = json_decode($registration->program->custom_fields, true);
                    if (isset($customFields['day_names']) && is_array($customFields['day_names'])) {
                        $dayNames = $customFields['day_names'];
                    }
                } catch (\Exception $e) {
                    Log::warning('Error parsing program custom fields for day names', [
                        'program_id' => $registration->program->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // If no custom day names, create default ones
            for ($i = 1; $i <= $totalDays; $i++) {
                $dayKey = "day_$i";
                if (!isset($dayNames[$dayKey])) {
                    $dayNames[$dayKey] = "Day $i";
                }
            }
            
            // Calculate present count
            $presentCount = count(array_filter($attendanceDays, function($attended) {
                return $attended === true || $attended === 'true' || $attended === 1 || $attended === '1';
            }));

            Log::info('Daily attendance data fetched successfully', [
                'registration_id' => $registrationId,
                'total_days' => $totalDays,
                'present_count' => $presentCount,
                'attendance_days_type' => gettype($registration->attendance_days),
                'attendance_data' => $attendanceDays
            ]);
            
            return response()->json([
                'success' => true,
                'attendance_data' => $attendanceDays,
                'day_names' => $dayNames,
                'total_days' => $totalDays,
                'present_count' => $presentCount,
                'registration' => [
                    'id' => $registration->id,
                    'reference_id' => $registration->reference_id,
                    'user_name' => $registration->user->given_name . ' ' . $registration->user->last_name,
                    'attended' => $registration->attended
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting daily attendance: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update daily attendance - FIXED VERSION
     */
    public function updateDailyAttendance(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'registration_id' => 'required|exists:program_registrations,id',
                'attendance_data' => 'required|array',
                'present_count' => 'required|integer',
                'total_days' => 'required|integer'
            ]);
            
            $registration = ProgramRegistration::with('program')
                ->where('id', $validated['registration_id'])
                ->firstOrFail();
                
            // Check if program belongs to user's barangay
            if ($registration->program->barangay_id !== $user->barangay_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
            
            // NEW: Check if program is ended
            if ($registration->program->status === 'ended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update attendance for an ended program.'
                ], 400);
            }
            
            // Ensure attendance_data is properly formatted
            $attendanceData = [];
            foreach ($validated['attendance_data'] as $day => $attended) {
                $attendanceData[$day] = filter_var($attended, FILTER_VALIDATE_BOOLEAN);
            }
            
            // FIXED: Update attendance days - Laravel will automatically encode to JSON
            $registration->attendance_days = $attendanceData;
            
            // Update overall attendance status
            $registration->attended = ($validated['present_count'] > 0);
            
            // Update attended_at if first time marking as present
            if ($validated['present_count'] > 0 && !$registration->attended_at) {
                $registration->attended_at = now();
                $registration->marked_by_user_id = $user->id;
            }
            
            // Update program days count if it has changed
            if ($validated['total_days'] != $registration->program->number_of_days) {
                $program = $registration->program;
                $program->number_of_days = $validated['total_days'];
                
                // If no end date was set, calculate it based on new number of days
                if (!$program->event_end_date) {
                    $startDate = Carbon::parse($program->event_date);
                    $program->event_end_date = $startDate->copy()->addDays($validated['total_days'] - 1);
                }
                
                $program->save();
            }
            
            $registration->save();
            
            Log::info('Daily attendance updated successfully', [
                'registration_id' => $registration->id,
                'attendance_data' => $attendanceData,
                'present_count' => $validated['present_count'],
                'marked_by_user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'data' => [
                    'present_count' => $validated['present_count'],
                    'total_days' => $validated['total_days'],
                    'attended' => $registration->attended
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating daily attendance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save day name for dynamic days
     */
    public function saveDayName(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'program_id' => 'required|exists:programs,id',
                'day_number' => 'required|integer|min:1',
                'day_name' => 'required|string|max:255'
            ]);
            
            $program = Program::where('id', $validated['program_id'])
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();
            
            // NEW: Check if program is ended
            if ($program->status === 'ended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot modify an ended program.'
                ], 400);
            }
            
            // Get current custom fields
            $customFields = [];
            if ($program->custom_fields) {
                try {
                    $customFields = json_decode($program->custom_fields, true);
                    if (!is_array($customFields)) {
                        $customFields = [];
                    }
                } catch (\Exception $e) {
                    Log::warning('Error parsing custom fields JSON', [
                        'program_id' => $program->id,
                        'error' => $e->getMessage()
                    ]);
                    $customFields = [];
                }
            }
            
            // Initialize day_names array if it doesn't exist
            if (!isset($customFields['day_names'])) {
                $customFields['day_names'] = [];
            }
            
            // Save day name
            $dayKey = "day_{$validated['day_number']}";
            $customFields['day_names'][$dayKey] = $validated['day_name'];
            
            // Update program
            $program->custom_fields = json_encode($customFields);
            $program->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Day name saved successfully',
                'day_name' => $validated['day_name'],
                'day_key' => $dayKey
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saving day name: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a day from program
     */
    public function removeDay(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'program_id' => 'required|exists:programs,id',
                'day_number' => 'required|integer|min:2' // Can't remove day 1
            ]);
            
            $program = Program::where('id', $validated['program_id'])
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();
            
            // NEW: Check if program is ended
            if ($program->status === 'ended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot modify an ended program.'
                ], 400);
            }
            
            $dayNumber = $validated['day_number'];
            $dayKey = "day_{$dayNumber}";
            
            // Update all registrations to remove this day from attendance
            $registrations = ProgramRegistration::where('program_id', $program->id)->get();
            
            foreach ($registrations as $registration) {
                if ($registration->attendance_days) {
                    // FIXED: Handle attendance_days properly
                    $attendanceData = [];
                    
                    if (is_array($registration->attendance_days)) {
                        $attendanceData = $registration->attendance_days;
                    } elseif (is_string($registration->attendance_days)) {
                        try {
                            $decoded = json_decode($registration->attendance_days, true);
                            $attendanceData = is_array($decoded) ? $decoded : [];
                        } catch (\Exception $e) {
                            Log::warning('Error decoding attendance_days when removing day', [
                                'registration_id' => $registration->id,
                                'error' => $e->getMessage()
                            ]);
                            $attendanceData = [];
                        }
                    }
                    
                    // Remove the day from attendance data
                    if (isset($attendanceData[$dayKey])) {
                        unset($attendanceData[$dayKey]);
                        
                        // Reindex days if needed (convert from associative to indexed array)
                        $isIndexed = false;
                        $newAttendanceData = [];
                        $index = 1;
                        
                        foreach ($attendanceData as $key => $value) {
                            if (strpos($key, 'day_') === 0) {
                                $newAttendanceData["day_{$index}"] = $value;
                                $index++;
                            } else {
                                $newAttendanceData[$key] = $value;
                            }
                        }
                        
                        $registration->attendance_days = $newAttendanceData;
                        
                        // Update overall attendance
                        $presentCount = count(array_filter($newAttendanceData, function($attended) {
                            return $attended === true || $attended === 'true' || $attended === 1 || $attended === '1';
                        }));
                        $registration->attended = ($presentCount > 0);
                        
                        $registration->save();
                    }
                }
            }
            
            // Update program custom fields to remove day name
            if ($program->custom_fields) {
                $customFields = [];
                try {
                    $customFields = json_decode($program->custom_fields, true);
                    if (!is_array($customFields)) {
                        $customFields = [];
                    }
                } catch (\Exception $e) {
                    Log::warning('Error parsing custom fields when removing day', [
                        'program_id' => $program->id,
                        'error' => $e->getMessage()
                    ]);
                    $customFields = [];
                }
                
                if (isset($customFields['day_names'][$dayKey])) {
                    unset($customFields['day_names'][$dayKey]);
                    
                    // Reindex day names
                    $newDayNames = [];
                    $index = 1;
                    foreach ($customFields['day_names'] as $key => $name) {
                        if (strpos($key, 'day_') === 0) {
                            $newDayNames["day_{$index}"] = $name;
                            $index++;
                        } else {
                            $newDayNames[$key] = $name;
                        }
                    }
                    $customFields['day_names'] = $newDayNames;
                    
                    $program->custom_fields = json_encode($customFields);
                }
            }
            
            // Decrease number of days
            $program->number_of_days = max(1, $program->number_of_days - 1);
            
            // Recalculate end date if no end date was set
            if (!$program->event_end_date || $program->event_end_date == $program->event_date) {
                $startDate = Carbon::parse($program->event_date);
                $program->event_end_date = $startDate->copy()->addDays($program->number_of_days - 1);
            }
            
            $program->save();
            
            return response()->json([
                'success' => true,
                'message' => "Day {$dayNumber} removed successfully",
                'new_total_days' => $program->number_of_days
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error removing day: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export registrations data
     */
    public function exportRegistrations(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'program_id' => 'required|exists:programs,id',
                'format' => 'required|in:csv,excel,pdf',
                'include_all_data' => 'boolean',
                'include_attendance' => 'boolean',
                'include_custom_fields' => 'boolean'
            ]);
            
            $program = Program::where('id', $validated['program_id'])
                ->where('barangay_id', $user->barangay_id)
                ->firstOrFail();
            
            $registrations = ProgramRegistration::with(['user', 'user.barangay'])
                ->where('program_id', $program->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Prepare data based on format
            $data = [];
            
            foreach ($registrations as $registration) {
                // FIXED: Handle registration_data properly
                $registrationData = [];
                if ($registration->registration_data) {
                    try {
                        $registrationData = json_decode($registration->registration_data, true);
                        if (!is_array($registrationData)) {
                            $registrationData = [];
                        }
                    } catch (\Exception $e) {
                        Log::warning('Error parsing registration_data for export', [
                            'registration_id' => $registration->id,
                            'error' => $e->getMessage()
                        ]);
                        $registrationData = [];
                    }
                }
                
                $row = [
                    'Reference ID' => $registration->reference_id,
                    'Full Name' => $registrationData['user_profile']['full_name'] ?? 
                        $registration->user->given_name . ' ' . $registration->user->last_name,
                    'Email' => $registrationData['user_profile']['email'] ?? $registration->user->email,
                    'Contact Number' => $registrationData['user_profile']['contact_no'] ?? $registration->user->contact_no,
                    'Age' => $registrationData['user_profile']['age'] ?? 
                        ($registration->user->date_of_birth ? 
                            Carbon::parse($registration->user->date_of_birth)->age : null),
                    'Barangay' => $registrationData['user_profile']['barangay'] ?? 
                        ($registration->user->barangay->name ?? 'N/A'),
                    'Registered At' => $registration->created_at->format('Y-m-d H:i:s'),
                    'Overall Attendance' => $registration->attended ? 'Present' : 'Absent',
                    'Program Status' => $program->status,
                ];
                
                // Add attendance data if requested
                if ($validated['include_attendance'] && $registration->attendance_days) {
                    // FIXED: Handle attendance_days properly
                    $attendanceDays = [];
                    
                    if (is_array($registration->attendance_days)) {
                        $attendanceDays = $registration->attendance_days;
                    } elseif (is_string($registration->attendance_days)) {
                        try {
                            $decoded = json_decode($registration->attendance_days, true);
                            $attendanceDays = is_array($decoded) ? $decoded : [];
                        } catch (\Exception $e) {
                            Log::warning('Error decoding attendance_days for export', [
                                'registration_id' => $registration->id,
                                'error' => $e->getMessage()
                            ]);
                            $attendanceDays = [];
                        }
                    }
                    
                    $totalDays = $program->number_of_days;
                    
                    // Get day names from program
                    $dayNames = [];
                    if ($program->custom_fields) {
                        try {
                            $customFields = json_decode($program->custom_fields, true);
                            $dayNames = $customFields['day_names'] ?? [];
                        } catch (\Exception $e) {
                            Log::warning('Error parsing custom fields for day names in export', [
                                'program_id' => $program->id,
                                'error' => $e->getMessage()
                            ]);
                            $dayNames = [];
                        }
                    }
                    
                    for ($i = 1; $i <= $totalDays; $i++) {
                        $dayKey = "day_$i";
                        $dayName = $dayNames[$dayKey] ?? "Day $i";
                        $isPresent = $attendanceDays[$dayKey] ?? false;
                        $row[$dayName] = $isPresent ? 'Present' : 'Absent';
                    }
                    
                    $row['Days Attended'] = count(array_filter($attendanceDays, function($attended) {
                        return $attended === true || $attended === 'true' || $attended === 1 || $attended === '1';
                    })) . "/$totalDays";
                }
                
                // Add custom fields if requested
                if ($validated['include_custom_fields'] && isset($registrationData['custom_fields'])) {
                    foreach ($registrationData['custom_fields'] as $index => $field) {
                        if (is_array($field)) {
                            $fieldLabel = $field['label'] ?? "Field $index";
                            $fieldValue = $field['answer'] ?? '';
                            $row[$fieldLabel] = $fieldValue;
                        }
                    }
                }
                
                $data[] = $row;
            }
            
            // For now, return the data structure
            // In a real implementation, you would generate the actual file
            return response()->json([
                'success' => true,
                'message' => 'Export data prepared successfully',
                'data' => $data,
                'program' => [
                    'title' => $program->title,
                    'event_date' => $program->event_date,
                    'status' => $program->status,
                    'total_registrations' => count($registrations)
                ],
                'download_url' => '#', // Placeholder for actual download URL
                'file_name' => "program_registrations_{$program->id}_{$validated['format']}_" . date('Ymd_His') . ".{$validated['format']}"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error exporting registrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * NEW: Helper function to notify participants when program ends
     */
    private function notifyParticipantsProgramEnded(Program $program, User $endedBy)
    {
        try {
            $registrations = ProgramRegistration::where('program_id', $program->id)
                ->with('user')
                ->get();
            
            foreach ($registrations as $registration) {
                // Create notification for each registrant
                // This would typically be done through Laravel's notification system
                // For now, we'll just log it
                Log::info('Program ended notification would be sent to user', [
                    'program_id' => $program->id,
                    'program_title' => $program->title,
                    'user_id' => $registration->user_id,
                    'ended_by' => $endedBy->id,
                    'ended_at' => $program->ended_at
                ]);
                
                // In a real implementation, you would:
                // 1. Create a notification record in the database
                // 2. Send email notification
                // 3. Send in-app notification
            }
            
            return count($registrations);
            
        } catch (\Exception $e) {
            Log::error('Error notifying participants: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * NEW: Get programs that can be ended (for SK officials)
     */
    public function getEndablePrograms(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get programs that are active and can be ended
            $programs = Program::where('barangay_id', $user->barangay_id)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->whereNull('event_end_date')
                          ->orWhere('event_end_date', '>=', Carbon::now());
                })
                ->with('user')
                ->orderBy('event_date', 'desc')
                ->get()
                ->map(function($program) use ($user) {
                    return [
                        'id' => $program->id,
                        'title' => $program->title,
                        'event_date' => $program->event_date,
                        'event_end_date' => $program->event_end_date,
                        'status' => $program->status,
                        'created_by' => $program->user->given_name . ' ' . $program->user->last_name,
                        'can_end' => $program->user_id === $user->id || in_array($user->role, ['sk', 'sk_chairperson']),
                        'has_end_date' => !empty($program->event_end_date)
                    ];
                });

            return response()->json([
                'success' => true,
                'programs' => $programs,
                'total' => $programs->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching endable programs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching programs'
            ], 500);
        }
    }

    /**
     * NEW: Get ended programs
     */
    public function getEndedPrograms(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $programs = Program::where('barangay_id', $user->barangay_id)
                ->where('status', 'ended')
                ->with('user')
                ->orderBy('ended_at', 'desc')
                ->get()
                ->map(function($program) {
                    return [
                        'id' => $program->id,
                        'title' => $program->title,
                        'event_date' => $program->event_date,
                        'event_end_date' => $program->event_end_date,
                        'ended_at' => $program->ended_at ? Carbon::parse($program->ended_at)->format('M d, Y g:i A') : null,
                        'end_reason' => $program->end_reason,
                        'created_by' => $program->user->given_name . ' ' . $program->user->last_name
                    ];
                });

            return response()->json([
                'success' => true,
                'programs' => $programs,
                'total' => $programs->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching ended programs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching ended programs'
            ], 500);
        }
    }

    /**
     * FIXED: Improved time conversion method with better error handling
     */
    private function convertTimeTo24Hour($timeString)
    {
        try {
            Log::info('Converting time to 24-hour: ' . $timeString);
            
            // If already in 24-hour format, return as is
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeString)) {
                Log::info('Already in 24-hour format: ' . $timeString);
                return $timeString;
            }
            
            // If in 12-hour format with AM/PM, convert to 24-hour
            if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i', $timeString, $matches)) {
                $hour = (int)$matches[1];
                $minute = $matches[2];
                $period = strtoupper($matches[3]);
                
                if ($period === 'PM' && $hour < 12) {
                    $hour += 12;
                } elseif ($period === 'AM' && $hour == 12) {
                    $hour = 0;
                }
                
                $result = sprintf('%02d:%02d:00', $hour, $minute);
                Log::info('Converted 12-hour to 24-hour: ' . $timeString . ' -> ' . $result);
                return $result;
            }
            
            // If just hours and minutes, add seconds
            if (preg_match('/^\d{1,2}:\d{2}$/', $timeString)) {
                $result = $timeString . ':00';
                Log::info('Added seconds to time: ' . $timeString . ' -> ' . $result);
                return $result;
            }
            
            // Default: try to parse with Carbon and return in 24-hour format
            $carbonTime = Carbon::parse($timeString);
            $result = $carbonTime->format('H:i:s');
            Log::info('Parsed with Carbon and formatted: ' . $timeString . ' -> ' . $result);
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Error converting time: ' . $e->getMessage());
            Log::error('Time string that failed: ' . $timeString);
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return '00:00:00'; // Fallback
        }
    }

    /**
     * Parse custom fields from form data
     */
    private function parseCustomFields($request)
    {
        $customFields = [];
        
        if ($request->has('custom_fields') && !empty($request->custom_fields)) {
            try {
                $fieldsData = json_decode($request->custom_fields, true);
                
                if (is_array($fieldsData)) {
                    foreach ($fieldsData as $field) {
                        if (!empty($field['label']) && !empty($field['type'])) {
                            $customFields[] = [
                                'type' => $field['type'],
                                'label' => $field['label'],
                                'required' => $field['required'] ?? false,
                                'options' => $field['options'] ?? null,
                                'field_id' => 'field_' . uniqid()
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error parsing custom fields: ' . $e->getMessage());
            }
        }
        
        return json_encode($customFields);
    }
}