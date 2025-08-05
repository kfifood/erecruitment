@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stat Cards -->
    <div class="row mb-4">
        <!-- Total Employees -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                TOTAL EMPLOYEES</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEmployees }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Applicants -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                TOTAL APPLICANTS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalApplicants }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Job Posts -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                TOTAL JOB POSTS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalJobs }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Interviewed -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                TOTAL INTERVIEWED</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalInterviewed }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Bar Chart (Full Month) -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Applications ({{ $currentMonth }})</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="applicationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Small Donut Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Hiring Decisions</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="chart-container" style="position: relative; height: 250px; width: 250px;">
                        <canvas id="decisionChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Hired
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Unhired
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Chart container styling */
    .chart-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    /* Bar chart specific styling */
    #applicationsChart {
        width: 100% !important;
        height: 100% !important;
    }
    
    /* Donut chart specific styling */
    #decisionChart {
        max-height: 250px;
        max-width: 250px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Applications Chart (Full Month)
    const appsCtx = document.getElementById('applicationsChart');
    if (appsCtx) {
        new Chart(appsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($applicationsPerDay->pluck('date')->map(function($date) {
                    return \Carbon\Carbon::parse($date)->format('d');
                })) !!},
                datasets: [{
                    label: 'Applications',
                    data: {!! json_encode($applicationsPerDay->pluck('count')) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1,
                    borderRadius: 2,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return 'Date: ' + {!! json_encode($applicationsPerDay->pluck('date')->map(function($date) {
                                    return \Carbon\Carbon::parse($date)->format('M d');
                                })) !!}[context[0].dataIndex];
                            },
                            label: function(context) {
                                return 'Applications: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, stepSize: 1 },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    }
                }
            }
        });
    }

    // 2. Small Donut Chart
    const decisionCtx = document.getElementById('decisionChart');
    if (decisionCtx) {
        new Chart(decisionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hired', 'Unhired'],
                datasets: [{
                    data: [
                        {{ $hiringDecisions['hired'] }},
                        {{ $hiringDecisions['unhired'] }}
                    ],
                    backgroundColor: [
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderColor: [
                        'rgba(28, 200, 138, 1)',
                        'rgba(231, 74, 59, 1)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                cutout: '65%',
                radius: '90%' // Membuat donut chart lebih kecil
            }
        });
    }

    // Trigger resize event to ensure proper rendering
    setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
    }, 200);
});
</script>
@endpush