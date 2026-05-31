<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\SalaryLog;
use App\Models\SmsSetting;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $mobile, string $message): bool
    {
        $setting = SmsSetting::first();
        if (!$setting || !$setting->is_active || empty($setting->api_key)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://api.bdbulksms.net/api.php?json', [
                'token' => $setting->api_key,
                'smsdata' => [
                    [
                        'to' => $mobile,
                        'message' => $message
                    ]
                ]
            ]);

            // Assuming a successful request returns JSON or contains "Ok"
            $success = $response->successful() && !str_contains(strtolower($response->body()), 'error');
            
            SmsLog::create([
                'recipient_mobile' => $mobile,
                'message' => $message,
                'status' => $success ? 'sent' : 'failed',
                'response' => $response->body(),
                'sent_at' => now(),
            ]);

            return $success;
        } catch (\Exception $e) {
            Log::error('SMS Send Error: ' . $e->getMessage());
            return false;
        }
    }

    public function triggerEvent(string $eventName, string $mobile, array $data = []): bool
    {
        $template = \App\Models\SmsTemplate::where('event', $eventName)->where('is_active', true)->first();
        if (!$template) {
            return false;
        }

        $message = $template->message;
        foreach ($data as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $this->send($mobile, $message);
    }

    public function sendBulk(array $mobiles, string $message): array
    {
        $results = [];
        // Optional optimization: Send all numbers in one single request using 'smsdata' array
        $setting = SmsSetting::first();
        if (!$setting || !$setting->is_active || empty($setting->api_key) || empty($mobiles)) {
            return $results;
        }
        
        $smsData = [];
        foreach ($mobiles as $mobile) {
            $smsData[] = [
                'to' => $mobile,
                'message' => $message
            ];
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://api.bdbulksms.net/api.php?json', [
                'token' => $setting->api_key,
                'smsdata' => $smsData
            ]);
            $success = $response->successful() && !str_contains(strtolower($response->body()), 'error');
            
            foreach ($mobiles as $mobile) {
                SmsLog::create([
                    'recipient_mobile' => $mobile,
                    'message' => $message,
                    'status' => $success ? 'sent' : 'failed',
                    'response' => $response->body(),
                    'sent_at' => now(),
                ]);
                $results[$mobile] = $success;
            }
        } catch (\Exception $e) {
            Log::error('SMS Send Bulk Error: ' . $e->getMessage());
        }

        return $results;
    }
}
