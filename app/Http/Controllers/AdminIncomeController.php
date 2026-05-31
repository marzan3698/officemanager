<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyIncome;
use App\Models\User;

class AdminIncomeController extends Controller
{
    public function index()
    {
        $incomes = CompanyIncome::with('employee')->latest('income_date')->get();
        $employees = User::where('role', 'employee')->get();
        
        return view('admin.incomes.index', compact('incomes', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
            'employee_id' => 'nullable|exists:users,id'
        ]);
        
        $validated['created_by'] = auth()->id();
        CompanyIncome::create($validated);
        
        return back()->with('success', 'ইনকাম সফলভাবে যুক্ত করা হয়েছে');
    }
}
