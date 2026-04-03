@extends('layouts.admin_app')

@section('title', 'Pharmacy Dashboard')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Pending prescriptions',
            'ready' => 'Ready for pickup',
            'dispensed' => 'Dispensed prescriptions',
        ];
        $statusDescriptions = [
            'pending' => 'Review new prescriptions, confirm the details, and move them forward.',
            'ready' => 'These prescriptions have been prepared and are ready for the patient.',
            'dispensed' => 'Completed pharmacy handoffs stay visible here for quick review.',
        ];
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-pills"></i>
                Pharmacy Dashboard
            </span>
            <h1 class="page-title">{{ $statusLabels[$selectedStatus] ?? 'Prescription queue' }}</h1>
            <p class="page-subtitle">
                {{ $statusDescriptions[$selectedStatus] ?? 'Manage the prescription workflow with the same clear dashboard pattern used elsewhere.' }}
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-list-check"></i>
                {{ number_format($prescriptions->count()) }} shown
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-hourglass-half"></i>
                {{ number_format($pendingCount) }} still pending
            </span>
        </div>
    </div>

    @if ($prescriptions->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-prescription-bottle-medical"></i>
            </div>
            <h2 class="empty-state__title">No prescriptions match this status right now.</h2>
            <p class="empty-state__copy mb-0">
                Use the app bar filters to switch between pending, ready, and dispensed queues.
            </p>
        </section>
    @else
        <section class="section-card">
            <div class="toolbar-row">
                <div>
                    <h2 class="section-heading">Prescription queue</h2>
                    <p class="section-copy">Open any item to review medicines and update its status.</p>
                </div>
            </div>

            <div class="table-shell">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Prescription</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Medicines</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prescriptions as $prescription)
                                @php
                                    $statusClass = match ($prescription->pharmacy_status) {
                                        'ready' => 'status-pill--info',
                                        'dispensed' => 'status-pill--success',
                                        default => 'status-pill--warning',
                                    };
                                @endphp
                                <tr>
                                    <td class="fw-bold">#{{ $prescription->id }}</td>
                                    <td>{{ trim(($prescription->appointment?->patient?->user?->name ?? '') . ' ' . ($prescription->appointment?->patient?->user?->last_name ?? '')) }}</td>
                                    <td>{{ trim(($prescription->appointment?->doctor?->user?->name ?? '') . ' ' . ($prescription->appointment?->doctor?->user?->last_name ?? '')) }}</td>
                                    <td>{{ optional($prescription->appointment?->start_at)->format('M d, Y') ?? '---' }}</td>
                                    <td>{{ number_format($prescription->items->count()) }}</td>
                                    <td>
                                        <span class="status-pill {{ $statusClass }}">
                                            <i class="fas fa-capsules"></i>
                                            {{ ucfirst($prescription->pharmacy_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pharmacy.prescriptions.show', $prescription->id) }}"
                                            class="outline-button">
                                            <i class="fas fa-eye"></i>
                                            View details
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
