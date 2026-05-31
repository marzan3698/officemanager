@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>লেনদেনসমূহ (মোট: {{ $totalTransactions ?? 0 }})</h1>
        <a href="/admin/transactions/create" class="btn btn-primary" style="width: auto; padding: 8px 16px; border-radius: 20px;">+ নতুন</a>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach($transactions as $transaction)
        <a href="/admin/transactions/{{ $transaction->id }}" style="text-decoration: none; color: inherit; display: block;">
        <x-card>
            <div class="d-flex justify-between align-center">
                <div>
                    <div style="font-weight: 600;">{{ optional($transaction->employee)->name }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $transaction->transaction_date->format('d M, Y') }}</div>
                    @if($transaction->note)
                        <div style="font-size: 12px; margin-top: 4px;">{{ $transaction->note }}</div>
                    @endif
                    @if($transaction->invoice_file)
                        <div style="margin-top: 8px;">
                            <a href="{{ asset('storage/' . $transaction->invoice_file) }}" target="_blank" style="font-size: 12px; color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                📄 ইনভয়েস দেখুন
                            </a>
                        </div>
                    @endif
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
        </a>
    @endforeach
</div>
@endsection
