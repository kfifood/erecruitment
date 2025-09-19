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
use App\Services\NextcloudService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ApplicationController extends Controller
{
    protected $nextcloudService;

    public function __construct(NextcloudService $nextcloudService)
    {
        $this->nextcloudService = $nextcloudService;
    }

    // Waiting List Office
    public function waitingListOffice()
    {
        $applications = Application::with([
                'job', 
                'educations',
                'familyMembers',
                'certificates',
                'references',
                'emergencyContacts',
                'languageSkills',
                'computerSkills',
                'socialActivities',
                'employmentHistories'])
            ->where('job_type','office')
            ->whereIn('status', ['not-reviewed', 'rejected'])
            ->latest()
            ->get();

        return view('applications.waiting_list_office', compact('applications'));
    }

    // Waiting List Production
    public function waitingListProduction()
    {
        $applications = Application::with([
                'job', 
                'educations',
                'familyMembers',
                'certificates',
                'references',
                'emergencyContacts',
                'languageSkills',
                'computerSkills',
                'socialActivities',
                'employmentHistories'])
            ->where('job_type', 'production')
            ->whereIn('status', ['not-reviewed','rejected'])
            ->latest()
            ->get();

        return view('applications.waiting_list_production', compact('applications'));
    }

    // Review List Office
    public function reviewListOffice()
    {
        $applications = Application::with([
                'job', 
                'educations',
                'familyMembers',
                'certificates',
                'references',
                'emergencyContacts',
                'languageSkills',
                'computerSkills',
                'socialActivities',
                'employmentHistories'])
            ->where('job_type', 'office')
            ->whereIn('status', ['review-list', 'interview'])
            ->latest()
            ->get();

        return view('applications.review_list_office', compact('applications'));
    }

    // Review List Production
    public function reviewListProduction()
    {
        $applications = Application::with([
                'job', 
                'educations',
                'familyMembers',
                'certificates',
                'references',
                'emergencyContacts',
                'languageSkills',
                'computerSkills',
                'socialActivities',
                'employmentHistories'])
            ->where('job_type', 'production')
            ->whereIn('status', ['review-list', 'interview'])
            ->latest()
            ->get();

        return view('applications.review_list_production', compact('applications'));
    }

    public function create($job_id)
    {
        $job = Job::findOrFail($job_id);
        
        $view = $job->recruitment_type === 'production' 
            ? 'applications.create-production' 
            : 'applications.create';
        
        return view($view, compact('job'));
    }

    /**
     * Helper untuk redirect ke halaman yang sesuai
     */
    private function redirectToAppropriateList(Application $application)
    {
        $recruitmentType = $application->job->recruitment_type;
        $status = $application->status;

        if ($status === 'not-reviewed') {
            $route = $recruitmentType === 'office' 
                ? 'applications.waiting-list-office' 
                : 'applications.waiting-list-production';
        } else {
            $route = $recruitmentType === 'office' 
                ? 'applications.review-list-office' 
                : 'applications.review-list-production';
        }

        return redirect()->route($route);
    }

    public function store(Request $request)
    {
        // Cek duplicate submission
        $submissionToken = $request->header('X-Submission-Token') ?? $request->input('submission_token');
        if ($submissionToken && Cache::has('submission_' . $submissionToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate submission detected. Lamaran Anda sudah dikirim sebelumnya.'
            ], 429);
        }
        
        // Simpan token untuk 5 menit
        $token = Str::random(32);
        Cache::put('submission_' . $token, true, 300);

        // Dapatkan job terlebih dahulu
        $job = Job::findOrFail($request->job_id);
        
        // Validasi dasar yang berlaku untuk semua tipe recruitment
        $baseValidationRules = [
            'job_id' => 'required|exists:jobs,id',
            'full_name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('applications')->where(function ($query) use ($request) {
                    return $query->where('job_id', $request->job_id);
                })
            ],
            'address' => 'required|string',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'id_number' => 'required|string|max:50',
            'religion' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'ethnicity' => 'required|string|max:100',
            'height' => 'required|integer|min:100|max:250',
            'weight' => 'required|integer|min:30|max:200',
            'marital_status' => 'required|in:belum menikah,menikah,duda/janda',
            'family_members' => 'required|integer|min:1',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cv' => 'required|file|mimes:pdf|max:5120',
            'cover_letter' => 'required|file|mimes:pdf|max:5120',
            'strengths' => 'required|string|min:20|max:1000',
            'weaknesses' => 'required|string|min:0|max:1000',
            
            // Validasi untuk pendidikan
            'educations' => 'required|array|min:1',
            'educations.*.education_level' => 'required|string',
            'educations.*.school_name' => 'required|string',
            'educations.*.city' => 'required|string',
            'educations.*.major' => 'nullable|string',
            'educations.*.start_year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'educations.*.end_year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
        ];

        // Validasi khusus untuk office
        $officeValidationRules = [
            'email' => 'required|email|max:255',
            'home_phone' => 'nullable|string|max:20',
            'house_ownership' => 'required|in:sendiri,orangtua,sewa,lainnya',
            'vehicle_ownership' => 'required|in:mobil,motor,keduanya,tidak ada',
            
            // Validasi untuk sertifikat
            'certificates' => 'nullable|array',
            'certificates.*.name' => 'nullable|string',
            'certificates.*.city' => 'nullable|string',
            'certificates.*.organizer' => 'nullable|string',
            'certificates.*.year' => 'nullable|digits:4',
            'certificates.*.certificate_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            
            // Validasi untuk referensi
            'references' => 'nullable|array',
            'references.*.name' => 'nullable|string',
            'references.*.address' => 'nullable|string',
            'references.*.phone' => 'nullable|string',
            'references.*.occupation' => 'nullable|string',
            'references.*.relationship' => 'nullable|string',
            
            // Validasi untuk kemampuan bahasa
            'language_skills' => 'nullable|array',
            'language_skills.*.language' => 'nullable|string',
            'language_skills.*.level' => 'nullable|in:mahir,menguasai,pemula',
            'language_skills.*.description' => 'nullable|string',
            
            // Validasi untuk kemampuan komputer
            'computer_skills' => 'nullable|array',
            'computer_skills.*.program' => 'nullable|string',
            'computer_skills.*.level' => 'nullable|in:mahir,menguasai,pemula',
            'computer_skills.*.description' => 'nullable|string',
        ];

        // Validasi khusus untuk produksi
        $productionValidationRules = [
            'email' => 'nullable|email|max:255',
            'house_ownership' => 'nullable|in:sendiri,orangtua,sewa,lainnya',
            'vehicle_ownership' => 'nullable|in:mobil,motor,keduanya,tidak ada',
            'shift_work' => 'required|in:Ya,Tidak',
            'piecework_system' => 'required|in:Ya,Tidak',
            'position_transfer' => 'required|in:Ya,Tidak',
            'organization_experience' => 'nullable|string',
            'current_sickness' => 'required|in:Ya,Tidak',
            'recent_sickness' => 'required|in:Ya,Tidak',
            'typhoid' => 'nullable|in:Ya,Tidak',
            'hepatitis' => 'nullable|in:Ya,Tidak',
            'tuberculosis' => 'nullable|in:Ya,Tidak',
            'cyst' => 'nullable|in:Ya,Tidak',
            'police_record' => 'required|in:Ya,Tidak',
            'color_blind' => 'required|in:Ya,Tidak',
            'contagious_disease' => 'required|in:Ya,Tidak',
            'current_contract' => 'required|in:Ya,Tidak',
            'disliked_job_types' => 'required|string',
            'computer_machine_skills' => 'required|string',
        ];

        // Gabungkan validasi berdasarkan tipe recruitment
        if ($job->recruitment_type === 'production') {
            $validationRules = array_merge($baseValidationRules, $productionValidationRules);
        } else {
            $validationRules = array_merge($baseValidationRules, $officeValidationRules);
        }

        // Jalankan validasi
        $validated = $request->validate($validationRules);

        DB::beginTransaction();

        try {
            // Simpan file utama ke Nextcloud (hanya Nextcloud, tanpa fallback)
            $photoPath = $this->storeFile($request->file('photo'), 'photos');
            $cvPath = $this->storeFile($request->file('cv'), 'cvs');
            $coverLetterPath = $this->storeFile($request->file('cover_letter'), 'cover_letters');

            // Data untuk aplikasi utama
            $applicationData = [
                'job_id' => $validated['job_id'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'] ?? 'tidak-ada@example.com',
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'id_number' => $validated['id_number'],
                'religion' => $validated['religion'],
                'ethnicity' => $validated['ethnicity'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'marital_status' => $validated['marital_status'],
                'family_members' => $validated['family_members'],
                'photo' => $photoPath,
                'cv' => $cvPath,
                'cover_letter' => $coverLetterPath,
                'status' => 'not-reviewed',
                'interview_status' => null,
                'strengths' => $validated['strengths'],
                'weaknesses' => $validated['weaknesses'],
            ];

            // Tambahkan field khusus office jika bukan produksi
            if ($job->recruitment_type !== 'production') {
                $applicationData['home_phone'] = $validated['home_phone'] ?? null;
                $applicationData['house_ownership'] = $validated['house_ownership'];
                $applicationData['vehicle_ownership'] = $validated['vehicle_ownership'];
            }

            // Simpan data aplikasi utama
            $application = Application::create($applicationData);

            // Simpan pertanyaan tambahan
            if ($job->recruitment_type !== 'production') {
            $questionData = [
                'question_1' => $request->question_1,
                'question_1_explanation' => $request->question_1_explanation,
                'question_2' => $request->question_2,
                'question_3' => $request->question_3,
                'question_3_explanation' => $request->question_3_explanation,
                'question_4' => $request->question_4,
                'question_5' => $request->question_5,
                'question_5_explanation' => $request->question_5_explanation,
                'question_6' => $request->question_6,
                'question_6_explanation' => $request->question_6_explanation,
                'question_7' => $request->question_7,
                'question_7_explanation' => $request->question_7_explanation,
                'question_8' => $request->question_8,
                'question_8_explanation' => $request->question_8_explanation,
                'question_9' => $request->question_9,
                'question_10' => $request->question_10,
                'question_10_explanation' => $request->question_10_explanation,
                'question_11' => $request->question_11,
                'question_11_explanation' => $request->question_11_explanation,
                'question_12' => $request->question_12,
                'question_12_explanation' => $request->question_12_explanation,
                'question_13' => $request->question_13,
                'question_14' => $request->question_14,
                'question_15' => $request->question_15,
                'question_16' => $request->question_16,
            ];

            $application->questions()->create($questionData);
            }
            // Simpan pertanyaan produksi jika jenis rekrutmen production
            if ($job->recruitment_type === 'production') {
                $productionQuestionData = [
                    'shift_work' => $request->shift_work,
                    'piecework_system' => $request->piecework_system,
                    'position_transfer' => $request->position_transfer,
                    'organization_experience' => $request->organization_experience,
                    'current_sickness' => $request->current_sickness,
                    'recent_sickness' => $request->recent_sickness,
                    'typhoid' => $request->typhoid,
                    'hepatitis' => $request->hepatitis,
                    'tuberculosis' => $request->tuberculosis,
                    'cyst' => $request->cyst,
                    'police_record' => $request->police_record,
                    'color_blind' => $request->color_blind,
                    'contagious_disease' => $request->contagious_disease,
                    'current_contract' => $request->current_contract,
                    'disliked_job_types' => $request->disliked_job_types,
                    'computer_machine_skills' => $request->computer_machine_skills,
                ];

                $application->productionQuestions()->create($productionQuestionData);

                // Validasi khusus untuk SMA/SMK
                $baseValidationRules['educations.*.major'] = [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) use ($request) {
                        $index = explode('.', $attribute)[1];
                        $educationLevel = $request->input("educations.{$index}.education_level");
                
                            if (in_array($educationLevel, ['SMA', 'SMK']) && empty($value)) {
                                $fail('Jurusan wajib diisi untuk pendidikan SMA/SMK');
                            }
                    }
                ];
            }

            // Simpan data pendidikan (wajib)
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
            if ($request->filled('family_members_data')) {
                foreach ($request->family_members_data as $family) {
                    if (!empty($family['name']) || !empty($family['family_role'])) {
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
            }

            // Simpan data lainnya hanya untuk office
            if ($job->recruitment_type !== 'production') {
                // Simpan sertifikat jika ada data yang diisi
                if ($request->filled('certificates')) {
                    foreach ($request->certificates as $certificate) {
                        if (!empty($certificate['name']) || !empty($certificate['city']) || !empty($certificate['organizer'])) {
                            $certificatePath = null;
                            if (isset($certificate['certificate_file']) && $certificate['certificate_file'] instanceof \Illuminate\Http\UploadedFile) {
                                $certificatePath = $this->storeFile($certificate['certificate_file'], 'certificates');
                            }
                            
                            $application->certificates()->create([
                                'name' => $certificate['name'] ?? null,
                                'city' => $certificate['city'] ?? null,
                                'organizer' => $certificate['organizer'] ?? null,
                                'year' => $certificate['year'] ?? null,
                                'certificate_file' => $certificatePath,
                            ]);
                        }
                    }
                }

                // Simpan referensi jika ada data yang diisi
                if ($request->filled('references')) {
                    foreach ($request->references as $reference) {
                        if (!empty($reference['name']) || !empty($reference['phone'])) {
                            $application->references()->create([
                                'name' => $reference['name'] ?? null,
                                'address' => $reference['address'] ?? null,
                                'phone' => $reference['phone'] ?? null,
                                'occupation' => $reference['occupation'] ?? null,
                                'relationship' => $reference['relationship'] ?? null,
                            ]);
                        }
                    }
                }

                // Simpan kemampuan bahasa jika ada data yang diisi
                if ($request->filled('language_skills')) {
                    foreach ($request->language_skills as $skill) {
                        if (!empty($skill['language'])) {
                            $application->languageSkills()->create([
                                'language' => $skill['language'] ?? null,
                                'level' => $skill['level'] ?? null,
                                'description' => $skill['description'] ?? null,
                            ]);
                        }
                    }
                }

                // Simpan kemampuan komputer jika ada data yang diisi
                if ($request->filled('computer_skills')) {
                    foreach ($request->computer_skills as $skill) {
                        if (!empty($skill['program'])) {
                            $application->computerSkills()->create([
                                'program' => $skill['program'] ?? null,
                                'level' => $skill['level'] ?? null,
                                'description' => $skill['description'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Simpan kontak darurat jika ada data yang diisi (untuk semua tipe)
            if ($request->filled('emergency_contacts')) {
                foreach ($request->emergency_contacts as $contact) {
                    if (!empty($contact['name']) || !empty($contact['phone'])) {
                        $application->emergencyContacts()->create([
                            'name' => $contact['name'] ?? null,
                            'address' => $contact['address'] ?? null,
                            'phone' => $contact['phone'] ?? null,
                            'occupation' => $contact['occupation'] ?? null,
                            'relationship' => $contact['relationship'] ?? null,
                        ]);
                    }
                }
            }

            // Simpan kegiatan sosial jika ada data yang diisi (untuk semua tipe)
            if ($request->filled('social_activities')) {
                foreach ($request->social_activities as $activity) {
                    if (!empty($activity['organization']) || !empty($activity['position'])) {
                        $application->socialActivities()->create([
                            'organization' => $activity['organization'] ?? null,
                            'address' => $activity['address'] ?? null,
                            'position' => $activity['position'] ?? null,
                            'year' => $activity['year'] ?? null,
                            'activity_type' => $activity['activity_type'] ?? null,
                        ]);
                    }
                }
            }

            // Simpan riwayat pekerjaan jika ada data yang diisi (untuk semua tipe)
            if ($request->filled('employment_histories')) {
                foreach ($request->employment_histories as $history) {
                    if (!empty($history['company_name']) || !empty($history['position'])) {
                        $application->employmentHistories()->create([
                            'company_name' => $history['company_name'] ?? null,
                            'address' => $history['address'] ?? null,
                            'phone' => $history['phone'] ?? null,
                            'start_year' => $history['start_year'] ?? null,
                            'end_year' => $history['end_year'] ?? null,
                            'position' => $history['position'] ?? null,
                            'business_type' => $history['business_type'] ?? null,
                            'employee_count' => $history['employee_count'] ?? null,
                            'last_salary' => $history['last_salary'] ?? null,
                            'reason_for_leaving' => $history['reason_for_leaving'] ?? null,
                            'job_description' => $history['job_description'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            // Hapus token setelah berhasil
            Cache::forget('submission_' . $token);

            // Kirim notifikasi WhatsApp
            try {
                $wablasService = new WablasService();
                $message = View::make('formats.application_submitted_message', compact('application'))->render();
                $wablasService->sendMessage($application->phone, $message);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim pesan WhatsApp: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Lamaran berhasil dikirim! Terima kasih telah melamar.',
                'redirect' => route('jobs.show.public', $validated['job_id'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus token jika error
            Cache::forget('submission_' . $token);
            
            Log::error('Error submitting application: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => 'Terjadi kesalahan saat mengirim lamaran'
            ], 500);
        }
    }

    public function edit(Application $application)
    {
        $application->load([
            'educations',
            'familyMembers',
            'certificates',
            'references',
            'emergencyContacts',
            'languageSkills',
            'computerSkills',
            'socialActivities',
            'employmentHistories'
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return view('applications.edit', compact('application'));
        }
        // Redirect ke halaman yang sesuai berdasarkan recruitment type dan status
        return $this->redirectToAppropriateList($application);
    }

    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:not-reviewed,review-list,interview,rejected',
            'interview_status' => 'sometimes|in:not yet,interviewed'
        ]);

        $application->update($request->only('status', 'interview_status'));

        return $this->redirectToAppropriateList($application)
            ->with('success', 'Status lamaran berhasil diperbarui!');
    }

    public function destroy(Application $application)
    {
        DB::beginTransaction();

        try {
            // Hapus semua relasi terlebih dahulu
            $application->educations()->delete();
            $application->familyMembers()->delete();
            
            // Hapus file sertifikat
            foreach ($application->certificates as $certificate) {
                if ($certificate->certificate_file) {
                    $this->deleteFile($certificate->certificate_file);
                }
            }
            $application->certificates()->delete();
            
            $application->references()->delete();
            $application->emergencyContacts()->delete();
            $application->languageSkills()->delete();
            $application->computerSkills()->delete();
            $application->socialActivities()->delete();
            $application->employmentHistories()->delete();

            // Hapus file utama
            $this->deleteFile($application->photo);
            $this->deleteFile($application->cv);
            $this->deleteFile($application->cover_letter);

            // Hapus file merged PDF jika ada (local storage)
            $mergedPdfPath = storage_path('app/public/merged_' . $application->id . '.pdf');
            if (file_exists($mergedPdfPath)) {
                unlink($mergedPdfPath);
            }

            // Hapus aplikasi utama
            $application->delete();

            DB::commit();

            return $this->redirectToAppropriateList($application)
                ->with('success', 'Lamaran beserta semua data terkait berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting application: ' . $e->getMessage());
            
            return $this->redirectToAppropriateList($application)
                ->with('error', 'Gagal menghapus lamaran. Silakan coba lagi.');
        }
    }

    /**
     * Menyimpan file ke Nextcloud saja (tanpa fallback ke local storage)
     * Jika gagal, langsung throw exception
     */
    private function storeFile($file, $subfolder)
    {
        $originalName = $file->getClientOriginalName();
        
        // Upload ke Nextcloud
        $remotePath = $this->nextcloudService->uploadFile($file, $subfolder, $originalName);
        
        if ($remotePath) {
            return $remotePath; // Return Nextcloud path
        }

        // Jika gagal, throw exception dengan pesan error
        throw new \Exception("Gagal mengupload file '$originalName' ke Nextcloud. Silakan coba lagi atau hubungi administrator.");
    }

    /**
     * Hapus file dari Nextcloud saja
     */
    private function deleteFile($filePath)
    {
        if (empty($filePath)) {
            return false;
        }

        // Hanya hapus jika file berasal dari Nextcloud
        if (strpos($filePath, '/remote.php/dav/files/') === 0) {
            return $this->nextcloudService->deleteFile($filePath);
        }
        
        // Jika file local, tidak dihapus (karena sekarang hanya Nextcloud)
        return false;
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

    /**
     * Mendapatkan URL lengkap untuk file yang disimpan di Nextcloud
     */
    public static function getFileUrl($filePath)
    {
        if (empty($filePath)) {
            return null;
        }
        
        // Jika file disimpan di Nextcloud
        if (strpos($filePath, '/remote.php/dav/files/') === 0) {
            return route('nextcloud.file.proxy', ['path' => ltrim($filePath, '/')]);
        }
        
        // Untuk file local (fallback)
        if (file_exists(public_path($filePath))) {
            return asset($filePath);
        }
        
        return null;
    }

    public function proxyNextcloudFile(Request $request, $path)
    {
        $config = config('services.nextcloud');
        $username = $config['username'];
        $password = $config['password'];
        
        // Pastikan path dimulai dengan slash
        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }
        
        $fullUrl = rtrim($config['base_url'], '/') . $path;
        
        try {
            $response = Http::withBasicAuth($username, $password)
                ->timeout(30)
                ->withHeaders([
                    'Accept' => 'application/octet-stream',
                ])
                ->get($fullUrl);
                
            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?? 'application/octet-stream';
                
                return response($response->body(), 200)
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', 'inline')
                    ->header('Cache-Control', 'public, max-age=3600');
            }
            
            Log::error('Nextcloud file not found: ' . $fullUrl . ' - Status: ' . $response->status());
            abort(404, 'File not found');
            
        } catch (\Exception $e) {
            Log::error('Nextcloud access error: ' . $e->getMessage());
            abort(500, 'Error accessing file');
        }
    }
}