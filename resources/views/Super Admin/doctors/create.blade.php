@extends('layouts.app')

@section('title', 'Add Doctor')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-person-plus-fill"></i>
                New Doctor
            </span>
            <h1 class="page-title">Create a doctor profile that feels structured and complete.</h1>
            <p class="page-subtitle">
                The form is grouped for faster scanning so admins can add providers with less friction and fewer mistakes.
            </p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.doctor.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-6">
                    <label for="name" class="field-label">Doctor name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        placeholder="Enter doctor name" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="email" class="field-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        placeholder="Enter doctor email" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="phone" class="field-label">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        placeholder="Enter doctor phone" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="password" class="field-label">Password</label>
                    <input type="password" name="password" id="password"
                        placeholder="Set account password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="experience_years" class="field-label">Experience years</label>
                    <input type="number" name="experience_years" id="experience_years"
                        value="{{ old('experience_years') }}"
                        placeholder="Enter years of experience"
                        class="form-control @error('experience_years') is-invalid @enderror">
                    @error('experience_years')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="specialization_id" class="field-label">Specialization</label>
                    <select name="specialization_id" id="specialization_id"
                        class="form-select @error('specialization_id') is-invalid @enderror">
                        <option value="">Choose specialization</option>
                        @foreach ($specializations as $specialization)
                            <option value="{{ $specialization->id }}" @selected(old('specialization_id') == $specialization->id)>
                                {{ $specialization->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialization_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="bio" class="field-label">Bio</label>
                    <textarea name="bio" id="bio" placeholder="Enter doctor bio"
                        class="form-control @error('bio') is-invalid @enderror">{{ old('bio') }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-7">
                    <label class="field-label">Doctor type</label>
                    <div class="type-grid">
                        @foreach (['doctor' => 'Doctor', 'radiology' => 'Radiology', 'lab' => 'Lab'] as $value => $label)
                            <label class="type-card">
                                <input type="radio" name="doctor_type" value="{{ $value }}"
                                    @checked(old('doctor_type', 'doctor') === $value)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('doctor_type')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-5">
                    <label for="profile_image" class="field-label">Profile image</label>
                    <div class="file-drop">
                        <input type="file" name="profile_image" id="profile_image"
                            class="form-control @error('profile_image') is-invalid @enderror">
                        <div class="field-note">Optional image to make directory browsing clearer.</div>
                    </div>
                    @error('profile_image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-check2-circle"></i>
                    Save doctor
                </button>
                <a href="{{ route('SuperAdmin.doctor.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to doctors
                </a>
            </div>
        </form>
    </section>
@endsection

@push('styles')
    <style>
        .type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.85rem;
        }

        .type-card {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 1rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(148, 163, 184, 0.18);
            font-weight: 700;
            cursor: pointer;
        }

        .type-card input {
            accent-color: var(--main-color);
        }
    </style>
@endpush
