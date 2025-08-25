<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'name',
        'city',
        'organizer',
        'year',
        'certificate_file'
    ];

    protected $casts = [
        'year' => 'integer'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getCertificateFileUrlAttribute()
    {
        return $this->certificate_file ? asset($this->certificate_file) : null;
    }
}