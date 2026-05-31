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
        $templates = \App\Models\SmsTemplate::all();
        return view('admin.settings.sms', compact('setting', 'logs', 'templates'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $setting = SmsSetting::first();
        if ($setting) {
            $setting->update($validated);
        } else {
            SmsSetting::create($validated);
        }
        
        // Handle SMS Events Templates update
        if ($request->has('templates')) {
            foreach ($request->templates as $event => $data) {
                \App\Models\SmsTemplate::where('event', $event)->update([
                    'is_active' => isset($data['is_active']) && $data['is_active'] == '1',
                    'message' => $data['message'] ?? '',
                ]);
            }
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
}
