<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalaryLog;
use App\Services\SmsService;

class AdminSalaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $employees = User::where('role', 'employee')->where('is_active', true)->get();
        
        foreach ($employees as $employee) {
            $log = SalaryLog::where('employee_id', $employee->id)
                ->where('month', $month)
                ->first();
                
            if (!$log) {
                $log = SalaryLog::create([
                    'employee_id' => $employee->id,
                    'month' => $month,
                    'base_salary' => $employee->salary,
                    'bonus' => 0,
                    'deduction' => 0,
                    'net_salary' => $employee->salary,
                    'status' => 'pending'
                ]);
            }
            $employee->salary_log = $log;
        }
        
        return view('admin.salary.index', compact('employees', 'month'));
    }

    public function pay(User $employee, Request $request, SmsService $smsService)
    {
        $month = $request->input('month');
        $log = SalaryLog::where('employee_id', $employee->id)->where('month', $month)->firstOrFail();
        
        $validated = $request->validate([
            'payment_ref' => 'nullable|string',
            'proof_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        if ($request->hasFile('proof_file')) {
            $validated['proof_file'] = $request->file('proof_file')->store('salaries/proofs', 'public');
        }
        
        $log->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_ref' => $validated['payment_ref'] ?? null,
            'proof_file' => $validated['proof_file'] ?? null,
        ]);
        
        $smsService->salaryNotification($employee, $log);
        
        return back()->with('success', 'বেতন পরিশোধ করা হয়েছে');
    }

    public function payAll(Request $request, SmsService $smsService)
    {
        $month = $request->input('month');
        $logs = SalaryLog::where('month', $month)->where('status', 'pending')->get();
        
        foreach ($logs as $log) {
            $log->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            $employee = User::find($log->employee_id);
            if ($employee) {
                $smsService->salaryNotification($employee, $log);
            }
        }
        
        return back()->with('success', 'সকলের বেতন পরিশোধ করা হয়েছে');
    }
}
