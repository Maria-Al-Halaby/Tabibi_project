@extends('layouts.admin_app')

@section('title', 'All Doctors')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-users"></i>
                Doctors Directory
            </span>
            <h1 class="page-title">Review the full clinic doctor directory.</h1>
            <p class="page-subtitle">
                A simpler, more readable directory view for browsing every doctor currently available to this center.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-address-book"></i>
                {{ number_format($doctors->count()) }} records
            </span>
        </div>
    </div>

    @if ($doctors->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="fas fa-user-slash"></i>
            </div>
            <h2 class="empty-state__title">There are no doctors registered yet.</h2>
            <p class="empty-state__copy">Once doctors are linked to the center, they will appear in this directory.</p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($doctors as $doctor)
                <div class="col-sm-6 col-xl-3">
                    <section class="record-card text-center">
                        <img src="{{ $doctor->user->profile_image }}" alt="{{ $doctor->user->name }}"
                            class="avatar-circle mb-3">
                        <h2 class="record-card__title mb-1">{{ $doctor->user->name }}</h2>
                        <p class="record-card__copy mb-4">{{ $doctor->user->email }}</p>

                        <a href="{{ route('Admin.DoctorSchedule.show', $doctor->id) }}" class="outline-button w-100">
                            <i class="fas fa-clock"></i>
                            Schedule
                        </a>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
