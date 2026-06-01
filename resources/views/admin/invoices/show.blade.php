@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/invoices" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>ইনভয়েস #{{ $invoice->id }}</h1>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <x-card>
        <div class="d-flex justify-between align-center mb-3">
            <div>
                <div style="font-weight: 600; font-size: 18px;">বিস্তারিত তথ্য</div>
                <div style="font-size: 13px; color: var(--text-secondary);">তৈরি করেছেন: <span style="font-weight: 600;">{{ $invoice->employee ? $invoice->employee->name : 'অজ্ঞাত কর্মী' }}</span></div>
            </div>
            <div>
                @if($invoice->status === 'paid')
                    <x-badge type="success">পেইড</x-badge>
                @else
                    <x-badge type="warning">পেন্ডিং</x-badge>
                @endif
            </div>
        </div>

        <div style="background: #F8FAFC; border-radius: 12px; padding: 12px; margin-bottom: 20px; font-size: 14px;">
            <div style="margin-bottom: 8px;"><strong>ক্লায়েন্ট:</strong> {{ $invoice->client_name ?: 'দেওয়া হয়নি' }}</div>
            <div style="margin-bottom: 8px;"><strong>মোবাইল:</strong> {{ $invoice->client_phone ?: 'দেওয়া হয়নি' }}</div>
            <div><strong>তারিখ:</strong> {{ $invoice->created_at->format('d M, Y h:i A') }}</div>
        </div>

        <div style="font-weight: 600; font-size: 16px; margin-bottom: 12px;">আইটেমসমূহ</div>
        <div style="border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; margin-bottom: 20px;">
            @foreach($invoice->items as $index => $item)
                <div class="d-flex justify-between" style="padding: 12px 16px; border-bottom: {{ $loop->last ? 'none' : '1px solid #E2E8F0' }}; background: {{ $index % 2 == 0 ? 'white' : '#F8FAFC' }};">
                    <div>
                        <div style="font-weight: 600; font-size: 14px;">{{ $item['name'] }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">{{ $item['qty'] }} x {{ $item['price'] }}৳</div>
                    </div>
                    <div style="font-weight: 700; font-size: 15px;">
                        {{ number_format($item['total']) }}৳
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-between align-center mb-4" style="padding: 16px; background: #DBEAFE; border-radius: 12px; border: 1px dashed #3B82F6;">
            <div style="font-weight: 600; font-size: 16px; color: #1D4ED8;">সর্বমোট:</div>
            <div style="font-weight: 700; font-size: 24px; color: #1D4ED8;">{{ number_format($invoice->total_amount) }}৳</div>
        </div>

        @if($invoice->status !== 'paid')
            <form method="POST" action="/admin/invoices/{{ $invoice->id }}/pay" enctype="multipart/form-data" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ইনভয়েসটি পে করা হয়েছে? এটি কোম্পানির খরচে (Expense) যুক্ত হবে।')">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; color: var(--text-secondary); display: block; margin-bottom: 4px;">পেমেন্ট রেফারেন্স (Txn ID) - ঐচ্ছিক</label>
                    <input type="text" name="payment_ref" placeholder="TrxID..." style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #E2E8F0; margin-bottom: 12px; box-sizing: border-box;">
                    
                    <label style="font-size: 13px; color: var(--text-secondary); display: block; margin-bottom: 4px;">প্রুফ স্ক্রিনশট - ঐচ্ছিক</label>
                    <input type="file" name="proof_file" accept="image/*,.pdf" style="width: 100%; font-size: 13px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 16px; padding: 14px; border-radius: 12px; font-weight: 600;">পে করুন</button>
            </form>
        @else
            <div style="text-align: center; color: var(--success); font-weight: 600; background: #DEF7EC; padding: 12px; border-radius: 12px; border: 1px solid #31C48D;">
                ✅ পেমেন্ট সম্পূর্ণ হয়েছে ({{ $invoice->paid_at->format('d M, Y') }})
            </div>
            @if($invoice->payment_ref)
                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #E2E8F0; font-size: 13px;">
                    <strong>Txn ID:</strong> {{ $invoice->payment_ref }}
                </div>
            @endif
            @if($invoice->proof_file)
                <div style="margin-top: 8px; font-size: 13px;">
                    <a href="{{ asset('storage/' . $invoice->proof_file) }}" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">📄 প্রুফ দেখুন</a>
                </div>
            @endif
        @endif
    </x-card>
</div>
@endsection
