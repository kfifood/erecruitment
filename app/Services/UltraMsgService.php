<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UltraMsgService
{
    protected $instanceId;
    protected $token;
    protected $apiUrl;

    public function __construct()
    {
        $this->instanceId = env('ULTRAMSG_INSTANCE_ID');
        $this->token = env('ULTRAMSG_TOKEN');
        $this->apiUrl = env('ULTRAMSG_API_URL');
    }

    /**
     * Mengirim pesan WhatsApp via UltraMSG API
     */
    public function sendMessage(string $phone, string $message): array
    {
        try {
            $response = Http::post("{$this->apiUrl}/{$this->instanceId}/messages/chat", [
                'token' => $this->token,
                'to' => $this->formatPhone($phone),
                'body' => $message
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            Log::error('UltraMSG API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format nomor HP ke format internasional
     */
    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return match (true) {
            str_starts_with($phone, '0') => '62' . substr($phone, 1),
            str_starts_with($phone, '62') => $phone,
            default => '62' . $phone
        };
    }
}