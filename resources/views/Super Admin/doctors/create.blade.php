{{-- @extends('layouts.app')


@section('title', 'add new doctor')


@section('content')

    <form action="{{ route('SuperAdmin.doctor.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" placeholder="enter doctor name">
        <input type="email" name="email" placeholder="enter doctor email">
        <input type="text" name="phone" placeholder="enter doctor phone">
        <input type="password" name="password" placeholder="enter password">
        <input type="file" name="profile_image">
        <select name="specialization_id">
            @foreach ($specializations as $specialization)
                <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
            @endforeach
        </select>
        <input type="submit" value="send">
    </form>
@endsection
 --}}


@extends('layouts.app')

@section('title', 'add new doctor')


@section('content')
    <!-- تنسيق مخصص لضمان شكل الحقول المستديرة والزر الموحد -->
    <style>
        :root {
            --main-color: #008080;
            /* اللون الأخضر المائي */
        }

        /* تنسيق حقول الإدخال والقائمة المنسدلة */
        .form-control-custom,
        .form-select-custom {
            border: 1px solid #ced4da;
            /* حدود خفيفة */
            border-radius: 12px;
            /* حواف مستديرة */
            padding: 15px 15px;
            background-color: white;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
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
            <i class="bi bi-person-plus-fill me-2"></i> Add New Doctor
        </h3>

        <form action="{{ route('SuperAdmin.doctor.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- حقل اسم الطبيب (Name) -->
            <div class="mb-4">
                <input type="text" name="name"
                    class="form-control form-control-custom @error('name') is-invalid @enderror"
                    placeholder="enter doctor name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل البريد الإلكتروني (Email) -->
            <div class="mb-4">
                <input type="email" name="email"
                    class="form-control form-control-custom @error('email') is-invalid @enderror"
                    placeholder="enter doctor email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل الهاتف (Phone) -->
            <div class="mb-4">
                <input type="text" name="phone"
                    class="form-control form-control-custom @error('phone') is-invalid @enderror"
                    placeholder="enter doctor phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل كلمة المرور (Password) -->
            <div class="mb-4">
                <input type="password" name="password"
                    class="form-control form-control-custom @error('password') is-invalid @enderror"
                    placeholder="enter password" required>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل صورة الملف الشخصي (Profile Image) -->
            <div class="mb-4">
                <label for="profile_image" class="form-label fw-semibold text-muted">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image"
                    class="form-control @error('profile_image') is-invalid @enderror">
                @error('profile_image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل الاختصاص (Specialization ID) -->
            <div class="mb-5">
                <label for="specialization_id" class="form-label fw-semibold text-muted">Select Specialization</label>
                <select name="specialization_id" id="specialization_id"
                    class="form-select form-select-custom @error('specialization_id') is-invalid @enderror" required>
                    <option value="">-- Choose Specialization --</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}"
                            {{ old('specialization_id') == $specialization->id ? 'selected' : '' }}>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
                @error('specialization_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- زر الإرسال (Send) -->
            <div class="d-grid">
                <input type="submit" value="Send" class="btn btn-main">
            </div>

        </form>

        <!-- عرض الأخطاء العامة إن وجدت -->
        @if (
            $errors->any() &&
                !$errors->has('name') &&
                !$errors->has('email') &&
                !$errors->has('phone') &&
                !$errors->has('password') &&
                !$errors->has('profile_image') &&
                !$errors->has('specialization_id'))
            <div class="alert alert-danger text-center small mt-4" role="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

    </div>

@endsection
