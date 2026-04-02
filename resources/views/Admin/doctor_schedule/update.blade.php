@extends('layouts.admin_app')

@section('title', 'Update Doctor Schedule')

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
        $selectedDays = old('day_of_week', $currentDays ?? []);
        if (!is_array($selectedDays)) {
            $selectedDays = (array) $selectedDays;
        }
        $oldStart = old('start_time', $oldSchedule['start_time'] ?? '');
        $oldEnd = old('end_time', $oldSchedule['end_time'] ?? '');
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-pen-to-square"></i>
                Schedule Update
            </span>
            <h1 class="page-title">Update the working schedule for {{ $doctor->user->name }}.</h1>
            <p class="page-subtitle">
                Refine working days, hours, or price while keeping the structure consistent with the rest of the admin
                area.
            </p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('Admin.DoctorSchedule.update', $doctor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="field-label">Working days</label>
                <div class="schedule-day-grid">
                    @foreach ($days as $key => $value)
                        <input type="checkbox" class="btn-check" name="day_of_week[]" id="day-{{ $key }}"
                            value="{{ $key }}" @checked(in_array((string) $key, array_map('strval', $selectedDays)))>
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
                    <input type="time" name="start_time" id="start_time" value="{{ $oldStart }}"
                        class="form-control @error('start_time') is-invalid @enderror">
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_time" class="field-label">End time</label>
                    <input type="time" name="end_time" id="end_time" value="{{ $oldEnd }}"
                        class="form-control @error('end_time') is-invalid @enderror">
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="priceInput" class="field-label">Appointment price</label>
                    <input type="number" id="priceInput" name="price" placeholder="Enter appointment price"
                        value="{{ old('price', $oldSchedule['price'] ?? '') }}"
                        class="form-control @error('price') is-invalid @enderror">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="fas fa-floppy-disk"></i>
                    Update schedule
                </button>
                <a href="{{ route('Admin.DoctorSchedule.show', $doctor->id) }}" class="ghost-button">
                    <i class="fas fa-arrow-left"></i>
                    Back to schedule
                </a>
            </div>
        </form>
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
