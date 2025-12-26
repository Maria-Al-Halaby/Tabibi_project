{{-- @extends('layouts.app')

@section('title', 'promot')


@section('content')

    @if (session('message'))
        <p>{{ session('message') }}</p>
    @endif

    <a href="{{ route('SuperAdmin.Promot.create') }}">add new promot</a>
    @forelse ($promots as $promot)
        <p>{{ $promot->information }}</p>
        <img src="{{ $promot->image }}" alt="promot image">
        <a href="{{ route('SuperAdmin.Promot.edit', $promot->id) }}">update promot</a>
        <a href="{{ route('SuperAdmin.Promot.destroy', $promot->id) }}">delete promot</a>
    @empty
        <h1>there is'nt any promot yet!!</h1>
    @endforelse

@endsection
 --}}


@extends('layouts.app')

@section('title', 'Promotions Management')

@section('content')
    <style>
        :root {
            --main-color: #008080;
            --danger-color: #dc3545;
        }

        .btn-main {
            background-color: var(--main-color);
            border-color: var(--main-color);
            border-radius: 12px;
            color: white;
            padding: 10px 20px;
            transition: 0.3s;
        }

        .btn-main:hover {
            background-color: #006666;
            color: white;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .card-custom:hover {
            transform: translateY(-5px);
        }

        .promo-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .action-link {
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }
    </style>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-5">
            <h3 class="fw-bold m-0" style="color: var(--main-color);">
                <i class="bi bi-megaphone-fill me-2"></i> Promotions
            </h3>
            <a href="{{ route('SuperAdmin.Promot.create') }}" class="btn btn-main">
                <i class="bi bi-plus-lg me-1"></i> Add New Promotion
            </a>
        </div>

        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert"
                style="border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse ($promots as $promot)
                <div class="col-md-4">
                    <div class="card card-custom h-100">
                        <img src="{{ asset($promot->image) }}" class="promo-img" alt="Promotion Image">

                        <div class="card-body d-flex flex-column">
                            <p class="card-text text-muted mb-4">
                                {{ Str::limit($promot->information, 100) }}
                            </p>

                            <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                                <a href="{{ route('SuperAdmin.Promot.edit', $promot->id) }}"
                                    class="text-primary action-link">
                                    <i class="bi bi-pencil-square"></i> Update
                                </a>

                                <a href="{{ route('SuperAdmin.Promot.destroy', $promot->id) }}"
                                    class="text-danger action-link"
                                    onclick="return confirm('Are you sure you want to delete this promotion?')">
                                    <i class="bi bi-trash3"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-emoji-frown display-1 text-muted"></i>
                    </div>
                    <h3 class="text-muted">There isn't any promotion yet!!</h3>
                    <p class="text-secondary">Start by adding your first promotion above.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
