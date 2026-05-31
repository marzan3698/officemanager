@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center justify-between">
        <div class="d-flex align-center">
            <a href="/admin/dashboard" style="margin-right: 16px; font-size: 20px;">⬅</a>
            <h1>ইনভয়েস সমূহ</h1>
        </div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($invoices as $invoice)
        <a href="/admin/invoices/{{ $invoice->id }}" style="text-decoration: none; color: inherit; display: block;">
            <x-card class="mb-3">
                <div class="d-flex justify-between align-center mb-2">
                    <div>
                        <div style="font-weight: 600; font-size: 16px;">ইনভয়েস #{{ $invoice->id }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">
                            {{ $invoice->employee ? $invoice->employee->name : 'অজ্ঞাত কর্মী' }} • {{ $invoice->created_at->format('d M, Y') }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; font-size: 16px; color: var(--primary);">
                            {{ number_format($invoice->total_amount) }}৳
                        </div>
                        @if($invoice->status === 'paid')
                            <x-badge type="success">পেইড</x-badge>
                        @else
                            <x-badge type="warning">পেন্ডিং</x-badge>
                        @endif
                    </div>
                </div>
                
                <div style="font-size: 13px; color: var(--text-secondary); margin-top: 8px;">
                    ক্লায়েন্ট: <span style="font-weight: 600;">{{ $invoice->client_name ?: 'দেওয়া হয়নি' }}</span>
                </div>
            </x-card>
        </a>
    @empty
        <div style="text-align: center; padding: 40px 20px; background: white; border-radius: 12px; border: 1px dashed #CBD5E1;">
            <div style="font-size: 40px; margin-bottom: 12px;">🧾</div>
            <div style="font-weight: 600; color: var(--text-secondary);">কোনো ইনভয়েস নেই</div>
        </div>
    @endforelse
</div>
@endsection
