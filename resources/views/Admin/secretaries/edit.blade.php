@extends('layouts.admin_app')

@section('title', 'Edit Secretary')

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-user-pen"></i>
                Secretary Management
            </span>
            <h1 class="page-title">Update secretary details without leaving the dashboard flow.</h1>
            <p class="page-subtitle">
                Keep {{ trim(($user->name ?? '') . ' ' . ($user->last_name ?? '')) }} ready for the front-desk workflow.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-hospital"></i>
                {{ $center->name }}
            </span>
        </div>
    </div>

    <section class="section-card form-panel">
        <div class="toolbar-row mb-4">
            <div>
                <h2 class="section-heading">Edit secretary account</h2>
                <p class="section-copy">Refresh contact details or rotate credentials when the desk team changes.</p>
            </div>

            <a href="{{ route('Admin.Secretary.index') }}" class="ghost-button">
                <i class="fas fa-arrow-left"></i>
                Back to secretary team
            </a>
        </div>

        <form action="{{ route('Admin.Secretary.update', $user->id) }}" method="POST" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label for="name" class="field-label">First name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="last_name" class="field-label">Last name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                    class="form-control">
            </div>

            <div class="col-md-6">
                <label for="email" class="field-label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="phone" class="field-label">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="form-control" required>
            </div>

            <div class="col-12">
                <label for="password" class="field-label">New password</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Leave blank to keep the current password">
                <p class="field-note">Only enter a new password if this secretary should use a new login.</p>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-tabibi">
                    <i class="fas fa-floppy-disk me-2"></i>Update secretary
                </button>
            </div>
        </form>
    </section>
@endsection
