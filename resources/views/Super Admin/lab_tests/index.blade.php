@extends('layouts.app')

@section('title', 'Lab Tests')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-capsule"></i>
                Lab Tests
            </span>
            <h1 class="page-title">Keep the lab test catalog structured and easy to maintain.</h1>
            <p class="page-subtitle">
                This new Super Admin section gives lab tests the same polished CRUD workflow used across the rest of the
                platform.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-list-check"></i>
                {{ number_format($labTests->count()) }} lab tests
            </span>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('message') }}</div>
    @endif

    <div class="toolbar-row">
        <div>
            <h2 class="section-heading">Lab test directory</h2>
            <p class="section-copy">Review, edit, or remove tests from one clean management surface.</p>
        </div>

        <div class="toolbar-actions">
            <a href="{{ route('SuperAdmin.labTest.create') }}" class="btn btn-tabibi">
                <i class="bi bi-plus-circle"></i>
                Add lab test
            </a>
        </div>
    </div>

    @if ($labTests->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="bi bi-clipboard2-pulse"></i>
            </div>
            <h2 class="empty-state__title">No lab tests have been added yet.</h2>
            <p class="empty-state__copy">Create the first lab test to start managing laboratory services centrally.</p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($labTests as $labTest)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="empty-state__icon mb-0" style="width:72px;height:72px;">
                                <i class="bi bi-capsule-pill"></i>
                            </span>

                            <div>
                                <h2 class="record-card__title mb-1">{{ $labTest->name }}</h2>
                                <p class="record-card__copy">Platform lab service</p>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Clinics</div>
                                    <p class="mini-metric__value">{{ number_format($labTest->clinic_centers_count) }}</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mini-metric h-100">
                                    <div class="mini-metric__label">Appointments</div>
                                    <p class="mini-metric__value">{{ number_format($labTest->appointments_count) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="toolbar-actions">
                            <a href="{{ route('SuperAdmin.labTest.edit', $labTest->id) }}" class="outline-button">
                                <i class="bi bi-pencil-square"></i>
                                Edit
                            </a>

                            <form action="{{ route('SuperAdmin.labTest.destroy', $labTest->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete lab test {{ $labTest->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-outline-button">
                                    <i class="bi bi-trash3"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
