{{-- @extends('layouts.app')

@section('title', 'doctors ratings')

@section('content')
    <div class="container">

        <h2>تقييمات الأطباء</h2>

        
        <form method="GET" class="mb-3">
            <label>اعتبر سلبي إذا التقييم ≤</label>
            <input type="number" name="negative_max" value="{{ $negativeMaxStars }}" min="1" max="5">

            <label class="ms-2">حد التنبيه (عدد السلبيات ≥)</label>
            <input type="number" name="min_negative" value="{{ $minNegativeCount }}" min="1">

            <button class="btn btn-primary ms-2">تطبيق</button>
        </form>

        <hr>

        <h4>ملخص حسب الطبيب</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>الطبيب</th>
                    <th>متوسط</th>
                    <th>عدد التقييمات</th>
                    <th>عدد السلبيات</th>
                    <th>حالة</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($doctors as $d)
                    @php $isFlagged = $d->negative_ratings_count >= $minNegativeCount; @endphp
                    <tr @if ($isFlagged) style="background:#fff3cd;" @endif>
                        <td>{{ $d->user->name ?? 'Doctor#' . $d->id }}</td>
                        <td>{{ number_format((float) $d->avg_rating, 2) }}</td>
                        <td>{{ $d->ratings_count }}</td>
                        <td>
                            <strong>{{ $d->negative_ratings_count }}</strong>
                            @if ($isFlagged)
                                <span class="badge bg-warning text-dark">تحذير</span>
                            @endif
                        </td>
                        <td>{{ $d->is_active ?? 1 ? 'نشط' : 'موقوف' }}</td>
                        <td>
                            <form method="POST" action="{{ route('doctors.deactivate', $d) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger">إيقاف</button>
                            </form>

                            <form method="POST" action="{{ route('doctors.destroy', $d) }}"
                                style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('متأكد من حذف الطبيب؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $doctors->links() }}

        <hr>

        <h4>كل التقييمات (تفاصيل)</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الطبيب</th>
                    <th>المريض</th>
                    <th>التقييم</th>
                    <th>التعليق</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ratings as $r)
                    <tr>
                        <td>{{ $r->doctor?->user?->name ?? 'Doctor#' . $r->doctor_id }}</td>
                        <td>{{ $r->patient?->user?->name ?? 'Patient#' . $r->patient_id }}</td>
                        <td>{{ $r->rating }}</td>
                        <td>{{ $r->comment }}</td>
                        <td>{{ optional($r->created_at)->toDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $ratings->links() }}

    </div>
@endsection --}}

@extends('layouts.app')

@section('title', 'Doctors Ratings')

@section('content')
    <style>
        :root {
            --main-color: #008080;
            --warning-bg: #fff3cd;
        }

        /* تنسيق العناوين */
        .section-title {
            color: var(--main-color);
            font-weight: 700;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        /* تنسيق الفلتر */
        .filter-card {
            background: #f8f9fa;
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 30px;
        }

        .form-control-custom {
            border-radius: 10px;
            border: 1px solid #ced4da;
            padding: 8px 12px;
        }

        /* تنسيق الجداول */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .table thead {
            background-color: var(--main-color);
            color: white;
        }

        .table th {
            font-weight: 500;
            border: none;
            padding: 15px;
        }

        .table td {
            vertical-align: middle;
            padding: 12px 15px;
        }

        .badge-custom {
            border-radius: 8px;
            padding: 6px 12px;
        }

        .btn-action {
            border-radius: 8px;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .flagged-row {
            background-color: var(--warning-bg) !important;
        }
    </style>

    <div class="container py-4">

        <h3 class="section-title">
            <i class="bi bi-star-fill me-2"></i> Doctors Performance & Ratings
        </h3>

   {{--      <div class="filter-card shadow-sm">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">Negative if stars ≤</label>
                    <input type="number" name="negative_max" class="form-control form-control-custom" 
                           value="{{ $negativeMaxStars }}" min="1" max="5">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">Alert Threshold (Negatives ≥)</label>
                    <input type="number" name="min_negative" class="form-control form-control-custom" 
                           value="{{ $minNegativeCount }}" min="1">
                </div>

                <div class="col-md-4">
                    <button class="btn btn-main w-100 py-2 shadow-sm" style="background-color: var(--main-color); color: white; border-radius: 10px;">
                        <i class="bi bi-funnel-fill me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div> --}}

        <h4 class="fw-bold mb-3" style="color: #444;">Doctor Summary</h4>
        <div class="table-container">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Doctor Name</th>
                        <th>Avg. Rating</th>
                        <th>Total Ratings</th>
                        <th>Negatives</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doctors as $d)
                        @php $isFlagged = $d->negative_ratings_count >= $minNegativeCount; @endphp
                        <tr class="{{ $isFlagged ? 'flagged-row' : '' }}">
                            <td class="fw-bold text-dark">{{ $d->user->name ?? 'Doctor #' . $d->id }}</td>
                            <td>
                                <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                {{ number_format((float) $d->avg_rating, 2) }}
                            </td>
                            <td>{{ $d->ratings_count }}</td>
                            <td>
                                <span class="badge {{ $isFlagged ? 'bg-danger' : 'bg-secondary' }} badge-custom">
                                    {{ $d->negative_ratings_count }}
                                </span>
                                @if ($isFlagged)
                                    <span class="ms-1 text-danger small fw-bold mt-1 d-block"><i class="bi bi-exclamation-triangle"></i> Warning</span>
                                @endif
                            </td>
                            <td>
                                @if($d->is_active ?? 1)
                                    <span class="badge bg-success badge-custom">Active</span>
                                @else
                                    <span class="badge bg-dark badge-custom">Suspended</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <form method="POST" action="{{ route('doctors.deactivate', $d) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-dark btn-action me-1">Suspend</button>
                                    </form>

                                    <form method="POST" action="{{ route('doctors.destroy', $d) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger btn-action"
                                                onclick="return confirm('Are you sure you want to delete this doctor?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mb-5">
            {{ $doctors->links() }}
        </div>

        <h4 class="fw-bold mb-3" style="color: #444;">Detailed Reviews</h4>
        <div class="table-container shadow-sm">
            <table class="table table-hover mb-0">
                <thead class="bg-light text-dark">
                    <tr style="background-color: #f1f1f1; color: #333 !important;">
                        <th style="color: #333">Doctor</th>
                        <th style="color: #333">Patient</th>
                        <th style="color: #333 text-center">Stars</th>
                        <th style="color: #333">Comment</th>
                        <th style="color: #333">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ratings as $r)
                        <tr>
                            <td class="small fw-bold">{{ $r->doctor?->user?->name ?? 'Doctor#' . $r->doctor_id }}</td>
                            <td class="small">{{ $r->patient?->user?->name ?? 'Patient#' . $r->patient_id }}</td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $r->rating }} <i class="bi bi-star-fill"></i></span>
                            </td>
                            <td class="text-muted italic small" style="max-width: 300px;">"{{ $r->comment }}"</td>
                            <td class="small text-secondary">{{ optional($r->created_at)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $ratings->links() }}
        </div>

    </div>
@endsection
