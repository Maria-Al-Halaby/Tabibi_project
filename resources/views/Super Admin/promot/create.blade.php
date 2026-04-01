@extends('layouts.app')

@section('title', 'Add Promotion')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-plus-circle-fill"></i>
                New Promotion
            </span>
            <h1 class="page-title">Create a promotional card that feels deliberate and polished.</h1>
            <p class="page-subtitle">
                Add campaign copy and a supporting image in a layout that keeps content and media balanced.
            </p>
        </div>
    </div>

    <section class="section-card form-panel">
        <form action="{{ route('SuperAdmin.Promot.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-7">
                    <label for="information" class="field-label">Promotion information</label>
                    <textarea name="information" id="information" placeholder="Write the campaign message"
                        class="form-control @error('information') is-invalid @enderror">{{ old('information') }}</textarea>
                    @error('information')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-5">
                    <label for="image" class="field-label">Promotion image</label>
                    <div class="file-drop">
                        <input type="file" name="image" id="image"
                            class="form-control @error('image') is-invalid @enderror">
                        <div class="field-note">A landscape image usually works best for promotional content.</div>
                    </div>
                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="toolbar-actions mt-4">
                <button type="submit" class="btn btn-tabibi">
                    <i class="bi bi-send-fill"></i>
                    Publish promotion
                </button>
                <a href="{{ route('SuperAdmin.Promot.index') }}" class="ghost-button">
                    <i class="bi bi-arrow-left"></i>
                    Back to promotions
                </a>
            </div>
        </form>
    </section>
@endsection
