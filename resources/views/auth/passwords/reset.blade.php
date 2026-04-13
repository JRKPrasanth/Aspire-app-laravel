@extends('layouts_login.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
        <div class="card-header text-center bg-white border-0">
            <h4 class="fw-bold mb-0">Reset Password</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('password.request') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email', $email ?? '') }}" required autofocus>

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password" required>

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input id="password-confirm" type="password"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation" required>

                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

