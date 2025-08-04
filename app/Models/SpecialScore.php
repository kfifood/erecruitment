<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialScore extends Model
{
    use HasFactory;
    protected $fillable = ['interview_score_id', 'criteria_name', 'score'];

}
