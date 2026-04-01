@extends('layouts.app')

@section('title', 'Update Clinic Center')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-pencil-square"></i>
                Edit Clinic Center
            </span>
            <h1 class="page-title">Update {{ $clinicCenter->name }} with a more reliable editing flow.</h1>
            <p class="page-subtitle">
                Refresh contact details, address, credentials, or icon while keeping the experience consistent.
            </p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.clinic_center.update', $clinicCenter->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-6">
                    <label for="name" class="field-label">Center name</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $clinicCenter->name) }}"
                        placeholder="Enter clinic center name" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="email" class="field-label">Email</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', $clinicCenter->user->email) }}"
                        placeholder="Enter clinic center email" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="phone" class="field-label">Phone</label>
                    <input type="text" name="phone" id="phone"
                        value="{{ old('phone', $clinicCenter->user->phone) }}"
                        placeholder="Enter clinic center phone" class="form-control @error('phone') is-invalid @enderror">
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

                <div class="col-lg-7">
                    <label for="address" class="field-label">Address</label>
                    <input type="text" name="address" id="address"
                        value="{{ old('address', $clinicCenter->address) }}"
                        placeholder="Enter clinic center address" class="form-control @error('address') is-invalid @enderror">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-5">
                    <label for="profile_image_input" class="field-label">Current icon</label>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $clinicCenter->user->profile_image }}" alt="{{ $clinicCenter->name }}"
                            class="image-preview"
                            onerror="this.onerror=null; this.src='https://placehold.co/120x120/0f766e/ffffff?text=CC';">
                    </div>

                    <div class="file-drop">
                        <input type="file" name="profile_image" id="profile_image_input"
                            class="form-control @error('profile_image') is-invalid @enderror">
                        <div class="field-note">Upload a new icon only if you want to replace the current one.</div>
                    </div>
                    @error('profile_image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-floppy-fill"></i>
                    Update clinic center
                </button>
                <a href="{{ route('SuperAdmin.ClinicCenter.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to centers
                </a>
            </div>
        </form>
    </section>
@endsection
