@extends('layouts.admin_app')

@section('title', __('Lab Dashboard'))

@section('content')
    @php
        $todayCount = $appointments->filter(fn($appointment) => optional($appointment->start_at)?->isToday())->count();
        $centerCount = $appointments->pluck('clinic_center_id')->filter()->unique()->count();
        $requestedTestsCount = $appointments->sum(fn($appointment) => $appointment->labTests->count());
        $nextAppointment = $appointments->sortBy('start_at')->first();
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-flask-vial"></i>
                {{ __('Lab Dashboard') }}</span>
            <h1 class="page-title">{{ __('Review pending lab appointments in one focused queue.') }}</h1>
            <p class="page-subtitle">
                {{ __('Keep the lab team in one clear workflow: scan the queue, confirm the selected tests, and move straight into
                uploading the final result.') }}</p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-user-doctor"></i>
                {{ __('Dr. :name', ['name' => $doctorName ?: __('Doctor')]) }}
            </span>
            <span class="helper-badge">
                <i class="fas fa-vials"></i>
                {{ __(':count pending appointments', ['count' => number_format($appointments->count())]) }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-calendar-day"></i>
                {{ __(':count scheduled today', ['count' => number_format($todayCount)]) }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <section class="section-card h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h2 class="section-heading mb-1">{{ __('Queue overview') }}</h2>
                        <p class="section-copy">{{ __('A fast operational snapshot for the pending lab workload.') }}</p>
                    </div>

                    <span class="helper-badge">
                        <i class="fas fa-list-check"></i>
                        {{ __('Completion-first workflow') }}</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="mini-metric h-100">
                            <div class="mini-metric__label">{{ __('Pending cases') }}</div>
                            <p class="mini-metric__value">{{ number_format($appointments->count()) }}</p>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="mini-metric h-100">
                            <div class="mini-metric__label">{{ __('Scheduled today') }}</div>
                            <p class="mini-metric__value">{{ number_format($todayCount) }}</p>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="mini-metric h-100">
                            <div class="mini-metric__label">{{ __('Active centers') }}</div>
                            <p class="mini-metric__value">{{ number_format($centerCount) }}</p>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="mini-metric h-100">
                            <div class="mini-metric__label">{{ __('Requested tests') }}</div>
                            <p class="mini-metric__value">{{ number_format($requestedTestsCount) }}</p>
                        </div>
                    </div>
                </div>

                @if ($nextAppointment)
                    <div class="mini-metric">
                        <div class="mini-metric__label">{{ __('Next patient to review') }}</div>
                        <p class="mini-metric__value">{{ $nextAppointment->patient_display_name }}</p>
                        <p class="section-copy mt-2 mb-0">
                            {{ __(':time at :center.', [
                                'time' => optional($nextAppointment->start_at)->translatedFormat('M d, Y - H:i') ?? __('No visit time selected yet'),
                                'center' => $nextAppointment->clinic_center?->name ?? __('Unknown center'),
                            ]) }}
                        </p>
                    </div>
                @endif
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <h2 class="section-heading">{{ __('Completion flow') }}</h2>
                <p class="section-copy mb-4">{{ __('Keep each case readable and consistent before marking it completed.') }}</p>

                <div class="insight-list">
                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-vial"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">{{ __('Confirm the requested tests') }}</h3>
                            <p class="insight-item__copy">{{ __('Use the selected test list on each card before uploading the result file.') }}</p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-file-arrow-up"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">{{ __('Upload once per appointment') }}</h3>
                            <p class="insight-item__copy">{{ __('The completion form stores the final lab file and closes the visit in one step.') }}</p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-hourglass-half"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">{{ __('Start with today’s cases') }}</h3>
                            <p class="insight-item__copy">{{ __('Appointments scheduled for today are highlighted in the queue for faster scanning.') }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @if ($appointments->isEmpty())<section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-circle-check"></i>
            </div>
            <h2 class="empty-state__title">{{ __('No lab appointments are waiting right now.') }}</h2>
            <p class="empty-state__copy mb-0">
                {{ __('Once new lab requests are assigned, they will appear here in the same dashboard flow.') }}</p>
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
                                    <h2 class="record-card__title mb-1">
                                        {{ $patientName }}
                                    </h2>
                                    <p class="record-card__copy">{{ __('Lab appointment #:id', ['id' => $appointment->id]) }}</p>
                                </div>
                            </div>

                            <div class="d-flex flex-column align-items-end gap-2">
                                <span class="status-pill status-pill--warning">
                                    <i class="fas fa-hourglass-half"></i>
                                    {{ __('Pending') }}</span>

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
                                    <div class="mini-metric__label">{{ __('Booked price') }}</div>
                                    <p class="mini-metric__value">
                                        {{ $appointment->price !== null ? number_format((float) $appointment->price, 2) : '---' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mini-metric mb-4">
                            <div class="mini-metric__label">{{ __('Requested tests') }}</div>
                            <div class="list-pills mt-3">
                                @forelse ($appointment->labTests as $test)
                                    <span class="list-pill">
                                        <i class="fas fa-vial"></i>
                                        {{ $test->name }}
                                    </span>
                                @empty
                                    <span class="record-card__meta">{{ __('No tests selected') }}</span>
                                @endforelse
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

                        <div class="toolbar-actions">
                            <a href="{{ route('lab.appointments.complete.form', $appointment->id) }}"
                                class="outline-button">
                                <i class="fas fa-upload"></i>
                                {{ __('Open completion form') }}</a>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
