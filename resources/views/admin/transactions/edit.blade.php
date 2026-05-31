@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/transactions/{{ $transaction->id }}" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>লেনদেন এডিট</h1>
    </div>
</div>

<div class="content">
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <x-card>
        <form method="POST" action="/admin/transactions/{{ $transaction->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <label>কর্মী নির্বাচন করুন</label>
            <select name="employee_id" required>
                <option value="">নির্বাচন করুন</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $transaction->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }} ({{ $employee->mobile }})</option>
                @endforeach
            </select>
            
            <label>ধরন</label>
            <select name="type" required>
                <option value="payment" {{ $transaction->type === 'payment' ? 'selected' : '' }}>পেমেন্ট</option>
                <option value="deduction" {{ $transaction->type === 'deduction' ? 'selected' : '' }}>কর্তন</option>
                <option value="bonus" {{ $transaction->type === 'bonus' ? 'selected' : '' }}>বোনাস</option>
            </select>
            
            <label>পরিমাণ (টাকা)</label>
            <input type="number" name="amount" value="{{ $transaction->amount }}" required>
            
            <label>তারিখ</label>
            <input type="date" name="transaction_date" value="{{ $transaction->transaction_date->format('Y-m-d') }}" required>
            
            <label>নোট (ঐচ্ছিক)</label>
            <textarea name="note" rows="2">{{ $transaction->note }}</textarea>
            
            <label>ইনভয়েস বা রশিদ (ঐচ্ছিক)</label>
            @if($transaction->invoice_file)
                <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 8px;">📄 বর্তমান ফাইল আছে। নতুন আপলোড দিলে পুরোনোটি রিপ্লেস হবে।</div>
            @endif
            <input type="file" name="invoice_file" accept=".pdf,image/*">
            
            <button type="submit" class="btn btn-primary mt-4">আপডেট করুন</button>
        </form>
    </x-card>
</div>
@endsection
