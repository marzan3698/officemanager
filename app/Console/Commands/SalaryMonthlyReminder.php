<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\SmsService;

class SalaryMonthlyReminder extends Command
{
    protected $signature = 'salary:monthly-reminder';
    protected $description = 'Send monthly salary reminder to admin';

    public function handle(SmsService $smsService)
    {
        $admin = User::where('role', 'admin')->where('is_active', true)->first();
        if ($admin) {
            $month = now()->format('F Y');
            $message = "অফিস ম্যানেজার রিমাইন্ডার: আজ ১ তারিখ। কর্মীদের {$month} মাসের বেতন পরিশোধ করুন।";
            $smsService->send($admin->mobile, $message);
            $this->info('Reminder sent to admin.');
        } else {
            $this->warn('Admin not found.');
        }
    }
}
