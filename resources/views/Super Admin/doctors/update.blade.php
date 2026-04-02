@extends('layouts.app')

@section('title', 'Update Doctor')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-person-fill-gear"></i>
                Edit Doctor
            </span>
            <h1 class="page-title">Update {{ $doctor->user->name }} with a cleaner editing flow.</h1>
            <p class="page-subtitle">
                Review profile data, refresh account details, and replace media without wrestling with the form.
            </p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.doctor.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-6">
                    <label for="name" class="field-label">Doctor name</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $doctor->user->name) }}"
                        placeholder="Enter doctor name" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="email" class="field-label">Email</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', $doctor->user->email) }}"
                        placeholder="Enter doctor email" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="phone" class="field-label">Phone</label>
                    <input type="text" name="phone" id="phone"
                        value="{{ old('phone', $doctor->user->phone) }}"
                        placeholder="Enter doctor phone" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="password" class="field-label">New password</label>
                    <input type="password" name="password" id="password"
                        placeholder="Leave blank to keep current password"
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="experience_years" class="field-label">Experience years</label>
                    <input type="number" name="experience_years" id="experience_years"
                        value="{{ old('experience_years', $doctor->experience_years) }}"
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
                        @foreach ($specializations as $specialization)
                            <option value="{{ $specialization->id }}"
                                @selected(old('specialization_id', $doctor->specialization_id) == $specialization->id)>
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
                        class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $doctor->bio) }}</textarea>
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
                                    @checked(old('doctor_type', $doctor->doctor_type) === $value)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('doctor_type')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-5">
                    <label for="profile_image_input" class="field-label">Current image</label>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $doctor->user->profile_image }}" alt="{{ $doctor->user->name }}"
                            class="image-preview"
                            onerror="this.onerror=null; this.src='https://placehold.co/120x120/0f766e/ffffff?text=DR';">
                    </div>

                    <div class="file-drop">
                        <input type="file" name="profile_image" id="profile_image_input"
                            class="form-control @error('profile_image') is-invalid @enderror">
                        <div class="field-note">Upload only if you want to replace the current image.</div>
                    </div>
                    @error('profile_image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-floppy-fill"></i>
                    Update doctor
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
