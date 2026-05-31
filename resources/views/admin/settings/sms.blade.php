@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <h1>SMS সেটিংস</h1>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <x-card class="mb-4">
        <form method="POST" action="/admin/settings/sms">
            @csrf
            
            <label>Green Web SMS API Key</label>
            <input type="text" name="api_key" value="{{ $setting->api_key }}">
            
            <label>Sender ID (Optional)</label>
            <input type="text" name="sender_id" value="{{ $setting->sender_id }}">
            
            <div class="d-flex align-center mb-4 mt-4">
                <input type="checkbox" name="is_active" id="is_active" {{ $setting->is_active ? 'checked' : '' }} style="width: auto; margin-right: 8px; margin-bottom: 0;">
                <label for="is_active">SMS সক্রিয় করুন</label>
            </div>
            
            <button type="submit" class="btn btn-primary">সেভ করুন</button>
        </form>
    </x-card>
    
    <h2 class="mb-2" style="font-size: 16px;">টেস্ট SMS পাঠান</h2>
    <x-card class="mb-4">
        <form method="POST" action="/admin/settings/sms/test">
            @csrf
            <div class="d-flex gap-2" style="gap: 8px;">
                <input type="text" name="mobile" placeholder="মোবাইল নম্বর" required style="margin-bottom: 0; flex: 1;">
                <button type="submit" class="btn btn-primary" style="width: auto; white-space: nowrap;">পরীক্ষা করুন</button>
            </div>
        </form>
    </x-card>

    <h2 class="mb-2" style="font-size: 16px;">সাম্প্রতিক SMS লগ</h2>
    @foreach($logs as $log)
        <x-card>
            <div class="d-flex justify-between align-center mb-2">
                <div style="font-weight: 600;">{{ $log->recipient_mobile }}</div>
                <div>
                    @if($log->status === 'sent')
                        <x-badge type="success">সফল</x-badge>
                    @else
                        <x-badge type="danger">ব্যর্থ</x-badge>
                    @endif
                </div>
            </div>
            <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 4px;">{{ $log->message }}</div>
            <div style="font-size: 11px; color: var(--text-secondary);">{{ $log->sent_at->format('d M, Y h:i A') }}</div>
        </x-card>
    @endforeach

    <!-- Danger Zone: Reset Data -->
    <div style="margin-top: 32px; border-top: 2px solid var(--danger); padding-top: 20px;">
        <h2 style="font-size: 16px; color: var(--danger); margin-bottom: 8px;">⚠️ ডেঞ্জার জোন</h2>
        <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">
            এখানে ক্লিক করলে সাইটের সকল ডাটা (কর্মী, লেনদেন, টাস্ক, বেতন, ইনভয়েস, ইনকাম, SMS লগ) সম্পূর্ণ মুছে যাবে। 
            <strong>শুধুমাত্র অ্যাডমিন একাউন্ট থাকবে।</strong> এই কাজ অপরিবর্তনীয়!
        </p>
        <x-card style="border: 2px solid var(--danger); background: #FDF2F2;">
            <form method="POST" action="/admin/settings/reset-data" id="reset-form">
                @csrf
                <label style="font-size: 13px; color: var(--danger); font-weight: 600;">নিশ্চিত করতে নিচে "RESET" লিখুন</label>
                <input type="text" name="confirm_text" placeholder="RESET লিখুন" required style="border-color: var(--danger);">
                <button type="submit" class="btn" style="background: var(--danger); color: white;" onclick="return confirm('আপনি কি সত্যিই সকল ডাটা মুছে ফেলতে চান? এটি পূর্বাবস্থায় ফেরানো যাবে না!')">🗑️ সকল ডাটা রিসেট করুন</button>
            </form>
        </x-card>
    </div>
</div>
@endsection
