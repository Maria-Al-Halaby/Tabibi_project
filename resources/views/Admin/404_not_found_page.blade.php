@extends('layouts.admin_app')

@section('title', '404 - Page Not Found')

@section('content')
    <section class="section-card empty-state" style="min-height: 70vh; display: flex; flex-direction: column; justify-content: center;">
        <div class="empty-state__icon">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <span class="eyebrow mx-auto mb-3">
            <i class="fas fa-circle-question"></i>
            Error 404
        </span>
        <h1 class="page-title mb-3">This admin page could not be found.</h1>
        <p class="empty-state__copy">
            The page may have moved, the URL may be incorrect, or the route may no longer exist.
        </p>
        <div class="toolbar-actions justify-content-center">
            <a href="{{ route('Admin.index') }}" class="btn btn-tabibi">
                <i class="fas fa-house"></i>
                Back to dashboard
            </a>
        </div>
    </section>
@endsection
