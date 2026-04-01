@extends('layouts.app')

@section('title', 'Promotions')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-megaphone-fill"></i>
                Promotions
            </span>
            <h1 class="page-title">Manage promotional content with better visual focus.</h1>
            <p class="page-subtitle">
                Campaigns now feel more editorial and easier to browse, while still keeping update and delete actions close.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-images"></i>
                {{ number_format($promots->count()) }} promotions
            </span>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('message') }}</div>
    @endif

    <div class="toolbar-row">
        <div>
            <h2 class="section-heading">Promotion library</h2>
            <p class="section-copy">Review active creatives, then edit or remove them with fewer clicks.</p>
        </div>

        <div class="toolbar-actions">
            <a href="{{ route('SuperAdmin.Promot.create') }}" class="btn btn-tabibi">
                <i class="bi bi-plus-circle"></i>
                Add promotion
            </a>
        </div>
    </div>

    @if ($promots->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="bi bi-megaphone"></i>
            </div>
            <h2 class="empty-state__title">There are no promotions yet.</h2>
            <p class="empty-state__copy">Create your first promotion to start using this space effectively.</p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($promots as $promot)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card p-0 overflow-hidden">
                        <img src="{{ asset($promot->image) }}" alt="Promotion image"
                            style="width: 100%; height: 220px; object-fit: cover;">

                        <div class="p-4">
                            <h2 class="record-card__title mb-3">Promotion #{{ $promot->id }}</h2>
                            <p class="record-card__copy mb-4">{{ \Illuminate\Support\Str::limit($promot->information, 140) }}</p>

                            <div class="toolbar-actions">
                                <a href="{{ route('SuperAdmin.Promot.edit', $promot->id) }}" class="outline-button">
                                    <i class="bi bi-pencil-square"></i>
                                    Edit
                                </a>

                                <a href="{{ route('SuperAdmin.Promot.destroy', $promot->id) }}"
                                    class="danger-outline-button"
                                    onclick="return confirm('Are you sure you want to delete this promotion?')">
                                    <i class="bi bi-trash3"></i>
                                    Delete
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
    @endif
@endsection
