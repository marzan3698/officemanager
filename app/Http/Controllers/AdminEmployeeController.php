<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminEmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|string|size:11',
            'salary' => 'required|numeric',
            'profile_image' => 'nullable|image|max:2048',
        ]);
        
        $validated['role'] = 'employee';
        $validated['is_active'] = $request->has('is_active');
        
        // Auto-generate numeric login_id
        do {
            $loginId = (string) mt_rand(10000, 99999);
        } while (User::where('login_id', $loginId)->exists());
        
        $validated['login_id'] = $loginId;

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path;
        }

        User::create($validated);
        
        return redirect('/admin/employees')->with('success', 'কর্মী যোগ করা হয়েছে');
    }

    public function show(User $employee)
    {
        $employee->load(['transactions' => fn($q) => $q->latest(), 'tasks' => fn($q) => $q->latest(), 'salaryLogs' => fn($q) => $q->latest()]);
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|string|size:11',
            'role' => 'required|in:admin,employee',
            'salary' => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|max:2048',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path;
        }

        $employee->update($validated);
        
        return redirect('/admin/employees/'.$employee->id)->with('success', 'কর্মী আপডেট করা হয়েছে');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect('/admin/employees')->with('success', 'কর্মী মুছে ফেলা হয়েছে');
    }
}
