@extends('layouts.admin_app')

@section('title', 'Secretary Management')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-user-tie"></i>
                Secretary Management
            </span>
            <h1 class="page-title">Build a front-desk team that keeps your center organized.</h1>
            <p class="page-subtitle">
                Add secretary accounts for {{ $center->name }}, keep their contact details current, and make sure the
                appointment desk is always covered.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hospital"></i>
                {{ $center->name }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-user-group"></i>
                {{ number_format($secretaries->count()) }} secretaries
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-5">
            <section class="section-card form-panel h-100">
                <div class="toolbar-row">
                    <div>
                        <h2 class="section-heading">Add a secretary</h2>
                        <p class="section-copy">Create a dedicated front-desk account directly for this center.</p>
                    </div>
                </div>

                <form action="{{ route('Admin.Secretary.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label for="name" class="field-label">First name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Enter first name" required>
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="field-label">Last name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                            class="form-control" placeholder="Enter last name">
                    </div>

                    <div class="col-12">
                        <label for="email" class="field-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Enter email address" required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="field-label">Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control"
                            placeholder="Enter phone number" required>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="field-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Enter password" required>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-tabibi">
                            <i class="fas fa-user-plus me-2"></i>Add secretary
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <div class="col-12 col-xl-7">
            @if ($secretaries->isEmpty())
                <section class="section-card empty-state h-100">
                    <div class="empty-state__icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h2 class="empty-state__title">No secretaries have been added yet.</h2>
                    <p class="empty-state__copy mb-0">
                        Create the first secretary for {{ $center->name }} using the form on the left.
                    </p>
                </section>
            @else
                <div class="row g-4">
                    @foreach ($secretaries as $secretary)
                        @php
                            $initials = strtoupper(substr($secretary->name ?? 'S', 0, 1) . substr($secretary->last_name ?? '', 0, 1));
                        @endphp
                        <div class="col-12">
                            <section class="record-card">
                                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="avatar-fallback">{{ $initials !== '' ? $initials : 'SC' }}</span>
                                        <div>
                                            <h2 class="record-card__title mb-1">
                                                {{ trim(($secretary->name ?? '') . ' ' . ($secretary->last_name ?? '')) }}
                                            </h2>
                                            <p class="record-card__copy mb-0">Center secretary</p>
                                        </div>
                                    </div>

                                    <div class="toolbar-actions">
                                        <a href="{{ route('Admin.Secretary.edit', $secretary->id) }}" class="outline-button">
                                            <i class="fas fa-pen"></i>
                                            Edit
                                        </a>

                                        <form action="{{ route('Admin.Secretary.destroy', $secretary->id) }}" method="POST"
                                            class="m-0"
                                            onsubmit="return confirm('Delete this secretary?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="danger-outline-button">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <div class="mini-metric h-100">
                                            <div class="mini-metric__label">Email</div>
                                            <p class="mini-metric__value">{{ $secretary->email }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mini-metric h-100">
                                            <div class="mini-metric__label">Phone</div>
                                            <p class="mini-metric__value">{{ $secretary->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
