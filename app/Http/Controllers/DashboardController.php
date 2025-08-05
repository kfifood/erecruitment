<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Application;
use App\Models\Job;
use App\Models\Interview;
use App\Models\InterviewScore;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. Hitung statistik dasar
        $stats = [
            'totalEmployees' => Employee::count(),
            'totalApplicants' => Application::count(),
            'totalJobs' => Job::count(),
            'totalInterviewed' => Interview::where('interview_status', 'interviewed')->count()
        ];

        // 2. Data untuk Applications Chart (Seluruh hari dalam bulan berjalan)
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $currentMonth = Carbon::now()->format('F Y');

        // Generate semua tanggal dalam bulan ini
        $dateRange = collect();
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateRange->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Ambil data aplikasi yang ada
        $applicationsData = Application::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Gabungkan dengan semua tanggal
        $applicationsPerDay = $dateRange->map(function ($date) use ($applicationsData) {
            return [
                'date' => $date,
                'count' => $applicationsData->has($date) ? $applicationsData[$date]->count : 0
            ];
        });

        // 3. Data untuk Donut Chart (Hiring Decisions)
        $hiringDecisions = InterviewScore::query()
            ->select('decision', DB::raw('COUNT(*) as count'))
            ->groupBy('decision')
            ->get()
            ->pluck('count', 'decision')
            ->toArray();

        return view('dashboard', array_merge($stats, [
            'applicationsPerDay' => $applicationsPerDay,
            'hiringDecisions' => [
                'hired' => $hiringDecisions['hired'] ?? 0,
                'unhired' => $hiringDecisions['unhired'] ?? 0
            ],
            'currentMonth' => $currentMonth
        ]));
    }
}