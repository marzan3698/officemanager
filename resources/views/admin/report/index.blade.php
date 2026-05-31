@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>রিপোর্ট ও এনালাইসিস</h1>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <x-card class="mb-4">
        <form method="GET" action="/admin/report" class="d-flex align-center">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" style="margin-bottom: 0;">
        </form>
    </x-card>

    <div class="d-flex justify-between mb-4">
        <x-card style="width: 48%; text-align: center; border-bottom: 4px solid var(--success);">
            <div style="font-size: 12px; color: var(--text-secondary);">মোট আয়</div>
            <div style="font-size: 18px; font-weight: 700; color: var(--success);">{{ number_format($totalIncome) }}৳</div>
        </x-card>
        <x-card style="width: 48%; text-align: center; border-bottom: 4px solid var(--danger);">
            <div style="font-size: 12px; color: var(--text-secondary);">মোট ব্যয়</div>
            <div style="font-size: 18px; font-weight: 700; color: var(--danger);">{{ number_format($totalExpense) }}৳</div>
        </x-card>
    </div>

    <x-card class="mb-4">
        <div class="section-title">আয় বনাম ব্যয়</div>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="incomeExpenseChart"></canvas>
        </div>
    </x-card>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['মোট আয়', 'মোট ব্যয়'],
            datasets: [{
                label: 'পরিমাণ (৳)',
                data: [{{ $totalIncome }}, {{ $totalExpense }}],
                backgroundColor: ['#0E9F6E', '#E02424'],
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
