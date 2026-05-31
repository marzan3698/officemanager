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

            $responseData = $response->json();
            $success = false;
            
            if ($response->successful() && is_array($responseData) && count($responseData) > 0) {
                $status = strtoupper($responseData[0]['status'] ?? '');
                $success = ($status === 'SENT' || $status === 'SUCCESS');
            }
            
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
            $responseData = $response->json();
            $globalSuccess = false;
            
            if ($response->successful() && is_array($responseData) && count($responseData) > 0) {
                // Determine success based on the first item or overall
                $status = strtoupper($responseData[0]['status'] ?? '');
                $globalSuccess = ($status === 'SENT' || $status === 'SUCCESS');
            }
            
            foreach ($mobiles as $index => $mobile) {
                // Try to get specific status if available, else fallback to global
                $specificStatus = $globalSuccess;
                if (isset($responseData[$index])) {
                    $status = strtoupper($responseData[$index]['status'] ?? '');
                    $specificStatus = ($status === 'SENT' || $status === 'SUCCESS');
                }
                
                SmsLog::create([
                    'recipient_mobile' => $mobile,
                    'message' => $message,
                    'status' => $specificStatus ? 'sent' : 'failed',
                    'response' => $response->body(),
                    'sent_at' => now(),
                ]);
                $results[$mobile] = $specificStatus;
            }
        } catch (\Exception $e) {
            Log::error('SMS Send Bulk Error: ' . $e->getMessage());
        }

        return $results;
    }
}
