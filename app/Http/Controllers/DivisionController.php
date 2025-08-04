<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::all();
        return view('divisions.index', compact('divisions'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:divisions,name',
                'description' => 'nullable|string'
            ]);

            Division::create($request->all());

            return redirect()->route('divisions.index')
                ->with('success', 'Division created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating division: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Division $division)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:divisions,name,'.$division->id,
                'description' => 'nullable|string'
            ]);

            $division->update($request->all());

            return redirect()->route('divisions.index')
                ->with('success', 'Division updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating division: ' . $e->getMessage());
        }
    }

    public function destroy(Division $division)
    {
        try {
            $division->delete();
            return redirect()->route('divisions.index')
                ->with('success', 'Division deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting division: ' . $e->getMessage());
        }
    }
}