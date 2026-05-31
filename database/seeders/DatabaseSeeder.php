<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\SalaryLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'প্রশাসক',
            'mobile' => '01700000000',
            'role' => 'admin',
            'login_id' => 'ADM001',
            'password' => Hash::make('password'),
            'salary' => 50000,
            'is_active' => true,
        ]);

        // Create Employees
        $emp1 = User::create([
            'name' => 'রাহেলা বেগম',
            'mobile' => '01711111111',
            'role' => 'employee',
            'login_id' => 'EMP001',
            'password' => Hash::make('password'),
            'salary' => 25000,
            'is_active' => true,
        ]);

        $emp2 = User::create([
            'name' => 'করিম সাহেব',
            'mobile' => '01722222222',
            'role' => 'employee',
            'login_id' => 'EMP002',
            'password' => Hash::make('password'),
            'salary' => 30000,
            'is_active' => true,
        ]);

        $emp3 = User::create([
            'name' => 'সুমাইয়া খানম',
            'mobile' => '01733333333',
            'role' => 'employee',
            'login_id' => 'EMP003',
            'password' => Hash::make('password'),
            'salary' => 22000,
            'is_active' => true,
        ]);

        // Create Tasks
        Task::create([
            'employee_id' => $emp1->id,
            'title' => 'মাসিক রিপোর্ট তৈরি',
            'description' => 'এই মাসের সব হিসাব নিকাশ করে রিপোর্ট তৈরি করতে হবে।',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(2),
            'assigned_by' => $admin->id,
        ]);

        Task::create([
            'employee_id' => $emp2->id,
            'title' => 'ক্লায়েন্ট মিটিং',
            'description' => 'নতুন প্রজেক্ট নিয়ে আলোচনা।',
            'status' => 'in_progress',
            'due_date' => Carbon::now()->addDays(1),
            'assigned_by' => $admin->id,
        ]);

        // Create Transactions
        Transaction::create([
            'employee_id' => $emp1->id,
            'type' => 'bonus',
            'amount' => 2000,
            'note' => 'ভালো কাজের জন্য বোনাস',
            'created_by' => $admin->id,
            'transaction_date' => Carbon::now()->subDays(5),
        ]);

        Transaction::create([
            'employee_id' => $emp2->id,
            'type' => 'deduction',
            'amount' => 500,
            'note' => 'অফিসে দেরি করে আসার জরিমানা',
            'created_by' => $admin->id,
            'transaction_date' => Carbon::now()->subDays(2),
        ]);
        
        // Create Salary Logs
        SalaryLog::create([
            'employee_id' => $emp1->id,
            'month' => Carbon::now()->subMonth()->format('Y-m'),
            'base_salary' => 25000,
            'bonus' => 2000,
            'deduction' => 0,
            'net_salary' => 27000,
            'paid_at' => Carbon::now()->subDays(10),
            'status' => 'paid',
        ]);
    }
}
