@extends('layouts.app')

@section('title', 'Add Specialization')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-plus-circle-fill"></i>
                New Specialization
            </span>
            <h1 class="page-title">Create a specialization with a stronger presentation.</h1>
            <p class="page-subtitle">
                Add a medical category, optionally attach an image, and keep the platform taxonomy consistent.
            </p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.specialization.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-7">
                    <label for="name" class="field-label">Specialization name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Enter specialization name"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-5">
                    <label for="image" class="field-label">Specialization image</label>
                    <div class="file-drop">
                        <input type="file" name="image" id="image"
                            class="form-control @error('image') is-invalid @enderror">
                        <div class="field-note">Optional, but helpful for a richer browsing experience.</div>
                    </div>
                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-check2-circle"></i>
                    Save specialization
                </button>
                <a href="{{ route('SuperAdmin.specialization.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>
        </form>
    </section>
@endsection
