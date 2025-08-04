<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'full_name',
        'email',
        'phone',
        'address',
        'education',
        'major',
        'study_program',
        'birth_date',
        'photo',
        'cv',
        'cover_letter',
        'status',
        'interview_status'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'birth_date' => 'date',
    ];
    
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
    
    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }
    // Di dalam model Application
    public function getCvPathAttribute()
    {
        return Storage::disk('public')->path($this->cv);
    }

    public function getCoverLetterPathAttribute()
    {
        return Storage::disk('public')->path($this->cover_letter);
    }
    // Tambahkan method untuk cek status interview
    public function hasBeenInterviewed(): bool
    {
        return $this->interview_status === 'interviewed';
    }

    // Method untuk sinkronisasi manual jika diperlukan
    public function syncInterviewStatus()
    {
        if ($this->interview) {
            $this->interview_status = $this->interview->interview_status;
            $this->save();
        }
    }
}