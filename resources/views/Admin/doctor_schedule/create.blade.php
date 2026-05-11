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
    foreach (($currentSchedules ?? collect()) as $schedule) {
        $existingSchedules[$schedule->day_of_week] = $schedule;
    }

    $oldSchedules = [];
    foreach (old('schedules', []) as $schedule) {
        if (is_array($schedule) && isset($schedule['day_of_week'])) {
            $oldSchedules[(int) $schedule['day_of_week']] = $schedule;
        }
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

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="field-label">Appointment Price</label>
                        <input type="number"
                               name="price"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $currentPrice ?? '') }}"
                               placeholder="Enter appointment price">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="field-label">Appointment Duration (minutes)</label>
                        <input type="number"
                               name="appointment_duration_minutes"
                               min="5"
                               max="240"
                               step="5"
                               class="form-control @error('appointment_duration_minutes') is-invalid @enderror"
                               value="{{ old('appointment_duration_minutes', $currentAppointmentDuration ?? 30) }}"
                               placeholder="30">
                        @error('appointment_duration_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @error('schedules')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="schedule-days">
                    @foreach($days as $key => $day)
                        @php
                            $schedule = $existingSchedules[$key] ?? null;
                            $oldSchedule = $oldSchedules[$key] ?? null;
                            $isSelected = $oldSchedule || $schedule;

                            $startVal = $oldSchedule['start_time']
                                ?? ($schedule?->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '');
                            $endVal = $oldSchedule['end_time']
                                ?? ($schedule?->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '');
                        @endphp

                        <div class="schedule-day-card {{ $isSelected ? 'is-open' : '' }}" data-schedule-day>
                            <label class="schedule-day-toggle">
                                <input type="checkbox"
                                       class="schedule-day-checkbox"
                                       {{ $isSelected ? 'checked' : '' }}>
                                <span>{{ $day }}</span>
                            </label>

                            <div class="schedule-day-times">
                                <input type="hidden"
                                       name="schedules[{{ $key }}][day_of_week]"
                                       value="{{ $key }}"
                                       {{ $isSelected ? '' : 'disabled' }}>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="field-label">Start Time</label>
                                        <input type="time"
                                               name="schedules[{{ $key }}][start_time]"
                                               class="form-control @error('schedules.'.$key.'.start_time') is-invalid @enderror"
                                               value="{{ $startVal }}"
                                               {{ $isSelected ? '' : 'disabled' }}>
                                        @error('schedules.'.$key.'.start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="field-label">End Time</label>
                                        <input type="time"
                                               name="schedules[{{ $key }}][end_time]"
                                               class="form-control @error('schedules.'.$key.'.end_time') is-invalid @enderror"
                                               value="{{ $endVal }}"
                                               {{ $isSelected ? '' : 'disabled' }}>
                                        @error('schedules.'.$key.'.end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

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
    .schedule-days {
        display: grid;
        gap: 0.9rem;
    }

    .schedule-day-card {
        border: 1px solid rgba(148, 163, 184, 0.24);
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.78);
        padding: 1rem;
        transition: 0.2s ease;
    }

    .schedule-day-card.is-open {
        border-color: rgba(15, 118, 110, 0.36);
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.08);
    }

    .schedule-day-toggle {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        margin: 0;
        cursor: pointer;
        font-weight: 800;
        color: #0f172a;
    }

    .schedule-day-toggle input {
        width: 1.1rem;
        height: 1.1rem;
        accent-color: var(--tabibi-primary-color);
    }

    .schedule-day-times {
        display: none;
        margin-top: 1rem;
    }

    .schedule-day-card.is-open .schedule-day-times {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('[data-schedule-day]').forEach((card) => {
        const checkbox = card.querySelector('.schedule-day-checkbox');
        const fields = card.querySelectorAll('.schedule-day-times input');

        const sync = () => {
            card.classList.toggle('is-open', checkbox.checked);
            fields.forEach((field) => {
                field.disabled = !checkbox.checked;
            });
        };

        checkbox.addEventListener('change', sync);
        sync();
    });
</script>
@endpush
