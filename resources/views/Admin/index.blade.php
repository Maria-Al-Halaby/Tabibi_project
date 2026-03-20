{{-- @extends('layouts.admin_app')

@section('title', 'Main page')


@section('content')

    <h1>Doctors Count : {{ $doctorCount }}</h1>
    <h1>Appintments Count : {{ $appointmentsCount }}</h1>
    <h1>patients Count : {{ $patientsCount }}</h1>
    <h1>Clinic Count : {{ $clinicCount }}</h1>
@endsection
 --}}


{{-- @extends('layouts.admin_app')

@section('title', 'Admin Dashboard')


@section('content')

    <h2 class="mb-4 tabibi-text-primary">👋 Welcome to Tabiby Admin Panel</h2>
    <p class="text-muted">Quick Overview of System Statistics:</p>

    <div class="row g-4">

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-md fa-3x me-3 tabibi-text-primary"></i>
                        <div>
                            <p class="text-uppercase text-muted mb-1 small">Doctors</p>
                            <h1 class="card-title mb-0 tabibi-text-primary">{{ $doctorCount }}</h1>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <small class="text-muted">Total registered doctors</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check fa-3x me-3 text-info"></i>
                        <div>
                            <p class="text-uppercase text-muted mb-1 small">Appointments</p>
                            <h1 class="card-title mb-0 text-info">{{ $appointmentsCount }}</h1>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <small class="text-muted">Upcoming and past appointments</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-3x me-3 text-success"></i>
                        <div>
                            <p class="text-uppercase text-muted mb-1 small">Patients</p>
                            <h1 class="card-title mb-0 text-success">{{ $patientsCount }}</h1>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <small class="text-muted">Total registered patients</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clinic-medical fa-3x me-3 text-warning"></i>
                        <div>
                            <p class="text-uppercase text-muted mb-1 small">Clinics</p>
                            <h1 class="card-title mb-0 text-warning">{{ $clinicCount }}</h1>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <small class="text-muted">Available clinic locations</small>
                </div>
            </div>
        </div>

    </div>
@endsection --}}


@extends('layouts.admin_app')

@section('title', 'Admin Dashboard')

@section('content')
    <h2 class="mb-4 tabibi-text-primary">👋 Welcome to Tabiby Admin Panel</h2>
    <p class="text-muted">Visual Analytics of System Statistics:</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold tabibi-text-primary">
                        <i class="fas fa-chart-bar me-2"></i> System Overview (Bar Chart)
                    </h5>
                    <div style="height: 350px;">
                        <canvas id="adminBarChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <small class="text-muted">Comparison between different system entities.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold tabibi-text-primary">
                        <i class="fas fa-chart-pie me-2"></i> Data Distribution
                    </h5>
                    <div style="height: 350px;">
                        <canvas id="adminPieChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <small class="text-muted">Percentage share of each category.</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // بيانات Laravel
            const labels = ['Doctors', 'Appointments', 'Patients', 'Clinics'];
            const dataValues = [
                {{ $doctorCount }}, 
                {{ $appointmentsCount }}, 
                {{ $patientsCount }}, 
                {{ $clinicCount }}
            ];
            
            // الألوان المتوافقة مع تصميمك الأصلي
            const colors = [
                '#008080', // Primary (Doctors)
                '#0dcaf0', // Info (Appointments)
                '#198754', // Success (Patients)
                '#ffc107'  // Warning (Clinics)
            ];

            // 1. إعداد Bar Chart
            const ctxBar = document.getElementById('adminBarChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Count',
                        data: dataValues,
                        backgroundColor: colors,
                        borderRadius: 10,
                        borderWidth: 0,
                        barThickness: 50
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // 2. إعداد Pie Chart (Doughnut)
            const ctxPie = document.getElementById('adminPieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: colors,
                        hoverOffset: 15,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    },
                    cutout: '70%' // يجعل الدائرة رشيقة (Doughnut)
                }
            });
        });
    </script>
@endsection
