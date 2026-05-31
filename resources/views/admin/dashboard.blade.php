@extends('layouts.app')

@section('content')
<div class="dashboard-header">
    <div class="d-flex justify-between align-center mb-4">
        <div class="d-flex align-center gap-3">
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-right: 12px;">
                👨‍💼
            </div>
            <div>
                <div style="font-size: 14px; opacity: 0.9;">স্বাগতম,</div>
                <div style="font-size: 18px; font-weight: 700;">{{ auth()->user()->name }}</div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/settings" style="padding: 0 12px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 18px; display: flex; align-items: center; gap: 6px; color: white; text-decoration: none; font-size: 13px; font-weight: 500;">
                <span>⚙️</span> সেটিংস
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" style="padding: 0 12px; height: 36px; background: rgba(255,255,255,0.2); border: none; border-radius: 18px; display: flex; align-items: center; gap: 6px; color: white; cursor: pointer; font-size: 13px; font-weight: 500;">
                    <span>🚪</span> লগআউট
                </button>
            </form>
        </div>
    </div>
    
    <div style="background: rgba(255,255,255,0.15); padding: 16px; border-radius: 16px; display: flex; justify-content: space-between; align-items: center; backdrop-filter: blur(10px);">
        <div>
            <div style="font-size: 13px; opacity: 0.9; margin-bottom: 4px;">মোট খরচ (বেতন + পেমেন্ট)</div>
            <div style="font-size: 24px; font-weight: 700;">{{ number_format($totalExpense) }}৳</div>
        </div>
        <div style="font-size: 32px; opacity: 0.8;">📊</div>
    </div>
</div>

<div class="dashboard-overlap-card">
    <div class="service-grid">
        <a href="/admin/employees" class="service-item">
            <div class="service-icon"><img src="{{ asset('images/icons/employee_icon_1780216702010.png') }}" style="width: 100%; height: 100%; object-fit: contain;"></div>
            <div class="service-label">কর্মীরা</div>
        </a>
        <a href="/admin/transactions" class="service-item">
            <div class="service-icon"><img src="{{ asset('images/icons/transaction_icon_1780216715077.png') }}" style="width: 100%; height: 100%; object-fit: contain;"></div>
            <div class="service-label">লেনদেন</div>
        </a>
        <a href="/admin/tasks" class="service-item">
            <div class="service-icon"><img src="{{ asset('images/icons/task_icon_1780216728764.png') }}" style="width: 100%; height: 100%; object-fit: contain;"></div>
            <div class="service-label">কাজ</div>
        </a>
        <a href="/admin/salary" class="service-item">
            <div class="service-icon"><img src="{{ asset('images/icons/salary_icon_1780216754558.png') }}" style="width: 100%; height: 100%; object-fit: contain;"></div>
            <div class="service-label">বেতন</div>
        </a>
        <a href="/admin/report" class="service-item">
            <div class="service-icon"><img src="{{ asset('images/icons/report_icon_1780216768668.png') }}" style="width: 100%; height: 100%; object-fit: contain;"></div>
            <div class="service-label">রিপোর্ট</div>
        </a>
        <a href="/admin/projects" class="service-item">
            <div class="service-icon"><img src="{{ asset('images/icons/project_icon_1780216826498.png') }}" style="width: 100%; height: 100%; object-fit: contain;"></div>
            <div class="service-label">প্রজেক্ট</div>
        </a>
        <a href="/admin/invoices" class="service-item">
            <div class="service-icon"><img src="{{ asset('images/icons/invoice_icon_1780216787465.png') }}" style="width: 100%; height: 100%; object-fit: contain;"></div>
            <div class="service-label">ইনভয়েস</div>
        </a>
        <a href="/admin/incomes" class="service-item">
            <div class="service-icon"><img src="{{ asset('images/icons/income_icon_1780216812068.png') }}" style="width: 100%; height: 100%; object-fit: contain;"></div>
            <div class="service-label">আয়</div>
        </a>
    </div>

    <div class="section-title">কুইক ফিচারসমূহ</div>
    <div class="quick-features-scroll mb-4">
        <div class="quick-feature-card">
            <img src="{{ asset('images/icons/employee_icon_1780216702010.png') }}" style="width: 32px; height: 32px; object-fit: contain;">
            <div>
                <div style="font-size: 12px; color: var(--text-secondary);">মোট কর্মী</div>
                <div style="font-size: 16px; font-weight: 600;">{{ $totalEmployees }} জন</div>
            </div>
        </div>
        <div class="quick-feature-card">
            <img src="{{ asset('images/icons/pending_icon_1780217054686.png') }}" style="width: 32px; height: 32px; object-fit: contain;">
            <div>
                <div style="font-size: 12px; color: var(--text-secondary);">বকেয়া পেমেন্ট</div>
                <div style="font-size: 16px; font-weight: 600;">{{ $unpaidCount }} টি</div>
            </div>
        </div>
        <div class="quick-feature-card">
            <img src="{{ asset('images/icons/task_icon_1780216728764.png') }}" style="width: 32px; height: 32px; object-fit: contain;">
            <div>
                <div style="font-size: 12px; color: var(--text-secondary);">সম্পন্ন কাজ</div>
                <div style="font-size: 16px; font-weight: 600;">{{ $completedTasks }} টি</div>
            </div>
        </div>
    </div>

    @if($pendingExpenses->count() > 0)
        <div class="section-title mb-2" style="color: var(--warning);">অপেক্ষমান ইনভয়েস</div>
        @foreach($pendingExpenses as $expense)
            <x-card style="border-left: 4px solid var(--warning); margin-bottom: 16px;">
                <div class="d-flex justify-between align-center mb-2">
                    <div>
                        <div style="font-weight: 600;">{{ $expense->employee->name }}</div>
                        <div style="font-size: 13px;">{{ $expense->title }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: var(--warning);">{{ number_format($expense->amount) }}৳</div>
                        <div style="font-size: 11px; color: var(--text-secondary);">{{ $expense->created_at->format('d M, Y') }}</div>
                    </div>
                </div>
                
                @if($expense->invoice_file)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $expense->invoice_file) }}" target="_blank" style="font-size: 12px; color: var(--primary); text-decoration: none;">📄 ইনভয়েস দেখুন</a>
                    </div>
                @endif
                
                <form method="POST" action="/admin/expenses/{{ $expense->id }}/status" enctype="multipart/form-data" style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border);">
                    @csrf
                    @method('PATCH')
                    
                    <label style="font-size: 12px; color: var(--text-secondary);">পেমেন্ট রেফারেন্স (Txn ID)</label>
                    <input type="text" name="payment_ref" placeholder="TrxID (ঐচ্ছিক)">
                    
                    <label style="font-size: 12px; color: var(--text-secondary);">প্রুফ স্ক্রিনশট</label>
                    <input type="file" name="proof_file" accept="image/*,.pdf" style="font-size: 12px;">
                    
                    <div class="d-flex" style="gap: 8px;">
                        <button type="submit" name="status" value="approved" class="btn btn-primary" style="padding: 8px; font-size: 12px;">✅ পে করুন</button>
                        <button type="submit" name="status" value="rejected" class="btn" style="background: #F3F4F6; color: var(--danger); padding: 8px; font-size: 12px;">❌ বাতিল</button>
                    </div>
                </form>
            </x-card>
        @endforeach
    @endif

    <div class="d-flex justify-between align-center mb-2">
        <div class="section-title" style="margin-bottom: 0;">সাম্প্রতিক লেনদেন</div>
        <a href="/admin/transactions" style="font-size: 13px;">সব দেখুন ➔</a>
    </div>
    
    @foreach($recentTransactions as $transaction)
        <x-card>
            <div class="d-flex justify-between align-center">
                <div>
                    <div style="font-weight: 600;">{{ $transaction->employee->name }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $transaction->transaction_date->format('d M, Y') }}</div>
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
</div>
@endsection
