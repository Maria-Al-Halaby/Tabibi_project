@extends('layouts.admin_app')

@section('title', 'Lab Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-flask-vial"></i>
                Lab Dashboard
            </span>
            <h1 class="page-title">Review pending lab appointments in one focused queue.</h1>
            <p class="page-subtitle">
                The new lab dashboard now follows the same scanning pattern as the rest of Tabibi, with clearer status,
                easier actions, and less visual clutter.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-vials"></i>
                {{ number_format($appointments->count()) }} pending appointments
            </span>
        </div>
    </div>

    @if ($appointments->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-circle-check"></i>
            </div>
            <h2 class="empty-state__title">No lab appointments are waiting right now.</h2>
            <p class="empty-state__copy mb-0">
                Once new lab requests are assigned, they will appear here in the same dashboard flow.
            </p>
        </section>
    @else
        <section class="section-card">
            <div class="toolbar-row">
                <div>
                    <h2 class="section-heading">Pending lab requests</h2>
                    <p class="section-copy">Open any appointment to upload the result file and complete the visit.</p>
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
                                <th>Tests</th>
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
                                    <td>
                                        <div class="list-pills">
                                            @forelse ($appointment->labTests as $test)
                                                <span class="list-pill">
                                                    <i class="fas fa-vial"></i>
                                                    {{ $test->name }}
                                                </span>
                                            @empty
                                                <span class="record-card__meta">No tests selected</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>{{ optional($appointment->start_at)->format('M d, Y - H:i') ?? '---' }}</td>
                                    <td>
                                        <span class="status-pill status-pill--warning">
                                            <i class="fas fa-hourglass-half"></i>
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('lab.appointments.complete.form', $appointment->id) }}"
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
