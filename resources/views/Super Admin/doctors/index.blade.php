@extends('layouts.app')

@section('title', __('Doctors'))

@section('content')
    @php
        $doctorTypeLabels = [
            'doctor' => __('Doctor'),
            'radiology' => __('Radiology'),
            'lab' => __('Lab'),
        ];
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-people-fill"></i>
                {{ __('Doctors') }}</span>
            <h1 class="page-title">{{ __('Manage the provider roster with more confidence.') }}</h1>
            <p class="page-subtitle">
                {{ __('Doctors are now displayed with clearer hierarchy, better metadata, and cleaner action grouping.') }}</p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-person-badge-fill"></i>
                {{ __(':count doctors', ['count' => number_format($doctors->count())]) }}
            </span>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('message') }}</div>
    @endif

    <div class="toolbar-row">
        <div>
            <h2 class="section-heading">{{ __('Doctor directory') }}</h2>
            <p class="section-copy">{{ __('Review medical staff, then edit or remove records from one clean surface.') }}</p>
        </div>

        <div class="toolbar-actions">
            <a href="{{ route('SuperAdmin.doctor.create') }}" class="btn btn-tabibi">
                <i class="bi bi-plus-circle"></i>
                {{ __('Add doctor') }}</a>
        </div>
    </div>

    @if ($doctors->isEmpty())<section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h2 class="empty-state__title">{{ __('There are no doctors yet.') }}</h2>
            <p class="empty-state__copy">{{ __('Start by creating the first doctor profile for the platform.') }}</p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($doctors as $doctor)
                <div class="col-12">
                    <section class="record-card">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-4">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <img src="{{ asset($doctor->user->profile_image) }}" alt="{{ $doctor->user->name }}"
                                    class="avatar-circle"
                                    onerror="this.onerror=null; this.src='https://placehold.co/96x96/0f766e/ffffff?text=DR';">

                                <div>
                                    <h2 class="record-card__title mb-1">{{ $doctor->user->name }}</h2>
                                    <p class="record-card__copy mb-2">{{ $doctor->specialization?->name ?? __('No specialization') }}</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="status-pill status-pill--success">{{ $doctorTypeLabels[$doctor->doctor_type ?? 'doctor'] ?? __('Doctor') }}</span>
                                        @if (!is_null($doctor->experience_years))<span class="status-pill status-pill--warning">{{ __(':count years experience', ['count' => $doctor->experience_years]) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 flex-grow-1">
                                <div class="col-md-6">
                                    <div class="mini-metric h-100">
                                        <div class="mini-metric__label">{{ __('Email') }}</div>
                                        <p class="mini-metric__value">{{ $doctor->user->email }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mini-metric h-100">
                                        <div class="mini-metric__label">{{ __('Phone') }}</div>
                                        <p class="mini-metric__value">{{ $doctor->user->phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="toolbar-actions">
                                <a href="{{ route('SuperAdmin.doctor.edit', $doctor->id) }}" class="outline-button">
                                    <i class="bi bi-pencil-square"></i>
                                    {{ __('Edit') }}</a>

                                <form action="{{ route('SuperAdmin.doctor.destroy', $doctor->id) }}" method="POST"
                                    onsubmit="return confirm(@js(__('Are you sure you want to delete Doctor :name?', ['name' => $doctor->user->name])))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="danger-outline-button">
                                        <i class="bi bi-trash3"></i>
                                        {{ __('Delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
