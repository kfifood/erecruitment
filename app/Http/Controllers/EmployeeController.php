<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->get();
        $users = User::where('role', 'Employee')
               ->whereDoesntHave('employee')
               ->get();
        return view('employees.index', compact('employees', 'users'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id|unique:employees,user_id',
                'position' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string'
            ]);

            Employee::create($validated);

            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Employee $employee)
    {
        try {
            $validated = $request->validate([
                'position' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string'
            ]);

            $employee->update($validated);

            return redirect()->route('employees.index')
                ->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return redirect()->route('employees.index')
                ->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
}