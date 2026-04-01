@extends('layouts.admin_app')

@section('title', 'Clinic Management')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-hospital-user"></i>
                Clinic Management
            </span>
            <h1 class="page-title">Browse doctors by specialty and act quickly.</h1>
            <p class="page-subtitle">
                This page now combines the specialty filter and doctor directory into a cleaner workflow, so finding the
                right doctor takes fewer steps.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-user-doctor"></i>
                {{ number_format($doctors->count()) }} doctors shown
            </span>
        </div>
    </div>

    <section class="section-card form-panel mb-4">
        <div class="toolbar-row">
            <div>
                <h2 class="section-heading">Filter by specialization</h2>
                <p class="section-copy">Pick a specialty to focus the list on one medical area.</p>
            </div>
        </div>

        <form action="{{ route('Admin.ClinicManagement.create') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-lg-9">
                <label for="specialization-filter" class="field-label">Specialization</label>
                <select name="specialization_id" id="specialization-filter" class="form-select">
                    <option value="">Select specialization to show doctors</option>
                    @foreach ($specializations as $specializaion)
                        <option value="{{ $specializaion->id }}" @selected(request('specialization_id') == $specializaion->id)>
                            {{ $specializaion->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3">
                <button type="submit" class="btn btn-tabibi w-100">
                    <i class="fas fa-sliders"></i>
                    Apply filter
                </button>
            </div>
        </form>
    </section>

    @if ($doctors->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-user-doctor"></i>
            </div>
            <h2 class="empty-state__title">No doctors match the current selection.</h2>
            <p class="empty-state__copy">
                Try a different specialization or remove the filter to see more doctors.
            </p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($doctors as $doctor)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $doctor->user->profile_image }}" alt="{{ $doctor->user->name }}"
                                class="avatar-circle">

                            <div>
                                <h2 class="record-card__title mb-1">{{ $doctor->user->name }}</h2>
                                <p class="record-card__copy mb-0">{{ $doctor->specialization?->name ?? 'No specialization assigned' }}</p>
                            </div>
                        </div>

                        <div class="d-grid gap-3 mb-4">
                            <div class="mini-metric">
                                <div class="mini-metric__label">Email</div>
                                <p class="mini-metric__value">{{ $doctor->user->email }}</p>
                            </div>
                        </div>

                        <div class="toolbar-actions">
                            <a href="{{ route('Admin.DoctorSchedule.show', $doctor->id) }}" class="outline-button">
                                <i class="fas fa-clock"></i>
                                View schedule
                            </a>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
