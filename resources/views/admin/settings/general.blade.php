@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/settings" style="margin-right: 16px; font-size: 20px; text-decoration: none;">⬅</a>
        <h1>জেনারেল সেটিংস</h1>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- Danger Zone: Reset Data -->
    <div style="margin-top: 16px; border-top: 2px solid var(--danger); padding-top: 20px;">
        <h2 style="font-size: 16px; color: var(--danger); margin-bottom: 8px;">⚠️ ডেঞ্জার জোন</h2>
        <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">
            এখানে ক্লিক করলে সাইটের সকল ডাটা (কর্মী, লেনদেন, কাজ, বেতন, ইনভয়েস, ইনকাম, SMS লগ) সম্পূর্ণ মুছে যাবে। 
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
