{{-- @extends('layouts.admin_app')

@section('title', 'Main page')


@section('content')

    <h1>Doctors Count : {{ $doctorCount }}</h1>
    <h1>Appintments Count : {{ $appointmentsCount }}</h1>
    <h1>patients Count : {{ $patientsCount }}</h1>
    <h1>Clinic Count : {{ $clinicCount }}</h1>
@endsection
 --}}


@extends('layouts.admin_app')

@section('title', 'Admin Dashboard')


@section('content')

    <h2 class="mb-4 tabibi-text-primary">👋 Welcome to Tabibi Admin Panel</h2>
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
@endsection
