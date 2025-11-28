@extends('layouts.admin_app')

@section('title', 'Appointment in This Center')


@section('content')

    <h2 class="mb-4">
        <i class="fas fa-calendar-alt tabibi-text-primary me-2"></i> Upcoming Appointments
    </h2>
    <hr>

    <div class="row g-4">

        @forelse ($appointments as $appointment)
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold tabibi-text-primary">
                                <i class="fas fa-bookmark me-2"></i> New Appointment
                            </h5>
                            <span class="badge bg-success rounded-pill p-2">Scheduled</span>
                        </div>

                        <div class="mb-2">
                            <p class="mb-0 text-muted small">Patient Name:</p>
                            <p class="fw-bold mb-0 text-dark">
                                <i class="fas fa-user me-2 text-secondary"></i> {{ $appointment->user->name }}
                            </p>
                        </div>

                        <div class="mb-2">
                            <p class="mb-0 text-muted small">Doctor Name:</p>
                            <p class="fw-bold mb-0 text-dark">
                                <i class="fas fa-user-md me-2 text-secondary"></i> {{ $appointment->doctor->user->name }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-0 text-muted small">Date & Time:</p>
                            <p class="fw-bold mb-0 text-info">
                                <i class="fas fa-clock me-2"></i>
                                {{ \Carbon\Carbon::parse($appointment->start_at)->format('Y-m-d H:i') }}
                            </p>
                        </div>

                    </div>

                    <div class="card-footer bg-light border-0 text-end">
                        <a href="#" class="btn btn-sm btn-outline-danger rounded-pill">
                            <i class="fas fa-times-circle me-1"></i> Cancel
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-warning rounded-pill">
                            <i class="fas fa-info-circle me-1"></i> Details
                        </a>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
                    <i class="fas fa-bell-slash fa-4x text-info mb-3"></i>
                    <h2 class="h3">There isn't any appointment yet!</h2>
                    <p class="text-muted">No appointments found for this center.</p>
                </div>
            </div>
        @endforelse
    </div>

@endsection
