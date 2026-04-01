@extends('layouts.admin_app')

@section('title', 'Doctor Schedule')

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
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-clock"></i>
                Doctor Schedule
            </span>
            <h1 class="page-title">Working schedule for {{ $doctor->user?->name ?? $doctor->name }}.</h1>
            <p class="page-subtitle">
                Review the doctor’s active work windows, then update or clear the schedule if staffing changes.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-calendar-week"></i>
                {{ number_format($schedules->count()) }} schedule entries
            </span>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">{{ session('message') }}</div>
    @endif

    @if ($schedules->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <h2 class="empty-state__title">No schedule has been created for this doctor yet.</h2>
            <p class="empty-state__copy">
                Add the first schedule entry to make the doctor available for appointments in this center.
            </p>
            <a href="{{ route('Admin.DoctorSchedule.create', $doctor->id) }}" class="btn btn-tabibi">
                <i class="fas fa-plus"></i>
                Add schedule
            </a>
        </section>
    @else
        <section class="section-card">
            <div class="toolbar-row">
                <div>
                    <h2 class="section-heading">Schedule table</h2>
                    <p class="section-copy">Each row represents one saved time block for this doctor.</p>
                </div>

                <div class="toolbar-actions">
                    <a href="{{ route('Admin.DoctorSchedule.edit', $doctor->id) }}" class="outline-button">
                        <i class="fas fa-pen"></i>
                        Update schedule
                    </a>

                    <form action="{{ route('Admin.DoctorSchedule.destroy', $doctor->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete all schedules for this doctor at this center?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="danger-outline-button">
                            <i class="fas fa-trash"></i>
                            Delete all
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-shell">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Days</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule)
                            <tr>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ((array) $schedule->day_of_week as $day)
                                            <span class="status-pill status-pill--success">{{ $days[$day] ?? $day }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                <td>{{ isset($schedule->price) ? number_format($schedule->price) : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
