{{-- @extends('layouts.app')

@section('doctors')

@section('content')
    @if (session('message'))
        <h1>{{ session('message') }}</h1>
    @endif
    <a href="{{ route('SuperAdmin.doctor.create') }}">add new doctor</a>
    @forelse ($doctors as $doctor)
        <p>{{ $doctor->user->name }}</p>
        <p>{{ $doctor->user->email }}</p>
        <p>{{ $doctor->user->phone }}</p>
        <p>{{ $doctor->specialization->name }}</p>
        <img src="{{ asset($doctor->user->profile_image) }}" alt="doctor profile image">
        <a href="{{ route('SuperAdmin.doctor.edit', $doctor->id) }}">update</a>
        <form action="{{ route('SuperAdmin.doctor.destroy', $doctor->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">delete</button>
        </form>
    @empty
        <h1>there is'nt any doctors yet!!</h1>
    @endforelse

@endsection
 --}}


@extends('layouts.app')

@section('title', 'doctors')

@section('content')
    <!-- تنسيق مخصص للبطاقات والألوان -->
    <style>
        :root {
            --main-color: #008080;
            /* اللون الأخضر المائي */
            --danger-color: #dc3545;
            /* لون الحذف */
            --border-color: #e9ecef;
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
            width: 100%;
            text-align: center;
            margin-bottom: 25px;
            transition: background-color 0.3s;
        }

        .btn-add-new:hover {
            background-color: #006666;
            color: white;
        }

        /* تنسيق بطاقة الطبيب */
        .doctor-card {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            padding: 15px;
            display: flex;
            /* لترتيب الصورة والمعلومات جنباً إلى جنب */
            align-items: center;
        }

        /* تنسيق صورة البروفايل */
        .profile-img-container {
            width: 80px;
            height: 80px;
            overflow: hidden;
            border-radius: 50%;
            margin-inline-end: 15px;
            flex-shrink: 0;
            /* لمنع الصورة من الانكماش */
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* تنسيق بيانات الطبيب */
        .doctor-info {
            flex-grow: 1;
        }

        .doctor-info p {
            margin-bottom: 3px;
            font-size: 0.9rem;
            color: #495057;
            display: flex;
            align-items: center;
        }

        .doctor-info .main-name {
            font-weight: bold;
            font-size: 1.1rem;
            color: #212529;
            margin-bottom: 5px;
        }

        .doctor-info .specialization {
            color: var(--main-color);
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* تنسيق أيقونات البيانات */
        .info-icon {
            font-size: 0.9rem;
            margin-inline-end: 8px;
            color: #6c757d;
        }

        /* تنسيق حاوية الأزرار */
        .action-buttons {
            display: flex;
            flex-direction: column;
            /* الأزرار فوق بعضها */
            gap: 8px;
            flex-shrink: 0;
            margin-inline-start: 15px;
        }

        /* تنسيق أزرار الإجراءات */
        .btn-action {
            border-radius: 8px;
            padding: 5px 10px;
            font-size: 0.9rem;
            text-decoration: none;
            width: 75px;
            /* عرض ثابت للأزرار */
            text-align: center;
        }

        .btn-update {
            background-color: #0d6efd;
            color: white;
        }

        .btn-delete {
            background-color: var(--danger-color);
            color: white;
            border: none;
        }
    </style>

    <div class="container py-4">

        <!-- عنوان الصفحة -->
        <h3 class="mb-4 fw-bold text-center" style="color: var(--main-color);">
            <i class="bi bi-people-fill me-2"></i> Doctors List
        </h3>

        <!-- عرض رسائل الجلسة (Session Message) -->
        @if (session('message'))
            <div class="alert alert-success text-center rounded-3 mb-4" role="alert">
                <h5 class="m-0">{{ session('message') }}</h5>
            </div>
        @endif

        <!-- زر "إضافة طبيب جديد" -->
        <a href="{{ route('SuperAdmin.doctor.create') }}" class="btn-add-new">
            <i class="bi bi-plus-circle me-2"></i> Add New Doctor
        </a>

        <!-- حلقة العرض للأطباء -->
        @forelse ($doctors as $doctor)
            <div class="doctor-card">

                <!-- صورة البروفايل -->
                <div class="profile-img-container">
                    <!-- يرجى التأكد من أن المسار $doctor->user->profile_image صحيح ومتاح -->
                    <img src="{{ asset($doctor->user->profile_image)  /* $doctor->user->profile_image  */}}" alt="{{ $doctor->user->name }} profile image"
                        class="profile-img"
                        onerror="this.onerror=null; this.src='https://placehold.co/80x80/6c757d/ffffff?text=DR' ">
                </div>

                <!-- المعلومات -->
                <div class="doctor-info">

                    <div class="main-name">{{ $doctor->user->name }}</div>

                    <p class="specialization">
                        <i class="bi bi-patch-check info-icon" style="color: var(--main-color);"></i>
                        {{ $doctor->specialization->name }}
                    </p>

                    <p>
                        <i class="bi bi-envelope info-icon"></i>
                        {{ $doctor->user->email }}
                    </p>

                    <p>
                        <i class="bi bi-phone info-icon"></i>
                        {{ $doctor->user->phone }}
                    </p>

                </div>

                <!-- أزرار الإجراءات -->
                <div class="action-buttons">
                    <!-- زر التعديل (Update) -->
                    <a href="{{ route('SuperAdmin.doctor.edit', $doctor->id) }}" class="btn-action btn-update">
                        <i class="bi bi-pencil"></i> Update
                    </a>

                    <!-- نموذج الحذف (Delete) -->
                    <form action="{{ route('SuperAdmin.doctor.destroy', $doctor->id) }}" method="POST"
                        class="d-inline-block m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete"
                            onclick="return confirm('Are you sure you want to delete Doctor {{ $doctor->user->name }}?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <!-- رسالة في حال عدم وجود أطباء -->
            <div class="alert alert-info text-center mt-5" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <h2>There are no doctors yet!</h2>
                <p class="mb-0">Please use the "Add New Doctor" button above to get started.</p>
            </div>
        @endforelse

    </div>

@endsection
