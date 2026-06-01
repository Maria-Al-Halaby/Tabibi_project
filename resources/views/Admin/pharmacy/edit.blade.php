@extends('layouts.admin_app')

@section('title', __('Edit Pharmacist'))

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-user-pen"></i>
                {{ __('Edit Pharmacist') }}</span>
            <h1 class="page-title">{{ __('Update pharmacist details without leaving the dashboard flow.') }}</h1>
            <p class="page-subtitle">
                {{ __('Contact information and password updates now follow the same form pattern used throughout the admin
                screens.') }}</p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hospital"></i>
                {{ $center->name }}
            </span>
        </div>
    </div>

    <section class="section-card form-panel">
        <div class="toolbar-row">
            <div>
                <h2 class="section-heading">{{ __('Pharmacist profile') }}</h2>
                <p class="section-copy">{{ __('Change the information below, then save the updated profile.') }}</p>
            </div>

            <div class="toolbar-actions">
                <a href="{{ route('Admin.Pharmacy.index') }}" class="ghost-button">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to pharmacy team') }}</a>
            </div>
        </div>

        <form action="{{ route('Admin.Pharmacy.update', $user->id) }}" method="POST" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label for="name" class="field-label">{{ __('First name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control"
                    placeholder="{{ __('Enter first name') }}" required>
            </div>

            <div class="col-md-6">
                <label for="last_name" class="field-label">{{ __('Last name') }}</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                    class="form-control"
                    placeholder="{{ __('Enter last name') }}">
            </div>

            <div class="col-md-6">
                <label for="email" class="field-label">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    class="form-control"
                    placeholder="{{ __('Enter email address') }}" required>
            </div>

            <div class="col-md-6">
                <label for="phone" class="field-label">{{ __('Phone') }}</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="form-control"
                    placeholder="{{ __('Enter phone number') }}" required>
            </div>

            <div class="col-12">
                <label for="password" class="field-label">{{ __('New password') }}</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="{{ __('Leave empty to keep the current password') }}">
                <p class="field-note">{{ __('Only enter a new password if this pharmacist should use a new login.') }}</p>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-tabibi">
                    <i class="fas fa-floppy-disk me-2"></i>{{ __('Update pharmacist') }}</button>
            </div>
        </form>
    </section>
@endsection
