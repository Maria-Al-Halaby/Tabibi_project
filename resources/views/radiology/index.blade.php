@extends('layouts.admin_app')

@section('title', 'Radiology Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-x-ray"></i>
                Radiology Dashboard
            </span>
            <h1 class="page-title">Work through imaging appointments with a cleaner queue.</h1>
            <p class="page-subtitle">
                Pending radiology visits now match the same dashboard structure used elsewhere, making it easier to scan
                patient details and move straight into completion.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-images"></i>
                {{ number_format($appointments->count()) }} pending appointments
            </span>
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
        <section class="section-card">
            <div class="toolbar-row">
                <div>
                    <h2 class="section-heading">Pending imaging requests</h2>
                    <p class="section-copy">Open an appointment to upload the image file or PDF and complete the visit.</p>
                </div>
            </div>

            <div class="table-shell">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Appointment</th>
                                <th>Patient</th>
                                <th>Center</th>
                                <th>Image type</th>
                                <th>Visit time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment)
                                <tr>
                                    <td class="fw-bold">#{{ $appointment->id }}</td>
                                    <td>{{ trim(($appointment->patient?->user?->name ?? '') . ' ' . ($appointment->patient?->user?->last_name ?? '')) }}</td>
                                    <td>{{ $appointment->clinic_center?->name ?? '---' }}</td>
                                    <td>{{ $appointment->radiologyAppointment?->type?->name ?? '---' }}</td>
                                    <td>{{ optional($appointment->start_at)->format('M d, Y - H:i') ?? '---' }}</td>
                                    <td>
                                        <span class="status-pill status-pill--warning">
                                            <i class="fas fa-hourglass-half"></i>
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('radiology.appointments.complete.form', $appointment->id) }}"
                                            class="outline-button">
                                            <i class="fas fa-upload"></i>
                                            Complete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif
@endsection
