@extends('layouts.app')

@section('content')
<style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 24px;
        margin-top: -70px; /* offset bottom padding if active */
    }
    .login-card {
        background: var(--surface);
        padding: 32px;
        border-radius: var(--radius-md);
        width: 100%;
        box-shadow: var(--shadow-md);
        text-align: center;
    }
    .logo {
        font-size: 48px;
        margin-bottom: 16px;
    }
    .login-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .login-subtitle {
        color: var(--text-secondary);
        margin-bottom: 24px;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="logo">🏢</div>
        <h1 class="login-title">অফিস ম্যানেজার</h1>
        <p class="login-subtitle">আপনার লগইন আইডি দিন</p>

        @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <input type="text" name="login_id" placeholder="লগইন আইডি" required autocomplete="off">
            <button type="submit" class="btn btn-primary">প্রবেশ করুন</button>
        </form>

        <div style="margin-top: 24px;">
            <button id="pwa-install-btn" type="button" class="btn" style="display: none; background: #F3F4F6; color: #111827; border-radius: 20px; font-weight: 600; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border: 1px solid #E5E7EB;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                অ্যাপ ইনস্টল করুন
            </button>
            
            <div id="ios-instructions" style="display: none; background: #F3F4F6; padding: 16px; border-radius: 16px; font-size: 13px; color: #374151; text-align: left; line-height: 1.6; border: 1px solid #E5E7EB;">
                <strong>📱 iOS অ্যাপ ইনস্টল:</strong><br>
                ১. নিচের <strong>Share</strong> আইকনে ট্যাপ করুন<br>
                ২. <strong>"Add to Home Screen"</strong> এ ক্লিক করুন
            </div>
        </div>
    </div>
</div>
@endsection
