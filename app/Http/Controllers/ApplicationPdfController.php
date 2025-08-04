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
        $application = Application::with('job')->findOrFail($id);
        $pdfPath = $this->generateMergedPdf($application);
        
        if (!file_exists($pdfPath)) {
            abort(500, "Gagal membuat file PDF gabungan");
        }
        
        return response()->file($pdfPath)->deleteFileAfterSend(true);
    }

    public function downloadPdf($id)
    {
        $application = Application::with('job')->findOrFail($id);
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
        $mergedPdfPath = '';

        try {
            // 1. Buat PDF data pelamar
            $dataPdf = Pdf::loadView('applications.pdf', ['application' => $application]);
            $dataPdfPath = $tempDir.'/temp_data_'.$application->id.'.pdf';
            $dataPdf->save($dataPdfPath);
            
            // 2. Inisialisasi PDF merger
            $merger = new Merger();
            $merger->addFile($dataPdfPath);
            
            // 3. Tambahkan cover letter jika ada
            if ($application->cover_letter && file_exists(public_path($application->cover_letter))) {
                $merger->addFile(public_path($application->cover_letter));
            }
            
            // 4. Tambahkan CV jika ada
            if ($application->cv && file_exists(public_path($application->cv))) {
                $merger->addFile(public_path($application->cv));
            }
            
            // 5. Gabungkan semua PDF
            $mergedPdfPath = $tempDir.'/merged_'.$application->id.'.pdf';
            file_put_contents($mergedPdfPath, $merger->merge());
            
            return $mergedPdfPath;
            
        } catch (\Exception $e) {
            Log::error("Error merging PDF: " . $e->getMessage());
            if (file_exists($dataPdfPath)) @unlink($dataPdfPath);
            if (file_exists($mergedPdfPath)) @unlink($mergedPdfPath);
            throw $e;
        }
    }
}