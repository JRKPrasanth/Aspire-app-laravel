@extends('layouts_login.app')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    main.py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .auth-wrap {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 28px 14px;
        background:
            radial-gradient(1200px 500px at 10% 10%, rgba(99, 102, 241, .18), transparent 55%),
            radial-gradient(900px 450px at 90% 40%, rgba(56, 189, 248, .16), transparent 55%),
            radial-gradient(900px 450px at 70% 90%, rgba(16, 185, 129, .14), transparent 55%),
            linear-gradient(180deg, #f6f8fc, #eef2ff);
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        border: 0;
        border-radius: 22px;
        box-shadow: 0 18px 55px rgba(15, 23, 42, .14);
        overflow: hidden;
        background: #fff;
        backdrop-filter: blur(6px);
    }

    .brand-logo {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        box-shadow: inset 0 0 0 1px rgba(99, 102, 241, .15);
        margin: 0 auto 12px auto;
        overflow: hidden;
    }

    .brand-logo img {
        width: 44px;
        height: 44px;
        object-fit: contain;
    }

    .title {
        font-weight: 700;
        font-size: 22px;
        color: #da251c;
        margin-bottom: 4px;
        text-align: center;
    }

    .subtitle {
        font-size: 13px;
        color: #64748b;
        text-align: center;
        margin-bottom: 18px;
    }

    .form-label {
        font-size: 13px;
        color: #334155;
        font-weight: 600;
    }

    .form-control,
    .form-select {
        border-radius: 14px;
        padding: 12px 14px;
        border: 1px solid rgba(148, 163, 184, .6);
        background: rgba(255, 255, 255, .8);
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 .2rem rgba(99, 102, 241, .18);
        border-color: rgba(99, 102, 241, .55);
    }

    .btn-primary-modern {
        border-radius: 14px;
        padding: 12px 14px;
        font-weight: 700;
        background: linear-gradient(90deg, #4f46e5, #2563eb);
        border: 0;
        box-shadow: 0 10px 24px rgba(37, 99, 235, .25);
    }

    .btn-primary-modern:hover {
        filter: brightness(.98);
    }

    .btn-soft {
        border-radius: 14px;
        padding: 12px 14px;
        font-weight: 600;
        background: #ffffff;
        border: 1px solid rgba(148, 163, 184, .55);
        color: #0f172a;
    }

    .btn-soft:hover {
        background: #f8fafc;
    }

    .divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 14px 0;
        color: #94a3b8;
        font-size: 12px;
        user-select: none;
    }

    .divider::before,
    .divider::after {
        content: "";
        height: 1px;
        flex: 1;
        background: rgba(148, 163, 184, .45);
    }

    .remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 2px;
    }

    footer {
        background: #111;
        color: #fff9f9;
    }

    .link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
    }

    .link:hover {
        text-decoration: underline;
    }

    .input-group .btn {
        border-radius: 14px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-color: rgba(148, 163, 184, .6);
    }

    .bg-primary {
        background-image: linear-gradient(45deg, #dd352d 0%, #e4e6e9 99%, #ecadaa 100%) !important;
    }

    .bg-login {
        content: "";
        top: 0;
        left: 50%;
        right: 0;
        bottom: 0;
        border-radius: 0px;
        position: absolute;
        transform-origin: bottom;
        background: var(--bs-green);
    }

    .custom-login {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        min-width: 100%;
        min-height: 100vh;
        background: var(--bs-white);
    }

        .login-bg-1 {
        position: absolute;
        bottom: -66px;
        left: -7%;
        z-index: 1;
        max-height: 600px;
        }

    .login-bg-2 {
        position: absolute;
        bottom: 0;
        right: 0;
        z-index: 1;
    }

    .login-bg-1 {
        display: none;
    }

    .login-bg-2 {
        display: none;
    }

    @media (min-width: 600px) {
        .login-bg-1 {
            display: block;
        }

        .login-bg-2 {
            display: block;
        }
    }
</style>
<div class="login-bg-img">
    <img src="{{ asset('images/profile_images/login.svg') }}" class="login-bg-1">

    <img src="{{ asset('images/profile_images/common.svg') }}" class="login-bg-2">
</div>
<div class="bg-login bg-primary"></div>
<div class="auth-wrap">
    <div class="card login-card">
        <div class="card-body p-4 p-md-5">

            <div class="brand-logo">
                <img src="{{ asset('images/profile_images/logo.png') }}" alt="Logo">
            </div>

            <div class="title">Login to Aspire</div>
            <div class="subtitle">Enter your credentials to access your account.</div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="employee_number" class="form-label">Employee Number</label>
                    <input id="employee_number" type="text"
                        class="form-control @error('employee_number') is-invalid @enderror" name="employee_number"
                        value="{{ old('employee_number') }}" required autofocus>
                    @error('employee_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                            <i class="fa fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select id="company_id" style="font-size: 13px !important;" name="company_id"
                        class="form-select select2 @error('company_id') is-invalid @enderror" required>
                        {!!
                        app(config('global.CONT'))->jCombologin(
                        'm_company_t',
                        'company_id',
                        'company_name',
                        ''
                        )
                        !!}
                    </select>

                    @error('company_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row mb-3">
                    <div class="form-check m-0">
                        <input type="hidden" name="active" value="Yes">
                        <input id="remember" type="checkbox" class="form-check-input" name="remember" {{ old('remember')
                            ? 'checked' : '' }}>
                        <label for="remember" class="form-check-label" style="font-size:13px;color:#334155;">
                            Remember Me
                        </label>
                    </div>

                    <a href="{{ route('password.request') }}" class="link">Forgot password?</a>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary-modern text-white">Login</button>
                </div>

                <div class="divider">or</div>

            <div class="d-grid gap-2 text-center">
                <a href="https://play.google.com/store/apps/details?id=testaspire.one.com&hl=en_IN" target="_blank">
                    <img src="{{ url('images/buttons/playstore.png') }}"
                        alt="Get it on Google Play" 
                        style="height:75px !important;">
                </a>
            </div>
            </form>
        </div>
    </div>
</div>
<footer class="py-1 text-center">
    <div class="container">
        <p class="mb-0">&copy; {{ date('Y') }} Dr.JRK's Research and Pharmaceuticals Pvt Ltd</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // password show & hide
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // SAFE "Remember me" suggestion:
    // If you want to remember employee_number only (not password), you can do this:
    document.addEventListener("DOMContentLoaded", function () {
        const employeeInput = document.getElementById("employee_number");
        const rememberCheckbox = document.getElementById("remember");

        if (localStorage.getItem("rememberEmployee") === "true") {
            employeeInput.value = localStorage.getItem("employee_number") || "";
            rememberCheckbox.checked = true;
        }

        document.querySelector("form").addEventListener("submit", function () {
            if (rememberCheckbox.checked) {
                localStorage.setItem("employee_number", employeeInput.value);
                localStorage.setItem("rememberEmployee", "true");
            } else {
                localStorage.removeItem("employee_number");
                localStorage.removeItem("rememberEmployee");
            }
        });
    });
</script>

@endsection