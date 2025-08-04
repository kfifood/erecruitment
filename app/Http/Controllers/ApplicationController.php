<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\WablasService;
use Illuminate\Support\Facades\View;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with('job')->get();
        return view('applications.index', compact('applications'));
    }

    public function create($job_id)
    {
        $job = Job::findOrFail($job_id);
        return view('applications.create', compact('job'));
    }


public function store(Request $request)
{
    $request->validate([
        'job_id' => 'required|exists:jobs,id',
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'education' => 'required|string|max:255',
        'major' => 'nullable|string|max:255',
        'study_program' => 'nullable|string|max:255',
        'birth_date' => 'required|date',
        'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'cv' => 'required|file|mimes:pdf|max:5120',
        'cover_letter' => 'required|file|mimes:pdf|max:5120',
    ]);

    // Simpan file dengan nama asli dan cegah duplikasi
    $photoPath = $this->storeFileWithOriginalName($request->file('photo'), 'photos');
    $cvPath = $this->storeFileWithOriginalName($request->file('cv'), 'cvs');
    $coverLetterPath = $this->storeFileWithOriginalName($request->file('cover_letter'), 'cover_letters');

    $application = Application::create([
        'job_id' => $request->job_id,
        'full_name' => $request->full_name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'education' => $request->education,
        'major' => $request->major,
        'study_program' => $request->study_program,
        'birth_date' => $request->birth_date,
        'photo' => $photoPath,
        'cv' => $cvPath,
        'cover_letter' => $coverLetterPath,
        'status' => 'submitted',
        'interview_status' => null,
    ]);

    // Kirim pesan WhatsApp
    try {
        $wablasService = new WablasService();
        $message = View::make('formats.application_submitted_message', compact('application'))->render();
        $wablasService->sendMessage($application->phone, $message);
    } catch (\Exception $e) {
        \Log::error('Gagal mengirim pesan WhatsApp: ' . $e->getMessage());
    }

    return response()->json([
        'success' => true,
        'message' => 'Lamaran berhasil dikirim! Terima kasih telah melamar.',
        'redirect' => route('jobs.show.public', $request->job_id)
    ]);
}

    /**
     * Menyimpan file dengan nama asli dan mencegah duplikasi
     */
    private function storeFileWithOriginalName($file, $directory)
    {
        // Buat folder jika belum ada
        Storage::disk('public')->makeDirectory($directory);

        $originalName = $file->getClientOriginalName();
        $cleanName = $this->sanitizeFilename($originalName);
        $baseName = pathinfo($cleanName, PATHINFO_FILENAME);
        $extension = pathinfo($cleanName, PATHINFO_EXTENSION);

        $counter = 1;
        $newName = $cleanName;

        // Cek duplikat dan tambahkan counter jika perlu
        while (Storage::disk('public')->exists("$directory/$newName")) {
            $newName = $baseName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        // Simpan file
        return $file->storeAs($directory, $newName, 'public');
    }

    /**
     * Membersihkan nama file dari karakter tidak aman
     */
    private function sanitizeFilename($filename)
    {
        // Hapus karakter khusus kecuali titik, underscore, dan dash
        $clean = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", $filename);
        
        // Hapus multiple underscore
        $clean = preg_replace("/_+/", "_", $clean);
        
        // Hapus underscore dari awal dan akhir
        $clean = trim($clean, "_");
        
        // Pastikan tidak kosong
        if (empty($clean)) {
            $clean = 'file_' . time();
        }
        
        return $clean;
    }

public function edit(Application $application)
{
    // Pastikan hanya mengembalikan view modal untuk AJAX request
    if(request()->ajax() || request()->wantsJson()) {
        return view('applications.edit', compact('application'));
    }
    
    // Jika perlu fallback untuk non-AJAX, bisa diarahkan ke halaman lain
    return redirect()->route('applications.index');
}

    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:submitted,interview,rejected',
            'interview_status' => 'sometimes|in:not yet,interviewed'
        ]);

        $application->update($request->only('status','interview_status'));

        return redirect()->route('applications.index')
            ->with('success', 'Status lamaran berhasil diperbarui!');
    }

    public function destroy(Application $application)
    {
        // Daftar file yang perlu dihapus
        $filesToDelete = [
            $application->photo,
            $application->cv,
            $application->cover_letter,
            'merged_'.$application->id.'.pdf'
        ];

        // Hapus semua file terkait
        foreach ($filesToDelete as $file) {
            try {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            } catch (\Exception $e) {
                \Log::error("Failed to delete file {$file}: " . $e->getMessage());
            }
        }

        // Hapus record dari database
        $application->delete();

        return redirect()->route('applications.index')
            ->with('success', 'Application Deleted Successfully!');
    }
}