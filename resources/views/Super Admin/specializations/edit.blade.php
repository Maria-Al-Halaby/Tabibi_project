@extends('layouts.app')

@section('title', 'Edit Specialization')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-pencil-square"></i>
                Edit Specialization
            </span>
            <h1 class="page-title">Update {{ $specialization->name }} without losing clarity.</h1>
            <p class="page-subtitle">Refine the name or artwork while keeping the specialization library polished.</p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.specialization.update', $specialization->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4 align-items-start">
                <div class="col-lg-7">
                    <label for="name" class="field-label">Specialization name</label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $specialization->name) }}"
                        placeholder="Enter specialization name"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-5">
                    <label for="image_input" class="field-label">Current image</label>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if (!empty($specialization->image))
                            <img src="{{ $specialization->image }}" alt="{{ $specialization->name }}"
                                class="image-preview">
                        @else
                            <span class="empty-state__icon mb-0" style="width:112px;height:112px;">
                                <i class="bi bi-image"></i>
                            </span>
                        @endif
                    </div>

                    <div class="file-drop">
                        <input type="file" name="image" id="image_input"
                            class="form-control @error('image') is-invalid @enderror">
                        <div class="field-note">Upload a new image only if you want to replace the current one.</div>
                    </div>
                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-floppy-fill"></i>
                    Update specialization
                </button>
                <a href="{{ route('SuperAdmin.specialization.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>
        </form>
    </section>
@endsection
