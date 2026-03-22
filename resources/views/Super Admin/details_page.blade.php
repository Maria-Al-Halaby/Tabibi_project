{{-- @extends('layouts.app')

@section('title', 'Main Page')

@section('content')
    <h1>Clinic Centers Count: {{ $ClinicCount }}</h1>
    <h1>Doctors Count : {{ $DoctorCount }}</h1>
    <h1>Patients Count : {{ $PatientCount }}</h1>
    <h1>Appointments Count : {{ $AppointmentCount }}</h1>

@endsection



@extends('layouts.app')

@section('title', 'Main Page')

@section('content')
    <!-- تنسيق مخصص لبطاقات الإحصائيات -->
    <style>
        :root {
            --main-color: #008080;
            /* اللون الأخضر المائي (Primary) */
            --second-color: #28a745;
            /* أخضر للإحصائيات */
            --warning-color: #ffc107;
            /* أصفر للإحصائيات */
        }

        /* تنسيق بطاقة الإحصاء */
        .stat-card {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            /* إضافة خط جانبي بلون مميز */
            border-left: 5px solid var(--main-color);
            min-height: 120px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* تنسيق الأيقونات داخل البطاقة */
        .stat-icon {
            font-size: 2.5rem;
        }

        /* تنسيق قيمة الإحصاء (العدد) */
        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #343a40;
            line-height: 1;
        }

        /* تنسيق عنوان الإحصاء */
        .stat-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>

    <div class="container py-5">

        <!-- عنوان لوحة التحكم -->
        <h2 class="mb-5 fw-bold text-center" style="color: var(--main-color);">
            <i class="bi bi-speedometer2 me-2"></i> SuperAdmin Dashboard Overview
        </h2>

        <!-- صف البطاقات الإحصائية (يصبح عموداً في الجوال) -->
        <div class="row">

            <!-- 1. Clinic Centers Count Card -->
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card d-flex align-items-center">
                    <i class="bi bi-hospital stat-icon me-4" style="color: var(--main-color);"></i>
                    <div>
                        <div class="stat-value">{{ $ClinicCount }}</div>
                        <div class="stat-title">Clinic Centers Count</div>
                    </div>
                </div>
            </div>

            <!-- 2. Doctors Count Card -->
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card d-flex align-items-center" style="border-left-color: var(--second-color);">
                    <i class="bi bi-person-badge-fill stat-icon me-4" style="color: var(--second-color);"></i>
                    <div>
                        <div class="stat-value">{{ $DoctorCount }}</div>
                        <div class="stat-title">Doctors Count</div>
                    </div>
                </div>
            </div>

            <!-- 3. Patients Count Card -->
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card d-flex align-items-center" style="border-left-color: var(--warning-color);">
                    <i class="bi bi-people-fill stat-icon me-4" style="color: var(--warning-color);"></i>
                    <div>
                        <div class="stat-value">{{ $PatientCount }}</div>
                        <div class="stat-title">Patients Count</div>
                    </div>
                </div>
            </div>

            <!-- 4. Appointments Count Card -->
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card d-flex align-items-center" style="border-left-color: #0d6efd;">
                    <i class="bi bi-calendar-check-fill stat-icon me-4" style="color: #0d6efd;"></i>
                    <div>
                        <div class="stat-value">{{ $AppointmentCount }}</div>
                        <div class="stat-title">Appointments Count</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection  --}}


@extends('layouts.app')

@section('title', 'Statistics Dashboard')

@section('content')
<div class="container py-5">
    <h2 class="mb-5 fw-bold text-center" style="color: #008080;">
        <i class="bi bi-graph-up-arrow me-2"></i> SuperAdmin Analytics Overview
    </h2>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">General Statistics (Bar View)</h5>
                    <canvas id="barChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Distribution</h5>
                    <canvas id="pieChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. إعداد البيانات القادمة من Laravel
    const statsData = {
        labels: ['Clinics', 'Doctors', 'Patients', 'Appointments'],
        values: [{{ $ClinicCount }}, {{ $DoctorCount }}, {{ $PatientCount }}, {{ $AppointmentCount }}],
        colors: ['#008080', '#28a745', '#ffc107', '#0d6efd']
    };

    // 2. إنشاء Bar Chart
    const ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: statsData.labels,
            datasets: [{
                label: 'Total Count',
                data: statsData.values,
                backgroundColor: statsData.colors,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 3. إنشاء Pie Chart
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut', // نوع الدونات يعطي مظهر عصري أكثر من Pie العادي
        data: {
            labels: statsData.labels,
            datasets: [{
                data: statsData.values,
                backgroundColor: statsData.colors,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection
