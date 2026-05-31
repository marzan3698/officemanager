@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <h1>আমার টাস্ক</h1>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <form method="GET" action="/employee/tasks" class="d-flex">
            <select name="status" onchange="this.form.submit()">
                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>সব টাস্ক</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>অপেক্ষমান</option>
                <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>চলমান</option>
                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>সম্পন্ন</option>
            </select>
        </form>
    </div>

    @foreach($tasks as $task)
        <x-card>
            <div class="d-flex justify-between align-center mb-2">
                <div style="font-weight: 600; font-size: 16px;">{{ $task->title }}</div>
                <div>
                    @if($task->status === 'pending')
                        <x-badge type="warning">অপেক্ষমান</x-badge>
                    @elseif($task->status === 'in_progress')
                        <x-badge type="primary">চলমান</x-badge>
                    @else
                        <x-badge type="success">সম্পন্ন</x-badge>
                    @endif
                </div>
            </div>
            
            <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                {{ $task->description }}
            </div>
            
            <div class="d-flex justify-between align-center pt-2" style="border-top: 1px solid var(--border);">
                <div style="font-size: 12px; color: var(--text-secondary);">
                    শেষ তারিখ: {{ $task->due_date ? $task->due_date->format('d M, Y') : '-' }}
                </div>
                
                @if($task->status !== 'completed')
                    <form method="POST" action="/employee/tasks/{{ $task->id }}/status">
                        @csrf
                        @method('PATCH')
                        @if($task->status === 'pending')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-primary" style="padding: 4px 8px; font-size: 12px; width: auto;">শুরু করুন</button>
                        @elseif($task->status === 'in_progress')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-primary" style="background: var(--success); padding: 4px 8px; font-size: 12px; width: auto;">সম্পন্ন করুন</button>
                        @endif
                    </form>
                @endif
            </div>
        </x-card>
    @endforeach
</div>
@endsection
