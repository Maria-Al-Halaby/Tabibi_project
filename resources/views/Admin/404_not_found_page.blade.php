{{-- @extends('layouts.admin-app')

@section("title" , "404 not found page")


@section('content')

<h1>404 not found page</h1>

@endsection --}}


@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
    <style>
        :root {
            --main-color: #008080;
        }

        .error-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            color: var(--main-color);
            line-height: 1;
            margin-bottom: 0;
            opacity: 0.2;
        }

        .error-message {
            margin-top: -50px;
        }

        .btn-home {
            background-color: var(--main-color);
            border-color: var(--main-color);
            border-radius: 12px;
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-home:hover {
            background-color: #006666;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 128, 128, 0.3);
        }

        .icon-box {
            font-size: 4rem;
            color: var(--main-color);
            margin-bottom: 20px;
        }
    </style>

    <div class="container error-container">
        <div class="row">
            <div class="col-12">
                <div class="error-code">404</div>
                
                <div class="error-message">
                    <div class="icon-box">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h2 class="fw-bold mb-3" style="color: #333;">Oops! Page Not Found</h2>
                    <p class="text-muted mb-5 fs-5">
                        The page you are looking for might have been removed, <br> 
                        had its name changed, or is temporarily unavailable.
                    </p>
                    
                    <a href="{{ url('/') }}" class="btn-home shadow-sm">
                        <i class="bi bi-house-door-fill me-2"></i> Back to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection