@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/employees/{{ $employee->id }}" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>এডিট করুন</h1>
    </div>
</div>

<div class="content">
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <x-card>
        <form method="POST" action="/admin/employees/{{ $employee->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <label>নাম</label>
            <input type="text" name="name" value="{{ $employee->name }}" required>
            
            <label>মোবাইল</label>
            <input type="text" name="mobile" value="{{ $employee->mobile }}" required>
            
            <label>বেতন</label>
            <input type="number" name="salary" value="{{ $employee->salary }}" required>
            
            <label>প্রোফাইল ছবি পরিবর্তন (ঐচ্ছিক)</label>
            <input type="file" name="profile_image" accept="image/*">
            
            <div class="d-flex align-center mb-4 mt-4">
                <input type="checkbox" name="is_active" id="is_active" {{ $employee->is_active ? 'checked' : '' }} style="width: auto; margin-right: 8px; margin-bottom: 0;">
                <label for="is_active">অ্যাকাউন্ট সক্রিয়</label>
            </div>
            
            <input type="hidden" name="role" value="employee">
            <button type="submit" class="btn btn-primary mt-4">আপডেট করুন</button>
        </form>
    </x-card>
</div>
@endsection
