<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\SalaryLog;
use App\Models\Expense;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::where('role', 'employee')->where('is_active', true)->count();
        $thisMonth = now()->format('Y-m');
        $salaryDue = SalaryLog::where('month', $thisMonth)->where('status', 'pending')->sum('net_salary');
        $unpaidCount = SalaryLog::where('month', $thisMonth)->where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();
        $pendingTasksCount = Task::whereIn('status', ['pending', 'in_progress'])->count();

        $totalTransactionExpense = Transaction::where('type', 'payment')->sum('amount');
        $totalSalaryExpense = SalaryLog::where('status', 'paid')->sum('net_salary');
        $totalExpense = $totalTransactionExpense + $totalSalaryExpense;

        $recentTransactions = Transaction::with('employee')->latest()->limit(5)->get();
        $recentTasks = Task::with('employee')->latest()->limit(5)->get();

        $pendingExpenses = Expense::with('employee')->where('status', 'pending')->latest()->get();

        return view('admin.dashboard', compact(
            'totalEmployees', 'salaryDue', 'unpaidCount', 'completedTasks', 'pendingTasksCount',
            'recentTransactions', 'recentTasks', 'totalExpense', 'pendingExpenses'
        ));
    }
}
