<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'interviewer_id',
        'interview_date',
        'interview_time',
        'method',
        'notes',
        'invitation_sent_at',
        'security_notification_sent_at',
        'security_notification_status',
        'interview_status'
    ];

    protected $casts = [
        'interview_date' => 'date',
    'interview_time' => 'datetime:H:i',
    'invitation_sent_at' => 'datetime',
    'security_notification_sent_at'=> 'datetime'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'interviewer_id');
    }

    // Helper untuk mendapatkan lokasi dari job terkait
    public function getLocationAttribute(): string
    {
        return $this->application->job->location;
    }

    // Helper untuk format tanggal & waktu
    public function getFormattedInterviewTimeAttribute(): string
    {
        return $this->interview_date->format('d M Y') . ' ' . $this->interview_time->format('H:i');
    }

    // Helper untuk cek apakah sudah notif satpam
    public function isSecurityNotified(): bool
    {
        return !is_null($this->security_notification_sent_at);
    }
    // Tambahkan method untuk menandai interview selesai
    public function markAsInterviewed()
    {
        $this->update(['interview_status' => 'interviewed']);
    }

    // Tambahkan method untuk cek status interview
    public function isInterviewed(): bool
    {
        return $this->interview_status === 'interviewed';
    }

    public function score()
    {
        return $this->hasOne(InterviewScore::class);
    }
}