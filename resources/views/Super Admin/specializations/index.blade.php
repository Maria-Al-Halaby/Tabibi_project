@extends('layouts.app')

@section('title', 'Specializations')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-grid-1x2-fill"></i>
                Specializations
            </span>
            <h1 class="page-title">Keep medical categories organized and easy to manage.</h1>
            <p class="page-subtitle">
                This page is now cleaner to scan, with clearer actions for editing taxonomy across the whole platform.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-collection-fill"></i>
                {{ number_format($specializations->count()) }} specializations
            </span>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('message') }}</div>
    @endif

    <div class="toolbar-row">
        <div>
            <h2 class="section-heading">Specialization directory</h2>
            <p class="section-copy">Each card includes the core information and the primary management actions.</p>
        </div>

        <div class="toolbar-actions">
            <a href="{{ route('SuperAdmin.specialization.create') }}" class="btn btn-tabibi">
                <i class="bi bi-plus-circle"></i>
                Add specialization
            </a>
        </div>
    </div>

    @if ($specializations->isEmpty())
        <section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="bi bi-grid"></i>
            </div>
            <h2 class="empty-state__title">No specializations have been added yet.</h2>
            <p class="empty-state__copy">Create the first specialization to start organizing doctor records cleanly.</p>
        </section>
    @else
        <div class="row g-4">
            @foreach ($specializations as $specialization)
                <div class="col-md-6 col-xl-4">
                    <section class="record-card">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            @if (!empty($specialization->image))
                                <img src="{{ $specialization->image }}" alt="{{ $specialization->name }}"
                                    class="avatar-circle">
                            @else
                                <span class="empty-state__icon mb-0" style="width:72px;height:72px;">
                                    <i class="bi bi-patch-check-fill"></i>
                                </span>
                            @endif

                            <div>
                                <h2 class="record-card__title mb-1">{{ $specialization->name }}</h2>
                                <p class="record-card__copy">Platform taxonomy item</p>
                            </div>
                        </div>

                        <div class="toolbar-actions">
                            <a href="{{ route('SuperAdmin.specialization.edit', $specialization->id) }}"
                                class="outline-button">
                                <i class="bi bi-pencil-square"></i>
                                Edit
                            </a>

                            <form action="{{ route('SuperAdmin.specialization.destroy', $specialization->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete specialization {{ $specialization->name }}?')">
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
