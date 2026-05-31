<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TaskMonitorCommand extends Command
{
    protected $signature = 'task:monitor';

    protected $description = 'Monitor tasks for deadlines, process penalties, and send SMS reminders';

    public function handle(\App\Services\SmsService $smsService)
    {
        $now = now();
        
        // 1. Process Penalties
        $expiredTasks = \App\Models\Task::where('due_date', '<=', $now)
            ->where('status', '!=', 'completed')
            ->where('is_penalized', false)
            ->where('penalty_amount', '>', 0)
            ->with('employee')
            ->get();
            
        foreach ($expiredTasks as $task) {
            // Deduct penalty
            \App\Models\Transaction::create([
                'employee_id' => $task->employee_id,
                'type' => 'deduction',
                'amount' => $task->penalty_amount,
                'transaction_date' => $now->toDateString(),
                'note' => "Penalty for missing deadline: {$task->title}",
                'created_by' => 1 // System/Admin
            ]);
            
            $task->update(['is_penalized' => true]);
            
            // Notify via SMS
            $smsService->triggerEvent('task_reminder', $task->employee->mobile, [
                'name' => $task->employee->name,
                'task_name' => "{$task->title} (পেনাল্টি কাটা হয়েছে: {$task->penalty_amount}৳)"
            ]);
            $this->info("Penalty applied for task: {$task->id}");
        }

        // 2. Process Reminders (Before deadline)
        $pendingTasks = \App\Models\Task::where('due_date', '>', $now)
            ->where('status', '!=', 'completed')
            ->where('reminder_count', '<', 3)
            ->with('employee')
            ->get();
            
        foreach ($pendingTasks as $task) {
            $hoursLeft = $now->diffInHours($task->due_date, false);
            
            $shouldSend = false;
            
            if ($task->reminder_count == 0 && $hoursLeft <= 24 && $hoursLeft > 6) {
                $shouldSend = true;
            } elseif ($task->reminder_count == 1 && $hoursLeft <= 6 && $hoursLeft > 1) {
                $shouldSend = true;
            } elseif ($task->reminder_count == 2 && $hoursLeft <= 1 && $hoursLeft > 0) {
                $shouldSend = true;
            }
            
            if ($shouldSend) {
                $smsService->triggerEvent('task_reminder', $task->employee->mobile, [
                    'name' => $task->employee->name,
                    'task_name' => $task->title
                ]);
                
                $task->increment('reminder_count');
                $this->info("Reminder {$task->reminder_count} sent for task: {$task->id}");
            }
        }
    }
}
