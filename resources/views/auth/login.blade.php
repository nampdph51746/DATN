@extends('layouts.client.client')
@section('title', 'Đăng nhập | CineVN')

@section('content')
<style>
    .login-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 2rem;
        background-color: #f9fafb;
        transition: background-color 0.3s;
    }

    [data-theme="dark"] .login-container {
        background-color: #111827;
    }

    .login-box {
        max-width: 720px;
        margin: auto;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
        transition: background-color 0.3s, color 0.3s;
    }

    [data-theme="dark"] .login-box {
        background-color: #1f2937;
        color: #f9fafb;
    }

    .login-box h2 {
        font-size: 24px;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    [data-theme="dark"] .login-box h2 {
        color: #f9fafb;
    }

    .login-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    [data-theme="dark"] .login-subtitle {
        color: #9ca3af;
    }

    .form-label {
        font-size: 14px;
        color: #374151;
        margin-bottom: 6px;
        font-weight: 500;
        display: block;
    }

    [data-theme="dark"] .form-label {
        color: #d1d5db;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background-color: #fff;
        color: #111827;
        margin-bottom: 10px;
    }

    [data-theme="dark"] .form-control {
        background-color: #374151;
        color: #f9fafb;
        border-color: #4b5563;
    }

    .form-control:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }

    .btn-primary {
        background-color: #3b82f6;
        color: #fff;
        font-weight: 600;
        padding: 0.75rem;
        border-radius: 8px;
        border: none;
        transition: background-color 0.3s;
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.65rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 15px;
        gap: 8px;
        transition: background-color 0.3s;
        width: 100%;
    }

    .google-btn {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    .google-btn:hover {
        background-color: #e5e7eb;
    }

    [data-theme="dark"] .google-btn {
        background-color: #4b5563;
        color: #fff;
    }

    .facebook-btn {
        background-color: #4267B2;
        color: #fff;
    }

    .facebook-btn:hover {
        background-color: #365899;
    }

    .text-link {
        color: #3b82f6;
        font-weight: 500;
        font-size: 14px;
    }

    .text-link:hover {
        text-decoration: underline;
    }

    .text-danger {
        font-size: 13px;
        color: #ef4444;
    }

    .login-form-container {
        display: flex;
        gap: 40px;
    }

    .login-form-left {
        flex: 1;
    }

    .login-form-right {
        width: 280px;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #e5e7eb;
    }

    .divider-text {
        padding: 0 1rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    [data-theme="dark"] .divider::before,
    [data-theme="dark"] .divider::after {
        border-bottom-color: #4b5563;
    }

    [data-theme="dark"] .divider-text {
        color: #9ca3af;
    }

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .form-check {
        display: flex;
        align-items: center;
    }

    .form-check-input {
        margin-right: 0.5rem;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 14px;
    }

    [data-theme="dark"] .alert-success {
        background-color: #064e3b;
        color: #6ee7b7;
    }
</style>


<div class="login-container">
    <div class="login-box">
        <h2>Đăng Nhập</h2>
        <p class="login-subtitle">Vui lòng nhập thông tin để đăng nhập vào hệ thống.</p>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="login-form-container">
            <div class="login-form-left">
                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="Nhập email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control" 
                               placeholder="Nhập mật khẩu" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <label>
                            <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                        </label>
                        <a href="{{ route('password.request') }}" class="text-link">Quên mật khẩu?</a>
                    </div>
                </form>
            </div>

            <div class="login-form-right">
                <button type="submit" form="login-form" class="btn btn-primary mb-4">Đăng Nhập</button>

                <div class="divider">
                    <span class="divider-text">Hoặc đăng nhập bằng</span>
                </div>

                <div class="flex flex-col gap-2 mt-2 mb-3">
                    <a href="{{ route('auth.google') }}" class="btn btn-primary">Đăng nhập bằng Google</a>
                    <a href="#" class="social-btn facebook-btn">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Chưa có tài khoản?
                        <a href="{{ route('register') }}" class="text-link">Đăng ký</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('login-form');
        const submitButton = document.querySelector('button[type="submit"]');
        
        submitButton.addEventListener('click', function() {
            loginForm.submit();
        });
    });
</script>
@endsection