@extends('layouts.app')

@section('title', 'Clinic Centers')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-hospital-fill"></i>
                Clinic Centers
            </span>
            <h1 class="page-title">Manage every clinic center from one polished overview.</h1>
            <p class="page-subtitle">
                The listing now gives each center clearer structure, status visibility, and safer management actions.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-buildings-fill"></i>
                {{ number_format($clinicCenters->count()) }} centers
            </span>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('message') }}</div>
    @endif

    <div class="toolbar-row">
        <div>
            <h2 class="section-heading">Clinic network</h2>
            <p class="section-copy">Review center contact details, operational status, and edit paths in one place.</p>
        </div>

        <div class="toolbar-actions">
            <a href="{{ route('SuperAdmin.clinicCenter.create') }}" class="btn btn-tabibi">
                <i class="bi bi-plus-circle"></i>
                Add clinic center
            </a>
        </div>
    </div>

    @if ($clinicCenters->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="bi bi-hospital"></i>
            </div>
            <h2 class="empty-state__title">There are no clinic centers yet.</h2>
            <p class="empty-state__copy">Create the first center to start building the network.</p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($clinicCenters as $clinic_center)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card">
                        <div class="record-card__header">
                            <div>
                                <h2 class="record-card__title mb-1">{{ $clinic_center->name }}</h2>
                                <p class="record-card__copy">{{ $clinic_center->address }}</p>
                            </div>

                            <span class="status-pill {{ $clinic_center->is_active ? 'status-pill--success' : 'status-pill--danger' }}">
                                <i class="bi {{ $clinic_center->is_active ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                {{ $clinic_center->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="d-grid gap-3 mb-4">
                            <div class="mini-metric">
                                <div class="mini-metric__label">Email</div>
                                <p class="mini-metric__value">{{ $clinic_center->user->email }}</p>
                            </div>

                            <div class="mini-metric">
                                <div class="mini-metric__label">Phone</div>
                                <p class="mini-metric__value">{{ $clinic_center->user->phone }}</p>
                            </div>
                        </div>

                        <div class="toolbar-actions">
                            <a href="{{ route('SuperAdmin.clinic_center.edit', $clinic_center->id) }}"
                                class="outline-button">
                                <i class="bi bi-pencil-square"></i>
                                Edit
                            </a>

                            <form action="{{ route('SuperAdmin.clinic_center.destroy', $clinic_center->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete {{ $clinic_center->name }}?')">
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
