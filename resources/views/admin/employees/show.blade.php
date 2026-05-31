@extends('layouts.app')

@section('content')
@php
    // Calculate last 6 months expenses (Salary + Bonus)
    $months = [];
    $expenses = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $date = \Carbon\Carbon::now()->subMonths($i);
        $monthLabel = $date->format('M Y');
        
        $salary = $employee->salaryLogs->where('status', 'paid')
            ->filter(function($log) use ($date) {
                return $log->paid_at && $log->paid_at->year == $date->year && $log->paid_at->month == $date->month;
            })->sum('net_salary');
            
        $bonus = $employee->transactions->where('type', 'bonus')
            ->filter(function($txn) use ($date) {
                return $txn->transaction_date && $txn->transaction_date->year == $date->year && $txn->transaction_date->month == $date->month;
            })->sum('amount');
            
        $months[] = $monthLabel;
        $expenses[] = $salary + $bonus;
    }
@endphp

<div style="background: white; border-radius: 0 0 24px 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 16px; margin-top: -16px;">
    <!-- Cover Photo -->
    <div style="height: 140px; background: linear-gradient(135deg, var(--primary), #818CF8); position: relative;">
        <a href="/admin/employees" style="position: absolute; top: 16px; left: 16px; color: white; text-decoration: none; font-size: 20px; background: rgba(0,0,0,0.2); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; backdrop-filter: blur(4px);">⬅</a>
    </div>
    
    <!-- Profile Info -->
    <div style="padding: 0 24px 24px 24px; position: relative;">
        <!-- Avatar -->
        <div style="width: 100px; height: 100px; border-radius: 50%; background: white; padding: 4px; position: absolute; top: -50px; left: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div style="width: 100%; height: 100%; border-radius: 50%; overflow: hidden; background: #F3F4F6; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: bold; color: var(--primary);">
                @if($employee->profile_image)
                    <img src="{{ asset('storage/' . $employee->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ mb_substr($employee->name, 0, 1) }}
                @endif
            </div>
            
            @if($employee->is_active)
                <div style="position: absolute; bottom: 8px; right: 8px; width: 16px; height: 16px; background: var(--success); border: 2px solid white; border-radius: 50%;"></div>
            @endif
        </div>
        
        <!-- Details -->
        <div style="padding-top: 60px;">
            <div class="d-flex justify-between align-center">
                <h1 style="margin: 0 0 4px 0; font-size: 24px;">{{ $employee->name }}</h1>
                <button type="button" onclick="openEditModal()" style="background: #F3F4F6; color: #374151; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; font-family: inherit;">এডিট</button>
            </div>
            <div style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
                📞 {{ $employee->mobile }} &nbsp;•&nbsp; 🆔 {{ $employee->login_id }}
            </div>
            
            <!-- Salary Highlight -->
            <div class="d-flex align-center justify-between" style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 12px 16px; border-radius: 12px;">
                <div class="d-flex align-center" style="gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #DBEAFE; color: #1D4ED8; display: flex; align-items: center; justify-content: center; font-size: 20px;">💰</div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-secondary); font-weight: 600;">মাসিক বেতন</div>
                        <div style="font-weight: 700; font-size: 18px; color: var(--text-primary);">{{ number_format($employee->salary) }}৳</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabs Header -->
    <div style="display: flex; border-top: 1px solid #E5E7EB; background: white;">
        <button class="profile-tab active" onclick="switchTab('salary')">বেতন</button>
        <button class="profile-tab" onclick="switchTab('transactions')">লেনদেন</button>
        <button class="profile-tab" onclick="switchTab('tasks')">টাস্ক</button>
    </div>
</div>

<style>
    .profile-tab {
        flex: 1;
        background: transparent;
        border: none;
        padding: 16px 0;
        font-family: inherit;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-secondary);
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }
    .profile-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    .tab-content {
        display: none;
        padding: 0 16px 16px 16px;
        animation: fadeIn 0.3s;
    }
    .tab-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .list-item {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<!-- Salary Tab -->
<div id="tab-salary" class="tab-content active">
    @forelse($employee->salaryLogs as $log)
        <div class="list-item">
            <div>
                <div style="font-weight: 600; font-size: 15px; margin-bottom: 4px;">{{ $log->month }}</div>
                <div style="font-size: 12px; color: var(--text-secondary);">পেইড: {{ $log->paid_at ? $log->paid_at->format('d M, Y') : '-' }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px;">{{ number_format($log->net_salary) }}৳</div>
                @if($log->status === 'paid')
                    <span style="background: #DEF7EC; color: var(--success); padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">পরিশোধিত</span>
                @else
                    <span style="background: #FEF3C7; color: #D97706; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">বকেয়া</span>
                @endif
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 30px; color: var(--text-secondary);">কোনো বেতন রেকর্ড নেই</div>
    @endforelse
</div>

<!-- Transactions Tab -->
<div id="tab-transactions" class="tab-content">
    @forelse($employee->transactions()->orderBy('transaction_date', 'desc')->take(10)->get() as $transaction)
        <div class="list-item">
            <div class="d-flex align-center" style="gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; 
                    background: {{ $transaction->type === 'payment' ? '#DEF7EC' : ($transaction->type === 'deduction' ? '#FDE8E8' : '#E0E7FF') }};
                    color: {{ $transaction->type === 'payment' ? 'var(--success)' : ($transaction->type === 'deduction' ? 'var(--danger)' : 'var(--primary)') }};">
                    {{ $transaction->type === 'payment' ? '↓' : ($transaction->type === 'deduction' ? '↑' : '★') }}
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 14px; margin-bottom: 2px;">{{ Str::limit($transaction->note, 20) }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $transaction->transaction_date->format('d M, Y') }}</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; font-size: 16px; color: {{ $transaction->type === 'payment' ? 'var(--success)' : ($transaction->type === 'deduction' ? 'var(--danger)' : 'var(--primary)') }};">
                    {{ $transaction->type === 'deduction' ? '-' : '+' }}{{ number_format($transaction->amount) }}৳
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 30px; color: var(--text-secondary);">কোনো লেনদেন নেই</div>
    @endforelse
</div>

<!-- Tasks Tab -->
<div id="tab-tasks" class="tab-content">
    @forelse($employee->tasks()->orderBy('created_at', 'desc')->take(10)->get() as $task)
        <div class="list-item">
            <div>
                <div style="font-weight: 600; font-size: 15px; margin-bottom: 4px;">{{ $task->title }}</div>
                <div style="font-size: 12px; color: var(--text-secondary);">ড্যু: {{ $task->due_date ? $task->due_date->format('d M, Y') : 'নাই' }}</div>
            </div>
            <div>
                @if($task->status === 'pending')
                    <span style="background: #FEF3C7; color: #D97706; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">অপেক্ষমান</span>
                @elseif($task->status === 'in_progress')
                    <span style="background: #E0E7FF; color: var(--primary); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">চলমান</span>
                @else
                    <span style="background: #DEF7EC; color: var(--success); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">সম্পন্ন</span>
                @endif
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 30px; color: var(--text-secondary);">কোনো টাস্ক নেই</div>
    @endforelse
</div>

<!-- Chart Section (Always visible at the bottom) -->
<div style="padding: 0 16px 24px 16px;">
    <h2 style="font-size: 16px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 20px;">📈</span> ৬ মাসের খরচ রিপোর্ট
    </h2>
    <div style="background: white; border-radius: 16px; padding: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05);">
        <canvas id="expenseChart" height="200"></canvas>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(2px);">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 400px; max-height: 90vh; overflow-y: auto; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div class="d-flex justify-between align-center mb-4">
            <h3 style="margin: 0; font-size: 18px;">প্রোফাইল আপডেট</h3>
            <button type="button" onclick="closeEditModal()" style="background: transparent; border: none; font-size: 20px; cursor: pointer; color: var(--text-secondary);">✕</button>
        </div>
        
        <form method="POST" action="/admin/employees/{{ $employee->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="role" value="employee">
            
            <div style="margin-bottom: 16px; text-align: center;">
                <label for="profile_image" style="cursor: pointer; display: inline-block; position: relative;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #F3F4F6; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 30px; font-weight: bold; color: var(--primary); border: 2px dashed #CBD5E1;">
                        @if($employee->profile_image)
                            <img src="{{ asset('storage/' . $employee->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ mb_substr($employee->name, 0, 1) }}
                        @endif
                    </div>
                    <div style="position: absolute; bottom: 0; right: 0; background: var(--primary); color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; border: 2px solid white;">📷</div>
                </label>
                <input type="file" name="profile_image" id="profile_image" style="display: none;" accept="image/*" onchange="previewImage(event)">
                <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">ছবি পরিবর্তন করতে ক্লিক করুন</div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">নাম</label>
                <input type="text" name="name" value="{{ $employee->name }}" required style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; font-family: inherit;">
            </div>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">মোবাইল</label>
                <input type="text" name="mobile" value="{{ $employee->mobile }}" required minlength="11" maxlength="11" style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; font-family: inherit;">
            </div>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">মাসিক বেতন</label>
                <input type="number" name="salary" value="{{ $employee->salary }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; font-family: inherit;">
            </div>
            
            <div style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $employee->is_active ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                    <span style="font-size: 14px; font-weight: 600;">অ্যাকাউন্ট সক্রিয়</span>
                </label>
            </div>
            
            <button type="submit" style="width: 100%; background: var(--primary); color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; font-family: inherit; font-size: 15px; cursor: pointer;">আপডেট করুন</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function openEditModal() {
        document.getElementById('editModal').style.display = 'flex';
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    
    function previewImage(event) {
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var previewContainer = event.target.previousElementSibling.firstElementChild;
            previewContainer.innerHTML = '<img src="' + src + '" style="width: 100%; height: 100%; object-fit: cover;">';
        }
    }
    function switchTab(tabName) {
        document.querySelectorAll('.profile-tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        event.currentTarget.classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');
    }

    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('expenseChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'মোট খরচ (৳)',
                    data: {!! json_encode($expenses) !!},
                    backgroundColor: 'rgba(26, 86, 219, 0.8)',
                    borderRadius: 6,
                    borderWidth: 0,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { borderDash: [4, 4] }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
