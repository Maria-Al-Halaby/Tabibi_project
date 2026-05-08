@extends('layouts.admin_app')

@section('title', 'Prescription Details')

@section('content')
    @php
        $statusClass = match ($prescription->pharmacy_status) {
            'ready' => 'status-pill--info',
            'dispensed' => 'status-pill--success',
            default => 'status-pill--warning',
        };
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-prescription-bottle-medical"></i>
                Prescription Details
            </span>
            <h1 class="page-title">Review medicines and update the pharmacy status clearly.</h1>
            <p class="page-subtitle">
                This screen now matches the rest of the dashboard flow, keeping the prescription summary and status action
                side by side.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hashtag"></i>
                Prescription #{{ $prescription->id }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-capsules"></i>
                {{ number_format($prescription->items->count()) }} medicines
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <section class="section-card h-100">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h2 class="section-heading mb-1">Prescription summary</h2>
                        <p class="section-copy">Key patient and doctor details for this prescription.</p>
                    </div>

                    <span class="status-pill {{ $statusClass }}">
                        <i class="fas fa-capsules"></i>
                        {{ ucfirst($prescription->pharmacy_status) }}
                    </span>
                </div>

                <div class="d-grid gap-3">
                    <div class="mini-metric">
                        <div class="mini-metric__label">Patient</div>
                        <p class="mini-metric__value">{{ $prescription->appointment?->patient_display_name }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">Doctor</div>
                        <p class="mini-metric__value">{{ trim(($prescription->appointment?->doctor?->user?->name ?? '') . ' ' . ($prescription->appointment?->doctor?->user?->last_name ?? '')) }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">Date</div>
                        <p class="mini-metric__value">{{ optional($prescription->appointment?->start_at)->format('M d, Y') ?? '---' }}</p>
                    </div>

                    <div class="mini-metric">
                        <div class="mini-metric__label">General note</div>
                        <p class="mini-metric__value">{{ $prescription->general_note ?: '---' }}</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-8">
            <section class="section-card h-100">
                <div class="toolbar-row">
                    <div>
                        <h2 class="section-heading">Medicines</h2>
                        <p class="section-copy">Review the prescription items before changing its pharmacy status.</p>
                    </div>

                    <div class="toolbar-actions">
                        <a href="{{ route('pharmacy.dashboard', ['status' => $prescription->pharmacy_status]) }}"
                            class="ghost-button">
                            <i class="fas fa-arrow-left"></i>
                            Back to queue
                        </a>
                    </div>
                </div>

                <div class="table-shell mb-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Dose</th>
                                    <th>Frequency</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prescription->items as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $item->medicine_name }}</td>
                                        <td>{{ $item->dose }}</td>
                                        <td>{{ $item->frequency }}</td>
                                        <td>{{ $item->start_date }}</td>
                                        <td>{{ $item->end_date }}</td>
                                        <td>{{ $item->instructions ?: '---' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <span class="record-card__meta">No medicines were added to this prescription.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <section class="section-card form-panel" style="padding: 1.25rem;">
                    <h3 class="section-heading">Update pharmacy status</h3>
                    <p class="section-copy mb-3">Move the prescription to the next step in the pharmacy workflow.</p>

                    <form action="{{ route('pharmacy.prescriptions.updateStatus', $prescription->id) }}" method="POST"
                        class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-8">
                            <label for="pharmacy_status" class="field-label">Status</label>
                            <select name="pharmacy_status" id="pharmacy_status" class="form-select" required>
                                <option value="pending" @selected($prescription->pharmacy_status == 'pending')>Pending</option>
                                <option value="ready" @selected($prescription->pharmacy_status == 'ready')>Ready</option>
                                <option value="dispensed" @selected($prescription->pharmacy_status == 'dispensed')>Dispensed</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex justify-content-md-end">
                            <button type="submit" class="btn btn-tabibi w-100">
                                <i class="fas fa-floppy-disk me-2"></i>Update
                            </button>
                        </div>
                    </form>
                </section>
            </section>
        </div>
    </div>
@endsection
