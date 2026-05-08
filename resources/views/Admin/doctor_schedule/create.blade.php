@extends('layouts.admin_app')

@section('title', 'Add Doctor Schedule')

@section('content')

@php
    $days = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    $existingSchedules = [];
    foreach ($doctor->schedules ?? [] as $schedule) {
        $existingSchedules[$schedule->day_of_week] = $schedule;
    }
@endphp

<div class="page-header">
    <div>
        <span class="eyebrow">
            <i class="fas fa-calendar-plus"></i>
            Doctor Schedule
        </span>
        <h1 class="page-title">
            Add a working schedule for {{ $doctor->user->name }}.
        </h1>
        <p class="page-subtitle">
            Configure working days, clinic hours, and appointment price in one clear form.
        </p>
    </div>
    <div class="helper-badges">
        <span class="helper-badge">
            <i class="fas fa-user-doctor"></i>
            {{ $doctor->specialization?->name ?? 'Doctor' }}
        </span>
    </div>
</div>

<section class="section-card form-panel">
    <div class="row g-4">

        <div class="col-lg-4">
            <div class="mini-metric mb-3">
                <div class="mini-metric__label">Doctor</div>
                <p class="mini-metric__value">{{ $doctor->user->name }}</p>
            </div>
            <div class="mini-metric">
                <div class="mini-metric__label">Clinic</div>
                <p class="mini-metric__value">
                    {{ auth()->user()->clinic_center?->name ?? auth()->user()->name }}
                </p>
            </div>
        </div>

        <div class="col-lg-8">
            <form action="{{ route('Admin.DoctorSchedule.store', $doctor->id) }}" method="POST">
                @csrf

                {{-- PRICE --}}
                <div class="mb-4">
                    <label class="field-label">Appointment Price</label>
                    <input type="number"
                           name="price"
                           class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price') }}"
                           placeholder="Enter appointment price">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DAYS --}}
                @foreach($days as $key => $day)
                    @php
                        $schedule = $existingSchedules[$key] ?? null;

                       
                        $startVal = old(
                            'schedules.' . $key . '.start_time',
                            $schedule?->start_time
                                ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i')
                                : ''
                        );
                        $endVal = old(
                            'schedules.' . $key . '.end_time',
                            $schedule?->end_time
                                ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i')
                                : ''
                        );
                    @endphp

                    <div class="border rounded p-3 mb-3">
                        <h5 class="mb-3">{{ $day }}</h5>

                        <input type="hidden"
                               name="schedules[{{ $key }}][day_of_week]"
                               value="{{ $key }}">

                        <div class="row">

                            {{-- START TIME --}}
                            <div class="col-md-6">
                                <label class="field-label">Start Time</label>
                                <input type="time"
                                       name="schedules[{{ $key }}][start_time]"
                                       class="form-control @error('schedules.'.$key.'.start_time') is-invalid @enderror"
                                       value="{{ $startVal }}">
                                @error('schedules.'.$key.'.start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- END TIME --}}
                            <div class="col-md-6">
                                <label class="field-label">End Time</label>
                                <input type="time"
                                       name="schedules[{{ $key }}][end_time]"
                                       class="form-control @error('schedules.'.$key.'.end_time') is-invalid @enderror"
                                       value="{{ $endVal }}">
                                @error('schedules.'.$key.'.end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                @endforeach

                <div class="toolbar-actions mt-4">
                    <button type="submit" class="btn btn-tabibi">
                        <i class="fas fa-save"></i>
                        Save schedule
                    </button>
                    <a href="{{ route('Admin.DoctorSchedule.show', $doctor->id) }}" class="ghost-button">
                        <i class="fas fa-arrow-left"></i>
                        Back to schedule
                    </a>
                </div>

            </form>
        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
.schedule-day-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.schedule-day-option {
    border-radius: 999px;
    padding: 0.8rem 1rem;
    font-weight: 700;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(148, 163, 184, 0.22);
    cursor: pointer;
    transition: 0.25s ease;
}
.btn-check:checked + .schedule-day-option {
    background: rgba(15, 118, 110, 0.12);
    color: var(--tabibi-primary-color);
    border-color: rgba(15, 118, 110, 0.28);
    box-shadow: 0 14px 24px rgba(15, 118, 110, 0.08);
}
</style>
@endpush