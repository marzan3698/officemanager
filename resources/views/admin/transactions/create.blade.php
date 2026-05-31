@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/transactions" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>নতুন লেনদেন</h1>
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
        <form method="POST" action="/admin/transactions" enctype="multipart/form-data">
            @csrf
            
            <label>কর্মী নির্বাচন করুন</label>
            <select name="employee_id" required>
                <option value="">নির্বাচন করুন</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->mobile }})</option>
                @endforeach
            </select>
            
            <label>পেমেন্ট ধরণ</label>
            <select name="type" required>
                <option value="payment">বেতন/পেমেন্ট</option>
                <option value="deduction">কর্তন/জরিমানা</option>
                <option value="bonus">বোনাস/ইনসেনটিভ</option>
            </select>
            
            <label>প্রজেক্ট (ঐচ্ছিক)</label>
            <select name="project_id">
                <option value="">নির্বাচন করুন</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
            
            <label>পরিমাণ (টাকা)</label>
            <input type="number" name="amount" required>
            
            <label>তারিখ</label>
            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required>
            
            <label>নোট (ঐচ্ছিক)</label>
            <textarea name="note" rows="2"></textarea>
            
            <label>ইনভয়েস বা রশিদ (ঐচ্ছিক)</label>
            <input type="file" name="invoice_file" accept=".pdf,image/*">
            
            <button type="submit" class="btn btn-primary mt-4">সেভ করুন</button>
        </form>
    </x-card>
</div>
@endsection
