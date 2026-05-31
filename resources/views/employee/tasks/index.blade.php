@extends('layouts.app')

@section('content')
<style>
    .btn-start {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .btn-start.loading {
        color: transparent !important;
        pointer-events: none;
        transform: scale(0.95);
    }
    .btn-start.loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-top: -8px;
        margin-left: -8px;
        border: 2px solid white;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .task-card {
        transition: all 0.3s ease;
    }
    .task-card.starting {
        transform: scale(0.98);
        opacity: 0.8;
        border: 1px solid var(--primary);
    }
</style>

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
        <div class="task-card">
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
                                <button type="submit" class="btn btn-primary btn-start" onclick="startTask(this, event)" style="padding: 4px 8px; font-size: 12px; width: auto;">শুরু করুন</button>
                            @elseif($task->status === 'in_progress')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-primary" style="background: var(--success); padding: 4px 8px; font-size: 12px; width: auto;">সম্পন্ন করুন</button>
                            @endif
                        </form>
                    @endif
                </div>
            </x-card>
        </div>
    @endforeach
</div>

<script>
    function startTask(btn, e) {
        e.preventDefault();
        btn.classList.add('loading');
        btn.closest('.task-card').classList.add('starting');
        
        // Add a slight delay to let the animation play before submitting
        setTimeout(() => {
            btn.closest('form').submit();
        }, 500);
    }
</script>
@endsection
