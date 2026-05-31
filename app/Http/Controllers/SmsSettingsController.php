<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsSetting;
use App\Models\SmsLog;
use App\Services\SmsService;

class SmsSettingsController extends Controller
{
    public function index()
    {
        $setting = SmsSetting::first() ?? new SmsSetting();
        $logs = SmsLog::latest()->limit(20)->get();
        return view('admin.settings.sms', compact('setting', 'logs'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'nullable|string',
            'sender_id' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $setting = SmsSetting::first();
        if ($setting) {
            $setting->update($validated);
        } else {
            SmsSetting::create($validated);
        }
        
        return back()->with('success', 'SMS সেটিংস আপডেট করা হয়েছে');
    }

    public function test(Request $request, SmsService $smsService)
    {
        $request->validate(['mobile' => 'required|string']);
        
        $success = $smsService->send($request->mobile, 'This is a test message from Office Manager');
        
        if ($success) {
            return back()->with('success', 'টেস্ট SMS সফলভাবে পাঠানো হয়েছে');
        }
        return back()->with('error', 'SMS পাঠানো ব্যর্থ হয়েছে');
    }

    public function resetData(Request $request)
    {
        $request->validate(['confirm_text' => 'required|in:RESET']);

        // Delete all non-admin data
        \App\Models\Transaction::truncate();
        \App\Models\Task::truncate();
        \App\Models\SalaryLog::truncate();
        \App\Models\SmsLog::truncate();
        \App\Models\Expense::truncate();
        \App\Models\CompanyIncome::truncate();
        
        // Delete all employees (non-admin users)
        \App\Models\User::where('role', 'employee')->delete();
        
        return back()->with('success', 'সকল ডাটা সফলভাবে রিসেট করা হয়েছে। শুধুমাত্র অ্যাডমিন একাউন্ট রয়ে গেছে।');
    }
}
