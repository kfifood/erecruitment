<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';
    protected $fillable = [
        'position',
        'qualification',
        'division_id',
        'location',
        'address',
        'full_address',
        'is_active',
        'posted_date',
        'closing_date',
        'experience',
        'usia',
        'gender',
        'recruitment_type'
    ];

    // app/Models/Job.php
protected $casts = [
    'is_active' => 'boolean',
    'posted_date' => 'date',
    'closing_date' => 'date',
    'experience' => 'integer',
    'usia' => 'integer',
    'gender' => 'array',
    'recruitment_type' => 'array'
];
    

  protected $with = ['division'];
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function educations()
{
    return $this->hasMany(JobEducation::class);
}
}
