<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterviewLog;

class WhatsAppController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $data = $request->all();
        
        // Simpan status pengiriman
        InterviewLog::create([
            'message_id' => $data['id'],
            'status' => $data['status'],
            'phone' => $data['phone'],
            'timestamp' => now()
        ]);

        return response()->json(['status' => 'success']);
    }
}