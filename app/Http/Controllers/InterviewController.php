<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Application;
use App\Models\Employee;
use App\Models\SecurityContact;
use Illuminate\Http\Request;
use App\Services\WablasService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InterviewController extends Controller
{
    public function index()
    {
        $interviews = Interview::with(['application.job', 'interviewer.user'])
            ->orderBy('interview_date', 'desc')
            ->orderBy('interview_time', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->interview_date->format('Y-m-d');
            });

        $applications = Application::where('status', 'interview')
            ->whereDoesntHave('interview')
            ->orWhereHas('interview', function($query) {
                $query->where('interview_status', 'not yet');
            })
            ->with('job')
            ->get();
            
        $interviewers = Employee::with('user')->get();

        return view('interviews.index', compact('interviews', 'applications', 'interviewers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'interviewer_id' => 'required|exists:employees,id',
            'interview_date' => 'required|date',
            'interview_time' => 'required',
            'method' => 'required|in:offsite,online',
            'notes' => 'nullable|string'
        ]);

        // Set default interview status
        $validated['interview_status'] = 'not yet';

        // Update application status
        Application::where('id', $validated['application_id'])
            ->update(['status' => 'interview']);

        $interview = Interview::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Interview scheduled successfully',
            'data' => $interview
        ]);
    }

    public function edit(Interview $interview)
{
    $interview->load(['application.job', 'interviewer.user']);

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $interview->id,
            'application' => [
                'full_name' => $interview->application->full_name,
                'job' => [
                    'position' => $interview->application->job->position
                ]
            ],
            'interviewer_id' => $interview->interviewer_id,
            'interview_date' => $interview->interview_date->format('Y-m-d'),
            'interview_time' => $interview->interview_time->format('H:i'), // Format ke H:i saja
            'method' => $interview->method,
            'notes' => $interview->notes,
            'interview_status' => $interview->interview_status ?? 'not yet' // Pastikan ada default value
        ]
    ]);
}

    public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'interviewer_id' => 'required|exists:employees,id',
            'interview_date' => 'required|date',
            'interview_time' => 'required',
            'method' => 'required|in:offsite,online',
            'notes' => 'nullable|string',
            'interview_status' => 'required|in:not yet,interviewed'
        ]);

        $interview->update($validated);

        // Update application interview status if changed
        if ($interview->wasChanged('interview_status')) {
            $interview->application->update([
                'interview_status' => $validated['interview_status']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Interview updated successfully',
            'data' => $interview
        ]);
    }

    public function destroy(Interview $interview)
    {
        $interview->delete();
        
        return redirect()->route('interviews.index')
            ->with('success', 'Interview deleted successfully!');
    }

    public function markAsInterviewed(Interview $interview)
    {
        DB::transaction(function () use ($interview) {
            $interview->update(['interview_status' => 'interviewed']);
            $interview->application->update(['interview_status' => 'interviewed']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Interview marked as completed'
        ]);
    }

    public function sendInvitation(Interview $interview)
    {
        try {
            $wablas = new WablasService();
            $message = $this->generateCandidateMessage($interview);

            $response = $wablas->sendMessage(
                $interview->application->phone,
                $message
            );

            if ($response->successful()) {
                $interview->update(['invitation_sent_at' => now()]);
                return back()->with('success', 'Undangan berhasil dikirim!');
            }

            return back()->with('error', 'Gagal mengirim: ' . $response->json()['message']);
        } catch (\Exception $e) {
            Log::error('Error sending invitation: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem');
        }
    }

public function notifySecurity($date)
{
    try {
        $interviews = Interview::whereDate('interview_date', $date)
            ->where('method', 'offsite')
            ->with(['application.job'])
            ->get();

        if ($interviews->isEmpty()) {
            return back()->with('warning', 'Tidak ada jadwal offline di tanggal ini');
        }

        // Ambil nomor security yang aktif
        $securityContacts = SecurityContact::where('is_active', true)->get();
        
        if ($securityContacts->isEmpty()) {
            return back()->with('error', 'Tidak ada kontak security yang aktif');
        }

        $wablas = new WablasService();
        $message = $this->generateSecurityMessage($interviews);

        foreach ($securityContacts as $contact) {
            $response = $wablas->sendMessage(
                $contact->phone,
                $message
            );
            
            // Log pengiriman jika perlu
            Log::info("Notifikasi dikirim ke {$contact->name}", [
                'phone' => $contact->phone,
                'status' => $response->successful()
            ]);
        }

        Interview::whereDate('interview_date', $date)
            ->update([
                'security_notification_sent_at' => now(),
                'security_notification_status' => 'notified'
            ]);
            
        return back()->with('success', 'Notifikasi berhasil dikirim ke '.$securityContacts->count().' security!');
    } catch (\Exception $e) {
        Log::error('Error notifying security: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan sistem');
    }
}

    private function generateCandidateMessage(Interview $interview): string
    {
        return view('formats.whatsapp_invitation', [
            'interview' => $interview
        ])->render();
    }

    private function generateSecurityMessage($interviews): string
    {
        $firstInterview = $interviews->first();
        
        return view('formats.security_notification', [
            'date' => $firstInterview->interview_date->format('d F Y'),
            'time' => $firstInterview->interview_time->format('H:i'),
            'location' => $firstInterview->application->job->location,
            'interviews' => $interviews
        ])->render();
    }
}