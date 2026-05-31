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
        $query = Task::with('employee', 'project')->latest('due_date');
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        $tasks = $query->get();
        $employees = User::where('role', 'employee')->get();
        $projects = \App\Models\Project::where('status', 'active')->get();
        return view('admin.tasks.index', compact('tasks', 'status', 'employees', 'projects'));
    }

    public function create()
    {
        $employees = User::where('role', 'employee')->where('is_active', true)->get();
        $projects = \App\Models\Project::where('status', 'active')->get();
        return view('admin.tasks.create', compact('employees', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employee_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $validated['assigned_by'] = auth()->id();
        $task = Task::create($validated);
        $task->load('employee', 'project');
        
        // Trigger SMS
        app(\App\Services\SmsService::class)->triggerEvent('task_assigned', $task->employee->mobile, [
            'name' => $task->employee->name,
            'task_name' => $task->title,
            'project_name' => $task->project ? $task->project->name : 'N/A'
        ]);
        
        return redirect('/admin/tasks')->with('success', 'কাজ তৈরি করা হয়েছে');
    }
}
