<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,employee',
                'division' => 'required|in:management,finance_accounting,human_resources,information_technology,quality_assurance,marketing,technic,ppic,export_import,production',
            ]);

            User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'division' => $validated['division'],
            ]);

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'role' => 'required|in:admin,employee',
                'division' => 'required|in:management,finance_accounting,human_resources,information_technology,quality_assurance,marketing,technic,ppic,export_import,production',
            ]);

            $updateData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'division' => $validated['division'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}