@extends('layouts.admin_app')

@section('title', 'Complete Doctor Appointment')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-file-prescription"></i>
                Complete Clinical Appointment
            </span>
            <h1 class="page-title">Finish the visit with notes, prescriptions, and follow-up requests.</h1>
            <p class="page-subtitle">
                This dashboard flow mirrors the mobile doctor API: clinical note, prescription items, optional pharmacy
                send, lab tests, and radiology requests.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hashtag"></i>
                Appointment #{{ $appointment->id }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-circle"></i>
                {{ ucfirst($appointment->status) }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <h2 class="section-heading">Appointment summary</h2>
                <p class="section-copy mb-4">Review the visit before submitting the clinical outcome.</p>

                <div class="d-grid gap-3">
                    <div class="mini-metric">
                        <div class="mini-metric__label">Patient</div>
                        <p class="mini-metric__value">{{ $appointment->patient_display_name }}</p>
                        <p class="record-card__meta mb-0">{{ $appointment->patient_display_phone ?? 'No phone' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">Center</div>
                        <p class="mini-metric__value">{{ $appointment->clinic_center?->name ?? '---' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">Visit time</div>
                        <p class="mini-metric__value">{{ optional($appointment->start_at)->format('M d, Y - H:i') ?? '---' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">Patient note</div>
                        <p class="record-card__copy mb-0">{{ $appointment->note ?: '---' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">Attached medical files</div>
                        @if ($attachedMedicalRecords->isEmpty())
                            <p class="record-card__meta mb-0">No files were attached to this appointment.</p>
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
                        <h2 class="section-heading">Clinical completion</h2>
                        <p class="section-copy">Add only the sections needed for this visit.</p>
                    </div>

                    <div class="toolbar-actions">
                        <a href="{{ route('doctor.dashboard') }}" class="ghost-button">
                            <i class="fas fa-arrow-left"></i>
                            Back to dashboard
                        </a>
                    </div>
                </div>

                <form action="{{ route('doctor.appointments.complete') }}" method="POST" class="row g-4" id="completeVisitForm">
                    @csrf
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    <div class="col-12">
                        <label for="note" class="field-label">Doctor clinical note</label>
                        <textarea name="note" id="note" class="form-control" rows="4"
                            placeholder="Write the clinical assessment and visit notes" required>{{ old('note', $appointment->doctor_note) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="mini-metric">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                                <div>
                                    <div class="mini-metric__label">Prescription</div>
                                    <p class="section-copy mb-0">Add medicine items and a general note when needed.</p>
                                </div>
                                <button type="button" class="outline-button" id="addPrescriptionItem">
                                    <i class="fas fa-plus"></i>
                                    Add medicine
                                </button>
                            </div>

                            <div class="mb-3">
                                <label for="prescription_note" class="field-label">General prescription note</label>
                                <textarea name="prescription_note" id="prescription_note" class="form-control"
                                    placeholder="Optional note for the whole prescription">{{ old('prescription_note') }}</textarea>
                            </div>

                            @if ($hasPharmacist)
                                <div class="form-check mb-3">
                                    <input type="checkbox" name="send_to_pharmacy" value="1" id="send_to_pharmacy"
                                        class="form-check-input" @checked(old('send_to_pharmacy'))>
                                    <label for="send_to_pharmacy" class="form-check-label">Send prescription to pharmacy</label>
                                </div>
                            @else
                                <p class="field-note mb-3">This center does not have a pharmacist, so pharmacy sending is unavailable.</p>
                            @endif

                            <div id="prescriptionItems" class="d-grid gap-3"></div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mini-metric">
                            <div class="mini-metric__label">Lab requests</div>
                            <p class="section-copy">Select tests if the patient needs lab follow-up.</p>

                            <div class="mb-3">
                                <label for="lab_request_note" class="field-label">Lab request note</label>
                                <textarea name="lab_request_note" id="lab_request_note" class="form-control"
                                    placeholder="Optional note for requested lab tests">{{ old('lab_request_note') }}</textarea>
                            </div>

                            <div class="row g-2">
                                @foreach ($labTests as $test)
                                    <div class="col-sm-6 col-lg-4">
                                        <label class="list-pill w-100">
                                            <input type="checkbox" name="lab_tests[]" value="{{ $test->id }}"
                                                class="form-check-input me-2" @checked(in_array($test->id, old('lab_tests', [])))>
                                            <i class="fas fa-vial"></i>
                                            {{ $test->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mini-metric">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                                <div>
                                    <div class="mini-metric__label">Radiology requests</div>
                                    <p class="section-copy mb-0">Add image requests if the patient needs imaging follow-up.</p>
                                </div>
                                <button type="button" class="outline-button" id="addRadiologyRequest">
                                    <i class="fas fa-plus"></i>
                                    Add request
                                </button>
                            </div>

                            <div id="radiologyRequests" class="d-grid gap-3"></div>
                        </div>
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

    <template id="prescriptionItemTemplate">
        <div class="row g-3 align-items-end prescription-item">
            <div class="col-md-6">
                <label class="field-label">Medicine name</label>
                <input type="text" data-name="medicine_name" class="form-control" placeholder="Medicine">
            </div>
            <div class="col-md-3">
                <label class="field-label">Dose</label>
                <input type="text" data-name="dose" class="form-control" placeholder="500mg">
            </div>
            <div class="col-md-3">
                <label class="field-label">Frequency</label>
                <input type="text" data-name="frequency" class="form-control" placeholder="Twice daily">
            </div>
            <div class="col-md-3">
                <label class="field-label">Start date</label>
                <input type="date" data-name="start_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="field-label">End date</label>
                <input type="date" data-name="end_date" class="form-control">
            </div>
            <div class="col-md-5">
                <label class="field-label">Instructions</label>
                <input type="text" data-name="instructions" class="form-control" placeholder="After food">
            </div>
            <div class="col-md-1">
                <button type="button" class="ghost-button remove-row" aria-label="Remove medicine">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>

    <template id="radiologyRequestTemplate">
        <div class="row g-3 align-items-end radiology-request">
            <div class="col-md-5">
                <label class="field-label">Image type</label>
                <select data-name="type_of_medical_image_id" class="form-select">
                    <option value="">Select image type</option>
                    @foreach ($medicalImageTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="field-label">Notes</label>
                <input type="text" data-name="notes" class="form-control" placeholder="Optional request note">
            </div>
            <div class="col-md-1">
                <button type="button" class="ghost-button remove-row" aria-label="Remove radiology request">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wireRows = (containerId, templateId, buttonId, prefix) => {
                const container = document.getElementById(containerId);
                const template = document.getElementById(templateId);
                const button = document.getElementById(buttonId);
                let index = 0;

                const addRow = () => {
                    const row = template.content.firstElementChild.cloneNode(true);

                    row.querySelectorAll('[data-name]').forEach((input) => {
                        input.name = `${prefix}[${index}][${input.dataset.name}]`;
                    });

                    row.querySelector('.remove-row').addEventListener('click', () => row.remove());
                    container.appendChild(row);
                    index += 1;
                };

                button.addEventListener('click', addRow);
            };

            wireRows('prescriptionItems', 'prescriptionItemTemplate', 'addPrescriptionItem', 'prescription_items');
            wireRows('radiologyRequests', 'radiologyRequestTemplate', 'addRadiologyRequest', 'radiology_requests');
        });
    </script>
@endpush
