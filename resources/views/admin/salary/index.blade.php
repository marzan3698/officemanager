@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>বেতন ব্যবস্থাপনা</h1>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <x-card class="mb-4">
        <form method="GET" action="/admin/salary" class="d-flex align-center">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" style="margin-bottom: 0;">
        </form>
    </x-card>

    <div class="d-flex justify-between align-center mb-4">
        <h2 style="font-size: 16px;">{{ date('F Y', strtotime($month . '-01')) }}</h2>
        <form method="POST" action="/admin/salary/pay-all">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">
            <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 14px; width: auto;" onclick="return confirm('সবার বেতন পরিশোধ করতে চান?')">সব বেতন দিন</button>
        </form>
    </div>

    @foreach($employees as $employee)
        <x-card>
            <div class="d-flex justify-between align-center">
                <div>
                    <div style="font-weight: 600;">{{ $employee->name }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">বেসিক: {{ number_format($employee->salary_log->base_salary) }}৳</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 700; color: var(--primary);">{{ number_format($employee->salary_log->net_salary) }}৳</div>
                    
                    @if($employee->salary_log->status === 'paid')
                        <x-badge type="success">পরিশোধিত</x-badge>
                        @if($employee->salary_log->payment_ref)
                            <div style="font-size: 11px; margin-top: 4px; color: var(--text-secondary);">Txn ID: {{ $employee->salary_log->payment_ref }}</div>
                        @endif
                        @if($employee->salary_log->proof_file)
                            <div style="font-size: 11px; margin-top: 2px;">
                                <a href="{{ asset('storage/' . $employee->salary_log->proof_file) }}" target="_blank" style="color: var(--primary);">📄 প্রুফ দেখুন</a>
                            </div>
                        @endif
                    @else
                        <form method="POST" action="/admin/salary/pay/{{ $employee->id }}" enctype="multipart/form-data" style="margin-top: 12px; text-align: left;">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="text" name="payment_ref" placeholder="Txn ID (ঐচ্ছিক)" style="padding: 6px; font-size: 11px; margin-bottom: 6px; width: 100%; box-sizing: border-box;">
                            <input type="file" name="proof_file" accept="image/*,.pdf" style="font-size: 10px; margin-bottom: 8px; width: 100%; box-sizing: border-box;">
                            <button type="submit" class="btn btn-primary" style="padding: 6px 8px; font-size: 12px; width: 100%; border-radius: 4px;">বেতন দিন</button>
                        </form>
                    @endif
                </div>
            </div>
            
            @if($employee->salary_log->bonus > 0 || $employee->salary_log->deduction > 0)
            <div style="font-size: 11px; margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--border);">
                @if($employee->salary_log->bonus > 0)
                    <span style="color: var(--success); margin-right: 8px;">+ বোনাস: {{ number_format($employee->salary_log->bonus) }}৳</span>
                @endif
                @if($employee->salary_log->deduction > 0)
                    <span style="color: var(--danger);">- কর্তন: {{ number_format($employee->salary_log->deduction) }}৳</span>
                @endif
            </div>
            @endif
        </x-card>
    @endforeach
</div>
@endsection
