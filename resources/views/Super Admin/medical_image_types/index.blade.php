@extends('layouts.app')

@section('title', 'Medical Image Types')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-badge-ad"></i>
                Medical Image Types
            </span>
            <h1 class="page-title">Manage radiology image types with the same clarity as other platform data.</h1>
            <p class="page-subtitle">
                This section gives radiology service types a dedicated CRUD workflow inside the Super Admin panel.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-collection"></i>
                {{ number_format($typeOfMedicalImages->count()) }} image types
            </span>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('message') }}</div>
    @endif

    <div class="toolbar-row">
        <div>
            <h2 class="section-heading">Image type directory</h2>
            <p class="section-copy">Keep radiology service categories clean, searchable, and easy to maintain.</p>
        </div>

        <div class="toolbar-actions">
            <a href="{{ route('SuperAdmin.medicalImageType.create') }}" class="btn btn-tabibi">
                <i class="bi bi-plus-circle"></i>
                Add image type
            </a>
        </div>
    </div>

    @if ($typeOfMedicalImages->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="bi bi-image-alt"></i>
            </div>
            <h2 class="empty-state__title">No medical image types have been added yet.</h2>
            <p class="empty-state__copy">Create the first image type to organize radiology services across the platform.</p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($typeOfMedicalImages as $typeOfMedicalImage)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="empty-state__icon mb-0" style="width:72px;height:72px;">
                                <i class="bi bi-images"></i>
                            </span>

                            <div>
                                <h2 class="record-card__title mb-1">{{ $typeOfMedicalImage->name }}</h2>
                                <p class="record-card__copy">Radiology catalog item</p>
                            </div>
                        </div>

                        <div class="mini-metric mb-4">
                            <div class="mini-metric__label">Clinics using this type</div>
                            <p class="mini-metric__value">{{ number_format($typeOfMedicalImage->clinic_centers_count) }}</p>
                        </div>

                        <div class="toolbar-actions">
                            <a href="{{ route('SuperAdmin.medicalImageType.edit', $typeOfMedicalImage->id) }}"
                                class="outline-button">
                                <i class="bi bi-pencil-square"></i>
                                Edit
                            </a>

                            <form action="{{ route('SuperAdmin.medicalImageType.destroy', $typeOfMedicalImage->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete image type {{ $typeOfMedicalImage->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-outline-button">
                                    <i class="bi bi-trash3"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
