<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryLog;

class EmployeeProfileController extends Controller
{
    public function index()
    {
        $employee = auth()->user();
        $salaryHistory = SalaryLog::where('employee_id', $employee->id)->latest('month')->limit(6)->get();
        return view('employee.profile.index', compact('employee', 'salaryHistory'));
    }
}
