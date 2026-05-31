<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class AdminProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['employees', 'creator'])->latest()->get();
        $employees = User::where('role', 'employee')->get();
        return view('admin.projects.index', compact('projects', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:users,id',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_by' => auth()->id(),
        ]);

        if (!empty($validated['employee_ids'])) {
            $project->employees()->attach($validated['employee_ids']);
        }

        return back()->with('success', 'প্রজেক্ট সফলভাবে তৈরি হয়েছে');
    }
}
