{{-- @extends('layouts.app')

@section('title', 'add new specialization')


@section('content')
    <form action="{{ route('SuperAdmin.specialization.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="enter specialization name">
        <input type="submit" value="send">
    </form>
@endsection
 --}}

@extends('layouts.app')

@section('title', 'add new specialization')


@section('content')
    <!-- تنسيق مخصص لضمان شكل الحقول المستديرة والزر الموحد -->
    <style>
        :root {
            --main-color: #008080;
            /* اللون الأخضر المائي */
        }

        /* تنسيق حقول الإدخال */
        .form-control-custom {
            border: 1px solid #ced4da;
            /* حدود خفيفة */
            border-radius: 12px;
            /* حواف مستديرة */
            padding: 15px 15px;
            background-color: white;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control-custom:focus {
            border-color: var(--main-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 128, 128, 0.25);
        }

        /* تنسيق زر الإرسال */
        .btn-main {
            background-color: var(--main-color);
            border-color: var(--main-color);
            border-radius: 12px;
            color: white;
            padding: 12px 0;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }

        .btn-main:hover {
            background-color: #006666;
            border-color: #006666;
            color: white;
        }
    </style>

    <div class="container py-4">

        <!-- عنوان الصفحة -->
        <h3 class="mb-5 fw-bold text-center" style="color: var(--main-color);">
            <i class="bi bi-tag-fill me-2"></i> Add New Specialization
        </h3>

        <form action="{{ route('SuperAdmin.specialization.store') }}" method="POST">
            @csrf

            <!-- حقل اسم الاختصاص (Name) -->
            <div class="mb-4">
                <input type="text" name="name"
                    class="form-control form-control-custom @error('name') is-invalid @enderror"
                    placeholder="enter specialization name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- زر الإرسال (Send) -->
            <div class="d-grid">
                <input type="submit" value="Send" class="btn btn-main">
            </div>

        </form>

        <!-- عرض الأخطاء العامة إن وجدت -->
        @if ($errors->any() && !$errors->has('name'))
            <div class="alert alert-danger text-center small mt-4" role="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

    </div>

@endsection
