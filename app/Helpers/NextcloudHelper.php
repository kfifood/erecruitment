<?php

use App\Services\NextcloudService;

if (!function_exists('nextcloud_url')) {
    /**
     * Generate URL untuk mengakses file di Nextcloud
     */
    function nextcloud_url($filePath)
    {
        if (empty($filePath)) {
            return null;
        }
        
        // Jika file disimpan di Nextcloud
        if (strpos($filePath, '/remote.php/dav') === 0) {
            $config = config('services.nextcloud');
            return $config['base_uri'] . '/apps/dav' . $filePath;
        }
        
        // Jika file disimpan secara lokal
        return asset($filePath);
    }
}

if (!function_exists('nextcloud_download')) {
    /**
     * Download file dari Nextcloud
     */
    function nextcloud_download($filePath, $filename = null)
    {
        $nextcloudService = new NextcloudService();
        $fileContent = $nextcloudService->downloadFile($filePath);
        
        if ($fileContent === false) {
            return false;
        }
        
        $filename = $filename ?? basename($filePath);
        
        return response($fileContent)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}