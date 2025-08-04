<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class PublicJobController extends Controller
{
    public function show(Job $job)
    {
        return view('jobs.public_show', compact('job'));
    }
}