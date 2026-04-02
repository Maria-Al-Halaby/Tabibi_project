@extends('layouts.app')

@section('title', 'Add Clinic Center')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-plus-circle-fill"></i>
                New Clinic Center
            </span>
            <h1 class="page-title">Add a clinic center with cleaner structure and better flow.</h1>
            <p class="page-subtitle">Create a center profile, assign contact details, and upload an identifying image.</p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.clinic_center.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-6">
                    <label for="name" class="field-label">Center name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        placeholder="Enter clinic center name" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="email" class="field-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        placeholder="Enter clinic center email" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="phone" class="field-label">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        placeholder="Enter clinic center phone" class="form-control @error('phone') is-invalid @enderror">
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

                <div class="col-lg-7">
                    <label for="address" class="field-label">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                        placeholder="Enter clinic center address" class="form-control @error('address') is-invalid @enderror">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-5">
                    <label for="profile_image" class="field-label">Icon image</label>
                    <div class="file-drop">
                        <input type="file" name="profile_image" id="profile_image"
                            class="form-control @error('profile_image') is-invalid @enderror">
                        <div class="field-note">Optional image for quicker recognition in management pages.</div>
                    </div>
                    @error('profile_image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-check2-circle"></i>
                    Save clinic center
                </button>
                <a href="{{ route('SuperAdmin.ClinicCenter.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to centers
                </a>
            </div>
        </form>
    </section>
@endsection
