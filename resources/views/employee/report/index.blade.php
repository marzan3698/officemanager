@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>আমার রিপোর্ট</h1>
    </div>
</div>

<div class="content">
    <x-card class="mb-4">
        <div class="section-title">বিগত ৬ মাসের আয় ও ব্যয়</div>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="employeeReportChart"></canvas>
        </div>
    </x-card>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('employeeReportChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_reverse($months)) !!},
            datasets: [
                {
                    label: 'মোট আয়',
                    data: {!! json_encode(array_reverse($earnings)) !!},
                    backgroundColor: '#1A56DB',
                    borderRadius: 4
                },
                {
                    label: 'কর্তন/ব্যয়',
                    data: {!! json_encode(array_reverse($deductions)) !!},
                    backgroundColor: '#E02424',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection
