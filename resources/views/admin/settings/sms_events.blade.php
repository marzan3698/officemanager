@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center" style="gap: 12px;">
        <a href="/admin/settings" style="width: 36px; height: 36px; background: var(--surface); border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 16px; flex-shrink: 0;">⬅</a>
        <div>
            <h1 style="margin: 0;">SMS ইভেন্ট সেটিংস</h1>
            <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">প্রতিটি ইভেন্টের জন্য SMS টেমপ্লেট কাস্টমাইজ করুন</div>
        </div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success mb-4">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error mb-4">❌ {{ session('error') }}</div>
    @endif

    @php
    $eventMeta = [
        'new_employee' => [
            'title' => 'নতুন কর্মী যোগ',
            'description' => 'নতুন কর্মী যোগ করার সাথে সাথে স্বয়ংক্রিয়ভাবে পাঠানো হয়',
            'icon' => '👤',
            'color' => '#3B82F6',
            'bg' => 'rgba(59,130,246,0.08)',
            'variables' => ['{name}' => 'কর্মীর নাম', '{password}' => 'প্রাথমিক পাসওয়ার্ড (Login ID)'],
            'trigger' => 'Admin → কর্মী যোগ করুন',
        ],
        'task_assigned' => [
            'title' => 'কাজ প্রদান (Task Assigned)',
            'description' => 'কর্মীকে নতুন কাজ দেওয়ার সাথে সাথে পাঠানো হয়',
            'icon' => '✅',
            'color' => '#10B981',
            'bg' => 'rgba(16,185,129,0.08)',
            'variables' => ['{name}' => 'কর্মীর নাম', '{task_name}' => 'কাজের শিরোনাম', '{project_name}' => 'প্রজেক্টের নাম'],
            'trigger' => 'Admin → নতুন কাজ যোগ করুন',
        ],
        'payment_made' => [
            'title' => 'পেমেন্ট/বেতন প্রদান',
            'description' => 'বেতন বা পেমেন্ট করার পর কর্মীকে জানানো হয়',
            'icon' => '💰',
            'color' => '#F59E0B',
            'bg' => 'rgba(245,158,11,0.08)',
            'variables' => ['{name}' => 'কর্মীর নাম', '{amount}' => 'পেমেন্টের পরিমাণ (টাকা)', '{ref}' => 'পেমেন্ট রেফারেন্স'],
            'trigger' => 'Admin → বেতন → পে করুন অথবা ট্রান্জেকশন',
        ],
        'task_reminder' => [
            'title' => 'টাস্ক রিমাইন্ডার ও পেনাল্টি',
            'description' => 'ডেডলাইনের আগে ৩টি রিমাইন্ডার এবং মিস করলে পেনাল্টি নোটিফিকেশন পাঠানো হয়',
            'icon' => '⏰',
            'color' => '#EF4444',
            'bg' => 'rgba(239,68,68,0.08)',
            'variables' => ['{name}' => 'কর্মীর নাম', '{task_name}' => 'কাজের শিরোনাম (পেনাল্টির ক্ষেত্রে পরিমাণ যোগ হয়)'],
            'trigger' => 'অটোমেটিক → ২৪ ঘণ্টা, ৬ ঘণ্টা, ১ ঘণ্টা আগে এবং মিস করলে',
        ],
    ];
    @endphp

    <form method="POST" action="/admin/settings/sms-events">
        @csrf
        
        @forelse($templates as $template)
        @php $meta = $eventMeta[$template->event] ?? null; @endphp
        
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            
            {{-- Event Header --}}
            <div style="background: {{ $meta['bg'] ?? '#F9FAFB' }}; padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 44px; height: 44px; background: {{ $meta['color'] ?? '#6B7280' }}20; border: 2px solid {{ $meta['color'] ?? '#6B7280' }}40; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                        {{ $meta['icon'] ?? '📩' }}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 15px;">
                            {{ $meta['title'] ?? $template->event }}
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                            {{ $meta['description'] ?? '' }}
                        </div>
                    </div>
                </div>
                
                {{-- Toggle Switch --}}
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; flex-shrink: 0;">
                    <input type="hidden" name="templates[{{ $template->event }}][is_active]" value="0">
                    <div style="position: relative;">
                        <input type="checkbox" 
                               name="templates[{{ $template->event }}][is_active]" 
                               value="1" 
                               id="event_{{ $template->event }}" 
                               {{ $template->is_active ? 'checked' : '' }}
                               style="display: none;"
                               onchange="this.closest('.toggle-wrap').querySelector('.toggle-track').style.background = this.checked ? 'var(--primary)' : '#D1D5DB'; this.closest('.toggle-wrap').querySelector('.toggle-thumb').style.transform = this.checked ? 'translateX(20px)' : 'translateX(2px)';">
                        <div class="toggle-wrap" onclick="this.querySelector('input').click();" style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                            <div class="toggle-track" style="width: 44px; height: 24px; border-radius: 12px; background: {{ $template->is_active ? 'var(--primary)' : '#D1D5DB' }}; transition: background 0.3s; position: relative;">
                                <div class="toggle-thumb" style="position: absolute; top: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: transform 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transform: translateX({{ $template->is_active ? '20' : '2' }}px);"></div>
                            </div>
                            <span style="font-size: 13px; font-weight: 500; color: {{ $template->is_active ? 'var(--primary)' : 'var(--text-secondary)' }};">
                                {{ $template->is_active ? 'সক্রিয়' : 'বন্ধ' }}
                            </span>
                        </div>
                    </div>
                </label>
            </div>
            
            {{-- Message Template --}}
            <div style="padding: 16px 20px;">
                <label style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; display: block;">
                    📝 SMS টেমপ্লেট মেসেজ
                </label>
                <textarea name="templates[{{ $template->event }}][message]" 
                          rows="3" 
                          placeholder="এখানে SMS মেসেজ লিখুন..."
                          style="font-size: 14px; line-height: 1.6; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border); background: #FAFAFA; width: 100%; box-sizing: border-box; resize: vertical; font-family: inherit;">{{ $template->message }}</textarea>
                <div style="font-size: 11px; color: var(--text-secondary); margin-top: 6px; text-align: right;" id="char_{{ $template->event }}">
                    {{ mb_strlen($template->message) }} অক্ষর
                </div>
                
                @if($meta && !empty($meta['variables']))
                {{-- Variables Guide --}}
                <div style="background: #F8FAFF; border: 1px solid #DBEAFE; border-radius: 8px; padding: 12px 14px; margin-top: 10px;">
                    <div style="font-size: 12px; font-weight: 600; color: #1D4ED8; margin-bottom: 8px;">🔤 উপলব্ধ ভ্যারিয়েবল (ক্লিক করে কপি করুন)</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @foreach($meta['variables'] as $var => $desc)
                        <div onclick="copyToTextarea('{{ $template->event }}', '{{ $var }}')" 
                             style="background: white; border: 1px solid #BFDBFE; border-radius: 6px; padding: 4px 10px; cursor: pointer; font-size: 12px; font-family: monospace; color: #1D4ED8; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; user-select: none;"
                             title="{{ $desc }}">
                            <span>{{ $var }}</span>
                            <span style="font-size: 10px; color: #93C5FD;">→ {{ $desc }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($meta && isset($meta['trigger']))
                <div style="margin-top: 10px; font-size: 11px; color: var(--text-secondary); display: flex; align-items: center; gap: 4px;">
                    <span>⚡</span>
                    <span><strong>কখন পাঠানো হয়:</strong> {{ $meta['trigger'] }}</span>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 48px; color: var(--text-secondary);">
            <div style="font-size: 48px; margin-bottom: 12px;">📭</div>
            <div>কোনো SMS ইভেন্ট পাওয়া যায়নি। সিস্টেম সিড রান করুন।</div>
        </div>
        @endforelse

        @if($templates->count() > 0)
        <div style="position: sticky; bottom: 80px; z-index: 10; padding: 12px 0;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; font-weight: 600; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                💾 পরিবর্তন সেভ করুন
            </button>
        </div>
        @endif
    </form>
</div>

<script>
function copyToTextarea(event, variable) {
    const textarea = document.querySelector(`textarea[name="templates[${event}][message]"]`);
    if (!textarea) return;
    
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    
    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.selectionStart = textarea.selectionEnd = start + variable.length;
    textarea.focus();
    
    // Update char count
    document.getElementById('char_' + event).textContent = textarea.value.length + ' অক্ষর';
}

// Live char count
document.querySelectorAll('textarea[name]').forEach(textarea => {
    const event = textarea.name.match(/templates\[(.+?)\]/)?.[1];
    if (!event) return;
    
    textarea.addEventListener('input', function() {
        const counter = document.getElementById('char_' + event);
        if (counter) counter.textContent = this.value.length + ' অক্ষর';
    });
});
</script>
@endsection
