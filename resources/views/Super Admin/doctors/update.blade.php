{{-- @extends('layouts.app')

@section('title', 'update doctor information')


@section('content')

    <form action="{{ route('SuperAdmin.doctor.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="text" name="name" placeholder="enter doctor name" value="{{ $doctor->user->name }}">
        <input type="email" name="email" placeholder="enter doctor email" value="{{ $doctor->user->email }}">
        <input type="text" name="phone" placeholder="enter doctor phone" value="{{ $doctor->user->phone }}">

        <input type="password" name="password" placeholder="enter doctor password">
        <label for="chageDoctorImage">change doctor image:
            <input type="file" name="profile_image" style="display: none" id="chageDoctorImage">
            <img src="{{ $doctor->user->profile_image }}" alt="doctor profile image">


            <select name="specialization_id">
                @foreach ($specializations as $specialization)
                    <option value="{{ $specialization->id }}"
                        {{ $specialization->id == $doctor->specialization_id ? 'selected' : '' }}>
                        {{ $specialization->name }}</option>
                @endforeach
            </select>

        </label>

        <input type="submit" value="update information">


    </form>

@endsection
 --}}

@extends('layouts.app')

@section('title', 'update doctor information')


@section('content')
    <!-- تنسيق مخصص لضمان شكل الحقول المستديرة والزر الموحد وتنسيق الصورة -->
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

        /* تنسيق معاينة الصورة */
        .img-preview-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--main-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: 10px auto;
            position: relative;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .img-preview-container:hover {
            opacity: 0.8;
        }

        .img-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* إخفاء زر اختيار الملف الافتراضي */
        #profile_image_input {
            display: none !important;
        }
    </style>

    <div class="container py-4">

        <!-- عنوان الصفحة -->
        <h3 class="mb-4 fw-bold text-center" style="color: var(--main-color);">
            <i class="bi bi-person-fill-gear me-2"></i> Update Doctor Information
        </h3>

        <form action="{{ route('SuperAdmin.doctor.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- حقل اسم الطبيب (Name) -->
            <div class="mb-4">
                <input type="text" name="name"
                    class="form-control form-control-custom @error('name') is-invalid @enderror"
                    placeholder="enter doctor name" value="{{ old('name', $doctor->user->name) }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل البريد الإلكتروني (Email) -->
            <div class="mb-4">
                <input type="email" name="email"
                    class="form-control form-control-custom @error('email') is-invalid @enderror"
                    placeholder="enter doctor email" value="{{ old('email', $doctor->user->email) }}" required>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل الهاتف (Phone) -->
            <div class="mb-4">
                <input type="text" name="phone"
                    class="form-control form-control-custom @error('phone') is-invalid @enderror"
                    placeholder="enter doctor phone" value="{{ old('phone', $doctor->user->phone) }}" required>
                @error('phone')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <input type="number" name="experience_years"
                    class="form-control form-control-custom @error('experience_years') is-invalid @enderror"
                    placeholder="enter doctor experience years" value="{{ old( "experience_years" , $doctor->experience_years) }}">
                @error('experience_years')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <input type="text" name="bio"
                    class="form-control form-control-custom @error('bio') is-invalid @enderror"
                    placeholder="enter doctor bio" value="{{ old("bio" , $doctor->bio) }}">
                @error('bio')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل كلمة المرور (Password) -->
            <div class="mb-4">
                <input type="password" name="password"
                    class="form-control form-control-custom @error('password') is-invalid @enderror"
                    placeholder="enter new password (leave empty to keep current)">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>




            <!-- حقل صورة الملف الشخصي (Profile Image) -->
            <div class="mb-4 text-center">
                <label for="profile_image_input" class="form-label fw-semibold text-muted">Change Doctor Image</label>

                <!-- حاوية الصورة الحالية القابلة للنقر -->
                <div class="img-preview-container" onclick="document.getElementById('profile_image_input').click()">

                    <img src="{{ /* asset($doctor->user->profile_image) */ $doctor->user->profile_image }}" alt="doctor profile image" class="img-preview"
                        onerror="this.onerror=null; this.src='https://placehold.co/120x120/008080/ffffff?text=DR';">
                </div>

                <!-- حقل إدخال الملف الفعلي (مخفي) -->
                <input type="file" name="profile_image" id="profile_image_input"
                    class="@error('profile_image') is-invalid @enderror">

                @error('profile_image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- حقل الاختصاص (Specialization ID) -->
            <div class="mb-5">
                <label for="specialization_id" class="form-label fw-semibold text-muted">Select Specialization</label>
                <select name="specialization_id" id="specialization_id"
                    class="form-select form-select-custom @error('specialization_id') is-invalid @enderror" required>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}"
                            {{ old('specialization_id', $doctor->specialization_id) == $specialization->id ? 'selected' : '' }}>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
                @error('specialization_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- زر الإرسال (Update Information) -->
            <div class="d-grid">
                <input type="submit" value="Update Information" class="btn btn-main">
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
