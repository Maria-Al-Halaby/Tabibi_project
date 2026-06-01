@extends('layouts.admin_app')

@section('title', __('Admin Dashboard'))

@section('content')
    @php
        $centerName = auth()->user()?->clinic_center?->name ?? __('Your clinic center');
        $today = \Carbon\Carbon::now()->translatedFormat('M d, Y');
        $totalEntities = $doctorCount + $appointmentsCount + $patientsCount + $specializationCount;
        $appointmentsPerDoctor = $doctorCount > 0 ? number_format($appointmentsCount / $doctorCount, 1) : '0.0';
        $patientsPerDoctor = $doctorCount > 0 ? number_format($patientsCount / $doctorCount, 1) : '0.0';
        $stats = [
            [
                'label' => __('Doctors'),
                'value' => $doctorCount,
                'description' => __('Care providers currently linked to your center.'),
                'icon' => 'fa-user-doctor',
                'color' => 'linear-gradient(135deg, #0f766e, #14b8a6)',
            ],
            [
                'label' => __('Appointments'),
                'value' => $appointmentsCount,
                'description' => __('Booked visits that need scheduling attention.'),
                'icon' => 'fa-calendar-check',
                'color' => 'linear-gradient(135deg, #2563eb, #60a5fa)',
            ],
            [
                'label' => __('Patients'),
                'value' => $patientsCount,
                'description' => __('Unique patients engaged through this center.'),
                'icon' => 'fa-user-group',
                'color' => 'linear-gradient(135deg, #f59e0b, #fbbf24)',
            ],
            [
                'label' => __('Specialties'),
                'value' => $specializationCount,
                'description' => __('Distinct medical specialties available on site.'),
                'icon' => 'fa-stethoscope',
                'color' => 'linear-gradient(135deg, #7c3aed, #a78bfa)',
            ],
        ];
        $chartLabels = [__('Doctors'), __('Appointments'), __('Patients'), __('Specialties')];
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-gauge-high"></i>
                {{ __('Admin Dashboard') }}</span>
            <h1 class="page-title">{{ __('Keep :center calm, clear, and ready for patients.', ['center' => $centerName]) }}</h1>
            <p class="page-subtitle">
                {{ __('This refreshed overview is designed around quick scanning, faster decisions, and direct paths to your most
                important admin tasks.') }}</p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-calendar-day"></i>
                {{ __('Updated :date', ['date' => $today]) }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-layer-group"></i>
                {{ __(':count tracked records', ['count' => number_format($totalEntities)]) }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <section class="section-card h-100">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                    <div class="flex-grow-1">
                        <h2 class="section-heading">{{ __('Today’s control tower') }}</h2>
                        <p class="section-copy mb-4">
                            {{ __('Balance staffing, appointments, and patient flow from one place. The dashboard now favors
                            readability first, then analytics second.') }}</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">{{ __('Appointments per doctor') }}</div>
                                    <p class="mini-metric__value">{{ $appointmentsPerDoctor }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">{{ __('Patients per doctor') }}</div>
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
                            <div class="action-tile__title">{{ __('Review appointments') }}</div>
                            <p class="action-tile__copy">{{ __('Open the upcoming appointment queue and handle cancellations fast.') }}</p>
                        </a>

                        <a href="{{ route('Admin.ClinicManagement.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="fas fa-hospital-user"></i>
                            </span>
                            <div class="action-tile__title">{{ __('Manage clinic doctors') }}</div>
                            <p class="action-tile__copy">{{ __('Filter the medical team by specialty and keep center coverage balanced.') }}</p>
                        </a>

                        <a href="{{ route('Admin.Secretary.index') }}" class="action-tile">
                            <span class="action-tile__icon">
                                <i class="fas fa-headset"></i>
                            </span>
                            <div class="action-tile__title">{{ __('Manage secretary desk') }}</div>
                            <p class="action-tile__copy">{{ __('Add secretary accounts and keep appointment-desk access assigned to your center.') }}</p>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <h2 class="section-heading">{{ __('Operational highlights') }}</h2>
                <p class="section-copy mb-4">{{ __('A few readable signals that help you decide what needs attention next.') }}</p>

                <div class="insight-list">
                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-wave-square"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">{{ __('Care capacity') }}</h3>
                            <p class="insight-item__copy">
                                {{ __(':doctors doctors are supporting :patients patients across :specialties specialties.', [
                                    'doctors' => number_format($doctorCount),
                                    'patients' => number_format($patientsCount),
                                    'specialties' => number_format($specializationCount),
                                ]) }}
                            </p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-clock-rotate-left"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">{{ __('Scheduling pressure') }}</h3>
                            <p class="insight-item__copy">
                                {{ __(':count appointments are currently in the system, so appointment review is a strong daily touchpoint.', [
                                    'count' => number_format($appointmentsCount),
                                ]) }}
                            </p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-shield-halved"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">{{ __('Coverage mix') }}</h3>
                            <p class="insight-item__copy">
                                {{ __('A wider specialty mix helps reduce bottlenecks and gives patients a smoother booking
                                experience.') }}</p>
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
                        <h2 class="section-heading mb-1">{{ __('Center activity mix') }}</h2>
                        <p class="section-copy">{{ __('Compare your main operational categories without the old clutter.') }}</p>
                    </div>
                    <span class="helper-badge">
                        <i class="fas fa-chart-column"></i>
                        {{ __('Bar view') }}</span>
                </div>

                <div class="chart-wrap">
                    <canvas id="adminBarChart"></canvas>
                </div>

                <div class="chart-legend">
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #0f766e;"></span>
                        {{ __('Doctors') }}</span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #2563eb;"></span>
                        {{ __('Appointments') }}</span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #f59e0b;"></span>
                        {{ __('Patients') }}</span>
                    <span class="chart-legend__item">
                        <span class="chart-legend__dot" style="background: #7c3aed;"></span>
                        {{ __('Specialties') }}</span>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h2 class="section-heading mb-1">{{ __('Distribution snapshot') }}</h2>
                        <p class="section-copy">{{ __('A compact view for relative share across your center.') }}</p>
                    </div>
                    <span class="helper-badge">
                        <i class="fas fa-chart-pie"></i>
                        {{ __('Doughnut') }}</span>
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
            const labels = @json($chartLabels);
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
