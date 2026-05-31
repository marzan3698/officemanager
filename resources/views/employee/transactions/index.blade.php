@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <h1>লেনদেন ইতিহাস</h1>
</div>

<div class="content">
    <x-card class="mb-4">
        <form method="GET" action="/employee/transactions" class="d-flex align-center">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" style="margin-bottom: 0;">
        </form>
    </x-card>
    
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="font-size: 14px; color: var(--text-secondary);">মোট ব্যালেন্স</div>
        <div style="font-size: 28px; font-weight: 700; color: {{ $balance >= 0 ? 'var(--success)' : 'var(--danger)' }};">
            {{ $balance >= 0 ? '+' : '' }}{{ number_format($balance) }}৳
        </div>
    </div>

    @foreach($transactions as $transaction)
        <x-card>
            <div class="d-flex justify-between align-center">
                <div>
                    <div style="font-size: 14px; font-weight: 600;">
                        @if($transaction->type === 'payment') পেমেন্ট @elseif($transaction->type === 'deduction') কর্তন @else বোনাস @endif
                    </div>
                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $transaction->transaction_date->format('d M, Y') }}</div>
                    @if($transaction->note)
                        <div style="font-size: 12px; margin-top: 4px;">{{ $transaction->note }}</div>
                    @endif
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 700; color: {{ $transaction->type === 'payment' ? 'var(--success)' : ($transaction->type === 'deduction' ? 'var(--danger)' : 'var(--primary)') }};">
                        {{ $transaction->type === 'deduction' ? '-' : '+' }}{{ number_format($transaction->amount) }}৳
                    </div>
                </div>
            </div>
        </x-card>
    @endforeach
</div>
@endsection
