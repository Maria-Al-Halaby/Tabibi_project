{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
</head>

<body>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="enter your email">
        <input type="password" name="password" placeholder="enter your password">
        <input type="submit" value="login">
    </form>
    @if ($errors != null)
        <p style="color: red;">{{ $errors->message }}</p>
    @endif
</body>

</html>



 --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" href="{{ asset('project_icon/logo.png') }}?v=3" type="image/png">

    <style>
        /* اللون الأساسي للتصميم */
        :root {
            --main-color: #008080;
            /* لون أخضر مائي داكن */
        }

        /* تنسيق للجسم لضمان أن النموذج يتوسط الشاشة في وضع الموبايل */
        body {
            background-color: #f8f9fa;
            /* خلفية فاتحة */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* يبدأ من الأعلى قليلاً */
            padding-top: 50px;
        }

        /* تنسيق الحاوية للنموذج لتقييد العرض في شاشات الجوال */
        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }

        /* تنسيق حقول الإدخال لتشبه التصميم (حواف مستديرة وخلفية فاتحة) */
        .form-control-custom {
            border: none;
            /* هام: إزالة الحدود */
            padding: 15px 20px;
            background-color: #e9ecef;
            /* لون خلفية الحقل */
            box-shadow: none !important;
        }

        /* تنسيق زر الإدخال الملحق (الذي يحتوي على الأيقونة) */
        .input-group-text-custom {
            border-radius: 15px 0 0 15px;
            background-color: #e9ecef;
            border: none;
            color: #6c757d;
            /* لون الأيقونة */
        }

        /* تنسيق زر إظهار كلمة المرور في نهاية الحقل */
        .btn-eye-toggle {
            border: none;
            border-radius: 0 15px 15px 0;
            background-color: #e9ecef;
            color: #6c757d;
        }

        /* تنسيق حقل الإدخال ليأخذ الاستدارة المناسبة */
        .form-control-rounded-end {
            border-radius: 0 15px 15px 0 !important;
            padding-left: 10px;
        }

        /* تنسيق زر الدخول */
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
</head>

<body>
    <div class="container login-card">
        <div class="text-center mb-4">
            <i class="bi bi-heart-pulse-fill display-3" style="color: var(--main-color);"></i>
            <h1 class="h3 fw-bold mt-2" style="color: var(--main-color);">Tabiby</h1>
        </div>

        <h2 class="mt-4 mb-5 fw-bold text-center" style="color: var(--main-color);">Log in</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text input-group-text-custom"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email"
                        class="form-control form-control-custom form-control-rounded-end @error('email') is-invalid @enderror"
                        placeholder="Enter your email" required value="{{ old('email') }}">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text input-group-text-custom"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password_input"
                        class="form-control form-control-custom @error('password') is-invalid @enderror"
                        placeholder="Enter your password" required>
                    <button class="btn btn-light btn-eye-toggle" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

            </div>

            <div class="d-grid mb-4 mt-5">
                <input type="submit" value="Log in" class="btn btn-main shadow-sm">
            </div>

            @if ($errors->any() && !($errors->has('email') || $errors->has('password')))
                <div class="alert alert-danger text-center small mb-3" role="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif



        </form>

    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            const passwordInput = document.getElementById('password_input');
            const icon = this.querySelector('i');

            // تبديل نوع الحقل بين 'password' و 'text'
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // تبديل الأيقونة
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
