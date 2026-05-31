@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <a href="/admin/dashboard" style="color: var(--text-primary); text-decoration: none;">
            <span style="font-size: 20px;">⬅</span>
        </a>
        <h1>প্রজেক্টসমূহ</h1>
        <button onclick="openModal()" class="btn btn-primary" style="width: auto; padding: 6px 12px; font-size: 14px; border-radius: 20px;">+ নতুন</button>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-title">সকল প্রজেক্ট</div>
    
    @forelse($projects as $project)
        <x-card class="mb-3">
            <div class="d-flex justify-between align-center mb-2">
                <div style="font-size: 16px; font-weight: 600; color: var(--text-primary);">{{ $project->name }}</div>
                @if($project->status === 'active')
                    <span style="font-size: 12px; font-weight: 600; color: var(--success); background: #DEF7EC; padding: 4px 8px; border-radius: 12px;">একটিভ</span>
                @else
                    <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary); background: #E5E7EB; padding: 4px 8px; border-radius: 12px;">সম্পন্ন</span>
                @endif
            </div>
            
            @if($project->description)
                <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                    {{ $project->description }}
                </div>
            @endif

            <div>
                <div style="font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 4px;">এসাইন করা কর্মীরা:</div>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    @forelse($project->employees as $emp)
                        <span style="font-size: 11px; background: var(--primary); color: white; padding: 4px 8px; border-radius: 12px;">{{ $emp->name }}</span>
                    @empty
                        <span style="font-size: 12px; color: var(--text-secondary); font-style: italic;">কাউকে এসাইন করা হয়নি</span>
                    @endforelse
                </div>
            </div>
        </x-card>
    @empty
        <div style="text-align: center; padding: 40px 20px; background: white; border-radius: 12px; border: 1px dashed var(--border);">
            <div style="font-size: 32px; margin-bottom: 12px;">📁</div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">কোনো প্রজেক্ট নেই</div>
            <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">আপনি এখনো কোনো প্রজেক্ট তৈরি করেননি।</div>
            <button onclick="openModal()" class="btn btn-primary" style="width: auto; padding: 8px 16px;">নতুন প্রজেক্ট তৈরি করুন</button>
        </div>
    @endforelse
</div>

<!-- Modal -->
<div id="projectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 16px; width: 100%; max-width: 400px; max-height: 90vh; display: flex; flex-direction: column;">
        <div style="padding: 16px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 600; font-size: 16px;">নতুন প্রজেক্ট</div>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-secondary);">&times;</button>
        </div>
        
        <div style="padding: 16px; overflow-y: auto;">
            <form id="projectForm" method="POST" action="/admin/projects">
                @csrf
                
                <!-- Step 1 -->
                <div id="step1">
                    <div style="font-size: 14px; font-weight: 500; margin-bottom: 12px; color: var(--primary);">ধাপ ১: প্রজেক্টের তথ্য</div>
                    
                    <label>প্রজেক্টের নাম <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="projectName" placeholder="প্রজেক্টের নাম লিখুন" required>
                    
                    <label>বিস্তারিত (ঐচ্ছিক)</label>
                    <textarea name="description" placeholder="প্রজেক্ট সম্পর্কে বিস্তারিত লিখুন" rows="3"></textarea>
                    
                    <button type="button" class="btn btn-primary" onclick="nextStep()" style="margin-top: 16px;">পরবর্তী ধাপ ➔</button>
                </div>
                
                <!-- Step 2 -->
                <div id="step2" style="display: none;">
                    <div style="font-size: 14px; font-weight: 500; margin-bottom: 12px; color: var(--primary);">ধাপ ২: কর্মী এসাইন (ঐচ্ছিক)</div>
                    
                    <input type="text" id="employeeSearch" placeholder="কর্মী খুঁজুন..." style="margin-bottom: 8px; padding: 8px; border-radius: 8px; border: 1px solid var(--border); width: 100%; box-sizing: border-box; font-size: 13px;" onkeyup="filterEmployees()">
                    
                    <div id="employeeGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; max-height: 250px; overflow-y: auto; background: var(--background); padding: 10px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 16px;">
                        @foreach($employees as $emp)
                            <label class="employee-card" style="display: flex; flex-direction: column; align-items: center; gap: 8px; background: white; padding: 10px; border-radius: 8px; border: 1px solid var(--border); cursor: pointer; text-align: center; position: relative; transition: 0.2s;">
                                <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" style="position: absolute; top: 8px; left: 8px; margin: 0; transform: scale(1.1); cursor: pointer;">
                                
                                @if($emp->profile_image)
                                    <img src="{{ asset('storage/' . $emp->profile_image) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px;">
                                        {{ mb_substr($emp->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="employee-name" style="font-size: 12px; font-weight: 500;">{{ $emp->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()" style="background: var(--border); color: var(--text-primary);">⬅ পেছনে</button>
                        <button type="submit" class="btn btn-primary">সেভ করুন</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('projectModal').style.display = 'flex';
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
}

function closeModal() {
    document.getElementById('projectModal').style.display = 'none';
    document.getElementById('projectForm').reset();
}

function nextStep() {
    let nameInput = document.getElementById('projectName');
    if (!nameInput.value.trim()) {
        alert('দয়া করে প্রজেক্টের নাম লিখুন');
        nameInput.focus();
        return;
    }
    
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
}

function prevStep() {
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
}

function filterEmployees() {
    let input = document.getElementById('employeeSearch').value.toLowerCase();
    let cards = document.querySelectorAll('.employee-card');
    
    cards.forEach(card => {
        let name = card.querySelector('.employee-name').innerText.toLowerCase();
        if (name.includes(input)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection
