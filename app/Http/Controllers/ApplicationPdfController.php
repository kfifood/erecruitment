<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Log;

class ApplicationPdfController extends Controller
{
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

    private function generateMergedPdf($application)
{
    $tempDir = storage_path('app/public');
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $dataPdfPath = '';
    $questionsPdfPath = '';
    $mergedPdfPath = '';

    try {
        // 1. Buat PDF data pelamar
        $dataPdf = Pdf::loadView('applications.pdf', ['application' => $application]);
        $dataPdfPath = $tempDir.'/temp_data_'.$application->id.'.pdf';
        $dataPdf->save($dataPdfPath);
        
        // 2. Buat PDF pertanyaan berdasarkan recruitment type
        if ($application->job->recruitment_type === 'production' && $application->productionQuestions) {
            $questionsPdf = Pdf::loadView('applications.pdf-questions-production', ['application' => $application]);
        } else if ($application->job->recruitment_type === 'office' && $application->questions) {
            $questionsPdf = Pdf::loadView('applications.pdf-questions-office', ['application' => $application]);
        }
        
        if (isset($questionsPdf)) {
            $questionsPdfPath = $tempDir.'/temp_questions_'.$application->id.'.pdf';
            $questionsPdf->save($questionsPdfPath);
        }
        
        // 3. Inisialisasi PDF merger
        $merger = new Merger();
        $merger->addFile($dataPdfPath);
        
        // 4. Tambahkan PDF pertanyaan jika ada
        if (file_exists($questionsPdfPath)) {
            $merger->addFile($questionsPdfPath);
        }
        
        // 5. Tambahkan cover letter jika ada
        if ($application->cover_letter && file_exists(public_path($application->cover_letter))) {
            $merger->addFile(public_path($application->cover_letter));
        }
        
        // 6. Tambahkan CV jika ada
        if ($application->cv && file_exists(public_path($application->cv))) {
            $merger->addFile(public_path($application->cv));
        }
        
        // 7. Tambahkan sertifikat jika ada (langsung file asli)
        if ($application->certificates) {
            foreach ($application->certificates as $certificate) {
                if ($certificate->certificate_file && file_exists(public_path($certificate->certificate_file))) {
                    $extension = strtolower(pathinfo($certificate->certificate_file, PATHINFO_EXTENSION));
                    
                    // Jika file adalah PDF, langsung tambahkan
                    if ($extension === 'pdf') {
                        $merger->addFile(public_path($certificate->certificate_file));
                    } 
                    // Jika file adalah gambar, konversi ke PDF sederhana
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
                                <img src="' . public_path($certificate->certificate_file) . '" alt="Sertifikat">
                            </body>
                            </html>
                        ');
                        
                        $imagePdfPath = $tempDir.'/temp_cert_image_'.$application->id.'_'.$certificate->id.'.pdf';
                        $imagePdf->save($imagePdfPath);
                        $merger->addFile($imagePdfPath);
                    }
                }
            }
        }
        
        // 8. Gabungkan semua PDF
        $mergedPdfPath = $tempDir.'/merged_'.$application->id.'.pdf';
        file_put_contents($mergedPdfPath, $merger->merge());
        
        // 9. Hapus file temporary
        if (file_exists($dataPdfPath)) @unlink($dataPdfPath);
        if (file_exists($questionsPdfPath)) @unlink($questionsPdfPath);
        
        // Hapus file temporary gambar PDF yang dibuat
        $tempFiles = glob($tempDir.'/temp_cert_image_'.$application->id.'_*.pdf');
        foreach ($tempFiles as $tempFile) {
            if (file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
        
        return $mergedPdfPath;
        
    } catch (\Exception $e) {
        Log::error("Error merging PDF: " . $e->getMessage());
        if (file_exists($dataPdfPath)) @unlink($dataPdfPath);
        if (file_exists($questionsPdfPath)) @unlink($questionsPdfPath);
        if (file_exists($mergedPdfPath)) @unlink($mergedPdfPath);
        
        // Hapus file temporary gambar PDF jika ada error
        $tempFiles = glob($tempDir.'/temp_cert_image_'.$application->id.'_*.pdf');
        foreach ($tempFiles as $tempFile) {
            if (file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
        
        throw $e;
    }
}
}