<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationSocialActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'organization',
        'address',
        'position',
        'year',
        'activity_type'
    ];

    protected $casts = [
        'year' => 'integer'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}