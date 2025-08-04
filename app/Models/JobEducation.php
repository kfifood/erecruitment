<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobEducation extends Model
{
    use HasFactory;
     protected $table = 'job_educations'; // Tambahkan ini
    protected $fillable = ['job_id', 'level'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
