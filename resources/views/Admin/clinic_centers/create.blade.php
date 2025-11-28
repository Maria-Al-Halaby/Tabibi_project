@extends('layouts.admin_app')

@section('title', 'All Doctors on Specializations')

@section('content')

    <h2 class="mb-4">
        <i class="fas fa-user-md tabibi-text-primary me-2"></i> Doctors List
    </h2>
    <hr>

    @if ($doctors->isEmpty())
        <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
            <i class="fas fa-info-circle fa-4x text-info mb-3"></i>
            <h1 class="h3">There are no doctors registered in this specialization yet.</h1>
            <a href="#" class="btn btn-tabibi mt-3 shadow">
                <i class="fas fa-plus me-2"></i> Add New Doctor
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($doctors as $doctor)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                        <div class="card-body d-flex flex-column align-items-center text-center p-4">

                            <img src="{{ $doctor->user->profile_image }}" alt="Doctor Profile Image"
                                class="rounded-circle mb-3 border border-4 border-light shadow-sm"
                                style="width: 90px; height: 90px; object-fit: cover;">

                            <h5 class="card-title fw-bold mb-1">{{ $doctor->user->name }}</h5>

                            <p class="text-muted small mb-3">
                                <i class="fas fa-tag me-1 text-secondary"></i>
                                {{ $doctor->specialization->name }}
                            </p>

                            <div class="mt-auto w-100">
                                <a href="{{ route('Admin.DoctorSchedule.show', $doctor->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill w-100 tabibi-text-primary border-tabibi-primary">
                                    <i class="fas fa-clock me-1"></i> Show Doctor Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection

<style>
    /* يجب أن يكون هذا التنسيق في ملف CSS العام أو في الـ Layout إذا لم يكن موجوداً */
    .border-tabibi-primary {
        border-color: var(--tabibi-primary-color) !important;
    }
</style>
