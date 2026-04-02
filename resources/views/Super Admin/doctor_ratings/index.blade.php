@extends('layouts.app')

@section('title', 'Doctor Ratings')

@section('content')
    @php
        $flaggedDoctorsCount = $doctors->filter(fn($doctor) => $doctor->negative_ratings_count >= $minNegativeCount)->count();
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-stars"></i>
                Doctor Ratings
            </span>
            <h1 class="page-title">Monitor quality signals before they become trust issues.</h1>
            <p class="page-subtitle">
                Doctor performance, warnings, and recent patient feedback now sit in one cleaner moderation workspace.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-flag-fill"></i>
                {{ $flaggedDoctorsCount }} flagged doctors
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="bi bi-chat-left-text-fill"></i>
                {{ $ratings->total() }} reviews
            </span>
        </div>
    </div>

    <section class="section-card form-panel mb-4">
        <div class="toolbar-row">
            <div>
                <h2 class="section-heading">Rating filters</h2>
                <p class="section-copy">Adjust the negative-rating threshold and the alert minimum in one place.</p>
            </div>
        </div>

        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="negative_max" class="field-label">Negative if stars are less than or equal to</label>
                <input type="number" name="negative_max" id="negative_max" min="1" max="5"
                    value="{{ $negativeMaxStars }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label for="min_negative" class="field-label">Alert threshold for negative ratings</label>
                <input type="number" name="min_negative" id="min_negative" min="1"
                    value="{{ $minNegativeCount }}" class="form-control">
            </div>

            <div class="col-md-4">
                <button class="btn btn-tabibi w-100">
                    <i class="bi bi-funnel-fill"></i>
                    Apply filters
                </button>
            </div>
        </form>
    </section>

    <section class="section-card mb-4">
        <div class="toolbar-row">
            <div>
                <h2 class="section-heading">Doctor summary</h2>
                <p class="section-copy">A structured overview of performance, warnings, and moderation actions.</p>
            </div>
        </div>

        <div class="table-shell">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Average rating</th>
                        <th>Total ratings</th>
                        <th>Negative ratings</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doctors as $doctor)
                        @php
                            $isFlagged = $doctor->negative_ratings_count >= $minNegativeCount;
                        @endphp
                        <tr @if ($isFlagged) style="background: rgba(245, 158, 11, 0.08);" @endif>
                            <td class="fw-bold">{{ $doctor->user?->name ?? 'Doctor #' . $doctor->id }}</td>
                            <td>{{ number_format((float) $doctor->avg_rating, 2) }}</td>
                            <td>{{ $doctor->ratings_count }}</td>
                            <td>
                                <span class="status-pill {{ $isFlagged ? 'status-pill--warning' : 'status-pill--success' }}">
                                    <i class="bi {{ $isFlagged ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }}"></i>
                                    {{ $doctor->negative_ratings_count }}
                                </span>
                            </td>
                            <td>
                                <span class="status-pill {{ ($doctor->is_active ?? 1) ? 'status-pill--success' : 'status-pill--danger' }}">
                                    <i class="bi {{ ($doctor->is_active ?? 1) ? 'bi-check-circle-fill' : 'bi-slash-circle-fill' }}"></i>
                                    {{ ($doctor->is_active ?? 1) ? 'Active' : 'Suspended' }}
                                </span>
                            </td>
                            <td>
                                <div class="toolbar-actions">
                                    <form method="POST" action="{{ route('doctors.deactivate', $doctor) }}">
                                        @csrf
                                        <button class="outline-button">
                                            <i class="bi bi-pause-circle"></i>
                                            Suspend
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('doctors.destroy', $doctor) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this doctor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger-outline-button">
                                            <i class="bi bi-trash3"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $doctors->links() }}
        </div>
    </section>

    <section class="section-card">
        <div class="toolbar-row">
            <div>
                <h2 class="section-heading">Detailed reviews</h2>
                <p class="section-copy">Recent patient feedback with stars, comments, and timestamps.</p>
            </div>
        </div>

        <div class="table-shell">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th>Stars</th>
                        <th>Comment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ratings as $rating)
                        <tr>
                            <td class="fw-bold">{{ $rating->doctor?->user?->name ?? 'Doctor #' . $rating->doctor_id }}</td>
                            <td>{{ $rating->patient?->user?->name ?? 'Patient #' . $rating->patient_id }}</td>
                            <td>
                                <span class="status-pill status-pill--warning">
                                    <i class="bi bi-star-fill"></i>
                                    {{ $rating->rating }}
                                </span>
                            </td>
                            <td>{{ $rating->comment ?: 'No comment provided.' }}</td>
                            <td>{{ optional($rating->created_at)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $ratings->links() }}
        </div>
    </section>
@endsection
