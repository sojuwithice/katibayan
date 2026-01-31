<?php

// app/Http/Controllers/ReportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportFolder;
use App\Models\ReportFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Kunin ang User
        $user = Auth::user();

        // 2. Role Badge Logic
        $roleBadge = $user->role ? strtoupper($user->role) . '-Member' : 'SK-Member';
        if ($user->role === 'admin') {
            $roleBadge = 'SK-Chairperson';
        }

        // 3. Age Logic
        $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 'N/A';

        // 4. Kunin ang Folders at Files
        $folders = ReportFolder::where('user_id', Auth::id())->get();
        $files = ReportFile::where('user_id', Auth::id())->get();

        // --- NEW LOGIC: DETERMINE REAL OWNER (FULL NAME) ---
        foreach ($files as $file) {
            
            if (preg_match('/^\[.*?\] - (.*?) \(.*?\)/', $file->name, $matches)) {
                // Trim para tanggalin ang extra spaces sa gilid
                $file->uploaded_by = trim($matches[1]); 
            } else {
                // Fallback: Full Name ng naka-login
                $file->uploaded_by = $user->given_name . ' ' . $user->last_name; 
            }
        }
        // ----------------------------------------

        return view('reports', compact('folders', 'files', 'user', 'roleBadge', 'age'));
    }

    // 2. Create Folder
    public function storeFolder(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $folder = ReportFolder::create([
            'name' => $request->name,
            'color' => $this->getRandomColor(),
            'user_id' => Auth::id()
        ]);

        return response()->json($folder);
    }

    // 3. Upload File
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'folder_id' => 'required|exists:report_folders,id'
        ]);

        $uploadedFile = $request->file('file');
        $filename = $uploadedFile->getClientOriginalName();
        
        // I-save sa storage folder (storage/app/public/reports)
        $path = $uploadedFile->storeAs('reports', time() . '_' . $filename, 'public');

        $file = ReportFile::create([
            'folder_id' => $request->folder_id,
            'name' => $filename,
            'path' => $path,
            'type' => $this->getFileType($filename),
            'size' => $this->formatSize($uploadedFile->getSize()),
            'user_id' => Auth::id()
        ]);

        return response()->json($file);
    }

    // 4. Delete File/Folder
    public function destroy($type, $id)
    {
        if ($type === 'folder') {
            $folder = ReportFolder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            // Delete physical files inside folder first
            foreach($folder->files as $file) {
                Storage::disk('public')->delete($file->path);
            }
            $folder->delete(); // Cascading delete sa DB migration handles the rows
        } else {
            $file = ReportFile::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            Storage::disk('public')->delete($file->path);
            $file->delete();
        }

        return response()->json(['success' => true]);
    }
    
    // 5. Download File
    public function download($id)
    {
        $file = ReportFile::findOrFail($id);
        return Storage::download('public/' . $file->path, $file->name);
    }

    // Helpers (Galing sa JS logic mo dati, nilipat lang sa PHP)
    private function getRandomColor() {
        $colors = ['#4a6cf7', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6b7280'];
        return $colors[array_rand($colors)];
    }

    private function getFileType($filename) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $types = ['pdf'=>'PDF', 'doc'=>'Document', 'docx'=>'Document', 'xls'=>'Spreadsheet', 'xlsx'=>'Spreadsheet', 'jpg'=>'Image', 'png'=>'Image'];
        return $types[$ext] ?? 'Document';
    }

    private function formatSize($bytes) {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    // Idagdag ito sa pinaka-baba ng controller class, bago ang closing "}"
    public function viewFile($id)
    {
        $file = ReportFile::findOrFail($id);
        
        // Hahanapin ang file sa storage folder
        $path = storage_path('app/public/' . $file->path);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        // Return the file content to the browser (Preview)
        return response()->file($path);
    }


public function createBackup($id)
{
    $file = ReportFile::findOrFail($id);
    
    // 1. Generate new name
    $ext = pathinfo($file->name, PATHINFO_EXTENSION);
    $filenameOnly = pathinfo($file->name, PATHINFO_FILENAME);
    $newName = $filenameOnly . ' - Backup.' . $ext;
    
    // 2. Physical Copy
    $newPath = 'reports/' . time() . '_' . $newName;
    \Illuminate\Support\Facades\Storage::disk('public')->copy($file->path, $newPath);

    // 3. Database Entry
    $newFile = ReportFile::create([
        'folder_id' => $file->folder_id, // Same folder
        'name' => $newName,
        'path' => $newPath,
        'type' => $file->type,
        'size' => $file->size,
        'user_id' => \Illuminate\Support\Facades\Auth::id()
    ]);

    return response()->json($newFile);
}

public function archiveFile($id)
{
    $file = ReportFile::findOrFail($id);
    
    // 1. Find or Create "Archives" folder
    $archiveFolder = ReportFolder::firstOrCreate(
        ['name' => 'Archives', 'user_id' => \Illuminate\Support\Facades\Auth::id()],
        ['color' => '#6b7280'] // Gray color for archives
    );

    // 2. Move file to that folder
    $file->folder_id = $archiveFolder->id;
    $file->save();

    return response()->json(['success' => true]);
}

public function renameItem(Request $request, $type, $id)
{
    $request->validate(['name' => 'required|string|max:255']);

    if ($type === 'folder') {
        $item = ReportFolder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    } else {
        $item = ReportFile::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    }

    $item->name = $request->name;
    $item->save();

    return response()->json(['success' => true]);
}

public function submitReport(Request $request)
    {
        // 1. Validation
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:20480', 
            'report_type' => 'required|string',
        ]);

        // A. KUNIN ANG SENDER
        $sender = Auth::user();
        
        if (!$sender->barangay_id) {
             return response()->json([
                'success' => false, 
                'message' => 'Error: Your account does not have a designated Barangay ID.'
            ], 400);
        }

    
        $skOfficials = User::where('barangay_id', $sender->barangay_id)
                           ->where('role', 'sk') 
                           ->get();

        $recipient = null;

        // Step 2: Hanapin kung sino sa kanila ang Chairperson (base sa sk_role)
        $recipient = $skOfficials->first(function ($user) {
            return stripos($user->sk_role, 'chair') !== false; 
        });

        if (!$recipient && $skOfficials->count() > 0) {
            $recipient = $skOfficials->first();
        }

        // VALIDATION: Kung wala talagang kahit anong 'sk' account sa barangay
        if (!$recipient) {
            return response()->json([
                'success' => false, 
                'message' => "Failed: No account with role 'sk' found for Barangay ID: {$sender->barangay_id}."
            ], 404);
        }

        $recipientId = $recipient->id;

        // C. GAWIN ANG FOLDER SA ACCOUNT NI CHAIR
        $folder = ReportFolder::firstOrCreate(
            [
                'name' => 'SK Report Files', 
                'user_id' => $recipientId 
            ],
            [
                'color' => '#ef4444' 
            ]
        );

        // D. I-SAVE ANG FILES
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $prefix = strtoupper($request->report_type);
                $brgyName = $sender->barangay ? $sender->barangay->name : 'Brgy-' . $sender->barangay_id;
                
                $fullName = $sender->given_name . ' ' . $sender->last_name;
                
                $finalName = "[$prefix] - $fullName ($brgyName) - $originalName";
                // --------------------------------------------------
                
                $path = $file->storeAs('reports', time() . '_' . $originalName, 'public');

                ReportFile::create([
                    'folder_id' => $folder->id,
                    'name' => $finalName,
                    'path' => $path,
                    'type' => $this->getFileType($originalName),
                    'size' => $this->formatSize($file->getSize()),
                    'user_id' => $recipientId 
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Report sent successfully to SK Chairperson!']);
    }

    
}