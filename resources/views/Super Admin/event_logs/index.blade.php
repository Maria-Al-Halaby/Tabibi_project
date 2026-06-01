@extends('layouts.app')

@section('title', __('Event Logs'))

@section('content')
    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="bi bi-clipboard-data-fill"></i>
                {{ __('Event Logs') }}</span>
            <h1 class="page-title">{{ __('Track platform activity with a clear audit trail.') }}</h1>
            <p class="page-subtitle">
                {{ __('Review who performed each add or delete event, which role they had, what table changed, and the captured
                event parameters.') }}</p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge helper-badge--accent">
                <i class="bi bi-list-check"></i>
                {{ __(':count total logs', ['count' => number_format($totalLogs)]) }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <section class="section-card stat-card">
                <div class="stat-card__top">
                    <div>
                        <div class="stat-card__eyebrow">{{ __('All events') }}</div>
                        <div class="stat-card__value">{{ number_format($totalLogs) }}</div>
                    </div>
                    <span class="stat-card__icon" style="background: linear-gradient(135deg, #0f766e, #2dd4bf);">
                        <i class="bi bi-activity"></i>
                    </span>
                </div>
                <p class="stat-card__description">{{ __('Every recorded add and delete event.') }}</p>
            </section>
        </div>

        <div class="col-sm-6 col-xl-3">
            <section class="section-card stat-card">
                <div class="stat-card__top">
                    <div>
                        <div class="stat-card__eyebrow">{{ __('Added') }}</div>
                        <div class="stat-card__value">{{ number_format($addLogs) }}</div>
                    </div>
                    <span class="stat-card__icon" style="background: linear-gradient(135deg, #2563eb, #60a5fa);">
                        <i class="bi bi-plus-circle-fill"></i>
                    </span>
                </div>
                <p class="stat-card__description">{{ __('Records created across tracked tables.') }}</p>
            </section>
        </div>

        <div class="col-sm-6 col-xl-3">
            <section class="section-card stat-card">
                <div class="stat-card__top">
                    <div>
                        <div class="stat-card__eyebrow">{{ __('Deleted') }}</div>
                        <div class="stat-card__value">{{ number_format($deleteLogs) }}</div>
                    </div>
                    <span class="stat-card__icon" style="background: linear-gradient(135deg, #dc2626, #fb7185);">
                        <i class="bi bi-trash3-fill"></i>
                    </span>
                </div>
                <p class="stat-card__description">{{ __('Records removed by platform users.') }}</p>
            </section>
        </div>

        <div class="col-sm-6 col-xl-3">
            <section class="section-card stat-card">
                <div class="stat-card__top">
                    <div>
                        <div class="stat-card__eyebrow">{{ __('Actors') }}</div>
                        <div class="stat-card__value">{{ number_format($actorCount) }}</div>
                    </div>
                    <span class="stat-card__icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                        <i class="bi bi-people-fill"></i>
                    </span>
                </div>
                <p class="stat-card__description">{{ __('Authenticated users represented in the log.') }}</p>
            </section>
        </div>
    </div>

    <section class="section-card mb-4">
        <form action="{{ route('SuperAdmin.EventLogs.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6 col-xl-3">
                <label for="search" class="field-label">{{ __('Search') }}</label>
                <input type="search" id="search" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="{{ __('Message, actor, or record id') }}">
            </div>

            <div class="col-md-6 col-xl-2">
                <label for="status" class="field-label">{{ __('Status') }}</label>
                <select id="status" name="status" class="form-select">
                    <option value="">{{ __('All statuses') }}</option>
                    <option value="add" @selected(request('status') === 'add')>{{ __('Add') }}</option>
                    <option value="delete" @selected(request('status') === 'delete')>{{ __('Delete') }}</option>
                </select>
            </div>

            <div class="col-md-6 col-xl-3">
                <label for="table_name" class="field-label">{{ __('Table') }}</label>
                <select id="table_name" name="table_name" class="form-select">
                    <option value="">{{ __('All tables') }}</option>
                    @foreach ($tables as $table)
                        <option value="{{ $table }}" @selected(request('table_name') === $table)>{{ $table }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 col-xl-2">
                <label for="user_role" class="field-label">{{ __('Role') }}</label>
                <select id="user_role" name="user_role" class="form-select">
                    <option value="">{{ __('All roles') }}</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(request('user_role') === $role)>{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-2">
                <div class="toolbar-actions justify-content-xl-end">
                    <button type="submit" class="btn btn-tabibi">
                        <i class="bi bi-funnel-fill"></i>
                        {{ __('Filter') }}</button>

                    <a href="{{ route('SuperAdmin.EventLogs.index') }}" class="ghost-button">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        {{ __('Reset') }}</a>
                </div>
            </div>
        </form>
    </section>

    @if ($logs->isEmpty())<section class="section-card empty-state">
            <div class="empty-state__icon">
                <i class="bi bi-clipboard-x"></i>
            </div>
            <h2 class="empty-state__title">{{ __('No event logs match the current filters.') }}</h2>
            <p class="empty-state__copy">{{ __('Adjust the filters to inspect more platform activity.') }}</p>
        </section>
    @else
        <section class="section-card">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h2 class="section-heading">{{ __('Audit trail') }}</h2>
                    <p class="section-copy">{{ __('Newest events are shown first.') }}</p>
                </div>
                <span class="helper-badge">
                    <i class="bi bi-clock-history"></i>
                    {{ __('Showing :first-:last of :total', [
                        'first' => $logs->firstItem(),
                        'last' => $logs->lastItem(),
                        'total' => $logs->total(),
                    ]) }}
                </span>
            </div>

            <div class="table-shell">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Time') }}</th>
                                <th>{{ __('Actor') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Event') }}</th>
                                <th>{{ __('Table') }}</th>
                                <th>{{ __('Record') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th>{{ __('Parameters') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $log->created_at?->translatedFormat('M d, Y') }}</div>
                                        <div class="record-card__meta">{{ $log->created_at?->translatedFormat('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $log->user_name ?? __('System') }}</div>
                                        <div class="record-card__meta">{{ $log->user?->email ?? __('No account linked') }}</div>
                                    </td>
                                    <td>
                                        <span class="status-pill status-pill--info">
                                            {{ $log->user_role ?? __('No role') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($log->status === 'add')
                                            <span class="status-pill status-pill--success">
                                                <i class="bi bi-plus-circle-fill"></i>
                                                {{ __('Add') }}</span>
                                        @else
                                            <span class="status-pill status-pill--danger">
                                                <i class="bi bi-trash3-fill"></i>
                                                {{ __('Delete') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $log->table_name }}</div>
                                        <div class="record-card__meta">{{ $log->model_type ? class_basename($log->model_type) : __('Model') }}</div>
                                    </td>
                                    <td>#{{ $log->model_id ?? '-' }}</td>
                                    <td style="min-width: 260px;">
                                        {{ __(':actor:role :action :record in :table.', [
                                            'actor' => $log->user_name ?? __('System'),
                                            'role' => $log->user_role ? ' ('.$log->user_role.')' : '',
                                            'action' => $log->status === 'add' ? __('added') : __('deleted'),
                                            'record' => $log->model_id ? __('record #:id', ['id' => $log->model_id]) : __('a record'),
                                            'table' => $log->table_name,
                                        ]) }}
                                    </td>
                                    <td style="min-width: 280px;">
                                        <details class="log-details">
                                            <summary>{{ __('View parameters') }}</summary>
                                            <pre class="log-parameters">{{ json_encode($log->parameters, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </details>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </section>
    @endif
@endsection

@push('styles')
    <style>
        .log-details summary {
            cursor: pointer;
            color: var(--tabibi-primary-color);
            font-weight: 800;
        }

        .log-parameters {
            max-height: 240px;
            overflow: auto;
            margin: 0.75rem 0 0;
            padding: 1rem;
            border-radius: 16px;
            background: #0f172a;
            color: #e2e8f0;
            font-size: 0.82rem;
            line-height: 1.55;
            white-space: pre-wrap;
        }
    </style>
@endpush
