{{-- @extends('layouts.app')

@section('title', 'add new promot')


@section('content')

    <form action="{{ route('SuperAdmin.Promot.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="information">information for promot</label>
        <input type="text" name="information" id="information">
        <label for="image">image for promot</label>
        <input type="file" name="image" id="image">
        <input type="submit" value="send">
    </form>

@endsection
 --}}

@extends('layouts.app')

@section('title', 'Add New Promotion')

@section('content')
    <style>
        :root {
            --main-color: #008080;
        }

        .form-control-custom {
            border: 1px solid #ced4da;
            border-radius: 12px;
            padding: 15px;
            background-color: white;
            transition: all 0.3s ease-in-out;
        }

        .form-control-custom:focus {
            border-color: var(--main-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 128, 128, 0.25);
        }

        .btn-main {
            background-color: var(--main-color);
            border-color: var(--main-color);
            border-radius: 12px;
            color: white;
            padding: 12px 0;
            font-size: 1.1rem;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-main:hover {
            background-color: #006666;
            border-color: #006666;
            color: white;
        }

        .upload-box {
            border: 2px dashed #ced4da;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">

                <h3 class="mb-4 fw-bold text-center" style="color: var(--main-color);">
                    <i class="bi bi-megaphone-fill me-2"></i> Create New Promotion
                </h3>

                <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                    <form action="{{ route('SuperAdmin.Promot.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="information" class="form-label fw-semibold text-muted">Promotion Information</label>
                            <textarea name="information" id="information" rows="4"
                                class="form-control form-control-custom @error('information') is-invalid @enderror"
                                placeholder="Write a catchy description for the promotion..." required>{{ old('information') }}</textarea>
                            @error('information')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="image" class="form-label fw-semibold text-muted">Promotion Banner / Image</label>
                            <div class="upload-box">
                                <i class="bi bi-cloud-arrow-up display-6 text-muted mb-2 d-block"></i>
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror" required>
                                <small class="text-secondary mt-2 d-block">Recommended size: 1200x600px (JPG, PNG)</small>
                            </div>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-main">
                                <i class="bi bi-plus-circle me-1"></i> Publish Promotion
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('SuperAdmin.Promot.index') }}" class="text-muted text-decoration-none small">
                                <i class="bi bi-arrow-left"></i> Back to list
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
