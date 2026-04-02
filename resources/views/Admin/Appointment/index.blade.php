@extends('layouts.admin_app')

@section('title', 'Appointments')

@section('content')
    @php
        $appointmentCount = $appointments->count();
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-calendar-check"></i>
                Appointments
            </span>
            <h1 class="page-title">Track bookings before they become bottlenecks.</h1>
            <p class="page-subtitle">
                Upcoming appointments are presented as a clear action queue so your team can review schedules and resolve
                issues faster.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-list-check"></i>
                {{ number_format($appointmentCount) }} appointments
            </span>
        </div>
    </div>

    @if ($appointments->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-calendar-xmark"></i>
            </div>
            <h2 class="empty-state__title">No appointments are waiting right now.</h2>
            <p class="empty-state__copy">
                This center does not currently have scheduled appointments in the queue.
            </p>
            <a href="{{ route('Admin.index') }}" class="ghost-button">
                <i class="fas fa-arrow-left"></i>
                Back to overview
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
                                <div class="mini-metric__label">Visit time</div>
                                <p class="mini-metric__value">{{ \Carbon\Carbon::parse($appointment->start_at)->format('l, M d Y - H:i') }}</p>
                            </div>
                        </div>

                        <div class="toolbar-actions">
                            <a href="{{ route('Admin.Appointment.cancel', $appointment->id) }}"
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
