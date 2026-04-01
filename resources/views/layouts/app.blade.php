<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Super Admin Dashboard')</title>

    <link rel="icon" href="{{ asset('project_icon/logo.png') }}?v=3" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --main-color: #0f766e;
            --main-soft: rgba(15, 118, 110, 0.12);
            --secondary-color: #0f172a;
            --muted-color: #64748b;
            --surface-color: rgba(255, 255, 255, 0.88);
            --surface-border: rgba(15, 23, 42, 0.08);
            --accent-blue: #2563eb;
            --accent-gold: #f59e0b;
            --danger-color: #dc2626;
            --shadow-color: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--secondary-color);
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.14), transparent 26%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.16), transparent 22%),
                linear-gradient(180deg, #f7fbff 0%, #f2f7fb 48%, #edf3f7 100%);
        }

        .dashboard-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            backdrop-filter: blur(18px);
            background: rgba(248, 250, 252, 0.8);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
        }

        .navbar-width {
            width: min(1720px, calc(100vw - 2rem));
        }

        .dashboard-navbar .navbar-shell {
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 28px;
            padding: 1rem 1.2rem;
            margin: 1rem auto 0;
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.08);
        }

        .brand-block {
            display: inline-flex;
            align-items: center;
            gap: 0.9rem;
            text-decoration: none;
        }

        .brand-mark {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(145deg, var(--main-color), var(--accent-blue));
            box-shadow: 0 16px 34px rgba(37, 99, 235, 0.18);
        }

        .brand-copy {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-title {
            color: var(--secondary-color);
            font-weight: 800;
            font-size: 1rem;
        }

        .brand-subtitle {
            color: var(--muted-color);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .dashboard-nav {
            gap: 0.35rem;
        }

        .dashboard-nav .nav-link {
            color: #334155;
            font-weight: 700;
            border-radius: 999px;
            padding: 0.75rem 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            transition: 0.25s ease;
        }

        .dashboard-nav .nav-link:hover,
        .dashboard-nav .nav-link.active {
            background: var(--main-soft);
            color: var(--main-color);
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            border-radius: 999px;
            padding: 0.45rem 0.55rem 0.45rem 0.45rem;
            background: rgba(148, 163, 184, 0.12);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            background: linear-gradient(145deg, var(--main-color), var(--accent-blue));
        }

        .user-meta {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .user-name {
            font-size: 0.92rem;
            font-weight: 800;
        }

        .user-role {
            font-size: 0.78rem;
            color: var(--muted-color);
            font-weight: 600;
        }

        .logout-button {
            background: rgba(254, 242, 242, 0.92);
            border: 1px solid rgba(220, 38, 38, 0.14);
            color: var(--danger-color);
            border-radius: 999px;
            padding: 0.75rem 1.1rem;
            font-weight: 700;
            transition: 0.25s ease;
        }

        .logout-button:hover {
            background: var(--danger-color);
            color: #fff;
        }

        .content-wrapper {
            padding: 1rem 0 3.5rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .page-title {
            margin: 0.35rem 0 0.5rem;
            font-size: clamp(2rem, 3vw, 2.9rem);
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .page-subtitle {
            margin: 0;
            max-width: 720px;
            color: var(--muted-color);
            font-size: 1rem;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 999px;
            padding: 0.45rem 0.75rem;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(15, 118, 110, 0.14);
            color: var(--main-color);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .helper-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .helper-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.75rem 1rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(148, 163, 184, 0.16);
            color: #334155;
            font-weight: 700;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.04);
        }

        .helper-badge--accent {
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.1), rgba(37, 99, 235, 0.1));
            color: var(--main-color);
        }

        .section-card {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            padding: 1.5rem;
            background: var(--surface-color);
            border: 1px solid rgba(255, 255, 255, 0.72);
            box-shadow: var(--shadow-color);
            backdrop-filter: blur(18px);
        }

        .section-card::before {
            content: '';
            position: absolute;
            right: -5%;
            top: -30px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.14), transparent 66%);
            pointer-events: none;
        }

        .section-heading {
            margin: 0 0 0.45rem;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .section-copy {
            color: var(--muted-color);
            margin-bottom: 0;
        }

        .stat-card {
            height: 100%;
        }

        .stat-card__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .stat-card__icon {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12);
        }

        .stat-card__eyebrow {
            color: var(--muted-color);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .stat-card__value {
            font-size: clamp(1.9rem, 3vw, 2.6rem);
            line-height: 1;
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .stat-card__description {
            margin: 0;
            color: var(--muted-color);
        }

        .mini-metric {
            padding: 1rem 1.15rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.74);
            border: 1px solid rgba(148, 163, 184, 0.16);
        }

        .mini-metric__label {
            color: var(--muted-color);
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .mini-metric__value {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 800;
        }

        .action-tile {
            display: block;
            height: 100%;
            color: inherit;
            text-decoration: none;
            border-radius: 22px;
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(148, 163, 184, 0.15);
            transition: 0.25s ease;
        }

        .action-tile:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.08);
            border-color: rgba(15, 118, 110, 0.2);
        }

        .action-tile__icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            margin-bottom: 1rem;
            color: var(--main-color);
            background: rgba(15, 118, 110, 0.1);
        }

        .action-tile__title {
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .action-tile__copy {
            margin: 0;
            color: var(--muted-color);
            font-size: 0.94rem;
        }

        .toolbar-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .toolbar-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .ghost-button,
        .outline-button,
        .danger-outline-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            border-radius: 999px;
            padding: 0.85rem 1.15rem;
            font-weight: 700;
            text-decoration: none;
            transition: 0.25s ease;
        }

        .ghost-button {
            border: 1px solid rgba(15, 118, 110, 0.12);
            background: rgba(255, 255, 255, 0.72);
            color: var(--main-color);
        }

        .ghost-button:hover,
        .outline-button:hover,
        .danger-outline-button:hover {
            transform: translateY(-2px);
        }

        .outline-button {
            border: 1px solid rgba(37, 99, 235, 0.16);
            background: rgba(219, 234, 254, 0.72);
            color: var(--accent-blue);
        }

        .danger-outline-button {
            border: 1px solid rgba(220, 38, 38, 0.14);
            background: rgba(254, 242, 242, 0.92);
            color: var(--danger-color);
        }

        .form-panel .form-control,
        .form-panel .form-select,
        .form-panel textarea.form-control {
            border-radius: 20px;
            border: 1px solid rgba(148, 163, 184, 0.22);
            background: rgba(255, 255, 255, 0.82);
            padding: 0.95rem 1rem;
            box-shadow: none;
        }

        .form-panel .form-control:focus,
        .form-panel .form-select:focus,
        .form-panel textarea.form-control:focus {
            border-color: rgba(15, 118, 110, 0.35);
            box-shadow: 0 0 0 0.25rem rgba(15, 118, 110, 0.12);
        }

        .form-panel textarea.form-control {
            min-height: 140px;
        }

        .field-label {
            display: block;
            margin-bottom: 0.55rem;
            font-size: 0.92rem;
            font-weight: 800;
            color: #334155;
        }

        .field-note {
            margin-top: 0.45rem;
            color: var(--muted-color);
            font-size: 0.82rem;
        }

        .file-drop {
            border: 1.5px dashed rgba(148, 163, 184, 0.28);
            border-radius: 24px;
            padding: 1.1rem;
            background: rgba(248, 250, 252, 0.7);
        }

        .image-preview {
            width: 112px;
            height: 112px;
            border-radius: 28px;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1.4rem;
        }

        .empty-state__icon {
            width: 84px;
            height: 84px;
            margin: 0 auto 1rem;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--main-color);
            background: rgba(15, 118, 110, 0.12);
        }

        .empty-state__title {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .empty-state__copy {
            color: var(--muted-color);
            max-width: 520px;
            margin: 0 auto 1.25rem;
        }

        .table-shell {
            overflow: hidden;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(148, 163, 184, 0.14);
        }

        .table-shell .table {
            margin-bottom: 0;
        }

        .table-shell thead th {
            border: 0;
            padding: 1rem 1.1rem;
            color: #0f172a;
            background: rgba(226, 232, 240, 0.45);
            font-size: 0.83rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .table-shell tbody td {
            padding: 1rem 1.1rem;
            vertical-align: middle;
            border-color: rgba(226, 232, 240, 0.7);
        }

        .table-shell tbody tr:hover {
            background: rgba(248, 250, 252, 0.76);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.45rem 0.75rem;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .status-pill--success {
            background: rgba(34, 197, 94, 0.12);
            color: #15803d;
        }

        .status-pill--warning {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .status-pill--danger {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        .record-card {
            height: 100%;
            padding: 1.25rem;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid rgba(148, 163, 184, 0.14);
        }

        .record-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .record-card__title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .record-card__copy {
            margin-bottom: 0;
            color: var(--muted-color);
        }

        .avatar-circle {
            width: 72px;
            height: 72px;
            border-radius: 24px;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        }

        .chart-wrap {
            position: relative;
            height: 340px;
        }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem 1.25rem;
            margin-top: 1rem;
        }

        .chart-legend__item {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            color: #334155;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .chart-legend__dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .insight-list {
            display: grid;
            gap: 0.9rem;
        }

        .insight-item {
            display: flex;
            gap: 0.85rem;
            align-items: flex-start;
            padding: 1rem 1.1rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(148, 163, 184, 0.14);
        }

        .insight-item__icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(37, 99, 235, 0.1);
            color: var(--accent-blue);
        }

        .insight-item__title {
            margin: 0 0 0.2rem;
            font-size: 0.98rem;
            font-weight: 800;
        }

        .insight-item__copy {
            margin: 0;
            color: var(--muted-color);
            font-size: 0.92rem;
        }

        @media (max-width: 991.98px) {
            .dashboard-navbar .navbar-shell {
                border-radius: 24px;
            }

            .page-header {
                flex-direction: column;
            }

            .helper-badges {
                justify-content: flex-start;
            }

            .dashboard-nav {
                padding-top: 1rem;
            }

            .navbar-actions {
                padding-top: 1rem;
                flex-direction: column;
                align-items: stretch !important;
            }

            .toolbar-row {
                flex-direction: column;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @php
        $superUser = auth()->user();
        $userInitials = strtoupper(substr($superUser->name ?? 'SA', 0, 2));
    @endphp

    <nav class="dashboard-navbar navbar navbar-expand-xl">
        <div class="container-fluid px-3 px-xl-4 px-xxl-5">
            <div class="navbar-shell navbar-width">
                <div class="d-flex flex-column flex-xl-row align-items-xl-center gap-3 gap-xl-4">
                    <a class="brand-block" href="{{ route('SuperAdmin.Detials.index') }}">
                        <span class="brand-mark">
                            <i class="bi bi-shield-lock-fill"></i>
                        </span>

                        <span class="brand-copy">
                            <span class="brand-title">Super Admin Panel</span>
                            <span class="brand-subtitle">Platform oversight and governance</span>
                        </span>
                    </a>

                    <button class="navbar-toggler border-0 shadow-none ms-auto" type="button" data-bs-toggle="collapse"
                        data-bs-target="#superAdminNavbar" aria-controls="superAdminNavbar" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="superAdminNavbar">
                        <ul class="navbar-nav dashboard-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('SuperAdmin.Detials.*')) active @endif"
                                    href="{{ route('SuperAdmin.Detials.index') }}">
                                    <i class="bi bi-bar-chart-line-fill"></i>
                                    <span>Overview</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('SuperAdmin.specialization.*')) active @endif"
                                    href="{{ route('SuperAdmin.specialization.index') }}">
                                    <i class="bi bi-grid-1x2-fill"></i>
                                    <span>Specializations</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('SuperAdmin.doctor.*')) active @endif"
                                    href="{{ route('SuperAdmin.doctor.index') }}">
                                    <i class="bi bi-person-badge-fill"></i>
                                    <span>Doctors</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('SuperAdmin.ClinicCenter.index') || request()->routeIs('SuperAdmin.clinicCenter.*') || request()->routeIs('SuperAdmin.clinic_center.*')) active @endif"
                                    href="{{ route('SuperAdmin.ClinicCenter.index') }}">
                                    <i class="bi bi-hospital-fill"></i>
                                    <span>Clinics</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('SuperAdmin.Promot.*')) active @endif"
                                    href="{{ route('SuperAdmin.Promot.index') }}">
                                    <i class="bi bi-megaphone-fill"></i>
                                    <span>Promotions</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('doctor_ratings.*') || request()->routeIs('doctors.deactivate') || request()->routeIs('doctors.destroy')) active @endif"
                                    href="{{ route('doctor_ratings.index') }}">
                                    <i class="bi bi-stars"></i>
                                    <span>Ratings</span>
                                </a>
                            </li>
                        </ul>

                        <div class="navbar-actions d-flex align-items-center gap-3 ms-xl-auto">
                            <div class="user-chip">
                                <span class="user-avatar">{{ $userInitials }}</span>
                                <span class="user-meta">
                                    <span class="user-name">{{ $superUser->name ?? 'Super Admin' }}</span>
                                    <span class="user-role">Platform governance</span>
                                </span>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="logout-button">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="content-wrapper">
        <div class="container-xxl">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    @stack('scripts')
</body>

</html>
