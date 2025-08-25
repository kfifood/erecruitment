<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'question_1',
        'question_1_explanation',
        'question_2',
        'question_3',
        'question_3_explanation',
        'question_4',
        'question_5',
        'question_5_explanation',
        'question_6',
        'question_6_explanation',
        'question_7',
        'question_7_explanation',
        'question_8',
        'question_8_explanation',
        'question_9',
        'question_10',
        'question_10_explanation',
        'question_11',
        'question_11_explanation',
        'question_12',
        'question_12_explanation',
        'question_13',
        'question_14',
        'question_15',
        'question_16',
    ];

    protected $casts = [
        'question_16' => 'date',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}