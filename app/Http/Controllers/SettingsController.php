<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function general()
    {
        return view('admin.settings.general');
    }

    public function resetData(\Illuminate\Http\Request $request)
    {
        $request->validate(['confirm_text' => 'required|in:RESET']);

        \App\Models\Transaction::truncate();
        \App\Models\Task::truncate();
        \App\Models\SalaryLog::truncate();
        \App\Models\SmsLog::truncate();
        \App\Models\Expense::truncate();
        \App\Models\CompanyIncome::truncate();
        \App\Models\User::where('role', 'employee')->delete();
        
        return back()->with('success', 'সকল ডাটা সফলভাবে রিসেট করা হয়েছে। শুধুমাত্র অ্যাডমিন একাউন্ট রয়ে গেছে।');
    }
}
