@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <h1>প্রোফাইল</h1>
</div>

<div class="content">
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="font-size: 64px; margin-bottom: 8px;">👤</div>
        <h2 style="font-size: 24px; font-weight: 700;">{{ $employee->name }}</h2>
        <div style="color: var(--text-secondary);">{{ $employee->mobile }}</div>
    </div>

    <x-card class="mb-4">
        <div class="d-flex justify-between mb-2">
            <span style="color: var(--text-secondary);">লগইন আইডি</span>
            <span style="font-weight: 600;">{{ $employee->login_id }}</span>
        </div>
        <div class="d-flex justify-between mb-2">
            <span style="color: var(--text-secondary);">মাসিক বেতন</span>
            <span style="font-weight: 600; color: var(--primary);">{{ number_format($employee->salary) }}৳</span>
        </div>
        <div class="d-flex justify-between">
            <span style="color: var(--text-secondary);">যোগদানের তারিখ</span>
            <span style="font-weight: 600;">{{ $employee->created_at->format('d M, Y') }}</span>
        </div>
    </x-card>

    <h2 class="mb-2" style="font-size: 18px;">বেতন ইতিহাস (৬ মাস)</h2>
    @foreach($salaryHistory as $log)
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
</div>
@endsection
