<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Tabiby Dashboard')</title>

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

        .dashboard-shell {
            width: min(1720px, calc(100vw - 2rem));
            margin: 1rem auto;
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            gap: 1.25rem;
            align-items: start;
        }

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.42);
            backdrop-filter: blur(6px);
            opacity: 0;
            visibility: hidden;
            transition: 0.25s ease;
            z-index: 1040;
        }

        .dashboard-sidebar {
            position: sticky;
            top: 1rem;
            z-index: 1045;
        }

        .sidebar-panel {
            padding: 1.25rem;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(255, 255, 255, 0.72);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(20px);
        }

        .sidebar-card {
            border-radius: 24px;
            padding: 1.1rem;
            background: linear-gradient(165deg, #0f766e 0%, #14b8a6 100%);
            color: #fff;
            box-shadow: 0 20px 38px rgba(15, 118, 110, 0.24);
        }

        .sidebar-card__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.4rem 0.7rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .sidebar-card__title {
            margin: 1rem 0 0.35rem;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .sidebar-card__copy {
            margin: 0;
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.92rem;
        }

        .sidebar-section {
            margin-top: 1.25rem;
        }

        .sidebar-section__label {
            display: block;
            margin-bottom: 0.75rem;
            color: var(--tabibi-muted-color);
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            display: grid;
            gap: 0.65rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            padding: 0.9rem 1rem;
            border-radius: 18px;
            color: #334155;
            font-weight: 700;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(248, 250, 252, 0.78);
            transition: 0.25s ease;
        }

        .sidebar-link:hover {
            transform: translateX(4px);
            color: #0f172a;
            border-color: rgba(15, 118, 110, 0.2);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.14), rgba(20, 184, 166, 0.18));
            color: var(--tabibi-primary-color);
            border-color: rgba(15, 118, 110, 0.18);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
        }

        .sidebar-link__icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(15, 118, 110, 0.1);
            color: inherit;
        }

        .sidebar-link__content {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
            line-height: 1.15;
        }

        .sidebar-link__meta {
            color: var(--tabibi-muted-color);
            font-size: 0.78rem;
            font-weight: 600;
        }

        .dashboard-main {
            min-width: 0;
        }

        .dashboard-topbar {
            position: sticky;
            top: 1rem;
            z-index: 1030;
            margin-bottom: 1rem;
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.15rem;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(255, 255, 255, 0.72);
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(20px);
        }

        .topbar-leading {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }

        .sidebar-toggle {
            width: 48px;
            height: 48px;
            border: 0;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            background: rgba(226, 232, 240, 0.7);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        }

        .brand-block {
            display: inline-flex;
            align-items: center;
            gap: 0.9rem;
            text-decoration: none;
            min-width: 0;
        }

        .brand-mark {
            width: 58px;
            height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem;
            border-radius: 20px;
            background: linear-gradient(145deg, #0f766e, #14b8a6);
            box-shadow: 0 18px 32px rgba(15, 118, 110, 0.22);
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-copy {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
            min-width: 0;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.02rem;
            color: var(--tabibi-text-color);
        }

        .brand-subtitle {
            color: var(--tabibi-muted-color);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .topbar-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: flex-end;
        }

        .topbar-chip {
            display: flex;
            flex-direction: column;
            gap: 0.12rem;
            padding: 0.85rem 1rem;
            border-radius: 18px;
            background: rgba(248, 250, 252, 0.88);
            border: 1px solid rgba(148, 163, 184, 0.14);
            min-width: 180px;
        }

        .topbar-chip__label {
            color: var(--tabibi-muted-color);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .topbar-chip__value {
            font-size: 0.96rem;
            font-weight: 800;
            color: #0f172a;
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
            padding: 0 0 3.5rem;
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

        .alert-stack {
            display: grid;
            gap: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .alert-banner {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            padding: 1rem 1.15rem;
            border-radius: 22px;
            border: 1px solid transparent;
            background: rgba(255, 255, 255, 0.78);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.04);
        }

        .alert-banner__icon {
            width: 2.2rem;
            height: 2.2rem;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
        }

        .alert-banner__title {
            margin: 0 0 0.2rem;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .alert-banner__copy {
            margin: 0;
            color: var(--tabibi-muted-color);
        }

        .alert-banner--success {
            border-color: rgba(34, 197, 94, 0.16);
            background: rgba(240, 253, 244, 0.84);
        }

        .alert-banner--success .alert-banner__icon {
            background: rgba(34, 197, 94, 0.14);
            color: #15803d;
        }

        .alert-banner--danger {
            border-color: rgba(239, 68, 68, 0.16);
            background: rgba(254, 242, 242, 0.92);
        }

        .alert-banner--danger .alert-banner__icon {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
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

        .table-shell .form-control,
        .table-shell .form-select {
            min-width: 120px;
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

        .status-pill--info {
            background: rgba(37, 99, 235, 0.12);
            color: #1d4ed8;
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

        .record-card__meta {
            color: var(--tabibi-muted-color);
            font-size: 0.86rem;
            font-weight: 700;
        }

        .record-card--interactive {
            transition: 0.25s ease;
        }

        .record-card--interactive:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
        }

        .avatar-circle {
            width: 72px;
            height: 72px;
            border-radius: 24px;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        }

        .avatar-fallback {
            width: 72px;
            height: 72px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--tabibi-primary-color), #14b8a6);
            color: #fff;
            font-size: 1.35rem;
            font-weight: 800;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        }

        .list-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .list-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 0.8rem;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.08);
            color: var(--tabibi-primary-color);
            font-size: 0.88rem;
            font-weight: 700;
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

        @media (max-width: 1199.98px) {
            .dashboard-shell {
                grid-template-columns: 1fr;
            }

            .sidebar-backdrop {
                display: block;
            }

            .dashboard-sidebar {
                position: fixed;
                top: 1rem;
                left: 1rem;
                bottom: 1rem;
                width: min(320px, calc(100vw - 2rem));
                max-width: calc(100vw - 2rem);
                transform: translateX(calc(-100% - 2rem));
                transition: transform 0.25s ease;
            }

            .sidebar-panel {
                height: 100%;
                overflow-y: auto;
            }

            body.sidebar-open .dashboard-sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-backdrop {
                opacity: 1;
                visibility: visible;
            }

            .topbar-inner {
                border-radius: 24px;
            }

            .topbar-meta {
                flex-wrap: wrap;
                justify-content: flex-start;
            }

            .page-header {
                flex-direction: column;
            }

            .helper-badges {
                justify-content: flex-start;
            }

            .toolbar-row {
                flex-direction: column;
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-shell {
                width: min(calc(100vw - 1rem), 1720px);
                margin: 0.5rem auto 1rem;
            }

            .topbar-inner {
                padding: 0.9rem;
            }

            .topbar-leading {
                width: 100%;
            }

            .topbar-meta {
                width: 100%;
            }

            .topbar-chip,
            .user-summary {
                width: 100%;
            }

            .logout-button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @php
        $adminUser = auth()->user();
        $roleNames = method_exists($adminUser, 'getRoleNames') ? $adminUser->getRoleNames()->toArray() : [];
        $doctorType = $adminUser?->doctor?->doctor_type;
        $clinicName = $adminUser?->clinic_center?->name ?? 'Clinic center';
        $fullName = trim(($adminUser->name ?? '') . ' ' . ($adminUser->last_name ?? ''));
        $displayName = $fullName !== '' ? $fullName : 'Dashboard User';
        $nameParts = preg_split('/\s+/', trim($displayName)) ?: [];
        $initials = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
        $userInitials = $initials !== '' ? $initials : 'TB';
        $currentStatus = request('status', 'pending');

        $brandTitle = 'Tabiby Dashboard';
        $brandSubtitle = 'Operational workflow center';
        $brandRoute = url('/');
        $userRoleLabel = ucfirst(str_replace('_', ' ', $roleNames[0] ?? 'dashboard user'));
        $navItems = [];
        $currentCompleteUrl = url()->current();
        $centerDisplay = $clinicName !== 'Clinic center' ? $clinicName : $userRoleLabel;
        $workflowCopy = 'Move between the main dashboard controls from one place.';

        if (request()->routeIs('Admin.*') || in_array('admin', $roleNames, true)) {
            $brandTitle = 'Tabiby Admin';
            $brandSubtitle = 'Operational control center';
            $brandRoute = route('Admin.index');
            $userRoleLabel = $clinicName;
            $centerDisplay = $clinicName;
            $workflowCopy = 'Manage clinic operations, appointments, pricing, and pharmacy tools.';
            $navItems = [
                [
                    'label' => 'Overview',
                    'icon' => 'fa-chart-line',
                    'url' => route('Admin.index'),
                    'active' => request()->routeIs('Admin.index'),
                ],
                [
                    'label' => 'Clinic Management',
                    'icon' => 'fa-hospital-user',
                    'url' => route('Admin.ClinicManagement.index'),
                    'active' => request()->routeIs('Admin.ClinicManagement.*') || request()->routeIs('Admin.DoctorSchedule.*'),
                ],
                [
                    'label' => 'Appointments',
                    'icon' => 'fa-calendar-check',
                    'url' => route('Admin.Appointment.index'),
                    'active' => request()->routeIs('Admin.Appointment.*'),
                ],
                [
                    'label' => 'Pharmacy',
                    'icon' => 'fa-pills',
                    'url' => route('Admin.Pharmacy.index'),
                    'active' => request()->routeIs('Admin.Pharmacy.*'),
                ],
                [
                    'label' => 'Pricing',
                    'icon' => 'fa-tags',
                    'url' => route('Admin.Pricing.index'),
                    'active' => request()->routeIs('Admin.Pricing.*'),
                ],
            ];
        } elseif (request()->routeIs('radiology.*') || $doctorType === 'radiology') {
            $brandTitle = 'Tabiby Radiology';
            $brandSubtitle = 'Imaging workflow dashboard';
            $brandRoute = route('radiology.dashboard');
            $userRoleLabel = 'Radiology doctor';
            $workflowCopy = 'Review the imaging queue and complete pending radiology visits.';
            $navItems = [
                [
                    'label' => 'Radiology Queue',
                    'icon' => 'fa-x-ray',
                    'url' => route('radiology.dashboard'),
                    'active' => request()->routeIs('radiology.dashboard'),
                ],
            ];
            if (request()->routeIs('radiology.appointments.complete.form')) {
                $navItems[] = [
                    'label' => 'Complete Visit',
                    'icon' => 'fa-file-medical',
                    'url' => $currentCompleteUrl,
                    'active' => true,
                ];
            }
        } elseif (request()->routeIs('lab.*') || $doctorType === 'lab') {
            $brandTitle = 'Tabiby Lab';
            $brandSubtitle = 'Lab workflow dashboard';
            $brandRoute = route('lab.dashboard');
            $userRoleLabel = 'Lab doctor';
            $workflowCopy = 'Review the lab queue and upload the final result for each case.';
            $navItems = [
                [
                    'label' => 'Lab Queue',
                    'icon' => 'fa-flask-vial',
                    'url' => route('lab.dashboard'),
                    'active' => request()->routeIs('lab.dashboard'),
                ],
            ];
            if (request()->routeIs('lab.appointments.complete.form')) {
                $navItems[] = [
                    'label' => 'Complete Visit',
                    'icon' => 'fa-file-medical',
                    'url' => $currentCompleteUrl,
                    'active' => true,
                ];
            }
        } elseif (request()->routeIs('pharmacy.*') || in_array('pharmacist', $roleNames, true)) {
            $brandTitle = 'Tabiby Pharmacy';
            $brandSubtitle = 'Prescription workflow dashboard';
            $brandRoute = route('pharmacy.dashboard');
            $userRoleLabel = 'Pharmacist';
            $workflowCopy = 'Track prescription status and keep the dispensing queue moving.';
            $navItems = [
                [
                    'label' => 'Pending',
                    'icon' => 'fa-hourglass-half',
                    'url' => route('pharmacy.dashboard', ['status' => 'pending']),
                    'active' => request()->routeIs('pharmacy.*') && $currentStatus === 'pending',
                ],
                [
                    'label' => 'Ready',
                    'icon' => 'fa-box-open',
                    'url' => route('pharmacy.dashboard', ['status' => 'ready']),
                    'active' => request()->routeIs('pharmacy.*') && $currentStatus === 'ready',
                ],
                [
                    'label' => 'Dispensed',
                    'icon' => 'fa-hand-holding-medical',
                    'url' => route('pharmacy.dashboard', ['status' => 'dispensed']),
                    'active' => request()->routeIs('pharmacy.*') && $currentStatus === 'dispensed',
                ],
            ];
        }
    @endphp

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="dashboard-shell">
        <aside class="dashboard-sidebar" id="dashboardSidebar">
            <div class="sidebar-panel">
                <section class="sidebar-card">
                    <span class="sidebar-card__eyebrow">
                        <i class="fas fa-sliders"></i>
                        Control Panel
                    </span>
                    <h2 class="sidebar-card__title">{{ $brandTitle }}</h2>
                    <p class="sidebar-card__copy">{{ $workflowCopy }}</p>
                </section>

                <section class="sidebar-section">
                    <span class="sidebar-section__label">Navigation</span>
                    <nav class="sidebar-nav">
                        @foreach ($navItems as $navItem)
                            <a href="{{ $navItem['url'] }}" class="sidebar-link @if ($navItem['active']) active @endif">
                                <span class="sidebar-link__icon">
                                    <i class="fas {{ $navItem['icon'] }}"></i>
                                </span>
                                <span class="sidebar-link__content">
                                    <span>{{ $navItem['label'] }}</span>
                                    <span class="sidebar-link__meta">{{ $brandSubtitle }}</span>
                                </span>
                            </a>
                        @endforeach
                    </nav>
                </section>

            </div>
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div class="topbar-inner">
                    <div class="topbar-leading">
                        <button type="button" class="sidebar-toggle d-xl-none" id="sidebarToggle"
                            aria-label="Open dashboard sidebar">
                            <i class="fas fa-bars"></i>
                        </button>

                        <a class="brand-block" href="{{ $brandRoute }}">
                            <span class="brand-mark">
                                <img src="{{ asset('project_icon/logo/logo_white.png') }}" alt="Tabiby logo">
                            </span>

                            <span class="brand-copy">
                                <span class="brand-title">{{ $brandTitle }}</span>
                                <span class="brand-subtitle">{{ $brandSubtitle }}</span>
                            </span>
                        </a>
                    </div>

                    <div class="topbar-meta">
                        <div class="topbar-chip">
                            <span class="topbar-chip__label">Center</span>
                            <span class="topbar-chip__value">{{ $centerDisplay }}</span>
                        </div>

                        <div class="user-summary">
                            <span class="user-avatar">{{ $userInitials }}</span>
                            <span class="user-meta">
                                <span class="user-name">{{ $displayName }}</span>
                                <span class="user-role">{{ $userRoleLabel }}</span>
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
            </header>

            <main class="content-wrapper">
                <div class="container-xxl px-0">
                    @if (session('success') || session('error') || $errors->any())
                        <div class="alert-stack">
                            @if (session('success'))
                                <div class="alert-banner alert-banner--success">
                                    <span class="alert-banner__icon">
                                        <i class="fas fa-circle-check"></i>
                                    </span>
                                    <div>
                                        <p class="alert-banner__title">Success</p>
                                        <p class="alert-banner__copy">{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert-banner alert-banner--danger">
                                    <span class="alert-banner__icon">
                                        <i class="fas fa-circle-exclamation"></i>
                                    </span>
                                    <div>
                                        <p class="alert-banner__title">Something needs attention</p>
                                        <p class="alert-banner__copy">{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert-banner alert-banner--danger">
                                    <span class="alert-banner__icon">
                                        <i class="fas fa-triangle-exclamation"></i>
                                    </span>
                                    <div>
                                        <p class="alert-banner__title">Please review the highlighted details</p>
                                        <p class="alert-banner__copy">{{ $errors->first() }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            if (!sidebarToggle || !sidebarBackdrop) {
                return;
            }

            const closeSidebar = function() {
                body.classList.remove('sidebar-open');
            };

            sidebarToggle.addEventListener('click', function() {
                body.classList.toggle('sidebar-open');
            });

            sidebarBackdrop.addEventListener('click', closeSidebar);

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1200) {
                    closeSidebar();
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
