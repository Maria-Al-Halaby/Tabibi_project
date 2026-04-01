@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
    <section class="section-card empty-state" style="min-height: 70vh; display: flex; flex-direction: column; justify-content: center;">
        <div class="empty-state__icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <span class="eyebrow mx-auto mb-3">
            <i class="bi bi-question-circle-fill"></i>
            Error 404
        </span>
        <h1 class="page-title mb-3">This super-admin page could not be found.</h1>
        <p class="empty-state__copy">
            The route may be incorrect, the page may have moved, or the requested screen is not available anymore.
        </p>
        <div class="toolbar-actions justify-content-center">
            <a href="{{ route('SuperAdmin.Detials.index') }}" class="btn btn-tabibi">
                <i class="bi bi-house-fill"></i>
                Back to dashboard
            </a>
        </div>
    </section>
@endsection
