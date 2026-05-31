@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/settings" style="margin-right: 16px; font-size: 20px; text-decoration: none;">⬅</a>
        <h1>SMS ইভেন্ট সেটিংস</h1>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <x-card class="mb-4">
        <form method="POST" action="/admin/settings/sms-events">
            @csrf
            
            <p style="font-size: 14px; color: var(--text-secondary); margin-bottom: 24px;">নিচের ইভেন্টগুলোর জন্য কাস্টম ম্যাসেজ সেট করতে পারবেন। ভ্যারিয়েবলগুলো {second_bracket} এর মাঝে লিখুন।</p>
            
            @foreach($templates as $template)
                <div style="background: #F9FAFB; border: 1px solid var(--border); padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <div class="d-flex justify-between align-center mb-2">
                        <div style="font-weight: 600;">
                            @if($template->event == 'new_employee') নতুন কর্মী যোগ
                            @elseif($template->event == 'task_assigned') কাজ প্রদান
                            @elseif($template->event == 'payment_made') পেমেন্ট/বেতন প্রদান
                            @elseif($template->event == 'task_reminder') টাস্ক রিমাইন্ডার
                            @else {{ $template->event }} @endif
                        </div>
                        <div class="d-flex align-center">
                            <input type="hidden" name="templates[{{ $template->event }}][is_active]" value="0">
                            <input type="checkbox" name="templates[{{ $template->event }}][is_active]" value="1" id="event_{{ $template->event }}" {{ $template->is_active ? 'checked' : '' }} style="width: auto; margin: 0 8px 0 0;">
                            <label for="event_{{ $template->event }}" style="font-size: 13px; margin: 0; cursor: pointer;">সক্রিয়</label>
                        </div>
                    </div>
                    <textarea name="templates[{{ $template->event }}][message]" rows="3" style="font-size: 14px; margin-bottom: 8px; padding: 12px;">{{ $template->message }}</textarea>
                    
                    <div style="font-size: 12px; color: var(--text-secondary); background: white; padding: 8px 12px; border-radius: 4px; border: 1px solid #E5E7EB;">
                        <strong style="color: var(--text-primary);">উপলব্ধ ভ্যারিয়েবল:</strong> 
                        @if($template->event == 'new_employee') {name}, {password}
                        @elseif($template->event == 'task_assigned') {name}, {task_name}, {project_name}
                        @elseif($template->event == 'payment_made') {name}, {amount}, {ref}
                        @elseif($template->event == 'task_reminder') {name}, {task_name}
                        @endif
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary mt-4">সেভ করুন</button>
        </form>
    </x-card>
</div>
@endsection
