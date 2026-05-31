@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>আমার ইনভয়েস</h1>
        <a href="/employee/invoices/create" class="btn btn-primary" style="padding: 6px 12px; font-size: 13px;">+ নতুন ইনভয়েস</a>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($invoices as $invoice)
        <x-card>
            <div class="d-flex justify-between align-center mb-2">
                <div>
                    <div style="font-weight: 600; font-size: 16px;">ইনভয়েস #{{ $invoice->id }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">
                        {{ $invoice->client_name ?: 'অজ্ঞাত ক্লায়েন্ট' }} • {{ $invoice->created_at->format('d M, Y') }}
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
            
            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px dashed var(--border); font-size: 13px;">
                @foreach($invoice->items as $item)
                    <div class="d-flex justify-between mb-1" style="color: var(--text-secondary);">
                        <span>{{ $item['name'] }} ({{ $item['qty'] }}x)</span>
                        <span>{{ number_format($item['total']) }}৳</span>
                    </div>
                @endforeach
            </div>
        </x-card>
    @empty
        <div style="text-align: center; padding: 40px 20px; background: white; border-radius: 12px; border: 1px dashed #CBD5E1;">
            <div style="font-size: 40px; margin-bottom: 12px;">🧾</div>
            <div style="font-weight: 600; color: var(--text-secondary);">কোনো ইনভয়েস নেই</div>
        </div>
    @endforelse
</div>
@endsection
