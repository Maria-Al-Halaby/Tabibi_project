@extends('layouts.admin_app')

@section('title', 'Specialization Doctors')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-stethoscope"></i>
                Specialization View
            </span>
            <h1 class="page-title">Doctors inside the selected specialization.</h1>
            <p class="page-subtitle">
                This focused view gives you a cleaner list of doctors for one specialty, with a direct path to each
                schedule.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-user-doctor"></i>
                {{ number_format($doctors->count()) }} doctors
            </span>
        </div>
    </div>

    @if ($doctors->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-circle-info"></i>
            </div>
            <h2 class="empty-state__title">No doctors are assigned to this specialization yet.</h2>
            <p class="empty-state__copy">
                Once doctors are linked to this specialty, they will appear here for schedule management.
            </p>
            <a href="{{ route('Admin.ClinicManagement.index') }}" class="ghost-button">
                <i class="fas fa-arrow-left"></i>
                Back to clinic management
            </a>
        </section>
    @else
        <div class="row g-4">
            @foreach ($doctors as $doctor)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card">
                        <div class="d-flex flex-column align-items-center text-center mb-4">
                            <img src="{{ $doctor->user->profile_image }}" alt="{{ $doctor->user->name }}"
                                class="avatar-circle mb-3">
                            <h2 class="record-card__title mb-1">{{ $doctor->user->name }}</h2>
                            <p class="record-card__copy">{{ $doctor->specialization?->name ?? 'No specialization assigned' }}</p>
                        </div>

                        <div class="mini-metric mb-4">
                            <div class="mini-metric__label">Email</div>
                            <p class="mini-metric__value">{{ $doctor->user->email }}</p>
                        </div>

                        <a href="{{ route('Admin.DoctorSchedule.show', $doctor->id) }}" class="outline-button w-100">
                            <i class="fas fa-clock"></i>
                            Show doctor schedule
                        </a>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
