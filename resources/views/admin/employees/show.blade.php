@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/employees" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>{{ $employee->name }}</h1>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <x-card>
        <div class="d-flex justify-between align-center mb-2">
            <h2 style="font-size: 18px;">প্রোফাইল</h2>
            @if($employee->is_active)
                <x-badge type="success">সক্রিয়</x-badge>
            @else
                <x-badge type="danger">নিষ্ক্রিয়</x-badge>
            @endif
        </div>
        <div style="font-size: 14px; color: var(--text-secondary); line-height: 1.6;">
            <div><strong>মোবাইল:</strong> {{ $employee->mobile }}</div>
            <div><strong>লগইন আইডি:</strong> {{ $employee->login_id }}</div>
            <div><strong>মাসিক বেতন:</strong> {{ number_format($employee->salary) }}৳</div>
        </div>
    </x-card>

    <h2 class="mt-4 mb-2" style="font-size: 18px;">বেতন ইতিহাস</h2>
    @foreach($employee->salaryLogs as $log)
        <x-card>
            <div class="d-flex justify-between">
                <div>
                    <div style="font-weight: 600;">{{ $log->month }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">পেইড: {{ $log->paid_at ? $log->paid_at->format('d M, Y') : '-' }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 700;">{{ number_format($log->net_salary) }}৳</div>
                    @if($log->status === 'paid')
                        <x-badge type="success">পরিশোধিত</x-badge>
                    @else
                        <x-badge type="warning">বকেয়া</x-badge>
                    @endif
                </div>
            </div>
        </x-card>
    @endforeach

    <h2 class="mt-4 mb-2" style="font-size: 18px;">সাম্প্রতিক লেনদেন</h2>
    @foreach($employee->transactions->take(5) as $transaction)
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

    <h2 class="mt-4 mb-2" style="font-size: 18px;">টাস্ক</h2>
    @foreach($employee->tasks->take(5) as $task)
        <x-card>
            <div class="d-flex justify-between align-center">
                <div style="font-weight: 600;">{{ $task->title }}</div>
                <div>
                    @if($task->status === 'pending')
                        <x-badge type="warning">অপেক্ষমান</x-badge>
                    @elseif($task->status === 'in_progress')
                        <x-badge type="primary">চলমান</x-badge>
                    @else
                        <x-badge type="success">সম্পন্ন</x-badge>
                    @endif
                </div>
            </div>
        </x-card>
    @endforeach
</div>
@endsection
