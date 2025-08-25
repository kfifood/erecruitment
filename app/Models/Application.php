<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'job_type',
        'full_name',
        'email',
        'phone',
        'address',
        'birth_place',
        'birth_date',
        'gender',
        'home_phone',
        'id_number',
        'religion',
        'ethnicity',
        'height',
        'weight',
        'house_ownership',
        'vehicle_ownership',
        'marital_status',
        'family_members',
        'photo',
        'cv',
        'cover_letter',
        'status',
        'interview_status',
        'strengths',
        'weaknesses'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'birth_date' => 'date',
    ];
    
    // Tambahkan relasi ke tabel-tabel baru
    public function educations()
    {
        return $this->hasMany(ApplicationEducation::class);
    }
    
    public function certificates()
    {
        return $this->hasMany(ApplicationCertificate::class);
    }
    
    public function references()
    {
        return $this->hasMany(ApplicationReference::class);
    }
    
    public function emergencyContacts()
    {
        return $this->hasMany(ApplicationEmergencyContact::class);
    }
    
    public function languageSkills()
    {
        return $this->hasMany(ApplicationLanguageSkill::class);
    }
    
    public function computerSkills()
    {
        return $this->hasMany(ApplicationComputerSkill::class);
    }
    
    public function socialActivities()
    {
        return $this->hasMany(ApplicationSocialActivity::class);
    }
    
    public function employmentHistories()
    {
        return $this->hasMany(ApplicationEmploymentHistory::class);
    }
    
    public function familyMembers()
    {
        return $this->hasMany(ApplicationFamilyMember::class);
    }
    // Tambahkan relasi baru
    public function questions()
    {
    return $this->hasOne(ApplicationQuestion::class);
    }
    public function productionQuestions()
    {
    return $this->hasOne(ApplicationProductionQuestion::class);
    }
    
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

    protected static function booted()
    {
        static::creating(function ($application) {
            if ($application->job) {
                $application->job_type = $application->job->recruitment_type;
            }
        });
    }

    // Accessor untuk job_type
    public function getJobTypeNameAttribute()
    {
        return $this->job_type ? ucfirst(str_replace('_', ' ', $this->job_type)) : null;
    }
}