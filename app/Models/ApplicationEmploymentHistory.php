<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationEmploymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'company_name',
        'address',
        'phone',
        'start_year',
        'end_year',
        'position',
        'business_type',
        'employee_count',
        'last_salary',
        'reason_for_leaving',
        'job_description'
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}