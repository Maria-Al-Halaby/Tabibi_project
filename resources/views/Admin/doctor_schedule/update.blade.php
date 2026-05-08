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

    $existingSchedules = [];
    foreach ($doctor->schedules ?? [] as $schedule) {
        $existingSchedules[$schedule->day_of_week] = $schedule;
    }

    $center       = auth()->user()->clinic_center;
    $pivotRecord  = \App\Models\ClinicCenterDoctor::where('clinic_center_id', $center->id)
                        ->where('doctor_id', $doctor->id)
                        ->first();
    $currentPrice = $pivotRecord?->price ?? '';
@endphp

<div class="page-header">
    <div>
        <span class="eyebrow">
            <i class="fas fa-pen-to-square"></i>
            Schedule Update
        </span>
        <h1 class="page-title">
            Update the working schedule for {{ $doctor->user->name }}.
        </h1>
        <p class="page-subtitle">
            Refine working days, hours, or price while keeping the structure consistent with the rest of the admin area.
        </p>
    </div>
</div>

<section class="section-card form-panel">

    <form action="{{ route('Admin.DoctorSchedule.update', $doctor->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- PRICE --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="priceInput" class="field-label">Appointment Price</label>
                {{-- السعر  قيمته الحالية --}}
                <input type="number"
                       id="priceInput"
                       name="price"
                       placeholder="Enter appointment price"
                       value="{{ old('price', $currentPrice) }}"
                       class="form-control @error('price') is-invalid @enderror">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- SCHEDULES --}}
        <div class="row g-3">
            @foreach($days as $key => $day)

                @php
                    $schedule = $existingSchedules[$key] ?? null;

                    $startVal = old(
                        "schedules.$key.start_time",
                        $schedule?->start_time
                            ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i')
                            : ''
                    );
                    $endVal = old(
                        "schedules.$key.end_time",
                        $schedule?->end_time
                            ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i')
                            : ''
                    );
                @endphp

                <div class="border p-3 mb-3">

                    <h5>{{ $day }}</h5>

                    <input type="hidden"
                           name="schedules[{{ $key }}][day_of_week]"
                           value="{{ $key }}">

                    <div class="row">

                        {{-- START --}}
                        <div class="col-md-6">
                            <label>Start Time</label>
                            <input type="time"
                                   name="schedules[{{ $key }}][start_time]"
                                   class="form-control @error("schedules.$key.start_time") is-invalid @enderror"
                                   value="{{ $startVal }}">
                            @error("schedules.$key.start_time")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- END --}}
                        <div class="col-md-6">
                            <label>End Time</label>
                            <input type="time"
                                   name="schedules[{{ $key }}][end_time]"
                                   class="form-control @error("schedules.$key.end_time") is-invalid @enderror"
                                   value="{{ $endVal }}">
                            @error("schedules.$key.end_time")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>

            @endforeach
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