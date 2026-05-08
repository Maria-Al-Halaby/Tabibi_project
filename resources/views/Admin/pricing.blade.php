@extends('layouts.admin_app')

@section('title', 'Pricing Management')

@section('content')
    @php
        $configuredLabPrices = $labTests->whereNotNull('price')->count();
        $configuredRadiologyPrices = $images->whereNotNull('price')->count();
    @endphp

    <div class="page-header">
        <div>
            <span class="eyebrow">
                <i class="fas fa-tags"></i>
                Pricing Management
            </span>
            <h1 class="page-title">Keep service pricing clear, current, and easy to update.</h1>
            <p class="page-subtitle">
                Lab tests and radiology services now use the same dashboard flow as the rest of the admin area, so price
                updates are easier to scan and quicker to save.
            </p>
        </div>

        <div class="helper-badges">
            <span class="helper-badge">
                <i class="fas fa-flask-vial"></i>
                {{ number_format($configuredLabPrices) }}/{{ number_format($labTests->count()) }} lab prices set
            </span>
            <span class="helper-badge helper-badge--accent">
                <i class="fas fa-x-ray"></i>
                {{ number_format($configuredRadiologyPrices) }}/{{ number_format($images->count()) }} radiology prices set
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <section class="section-card form-panel">
                <div class="toolbar-row">
                    <div>
                        <h2 class="section-heading">Lab test pricing</h2>
                        <p class="section-copy">Adjust the center-specific price for each test without leaving the table.</p>
                    </div>
                </div>

                @if ($labTests->isEmpty())
                    <div class="empty-state px-0 pb-0">
                        <div class="empty-state__icon">
                            <i class="fas fa-flask-vial"></i>
                        </div>
                        <h2 class="empty-state__title">No lab tests are available yet.</h2>
                        <p class="empty-state__copy mb-0">Add lab tests first, then come back here to assign prices.</p>
                    </div>
                @else
                    <div class="table-shell">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Test</th>
                                        <th>Current price</th>
                                        <th>Update price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($labTests as $test)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $test['name'] }}</div>
                                                <div class="record-card__meta">Lab service</div>
                                            </td>
                                            <td>
                                                @if (!is_null($test['price']))
                                                    <span class="status-pill status-pill--info">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                        ${{ number_format($test['price'], 2) }}
                                                    </span>
                                                @else
                                                    <span class="status-pill status-pill--warning">
                                                        <i class="fas fa-hourglass-half"></i>
                                                        Not set
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('Admin.Pricing.lab') }}" method="POST"
                                                    class="d-flex flex-column flex-md-row gap-2 align-items-stretch align-items-md-center">
                                                    @csrf
                                                    <input type="hidden" name="lab_test_id" value="{{ $test['id'] }}">
                                                    <input type="number" name="price" step="0.01" min="0"
                                                        value="{{ old('lab_test_id') == $test['id'] ? old('price') : $test['price'] }}"
                                                        class="form-control"
                                                        placeholder="Enter price">
                                                    <button type="submit" class="btn btn-tabibi">
                                                        <i class="fas fa-floppy-disk me-2"></i>Save
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </section>
        </div>

        <div class="col-12">
            <section class="section-card form-panel">
                <div class="toolbar-row">
                    <div>
                        <h2 class="section-heading">Radiology pricing</h2>
                        <p class="section-copy">Set imaging prices using the same quick-save workflow.</p>
                    </div>
                </div>

                @if ($images->isEmpty())
                    <div class="empty-state px-0 pb-0">
                        <div class="empty-state__icon">
                            <i class="fas fa-x-ray"></i>
                        </div>
                        <h2 class="empty-state__title">No radiology services are available yet.</h2>
                        <p class="empty-state__copy mb-0">Add medical image types first, then assign prices here.</p>
                    </div>
                @else
                    <div class="table-shell">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Image type</th>
                                        <th>Current price</th>
                                        <th>Update price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($images as $img)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $img['name'] }}</div>
                                                <div class="record-card__meta">Radiology service</div>
                                            </td>
                                            <td>
                                                @if (!is_null($img['price']))
                                                    <span class="status-pill status-pill--info">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                        ${{ number_format($img['price'], 2) }}
                                                    </span>
                                                @else
                                                    <span class="status-pill status-pill--warning">
                                                        <i class="fas fa-hourglass-half"></i>
                                                        Not set
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('Admin.Pricing.radiology') }}" method="POST"
                                                    class="d-flex flex-column flex-md-row gap-2 align-items-stretch align-items-md-center">
                                                    @csrf
                                                    <input type="hidden" name="type_of_medical_image_id"
                                                        value="{{ $img['id'] }}">
                                                    <input type="number" name="price" step="0.01" min="0"
                                                        value="{{ old('type_of_medical_image_id') == $img['id'] ? old('price') : $img['price'] }}"
                                                        class="form-control"
                                                        placeholder="Enter price">
                                                    <button type="submit" class="btn btn-tabibi">
                                                        <i class="fas fa-floppy-disk me-2"></i>Save
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
