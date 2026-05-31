@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <a href="/admin/dashboard" style="color: var(--text-primary); text-decoration: none;">
            <span style="font-size: 20px;">⬅</span>
        </a>
        <h1>প্রজেক্টসমূহ</h1>
        <div style="width: 24px;"></div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-title">নতুন প্রজেক্ট তৈরি করুন</div>
    <x-card class="mb-4">
        <form method="POST" action="/admin/projects">
            @csrf
            <input type="text" name="name" placeholder="প্রজেক্টের নাম" required>
            <textarea name="description" placeholder="প্রজেক্টের বিস্তারিত (ঐচ্ছিক)" rows="3" style="margin-bottom: 16px;"></textarea>
            
            <div style="margin-bottom: 16px;">
                <label style="font-size: 14px; font-weight: 500; color: var(--text-secondary); display: block; margin-bottom: 8px;">কর্মীদের এসাইন করুন (ঐচ্ছিক):</label>
                <div style="max-height: 150px; overflow-y: auto; background: var(--background); padding: 8px; border-radius: 8px; border: 1px solid var(--border);">
                    @foreach($employees as $emp)
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}">
                            <span style="font-size: 14px;">{{ $emp->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">প্রজেক্ট তৈরি করুন</button>
        </form>
    </x-card>

    <div class="section-title">সকল প্রজেক্ট</div>
    @foreach($projects as $project)
        <x-card class="mb-3">
            <div class="d-flex justify-between align-center mb-2">
                <div style="font-size: 16px; font-weight: 600; color: var(--text-primary);">{{ $project->name }}</div>
                @if($project->status === 'active')
                    <span style="font-size: 12px; font-weight: 600; color: var(--success); background: #DEF7EC; padding: 4px 8px; border-radius: 12px;">একটিভ</span>
                @else
                    <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary); background: #E5E7EB; padding: 4px 8px; border-radius: 12px;">সম্পন্ন</span>
                @endif
            </div>
            
            @if($project->description)
                <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                    {{ $project->description }}
                </div>
            @endif

            <div>
                <div style="font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 4px;">এসাইন করা কর্মীরা:</div>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    @forelse($project->employees as $emp)
                        <span style="font-size: 11px; background: var(--primary); color: white; padding: 4px 8px; border-radius: 12px;">{{ $emp->name }}</span>
                    @empty
                        <span style="font-size: 12px; color: var(--text-secondary); font-style: italic;">কাউকে এসাইন করা হয়নি</span>
                    @endforelse
                </div>
            </div>
        </x-card>
    @endforeach
    
    @if($projects->isEmpty())
        <div style="text-align: center; color: var(--text-secondary); padding: 20px;">
            কোনো প্রজেক্ট পাওয়া যায়নি
        </div>
    @endif
</div>
@endsection
