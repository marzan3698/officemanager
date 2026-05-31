<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'event' => 'new_employee',
                'is_active' => true,
                'message' => "স্বাগতম {name}! আপনার একাউন্ট সফলভাবে তৈরি হয়েছে। লগইন করতে মোবাইল নম্বর এবং পাসওয়ার্ড {password} ব্যবহার করুন।",
            ],
            [
                'event' => 'task_assigned',
                'is_active' => true,
                'message' => "হ্যালো {name}, আপনাকে একটি নতুন কাজ ({task_name}) দেওয়া হয়েছে। প্রজেক্ট: {project_name}। বিস্তারিত ড্যাশবোর্ডে দেখুন।",
            ],
            [
                'event' => 'payment_made',
                'is_active' => true,
                'message' => "প্রিয় {name}, আপনার একাউন্টে {amount} টাকা পরিশোধ করা হয়েছে। রেফারেন্স: {ref}। ধন্যবাদ।",
            ],
            [
                'event' => 'task_reminder',
                'is_active' => true,
                'message' => "রিমাইন্ডার: {name}, আপনার একটি কাজ ({task_name}) পেন্ডিং আছে। অনুগ্রহ করে চেক করুন।",
            ]
        ];

        foreach ($templates as $template) {
            \App\Models\SmsTemplate::updateOrCreate(
                ['event' => $template['event']],
                $template
            );
        }
    }
}
