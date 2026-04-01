@extends('layouts.app')

@section('title', 'Statistics Dashboard')

@section('content')
    @php
        $today = \Carbon\Carbon::now()->format('M d, Y');
        $totalEntities = $ClinicCount + $DoctorCount + $PatientCount + $AppointmentCount;
        $doctorsPerClinic = $ClinicCount > 0 ? number_format($DoctorCount / $ClinicCount, 1) : '0.0';
        $appointmentsPerDoctor = $DoctorCount > 0 ? number_format($AppointmentCount / $DoctorCount, 1) : '0.0';
        $patientsPerClinic = $ClinicCount > 0 ? number_format($PatientCount / $ClinicCount, 1) : '0.0';
        $activeClinicRate = $ClinicCount > 0 ? round(($ActiveClinicCount / $ClinicCount) * 100) : 0;
        $activeDoctorRate = $DoctorCount > 0 ? round(($ActiveDoctorCount / $DoctorCount) * 100) : 0;
        $stats = [
            [
                'label' => 'Clinic centers',
                'value' => $ClinicCount,
                'description' => 'Total locations currently configured on the platform.',
                'icon' => 'bi-hospital-fill',
                'color' => 'linear-gradient(135deg, #0f766e, #2dd4bf)',
            ],
            [
                'label' => 'Doctors',
                'value' => $DoctorCount,
                'description' => 'Medical providers available across all managed centers.',
                'icon' => 'bi-person-badge-fill',
                'color' => 'linear-gradient(135deg, #2563eb, #60a5fa)',
            ],
            [
                'label' => 'Patients',
                'value' => $PatientCount,
                'description' => 'Registered patients currently represented in the system.',
                'icon' => 'bi-people-fill',
                'color' => 'linear-gradient(135deg, #f59e0b, #fbbf24)',
            ],
            [
                'label' => 'Appointments',
                'value' => $AppointmentCount,
                'description' => 'Platform-wide appointment activity across all centers.',
                'icon' => 'bi-calendar2-check-fill',
                'color' => 'linear-gradient(135deg, #7c3aed, #a78bfa)',
            ],
        ];
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-stars"></i>
                Super Admin Dashboard
            </span>
            <h1 class="page-title">Run the platform with better visibility and better flow.</h1>
            <p class="page-subtitle">
                The dashboard now prioritizes oversight, quick navigation, and high-level signals that matter to a
                platform operator, not just raw counts.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-calendar3"></i>
                Updated {{ $today }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="bi bi-layers-fill"></i>
                {{ number_format($totalEntities) }} platform records
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <section class="section-card h-100">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                    <div class="flex-grow-1">
                        <h2 class="section-heading">Platform command center</h2>
                        <p class="section-copy mb-4">
                            Use this view to understand network health, then jump directly into the management surface
                            you need without wading through dense pages first.
                        </p>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Doctors per clinic</div>
                                    <p class="mini-metric__value">{{ $doctorsPerClinic }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Appointments per doctor</div>
                                    <p class="mini-metric__value">{{ $appointmentsPerDoctor }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Patients per clinic</div>
                                    <p class="mini-metric__value">{{ $patientsPerClinic }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3" style="min-width: min(100%, 300px);">
                        <a href="{{ route('SuperAdmin.doctor.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="bi bi-person-badge-fill"></i>
                            </span>
                            <div class="action-tile__title">Manage doctors</div>
                            <p class="action-tile__copy">Review provider coverage, edit records, and keep the roster clean.</p>
                        </a>

                        <a href="{{ route('SuperAdmin.ClinicCenter.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="bi bi-hospital-fill"></i>
                            </span>
                            <div class="action-tile__title">Manage clinics</div>
                            <p class="action-tile__copy">Adjust center records, verify activity, and maintain network quality.</p>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <h2 class="section-heading">Governance highlights</h2>
                <p class="section-copy mb-4">Readable signals for network health and platform balance.</p>

                <div class="insight-list">
                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="bi bi-check2-circle"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Active clinic coverage</h3>
                            <p class="insight-item__copy">
                                {{ $ActiveClinicCount }} of {{ $ClinicCount }} clinics are active, which is
                                {{ $activeClinicRate }}% of the current network.
                            </p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="bi bi-person-check-fill"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Active doctor footprint</h3>
                            <p class="insight-item__copy">
                                {{ $ActiveDoctorCount }} of {{ $DoctorCount }} doctors are marked active, giving you a quick
                                staffing quality signal.
                            </p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="bi bi-activity"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Network activity</h3>
                            <p class="insight-item__copy">
                                Appointment volume currently sits at {{ $AppointmentCount }}, with an active-doctor rate of
                                {{ $activeDoctorRate }}%.
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
                            <i class="bi {{ $stat['icon'] }}"></i>
                        </span>
                    </div>

                    <p class="stat-card__description">{{ $stat['description'] }}</p>
                </section>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <section class="section-card h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h2 class="section-heading mb-1">Platform overview</h2>
                        <p class="section-copy">Balanced spacing, clearer labels, and easier comparison across core entities.</p>
                    </div>
                    <span class="helper-badge">
                        <i class="bi bi-bar-chart-fill"></i>
                        Bar view
                    </span>
                </div>

                <div class="chart-wrap">
                    <canvas id="superAdminBarChart"></canvas>
                </div>

                <div class="chart-legend">
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #0f766e;"></span>
                        Clinics
                    </span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #2563eb;"></span>
                        Doctors
                    </span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #f59e0b;"></span>
                        Patients
                    </span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #7c3aed;"></span>
                        Appointments
                    </span>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h2 class="section-heading mb-1">Distribution snapshot</h2>
                        <p class="section-copy">Relative share across your system at a glance.</p>
                    </div>
                    <span class="helper-badge">
                        <i class="bi bi-pie-chart-fill"></i>
                        Doughnut
                    </span>
                </div>

                <div class="chart-wrap">
                    <canvas id="superAdminPieChart"></canvas>
                </div>
            </section>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <section class="section-card">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h2 class="section-heading mb-1">Quick management routes</h2>
                        <p class="section-copy">The most important areas are now surfaced as direct, descriptive actions.</p>
                    </div>
                    <span class="helper-badge helper-badge--accent">
                        <i class="bi bi-compass-fill"></i>
                        Faster navigation
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('SuperAdmin.specialization.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="bi bi-grid-1x2-fill"></i>
                            </span>
                            <div class="action-tile__title">Specializations</div>
                            <p class="action-tile__copy">Control taxonomy and keep medical categories organized.</p>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('SuperAdmin.doctor.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="bi bi-person-badge-fill"></i>
                            </span>
                            <div class="action-tile__title">Doctors</div>
                            <p class="action-tile__copy">Edit profiles, maintain quality, and review staffing coverage.</p>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('SuperAdmin.Promot.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="bi bi-megaphone-fill"></i>
                            </span>
                            <div class="action-tile__title">Promotions</div>
                            <p class="action-tile__copy">Manage promotional visibility and support platform growth.</p>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('doctor_ratings.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="bi bi-stars"></i>
                            </span>
                            <div class="action-tile__title">Doctor ratings</div>
                            <p class="action-tile__copy">Review feedback quality and intervene when performance drops.</p>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = ['Clinics', 'Doctors', 'Patients', 'Appointments'];
            const values = [
                {{ $ClinicCount }},
                {{ $DoctorCount }},
                {{ $PatientCount }},
                {{ $AppointmentCount }}
            ];
            const colors = ['#0f766e', '#2563eb', '#f59e0b', '#7c3aed'];

            const sharedTooltip = {
                callbacks: {
                    label(context) {
                        return `${context.label}: ${context.formattedValue}`;
                    }
                }
            };

            const barContext = document.getElementById('superAdminBarChart');
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

            const pieContext = document.getElementById('superAdminPieChart');
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
