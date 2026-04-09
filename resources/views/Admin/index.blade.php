@extends('layouts.admin_app')

@section('title', 'Admin Dashboard')

@section('content')
    @php
        $centerName = auth()->user()?->clinic_center?->name ?? 'Your clinic center';
        $today = \Carbon\Carbon::now()->format('M d, Y');
        $totalEntities = $doctorCount + $appointmentsCount + $patientsCount + $specializationCount;
        $appointmentsPerDoctor = $doctorCount > 0 ? number_format($appointmentsCount / $doctorCount, 1) : '0.0';
        $patientsPerDoctor = $doctorCount > 0 ? number_format($patientsCount / $doctorCount, 1) : '0.0';
        $stats = [
            [
                'label' => 'Doctors',
                'value' => $doctorCount,
                'description' => 'Care providers currently linked to your center.',
                'icon' => 'fa-user-doctor',
                'color' => 'linear-gradient(135deg, #0f766e, #14b8a6)',
            ],
            [
                'label' => 'Appointments',
                'value' => $appointmentsCount,
                'description' => 'Booked visits that need scheduling attention.',
                'icon' => 'fa-calendar-check',
                'color' => 'linear-gradient(135deg, #2563eb, #60a5fa)',
            ],
            [
                'label' => 'Patients',
                'value' => $patientsCount,
                'description' => 'Unique patients engaged through this center.',
                'icon' => 'fa-user-group',
                'color' => 'linear-gradient(135deg, #f59e0b, #fbbf24)',
            ],
            [
                'label' => 'Specialties',
                'value' => $specializationCount,
                'description' => 'Distinct medical specialties available on site.',
                'icon' => 'fa-stethoscope',
                'color' => 'linear-gradient(135deg, #7c3aed, #a78bfa)',
            ],
        ];
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-gauge-high"></i>
                Admin Dashboard
            </span>
            <h1 class="page-title">Keep {{ $centerName }} calm, clear, and ready for patients.</h1>
            <p class="page-subtitle">
                This refreshed overview is designed around quick scanning, faster decisions, and direct paths to your most
                important admin tasks.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-calendar-day"></i>
                Updated {{ $today }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-layer-group"></i>
                {{ number_format($totalEntities) }} tracked records
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <section class="section-card h-100">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                    <div class="flex-grow-1">
                        <h2 class="section-heading">Today’s control tower</h2>
                        <p class="section-copy mb-4">
                            Balance staffing, appointments, and patient flow from one place. The dashboard now favors
                            readability first, then analytics second.
                        </p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Appointments per doctor</div>
                                    <p class="mini-metric__value">{{ $appointmentsPerDoctor }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Patients per doctor</div>
                                    <p class="mini-metric__value">{{ $patientsPerDoctor }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3" style="min-width: min(100%, 270px);">
                        <a href="{{ route('Admin.Appointment.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            <div class="action-tile__title">Review appointments</div>
                            <p class="action-tile__copy">Open the upcoming appointment queue and handle cancellations fast.</p>
                        </a>

                        <a href="{{ route('Admin.ClinicManagement.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="fas fa-hospital-user"></i>
                            </span>
                            <div class="action-tile__title">Manage clinic doctors</div>
                            <p class="action-tile__copy">Filter the medical team by specialty and keep center coverage balanced.</p>
                        </a>

                        <a href="{{ route('Admin.Secretary.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="fas fa-headset"></i>
                            </span>
                            <div class="action-tile__title">Manage secretary desk</div>
                            <p class="action-tile__copy">Add secretary accounts and keep appointment-desk access assigned to your center.</p>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <h2 class="section-heading">Operational highlights</h2>
                <p class="section-copy mb-4">A few readable signals that help you decide what needs attention next.</p>

                <div class="insight-list">
                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-wave-square"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Care capacity</h3>
                            <p class="insight-item__copy">
                                {{ $doctorCount }} doctors are supporting {{ $patientsCount }} patients across
                                {{ $specializationCount }} specialties.
                            </p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-clock-rotate-left"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Scheduling pressure</h3>
                            <p class="insight-item__copy">
                                {{ $appointmentsCount }} appointments are currently in the system, so appointment review is a
                                strong daily touchpoint.
                            </p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-shield-halved"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Coverage mix</h3>
                            <p class="insight-item__copy">
                                A wider specialty mix helps reduce bottlenecks and gives patients a smoother booking
                                experience.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @foreach ($stats as $stat)
            <div class="col-sm-6 col-xxl-3">
                <section class="section-card stat-card">
                    <div class="stat-card__top">
                        <div>
                            <div class="stat-card__eyebrow">{{ $stat['label'] }}</div>
                            <div class="stat-card__value">{{ number_format($stat['value']) }}</div>
                        </div>

                        <span class="stat-card__icon" style="background: {{ $stat['color'] }};">
                            <i class="fas {{ $stat['icon'] }}"></i>
                        </span>
                    </div>

                    <p class="stat-card__description">{{ $stat['description'] }}</p>
                </section>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <section class="section-card h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h2 class="section-heading mb-1">Center activity mix</h2>
                        <p class="section-copy">Compare your main operational categories without the old clutter.</p>
                    </div>
                    <span class="helper-badge">
                        <i class="fas fa-chart-column"></i>
                        Bar view
                    </span>
                </div>

                <div class="chart-wrap">
                    <canvas id="adminBarChart"></canvas>
                </div>

                <div class="chart-legend">
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #0f766e;"></span>
                        Doctors
                    </span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #2563eb;"></span>
                        Appointments
                    </span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #f59e0b;"></span>
                        Patients
                    </span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #7c3aed;"></span>
                        Specialties
                    </span>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h2 class="section-heading mb-1">Distribution snapshot</h2>
                        <p class="section-copy">A compact view for relative share across your center.</p>
                    </div>
                    <span class="helper-badge">
                        <i class="fas fa-chart-pie"></i>
                        Doughnut
                    </span>
                </div>

                <div class="chart-wrap">
                    <canvas id="adminPieChart"></canvas>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = ['Doctors', 'Appointments', 'Patients', 'Specialties'];
            const values = [
                {{ $doctorCount }},
                {{ $appointmentsCount }},
                {{ $patientsCount }},
                {{ $specializationCount }}
            ];
            const colors = ['#0f766e', '#2563eb', '#f59e0b', '#7c3aed'];

            const sharedTooltip = {
                callbacks: {
                    label(context) {
                        return `${context.label}: ${context.formattedValue}`;
                    }
                }
            };

            const barContext = document.getElementById('adminBarChart');
            if (barContext) {
                new Chart(barContext, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors.map((color) => `${color}CC`),
                            borderRadius: 18,
                            borderSkipped: false,
                            barThickness: 42,
                            hoverBackgroundColor: colors,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: sharedTooltip
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748b',
                                    font: {
                                        family: 'Manrope',
                                        weight: '700'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#64748b',
                                    font: {
                                        family: 'Manrope'
                                    }
                                },
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.16)',
                                    drawBorder: false
                                }
                            }
                        }
                    }
                });
            }

            const pieContext = document.getElementById('adminPieChart');
            if (pieContext) {
                new Chart(pieContext, {
                    type: 'doughnut',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors,
                            borderColor: '#f8fafc',
                            borderWidth: 6,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 10,
                                    color: '#334155',
                                    padding: 18,
                                    font: {
                                        family: 'Manrope',
                                        weight: '700'
                                    }
                                }
                            },
                            tooltip: sharedTooltip
                        }
                    }
                });
            }
        });
    </script>
@endpush
