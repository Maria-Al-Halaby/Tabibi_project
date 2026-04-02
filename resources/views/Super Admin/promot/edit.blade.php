@extends('layouts.app')

@section('title', 'Edit Promotion')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-pencil-square"></i>
                Edit Promotion
            </span>
            <h1 class="page-title">Update promotion #{{ $promot->id }} without losing clarity.</h1>
            <p class="page-subtitle">Refresh message or artwork while keeping the promotion management flow simple.</p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.Promot.update', $promot->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-7">
                    <label for="information" class="field-label">Promotion information</label>
                    <textarea name="information" id="information" placeholder="Enter promotion details"
                        class="form-control @error('information') is-invalid @enderror">{{ old('information', $promot->information) }}</textarea>
                    @error('information')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-5">
                    <label for="image" class="field-label">Current image</label>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ asset($promot->image) }}" alt="Current promotion image"
                            class="image-preview" style="width: 140px; height: 140px; border-radius: 32px;">
                    </div>

                    <div class="file-drop">
                        <input type="file" name="image" id="image"
                            class="form-control @error('image') is-invalid @enderror">
                        <div class="field-note">Leave this empty if the current image should stay unchanged.</div>
                    </div>
                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-floppy-fill"></i>
                    Update promotion
                </button>
                <a href="{{ route('SuperAdmin.Promot.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to promotions
                </a>
            </div>
        </form>
    </section>
@endsection
