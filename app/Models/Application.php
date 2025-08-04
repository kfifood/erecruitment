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

    // Path accessors
    public function getPhotoUrlAttribute()
    {
        return asset($this->photo);
    }

    public function getCvUrlAttribute()
    {
        return asset($this->cv);
    }

    public function getCoverLetterUrlAttribute()
    {
        return asset($this->cover_letter);
    }

    public function hasBeenInterviewed(): bool
    {
        return $this->interview_status === 'interviewed';
    }

    public function syncInterviewStatus()
    {
        if ($this->interview) {
            $this->interview_status = $this->interview->interview_status;
            $this->save();
        }
    }
}