@extends('layouts.app')

@section('content')
<div class="dashboard-header">
    <div class="d-flex justify-between align-center mb-4">
        <div class="d-flex align-center gap-3">
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-right: 12px;">
                👤
            </div>
            <div>
                <div style="font-size: 14px; opacity: 0.9;">স্বাগতম,</div>
                <div style="font-size: 18px; font-weight: 700;">{{ $employee->name }}</div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer;">🚪</button>
            </form>
        </div>
    </div>
    
    <div style="background: rgba(255,255,255,0.15); padding: 16px; border-radius: 16px; display: flex; justify-content: space-between; align-items: center; backdrop-filter: blur(10px);">
        <div>
            <div style="font-size: 13px; opacity: 0.9; margin-bottom: 4px;">এই মাসের বেতন ({{ now()->format('F Y') }})</div>
            <div style="font-size: 24px; font-weight: 700;">
                {{ $salary ? number_format($salary->net_salary) : number_format($employee->salary) }}৳
            </div>
        </div>
        <div>
            @if(isset($urgentTask))
                <div style="position: relative; width: 50px; height: 50px; text-align: center; border-radius: 50%; background: conic-gradient(var(--primary) calc(var(--progress, 0) * 1%), rgba(255,255,255,0.2) 0); display: flex; align-items: center; justify-content: center;">
                    <div style="width: 42px; height: 42px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                        <span id="task_hours_left" style="font-size: 11px; font-weight: 700; color: var(--primary);">--</span>
                        <span style="font-size: 8px; color: var(--text-secondary);">hr left</span>
                    </div>
                </div>
                <div style="font-size: 10px; margin-top: 4px; text-align: center; color: var(--text-secondary); max-width: 60px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $urgentTask->title }}</div>
                
                <script>
                    function updateTaskProgress() {
                        const dueDate = new Date('{{ $urgentTask->due_date }}').getTime();
                        const createdDate = new Date('{{ $urgentTask->created_at }}').getTime();
                        const totalTime = dueDate - createdDate;
                        
                        setInterval(() => {
                            const now = new Date().getTime();
                            const timeLeft = dueDate - now;
                            
                            if (timeLeft > 0) {
                                const hoursLeft = Math.floor(timeLeft / (1000 * 60 * 60));
                                document.getElementById('task_hours_left').innerText = hoursLeft;
                                
                                const progress = ((totalTime - timeLeft) / totalTime) * 100;
                                document.querySelector('[style*="conic-gradient"]').style.setProperty('--progress', progress);
                            } else {
                                document.getElementById('task_hours_left').innerText = "0";
                                document.querySelector('[style*="conic-gradient"]').style.setProperty('--progress', 100);
                                document.querySelector('[style*="conic-gradient"]').style.background = 'conic-gradient(var(--danger) 100%, rgba(255,255,255,0.2) 0)';
                            }
                        }, 1000);
                    }
                    updateTaskProgress();
                </script>
            @else
                @if($salary && $salary->status === 'paid')
                    <div style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 12px; font-size: 12px;">পরিশোধিত</div>
                @else
                    <div style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 12px; font-size: 12px;">বকেয়া</div>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="dashboard-overlap-card">
    <div class="service-grid">
        <a href="/employee/transactions" class="service-item">
            <div class="service-icon">💰</div>
            <div class="service-label">লেনদেন</div>
        </a>
        <a href="/employee/tasks" class="service-item">
            <div class="service-icon">✅</div>
            <div class="service-label">আমার কাজ</div>
        </a>
        <a href="/employee/invoices" class="service-item">
            <div class="service-icon">🧾</div>
            <div class="service-label">ইনভয়েস</div>
        </a>
        <a href="/employee/profile" class="service-item">
            <div class="service-icon">👤</div>
            <div class="service-label">প্রোফাইল</div>
        </a>
    </div>

    <div class="section-title">কুইক ফিচারসমূহ</div>
    <div class="quick-features-scroll mb-4">
        <a href="/employee/invoices/create" class="quick-feature-card" style="text-decoration: none;">
            <div style="font-size: 24px;">🧾</div>
            <div style="font-size: 14px; font-weight: 500; color: var(--text-primary);">নতুন ইনভয়েস</div>
        </a>
        <a href="/employee/report" class="quick-feature-card" style="text-decoration: none;">
            <div style="font-size: 24px;">📊</div>
            <div style="font-size: 14px; font-weight: 500; color: var(--text-primary);">রিপোর্ট</div>
        </a>
        <div class="quick-feature-card">
            <div style="font-size: 20px;">📌</div>
            <div>
                <div style="font-size: 12px; color: var(--text-secondary);">অপেক্ষমান কাজ</div>
                <div style="font-size: 16px; font-weight: 600;">{{ $activeTasksCount }} টি</div>
            </div>
        </div>
    </div>

    @if($projects && $projects->count() > 0)
        <div class="section-title">আমার প্রজেক্টসমূহ</div>
        <div class="quick-features-scroll mb-4" style="gap: 12px;">
            @foreach($projects as $project)
                <div class="quick-feature-card" style="min-width: 150px; padding: 12px; display: block;">
                    <div style="font-size: 20px; margin-bottom: 8px;">📁</div>
                    <div style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">{{ $project->name }}</div>
                    @if($project->description)
                        <div style="font-size: 11px; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($project->description, 30) }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="d-flex justify-between align-center mb-2">
        <div class="section-title" style="margin-bottom: 0;">সাম্প্রতিক কাজ</div>
        <a href="/employee/tasks" style="font-size: 13px;">সব দেখুন ➔</a>
    </div>
    
    @foreach($pendingTasks->take(3) as $task)
        <x-card>
            <div class="d-flex justify-between align-center">
                <div style="font-weight: 600;">{{ $task->title }}</div>
                <x-badge type="warning">অপেক্ষমান</x-badge>
            </div>
            <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                শেষ তারিখ: {{ $task->due_date ? $task->due_date->format('d M, Y') : '-' }}
            </div>
        </x-card>
    @endforeach

    <div class="d-flex justify-between align-center mt-4 mb-2">
        <div class="section-title" style="margin-bottom: 0;">সাম্প্রতিক লেনদেন</div>
        <a href="/employee/transactions" style="font-size: 13px;">সব দেখুন ➔</a>
    </div>

    @foreach($recentTransactions as $transaction)
        <x-card>
            <div class="d-flex justify-between align-center">
                <div>
                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $transaction->transaction_date->format('d M, Y') }}</div>
                    <div style="font-size: 12px;">{{ $transaction->note }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 700; color: {{ $transaction->type === 'payment' ? 'var(--success)' : ($transaction->type === 'deduction' ? 'var(--danger)' : 'var(--primary)') }};">
                        {{ $transaction->type === 'deduction' ? '-' : '+' }}{{ number_format($transaction->amount) }}৳
                    </div>
                    @if($transaction->type === 'payment')
                        <x-badge type="success">পেমেন্ট</x-badge>
                    @elseif($transaction->type === 'deduction')
                        <x-badge type="danger">কর্তন</x-badge>
                    @else
                        <x-badge type="primary">বোনাস</x-badge>
                    @endif
                </div>
            </div>
        </x-card>
    @endforeach
</div>
@endsection
