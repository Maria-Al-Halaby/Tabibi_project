<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tabiby Doctor Login</title>

    <link rel="icon" href="{{ asset('project_icon/logo.png') }}?v=3" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --tabibi-primary: #0f766e;
            --tabibi-primary-strong: #115e59;
            --tabibi-secondary: #2563eb;
            --tabibi-accent: #f59e0b;
            --tabibi-text: #0f172a;
            --tabibi-muted: #64748b;
            --tabibi-border: rgba(148, 163, 184, 0.2);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--tabibi-text);
            background:
                radial-gradient(circle at top left, rgba(45, 212, 191, 0.16), transparent 28%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.14), transparent 24%),
                linear-gradient(180deg, #f8fffe 0%, #f3f8fb 48%, #edf3f7 100%);
        }

        .login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
        }

        .login-frame {
            width: min(1160px, 100%);
            display: grid;
            grid-template-columns: 1.08fr 0.92fr;
            border-radius: 34px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 32px 80px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(18px);
        }

        .login-showcase {
            position: relative;
            padding: 3rem;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(45, 212, 191, 0.28), transparent 34%),
                radial-gradient(circle at bottom right, rgba(59, 130, 246, 0.18), transparent 28%),
                linear-gradient(160deg, #0f766e 0%, #115e59 42%, #0f172a 100%);
            color: #f8fafc;
        }

        .login-showcase::before,
        .login-showcase::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .login-showcase::before {
            width: 280px;
            height: 280px;
            right: -80px;
            top: -60px;
            background: rgba(255, 255, 255, 0.09);
        }

        .login-showcase::after {
            width: 220px;
            height: 220px;
            left: -60px;
            bottom: -70px;
            background: rgba(37, 99, 235, 0.14);
        }

        .brand-row {
            display: block;
            width: min(320px, 100%);
            margin: 0 auto 2rem;
        }

        .brand-mark {
            display: block;
            width: 100%;
        }

        .brand-mark img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .showcase-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2rem, 3vw, 3.35rem);
            line-height: 1;
            letter-spacing: -0.05em;
            margin-bottom: 1rem;
        }

        .showcase-copy {
            max-width: 520px;
            font-size: 1rem;
            color: rgba(248, 250, 252, 0.8);
            margin-bottom: 2rem;
        }

        .showcase-grid {
            display: grid;
            gap: 1rem;
        }

        .showcase-card {
            padding: 1rem 1.15rem;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .showcase-card__label {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(248, 250, 252, 0.7);
            margin-bottom: 0.35rem;
        }

        .showcase-card__value {
            font-size: 1.1rem;
            font-weight: 800;
            margin: 0;
        }

        .login-panel {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.92);
        }

        .login-panel__header {
            margin-bottom: 2rem;
        }

        .login-panel__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.1);
            color: var(--tabibi-primary);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .login-panel__title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.8rem, 2.4vw, 2.5rem);
            letter-spacing: -0.04em;
            margin-bottom: 0.6rem;
        }

        .login-panel__copy {
            color: var(--tabibi-muted);
            margin-bottom: 0;
        }

        .form-label {
            font-size: 0.92rem;
            font-weight: 800;
            color: #334155;
            margin-bottom: 0.55rem;
        }

        .field-shell {
            position: relative;
        }

        .field-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1rem;
            pointer-events: none;
        }

        .form-control {
            height: 58px;
            border-radius: 20px;
            border: 1px solid var(--tabibi-border);
            background: rgba(248, 250, 252, 0.9);
            padding-left: 2.9rem;
            padding-right: 3rem;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: rgba(15, 118, 110, 0.36);
            background: #fff;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 0.65rem;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #64748b;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .password-toggle:hover {
            background: rgba(148, 163, 184, 0.12);
        }

        .login-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin: 0.25rem 0 1.6rem;
            color: var(--tabibi-muted);
            font-size: 0.88rem;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.08);
            color: var(--tabibi-secondary);
            font-weight: 700;
        }

        .btn-login {
            width: 100%;
            border: 0;
            border-radius: 20px;
            padding: 0.95rem 1.25rem;
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--tabibi-primary), #14b8a6);
            box-shadow: 0 20px 36px rgba(15, 118, 110, 0.18);
        }

        .btn-login:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 24px 42px rgba(15, 118, 110, 0.22);
        }

        .support-copy {
            margin-top: 1rem;
            font-size: 0.88rem;
            color: var(--tabibi-muted);
            text-align: center;
        }

        .alert {
            border-radius: 18px;
            border: 0;
        }

        .back-link {
            margin-top: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.9rem 1rem;
            border-radius: 18px;
            color: var(--tabibi-primary);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.92rem;
            background: rgba(15, 118, 110, 0.08);
            border: 1px solid rgba(15, 118, 110, 0.14);
            transition: 0.25s ease;
        }

        .back-link:hover {
            color: #fff;
            background: linear-gradient(135deg, var(--tabibi-primary), #14b8a6);
            border-color: transparent;
            transform: translateY(-1px);
        }

        @media (max-width: 991.98px) {
            .login-frame {
                grid-template-columns: 1fr;
            }

            .login-showcase,
            .login-panel {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <main class="login-shell">
        <div class="login-frame">
            <section class="login-showcase">
                <div class="brand-row">
                    <span class="brand-mark">
                        <img src="{{ asset('project_icon/logo/logo_white.png') }}" alt="Tabiby logo">
                    </span>
                </div>

                <h1 class="showcase-title">Access your clinical dashboard.</h1>
                <p class="showcase-copy">
                    Sign in to view your schedule, manage appointments, and complete your clinical tasks.
                </p>

                <div class="showcase-grid">
                    <div class="showcase-card">
                        <div class="showcase-card__label">Radiology Doctors</div>
                        <p class="showcase-card__value">View and complete radiology appointments.</p>
                    </div>

                    <div class="showcase-card">
                        <div class="showcase-card__label">Lab Doctors</div>
                        <p class="showcase-card__value">View and complete laboratory appointments.</p>
                    </div>
                </div>
            </section>

            <section class="login-panel">
                <div class="login-panel__header">
                    <span class="login-panel__eyebrow">
                        <i class="bi bi-stethoscope"></i>
                        Doctor Login
                    </span>
                    <h2 class="login-panel__title">Welcome back</h2>
                    <p class="login-panel__copy">Use your phone number and password to access your dashboard.</p>
                </div>

                @if (session('message'))
                    <div class="alert alert-success shadow-sm mb-4" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4" role="alert">
                        @foreach ($errors->all() as $error)
                            {{ $error }}@if (!$loop->last)<br>@endif
                        @endforeach
                    </div>
                @endif

                <form action="" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="phone" class="form-label">Phone Number</label>
                        <div class="field-shell">
                            <span class="field-icon"><i class="bi bi-telephone"></i></span>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Enter your phone number" required>
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_input" class="form-label">Password</label>
                        <div class="field-shell">
                            <span class="field-icon"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password_input"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter your password" required>
                            <button class="password-toggle" type="button" id="togglePassword" aria-label="Toggle password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="login-meta">
                        <span>Protected account access</span>
                        <span class="status-chip">
                            <i class="bi bi-shield-lock-fill"></i>
                            Encrypted session
                        </span>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Log in
                    </button>
                </form>

                <a href="{{ route('login') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Back to admin login
                </a>

                <p class="support-copy">If you cannot access your account, contact the system administrator.</p>
            </section>
        </div>
    </main>

    <script>
        const togglePasswordButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password_input');

        togglePasswordButton?.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const nextType = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';

            passwordInput.setAttribute('type', nextType);
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
