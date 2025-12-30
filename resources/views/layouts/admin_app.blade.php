<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'main page')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJ8uR2dIQ7dFwF4yE4fJbQ/jA7J0n6m7n+2x5tK5F5d5f5m5z5A5q5u5z5Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="{{ asset('project_icon/logo.png') }}?v=3" type="image/png">    <style>
        /* 🎨 تنسيقات مخصصة لتطابق تصميم Tabibi */
        :root {
            --tabibi-primary-color: #20b2aa;
            /* Medium Sea Green/Teal */
        }

        .tabibi-text-primary {
            color: var(--tabibi-primary-color) !important;
        }

        /* تنسيق الروابط داخل قائمة التنقل العلوية */
        .tabibi-top-nav .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .tabibi-top-nav .nav-link:hover {
            background-color: #f0f8ff;
            /* Alice Blue */
            color: var(--tabibi-primary-color);
        }

        /* ... باقي تنسيقات الأزرار (btn-tabibi) ... */
        .btn-tabibi {
            background-color: var(--tabibi-primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
        }

        .btn-tabibi:hover {
            background-color: #198c86;
            color: white;
        }

        .bottom-nav-logout {
            color: #dc3545 !important;
            /* لون أحمر لتسجيل الخروج */
        }

        .bottom-nav-logout:hover {
            color: #b02a37 !important;
        }

        /* التنسيق الجديد: لتنسيق الحدود (الذي طلبته سابقًا) */
        .border-tabibi-primary {
            border-color: var(--tabibi-primary-color) !important;
        }

        /* 💡 إزالة التعديل الخاص بالـ Navbar السفلي من الـ body */
        body {
            padding-bottom: 0;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm p-3 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold tabibi-text-primary" href="{{ route('Admin.index') }}">
                <i class="fas fa-heartbeat me-2"></i> Tabibi Admin
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 tabibi-top-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('Admin.index') }}">
                            <i class="fas fa-chart-line me-1"></i> Details
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('Admin.ClinicManagement.index') }}">
                            <i class="fas fa-hospital-alt me-1"></i> Clinic Management
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('Admin.Appointment.index') }}">
                            <i class="fas fa-calendar-check me-1"></i> Appointment
                        </a>
                    </li>

                    {{--   <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-calendar-check me-1"></i> Secretary
                        </a>
                    </li> --}}
                </ul>

                <form action="{{ route('logout') }}" method="POST" class="d-flex ms-auto">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
