<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationEducation extends Model
{
    use HasFactory;

    protected $table = 'application_educations'; // Tambahkan ini
    protected $fillable = [
        'application_id',
        'education_level',
        'school_name',
        'city',
        'major',
        'start_year',
        'end_year'
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