<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewScore extends Model
{
    protected $fillable = [
        'interview_id',
        'appearance',
        'experience',
        'work_motivation',
        'problem_solving',
        'leadership',
        'communication',
        'job_knowledge',
        'discipline',
        'attitude',
        'special_score_1',
        'special_score_2',
        'special_score_3',
        'special_score_4',
        'special_score_5',
        'notes',
        'decision',
        'result_sent_at'
    ];

    protected $casts = [
        'result_sent_at' => 'datetime'
    ];

    public $incrementing = true;
    protected $keyType = 'int';

    protected $appends = ['final_score', 'final_category', 'is_result_sent'];


    // Tentukan kategori
    public function getFinalCategoryAttribute()
    {
        $total = $this->final_score;
        
        return match(true) {
            $total >= 7 && $total <= 35 => 'Tidak Disarankan',
            $total >= 36 && $total <= 70 => 'Cukup Disarankan',
            default => 'Disarankan'
        };
    }

    public function getIsResultSentAttribute()
    {
        return !is_null($this->result_sent_at);
    }

    // Relasi ke Interview
    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    /*public function score()
    {
        return $this->hasOne(InterviewScore::class);
    }*/
    public function specialScores()
    {
        return $this->hasMany(SpecialScore::class);
    }

    public function getFinalScoreAttribute()
    {
        $baseScore = $this->appearance + $this->experience + $this->work_motivation 
                   + $this->problem_solving + $this->leadership 
                   + $this->communication + $this->job_knowledge
                   +($this->discipline ?? 0) + ($this->attitude ?? 0);
        
        $specialScores = $this->specialScores->sum('score');
        
        return $baseScore + $specialScores;
    }
public function getAspectScores()
{
    return [
        'Appearance' => $this->appearance,
        'Experience' => $this->experience,
        'Work Motivation' => $this->work_motivation,
        'Problem Solving' => $this->problem_solving,
        'Leadership' => $this->leadership,
        'Communication' => $this->communication,
        'Job Knowledge' => $this->job_knowledge,
        'Discipline' => $this->discipline ?? 0,
        'Attitude' => $this->attitude ?? 0,
    ];
}

public function getHighestScoringAspects($count = 2)
{
    $scores = $this->getAspectScores();
    arsort($scores);
    return array_slice($scores, 0, $count, true);
}

public function getLowestScoringAspects($count = 2)
{
    $scores = $this->getAspectScores();
    asort($scores);
    return array_slice($scores, 0, $count, true);
}

// app/Models/InterviewScore.php

public function getRelativeStrengthsWeaknesses()
{
    $aspects = $this->getAspectScores(); // Menggunakan method yang sudah ada
    
    // Hitung rata-rata semua aspek
    $average = array_sum($aspects) / count($aspects);
    
    // Kategorikan aspek
    $strengths = [];
    $weaknesses = [];
    
    foreach ($aspects as $aspect => $score) {
        if ($score > $average) {
            $strengths[$aspect] = $score;
        } elseif ($score < $average) {
            $weaknesses[$aspect] = $score;
        }
    }
    
    // Urutkan dari yang paling menonjol/rendah
    arsort($strengths);
    asort($weaknesses);
    
    return [
        'strengths' => $strengths,
        'weaknesses' => $weaknesses,
        'average' => $average
    ];
}

public function getTopRelativeStrengths($count = 1)
{
    $analysis = $this->getRelativeStrengthsWeaknesses();
    return array_slice($analysis['strengths'], 0, $count, true);
}

public function getTopRelativeWeaknesses($count = 1)
{
    $analysis = $this->getRelativeStrengthsWeaknesses();
    return array_slice($analysis['weaknesses'], 0, $count, true);
}
}