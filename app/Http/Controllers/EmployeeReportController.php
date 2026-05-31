<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\SalaryLog;
use Carbon\Carbon;

class EmployeeReportController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = auth()->id();
        
        $months = [];
        $earnings = [];
        $deductions = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStr = $date->format('Y-m');
            $months[] = $date->format('M Y');
            
            $salary = SalaryLog::where('employee_id', $employeeId)->where('month', $monthStr)->where('status', 'paid')->sum('net_salary');
            $bonus = Transaction::where('employee_id', $employeeId)->where('type', 'bonus')->whereMonth('transaction_date', $date->month)->whereYear('transaction_date', $date->year)->sum('amount');
            $earnings[] = $salary + $bonus;
            
            $deduction = Transaction::where('employee_id', $employeeId)->where('type', 'deduction')->whereMonth('transaction_date', $date->month)->whereYear('transaction_date', $date->year)->sum('amount');
            $deductions[] = $deduction;
        }
        
        return view('employee.report.index', compact('months', 'earnings', 'deductions'));
    }
}
