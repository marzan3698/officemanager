@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>আমার ইনভয়েস</h1>
        <a href="/employee/expenses/create" class="btn btn-primary" style="width: auto; padding: 8px 16px; border-radius: 20px;">+ জমা দিন</a>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach($expenses as $expense)
        <x-card>
            <div class="d-flex justify-between align-center mb-2">
                <div style="font-weight: 600;">{{ $expense->title }}</div>
                <div style="font-weight: 700;">{{ number_format($expense->amount) }}৳</div>
            </div>
            <div class="d-flex justify-between align-center">
                <div style="font-size: 12px; color: var(--text-secondary);">
                    {{ $expense->created_at->format('d M, Y') }}
                </div>
                <div>
                    @if($expense->status === 'pending')
                        <x-badge type="warning">অপেক্ষমান</x-badge>
                    @elseif($expense->status === 'approved')
                        <x-badge type="success">অনুমোদিত</x-badge>
                    @else
                        <x-badge type="danger">বাতিল</x-badge>
                    @endif
                </div>
            </div>
            
            @if($expense->status === 'approved' && $expense->payment_ref)
                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); font-size: 12px;">
                    <div><strong>পেমেন্ট রেফারেন্স:</strong> {{ $expense->payment_ref }}</div>
                    @if($expense->proof_file)
                        <div style="margin-top: 4px;">
                            <a href="{{ asset('storage/' . $expense->proof_file) }}" target="_blank" style="color: var(--primary);">📄 প্রুফ দেখুন</a>
                        </div>
                    @endif
                </div>
            @endif
        </x-card>
    @endforeach
    
    @if($expenses->isEmpty())
        <div style="text-align: center; color: var(--text-secondary); margin-top: 32px;">
            কোনো ইনভয়েস নেই
        </div>
    @endif
</div>
@endsection
