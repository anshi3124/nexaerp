@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-header">
        <div class="logo">Nexa<span>ERP</span></div>
        <p>Business Management & CRM Platform</p>
    </div>

    <div class="auth-body">

        <div class="demo-card">
            <h6><i class="bi bi-info-circle me-1"></i> Demo Account</h6>
            <div class="credential">
                <span>Email</span>
                <span>demo@example.com</span>
            </div>
            <div class="credential">
                <span>Password</span>
                <span>Demo@123</span>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:0.88rem;">
                        Remember me
                    </label>
                </div>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       style="font-size:0.88rem; color:#667eea; text-decoration:none;">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Sign In
            </button>
        </form>
    </div>
@endsection