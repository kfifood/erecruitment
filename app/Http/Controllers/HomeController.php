<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
{
    $jobs = Job::where('is_active', true) // Ubah 'true' menjadi boolean true
               ->latest()
               ->take(6)
               ->get();
    return view('welcome', compact('jobs'));
}
    public function detail_job(Job $job)
    {
        return view('detail_jobs', compact('job'));
    }


}