@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center" style="gap: 12px;">
        <a href="/admin/settings" style="width: 36px; height: 36px; background: var(--surface); border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 16px; flex-shrink: 0;">⬅</a>
        <div>
            <h1 style="margin: 0;">জেনারেল সেটিংস</h1>
            <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">সাইটের নাম, লোগো এবং ফেভিকন পরিবর্তন করুন</div>
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

    {{-- App Identity --}}
    <form method="POST" action="/admin/settings/general" enctype="multipart/form-data">
        @csrf
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; margin-bottom: 20px;">
            
            <div style="background: linear-gradient(135deg, #9D1C5B15, #D42B6A10); padding: 16px 20px; border-bottom: 1px solid var(--border);">
                <div style="font-weight: 700; font-size: 15px; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                    🏢 অ্যাপ পরিচিতি (PWA + ব্রাউজার)
                </div>
                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">এই তথ্যগুলো PWA অ্যাপ, ব্রাউজার ট্যাব এবং হোম স্ক্রিনে দেখাবে</div>
            </div>
            
            <div style="padding: 20px;">
                <label style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; display: block;">📌 সাইটের পুরো নাম</label>
                <input type="text" name="site_name" value="{{ $setting->site_name ?? 'Shantikotha Office' }}" placeholder="যেমন: শান্তিকথা অফিস ম্যানেজার" style="margin-bottom: 16px;">
                @error('site_name')<div style="color:var(--danger); font-size:12px; margin-top:-8px; margin-bottom:8px;">{{ $message }}</div>@enderror
                
                <label style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; display: block;">📌 সংক্ষিপ্ত নাম (হোম স্ক্রিনে দেখাবে)</label>
                <input type="text" name="short_name" value="{{ $setting->short_name ?? 'Office' }}" placeholder="যেমন: অফিস" style="margin-bottom: 16px;">
                @error('short_name')<div style="color:var(--danger); font-size:12px; margin-top:-8px; margin-bottom:8px;">{{ $message }}</div>@enderror
                
                <hr style="border: none; border-top: 1px solid var(--border); margin: 4px 0 20px 0;">
                
                {{-- Favicon Upload --}}
                <label style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; display: block;">🖼️ ফেভিকন / PWA আইকন</label>
                <div style="background: #F9FAFB; border: 2px dashed var(--border); border-radius: 12px; padding: 16px; text-align: center; margin-bottom: 6px; cursor: pointer;" onclick="document.getElementById('favicon_input').click()">
                    @if($setting->favicon)
                        <img src="{{ asset('storage/' . $setting->favicon) }}" style="width: 64px; height: 64px; object-fit: contain; border-radius: 8px; margin-bottom: 8px; display: block; margin: 0 auto 8px;">
                    @else
                        <div style="font-size: 40px; margin-bottom: 8px;">🔖</div>
                    @endif
                    <div style="font-size: 13px; color: var(--text-secondary);">ক্লিক করে ফেভিকন আপলোড করুন</div>
                    <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">PNG / JPG, সর্বোচ্চ 2MB. এটি PWA আইকন হিসেবেও ব্যবহার হবে।</div>
                    <input type="file" id="favicon_input" name="favicon" accept="image/*" style="display: none;" onchange="previewImage(this, 'favicon_preview')">
                </div>
                <img id="favicon_preview" src="#" style="display:none; width: 64px; height: 64px; object-fit: contain; border-radius: 8px; margin: 8px auto; display: none; border: 1px solid var(--border);">
                
                <hr style="border: none; border-top: 1px solid var(--border); margin: 20px 0;">
                
                {{-- Logo Upload --}}
                <label style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; display: block;">🏷️ সাইটের লোগো</label>
                <div style="background: #F9FAFB; border: 2px dashed var(--border); border-radius: 12px; padding: 16px; text-align: center; margin-bottom: 6px; cursor: pointer;" onclick="document.getElementById('logo_input').click()">
                    @if($setting->logo)
                        <img src="{{ asset('storage/' . $setting->logo) }}" style="max-height: 60px; object-fit: contain; margin-bottom: 8px; display: block; margin: 0 auto 8px;">
                    @else
                        <div style="font-size: 40px; margin-bottom: 8px;">🏢</div>
                    @endif
                    <div style="font-size: 13px; color: var(--text-secondary);">ক্লিক করে লোগো আপলোড করুন</div>
                    <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">PNG / JPG, সর্বোচ্চ 4MB</div>
                    <input type="file" id="logo_input" name="logo" accept="image/*" style="display: none;" onchange="previewImage(this, 'logo_preview')">
                </div>
                <img id="logo_preview" src="#" style="display:none; max-height: 60px; object-fit: contain; margin: 8px auto; border: 1px solid var(--border); border-radius: 8px; padding: 4px;">
                
                <button type="submit" class="btn btn-primary mt-4" style="border-radius: 12px; padding: 14px; font-weight: 700;">
                    💾 পরিবর্তন সেভ করুন
                </button>
            </div>
        </div>
    </form>

    {{-- Danger Zone --}}
    <div style="background: var(--surface); border: 1px solid #FECACA; border-radius: 16px; overflow: hidden; margin-bottom: 80px;">
        <div style="background: #FEF2F2; padding: 16px 20px; border-bottom: 1px solid #FECACA;">
            <div style="font-weight: 700; font-size: 15px; color: var(--danger); display: flex; align-items: center; gap: 8px;">
                ⚠️ ডেঞ্জার জোন
            </div>
            <div style="font-size: 12px; color: #B91C1C; margin-top: 4px;">একবার করলে আর ফেরানো যাবে না!</div>
        </div>
        <div style="padding: 20px;">
            <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">
                এখানে ক্লিক করলে সাইটের সকল ডাটা (কর্মী, লেনদেন, কাজ, বেতন, ইনভয়েস, ইনকাম, SMS লগ) সম্পূর্ণ মুছে যাবে।
                <strong style="color: var(--danger);">শুধুমাত্র অ্যাডমিন একাউন্ট থাকবে।</strong>
            </p>
            <form method="POST" action="/admin/settings/reset-data">
                @csrf
                <label style="font-size: 13px; color: var(--danger); font-weight: 600; margin-bottom: 6px; display: block;">নিশ্চিত করতে নিচে "RESET" লিখুন</label>
                <input type="text" name="confirm_text" placeholder="RESET লিখুন" required style="border-color: var(--danger);">
                <button type="submit" class="btn mt-4" style="background: var(--danger); color: white; border-radius: 12px; padding: 14px;" onclick="return confirm('আপনি কি সত্যিই সকল ডাটা মুছে ফেলতে চান?')">
                    🗑️ সকল ডাটা রিসেট করুন
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
