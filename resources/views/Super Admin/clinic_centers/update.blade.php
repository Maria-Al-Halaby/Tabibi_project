{{-- @extends('layouts.app')

@section('title', 'update clinic center')


@section('content')
    <form action="{{ route('SuperAdmin.clinic_center.update', $clinicCenter->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" placeholder="enter clinic center name" value="{{ $clinicCenter->name }}">
        <input type="email" name="email" placeholder="enter clinic center email" value="{{ $clinicCenter->user->email }}">
        <input type="text" name="phone" placeholder="enter clinic center phone"
            value="{{ $clinicCenter->user->phone }}">
        <input type="text" name="address" placeholder="enter clinic center address" value="{{ $clinicCenter->address }}">
        <input type="password" name="password" placeholder="enter clinic center password">
        <input type="submit" value="update">
    </form>

@endsection
 --}}


@extends('layouts.app')

@section('title', 'update clinic center')


@section('content')
    <!-- تنسيق مخصص لضمان شكل الحقول المستديرة والزر الموحد -->
    <style>
        :root {
            --main-color: #008080;
            /* اللون الأخضر المائي */
        }

        /* تنسيق حقول الإدخال لتشبه التصميم */
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
        <h3 class="mb-4 fw-bold text-center" style="color: var(--main-color);">
            <i class="bi bi-pencil-square me-2"></i> Update Clinic Center
        </h3>

        <form action="{{ route('SuperAdmin.clinic_center.update', $clinicCenter->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- حقل اسم المركز (Name) -->
            <div class="mb-4">
                <input type="text" name="name"
                    class="form-control form-control-custom @error('name') is-invalid @enderror"
                    placeholder="enter clinic center name" value="{{ old('name', $clinicCenter->name) }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل البريد الإلكتروني (Email) -->
            <div class="mb-4">
                <input type="email" name="email"
                    class="form-control form-control-custom @error('email') is-invalid @enderror"
                    placeholder="enter clinic center email" value="{{ old('email', $clinicCenter->user->email) }}" required>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل الهاتف (Phone) -->
            <div class="mb-4">
                <input type="text" name="phone"
                    class="form-control form-control-custom @error('phone') is-invalid @enderror"
                    placeholder="enter clinic center phone" value="{{ old('phone', $clinicCenter->user->phone) }}" required>
                @error('phone')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل العنوان (Address) -->
            <div class="mb-4">
                <input type="text" name="address"
                    class="form-control form-control-custom @error('address') is-invalid @enderror"
                    placeholder="enter clinic center address" value="{{ old('address', $clinicCenter->address) }}" required>
                @error('address')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل كلمة المرور (Password) - اختياري للتحديث -->
            <div class="mb-5">
                <input type="password" name="password"
                    class="form-control form-control-custom @error('password') is-invalid @enderror"
                    placeholder="enter new password (leave empty to keep current)">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- زر الإرسال (Update) -->
            <div class="d-grid">
                <input type="submit" value="Update" class="btn btn-main">
            </div>

        </form>

        <!-- عرض الأخطاء العامة إن وجدت -->
        @if (
            $errors->any() &&
                !$errors->has('name') &&
                !$errors->has('email') &&
                !$errors->has('phone') &&
                !$errors->has('address') &&
                !$errors->has('password'))
            <div class="alert alert-danger text-center small mt-4" role="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

    </div>

@endsection
