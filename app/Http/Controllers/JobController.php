<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Division;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('division', 'educations')->get();
        $divisions = Division::all();
        return view('jobs.index', compact('jobs', 'divisions'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'position' => 'required|string|max:255|unique:jobs,position',
                'description' => 'required|string',
                'qualification' => 'required|string',
                'experience' => 'nullable|integer|min:0|max:50',
                'education_levels' => 'required|array',
                'education_levels.*' => 'in:SMP,SMA,SMK,D3,D4,S1,S2',
                'division_id' => 'nullable|exists:divisions,id',
                'location' => 'required|string|max:255',
                'address' => 'nullable|string',
                'full_address' => 'nullable|string',
                'is_active' => 'boolean',
                'posted_date' => 'nullable|date',
                'closing_date' => 'nullable|date|after_or_equal:posted_date'
            ]);

            $job = Job::create($validated);

            // Save education levels
            foreach ($validated['education_levels'] as $level) {
                $job->educations()->create(['level' => $level]);
            }

            return redirect()->route('jobs.index')
                ->with('success', 'Job position created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating job position: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Job $job)
    {
        try {
            $validated = $request->validate([
                'position' => 'required|string|max:255|unique:jobs,position,'.$job->id,
                'description' => 'required|string',
                'qualification' => 'required|string',
                'experience' => 'nullable|integer|min:0|max:50',
                'education_levels' => 'required|array',
                'education_levels.*' => 'in:SMP,SMA,SMK,D3,D4,S1,S2',
                'division_id' => 'nullable|exists:divisions,id',
                'location' => 'required|string|max:255',
                'address' => 'nullable|string',
                'full_address' => 'nullable|string',
                'is_active' => 'boolean',
                'posted_date' => 'nullable|date',
                'closing_date' => 'nullable|date|after_or_equal:posted_date'
            ]);

            $job->update($validated);

            // Update education levels
            $job->educations()->delete();
            foreach ($validated['education_levels'] as $level) {
                $job->educations()->create(['level' => $level]);
            }

            return redirect()->route('jobs.index')
                ->with('success', 'Job position updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating job position: ' . $e->getMessage());
        }
    }

    public function destroy(Job $job)
    {
        try {
            $job->educations()->delete();
            $job->delete();
            
            return redirect()->route('jobs.index')
                ->with('success', 'Job position deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting job position: ' . $e->getMessage());
        }
    }
    public function show(Job $job)
{
    return view('jobs.show', compact('job'));
}
}