@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/admin/employees" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>নতুন কর্মী যোগ করুন</h1>
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
        <form method="POST" action="/admin/employees" enctype="multipart/form-data">
            @csrf
            <label>নাম</label>
            <input type="text" name="name" required>
            
            <label>মোবাইল নম্বর (১১ ডিজিট)</label>
            <input type="text" name="mobile" required>
            
            <label>মাসিক বেতন (টাকা)</label>
            <input type="number" name="salary" required>
            

            <label>প্রোফাইল ছবি (ঐচ্ছিক)</label>
            <input type="file" name="profile_image" accept="image/*">
            
            <div class="d-flex align-center mb-4 mt-4">
                <input type="checkbox" name="is_active" id="is_active" checked style="width: auto; margin-right: 8px; margin-bottom: 0;">
                <label for="is_active">অ্যাকাউন্ট সক্রিয়</label>
            </div>
            
            <button type="submit" class="btn btn-primary">সেভ করুন</button>
        </form>
    </x-card>
</div>
@endsection
