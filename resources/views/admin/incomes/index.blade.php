@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <a href="/admin/dashboard" style="color: var(--text-primary); text-decoration: none;">
            <span style="font-size: 20px;">⬅</span>
        </a>
        <h1>আয়</h1>
        <div style="width: 24px;"></div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-title">নতুন আয় যুক্ত করুন</div>
    <x-card class="mb-4">
        <form method="POST" action="/admin/incomes">
            @csrf
            <input type="text" name="title" placeholder="আয়ের উৎস (যেমন: প্রজেক্ট পেমেন্ট)" required>
            <input type="number" name="amount" placeholder="টাকার পরিমাণ" required min="0">
            <input type="date" name="income_date" value="{{ date('Y-m-d') }}" required>
            
            <select name="employee_id" style="margin-bottom: 16px;">
                <option value="">কার মাধ্যমে আয় (ঐচ্ছিক)</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">যোগ করুন</button>
        </form>
    </x-card>

    <div class="section-title">সর্বশেষ আয়সমূহ</div>
    @foreach($incomes as $income)
        <x-card class="mb-2">
            <div class="d-flex justify-between align-center">
                <div>
                    <div style="font-weight: 600;">
                        {{ $income->title }}
                        @if($income->employee)
                            <span style="font-size: 11px; background: var(--primary); color: white; padding: 2px 6px; border-radius: 10px; margin-left: 4px;">{{ $income->employee->name }}</span>
                        @endif
                    </div>
                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $income->income_date->format('d M, Y') }}</div>
                </div>
                <div style="font-weight: 700; color: var(--success);">+{{ number_format($income->amount) }}৳</div>
            </div>
        </x-card>
    @endforeach
</div>
@endsection
