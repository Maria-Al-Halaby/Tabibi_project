@extends('layouts.app')

@section('title', __('Edit Medical Image Type'))

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-pencil-square"></i>
                {{ __('Edit Medical Image Type') }}</span>
            <h1 class="page-title">{{ __('Update :name without breaking consistency.', ['name' => $typeOfMedicalImage->name]) }}</h1>
            <p class="page-subtitle">{{ __('Refine the radiology service catalog while keeping the interface familiar.') }}</p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.medicalImageType.update', $typeOfMedicalImage->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">
                    <label for="name" class="field-label">{{ __('Medical image type name') }}</label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $typeOfMedicalImage->name) }}"
                        placeholder="{{ __('Enter image type name') }}"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-floppy-fill"></i>
                    {{ __('Update image type') }}</button>
                <a href="{{ route('SuperAdmin.medicalImageType.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    {{ __('Back to list') }}</a>
            </div>
        </form>
    </section>
@endsection
