<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Log;
use App\Services\NextcloudService;
use Illuminate\Support\Facades\Http;

class ApplicationPdfController extends Controller
{
    protected $nextcloudService;

    public function __construct(NextcloudService $nextcloudService)
    {
        $this->nextcloudService = $nextcloudService;
    }

    // ApplicationPdfController.php - perbaiki method previewPdf dan downloadPdf
public function previewPdf($id)
{
    $application = Application::with([
        'job',
        'educations',
        'familyMembers',
        'certificates',
        'references',
        'emergencyContacts',
        'languageSkills',
        'computerSkills',
        'socialActivities',
        'employmentHistories',
        'questions',
        'productionQuestions'
    ])->findOrFail($id);
    
    $pdfPath = $this->generateMergedPdf($application);
    
    if (!file_exists($pdfPath)) {
        abort(500, "Gagal membuat file PDF gabungan");
    }
    
    // Hapus file temporary setelah dikirim
    return response()->file($pdfPath)->deleteFileAfterSend(true);
}

public function downloadPdf($id)
{
    $application = Application::with([
        'job',
        'educations',
        'familyMembers',
        'certificates',
        'references',
        'emergencyContacts',
        'languageSkills',
        'computerSkills',
        'socialActivities',
        'employmentHistories',
        'questions',
        'productionQuestions'
    ])->findOrFail($id);
    
    $pdfPath = $this->generateMergedPdf($application);
    
    if (!file_exists($pdfPath)) {
        abort(500, "Gagal membuat file PDF gabungan");
    }
    
    $filename = 'Lamaran_'.$application->full_name.'_'.date('Ymd').'.pdf';
    
    return response()->download($pdfPath, $filename)
        ->deleteFileAfterSend(true);
}

    // ApplicationPdfController.php - perbaiki method generateMergedPdf
private function generateMergedPdf($application)
{
    $tempDir = public_path('temp');
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $dataPdfPath = '';
    $questionsPdfPath = '';
    $mergedPdfPath = '';
    $tempFilesToDelete = []; // Untuk menampung file temporary yang perlu dihapus

    try {
        // 1. Buat PDF data pelamar
        $dataPdf = Pdf::loadView('applications.pdf', ['application' => $application]);
        $dataPdfPath = $tempDir.'/temp_data_'.$application->id.'.pdf';
        $dataPdf->save($dataPdfPath);
        $tempFilesToDelete[] = $dataPdfPath;
        
        // 2. Buat PDF pertanyaan berdasarkan recruitment type
        if ($application->job->recruitment_type === 'production' && $application->productionQuestions) {
            $questionsPdf = Pdf::loadView('applications.pdf-questions-production', ['application' => $application]);
        } else if ($application->job->recruitment_type === 'office' && $application->questions) {
            $questionsPdf = Pdf::loadView('applications.pdf-questions-office', ['application' => $application]);
        }
        
        if (isset($questionsPdf)) {
            $questionsPdfPath = $tempDir.'/temp_questions_'.$application->id.'.pdf';
            $questionsPdf->save($questionsPdfPath);
            $tempFilesToDelete[] = $questionsPdfPath;
        }
        
        // 3. Inisialisasi PDF merger
        $merger = new Merger();
        $merger->addFile($dataPdfPath);
        
        // 4. Tambahkan PDF pertanyaan jika ada
        if (file_exists($questionsPdfPath)) {
            $merger->addFile($questionsPdfPath);
        }
        
        // 5. Tambahkan cover letter jika ada (download dari Nextcloud)
        if ($application->cover_letter) {
            $coverLetterPath = $this->getFilePath($application->cover_letter);
            if ($coverLetterPath && file_exists($coverLetterPath)) {
                $merger->addFile($coverLetterPath);
                $tempFilesToDelete[] = $coverLetterPath;
            }
        }
        
        // 6. Tambahkan CV jika ada (download dari Nextcloud)
        if ($application->cv) {
            $cvPath = $this->getFilePath($application->cv);
            if ($cvPath && file_exists($cvPath)) {
                $merger->addFile($cvPath);
                $tempFilesToDelete[] = $cvPath;
            }
        }
        
        // 7. Tambahkan sertifikat jika ada (download dari Nextcloud)
        if ($application->certificates) {
            foreach ($application->certificates as $certificate) {
                if ($certificate->certificate_file) {
                    $certificatePath = $this->getFilePath($certificate->certificate_file);
                    
                    if ($certificatePath && file_exists($certificatePath)) {
                        $extension = strtolower(pathinfo($certificatePath, PATHINFO_EXTENSION));
                        
                        // Jika file adalah PDF, langsung tambahkan
                        if ($extension === 'pdf') {
                            $merger->addFile($certificatePath);
                            $tempFilesToDelete[] = $certificatePath;
                        } 
                        // Jika file adalah gambar, konversi ke PDF
                        else if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $imagePdf = Pdf::loadHTML('
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <style>
                                        body { margin: 0; padding: 20px; text-align: center; }
                                        img { max-width: 100%; max-height: 700px; border: 1px solid #ddd; }
                                        .cert-info { margin-bottom: 15px; font-family: Arial; font-size: 12px; }
                                    </style>
                                </head>
                                <body>
                                    <div class="cert-info">
                                        <strong>Sertifikat:</strong> ' . htmlspecialchars($certificate->name) . '<br>
                                        <strong>Pelamar:</strong> ' . htmlspecialchars($application->full_name) . '
                                    </div>
                                    <img src="' . $certificatePath . '" alt="Sertifikat">
                                </body>
                                </html>
                            ');
                            
                            $imagePdfPath = $tempDir.'/temp_cert_image_'.$application->id.'_'.$certificate->id.'.pdf';
                            $imagePdf->save($imagePdfPath);
                            $merger->addFile($imagePdfPath);
                            $tempFilesToDelete[] = $imagePdfPath;
                        }
                    }
                }
            }
        }
        
        // 8. Gabungkan semua PDF
        $mergedPdfPath = $tempDir.'/merged_'.$application->id.'.pdf';
        file_put_contents($mergedPdfPath, $merger->merge());
        $tempFilesToDelete[] = $mergedPdfPath;
        
        return $mergedPdfPath;
        
    } catch (\Exception $e) {
        Log::error("Error merging PDF: " . $e->getMessage());
        
        // Clean up: hapus semua file temporary jika terjadi error
        foreach ($tempFilesToDelete as $tempFile) {
            if (file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
        
        throw $e;
    }
}



// ApplicationPdfController.php - tambahkan method ini
public static function getImageBase64($filePath)
{
    if (empty($filePath)) {
        return null;
    }
    
    $instance = new self(app(NextcloudService::class));
    $localPath = $instance->getFilePath($filePath);
    
    if (!$localPath || !file_exists($localPath)) {
        return null;
    }
    
    try {
        // Baca file dan convert ke base64
        $imageData = file_get_contents($localPath);
        
        // Validasi bahwa ini adalah image yang valid
        if ($instance->isValidImage($localPath)) {
            $base64 = base64_encode($imageData);
            
            // Hapus file temporary setelah dibaca
            @unlink($localPath);
            
            return $base64;
        }
        
        // Hapus file jika bukan image valid
        @unlink($localPath);
        return null;
        
    } catch (\Exception $e) {
        Log::error("Error reading image file: " . $e->getMessage());
        if (file_exists($localPath)) {
            @unlink($localPath);
        }
        return null;
    }
}


  private function getFilePath($filePath)
{
    if (empty($filePath)) {
        return null;
    }
    
    // Jika file disimpan di Nextcloud
    if (strpos($filePath, '/remote.php/dav/files/') === 0) {
        $tempDir = public_path('temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        
        $filename = basename($filePath);
        $cleanFilename = preg_replace("/[^a-zA-Z0-9._-]/", "_", $filename);
        $tempFilePath = $tempDir . '/' . uniqid() . '_' . $cleanFilename;
        
        try {
            $config = config('services.nextcloud');
            $username = $config['username'];
            $password = $config['password'];
            
            $fullUrl = $config['base_url'] . $filePath;
            
            $client = new \GuzzleHttp\Client();
            $response = $client->get($fullUrl, [
                'auth' => [$username, $password],
                'sink' => $tempFilePath,
                'timeout' => 30,
                'verify' => false
            ]);
            
            if ($response->getStatusCode() === 200) {
                return $tempFilePath;
            }
            
        } catch (\Exception $e) {
            Log::error("Error downloading file from Nextcloud: " . $e->getMessage());
            if (file_exists($tempFilePath)) {
                @unlink($tempFilePath);
            }
            return null;
        }
        
        return null;
    }
    
    // Jika file local
    if (file_exists(public_path($filePath))) {
        return public_path($filePath);
    }
    
    return null;
}

private function isValidImage($filePath)
{
    if (!file_exists($filePath)) {
        return false;
    }
    
    // Cek ekstensi file
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!in_array($extension, $allowedExtensions)) {
        return false;
    }
    
    // Cek menggunakan getimagesize
    $imageInfo = @getimagesize($filePath);
    if (!$imageInfo) {
        return false;
    }
    
    $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];
    return in_array($imageInfo[2], $allowedTypes);
}


}