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
            $response = Http::get('https://api.greenweb.com.bd/api.php', [
                'token' => $setting->api_key,
                'to' => $mobile,
                'message' => $message,
            ]);

            $success = str_contains($response->body(), 'Ok');
            
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

    public function sendBulk(array $mobiles, string $message): array
    {
        $results = [];
        foreach ($mobiles as $mobile) {
            $results[$mobile] = $this->send($mobile, $message);
        }
        return $results;
    }

    public function salaryNotification(User $employee, SalaryLog $salary): void
    {
        // Simple translation for demo (in production use Carbon locale)
        $message = "প্রিয় {$employee->name}, আপনার {$salary->month} মাসের বেতন {$salary->net_salary} টাকা পরিশোধ করা হয়েছে। -অফিস ম্যানেজার";
        
        $this->send($employee->mobile, $message);
    }
}
