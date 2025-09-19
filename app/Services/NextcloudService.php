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

    
public function uploadFile($file, $type, $originalName = null)
{
    try {
        Log::info('=== NEXTCLOUD UPLOAD DEBUG START ===');
        Log::info('Upload Type: ' . $type);
        Log::info('Original Filename: ' . ($originalName ?: $file->getClientOriginalName()));
        
        $folder = $this->folders[$type] ?? $type;
        $originalName = $originalName ?: $file->getClientOriginalName();
        $cleanName = $this->sanitizeFilename($originalName);
        
        Log::info('Target Folder: ' . $folder);
        Log::info('Cleaned Filename: ' . $cleanName);
        
        $remotePath = $this->webdavPath . '/' . $folder . '/' . $cleanName;
        $baseUrl = rtrim($this->baseUrl, '/');
        $fullUrl = $baseUrl . $remotePath;

        Log::info('Remote Path: ' . $remotePath);
        Log::info('Full URL: ' . $fullUrl);
        Log::info('Username: ' . $this->username);
        Log::info('WebDAV Path: ' . $this->webdavPath);

        // Pastikan folder exists
        if (!$this->ensureFolderExists($folder)) {
            Log::error("Folder '$folder' tidak dapat dibuat atau diakses");
            return null;
        }

        Log::info('Folder check passed, proceeding with upload...');

        // Upload file
        $response = Http::withBasicAuth($this->username, $this->password)
            ->timeout(60)
            ->withHeaders([
                'Content-Type' => $file->getMimeType(),
            ])
            ->withBody(fopen($file->getRealPath(), 'r'), $file->getMimeType())
            ->put($fullUrl);

        Log::info('HTTP Status: ' . $response->status());
        Log::info('HTTP Response: ' . $response->body());

        if ($response->successful()) {
            Log::info('Upload successful: ' . $remotePath);
            Log::info('=== NEXTCLOUD UPLOAD DEBUG END (SUCCESS) ===');
            return $remotePath;
        }

        Log::error('Upload failed. Status: ' . $response->status());
        Log::error('Response: ' . $response->body());
        Log::info('=== NEXTCLOUD UPLOAD DEBUG END (FAILED) ===');
        return null;

    } catch (\Exception $e) {
        Log::error('Nextcloud upload exception: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        Log::info('=== NEXTCLOUD UPLOAD DEBUG END (EXCEPTION) ===');
        return null;
    }
}
// NextcloudService.php
public function ensureFolderExists($folderPath)
{
    try {
        $fullUrl = rtrim($this->baseUrl, '/') . $this->webdavPath . '/' . $folderPath;
        
        // Cek apakah folder sudah ada
        $checkResponse = Http::withBasicAuth($this->username, $this->password)
            ->timeout(10)
            ->get($fullUrl);
            
        // Jika folder ada (status 200)
        if ($checkResponse->status() === 200) {
            Log::info("Folder '$folderPath' sudah ada");
            return true;
        }
        
        // Jika folder tidak ada (status 404), buat folder
        if ($checkResponse->status() === 404) {
            Log::info("Folder '$folderPath' tidak ada, mencoba membuat...");
            
            $createResponse = Http::withBasicAuth($this->username, $this->password)
                ->timeout(10)
                ->send('MKCOL', $fullUrl);
                
            if ($createResponse->successful()) {
                Log::info("Folder '$folderPath' berhasil dibuat");
                return true;
            }
            
            Log::error("Gagal membuat folder '$folderPath': " . $createResponse->status());
            return false;
        }
        
        Log::error("Error checking folder '$folderPath': " . $checkResponse->status());
        return false;
        
    } catch (\Exception $e) {
        Log::error('Folder check error: ' . $e->getMessage());
        return false;
    }
}
// NextcloudService.php
public function checkFolderPermissions($folder)
{
    try {
        $folderPath = $this->webdavPath . '/' . $folder;
        $fullUrl = rtrim($this->baseUrl, '/') . $folderPath;
        
        // Coba dengan GET request sederhana dulu
        $response = Http::withBasicAuth($this->username, $this->password)
            ->timeout(30)
            ->get($fullUrl);
            
        if ($response->status() === 200) {
            return [
                'exists' => true,
                'writable' => true, // Asumsi bisa write jika bisa read
                'status' => $response->status()
            ];
        }
        
        // Jika folder tidak ada, coba buat folder
        if ($response->status() === 404) {
            return [
                'exists' => false,
                'writable' => $this->createFolder($folder),
                'status' => $response->status()
            ];
        }
        
        return [
            'exists' => false,
            'writable' => false,
            'status' => $response->status(),
            'response' => $response->body()
        ];
        
    } catch (\Exception $e) {
        Log::error('Folder permission check error: ' . $e->getMessage());
        return [
            'exists' => false,
            'writable' => false,
            'error' => $e->getMessage()
        ];
    }
}

public function createFolder($folder)
{
    try {
        $folderPath = $this->webdavPath . '/' . $folder;
        $fullUrl = rtrim($this->baseUrl, '/') . $folderPath;
        
        $response = Http::withBasicAuth($this->username, $this->password)
            ->timeout(30)
            ->send('MKCOL', $fullUrl);
            
        return $response->successful();
        
    } catch (\Exception $e) {
        Log::error('Create folder error: ' . $e->getMessage());
        return false;
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