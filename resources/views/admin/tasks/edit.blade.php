@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/tasks" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>কাজ এডিট করুন</h1>
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
        <form method="POST" action="/admin/tasks/{{ $task->id }}">
            @csrf
            @method('PUT')
            
            <label>কর্মী নির্বাচন করুন</label>
            <select name="employee_id" required>
                <option value="">নির্বাচন করুন</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $task->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                @endforeach
            </select>
            
            <label>শিরোনাম</label>
            <input type="text" name="title" value="{{ $task->title }}" required>
            
            <label>প্রজেক্ট (ঐচ্ছিক)</label>
            <select name="project_id" style="margin-bottom: 16px;">
                <option value="">নির্বাচন করুন</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                @endforeach
            </select>
            
            <label>বিবরণ</label>
            <textarea name="description" rows="3">{{ $task->description }}</textarea>
            
            <label>শেষ তারিখ ও সময়</label>
            <input type="datetime-local" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '' }}">
            
            <label>পেনাল্টি এমাউন্ট (৳) - ঐচ্ছিক</label>
            <input type="number" step="0.01" name="penalty_amount" value="{{ $task->penalty_amount }}" placeholder="0.00">
            
            <label>স্ট্যাটাস</label>
            <select name="status">
                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>অপেক্ষমান</option>
                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>চলমান</option>
                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>সম্পন্ন</option>
            </select>
            
            <button type="submit" class="btn btn-primary mt-4">আপডেট করুন</button>
        </form>
    </x-card>
</div>
@endsection
