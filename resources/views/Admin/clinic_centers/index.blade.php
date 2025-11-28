@extends('layouts.admin_app')

@section('title', 'Clinic Center Doctors')


@section('content')

    <h2 class="mb-4">
        <i class="fas fa-clinic-medical tabibi-text-primary me-2"></i> Clinic Center Doctors
    </h2>
    <hr>

    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('Admin.ClinicManagement.create') }}" method="GET" class="d-flex align-items-center gap-3">
                <div class="flex-grow-1">
                    <label for="specialization-filter" class="form-label visually-hidden">Filter by Specialization</label>
                    <select name="specialization_id" id="specialization-filter"
                        class="form-select form-select-lg rounded-pill border-tabibi-primary">
                        <option value="" selected>Select Specialization to Show All Doctors</option>
                        @foreach ($specializations as $specializaion)
                            <option value="{{ $specializaion->id }}" @selected(request('specialization_id') == $specializaion->id)>
                                {{ $specializaion->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-tabibi rounded-pill px-4">
                    <i class="fas fa-search me-2"></i> Select
                </button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        @forelse ($doctors as $doctor)
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center p-3">

                            <img src="{{ $doctor->user->profile_image }}" alt="Doctor Profile Image"
                                class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">

                            <div class="flex-grow-1">
                                <h5 class="mb-0 fw-bold tabibi-text-primary">{{ $doctor->user->name }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-envelope me-1"></i> {{ $doctor->user->email }}
                                </p>
                            </div>

                            {{-- <a href="{{ route('Admin.DoctorSchedule.show', $doctor->id) }}" 
                               class="btn btn-sm btn-outline-warning rounded-pill">View Schedule</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
                <i class="fas fa-hospital-alt fa-4x text-danger mb-3"></i>
                <h1 class="h3">There are no doctors assigned to this center yet.</h1>
            </div>
        @endforelse
    </div>

@endsection

<style>
    .border-tabibi-primary {
        border-color: var(--tabibi-primary-color) !important;
    }
</style>
