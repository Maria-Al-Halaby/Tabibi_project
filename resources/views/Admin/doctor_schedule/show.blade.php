@extends('layouts.admin_app')

@section('title', 'Doctor Schedule')


@section('content')

    <h2 class="mb-4 d-flex justify-content-between align-items-center">
        <span><i class="fas fa-calendar-alt tabibi-text-primary me-2"></i> Doctor Schedule</span>
        @if (isset($doctor->name))
            <span class="badge bg-secondary rounded-pill p-2">{{ $doctor->name }}</span>
        @endif
    </h2>
    <hr>

    @if (Session::has('message'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ Session::get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday' /* تم تعديل Firday إلى Friday */,
            6 => 'Saturday',
        ];
    @endphp

    @if ($schedules->isEmpty())
        <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
            <i class="fas fa-exclamation-circle fa-4x text-warning mb-3"></i>
            <h1 class="h3">There isn't any schedules for this doctor yet.</h1>
            <a href="{{ route('Admin.DoctorSchedule.create', $doctor->id) }}" class="btn btn-tabibi mt-3 shadow">
                <i class="fas fa-plus-circle me-2"></i> Add Schedules for this Doctor
            </a>
        </div>
    @else
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="tabibi-bg-primary text-white">
                        <tr>
                            <th><i class="fas fa-calendar-day me-1"></i> Day</th>
                            <th><i class="fas fa-clock me-1"></i> From</th>
                            <th><i class="fas fa-clock me-1"></i> To</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule)
                            <tr>
                                @foreach ((array) $schedule->day_of_week as $day)
                                    <td>
                                        <span
                                            class="badge bg-light text-dark border p-2 rounded-pill">{{ $days[$day] ?? $day }}</span>
                                    </td>
                                @endforeach
                                {{-- تم حذف السطر القديم --}}
                                <td><span
                                        class="fw-bold text-success">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</span>
                                </td>
                                <td><span
                                        class="fw-bold text-danger">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                </td>
                                <td class="text-end">
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="4" class="bg-light text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('Admin.DoctorSchedule.edit', $doctor->id) }}"
                                        class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                        <i class="fas fa-edit me-1"></i> Update Schedule
                                    </a>

                                    <form action="{{ route('Admin.DoctorSchedule.destroy', $doctor->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete ALL schedules for this doctor at this center?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">
                                            <i class="fas fa-trash-alt me-1"></i> Delete All
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    @endif


@endsection
