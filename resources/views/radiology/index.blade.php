@extends('layouts.admin_app')

@section('title', 'Radiology Dashboard')

@section('content')
    @php
        $todayCount = $appointments->filter(fn($appointment) => optional($appointment->start_at)?->isToday())->count();
        $centerCount = $appointments->pluck('clinic_center_id')->filter()->unique()->count();
        $imageTypeCount = $appointments
            ->map(fn($appointment) => $appointment->radiologyAppointment?->type?->id)
            ->filter()
            ->unique()
            ->count();
        $nextAppointment = $appointments->sortBy('start_at')->first();
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-x-ray"></i>
                Radiology Dashboard
            </span>
            <h1 class="page-title">Work through imaging appointments with a cleaner queue.</h1>
            <p class="page-subtitle">
                Keep the radiology workflow focused: scan the pending queue, confirm the requested image type, and jump
                straight into the upload form.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-images"></i>
                {{ number_format($appointments->count()) }} pending appointments
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-calendar-day"></i>
                {{ number_format($todayCount) }} scheduled today
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <section class="section-card h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h2 class="section-heading mb-1">Queue overview</h2>
                        <p class="section-copy">A faster summary of the pending imaging workload.</p>
                    </div>

                    <span class="helper-badge">
                        <i class="fas fa-images"></i>
                        Imaging-first workflow
                    </span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="mini-metric h-100">
                            <div class="mini-metric__label">Pending cases</div>
                            <p class="mini-metric__value">{{ number_format($appointments->count()) }}</p>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="mini-metric h-100">
                            <div class="mini-metric__label">Scheduled today</div>
                            <p class="mini-metric__value">{{ number_format($todayCount) }}</p>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="mini-metric h-100">
                            <div class="mini-metric__label">Active centers</div>
                            <p class="mini-metric__value">{{ number_format($centerCount) }}</p>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="mini-metric h-100">
                            <div class="mini-metric__label">Image types</div>
                            <p class="mini-metric__value">{{ number_format($imageTypeCount) }}</p>
                        </div>
                    </div>
                </div>

                @if ($nextAppointment)
                    @php
                        $nextPatientName = trim(($nextAppointment->patient?->user?->name ?? '') . ' ' . ($nextAppointment->patient?->user?->last_name ?? ''));
                    @endphp
                    <div class="mini-metric">
                        <div class="mini-metric__label">Next patient to review</div>
                        <p class="mini-metric__value">{{ $nextPatientName !== '' ? $nextPatientName : 'Patient #' . $nextAppointment->patient_id }}</p>
                        <p class="section-copy mt-2 mb-0">
                            {{ optional($nextAppointment->start_at)->format('M d, Y - H:i') ?? 'No visit time selected yet' }}
                            at {{ $nextAppointment->clinic_center?->name ?? 'Unknown center' }}.
                        </p>
                    </div>
                @endif
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <h2 class="section-heading">Completion flow</h2>
                <p class="section-copy mb-4">Use the same pattern on each case before you finalize the appointment.</p>

                <div class="insight-list">
                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-x-ray"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Confirm the image type</h3>
                            <p class="insight-item__copy">Each queue card shows the requested image type before you open the upload form.</p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-file-arrow-up"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Upload the final file once</h3>
                            <p class="insight-item__copy">The completion screen stores the result file and closes the visit in one flow.</p>
                        </div>
                    </div>

                    <div class="insight-item">
                        <span class="insight-item__icon">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                        <div>
                            <h3 class="insight-item__title">Prioritize today’s queue</h3>
                            <p class="insight-item__copy">Today’s appointments are highlighted so the team can finish the near-term workload first.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @if ($appointments->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-circle-check"></i>
            </div>
            <h2 class="empty-state__title">No radiology appointments are waiting right now.</h2>
            <p class="empty-state__copy mb-0">
                New imaging requests will appear here as soon as they are assigned.
            </p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($appointments as $appointment)
                @php
                    $patientName = trim(($appointment->patient?->user?->name ?? '') . ' ' . ($appointment->patient?->user?->last_name ?? ''));
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
                                        {{ $patientName !== '' ? $patientName : 'Patient #' . $appointment->patient_id }}
                                    </h2>
                                    <p class="record-card__copy">Radiology appointment #{{ $appointment->id }}</p>
                                </div>
                            </div>

                            <div class="d-flex flex-column align-items-end gap-2">
                                <span class="status-pill status-pill--warning">
                                    <i class="fas fa-hourglass-half"></i>
                                    Pending
                                </span>

                                @if ($scheduledToday)
                                    <span class="status-pill status-pill--info">
                                        <i class="fas fa-calendar-day"></i>
                                        Today
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Center</div>
                                    <p class="mini-metric__value">{{ $appointment->clinic_center?->name ?? '---' }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Visit time</div>
                                    <p class="mini-metric__value">{{ optional($appointment->start_at)->format('M d, Y - H:i') ?? '---' }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Booked price</div>
                                    <p class="mini-metric__value">
                                        {{ $appointment->price !== null ? number_format((float) $appointment->price, 2) : '---' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mini-metric mb-4">
                            <div class="mini-metric__label">Requested image type</div>
                            <div class="list-pills mt-3">
                                <span class="list-pill">
                                    <i class="fas fa-image"></i>
                                    {{ $appointment->radiologyAppointment?->type?->name ?? '---' }}
                                </span>
                            </div>
                        </div>

                        @if (!empty($appointment->note))
                            <div class="mini-metric mb-4">
                                <div class="mini-metric__label">Patient note</div>
                                <p class="record-card__copy mb-0">{{ $appointment->note }}</p>
                            </div>
                        @endif

                        <div class="toolbar-actions">
                            <a href="{{ route('radiology.appointments.complete.form', $appointment->id) }}"
                                class="outline-button">
                                <i class="fas fa-upload"></i>
                                Open completion form
                            </a>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
