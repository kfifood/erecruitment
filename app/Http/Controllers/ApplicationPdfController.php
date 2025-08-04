<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
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
        try {
            // 1. Buat PDF data pelamar
            $dataPdf = Pdf::loadView('applications.pdf', ['application' => $application]);
            $dataPdfPath = storage_path('app/public/temp_data_'.$application->id.'.pdf');
            $dataPdf->save($dataPdfPath);
            
            // 2. Inisialisasi PDF merger
            $merger = new Merger();
            $merger->addFile($dataPdfPath);
            
            // 3. Tambahkan cover letter jika ada
            if ($application->cover_letter && Storage::disk('public')->exists($application->cover_letter)) {
                $merger->addFile(Storage::disk('public')->path($application->cover_letter));
            }
            
            // 4. Tambahkan CV jika ada
            if ($application->cv && Storage::disk('public')->exists($application->cv)) {
                $merger->addFile(Storage::disk('public')->path($application->cv));
            }
            
            // 5. Gabungkan semua PDF
            $mergedPdfPath = storage_path('app/public/merged_'.$application->id.'.pdf');
            file_put_contents($mergedPdfPath, $merger->merge());
            
            // 6. Hapus file temporary
            @unlink($dataPdfPath);
            
            return $mergedPdfPath;
            
        } catch (\Exception $e) {
            Log::error("Error merging PDF: " . $e->getMessage());
            @unlink($dataPdfPath ?? '');
            throw $e;
        }
    }
}