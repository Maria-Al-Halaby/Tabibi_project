{{-- @extends('layouts.app')

@section('title', 'specializations')


@section('content')

    @if (session('message'))
        <p style="color: red;">{{ session('message') }}</p>
    @endif
    <a href="{{ route('SuperAdmin.specialization.create') }}">add new specialization</a>
    @forelse ($specializations as $specialization)
        <div>
            <h2>{{ $specialization->name }}</h2>
            <a href="{{ route('SuperAdmin.specialization.edit', $specialization->id) }}">edit specialization</a>
            <form action="{{ route('SuperAdmin.specialization.destroy', $specialization->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="delete">
            </form>
        </div>
    @empty
        <h1>there is'nt any specializtion yet!!</h1>
    @endforelse


@endsection
 --}}

@extends('layouts.app')

@section('title', 'specializations')


@section('content')
    <!-- تنسيق مخصص ليظهر شكل البطاقة العصرية وأزرار الإجراءات -->
    <style>
        :root {
            --main-color: #008080; /* اللون الأخضر المائي */
            --danger-color: #dc3545; /* اللون الأحمر للحذف */
            --info-color: #0d6efd; /* اللون الأزرق للتعديل */
        }

        /* تنسيق زر "إضافة جديد" */
        .btn-add-new {
            background-color: var(--main-color);
            border-color: var(--main-color);
            color: white;
            border-radius: 12px;
            padding: 10px 20px;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-add-new:hover {
            background-color: #006666;
            color: white;
        }

        /* تنسيق البطاقة الفردية للاختصاص */
        .specialization-card {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .specialization-name {
            font-size: 1.15rem;
            font-weight: 600;
            color: #343a40;
            display: flex;
            align-items: center;
        }

        /* تنسيق الأزرار داخل البطاقة */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-button {
            border: none;
            border-radius: 8px;
            padding: 8px;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.2s;
        }

        .action-button:hover {
            opacity: 0.8;
        }
        
        /* تنسيق زر التعديل */
        .btn-edit {
            background-color: var(--info-color);
            color: white;
        }

        /* تنسيق زر الحذف */
        .btn-delete {
            background-color: var(--danger-color);
            color: white;
        }
    </style>

    <div class="container py-4">
        
        <!-- رسالة الجلسة (Session Message) -->
        @if (session('message'))
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- زر إضافة اختصاص جديد -->
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('SuperAdmin.specialization.create') }}" class="btn-add-new">
                <i class="bi bi-plus-circle me-2"></i> Add New Specialization
            </a>
        </div>

        <!-- قائمة الاختصاصات -->
        @forelse ($specializations as $specialization)
            <div class="specialization-card">
                
                <!-- اسم الاختصاص -->
                <h2 class="specialization-name">
                    <i class="bi bi-patch-check-fill me-2" style="color: var(--main-color);"></i>
                    {{ $specialization->name }}
                </h2>
                
                <!-- أزرار الإجراءات (تعديل وحذف) -->
                <div class="action-buttons">
                    
                    <!-- رابط التعديل -->
                    <a href="{{ route('SuperAdmin.specialization.edit', $specialization->id) }}" class="action-button btn-edit" title="Edit Specialization">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    
                    <!-- نموذج الحذف -->
                    <form action="{{ route('SuperAdmin.specialization.destroy', $specialization->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Are you sure you want to delete specialization: {{ $specialization->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-button btn-delete" title="Delete Specialization">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <!-- رسالة في حالة عدم وجود اختصاصات -->
            <div class="alert alert-info text-center mt-5" role="alert">
                <i class="bi bi-info-circle me-2"></i> There aren't any specializations yet!!
            </div>
        @endforelse

    </div>

    <!-- يجب تضمين ملف جافاسكريبت الخاص بـ Bootstrap في الـ layout الرئيسي لعمل الـ dismissible alert -->
    <script>
        // هذا مجرد تعليق للتذكير، يفترض أن يتم تضمين JS في layouts.app
        // <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </script>
@endsection
