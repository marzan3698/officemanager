<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function general()
    {
        $setting = GeneralSetting::first() ?? new GeneralSetting();
        return view('admin.settings.general', compact('setting'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name'  => 'required|string|max:100',
            'short_name' => 'required|string|max:30',
            'favicon'    => 'nullable|image|max:2048',
            'logo'       => 'nullable|image|max:4096',
        ]);

        $setting = GeneralSetting::first();
        if (!$setting) {
            $setting = new GeneralSetting();
        }

        $setting->site_name  = $request->site_name;
        $setting->short_name = $request->short_name;

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->storeAs('general', 'favicon.png', 'public');
            // Also copy to public/favicon.ico for browser support
            copy(storage_path('app/public/' . $faviconPath), public_path('favicon.ico'));
            copy(storage_path('app/public/' . $faviconPath), public_path('icons/icon-192x192.png'));
            copy(storage_path('app/public/' . $faviconPath), public_path('icons/icon-512x512.png'));
            $setting->favicon = $faviconPath;
        }

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->storeAs('general', 'logo.png', 'public');
            $setting->logo = $logoPath;
        }

        $setting->save();

        // Update manifest.json dynamically
        $manifestPath = public_path('manifest.json');
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $manifest['name']        = $setting->site_name;
        $manifest['short_name']  = $setting->short_name;
        $manifest['theme_color'] = '#9D1C5B';
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return back()->with('success', 'সেটিংস আপডেট হয়েছে। পরিবর্তন দেখতে পেজ রিফ্রেশ করুন।');
    }

    public function resetData(Request $request)
    {
        $request->validate(['confirm_text' => 'required|in:RESET']);

        \App\Models\Transaction::truncate();
        \App\Models\Task::truncate();
        \App\Models\SalaryLog::truncate();
        \App\Models\SmsLog::truncate();
        \App\Models\Expense::truncate();
        \App\Models\CompanyIncome::truncate();
        \App\Models\User::where('role', 'employee')->delete();

        return back()->with('success', 'সকল ডাটা সফলভাবে রিসেট করা হয়েছে। শুধুমাত্র অ্যাডমিন একাউন্ট রয়ে গেছে।');
    }
}
