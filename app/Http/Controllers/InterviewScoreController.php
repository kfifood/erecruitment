<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\InterviewScore;
use Illuminate\Http\Request;
use App\Services\WablasService;

class InterviewScoreController extends Controller
{
    public function index()
{
    // Pastikan hanya mengambil data yang memiliki relasi lengkap
    $scores = InterviewScore::with([
        'interview' => function($query) {
            $query->with(['application.job', 'interviewer.user']);
        }
    ])
    ->whereHas('interview', function($query) {
        $query->where('interview_status', 'interviewed')
              ->whereHas('application')
              ->whereHas('interviewer');
    })
    ->latest()
    ->paginate(10);

    $interviews = Interview::where('interview_status', 'interviewed')
        ->whereDoesntHave('score')
        ->with(['application.job', 'interviewer.user'])
        ->whereHas('application')
        ->whereHas('interviewer')
        ->latest()
        ->paginate(10);

    $hasInterviewData = Interview::where('interview_status', 'interviewed')->exists();

    return view('interview-scores.index', compact('scores', 'interviews', 'hasInterviewData'));
}
    // Form create penilaian
    public function create(Interview $interview)
    {
        return view('interview-scores.create', compact('interview'));
    }

    // Simpan penilaian
  public function store(Request $request, Interview $interview)
{
    // Debugging - pastikan interview terload
    \Log::info('Processing interview score for interview: ' . $interview->id);
    
    $validated = $request->validate([
        'appearance' => 'required|integer|between:1,9',
        'experience' => 'required|integer|between:1,9',
        'work_motivation' => 'required|integer|between:1,9',
        'problem_solving' => 'required|integer|between:1,9',
        'leadership' => 'required|integer|between:1,9',
        'communication' => 'required|integer|between:1,9',
        'job_knowledge' => 'required|integer|between:1,9',
        'discipline' => 'nullable|integer|between:1,9',
        'attitude' => 'nullable|integer|between:1,9',
        'notes' => 'nullable|string',
        'special_criteria' => 'nullable|array',
        'special_criteria.*.name' => 'nullable|string|max:255',
        'special_criteria.*.score' => 'nullable|integer|between:1,9'
    ]);

    // PASTIKAN menggunakan relasi untuk menyimpan
    $interviewScore = $interview->score()->create($validated);

    // Simpan penilaian khusus
    if ($request->has('special_criteria')) {
        $interviewScore->specialScores()->createMany(
            collect($request->special_criteria)->map(function($item) {
                return [
                    'criteria_name' => $item['name'],
                    'score' => $item['score']
                ];
            })->all()
        );
    }

    return redirect()->route('interviews.index')
           ->with('success', 'Penilaian berhasil disimpan!');
}

 public function updateDecision(Request $request, InterviewScore $score)
    {
        $validated = $request->validate([
            'decision' => 'required|in:hired,unhired'
        ]);

        $score->update($validated);

        return redirect()->route('interview-scores.office-undecided')
               ->with('success', 'Keputusan berhasil diperbarui!');
    }

    public function sendInterviewResult(InterviewScore $score)
    {
        if (empty($score->decision)) {
            return redirect()->back()
                   ->with('error', 'Keputusan belum ditentukan');
        }

        if (empty($score->interview->application->phone)) {
            return redirect()->back()
                   ->with('error', 'Nomor telepon kandidat tidak tersedia');
        }

        $wablas = new WablasService();
        $response = $wablas->sendInterviewResult($score);

        if ($response->successful()) {
            $score->update(['result_sent_at' => now()]);
            return redirect()->back()
                   ->with('success', 'Hasil interview berhasil dikirim!');
        }

        return redirect()->back()
               ->with('error', 'Gagal mengirim: ' . ($response->json()['message'] ?? 'Error tidak diketahui'));
    }
    // Method baru untuk API
public function getInterviewData(Interview $interview)
{
    return response()->json([
        'id' => $interview->id,
        'application' => [
            'full_name' => $interview->application->full_name,
            'job' => ['position' => $interview->application->job->position]
        ],
        'interviewer' => ['name' => $interview->interviewer->name],
        'interview_date' => $interview->interview_date
    ]);
}

public function getEligibleInterview()
{
    $interview = Interview::whereDoesntHave('score')
                 ->with(['application.job', 'interviewer'])
                 ->where('status', 'completed')
                 ->first();

    return response()->json(['interview' => $interview]);
}

public function unscored()
{
    $interviews = Interview::where('interview_status', 'interviewed')
        ->whereDoesntHave('score')
        ->with(['application.job', 'interviewer.user'])
        ->paginate(10);
        
    return view('interview-scores.unscored', compact('interviews'));
}

public function hired()
{
    $scores = InterviewScore::where('decision', 'hired')
        ->with(['interview.application.job', 'interview.interviewer.user'])
        ->paginate(10);
        
    return view('interview-scores.office-hired', compact('scores'));
}

public function unhired()
{
    $scores = InterviewScore::where('decision', 'unhired')
        ->with(['interview.application.job', 'interview.interviewer.user'])
        ->paginate(10);
        
    return view('interview-scores.unhired', compact('scores'));
}

public function undecided()
{
    $scores = InterviewScore::whereNull('decision')
        ->with(['interview.application.job', 'interview.interviewer.user'])
        ->paginate(10);
        
    return view('interview-scores.undecided', compact('scores'));
}

// InterviewScoreController.php

// Tambahkan method destroy untuk delete
public function destroy(InterviewScore $interviewScore)
{
    try {
        $interviewScore->delete();
        return redirect()->back()->with('success', 'Data penilaian berhasil dihapus');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
}

// Method untuk menampilkan detail
public function show(InterviewScore $interviewScore)
{
    // Pastikan hanya yang hired yang bisa dilihat detailnya
    if ($interviewScore->decision !== 'hired') {
        abort(404);
    }

    return view('interview-scores.show', compact('interviewScore'));
}

// app/Http/Controllers/InterviewScoreController.php
public function unhiredDetail($id)
{
    $score = InterviewScore::with([
                'interview.application.job',
                'interview.interviewer.user',
                'specialScores'
            ])->findOrFail($id);
    
    // Pastikan hanya menampilkan yang statusnya unhired
    if ($score->decision !== 'unhired') {
        abort(404);
    }

    return view('interview-scores.unhired-detail', compact('score'));
}


// Office Unscored
    public function officeUnscored()
    {
        $interviews = Interview::where('interview_status', 'interviewed')
            ->whereDoesntHave('score')
            ->whereHas('application', function($query) {
                $query->where('job_type', 'office');
            })
            ->with(['application.job', 'interviewer.user'])
            ->paginate(10);
            
        return view('interview-scores.office-unscored', compact('interviews'));
    }

    // Office Undecided
    public function officeUndecided()
    {
        $scores = InterviewScore::whereNull('decision')
            ->whereHas('interview.application', function($query) {
                $query->where('job_type', 'office');
            })
            ->with(['interview.application.job', 'interview.interviewer.user'])
            ->paginate(10);
            
        return view('interview-scores.office-undecided', compact('scores'));
    }

    // Office Hired
    public function officeHired()
    {
        $scores = InterviewScore::where('decision', 'hired')
            ->whereHas('interview.application', function($query) {
                $query->where('job_type', 'office');
            })
            ->with(['interview.application.job', 'interview.interviewer.user'])
            ->paginate(10);
            
        return view('interview-scores.office-hired', compact('scores'));
    }

    // Office Unhired
    public function officeUnhired()
    {
        $scores = InterviewScore::where('decision', 'unhired')
            ->whereHas('interview.application', function($query) {
                $query->where('job_type', 'office');
            })
            ->with(['interview.application.job', 'interview.interviewer.user'])
            ->paginate(10);
            
        return view('interview-scores.office-unhired', compact('scores'));
    }

    // Production Unscored
    public function productionUnscored()
    {
        $interviews = Interview::where('interview_status', 'interviewed')
            ->whereDoesntHave('score')
            ->whereHas('application', function($query) {
                $query->where('job_type', 'production');
            })
            ->with(['application.job', 'interviewer.user'])
            ->paginate(10);
            
        return view('interview-scores.production-unscored', compact('interviews'));
    }

    // Production Undecided
    public function productionUndecided()
    {
        $scores = InterviewScore::whereNull('decision')
            ->whereHas('interview.application', function($query) {
                $query->where('job_type', 'production');
            })
            ->with(['interview.application.job', 'interview.interviewer.user'])
            ->paginate(10);
            
        return view('interview-scores.production-undecided', compact('scores'));
    }

    // Production Hired
    public function productionHired()
    {
        $scores = InterviewScore::where('decision', 'hired')
            ->whereHas('interview.application', function($query) {
                $query->where('job_type', 'production');
            })
            ->with(['interview.application.job', 'interview.interviewer.user'])
            ->paginate(10);
            
        return view('interview-scores.production-hired', compact('scores'));
    }

    // Production Unhired
    public function productionUnhired()
    {
        $scores = InterviewScore::where('decision', 'unhired')
            ->whereHas('interview.application', function($query) {
                $query->where('job_type', 'production');
            })
            ->with(['interview.application.job', 'interview.interviewer.user'])
            ->paginate(10);
            
        return view('interview-scores.production-unhired', compact('scores'));
    }

    // Store Production Score
    public function storeProduction(Request $request)
    {
        $validated = $request->validate([
            'interview_id' => 'required|exists:interviews,id',
            'recommendation' => 'required|in:recommended,considered,not_recommended',
            'notes' => 'nullable|string'
        ]);

        // Simpan data penilaian produksi
        $score = InterviewScore::create([
            'interview_id' => $validated['interview_id'],
            'recommendation' => $validated['recommendation'],
            'notes' => $validated['notes'],
            // Field lain bisa diisi default atau dikosongkan
            'appearance' => 0,
            'experience' => 0,
            'work_motivation' => 0,
            'problem_solving' => 0,
            'leadership' => 0,
            'communication' => 0,
            'job_knowledge' => 0,
            'final_score' => $this->calculateProductionScore($validated['recommendation'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Penilaian produksi berhasil disimpan'
        ]);
    }

    private function calculateProductionScore($recommendation)
    {
        // Beri skor berdasarkan rekomendasi
        switch($recommendation) {
            case 'recommended': return 9;
            case 'considered': return 6;
            case 'not_recommended': return 3;
            default: return 0;
        }
    }
}