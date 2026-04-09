@extends('layouts.admin_app')

@section('title', 'Appointments')

@section('content')
    @php
        $appointmentCount = $appointments->count();
        $isSecretaryDashboard = ($dashboardMode ?? 'admin') === 'secretary';
        $dashboardTitle = $isSecretaryDashboard ? 'Secretary Appointment Desk' : 'Appointments';
        $dashboardLead = $isSecretaryDashboard
            ? 'Manage the front-desk queue by specialty, then cancel visits when scheduling changes need quick action.'
            : 'Upcoming appointments are presented as a clear action queue so your team can review schedules and resolve issues faster.';
        $dashboardBadge = $isSecretaryDashboard ? 'Appointment desk' : 'Appointments';
        $dashboardHomeRoute = $isSecretaryDashboard ? route('secretary.dashboard') : route('Admin.index');
        $filterRoute = $isSecretaryDashboard ? route('secretary.dashboard') : route('Admin.Appointment.index');
        $cancelRouteName = $isSecretaryDashboard ? 'secretary.appointments.cancel' : 'Admin.Appointment.cancel';
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-calendar-check"></i>
                {{ $dashboardTitle }}
            </span>
            <h1 class="page-title">Track bookings before they become bottlenecks.</h1>
            <p class="page-subtitle">
                {{ $dashboardLead }}
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hospital"></i>
                {{ $center->name }}
            </span>
            <span class="helper-badge">
                <i class="fas fa-list-check"></i>
                {{ number_format($appointmentCount) }} appointments
            </span>
        </div>
    </div>

    <section class="section-card mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
            <div>
                <h2 class="section-heading mb-1">Filter by specialty</h2>
                <p class="section-copy mb-0">Focus the queue on one specialty when the front desk needs a narrower view.</p>
            </div>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-filter"></i>
                {{ $dashboardBadge }}
            </span>
        </div>

        <form action="{{ $filterRoute }}" method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-8 col-xl-6">
                <label for="specialization_id" class="field-label">Specialty</label>
                <select name="specialization_id" id="specialization_id" class="form-select">
                    <option value="">All specialties</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" @selected(($selectedSpecializationId ?? null) == $specialization->id)>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-tabibi">
                    <i class="fas fa-filter me-2"></i>Apply filter
                </button>

                @if (!empty($selectedSpecializationId))
                    <a href="{{ $filterRoute }}" class="ghost-button">
                        <i class="fas fa-rotate-left"></i>
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </section>

    @if ($appointments->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-calendar-xmark"></i>
            </div>
            <h2 class="empty-state__title">No appointments are waiting right now.</h2>
            <p class="empty-state__copy">
                This center does not currently have pending appointments in the selected queue.
            </p>
            <a href="{{ $dashboardHomeRoute }}" class="ghost-button">
                <i class="fas fa-arrow-left"></i>
                {{ $isSecretaryDashboard ? 'Refresh appointment desk' : 'Back to overview' }}
            </a>
        </section>
    @else
        <div class="row g-4">
            @foreach ($appointments as $appointment)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card">
                        <div class="record-card__header">
                            <div>
                                <span class="status-pill status-pill--success">
                                    <i class="fas fa-circle-check"></i>
                                    Scheduled
                                </span>
                            </div>

                            <span class="helper-badge">
                                <i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::parse($appointment->start_at)->format('M d, H:i') }}
                            </span>
                        </div>

                        <h2 class="record-card__title mb-3">{{ $appointment->patient->user->name }}</h2>

                            <div class="d-grid gap-3 mb-4">
                                <div class="mini-metric">
                                    <div class="mini-metric__label">Assigned doctor</div>
                                    <p class="mini-metric__value">{{ $appointment->doctor->user->name }}</p>
                                </div>

                                <div class="mini-metric">
                                    <div class="mini-metric__label">Specialty</div>
                                    <p class="mini-metric__value">{{ $appointment->doctor->specialization->name ?? 'General' }}</p>
                                </div>

                                <div class="mini-metric">
                                    <div class="mini-metric__label">Visit time</div>
                                    <p class="mini-metric__value">{{ \Carbon\Carbon::parse($appointment->start_at)->format('l, M d Y - H:i') }}</p>
                                </div>
                            </div>

                            <div class="toolbar-actions">
                                <a href="{{ route($cancelRouteName, ['appointments' => $appointment->id, 'specialization_id' => $selectedSpecializationId]) }}"
                                    class="danger-outline-button"
                                    onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    <i class="fas fa-ban"></i>
                                    Cancel appointment
                                </a>
                            </div>
                        </section>
                    </div>
            @endforeach
        </div>
    @endif
@endsection
