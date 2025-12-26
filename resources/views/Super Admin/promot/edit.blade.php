{{-- @extends("layouts.app")

@section("title" , 'update promot')


@section("content")
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <label for="information">promot information :</label>
        <input type="text" name="information" id="information" value="{{ old($promot->information) }}">
        <label for="image">promot image :</label>
        <input type="file" name="image" id="image">
        <input type="submit" value="update">
    </form>
@endsection --}}

@extends('layouts.app')

@section('title', 'Update Promotion')

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
            transition: all 0.3s;
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
            transition: 0.3s;
        }

        .btn-main:hover {
            background-color: #006666;
            color: white;
        }

        .current-img-container {
            border-radius: 12px;
            overflow: hidden;
            border: 2px dashed #ced4da;
            padding: 10px;
            width: fit-content;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <h3 class="mb-5 fw-bold text-center" style="color: var(--main-color);">
                    <i class="bi bi-pencil-square me-2"></i> Update Promotion
                </h3>

                <form action="{{ route('SuperAdmin.Promot.update', $promot->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")

                    <div class="mb-4">
                        <label for="information" class="form-label fw-semibold text-muted">Promotion Information</label>
                        <textarea name="information" id="information" rows="4" 
                            class="form-control form-control-custom @error('information') is-invalid @enderror"
                            placeholder="Enter promotion details...">{{ old('information', $promot->information) }}</textarea>
                        @error('information')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted d-block">Current Image</label>
                        <div class="current-img-container mb-3">
                            <img src="{{ asset($promot->image) }}" alt="Current Promo" style="max-height: 150px; border-radius: 8px;">
                        </div>
                        
                        <label for="image" class="form-label fw-semibold text-muted">Upload New Image (Optional)</label>
                        <input type="file" name="image" id="image"
                            class="form-control form-control-custom @error('image') is-invalid @enderror">
                        <small class="text-secondary mt-1 d-block">Leave empty if you don't want to change the image.</small>
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-main shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Update Promotion Details
                        </button>
                        <a href="{{ route('SuperAdmin.Promot.index') }}" class="btn btn-link text-muted">Cancel and go back</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection