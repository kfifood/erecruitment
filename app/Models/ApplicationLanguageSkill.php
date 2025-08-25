<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationLanguageSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'language',
        'level',
        'description'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}