@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>কর্মী ব্যবস্থাপনা</h1>
        <a href="/admin/employees/create" class="btn btn-primary" style="width: auto; padding: 8px 16px; border-radius: 20px;">+ নতুন</a>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 16px;">
    @foreach($employees as $employee)
        <x-card style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 16px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; overflow: hidden; margin-bottom: 12px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">
                @if($employee->profile_image)
                    <img src="{{ asset('storage/' . $employee->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ mb_substr($employee->name, 0, 1) }}
                @endif
            </div>
            
            <div style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">{{ $employee->name }}</div>
            <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 8px;">{{ $employee->mobile }}</div>
            
            @if($employee->is_active)
                <x-badge type="success" style="margin-bottom: 8px;">সক্রিয়</x-badge>
            @else
                <x-badge type="danger" style="margin-bottom: 8px;">নিষ্ক্রিয়</x-badge>
            @endif
            
            <div class="d-flex" style="gap: 6px; align-items: center;">
                <a href="/admin/employees/{{ $employee->id }}" class="btn btn-primary" style="padding: 4px 12px; font-size: 12px; width: auto; border-radius: 12px; text-decoration: none;">দেখুন ➔</a>
                <form method="POST" action="/admin/employees/{{ $employee->id }}" style="margin: 0;" onsubmit="return confirm('এই কর্মী মুছে ফেলতে চান? তার সকল লেনদেন ও টাস্কও মুছে যাবে।')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="padding: 4px 10px; font-size: 12px; width: auto; border-radius: 12px; background: #FDE8E8; color: var(--danger);">🗑️</button>
                </form>
            </div>
        </x-card>
    @endforeach
    </div>
</div>
@endsection
