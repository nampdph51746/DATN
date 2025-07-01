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

    .dark .login-container {
        background-color: #111827;
    }

    .login-box {
        max-width: 480px;
        margin: auto;
        background-color: #fff;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: background-color 0.3s, color 0.3s;
    }

    .dark .login-box {
        background-color: #1f2937;
        color: #f3f4f6;
    }

    .login-box h2 {
        font-size: 24px;
        font-weight: bold;
        color: #1f2937;
    }

    .dark .login-box h2 {
        color: #f9fafb;
    }

    .login-box label {
        font-size: 14px;
        color: #374151;
    }

    .dark .login-box label {
        color: #d1d5db;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background-color: #fff;
        color: #111827;
    }

    .dark .form-control {
        background-color: #374151;
        color: #f9fafb;
        border-color: #4b5563;
    }

    .form-control:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }

    .btn-primary {
        background-color: #3b82f6;
        color: #fff;
        font-weight: 600;
        padding: 0.75rem;
        border-radius: 8px;
        transition: background-color 0.3s;
        border: none;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .google-btn {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    .google-btn:hover {
        background-color: #e5e7eb;
    }

    .dark .google-btn {
        background-color: #4b5563;
        color: #fff;
    }

    .facebook-btn {
        background-color: #1877f2;
        color: #fff;
    }

    .facebook-btn:hover {
        background-color: #145fbe;
    }

    .text-sm {
        font-size: 14px;
    }

    .text-link {
        color: #3b82f6;
        font-weight: 500;
    }

    .text-link:hover {
        text-decoration: underline;
    }
</style>

<div class="login-container">
    <div class="login-box">
        <!-- Tiêu đề -->
        <h2 class="mb-3">Đăng Nhập</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Vui lòng nhập thông tin để đăng nhập vào hệ thống.</p>

        <!-- Thông báo trạng thái -->
        @if (session('status'))
            <div class="alert alert-success bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 p-3 rounded mb-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control mt-1" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password">Mật khẩu</label>
                    <a href="{{ route('password.request') }}" class="text-sm text-link">Quên mật khẩu?</a>
                </div>
                <input type="password" id="password" name="password" class="form-control mt-1" required>
                @error('password')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label text-sm" for="remember_me">Ghi nhớ đăng nhập</label>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
            </div>
        </form>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-3">Hoặc đăng nhập với</p>
        <div class="d-grid gap-2 mb-3">
            <a href="#" class="social-btn google-btn"><i class="bx bxl-google me-2"></i> Google</a>
            <a href="#" class="social-btn facebook-btn"><i class="bx bxl-facebook me-2"></i> Facebook</a>
        </div>

        <p class="text-sm text-center text-gray-600 dark:text-gray-400 mt-4">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="text-link">Đăng ký</a>
        </p>
    </div>
</div>
@endsection
