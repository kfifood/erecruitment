<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NextcloudService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $webdavPath;
    protected $folders;

    public function __construct()
    {
        $config = config('services.nextcloud');
        $this->baseUrl = $config['base_url'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->webdavPath = $config['webdav_path'];
        $this->folders = $config['folders'];
    }

    /**
     * Upload file ke Nextcloud
     */
    public function uploadFile($file, $type, $originalName = null)
    {
        try {
            $folder = $this->folders[$type] ?? $type;
            $originalName = $originalName ?: $file->getClientOriginalName();
            $cleanName = $this->sanitizeFilename($originalName);
            
            $remotePath = $this->webdavPath . '/' . $folder . '/' . $cleanName;
            $fullUrl = $this->baseUrl . $remotePath;

            // Upload file menggunakan PUT request
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'Content-Type' => $file->getMimeType(),
                ])
                ->withBody(fopen($file->getRealPath(), 'r'), $file->getMimeType())
                ->put($fullUrl);

            if ($response->successful()) {
                return $remotePath; // Return path yang disimpan di Nextcloud
            }

            Log::error('Nextcloud upload failed: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Nextcloud upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Hapus file dari Nextcloud
     */
    public function deleteFile($filePath)
    {
        try {
            $fullUrl = $this->baseUrl . $filePath;
            
            $response = Http::withBasicAuth($this->username, $this->password)
                ->delete($fullUrl);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Nextcloud delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Download file dari Nextcloud
     */
    public function downloadFile($filePath)
    {
        try {
            $fullUrl = $this->baseUrl . $filePath;
            
            $response = Http::withBasicAuth($this->username, $this->password)
                ->get($fullUrl);

            if ($response->successful()) {
                return $response->body();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Nextcloud download error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Membersihkan nama file
     */
    private function sanitizeFilename($filename)
    {
        $clean = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", $filename);
        $clean = preg_replace("/_+/", "_", $clean);
        $clean = trim($clean, "_");
        
        return $clean ?: 'file_' . time();
    }

    /**
     * Generate public share link (jika diperlukan)
     */
    public function createShareLink($filePath)
    {
        try {
            $shareUrl = $this->baseUrl . '/ocs/v2.php/apps/files_sharing/api/v1/shares';
            
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'OCS-APIRequest' => 'true',
                    'Content-Type' => 'application/json',
                ])
                ->post($shareUrl, [
                    'path' => $filePath,
                    'shareType' => 3, // Public link
                    'permissions' => 1 // Read only
                ]);

            if ($response->successful()) {
                $data = simplexml_load_string($response->body());
                return (string)$data->data->url;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Nextcloud share error: ' . $e->getMessage());
            return null;
        }
    }
}