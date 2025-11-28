{{-- @extends('layouts.app')

@section('title', 'clinic centers')

@section('content')
    @if (session('message'))
        <h3 style="color: red;">{{ session('message') }}</h3>
    @endif
    <a href="{{ route('SuperAdmin.clinicCenter.create') }}">add new clinic center</a>
    @forelse ($clinicCenters as $clinic_center)
        <p>name : {{ $clinic_center->name }}</p>
        <p>eamil : {{ $clinic_center->user->email }}</p>
        <p>phone : {{ $clinic_center->user->phone }}</p>
        <p>address : {{ $clinic_center->address }}</p>
        <p>is active : {{ $clinic_center->is_active }}</p>
        <a href="{{ route('SuperAdmin.clinic_center.edit', $clinic_center->id) }}">update </a>
        <form action="{{ route('SuperAdmin.clinic_center.destroy', $clinic_center->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" value="delete">
        </form>
    @empty
        <h2>there is'nt clinic centers yet!!</h2>
    @endforelse

@endsection
 --}}


@extends('layouts.app')

@section('title', 'clinic centers')

@section('content')
    <!-- تنسيق مخصص للبطاقات والألوان -->
    <style>
        :root {
            --main-color: #008080;
            /* اللون الأخضر المائي */
            --danger-color: #dc3545;
            /* لون الحذف */
        }

        /* تنسيق زر "إضافة جديد" */
        .btn-add-new {
            background-color: var(--main-color);
            border-color: var(--main-color);
            border-radius: 12px;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            text-decoration: none;
            display: block;
            /* لجعله يأخذ عرض الحاوية */
            width: 100%;
            text-align: center;
            margin-bottom: 25px;
            transition: background-color 0.3s;
        }

        .btn-add-new:hover {
            background-color: #006666;
            color: white;
        }

        /* تنسيق بطاقة المركز الطبي */
        .clinic-card {
            background-color: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            padding: 20px;
        }

        /* تنسيق العنوان الرئيسي */
        .clinic-card h5 {
            color: var(--main-color);
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* تنسيق بيانات المركز (الفقرات) */
        .clinic-card p {
            margin-bottom: 5px;
            font-size: 0.95rem;
            color: #495057;
        }

        /* تنسيق الأيقونة النشطة/غير النشطة */
        .status-icon {
            font-size: 1.1rem;
            margin-right: 5px;
        }

        .active-text {
            color: var(--main-color);
            font-weight: 500;
        }

        .inactive-text {
            color: var(--danger-color);
            font-weight: 500;
        }

        /* تنسيق أزرار الإجراءات */
        .action-button {
            border: none;
            background: none;
            padding: 0;
            margin-left: 15px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: color 0.2s;
        }
    </style>

    <div class="container py-4">

        <!-- عنوان الصفحة -->
        <h3 class="mb-4 fw-bold text-center" style="color: var(--main-color);">
            Clinic Centers Management
        </h3>

        <!-- عرض رسائل الجلسة (Session Message) -->
        @if (session('message'))
            <div class="alert alert-success text-center rounded-3 mb-4" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <!-- زر "إضافة مركز جديد" -->
        <a href="{{ route('SuperAdmin.clinicCenter.create') }}" class="btn-add-new">
            <i class="bi bi-plus-circle me-2"></i> Add New Clinic Center
        </a>

        <!-- حلقة العرض للمراكز الطبية -->
        @forelse ($clinicCenters as $clinic_center)
            <div class="clinic-card">

                <!-- اسم المركز -->
                <h5 class="d-flex justify-content-between align-items-start">
                    {{ $clinic_center->name }}

                    <!-- أزرار الإجراءات (تعديل وحذف) -->
                    <div class="d-flex align-items-center">

                        <!-- زر التعديل (Update) -->
                        <a href="{{ route('SuperAdmin.clinic_center.edit', $clinic_center->id) }}"
                            class="action-button text-primary">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <!-- نموذج الحذف (Delete) -->
                        <form action="{{ route('SuperAdmin.clinic_center.destroy', $clinic_center->id) }}" method="POST"
                            class="d-inline-block m-0">
                            @csrf
                            @method('DELETE')
                            <!-- تم استبدال input بـ button مع أيقونة -->
                            <button type="submit" class="action-button text-danger" title="Delete Center"
                                onclick="return confirm('Are you sure you want to delete {{ $clinic_center->name }}?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </div>
                </h5>

                <!-- البيانات الأخرى -->
                <p>
                    <i class="bi bi-envelope me-2 text-muted"></i>
                    <span class="fw-semibold">Email:</span> {{ $clinic_center->user->email }}
                </p>
                <p>
                    <i class="bi bi-phone me-2 text-muted"></i>
                    <span class="fw-semibold">Phone:</span> {{ $clinic_center->user->phone }}
                </p>
                <p>
                    <i class="bi bi-geo-alt-fill me-2 text-muted"></i>
                    <span class="fw-semibold">Address:</span> {{ $clinic_center->address }}
                </p>

                <!-- حالة التفعيل (Is Active) -->
                <p class="mt-2">
                    @if ($clinic_center->is_active)
                        <i class="bi bi-check-circle-fill status-icon active-text"></i>
                        <span class="active-text">Active</span>
                    @else
                        <i class="bi bi-x-circle-fill status-icon inactive-text"></i>
                        <span class="inactive-text">Inactive</span>
                    @endif
                </p>

            </div>
        @empty
            <!-- رسالة في حال عدم وجود مراكز -->
            <div class="alert alert-info text-center mt-5" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <h2>There are no clinic centers yet!</h2>
                <p class="mb-0">Please use the "Add New Clinic Center" button above to get started.</p>
            </div>
        @endforelse

    </div>

@endsection
