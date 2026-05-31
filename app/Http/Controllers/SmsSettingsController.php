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
        $apiKey = $request->api_key;
        
        if ($validated['is_active'] && !empty($apiKey)) {
            $response = \Illuminate\Support\Facades\Http::get('https://api.bdbulksms.net/api.php', [
                'token' => $apiKey,
                'balance' => ''
            ]);
            
            if (str_contains(strtolower($response->body()), 'error') || str_contains(strtolower($response->body()), 'invalid token')) {
                return back()->with('error', 'অকার্যকর (Invalid) API Token! দয়া করে সঠিক টোকেন দিন।');
            }
        } elseif ($validated['is_active']) {
            return back()->with('error', 'API Token ছাড়া SMS সক্রিয় করা যাবে না।');
        }
        
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
        
        $setting = \App\Models\SmsSetting::first();
        if (!$setting || !$setting->is_active || empty($setting->api_key)) {
            return back()->with('error', 'SMS সেটিংস নিষ্ক্রিয় করা আছে অথবা API Key দেওয়া নেই।');
        }
        
        $success = $smsService->send($request->mobile, 'This is a test message from Office Manager');
        
        if ($success) {
            return back()->with('success', 'টেস্ট SMS সফলভাবে পাঠানো হয়েছে');
        }
        
        // Find the latest log to show detailed error
        $log = \App\Models\SmsLog::where('recipient_mobile', $request->mobile)->latest()->first();
        $errorMsg = 'SMS পাঠানো ব্যর্থ হয়েছে।';
        if ($log && $log->response) {
            $responseData = json_decode($log->response, true);
            if (is_array($responseData) && isset($responseData[0]['statusmsg'])) {
                $errorMsg .= ' কারণ: ' . $responseData[0]['statusmsg'];
            } else {
                $errorMsg .= ' রেসপন্স: ' . strip_tags($log->response);
            }
        }
        
        return back()->with('error', $errorMsg);
    }
}
