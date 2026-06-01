@extends('layouts.admin_app')

@section('title', __('Complete Radiology Appointment'))

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-file-medical"></i>
                {{ __('Complete Radiology Appointment') }}</span>
            <h1 class="page-title">{{ __('Upload the radiology result and finish the imaging visit clearly.') }}</h1>
            <p class="page-subtitle">
                {{ __('The patient summary stays visible while you attach the result file and finalize the appointment.') }}</p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hashtag"></i>
                {{ __('Appointment #:id', ['id' => $appointment->id]) }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <h2 class="section-heading">{{ __('Appointment summary') }}</h2>
                <p class="section-copy mb-4">{{ __('Review the request details before uploading the final result.') }}</p>

                <div class="d-grid gap-3">
                    <div class="mini-metric">
                        <div class="mini-metric__label">{{ __('Patient') }}</div>
                        <p class="mini-metric__value">{{ $appointment->patient_display_name }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">{{ __('Center') }}</div>
                        <p class="mini-metric__value">{{ $appointment->clinic_center?->name ?? '---' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">{{ __('Visit time') }}</div>
                        <p class="mini-metric__value">{{ optional($appointment->start_at)->translatedFormat('M d, Y - H:i') ?? '---' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">{{ __('Image type') }}</div>
                        <p class="mini-metric__value">{{ $appointment->radiologyAppointment?->type?->name ?? '---' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">{{ __('Attached medical files') }}</div>
                        @if ($attachedMedicalRecords->isEmpty())<p class="record-card__meta mb-0">{{ __('No files were attached to this appointment.') }}</p>
                        @else
                            <div class="list-pills mt-3">
                                @foreach ($attachedMedicalRecords as $record)
                                    <a href="{{ $record['file_url'] }}" target="_blank" class="list-pill">
                                        <i class="fas fa-paperclip"></i>
                                        {{ $record['title'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-8">
            <section class="section-card form-panel h-100">
                <div class="toolbar-row">
                    <div>
                        <h2 class="section-heading">{{ __('Result upload') }}</h2>
                        <p class="section-copy">{{ __('Attach the image or PDF result and leave an optional clinical note.') }}</p>
                    </div>

                    <div class="toolbar-actions">
                        <a href="{{ route('radiology.dashboard') }}" class="ghost-button">
                            <i class="fas fa-arrow-left"></i>
                            {{ __('Back to radiology dashboard') }}</a>
                    </div>
                </div>

                <form action="{{ route('radiology.appointments.complete') }}" method="POST" enctype="multipart/form-data"
                    class="row g-3">
                    @csrf
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    <div class="col-12">
                        <label for="image_file" class="field-label">{{ __('Image or PDF result') }}</label>
                        <div class="file-drop">
                            <input type="file" name="image_file" id="image_file" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf" required>
                            <p class="field-note">{{ __('Accepted formats: JPG, PNG, or PDF up to 6 MB.') }}</p>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="field-label">{{ __('Notes') }}</label>
                        <textarea name="notes" id="notes" class="form-control" placeholder="{{ __('Add optional notes for this result') }}">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-tabibi">
                            <i class="fas fa-circle-check me-2"></i>{{ __('Complete appointment') }}</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
