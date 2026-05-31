<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\SalaryLog;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $employee = auth()->user();
        
        $thisMonth = now()->format('Y-m');
        $salary = SalaryLog::where('employee_id', $employee->id)->where('month', $thisMonth)->first();
        
        $recentTransactions = Transaction::where('employee_id', $employee->id)->latest()->limit(3)->get();
        
        $activeTasksCount = Task::where('employee_id', $employee->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
            
        $pendingTasks = Task::where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->latest()
            ->get();
            
        return view('employee.dashboard', compact('employee', 'salary', 'recentTransactions', 'activeTasksCount', 'pendingTasks'));
    }
}
