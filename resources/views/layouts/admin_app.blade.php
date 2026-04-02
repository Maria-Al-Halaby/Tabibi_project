<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="{{ asset('project_icon/logo.png') }}?v=3" type="image/png">

    <style>
        :root {
            --tabibi-primary-color: #0f766e;
            --tabibi-primary-soft: rgba(15, 118, 110, 0.12);
            --tabibi-secondary-color: #1d4ed8;
            --tabibi-accent-color: #f59e0b;
            --tabibi-surface-color: rgba(255, 255, 255, 0.88);
            --tabibi-border-color: rgba(15, 23, 42, 0.08);
            --tabibi-text-color: #0f172a;
            --tabibi-muted-color: #64748b;
            --tabibi-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--tabibi-text-color);
            background:
                radial-gradient(circle at top left, rgba(45, 212, 191, 0.18), transparent 28%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.14), transparent 24%),
                linear-gradient(180deg, #f8fffe 0%, #f4f8fb 45%, #eef4f8 100%);
        }

        .tabibi-text-primary {
            color: var(--tabibi-primary-color) !important;
        }

        .btn-tabibi {
            background: linear-gradient(135deg, var(--tabibi-primary-color), #14b8a6);
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 0.8rem 1.35rem;
            font-weight: 700;
            box-shadow: 0 16px 30px rgba(15, 118, 110, 0.18);
        }

        .btn-tabibi:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 20px 34px rgba(15, 118, 110, 0.22);
        }

        .border-tabibi-primary {
            border-color: rgba(15, 118, 110, 0.25) !important;
        }

        .dashboard-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            backdrop-filter: blur(20px);
            background: rgba(248, 250, 252, 0.8);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
        }

        .navbar-width {
            width: min(1720px, calc(100vw - 2rem));
        }

        .dashboard-navbar .navbar-inner {
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 28px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
            padding: 1rem 1.25rem;
            margin: 1rem auto 0;
        }

        .brand-block {
            display: inline-flex;
            align-items: center;
            gap: 0.9rem;
            text-decoration: none;
        }

        .brand-mark {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: #fff;
            background: linear-gradient(145deg, var(--tabibi-primary-color), #14b8a6);
            box-shadow: 0 16px 32px rgba(15, 118, 110, 0.22);
        }

        .brand-copy {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1rem;
            color: var(--tabibi-text-color);
        }

        .brand-subtitle {
            color: var(--tabibi-muted-color);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .dashboard-nav {
            gap: 0.4rem;
        }

        .dashboard-nav .nav-link {
            border-radius: 999px;
            padding: 0.75rem 1rem;
            color: #334155;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            transition: 0.25s ease;
        }

        .dashboard-nav .nav-link:hover,
        .dashboard-nav .nav-link.active {
            color: var(--tabibi-primary-color);
            background: var(--tabibi-primary-soft);
        }

        .user-summary {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.45rem 0.55rem 0.45rem 0.45rem;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.12);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #0f766e, #1d4ed8);
            color: #fff;
            font-weight: 800;
            letter-spacing: 0.04em;
        }

        .user-meta {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .user-name {
            font-weight: 800;
            font-size: 0.92rem;
        }

        .user-role {
            color: var(--tabibi-muted-color);
            font-size: 0.78rem;
            font-weight: 600;
        }

        .logout-button {
            border: 1px solid rgba(239, 68, 68, 0.16);
            color: #dc2626;
            background: rgba(254, 242, 242, 0.92);
            border-radius: 999px;
            padding: 0.75rem 1.1rem;
            font-weight: 700;
            transition: 0.25s ease;
        }

        .logout-button:hover {
            background: #dc2626;
            color: #fff;
        }

        .content-wrapper {
            padding: 1rem 0 3.5rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.75rem;
        }

        .page-title {
            margin: 0.35rem 0 0.5rem;
            font-size: clamp(2rem, 3vw, 2.85rem);
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .page-subtitle {
            margin: 0;
            max-width: 700px;
            color: var(--tabibi-muted-color);
            font-size: 1rem;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 999px;
            padding: 0.45rem 0.75rem;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(15, 118, 110, 0.14);
            color: var(--tabibi-primary-color);
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
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid rgba(148, 163, 184, 0.18);
            color: #334155;
            font-weight: 700;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.04);
        }

        .helper-badge--accent {
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.1), rgba(29, 78, 216, 0.12));
            color: var(--tabibi-primary-color);
        }

        .section-card {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            padding: 1.5rem;
            background: var(--tabibi-surface-color);
            border: 1px solid rgba(255, 255, 255, 0.72);
            box-shadow: var(--tabibi-shadow);
            backdrop-filter: blur(18px);
        }

        .section-card::before {
            content: '';
            position: absolute;
            inset: auto auto 0 -8%;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(45, 212, 191, 0.16), transparent 68%);
            pointer-events: none;
        }

        .section-heading {
            margin: 0 0 0.45rem;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .section-copy {
            color: var(--tabibi-muted-color);
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
            color: var(--tabibi-muted-color);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .stat-card__value {
            font-size: clamp(1.9rem, 3vw, 2.6rem);
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .stat-card__description {
            color: var(--tabibi-muted-color);
            margin: 0;
        }

        .mini-metric {
            padding: 1rem 1.15rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.74);
            border: 1px solid rgba(148, 163, 184, 0.16);
        }

        .mini-metric__label {
            color: var(--tabibi-muted-color);
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
            text-decoration: none;
            color: inherit;
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
            color: var(--tabibi-primary-color);
            background: rgba(15, 118, 110, 0.1);
        }

        .action-tile__title {
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .action-tile__copy {
            margin: 0;
            color: var(--tabibi-muted-color);
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
            color: var(--tabibi-primary-color);
        }

        .ghost-button:hover,
        .outline-button:hover,
        .danger-outline-button:hover {
            transform: translateY(-2px);
        }

        .outline-button {
            border: 1px solid rgba(29, 78, 216, 0.16);
            background: rgba(219, 234, 254, 0.72);
            color: var(--tabibi-secondary-color);
        }

        .danger-outline-button {
            border: 1px solid rgba(220, 38, 38, 0.14);
            background: rgba(254, 242, 242, 0.92);
            color: #dc2626;
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
            color: var(--tabibi-muted-color);
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
            color: var(--tabibi-primary-color);
            background: rgba(15, 118, 110, 0.12);
        }

        .empty-state__title {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .empty-state__copy {
            color: var(--tabibi-muted-color);
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
            color: var(--tabibi-muted-color);
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
            background: rgba(29, 78, 216, 0.1);
            color: var(--tabibi-secondary-color);
        }

        .insight-item__title {
            margin: 0 0 0.2rem;
            font-size: 0.98rem;
            font-weight: 800;
        }

        .insight-item__copy {
            margin: 0;
            color: var(--tabibi-muted-color);
            font-size: 0.92rem;
        }

        @media (max-width: 991.98px) {
            .dashboard-navbar .navbar-inner {
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
        $adminUser = auth()->user();
        $clinicName = $adminUser?->clinic_center?->name ?? 'Clinic center';
        $userInitials = strtoupper(substr($adminUser->name ?? 'AD', 0, 2));
    @endphp

    <nav class="dashboard-navbar navbar navbar-expand-xl">
        <div class="container-fluid px-3 px-xl-4 px-xxl-5">
            <div class="navbar-inner navbar-width">
                <div class="d-flex flex-column flex-xl-row align-items-xl-center gap-3 gap-xl-4">
                    <a class="brand-block" href="{{ route('Admin.index') }}">
                        <span class="brand-mark">
                            <i class="fas fa-heart-pulse"></i>
                        </span>

                        <span class="brand-copy">
                            <span class="brand-title">Tabibi Admin</span>
                            <span class="brand-subtitle">Operational control center</span>
                        </span>
                    </a>

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

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Admin.Pharmacy.index') }}">
                                    <i class="fas fa-calendar-check me-1"></i> Pharmacy
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Admin.Pricing.index') }}">
                                    <i class="fas fa-calendar-check me-1"></i> Pricing
                                </a>
                            </li>

                        </ul>

                        <form action="{{ route('logout') }}" method="POST" class="d-flex ms-auto">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>

                    
                        <button class="navbar-toggler border-0 shadow-none ms-auto" type="button" data-bs-toggle="collapse"
                            data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="adminNavbar">
                        <ul class="navbar-nav dashboard-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('Admin.index')) active @endif"
                                    href="{{ route('Admin.index') }}">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Overview</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('Admin.ClinicManagement.*')) active @endif"
                                    href="{{ route('Admin.ClinicManagement.index') }}">
                                    <i class="fas fa-hospital-user"></i>
                                    <span>Clinic Management</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('Admin.Appointment.*')) active @endif"
                                    href="{{ route('Admin.Appointment.index') }}">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Appointments</span>
                                </a>
                            </li>
                        </ul>

                        <div class="navbar-actions d-flex align-items-center gap-3 ms-xl-auto">
                            <div class="user-summary">
                                <span class="user-avatar">{{ $userInitials }}</span>
                                <span class="user-meta">
                                    <span class="user-name">{{ $adminUser->name ?? 'Admin User' }}</span>
                                    <span class="user-role">{{ $clinicName }}</span>
                                </span>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="logout-button">
                                    <i class="fas fa-right-from-bracket me-2"></i>Logout
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
