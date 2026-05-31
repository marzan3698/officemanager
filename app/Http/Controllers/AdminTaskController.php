<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class AdminTaskController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Task::with('employee')->latest('due_date');
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        $tasks = $query->get();
        return view('admin.tasks.index', compact('tasks', 'status'));
    }

    public function create()
    {
        $employees = User::where('role', 'employee')->where('is_active', true)->get();
        return view('admin.tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
        ]);
        
        $validated['assigned_by'] = auth()->id();

        Task::create($validated);
        
        return redirect('/admin/tasks')->with('success', 'কাজ তৈরি করা হয়েছে');
    }
}
