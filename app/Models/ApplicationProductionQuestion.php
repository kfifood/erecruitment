<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationProductionQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'shift_work',
        'piecework_system',
        'position_transfer',
        'organization_experience',
        'organization_explanation',
        'current_sickness',
        'recent_sickness',
        'typhoid',
        'hepatitis',
        'tuberculosis',
        'cyst',
        'police_record',
        'color_blind',
        'contagious_disease',
        'current_contract',
        'disliked_job_types',
        'computer_machine_skills'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}