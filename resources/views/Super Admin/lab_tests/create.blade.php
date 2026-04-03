@extends('layouts.app')

@section('title', 'Add Lab Test')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-plus-circle-fill"></i>
                New Lab Test
            </span>
            <h1 class="page-title">Add a lab test with the same clean Super Admin workflow.</h1>
            <p class="page-subtitle">
                Create a new lab test once, then make it available across the platform wherever it is needed.
            </p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.labTest.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <label for="name" class="field-label">Lab test name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Enter lab test name"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-check2-circle"></i>
                    Save lab test
                </button>
                <a href="{{ route('SuperAdmin.labTest.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to list
                </a>
            </div>
        </form>
    </section>
@endsection
