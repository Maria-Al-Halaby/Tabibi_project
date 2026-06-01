@extends('layouts.admin_app')

@section('title', __('Doctor Dashboard'))

@section('content')
    @php
        $pendingCount = $appointments->where('status', 'pending')->count();
        $completedCount = $appointments->where('status', 'completed')->count();
        $canceledCount = $appointments->where('status', 'canceled')->count();
        $nextAppointment = $appointments->where('status', 'pending')->sortBy('start_at')->first();
        $statusClass = fn($status) => match ($status) {
            'completed' => 'status-pill--success',
            'canceled' => 'status-pill--danger',
            default => 'status-pill--warning',
        };
        $statusLabel = fn($status) => match ($status) {
            'completed' => __('Completed'),
            'canceled' => __('Canceled'),
            default => __('Pending'),
        };
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-stethoscope"></i>
                {{ __('Doctor Dashboard') }}</span>
            <h1 class="page-title">{{ __('Manage your clinical appointments from one queue.') }}</h1>
            <p class="page-subtitle">
                {{ __('Filter visits by day or center, cancel when needed, and complete appointments with notes,
                prescriptions, lab requests, and radiology requests.') }}</p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-user-doctor"></i>
                {{ __('Dr. :name', ['name' => $doctorName ?: __('Doctor')]) }}
            </span>
            <span class="helper-badge">
                <i class="fas fa-calendar-check"></i>
                {{ __(':count appointments', ['count' => number_format($appointments->count())]) }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-hourglass-half"></i>
                {{ __(':count pending', ['count' => number_format($pendingCount)]) }}
            </span>
        </div>
    </div>

    <section class="section-card mb-4">
        <form method="GET" action="{{ route('doctor.dashboard') }}" class="row g-3 align-items-end">
            <div class="col-md-4 col-xl-3">
                <label for="date_filter" class="field-label">{{ __('Date filter') }}</label>
                <select name="date_filter" id="date_filter" class="form-select">
                    <option value="today" @selected($filters['date_filter'] === 'today')>{{ __('Today') }}</option>
                    <option value="tomorrow" @selected($filters['date_filter'] === 'tomorrow')>{{ __('Tomorrow') }}</option>
                    <option value="this_week" @selected($filters['date_filter'] === 'this_week')>{{ __('This week') }}</option>
                    <option value="specific_day" @selected($filters['date_filter'] === 'specific_day')>{{ __('Specific day') }}</option>
                </select>
            </div>

            <div class="col-md-4 col-xl-3">
                <label for="specific_date" class="field-label">{{ __('Specific day') }}</label>
                <input type="date" name="specific_date" id="specific_date" class="form-control"
                    value="{{ $filters['specific_date'] }}">
            </div>

            <div class="col-md-4 col-xl-3">
                <label for="center_id" class="field-label">{{ __('Center') }}</label>
                <select name="center_id" id="center_id" class="form-select">
                    <option value="">{{ __('All centers') }}</option>
                    @foreach ($centers as $center)
                        <option value="{{ $center->id }}" @selected((int) $filters['center_id'] === (int) $center->id)>
                            {{ $center->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 col-xl-3">
                <div class="toolbar-actions justify-content-xl-end">
                    <button type="submit" class="btn btn-tabibi">
                        <i class="fas fa-filter me-2"></i>{{ __('Apply filters') }}</button>
                    <a href="{{ route('doctor.dashboard') }}" class="ghost-button">
                        <i class="fas fa-rotate-left"></i>
                        {{ __('Reset') }}</a>
                </div>
            </div>
        </form>
    </section>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="mini-metric h-100">
                <div class="mini-metric__label">{{ __('Filtered appointments') }}</div>
                <p class="mini-metric__value">{{ number_format($appointments->count()) }}</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="mini-metric h-100">
                <div class="mini-metric__label">{{ __('Pending') }}</div>
                <p class="mini-metric__value">{{ number_format($pendingCount) }}</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="mini-metric h-100">
                <div class="mini-metric__label">{{ __('Completed') }}</div>
                <p class="mini-metric__value">{{ number_format($completedCount) }}</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="mini-metric h-100">
                <div class="mini-metric__label">{{ __('Canceled') }}</div>
                <p class="mini-metric__value">{{ number_format($canceledCount) }}</p>
            </div>
        </div>
    </div>

    @if ($nextAppointment)
        <section class="section-card mb-4">
            <div class="mini-metric">
                <div class="mini-metric__label">{{ __('Next pending patient') }}</div>
                <p class="mini-metric__value">{{ $nextAppointment->patient_display_name }}</p>
                <p class="section-copy mt-2 mb-0">
                    {{ __(':time at :center.', [
                        'time' => optional($nextAppointment->start_at)->translatedFormat('M d, Y - H:i') ?? __('No visit time selected yet'),
                        'center' => $nextAppointment->clinic_center?->name ?? __('Unknown center'),
                    ]) }}
                </p>
            </div>
        </section>
    @endif

    @if ($appointments->isEmpty())<section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h2 class="empty-state__title">{{ __('No appointments match these filters.') }}</h2>
            <p class="empty-state__copy mb-0">{{ __('Try a different day or center to review more visits.') }}</p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($appointments as $appointment)
                @php
                    $patientName = $appointment->patient_display_name;
                    $attachedRecords = \App\Support\AppointmentMedicalRecordPresenter::forAppointment($appointment);
                    $patientInitials = collect(preg_split('/\s+/', $patientName ?: 'Patient'))
                        ->filter()
                        ->take(2)
                        ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                        ->implode('');
                    $scheduledToday = optional($appointment->start_at)?->isToday();
                @endphp

                <div class="col-12 col-xl-6">
                    <section class="record-card record-card--interactive">
                        <div class="record-card__header">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar-fallback">{{ $patientInitials !== '' ? $patientInitials : 'PT' }}</span>
                                <div>
                                    <h2 class="record-card__title mb-1">{{ $patientName }}</h2>
                                    <p class="record-card__copy">{{ __('Clinical appointment #:id', ['id' => $appointment->id]) }}</p>
                                </div>
                            </div>

                            <div class="d-flex flex-column align-items-end gap-2">
                                <span class="status-pill {{ $statusClass($appointment->status) }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $statusLabel($appointment->status) }}
                                </span>

                                @if ($scheduledToday)
                                    <span class="status-pill status-pill--info">
                                        <i class="fas fa-calendar-day"></i>
                                        {{ __('Today') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">{{ __('Center') }}</div>
                                    <p class="mini-metric__value">{{ $appointment->clinic_center?->name ?? '---' }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">{{ __('Visit time') }}</div>
                                    <p class="mini-metric__value">{{ optional($appointment->start_at)->translatedFormat('M d, Y - H:i') ?? '---' }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">{{ __('Price') }}</div>
                                    <p class="mini-metric__value">
                                        {{ $appointment->price !== null ? number_format((float) $appointment->price, 2) : '---' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if (!empty($appointment->note))<div class="mini-metric mb-4">
                                <div class="mini-metric__label">{{ __('Patient note') }}</div>
                                <p class="record-card__copy mb-0">{{ $appointment->note }}</p>
                            </div>
                        @endif

                        @if ($attachedRecords->isNotEmpty())<div class="mini-metric mb-4">
                                <div class="mini-metric__label">{{ __('Attached medical files') }}</div>
                                <div class="list-pills mt-3">
                                    @foreach ($attachedRecords as $record)
                                        <a href="{{ $record['file_url'] }}" target="_blank" class="list-pill">
                                            <i class="fas fa-paperclip"></i>
                                            {{ $record['title'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($appointment->status === 'completed')
                            <div class="mini-metric mb-4">
                                <div class="mini-metric__label">{{ __('Doctor note') }}</div>
                                <p class="record-card__copy mb-0">{{ $appointment->doctor_note ?: '---' }}</p>
                            </div>
                        @endif

                        <div class="toolbar-actions">
                            @if ($appointment->status === 'pending')
                                <a href="{{ route('doctor.appointments.complete.form', $appointment->id) }}"
                                    class="outline-button">
                                    <i class="fas fa-file-prescription"></i>
                                    {{ __('Complete visit') }}</a>

                                <form action="{{ route('doctor.appointments.cancel', $appointment->id) }}" method="POST"
                                    onsubmit="return confirm(@js(__('Cancel appointment #:id?', ['id' => $appointment->id])))">
                                    @csrf
                                    <button type="submit" class="ghost-button">
                                        <i class="fas fa-ban"></i>
                                        {{ __('Cancel') }}</button>
                                </form>
                            @else
                                <span class="record-card__meta">{{ __('No pending action for this appointment.') }}</span>
                            @endif
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
