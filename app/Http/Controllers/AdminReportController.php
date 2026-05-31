<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyIncome;
use App\Models\Transaction;
use App\Models\SalaryLog;
use App\Models\Expense;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Calculate Total Income
        $totalIncome = CompanyIncome::whereBetween('income_date', [$startDate, $endDate])->sum('amount');

        // Calculate Total Expenses
        $transactionExpense = Transaction::where('type', 'payment')->whereBetween('transaction_date', [$startDate, $endDate])->sum('amount');
        $salaryExpense = SalaryLog::where('status', 'paid')->where('month', $month)->sum('net_salary');
        $invoiceExpense = Expense::where('status', 'approved')->whereBetween('updated_at', [$startDate, $endDate])->sum('amount');
        
        $totalExpense = $transactionExpense + $salaryExpense + $invoiceExpense;
        
        return view('admin.report.index', compact('month', 'totalIncome', 'totalExpense', 'transactionExpense', 'salaryExpense', 'invoiceExpense'));
    }
}
