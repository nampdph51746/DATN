@extends('layouts.client.client')
@section('title', 'Đăng nhập | CineVN')

@section('content')
<style>
    .login-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 2rem;
        background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
        transition: background 0.3s ease;
    }


    [data-theme="dark"] .login-container {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }

    .login-box {
        max-width: 800px;
        margin: auto;
        background: #ffffff;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    [data-theme="dark"] .login-box {
        background: #1f2937;
        color: #f3f4f6;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }

    .login-box h2 {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.75rem;
        text-align: center;
    }

    [data-theme="dark"] .login-box h2 {
        color: #f3f4f6;
    }

    .login-subtitle {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .dark .login-box h2 {
        color: #f9fafb;
    }


    .form-label {
        font-size: 15px;
        color: #374151;
        margin-bottom: 8px;
        font-weight: 500;
        display: block;
    }

    .dark .login-box label {

        color: #d1d5db;
    }

    .form-control {
        width: 100%;
        padding: 0.9rem;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #111827;

        margin-bottom: 12px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    [data-theme="dark"] .form-control {
        background: #374151;
        color: #f3f4f6;

        border-color: #4b5563;
    }

    .form-control:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }

    .btn-primary {
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        font-weight: 600;
        padding: 0.9rem;
        border-radius: 10px;
        border: none;
        transition: transform 0.2s ease, background 0.3s ease;
        width: 100%;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.8rem;
        border-radius: 10px;
        font-weight: 500;
        font-size: 15px;
        gap: 10px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .google-btn {
        background: #f3f4f6;
        color: #1f2937;
        border: 1px solid #d1d5db;
    }

    .google-btn:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    [data-theme="dark"] .google-btn {
        background: #4b5563;
        color: #f3f4f6;
        border-color: #6b7280;
    }

    .facebook-btn {
        background: #4267B2;
        color: #fff;
    }

    .facebook-btn:hover {
        background: #365899;
        transform: translateY(-2px);
    }

    .text-link {
        color: #2563eb;
        font-weight: 500;
        font-size: 15px;
        transition: color 0.3s ease;
    }

    .text-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    .text-danger {
        font-size: 14px;
        color: #ef4444;
        margin-top: 4px;
    }

    .login-form-container {
        display: flex;
        gap: 48px;
        flex-wrap: wrap;
    }

    .login-form-left {
        flex: 1;
        min-width: 300px;
    }

    .login-form-right {
        width: 320px;
        min-width: 300px;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 2rem 0;
    }
=======

    .dark .google-btn {

        background-color: #4b5563;
        color: #fff;
    }

    .facebook-btn {

        background-color: #1877f2;

        color: #fff;
    }

    .divider-text {
        padding: 0 1.25rem;
        font-size: 0.9rem;
        color: #6b7280;
    }

        background-color: #145fbe;
    }

    .text-sm {
        font-size: 14px;

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        font-size: 14px;
    }

    .text-link {
        color: #3b82f6;
        font-weight: 500;

    .form-check-input {
        margin-right: 0.6rem;
        accent-color: #2563eb;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 0.9rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        font-size: 15px;
    }

    [data-theme="dark"] .alert-success {
        background: #064e3b;
        color: #6ee7b7;
    }

    @media (max-width: 768px) {
        .login-box {
            padding: 2rem;
        }

        .login-form-container {
            flex-direction: column;
            gap: 24px;
        }

        .login-form-right {
            width: 100%;
        }
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
                        <label class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input"> Ghi nhớ đăng nhập
                        </label>
                        <a href="{{ route('password.request') }}" class="text-link">Quên mật khẩu?</a>
                    </div>
                </form>
            </div>

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


                <div class="flex flex-col gap-3 mt-3 mb-4">
                    <a href="{{ route('auth.google') }}" class="social-btn google-btn">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.24 10.667v2.828h4.243c-.171 1.086-1.286 3.171-4.243 3.171-2.554 0-4.629-2.114-4.629-4.714s2.075-4.714 4.629-4.714c1.454 0 2.418.614 2.971 1.143l2.029-1.971C15.8 4.286 14.086 3 12.24 3 7.589 3 4 6.589 4 11.24s3.589 8.24 8.24 8.24c4.771 0 7.943-3.357 7.943-8.24 0-.554-.057-1.086-.143-1.6h-7.8z"/>
                        </svg>
                        Đăng nhập bằng Google
                    </a>
                    <a href="#" class="social-btn facebook-btn">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.987h-2.54V12h2.54V9.845c0-2.509 1.493-3.89 3.776-3.89 1.094 0 2.24.195 2.24.195v2.459h-1.264c-1.245 0-1.637.772-1.637 1.562V12h2.779l-.444 2.892h-2.335v6.987C18.343 21.128 22 16.991 22 12z"/>
                        </svg>
                        Đăng nhập bằng Facebook
                    </a>
                </div>
            </div>

                <div class="mt-5 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Chưa có tài khoản?
                        <a href="{{ route('register') }}" class="text-link">Đăng ký</a>
                    </p>
                </div>

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
