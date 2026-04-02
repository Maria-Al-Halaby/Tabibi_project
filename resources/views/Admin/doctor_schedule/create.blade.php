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
        $selectedDays = (array) old('day_of_week', []);
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-calendar-plus"></i>
                Doctor Schedule
            </span>
            <h1 class="page-title">Add a working schedule for {{ $doctor->user->name }}.</h1>
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
                    <p class="mini-metric__value">{{ auth()->user()->clinic_center?->name ?? auth()->user()->name }}</p>
                </div>
            </div>

            <div class="col-lg-8">
                <form action="{{ route('Admin.DoctorSchedule.store', $doctor->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="field-label">Working days</label>
                        <div class="schedule-day-grid">
                            @foreach ($days as $key => $value)
                                <input type="checkbox" class="btn-check" name="day_of_week[]" id="day-{{ $key }}"
                                    value="{{ $key }}" @checked(in_array($key, $selectedDays))>
                                <label class="schedule-day-option" for="day-{{ $key }}">{{ $value }}</label>
                            @endforeach
                        </div>
                        @error('day_of_week')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="start_time" class="field-label">Start time</label>
                            <input type="time" name="start_time" id="start_time"
                                value="{{ old('start_time') }}"
                                class="form-control @error('start_time') is-invalid @enderror">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_time" class="field-label">End time</label>
                            <input type="time" name="end_time" id="end_time"
                                value="{{ old('end_time') }}"
                                class="form-control @error('end_time') is-invalid @enderror">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="priceInput" class="field-label">Appointment price</label>
                            <input type="number" id="priceInput" name="price"
                                placeholder="Enter appointment price" value="{{ old('price') }}"
                                class="form-control @error('price') is-invalid @enderror">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
