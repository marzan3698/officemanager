<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class EmployeeTaskController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = Task::with('project')->where('employee_id', auth()->id())->latest('due_date');
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        $tasks = $query->get();
        return view('employee.tasks.index', compact('tasks', 'status'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        if ($task->employee_id != auth()->id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);
        
        $task->update($validated);
        
        return back()->with('success', 'কাজ স্ট্যাটাস আপডেট করা হয়েছে');
    }
}
