@extends('layouts.admin_app')

@section('title', __('Appointments'))

@section('content')
    @php
        $appointmentCount = $appointments->count();
        $isSecretaryDashboard = ($dashboardMode ?? 'admin') === 'secretary';
        $dashboardTitle = $isSecretaryDashboard ? __('Secretary Appointment Desk') : __('Appointments');
        $dashboardLead = $isSecretaryDashboard
            ? __('Manage the front-desk queue by specialty, then cancel visits when scheduling changes need quick action.')
            : __('Upcoming appointments are presented as a clear action queue so your team can review schedules and resolve issues faster.');
        $dashboardBadge = $isSecretaryDashboard ? __('Appointment desk') : __('Appointments');
        $dashboardHomeRoute = $isSecretaryDashboard ? route('secretary.dashboard') : route('Admin.index');
        $filterRoute = $isSecretaryDashboard ? route('secretary.dashboard') : route('Admin.Appointment.index');
        $cancelRouteName = $isSecretaryDashboard ? 'secretary.appointments.cancel' : 'Admin.Appointment.cancel';
        $selectedWalkInSpecializationId = old('specialization_id');
        $selectedWalkInLabTests = collect(old('lab_tests', []))->map(fn ($item) => (string) $item)->all();
        $selectedWalkInImageTypeId = old('type_of_medical_image_id');
        $availabilityLabels = [
            'loadingAvailableTimes' => __('Loading available times...'),
            'selectDayFirst' => __('Select a day first'),
            'checkingOpenTimeSlots' => __('Checking open time slots...'),
            'chooseDayToViewOpenTimeSlots' => __('Choose a day to view open time slots.'),
            'selectAvailableTime' => __('Select available time'),
            'noOpenTimeSlotsFound' => __('No open time slots found'),
            'onlyFreeTimeSlotsShown' => __('Only free time slots are shown here.'),
            'noFreeTimeSlotsAvailable' => __('No free time slots are available for the selected day.'),
            'unableToLoadAvailableTimes' => __('Unable to load available times'),
            'couldNotLoadAvailableTimes' => __('Could not load available times. Please try again.'),
            'loadingAvailableDays' => __('Loading available days...'),
            'selectDoctorFirst' => __('Select doctor first'),
            'checkingDoctorSchedule' => __('Checking the doctor schedule...'),
            'chooseDoctorToViewAvailableDays' => __('Choose a doctor to view available days.'),
            'selectAvailableDay' => __('Select available day'),
            'noAvailableDaysFound' => __('No available days found'),
            'chooseAvailableDay' => __('Choose one of the days available in the doctor schedule.'),
            'doctorHasNoAvailableDays' => __('This doctor does not have available days right now.'),
            'unableToLoadAvailableDays' => __('Unable to load available days'),
            'couldNotLoadAvailableDays' => __('Could not load available days. Please try again.'),
            'selectDoctor' => __('Select doctor'),
            'selectSpecialtyFirst' => __('Select specialty first'),
        ];
    @endphp

    @if ($isSecretaryDashboard)
        @push('styles')
            <style>
                .service-picker-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                    gap: 0.9rem;
                }

                .service-card {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    gap: 0.65rem;
                    padding: 1rem 1.1rem;
                    border-radius: 20px;
                    border: 1px solid rgba(148, 163, 184, 0.24);
                    background: linear-gradient(180deg, rgba(248, 250, 252, 0.95), rgba(241, 245, 249, 0.92));
                    cursor: pointer;
                    transition: 0.2s ease;
                    min-height: 132px;
                }

                .service-card:hover {
                    transform: translateY(-1px);
                    border-color: rgba(15, 118, 110, 0.28);
                    box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
                }

                .service-card.is-selected {
                    border-color: rgba(15, 118, 110, 0.5);
                    background: linear-gradient(180deg, rgba(240, 253, 250, 0.96), rgba(204, 251, 241, 0.72));
                    box-shadow: 0 18px 36px rgba(15, 118, 110, 0.12);
                }

                .service-card__top {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 0.8rem;
                }

                .service-card__name {
                    font-weight: 800;
                    color: #0f172a;
                    line-height: 1.35;
                }

                .service-card__price {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0.35rem 0.65rem;
                    border-radius: 999px;
                    background: rgba(37, 99, 235, 0.08);
                    color: #2563eb;
                    font-size: 0.82rem;
                    font-weight: 800;
                    white-space: nowrap;
                }

                .service-card__copy {
                    margin: 0;
                    color: #64748b;
                    font-size: 0.9rem;
                }

                .service-card input[type="checkbox"] {
                    position: absolute;
                    inset: 0;
                    opacity: 0;
                    cursor: pointer;
                }

                .lab-total-card {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.75rem;
                    padding: 0.95rem 1.1rem;
                    border-radius: 18px;
                    background: linear-gradient(135deg, rgba(15, 118, 110, 0.1), rgba(37, 99, 235, 0.08));
                    border: 1px solid rgba(15, 118, 110, 0.16);
                }

                .lab-total-card__label {
                    margin: 0;
                    color: #475569;
                    font-size: 0.85rem;
                    font-weight: 700;
                }

                .lab-total-card__value {
                    margin: 0;
                    color: #0f172a;
                    font-size: 1.25rem;
                    font-weight: 900;
                    letter-spacing: -0.03em;
                }

                .availability-shell {
                    display: grid;
                    gap: 0.85rem;
                }

                .availability-note {
                    margin: 0;
                    color: #64748b;
                    font-size: 0.9rem;
                }
            </style>
        @endpush
    @endif

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-calendar-check"></i>
                {{ $dashboardTitle }}
            </span>
            <h1 class="page-title">{{ __('Track bookings before they become bottlenecks.') }}</h1>
            <p class="page-subtitle">
                {{ $dashboardLead }}
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hospital"></i>
                {{ $center->name }}
            </span>
            <span class="helper-badge">
                <i class="fas fa-list-check"></i>
                {{ __(':count appointments', ['count' => number_format($appointmentCount)]) }}
            </span>
        </div>
    </div>

    @if ($isSecretaryDashboard)
        <section class="section-card form-panel mb-4">
            <div class="toolbar-row mb-4">
                <div>
                    <h2 class="section-heading">{{ __('Schedule a walk-in appointment') }}</h2>
                    <p class="section-copy">{{ __('Create a pending appointment for an on-site patient without a mobile account.') }}</p>
                </div>
                <span class="helper-badge helper-badge--accent">
                    <i class="fas fa-user-plus"></i>
                    {{ __('Walk-in booking') }}</span>
            </div>

            @if ($errors->any())<div class="alert alert-danger mb-4" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('secretary.appointments.store') }}" method="POST" class="row g-3" id="walkInAppointmentForm">
                @csrf

                <div class="col-md-6">
                    <label for="patient_name" class="field-label">{{ __('Patient name') }}</label>
                    <input type="text" id="patient_name" name="patient_name" value="{{ old('patient_name') }}"
                        class="form-control" placeholder="{{ __('Enter patient name') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="patient_phone" class="field-label">{{ __('Patient phone') }}</label>
                    <input type="text" id="patient_phone" name="patient_phone" value="{{ old('patient_phone') }}"
                        class="form-control" placeholder="{{ __('Enter phone number') }}" required>
                </div>

                <div class="col-md-3">
                    <label for="patient_gender" class="field-label">{{ __('Gender') }}</label>
                    <select name="patient_gender" id="patient_gender" class="form-select">
                        <option value="">{{ __('Select gender') }}</option>
                        <option value="male" @selected(old('patient_gender') === 'male')>{{ __('Male') }}</option>
                        <option value="female" @selected(old('patient_gender') === 'female')>{{ __('Female') }}</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="patient_age" class="field-label">{{ __('Age') }}</label>
                    <input type="number" id="patient_age" name="patient_age" value="{{ old('patient_age') }}"
                        class="form-control" min="0" max="120" placeholder="{{ __('Age') }}">
                </div>

                <div class="col-md-3">
                    <label for="walkin_specialization_id" class="field-label">{{ __('Specialty') }}</label>
                    <select name="specialization_id" id="walkin_specialization_id" class="form-select">
                        <option value="">{{ __('Select specialty') }}</option>
                        @foreach ($centerDoctorSpecializations as $specialization)
                            <option value="{{ $specialization->id }}" @selected((string) $selectedWalkInSpecializationId === (string) $specialization->id)>
                                {{ $specialization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="doctor_id" class="field-label">{{ __('Assigned doctor') }}</label>
                    <select name="doctor_id" id="doctor_id" class="form-select" required>
                        <option value="">{{ __('Select specialty first') }}</option>
                        @foreach ($centerDoctors as $doctor)
                            @php
                                $doctorLabel = trim(($doctor->user?->name ?? '') . ' ' . ($doctor->user?->last_name ?? ''));
                                $specialtyLabel = $doctor->specialization?->name ?? __('No specialty');
                            @endphp
                            <option value="{{ $doctor->id }}"
                                data-doctor-type="{{ $doctor->doctor_type }}"
                                data-specialization-id="{{ $doctor->specialization_id }}"
                                @selected((string) old('doctor_id') === (string) $doctor->id)>
                                {{ $doctorLabel }}{{ $specialtyLabel ? ' | ' . $specialtyLabel : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 d-none" id="lab-tests-shell">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                        <div>
                            <label class="field-label d-block mb-1">{{ __('Lab tests') }}</label>
                            <p class="section-copy mb-0">{{ __('Choose the requested tests for this walk-in lab appointment.') }}</p>
                        </div>

                        <div class="lab-total-card">
                            <div>
                                <p class="lab-total-card__label">{{ __('Total price') }}</p>
                                <p class="lab-total-card__value" id="lab-total-price">0.00</p>
                            </div>
                        </div>
                    </div>

                    <div class="service-picker-grid" id="lab-tests-grid">
                        @foreach ($centerLabTests as $labTest)
                            @php
                                $labTestPrice = (float) ($labTest->price ?? 0);
                            @endphp
                            <label class="service-card @if (in_array((string) $labTest->id, $selectedWalkInLabTests, true)) is-selected @endif">
                                <input
                                    type="checkbox"
                                    name="lab_tests[]"
                                    value="{{ $labTest->id }}"
                                    data-price="{{ $labTestPrice }}"
                                    @checked(in_array((string) $labTest->id, $selectedWalkInLabTests, true))
                                >
                                <div class="service-card__top">
                                    <div class="service-card__name">{{ $labTest->name }}</div>
                                    <span class="service-card__price">{{ number_format($labTestPrice, 2) }}</span>
                                </div>
                                <p class="service-card__copy">{{ __('Selectable lab service for walk-in booking.') }}</p>
                            </label>
                        @endforeach
                    </div>
                    <p class="field-note">{{ __('Select one or more test types for lab appointments.') }}</p>
                </div>

                <div class="col-12 d-none" id="radiology-type-shell">
                    <label for="type_of_medical_image_id" class="field-label">{{ __('Image type') }}</label>
                    <select name="type_of_medical_image_id" id="type_of_medical_image_id" class="form-select">
                        <option value="">{{ __('Select image type') }}</option>
                        @foreach ($centerMedicalImageTypes as $imageType)
                            <option value="{{ $imageType->id }}" @selected((string) $selectedWalkInImageTypeId === (string) $imageType->id)>
                                {{ $imageType->name }}{{ isset($imageType->price) ? ' | ' . number_format((float) $imageType->price, 2) : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="appointment_date" class="field-label">{{ __('Available day') }}</label>
                    <div class="availability-shell">
                        <select id="appointment_date" name="appointment_date" class="form-select" required disabled
                            data-selected-value="{{ old('appointment_date') }}">
                            <option value="">{{ __('Select doctor first') }}</option>
                        </select>
                        <p class="availability-note" id="available-days-note">{{ __('Choose a doctor to view available days.') }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="appointment_time" class="field-label">{{ __('Available time') }}</label>
                    <div class="availability-shell">
                        <select id="appointment_time" name="appointment_time" class="form-select" required disabled
                            data-selected-value="{{ old('appointment_time') }}">
                            <option value="">{{ __('Select a day first') }}</option>
                        </select>
                        <p class="availability-note" id="available-times-note">{{ __('Choose a day to view open time slots.') }}</p>
                    </div>
                </div>

                <div class="col-12">
                    <label for="note" class="field-label">{{ __('Appointment note') }}</label>
                    <textarea name="note" id="note" class="form-control" placeholder="{{ __('Add any visit note for the doctor or desk') }}">{{ old('note') }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-tabibi">
                        <i class="fas fa-calendar-plus me-2"></i>{{ __('Schedule appointment') }}</button>
                </div>
            </form>
        </section>
    @endif

    <section class="section-card mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
            <div>
                <h2 class="section-heading mb-1">{{ __('Filter by specialty') }}</h2>
                <p class="section-copy mb-0">{{ __('Focus the queue on one specialty when the front desk needs a narrower view.') }}</p>
            </div>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-filter"></i>
                {{ $dashboardBadge }}
            </span>
        </div>

        <form action="{{ $filterRoute }}" method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-8 col-xl-6">
                <label for="specialization_id" class="field-label">{{ __('Specialty') }}</label>
                <select name="specialization_id" id="specialization_id" class="form-select">
                    <option value="">{{ __('All specialties') }}</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" @selected(($selectedSpecializationId ?? null) == $specialization->id)>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-tabibi">
                    <i class="fas fa-filter me-2"></i>{{ __('Apply filter') }}</button>

                @if (!empty($selectedSpecializationId))
                    <a href="{{ $filterRoute }}" class="ghost-button">
                        <i class="fas fa-rotate-left"></i>
                        {{ __('Clear') }}</a>
                @endif
            </div>
        </form>
    </section>

    @if ($appointments->isEmpty())<section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-calendar-xmark"></i>
            </div>
            <h2 class="empty-state__title">{{ __('No appointments are waiting right now.') }}</h2>
            <p class="empty-state__copy">
                {{ __('This center does not currently have pending appointments in the selected queue.') }}</p>
            <a href="{{ $dashboardHomeRoute }}" class="ghost-button">
                <i class="fas fa-arrow-left"></i>
                {{ $isSecretaryDashboard ? __('Refresh appointment desk') : __('Back to overview') }}
            </a>
        </section>
    @else
        <div class="row g-4">
            @foreach ($appointments as $appointment)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card">
                        <div class="record-card__header">
                            <div>
                                <span class="status-pill status-pill--success">
                                    <i class="fas fa-circle-check"></i>
                                    {{ __('Scheduled') }}</span>
                            </div>

                            <span class="helper-badge">
                                <i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::parse($appointment->start_at)->translatedFormat('M d, H:i') }}
                            </span>
                        </div>

                        <h2 class="record-card__title mb-3">{{ $appointment->patient_display_name }}</h2>

                        <div class="d-grid gap-3 mb-4">
                            <div class="mini-metric">
                                <div class="mini-metric__label">{{ __('Appointment type') }}</div>
                                <p class="mini-metric__value">{{ __(ucfirst($appointment->type)) }}</p>
                            </div>

                            <div class="mini-metric">
                                <div class="mini-metric__label">{{ __('Assigned doctor') }}</div>
                                <p class="mini-metric__value">{{ $appointment->doctor->user->name }}</p>
                            </div>

                            <div class="mini-metric">
                                <div class="mini-metric__label">{{ __('Specialty') }}</div>
                                <p class="mini-metric__value">{{ $appointment->doctor->specialization->name ?? __('General') }}</p>
                            </div>

                            @if ($appointment->patient_display_phone)
                                <div class="mini-metric">
                                    <div class="mini-metric__label">{{ __('Patient phone') }}</div>
                                    <p class="mini-metric__value">{{ $appointment->patient_display_phone }}</p>
                                </div>
                            @endif

                            <div class="mini-metric">
                                <div class="mini-metric__label">{{ __('Visit time') }}</div>
                                <p class="mini-metric__value">{{ \Carbon\Carbon::parse($appointment->start_at)->translatedFormat('l, M d Y - H:i') }}</p>
                            </div>
                            </div>

                            <div class="toolbar-actions">
                                <a href="{{ route($cancelRouteName, ['appointments' => $appointment->id, 'specialization_id' => $selectedSpecializationId]) }}"
                                    class="danger-outline-button"
                                    onclick="return confirm(@js(__('Are you sure you want to cancel this appointment?')))">
                                    <i class="fas fa-ban"></i>
                                    {{ __('Cancel appointment') }}</a>
                            </div>
                        </section>
                    </div>
            @endforeach
        </div>
    @endif
@endsection

@if ($isSecretaryDashboard)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const specializationSelect = document.getElementById('walkin_specialization_id');
                const doctorSelect = document.getElementById('doctor_id');
                const labTestsShell = document.getElementById('lab-tests-shell');
                const radiologyTypeShell = document.getElementById('radiology-type-shell');
                const imageTypeSelect = document.getElementById('type_of_medical_image_id');
                const labTestCheckboxes = Array.from(document.querySelectorAll('input[name="lab_tests[]"]'));
                const labTotalPrice = document.getElementById('lab-total-price');
                const daySelect = document.getElementById('appointment_date');
                const timeSelect = document.getElementById('appointment_time');
                const daysNote = document.getElementById('available-days-note');
                const timesNote = document.getElementById('available-times-note');
                let initialDoctorValue = doctorSelect.value;
                const labels = @json($availabilityLabels);
                const doctorPool = Array.from(doctorSelect.options)
                    .filter((option) => option.value)
                    .map((option) => ({
                        value: option.value,
                        label: option.textContent,
                        doctorType: option.dataset.doctorType ?? '',
                        specializationId: option.dataset.specializationId ?? '',
                    }));

                if (!specializationSelect || !doctorSelect) {
                    return;
                }

                const formatPrice = (value) => Number(value || 0).toFixed(2);
                let selectedDayValue = daySelect?.dataset.selectedValue ?? '';
                let selectedTimeValue = timeSelect?.dataset.selectedValue ?? '';

                const resetSelect = (select, placeholder, disabled = true) => {
                    if (!select) {
                        return;
                    }

                    select.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = placeholder;
                    select.appendChild(option);
                    select.disabled = disabled;
                };

                const syncLabCards = () => {
                    let total = 0;

                    labTestCheckboxes.forEach((checkbox) => {
                        const card = checkbox.closest('.service-card');
                        const isChecked = checkbox.checked;

                        if (card) {
                            card.classList.toggle('is-selected', isChecked);
                        }

                        if (isChecked) {
                            total += Number(checkbox.dataset.price || 0);
                        }
                    });

                    if (labTotalPrice) {
                        labTotalPrice.textContent = formatPrice(total);
                    }
                };

                const resetHiddenFieldValues = (selectedDoctorType) => {
                    if (labTestsShell) {
                        labTestsShell.classList.toggle('d-none', selectedDoctorType !== 'lab');
                    }

                    if (radiologyTypeShell) {
                        radiologyTypeShell.classList.toggle('d-none', selectedDoctorType !== 'radiology');
                    }

                    if (selectedDoctorType !== 'lab') {
                        labTestCheckboxes.forEach((checkbox) => {
                            checkbox.checked = false;
                        });
                    }

                    if (selectedDoctorType !== 'radiology' && imageTypeSelect) {
                        imageTypeSelect.value = '';
                    }

                    syncLabCards();
                };

                const populateTimes = async () => {
                    const doctorId = doctorSelect.value;
                    const date = daySelect?.value;

                    resetSelect(timeSelect, date ? labels.loadingAvailableTimes : labels.selectDayFirst);

                    if (timesNote) {
                        timesNote.textContent = date
                            ? labels.checkingOpenTimeSlots
                            : labels.chooseDayToViewOpenTimeSlots;
                    }

                    if (!doctorId || !date) {
                        return;
                    }

                    try {
                        const response = await fetch(`/secretary/doctors/${doctorId}/available-times/${date}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });

                        const payload = await response.json();
                        const times = payload?.data?.times ?? [];

                        resetSelect(timeSelect, times.length ? labels.selectAvailableTime : labels.noOpenTimeSlotsFound, times.length === 0);

                        times.forEach((time) => {
                            const option = document.createElement('option');
                            option.value = time.time;
                            option.textContent = time.label;
                            timeSelect.appendChild(option);
                        });

                        if (selectedTimeValue && times.some((time) => time.time === selectedTimeValue)) {
                            timeSelect.value = selectedTimeValue;
                            selectedTimeValue = '';
                        }

                        if (timesNote) {
                            timesNote.textContent = times.length
                                ? labels.onlyFreeTimeSlotsShown
                                : labels.noFreeTimeSlotsAvailable;
                        }
                    } catch (error) {
                        resetSelect(timeSelect, labels.unableToLoadAvailableTimes);

                        if (timesNote) {
                            timesNote.textContent = labels.couldNotLoadAvailableTimes;
                        }
                    }
                };

                const populateDays = async () => {
                    const doctorId = doctorSelect.value;

                    resetSelect(daySelect, doctorId ? labels.loadingAvailableDays : labels.selectDoctorFirst);
                    resetSelect(timeSelect, labels.selectDayFirst);

                    if (daysNote) {
                        daysNote.textContent = doctorId
                            ? labels.checkingDoctorSchedule
                            : labels.chooseDoctorToViewAvailableDays;
                    }

                    if (timesNote) {
                        timesNote.textContent = labels.chooseDayToViewOpenTimeSlots;
                    }

                    if (!doctorId) {
                        return;
                    }

                    try {
                        const response = await fetch(`/secretary/doctors/${doctorId}/available-days`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });

                        const payload = await response.json();
                        const days = payload?.data?.days ?? [];

                        resetSelect(daySelect, days.length ? labels.selectAvailableDay : labels.noAvailableDaysFound, days.length === 0);

                        days.forEach((day) => {
                            const option = document.createElement('option');
                            option.value = day.date;
                            option.textContent = day.label;
                            daySelect.appendChild(option);
                        });

                        if (selectedDayValue && days.some((day) => day.date === selectedDayValue)) {
                            daySelect.value = selectedDayValue;
                            selectedDayValue = '';
                        }

                        if (daysNote) {
                            daysNote.textContent = days.length
                                ? labels.chooseAvailableDay
                                : labels.doctorHasNoAvailableDays;
                        }

                        await populateTimes();
                    } catch (error) {
                        resetSelect(daySelect, labels.unableToLoadAvailableDays);

                        if (daysNote) {
                            daysNote.textContent = labels.couldNotLoadAvailableDays;
                        }
                    }
                };

                const syncDoctorOptions = () => {
                    const selectedSpecializationId = specializationSelect.value;
                    const selectedDoctorId = doctorSelect.value || initialDoctorValue;
                    const filteredDoctors = selectedSpecializationId
                        ? doctorPool.filter((doctor) => doctor.specializationId === selectedSpecializationId)
                        : [];

                    resetSelect(doctorSelect, selectedSpecializationId ? labels.selectDoctor : labels.selectSpecialtyFirst, !selectedSpecializationId);

                    filteredDoctors.forEach((doctor) => {
                        const option = document.createElement('option');
                        option.value = doctor.value;
                        option.textContent = doctor.label;
                        option.dataset.doctorType = doctor.doctorType;
                        option.dataset.specializationId = doctor.specializationId;
                        doctorSelect.appendChild(option);
                    });

                    if (selectedDoctorId && filteredDoctors.some((doctor) => doctor.value === selectedDoctorId)) {
                        doctorSelect.value = selectedDoctorId;
                    }

                    initialDoctorValue = '';
                    doctorSelect.disabled = !selectedSpecializationId;
                    syncExtraFields();
                    populateDays();
                };

                const syncExtraFields = () => {
                    const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
                    const selectedDoctorType = selectedOption?.dataset.doctorType ?? '';

                    resetHiddenFieldValues(selectedDoctorType);
                };

                labTestCheckboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', syncLabCards);
                });

                specializationSelect.addEventListener('change', syncDoctorOptions);
                doctorSelect.addEventListener('change', syncExtraFields);
                doctorSelect.addEventListener('change', populateDays);
                daySelect?.addEventListener('change', populateTimes);
                syncDoctorOptions();
                syncLabCards();
            });
        </script>
    @endpush
@endif
