@extends('layouts.admin_app')

@section('title', 'Complete Lab Appointment')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-file-medical"></i>
                Complete Lab Appointment
            </span>
            <h1 class="page-title">Upload the result file and finish this lab request clearly.</h1>
            <p class="page-subtitle">
                Appointment details and selected tests stay visible while you submit the final result.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hashtag"></i>
                Appointment #{{ $appointment->id }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <h2 class="section-heading">Appointment summary</h2>
                <p class="section-copy mb-4">Review the request details before uploading the result file.</p>

                <div class="d-grid gap-3">
                    <div class="mini-metric">
                        <div class="mini-metric__label">Patient</div>
                        <p class="mini-metric__value">{{ $appointment->patient_display_name }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">Center</div>
                        <p class="mini-metric__value">{{ $appointment->clinic_center?->name ?? '---' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">Visit time</div>
                        <p class="mini-metric__value">{{ optional($appointment->start_at)->format('M d, Y - H:i') ?? '---' }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="mini-metric">
                        <div class="mini-metric__label">Requested tests</div>
                        <div class="list-pills mt-3">
                            @forelse ($appointment->labTests as $test)
                                <span class="list-pill">
                                    <i class="fas fa-vial"></i>
                                    {{ $test->name }}
                                </span>
                            @empty
                                <span class="record-card__meta">No tests selected</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-8">
            <section class="section-card form-panel h-100">
                <div class="toolbar-row">
                    <div>
                        <h2 class="section-heading">Result upload</h2>
                        <p class="section-copy">Attach the lab result file and leave an optional note for context.</p>
                    </div>

                    <div class="toolbar-actions">
                        <a href="{{ route('lab.dashboard') }}" class="ghost-button">
                            <i class="fas fa-arrow-left"></i>
                            Back to lab dashboard
                        </a>
                    </div>
                </div>

                <form action="{{ route('lab.appointments.complete') }}" method="POST" enctype="multipart/form-data"
                    class="row g-3">
                    @csrf
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    <div class="col-12">
                        <label for="result_file" class="field-label">Result file</label>
                        <div class="file-drop">
                            <input type="file" name="result_file" id="result_file" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf" required>
                            <p class="field-note">Accepted formats: JPG, PNG, or PDF up to 6 MB.</p>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="field-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" placeholder="Add optional notes for this result">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-tabibi">
                            <i class="fas fa-circle-check me-2"></i>Complete appointment
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
