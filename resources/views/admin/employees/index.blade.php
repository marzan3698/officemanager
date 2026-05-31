@extends('layouts.app')

@section('content')
<div class="dashboard-header">
    <div class="d-flex justify-between align-center">
        <h1>কর্মী ব্যবস্থাপনা</h1>
        <a href="/admin/employees/create" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; text-decoration: none; backdrop-filter: blur(10px);">+ নতুন কর্মী</a>
    </div>
</div>

<div class="dashboard-overlap-card" style="background: #F4F7FA;">
    @if(session('success'))
        <div class="alert alert-success" style="border-radius: 12px; border-left: 4px solid var(--success); box-shadow: 0 4px 6px rgba(0,0,0,0.05);">{{ session('success') }}</div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 16px;">
    @forelse($employees as $employee)
        <div style="background: white; border-radius: 16px; padding: 16px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); transition: transform 0.2s;">
            <div class="d-flex align-center" style="gap: 16px;">
                <div style="width: 56px; height: 56px; border-radius: 16px; overflow: hidden; background: linear-gradient(135deg, var(--primary), #4A7DF0); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; flex-shrink: 0; box-shadow: 0 4px 8px rgba(26,86,219,0.2);">
                    @if($employee->profile_image)
                        <img src="{{ asset('storage/' . $employee->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ mb_substr($employee->name, 0, 1) }}
                    @endif
                </div>
                
                <div>
                    <div style="font-weight: 700; font-size: 16px; color: var(--text-primary); margin-bottom: 4px;">{{ $employee->name }}</div>
                    <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 6px;">{{ $employee->mobile }}</div>
                    
                    @if($employee->is_active)
                        <span style="display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: 600; border-radius: 12px; background: #DEF7EC; color: var(--success);">সক্রিয়</span>
                    @else
                        <span style="display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: 600; border-radius: 12px; background: #FDE8E8; color: var(--danger);">নিষ্ক্রিয়</span>
                    @endif
                </div>
            </div>
            
            <div class="d-flex" style="flex-direction: column; gap: 8px;">
                <a href="/admin/employees/{{ $employee->id }}" style="display: flex; justify-content: center; align-items: center; width: 36px; height: 36px; border-radius: 50%; background: #F3F4F6; color: var(--primary); text-decoration: none; transition: background 0.2s;">
                    ➔
                </a>
                <form method="POST" action="/admin/employees/{{ $employee->id }}" style="margin: 0;" onsubmit="return confirm('এই কর্মী মুছে ফেলতে চান? তার সকল লেনদেন ও টাস্কও মুছে যাবে।')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="display: flex; justify-content: center; align-items: center; width: 36px; height: 36px; border-radius: 50%; background: #FDE8E8; color: var(--danger); border: none; cursor: pointer;">
                        🗑️
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px 20px; color: var(--text-secondary);">
            <div style="font-size: 48px; margin-bottom: 16px;">👥</div>
            <h3 style="margin-bottom: 8px;">কোনো কর্মী পাওয়া যায়নি</h3>
            <p style="font-size: 14px;">নতুন কর্মী যোগ করতে উপরের বাটনে ক্লিক করুন।</p>
        </div>
    @endforelse
    </div>
</div>
@endsection
