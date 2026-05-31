<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class EmployeeTransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $employeeId = auth()->id();
        
        $transactions = Transaction::where('employee_id', $employeeId)
            ->whereYear('transaction_date', substr($month, 0, 4))
            ->whereMonth('transaction_date', substr($month, 5, 2))
            ->latest('transaction_date')
            ->get();
            
        $balance = $transactions->sum(function($t) {
            return $t->type === 'deduction' ? -$t->amount : $t->amount;
        });
        
        return view('employee.transactions.index', compact('transactions', 'month', 'balance'));
    }
}
