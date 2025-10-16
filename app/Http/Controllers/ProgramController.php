<?php
// app/Http/Controllers/ProgramController.php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Barangay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        
        // Calculate age if birthdate exists
        $age = null;
        if ($user->date_of_birth) {
            $age = now()->diffInYears($user->date_of_birth);
        }
        
        // Set role badge
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';
        
        return view('create-program', compact('user', 'barangay', 'age', 'roleBadge'));
    }

    public function store(Request $request)
    {
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
            'registration_description' => 'nullable|string|required_if:registration_type,create',
            'registration_open_date' => 'nullable|date|required_if:registration_type,create',
            'registration_open_time' => 'nullable|required_if:registration_type,create',
            'registration_close_date' => 'nullable|date|required_if:registration_type,create',
            'registration_close_time' => 'nullable|required_if:registration_type,create',
        ]);

        // Handle file upload
        if ($request->hasFile('display_image')) {
            $imagePath = $request->file('display_image')->store('programs', 'public');
            $validated['display_image'] = $imagePath;
        }

        // Convert time format to proper time format for database
        $validated['event_time'] = $this->convertTimeTo24Hour($validated['event_time']);

        if ($validated['registration_type'] === 'create') {
            if (!empty($validated['registration_open_time'])) {
                $validated['registration_open_time'] = $this->convertTimeTo24Hour($validated['registration_open_time']);
            }
            if (!empty($validated['registration_close_time'])) {
                $validated['registration_close_time'] = $this->convertTimeTo24Hour($validated['registration_close_time']);
            }
        } else {
            // If registration type is link, set create registration fields to null
            $validated['registration_description'] = null;
            $validated['registration_open_date'] = null;
            $validated['registration_open_time'] = null;
            $validated['registration_close_date'] = null;
            $validated['registration_close_time'] = null;
        }

        // Add user and barangay info
        $validated['user_id'] = Auth::id();
        $validated['barangay_id'] = Auth::user()->barangay_id;

        // Create the program
        Program::create($validated);

        return response()->json(['success' => true, 'message' => 'Program created successfully']);
    }

    public function show($id)
    {
        $program = Program::with('user', 'barangay')->findOrFail($id);
        return view('programs.show', compact('program'));
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
        $age = $user->date_of_birth ? now()->diffInYears($user->date_of_birth) : null;
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        return view('programs.edit', compact('program', 'user', 'barangay', 'age', 'roleBadge'));
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);
        
        // Check if user owns the program
        if ($program->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
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
            'registration_description' => 'nullable|string|required_if:registration_type,create',
            'registration_open_date' => 'nullable|date|required_if:registration_type,create',
            'registration_open_time' => 'nullable|required_if:registration_type,create',
            'registration_close_date' => 'nullable|date|required_if:registration_type,create',
            'registration_close_time' => 'nullable|required_if:registration_type,create',
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
        } else {
            // If registration type is link, set create registration fields to null
            $validated['registration_description'] = null;
            $validated['registration_open_date'] = null;
            $validated['registration_open_time'] = null;
            $validated['registration_close_date'] = null;
            $validated['registration_close_time'] = null;
        }

        $program->update($validated);

        return response()->json(['success' => true, 'message' => 'Program updated successfully']);
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        
        // Check if user owns the program
        if ($program->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated image
        if ($program->display_image) {
            Storage::disk('public')->delete($program->display_image);
        }

        $program->delete();

        return response()->json(['success' => true, 'message' => 'Program deleted successfully']);
    }

    public function index()
    {
        $user = Auth::user();
        $programs = Program::where('barangay_id', $user->barangay_id)
                          ->with('user')
                          ->latest()
                          ->get();

        $barangay = Barangay::find($user->barangay_id);
        $age = $user->date_of_birth ? now()->diffInYears($user->date_of_birth) : null;
        $roleBadge = $user->role === 'sk' ? 'SK Member' : 'KK Member';

        return view('programs.index', compact('programs', 'user', 'barangay', 'age', 'roleBadge'));
    }

    private function convertTimeTo24Hour($timeString)
    {
        return date('H:i:s', strtotime($timeString));
    }
}