@extends('layouts.app')

@section('title', 'Edit Lab Test')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-pencil-square"></i>
                Edit Lab Test
            </span>
            <h1 class="page-title">Update {{ $labTest->name }} without losing consistency.</h1>
            <p class="page-subtitle">Refine the lab test catalog while keeping the same clear Super Admin flow.</p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.labTest.update', $labTest->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">
                    <label for="name" class="field-label">Lab test name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $labTest->name) }}"
                        placeholder="Enter lab test name"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-floppy-fill"></i>
                    Update lab test
                </button>
                <a href="{{ route('SuperAdmin.labTest.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>
        </form>
    </section>
@endsection
