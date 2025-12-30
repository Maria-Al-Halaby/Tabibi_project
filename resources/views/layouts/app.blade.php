<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'main page')</title>


    <link rel="icon" href="{{ asset('project_icon/logo.png') }}?v=3" type="image/png">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* 🎨 تنسيقات مخصصة */
        :root {
            --main-color: #008080;
            /* لون أخضر مائي داكن */
            --secondary-color: #f8f9fa;
            /* خلفية فاتحة */
        }

        /* 💡 إزالة الهامش السفلي وتثبيت الخلفية */
        body {
            padding-bottom: 0;
            background-color: var(--secondary-color);
        }

        /* تنسيق شريط التنقل العلوي */
        .top-nav .nav-link {
            color: var(--main-color);
            /* لون أساسي للنص غير النشط */
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .top-nav .nav-link:hover,
        .top-nav .nav-link.active {
            background-color: rgba(0, 128, 128, 0.1);
            color: var(--main-color);
        }

        /* تنسيق الأيقونات داخل شريط التنقل */
        .top-nav .nav-link i {
            font-size: 1.1rem;
            margin-right: 5px;
        }

        /* تنسيق زر تسجيل الخروج ليكون مطابقاً للروابط */
        .logout-button {
            background: none;
            border: none;
            color: #dc3545;
            /* أحمر للتسجيل الخروج */
            padding: 8px 15px;
            margin: 0;
            cursor: pointer;
            font-weight: 500;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .logout-button:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm p-3 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" style="color: var(--main-color);"
                href="{{ route('SuperAdmin.Detials.index') }}">
                <i class="bi bi-shield-fill-check me-2"></i> Super Admin Panel
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#superAdminNavbar"
                aria-controls="superAdminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="superAdminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 top-nav">

                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('SuperAdmin.Detials.index') }}">
                            <i class="bi bi-person-lines-fill"></i> Details
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('SuperAdmin.specialization.index') }}">
                            <i class="bi bi-boxes"></i> Specialization
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('SuperAdmin.doctor.index') }}">
                            <i class="bi bi-person-badge-fill"></i> Doctors
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('SuperAdmin.ClinicCenter.index') }}">
                            <i class="bi bi-hospital-fill"></i> Clinic Centers
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('SuperAdmin.Promot.index') }}">
                            <i class="bi bi-boxes"></i> Promot
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor_ratings.index') }}">
                            <i class="bi bi-stars"></i> doctors ratings
                        </a>
                    </li>
                </ul>

                <form action="{{ route('logout') }}" method="POST" class="d-flex ms-auto">
                    @csrf
                    <button type="submit" class="logout-button">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container p-3">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
