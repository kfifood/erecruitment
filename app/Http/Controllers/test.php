<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\ApplicationEducation;
use App\Models\ApplicationCertificate;
use App\Models\ApplicationReference;
use App\Models\ApplicationEmergencyContact;
use App\Models\ApplicationLanguageSkill;
use App\Models\ApplicationComputerSkill;
use App\Models\ApplicationSocialActivity;
use App\Models\ApplicationEmploymentHistory;
use App\Models\ApplicationFamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\WablasService;
use Illuminate\Support\Facades\View;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['job', 'educations', 'employmentHistories'])->get();
        return view('applications.index', compact('applications'));
    }

    public function create($job_id)
    {
        $job = Job::findOrFail($job_id);
        return view('applications.create', compact('job'));
    }

    public function store(Request $request)
    {
        // Validasi utama
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'home_phone' => 'nullable|string|max:20',
            'id_number' => 'required|string|max:50',
            'religion' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'ethnicity' => 'required|string|max:100',
            'height' => 'required|integer|min:100|max:250',
            'weight' => 'required|integer|min:30|max:200',
            'house_ownership' => 'required|in:sendiri,orangtua,sewa,lainnya',
            'vehicle_ownership' => 'required|in:mobil,motor,keduanya,tidak ada',
            'marital_status' => 'required|in:belum menikah,menikah,duda/janda',
            'family_members' => 'required|integer|min:1',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cv' => 'required|file|mimes:pdf|max:5120',
            'cover_letter' => 'required|file|mimes:pdf|max:5120',
            
            // Validasi untuk array data
            'educations' => 'required|array|min:1',
            'educations.*.education_level' => 'required|string',
            'educations.*.school_name' => 'required|string',
            'educations.*.city' => 'required|string',
            'educations.*.start_year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'educations.*.end_year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            
            'family_members_data' => 'nullable|array',
            'family_members_data.*.family_role' => 'required|string',
            'family_members_data.*.name' => 'required|string',
            'family_members_data.*.gender' => 'required|in:L,P',
            'family_members_data.*.birth_date' => 'required|date',
            
            'certificates' => 'nullable|array',
            'certificates.*.name' => 'required|string',
            'certificates.*.city' => 'required|string',
            'certificates.*.organizer' => 'required|string',
            'certificates.*.year' => 'required|digits:4',
            
            'references' => 'nullable|array',
            'references.*.name' => 'required|string',
            'references.*.address' => 'required|string',
            'references.*.phone' => 'required|string',
            'references.*.occupation' => 'required|string',
            'references.*.relationship' => 'required|string',
            
            // Emergency Contacts
    'emergency_contacts' => 'nullable|array',
    'emergency_contacts.*.name' => 'required|string',
    'emergency_contacts.*.address' => 'required|string',
    'emergency_contacts.*.phone' => 'required|string',
    'emergency_contacts.*.occupation' => 'required|string',
    'emergency_contacts.*.relationship' => 'required|string',
    
    // Language Skills
    'language_skills' => 'nullable|array',
    'language_skills.*.language' => 'required|string',
    'language_skills.*.level' => 'required|in:mahir,menguasai,pemula',
    'language_skills.*.description' => 'nullable|string',
    
    // Computer Skills
    'computer_skills' => 'nullable|array',
    'computer_skills.*.program' => 'required|string',
    'computer_skills.*.level' => 'required|in:mahir,menguasai,pemula',
    'computer_skills.*.description' => 'nullable|string',
    
    // Social Activities
    'social_activities' => 'nullable|array',
    'social_activities.*.organization' => 'required|string',
    'social_activities.*.address' => 'required|string',
    'social_activities.*.position' => 'required|string',
    'social_activities.*.year' => 'required|digits:4',
    'social_activities.*.activity_type' => 'required|string',
    
    // Employment Histories
    'employment_histories' => 'nullable|array',
    'employment_histories.*.company_name' => 'required|string',
    'employment_histories.*.address' => 'required|string',
    'employment_histories.*.phone' => 'required|string',
    'employment_histories.*.start_year' => 'required|digits:4',
    'employment_histories.*.end_year' => 'nullable|digits:4',
    'employment_histories.*.position' => 'required|string',
    'employment_histories.*.business_type' => 'required|string',
    'employment_histories.*.employee_count' => 'required|string',
    'employment_histories.*.last_salary' => 'nullable|string',
    'employment_histories.*.reason_for_leaving' => 'nullable|string',
    'employment_histories.*.job_description' => 'required|string',
        ]);

        try {
            // Simpan file
            $photoPath = $this->storeFile($request->file('photo'), 'photos');
            $cvPath = $this->storeFile($request->file('cv'), 'cvs');
            $coverLetterPath = $this->storeFile($request->file('cover_letter'), 'cover_letters');

            // Simpan data aplikasi utama
            $application = Application::create([
                'job_id' => $validated['job_id'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'home_phone' => $validated['home_phone'],
                'id_number' => $validated['id_number'],
                'religion' => $validated['religion'],
                'ethnicity' => $validated['ethnicity'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'house_ownership' => $validated['house_ownership'],
                'vehicle_ownership' => $validated['vehicle_ownership'],
                'marital_status' => $validated['marital_status'],
                'family_members' => $validated['family_members'],
                'photo' => $photoPath,
                'cv' => $cvPath,
                'cover_letter' => $coverLetterPath,
                'status' => 'not-reviewed',
                'interview_status' => null,
            ]);

            // Simpan data pendidikan
            foreach ($request->educations as $education) {
                $application->educations()->create([
                    'education_level' => $education['education_level'],
                    'school_name' => $education['school_name'],
                    'city' => $education['city'],
                    'major' => $education['major'] ?? null,
                    'start_year' => $education['start_year'],
                    'end_year' => $education['end_year'],
                ]);
            }

            // Simpan data keluarga jika ada
            if ($request->family_members_data) {
                foreach ($request->family_members_data as $family) {
                    $application->familyMembers()->create([
                        'family_role' => $family['family_role'],
                        'name' => $family['name'],
                        'gender' => $family['gender'],
                        'birth_date' => $family['birth_date'],
                        'last_position' => $family['last_position'] ?? null,
                        'last_company' => $family['last_company'] ?? null,
                    ]);
                }
            }

            // Simpan sertifikat jika ada
            if ($request->certificates) {
                foreach ($request->certificates as $certificate) {
                    $certificatePath = null;
                    if (isset($certificate['certificate_file'])) {
                        $certificatePath = $this->storeFile($certificate['certificate_file'], 'certificates');
                    }
                    
                    $application->certificates()->create([
                        'name' => $certificate['name'],
                        'city' => $certificate['city'],
                        'organizer' => $certificate['organizer'],
                        'year' => $certificate['year'],
                        'certificate_file' => $certificatePath,
                    ]);
                }
            }

            // Simpan referensi jika ada
            if ($request->references) {
                foreach ($request->references as $reference) {
                    $application->references()->create([
                        'name' => $reference['name'],
                        'address' => $reference['address'],
                        'phone' => $reference['phone'],
                        'occupation' => $reference['occupation'],
                        'relationship' => $reference['relationship'],
                    ]);
                }
            }

            // Simpan riwayat pekerjaan jika ada
            if ($request->employment_histories) {
                foreach ($request->employment_histories as $history) {
                    $application->employmentHistories()->create([
                        'company_name' => $history['company_name'],
                        'address' => $history['address'],
                        'phone' => $history['phone'],
                        'start_year' => $history['start_year'],
                        'end_year' => $history['end_year'],
                        'position' => $history['position'],
                        'business_type' => $history['business_type'],
                        'employee_count' => $history['employee_count'],
                        'last_salary' => $history['last_salary'],
                        'reason_for_leaving' => $history['reason_for_leaving'],
                        'job_description' => $history['job_description'],
                    ]);
                }
            }

            // Simpan data lainnya (emergency contacts, language skills, dll)
            // ... (implementasi serupa dengan yang di atas)

            // Kirim notifikasi WhatsApp
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
                'redirect' => route('jobs.show.public', $validated['job_id'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error submitting application: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan file dengan nama asli ke public/data/[subfolder]
     */
    private function storeFile($file, $subfolder)
    {
        $basePath = public_path('data/' . $subfolder);
        
        // Buat folder jika belum ada
        if (!file_exists($basePath)) {
            mkdir($basePath, 0755, true);
        }

        $originalName = $file->getClientOriginalName();
        $cleanName = $this->sanitizeFilename($originalName);
        $baseName = pathinfo($cleanName, PATHINFO_FILENAME);
        $extension = pathinfo($cleanName, PATHINFO_EXTENSION);

        $counter = 1;
        $newName = $cleanName;

        // Cek duplikat dan tambahkan counter jika perlu
        while (file_exists($basePath . '/' . $newName)) {
            $newName = $baseName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        // Simpan file
        $file->move($basePath, $newName);
        
        return 'data/' . $subfolder . '/' . $newName;
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
        return $clean ?: 'file_' . time();
    }

    public function edit(Application $application)
    {
        $application->load([
            'educations', 
            'familyMembers',
            'certificates',
            'references',
            'employmentHistories',
            // ... (load relasi lainnya)
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return view('applications.edit', compact('application'));
        }
        return redirect()->route('applications.index');
    }

    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:not-reviewed,review-list,interview,rejected',
            'interview_status' => 'sometimes|in:not yet,interviewed'
        ]);

        $application->update($request->only('status', 'interview_status'));

        return redirect()->route('applications.index')
            ->with('success', 'Status lamaran berhasil diperbarui!');
    }

    public function destroy(Application $application)
    {
        // Hapus semua relasi terlebih dahulu
        $application->educations()->delete();
        $application->familyMembers()->delete();
        $application->certificates()->delete();
        $application->references()->delete();
        $application->employmentHistories()->delete();
        // ... (hapus relasi lainnya)

        // Hapus file terkait dari public/data
        $filesToDelete = [
            public_path($application->photo),
            public_path($application->cv),
            public_path($application->cover_letter),
            storage_path('app/public/merged_' . $application->id . '.pdf')
        ];

        foreach ($filesToDelete as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        // Hapus file sertifikat jika ada
        foreach ($application->certificates as $certificate) {
            if ($certificate->certificate_file && file_exists(public_path($certificate->certificate_file))) {
                @unlink(public_path($certificate->certificate_file));
            }
        }

        $application->delete();

        return redirect()->route('applications.index')
            ->with('success', 'Lamaran berhasil dihapus!');
    }

    /**
     * Mendapatkan nama asli file dari path yang disimpan
     */
    public function getOriginalName($filePath)
    {
        if (empty($filePath)) {
            return null;
        }

        $filename = basename($filePath);
        
        // Hilangkan counter duplikat jika ada (format: nama_1.ext)
        if (preg_match('/^(.+)_\d+(\..+)$/', $filename, $matches)) {
            return $matches[1] . $matches[2];
        }
        
        return $filename;
    }
}