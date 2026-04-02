@extends('layouts.app')

@section('title', 'Doctor Moderation')

@section('content')
    <section class="section-card empty-state">
        <div class="empty-state__icon">
            <i class="bi bi-shield-exclamation"></i>
        </div>
        <h1 class="empty-state__title">Doctor moderation action</h1>
        <p class="empty-state__copy">
            This placeholder screen now matches the refreshed dashboard styling. The main moderation workflow remains on
            the doctor ratings page.
        </p>
        <a href="{{ route('doctor_ratings.index') }}" class="ghost-button">
            <i class="bi bi-arrow-left"></i>
            Back to ratings
        </a>
    </section>
@endsection
