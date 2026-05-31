@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>কাজ ম্যানেজমেন্ট</h1>
        <a href="/admin/tasks/create" class="btn btn-primary" style="width: auto; padding: 8px 16px; border-radius: 20px;">+ নতুন</a>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <form method="GET" action="/admin/tasks" class="d-flex">
            <select name="status" onchange="this.form.submit()">
                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>সব কাজ</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>অপেক্ষমান</option>
                <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>চলমান</option>
                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>সম্পন্ন</option>
            </select>
        </form>
    </div>

    @foreach($tasks as $task)
        <x-card>
            <div class="d-flex justify-between align-center mb-2">
                <div class="d-flex align-center gap-2">
                    <div style="font-weight: 600; font-size: 16px;">{{ $task->title }}</div>
                    <a href="/admin/tasks/{{ $task->id }}/edit" style="color: var(--primary); text-decoration: none; font-size: 11px; background: rgba(212, 43, 106, 0.1); padding: 2px 8px; border-radius: 12px; font-weight: 600;">✏️ এডিট</a>
                </div>
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
            <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 8px;">
                {{ $task->description }}
            </div>
            @if($task->project)
                <div style="margin-bottom: 8px;">
                    <span style="font-size: 11px; font-weight: 500; background: var(--primary); color: white; padding: 2px 6px; border-radius: 10px;">📁 {{ $task->project->name }}</span>
                </div>
            @endif
            <div class="d-flex justify-between align-center" style="font-size: 12px;">
                <div style="color: var(--primary);">👤 {{ optional($task->employee)->name }}</div>
                <div style="color: var(--text-secondary);">📅 {{ $task->due_date ? $task->due_date->format('d M, Y') : '-' }}</div>
            </div>
        </x-card>
    @endforeach
</div>
@endsection
