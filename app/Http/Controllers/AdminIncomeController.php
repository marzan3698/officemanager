<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyIncome;
use App\Models\User;

class AdminIncomeController extends Controller
{
    public function index()
    {
        $incomes = CompanyIncome::with(['employee', 'project'])->latest('income_date')->get();
        $employees = User::where('role', 'employee')->get();
        $projects = \App\Models\Project::where('status', 'active')->get();
        
        return view('admin.incomes.index', compact('incomes', 'employees', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
            'employee_id' => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id'
        ]);
        
        $validated['created_by'] = auth()->id();
        CompanyIncome::create($validated);
        
        return back()->with('success', 'ইনকাম সফলভাবে যুক্ত করা হয়েছে');
    }
}
