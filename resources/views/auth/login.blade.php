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
    </div>
</div>
@endsection
