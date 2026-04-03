@extends('layouts.app')

@section('title', 'Edit Medical Image Type')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-pencil-square"></i>
                Edit Medical Image Type
            </span>
            <h1 class="page-title">Update {{ $typeOfMedicalImage->name }} without breaking consistency.</h1>
            <p class="page-subtitle">Refine the radiology service catalog while keeping the interface familiar.</p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.medicalImageType.update', $typeOfMedicalImage->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">
                    <label for="name" class="field-label">Medical image type name</label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $typeOfMedicalImage->name) }}"
                        placeholder="Enter image type name"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-floppy-fill"></i>
                    Update image type
                </button>
                <a href="{{ route('SuperAdmin.medicalImageType.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>
        </form>
    </section>
@endsection
