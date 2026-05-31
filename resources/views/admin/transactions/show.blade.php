@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <div class="d-flex align-center">
            <a href="/admin/transactions" style="margin-right: 12px; font-size: 20px; text-decoration: none;">⬅</a>
            <h1>লেনদেন স্লিপ</h1>
        </div>
        <div class="d-flex" style="gap: 6px;">
            <a href="/admin/transactions/{{ $transaction->id }}/edit" class="btn" style="width: auto; padding: 8px 12px; border-radius: 20px; font-size: 12px; background: #E1EFFE; color: var(--primary);">✏️ এডিট</a>
            <form method="POST" action="/admin/transactions/{{ $transaction->id }}" style="margin: 0;" onsubmit="return confirm('এই লেনদেন মুছে ফেলতে চান?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="width: auto; padding: 8px 12px; border-radius: 20px; font-size: 12px; background: #FDE8E8; color: var(--danger);">🗑️ মুছুন</button>
            </form>
            <button onclick="window.print()" class="btn btn-primary" style="width: auto; padding: 8px 12px; border-radius: 20px; font-size: 12px;">🖨️ প্রিন্ট</button>
        </div>
    </div>
</div>

<div class="content">
    <div id="slip-container" style="background: white; border: 2px solid #E5E7EB; border-radius: 16px; padding: 24px; position: relative; overflow: hidden;">
        
        <!-- Decorative Top -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, var(--primary) 0%, #3F83F8 50%, var(--success) 100%);"></div>
        
        <!-- Company Header -->
        <div style="text-align: center; margin-top: 12px; margin-bottom: 20px;">
            <div style="font-size: 22px; font-weight: 700; color: var(--primary);">{{ config('app.name', 'অফিস ম্যানেজার') }}</div>
            <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">লেনদেন রসিদ / Transaction Slip</div>
        </div>
        
        <!-- Slip ID -->
        <div style="text-align: center; margin-bottom: 20px;">
            <span style="background: #F3F4F6; padding: 6px 16px; border-radius: 20px; font-size: 12px; color: var(--text-secondary); font-weight: 500;">
                স্লিপ নং: #TXN-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
            </span>
        </div>
        
        <!-- Divider -->
        <div style="border-top: 1px dashed #D1D5DB; margin-bottom: 16px;"></div>
        
        <!-- Transaction Details -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 13px; color: var(--text-secondary);">কর্মী</span>
                <span style="font-size: 14px; font-weight: 600;">{{ optional($transaction->employee)->name ?? 'অজ্ঞাত' }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 13px; color: var(--text-secondary);">মোবাইল</span>
                <span style="font-size: 14px; font-weight: 500;">{{ optional($transaction->employee)->mobile ?? 'অজ্ঞাত' }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 13px; color: var(--text-secondary);">তারিখ</span>
                <span style="font-size: 14px; font-weight: 500;">{{ $transaction->transaction_date->format('d M, Y') }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 13px; color: var(--text-secondary);">ধরণ</span>
                <span>
                    @if($transaction->type === 'payment')
                        <span style="background: #DEF7EC; color: var(--success); padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">পেমেন্ট</span>
                    @elseif($transaction->type === 'deduction')
                        <span style="background: #FDE8E8; color: var(--danger); padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">কর্তন</span>
                    @else
                        <span style="background: #E1EFFE; color: var(--primary); padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">বোনাস</span>
                    @endif
                </span>
            </div>
        </div>
        
        <!-- Divider -->
        <div style="border-top: 1px dashed #D1D5DB; margin: 16px 0;"></div>
        
        <!-- Amount -->
        <div style="text-align: center; padding: 16px; background: {{ $transaction->type === 'deduction' ? '#FDE8E8' : '#DEF7EC' }}; border-radius: 12px; margin-bottom: 16px;">
            <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">লেনদেনের পরিমাণ</div>
            <div style="font-size: 32px; font-weight: 700; color: {{ $transaction->type === 'deduction' ? 'var(--danger)' : 'var(--success)' }};">
                {{ $transaction->type === 'deduction' ? '-' : '+' }}{{ number_format($transaction->amount) }}৳
            </div>
        </div>
        
        <!-- Note -->
        @if($transaction->note)
        <div style="background: #F9FAFB; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            <div style="font-size: 11px; color: var(--text-secondary); margin-bottom: 4px;">নোট / বিবরণ</div>
            <div style="font-size: 14px;">{{ $transaction->note }}</div>
        </div>
        @endif
        
        <!-- Invoice -->
        @if($transaction->invoice_file)
        <div style="background: #E1EFFE; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            <a href="{{ asset('storage/' . $transaction->invoice_file) }}" target="_blank" style="font-size: 13px; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 8px;">
                📄 সংযুক্ত ইনভয়েস/ডকুমেন্ট দেখুন
            </a>
        </div>
        @endif
        
        <!-- Divider -->
        <div style="border-top: 1px dashed #D1D5DB; margin-bottom: 16px;"></div>
        
        <!-- Footer -->
        <div style="text-align: center;">
            <div style="font-size: 11px; color: var(--text-secondary);">তৈরির সময়: {{ $transaction->created_at->format('d M, Y h:i A') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">এটি একটি কম্পিউটার জেনারেটেড স্লিপ</div>
            <div style="margin-top: 12px; font-size: 10px; color: #9CA3AF;">Powered by Office Manager</div>
        </div>
        
        <!-- Decorative Bottom -->
        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, var(--primary) 0%, #3F83F8 50%, var(--success) 100%);"></div>
    </div>
</div>

<style>
    @media print {
        .header, .bottom-nav {
            display: none !important;
        }
        .app-container {
            max-width: 100% !important;
            box-shadow: none !important;
            padding-bottom: 0 !important;
        }
        .content {
            padding: 0 !important;
        }
        #slip-container {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
