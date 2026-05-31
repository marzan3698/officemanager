@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/tasks" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>নতুন কাজ দিন</h1>
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
        <form method="POST" action="/admin/tasks">
            @csrf
            
            <label>কর্মী নির্বাচন করুন</label>
            <select name="employee_id" required>
                <option value="">নির্বাচন করুন</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
            
            <label>শিরোনাম</label>
            <input type="text" name="title" required>
            
            <label>বিবরণ</label>
            <textarea name="description" rows="3"></textarea>
            
            <label>শেষ তারিখ</label>
            <input type="date" name="due_date">
            
            <label>স্ট্যাটাস</label>
            <select name="status">
                <option value="pending">অপেক্ষমান</option>
                <option value="in_progress">চলমান</option>
                <option value="completed">সম্পন্ন</option>
            </select>
            
            <button type="submit" class="btn btn-primary mt-4">সেভ করুন</button>
        </form>
    </x-card>
</div>
@endsection
