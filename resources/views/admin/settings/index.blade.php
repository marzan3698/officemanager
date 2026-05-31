@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <h1>সেটিংস ড্যাশবোর্ড</h1>
</div>

<div class="content">
    <div class="service-grid">
        <a href="/admin/settings/sms" class="service-item" style="text-decoration: none;">
            <div class="service-icon" style="font-size: 32px; background: rgba(59, 130, 246, 0.1); border-radius: 16px; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: var(--primary);">💬</div>
            <div class="service-label" style="font-weight: 600; color: var(--text-primary);">SMS সেটিংস</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">API Key ও ইভেন্ট রিমাইন্ডার</div>
        </a>
        
        <a href="/admin/settings/sms-events" class="service-item" style="text-decoration: none;">
            <div class="service-icon" style="font-size: 32px; background: rgba(16, 185, 129, 0.1); border-radius: 16px; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: #10B981;">📨</div>
            <div class="service-label" style="font-weight: 600; color: var(--text-primary);">SMS ইভেন্ট</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">অটোমেটিক টেমপ্লেট ও ইভেন্ট</div>
        </a>

        <a href="/admin/settings/general" class="service-item" style="text-decoration: none;">
            <div class="service-icon" style="font-size: 32px; background: rgba(239, 68, 68, 0.1); border-radius: 16px; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: var(--danger);">⚙️</div>
            <div class="service-label" style="font-weight: 600; color: var(--text-primary);">জেনারেল সেটিংস</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">রিসেট ডাটা ও অন্যান্য</div>
        </a>
    </div>
</div>
@endsection
