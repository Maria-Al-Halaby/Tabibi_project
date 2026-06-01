@extends('layouts.app')

@section('title', __('AI Usage Controls'))

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-cpu-fill"></i>
                {{ __('AI Limits') }}</span>
            <h1 class="page-title">{{ __('Control AI feature quotas and user usage.') }}</h1>
            <p class="page-subtitle">
                {{ __('Tune patient AI access limits by feature, then review and adjust individual usage counters when support needs it.') }}</p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="bi bi-sliders"></i>
                {{ __(':count features', ['count' => number_format($limits->count())]) }}
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="bi bi-activity"></i>
                {{ __(':count usage records', ['count' => number_format($usages->total())]) }}
            </span>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('message') }}</div>
    @endif

    @if ($errors->any())<div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="toolbar-row">
        <div>
            <h2 class="section-heading">{{ __('Feature limits') }}</h2>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @foreach ($limits as $featureType => $limit)
            <div class="col-md-6 col-xl-4">
                <section class="record-card h-100">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <h2 class="record-card__title mb-1">
                                {{ \Illuminate\Support\Str::headline($featureType) }}
                            </h2>
                            <p class="record-card__copy mb-0">
                                Default: {{ number_format($limit['default_limit']) }} / {{ $limit['default_period'] }}
                            </p>
                        </div>
                        @if ($limit['is_customized'])
                            <span class="status-pill status-pill--success">{{ __('Custom') }}</span>
                        @else
                            <span class="status-pill">{{ __('Default') }}</span>
                        @endif
                    </div>

                    <form action="{{ route('SuperAdmin.AiLimits.updateLimit', $featureType) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-6">
                            <label class="field-label">{{ __('Limit') }}</label>
                            <input type="number"
                                name="limit"
                                min="0"
                                max="1000000"
                                class="form-control"
                                value="{{ old("limits.$featureType.limit", $limit['limit']) }}"
                                required>
                        </div>

                        <div class="col-6">
                            <label class="field-label">{{ __('Period') }}</label>
                            <select name="period" class="form-select" required>
                                @foreach (['day' => 'Daily', 'week' => 'Weekly', 'month' => 'Monthly'] as $period => $label)
                                    <option value="{{ $period }}" @selected(old("limits.$featureType.period", $limit['period']) === $period)>
                                        {{ __($label) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-tabibi">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('Save limit') }}</button>
                        </div>
                    </form>
                </section>
            </div>
        @endforeach
    </div>

    <section class="section-card">
        <div class="toolbar-row mb-4">
            <div>
                <h2 class="section-heading">{{ __('User usage') }}</h2>
                <p class="section-copy">{{ __('Filter usage records, then change the consumed count for a patient and feature period.') }}</p>
            </div>

            <form action="{{ route('SuperAdmin.AiLimits.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                <input type="search"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    style="width: 260px"
                    placeholder="{{ __('Search patient') }}">

                <select name="feature_type" class="form-select" style="width: 220px">
                    <option value="">{{ __('All features') }}</option>
                    @foreach ($featureTypes as $featureType)
                        <option value="{{ $featureType }}" @selected(request('feature_type') === $featureType)>
                            {{ \Illuminate\Support\Str::headline($featureType) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="outline-button">
                    <i class="bi bi-search"></i>
                    {{ __('Filter') }}</button>
            </form>
        </div>

        @if ($usages->isEmpty())<div class="empty-state">
                <div class="empty-state__icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <h2 class="empty-state__title">{{ __('No AI usage records found.') }}</h2>
                <p class="empty-state__copy mb-0">{{ __('Usage rows are created when a patient uses an AI feature.') }}</p>
            </div>
        @else
            <div class="table-shell">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('Patient') }}</th>
                            <th>{{ __('Feature') }}</th>
                            <th>{{ __('Used') }}</th>
                            <th>{{ __('Current limit') }}</th>
                            <th>{{ __('Period') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usages as $usage)
                            @php
                                $patientUser = $usage->patient?->user;
                                $patientName = trim(($patientUser?->name ?? '') . ' ' . ($patientUser?->last_name ?? ''));
                                $patientName = $patientName !== '' ? $patientName : __('Patient #:id', ['id' => $usage->patient_id]);
                                $featureLimit = $limits->get($usage->feature_type);
                                $currentLimit = $featureLimit['limit'] ?? null;
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $patientName }}</div>
                                    <div class="text-muted small">{{ $patientUser?->email ?? __('No email') }}</div>
                                </td>
                                <td>{{ \Illuminate\Support\Str::headline($usage->feature_type) }}</td>
                                <td style="min-width: 150px">
                                    <form id="usage-form-{{ $usage->id }}"
                                        action="{{ route('SuperAdmin.AiLimits.updateUsage', $usage->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="feature_type" value="{{ request('feature_type') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <input type="hidden" name="page" value="{{ request('page') }}">
                                        <input type="number"
                                            name="used_count"
                                            min="0"
                                            max="1000000"
                                            class="form-control"
                                            value="{{ old('used_count', $usage->used_count) }}"
                                            required>
                                    </form>
                                </td>
                                <td>{{ $currentLimit !== null ? number_format($currentLimit) : __('Unknown feature') }}</td>
                                <td>
                                    <div>{{ $usage->period_start?->format('Y-m-d H:i') }}</div>
                                    <div class="text-muted small">{{ __('to :date', ['date' => $usage->period_end?->format('Y-m-d H:i')]) }}</div>
                                </td>
                                <td class="text-end">
                                    <button type="submit" form="usage-form-{{ $usage->id }}" class="outline-button">
                                        <i class="bi bi-save"></i>
                                        {{ __('Save') }}</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $usages->links() }}
            </div>
        @endif
    </section>
@endsection
