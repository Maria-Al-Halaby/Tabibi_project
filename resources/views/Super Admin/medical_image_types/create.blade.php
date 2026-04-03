@extends('layouts.app')

@section('title', 'Add Medical Image Type')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-plus-circle-fill"></i>
                New Medical Image Type
            </span>
            <h1 class="page-title">Add a radiology image type with a clear Super Admin flow.</h1>
            <p class="page-subtitle">
                Create a reusable medical image type that clinics can later price and expose in radiology workflows.
            </p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.medicalImageType.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <label for="name" class="field-label">Medical image type name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Enter image type name"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-check2-circle"></i>
                    Save image type
                </button>
                <a href="{{ route('SuperAdmin.medicalImageType.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>
        </form>
    </section>
@endsection
