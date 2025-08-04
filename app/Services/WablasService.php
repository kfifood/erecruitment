<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;

class WablasService
{
    protected $apiKey;
    protected $baseUrl = 'https://bdg.wablas.com/api';

    public function __construct()
    {
        $this->apiKey = env('WABLAS_API_KEY');
    }


    public function sendMessage($phone, $message)
{
    $response = Http::withHeaders([
        'Authorization' => $this->apiKey
    ])->post("{$this->baseUrl}/send-message", [
        'phone' => $this->formatPhone($phone),
        'message' => $message
    ]);

    \Log::info('Wablas API Response', [
        'phone' => $phone,
        'status' => $response->status(),
        'response' => $response->json()
    ]);

    return $response;
}

public function sendInterviewResult($score)
    {
        $message = View::make('formats.whatsapp_result', compact('score'))->render();
        
        return $this->sendMessage(
            $this->formatPhone($score->interview->application->phone),
            $message
        );
    }


    // Untuk multiple messages (ke satpam + log admin)
    public function sendBulkMessages(array $messages)
    {
        $formatted = array_map(function($msg) {
            return [
                'phone' => $this->formatPhone($msg['phone']),
                'message' => $msg['message']
            ];
        }, $messages);

        return Http::withHeaders([
            'Authorization' => $this->apiKey
        ])->post("{$this->baseUrl}/send-message", [
            'data' => $formatted
        ]);
    }

    
    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return match (true) {
            str_starts_with($phone, '0') => '62' . substr($phone, 1),
            default => $phone
        };
    }
}