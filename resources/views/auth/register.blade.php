@extends('layouts.client.client')
@section('title', 'Đăng ký | CineVN')

@section('content')
<style>
    .register-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 2rem;
        background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
        transition: background 0.3s ease;
    }

    [data-theme="dark"] .register-container {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }

    .register-box {
        max-width: 800px;
        margin: auto;
        background: #ffffff;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    [data-theme="dark"] .register-box {
        background: #1f2937;
        color: #f3f4f6;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }

    .register-box h2 {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.75rem;
        text-align: center;
    }

    [data-theme="dark"] .register-box h2 {
        color: #f3f4f6;
    }

    .register-subtitle {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 2.5rem;
        text-align: center;
    }

    [data-theme="dark"] .register-subtitle {
        color: #9ca3af;
    }

    .form-label {
        font-size: 15px;
        color: #374151;
        margin-bottom: 8px;
        font-weight: 500;
        display: block;

    }

    .dark .form-label {

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

    .register-form-container {
        display: flex;
        gap: 48px;
        flex-wrap: wrap;
    }

    .register-form-left {
        flex: 1;
        min-width: 300px;
    }

    .register-form-right {
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

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #e5e7eb;
    }

    .divider-text {
        padding: 0 1.25rem;
        font-size: 0.9rem;
        color: #6b7280;
    }

    [data-theme="dark"] .divider::before,
    [data-theme="dark"] .divider::after {
        border-bottom-color: #4b5563;
    }

    [data-theme="dark"] .divider-text {
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .register-box {
            padding: 2rem;
        }

        .register-form-container {
            flex-direction: column;
            gap: 24px;
        }

        .register-form-right {
            width: 100%;
        }
    }
</style>

<div class="register-container">
    <div class="register-box">
        <h2>Tạo tài khoản mới</h2>
        <p class="register-subtitle">Lần đầu sử dụng CineVN? Hãy tạo tài khoản ngay!</p>

        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf
            <div class="register-form-container">
                <div class="register-form-left">
                    <div class="form-group">
                        <label class="form-label" for="name">Họ và tên</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Nhập họ tên"
                               value="{{ old('name') }}" required>
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email"
                               value="{{ old('email') }}" required>
                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone_number">Số điện thoại</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control"
                               placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}">
                        @error('phone_number') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="date_of_birth">Ngày sinh</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                               value="{{ old('date_of_birth') }}">
                        @error('date_of_birth') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Tạo mật khẩu" required>
                        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control" placeholder="Nhập lại mật khẩu" required>
                        @error('password_confirmation') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="register-form-right">
                    <button type="submit" class="btn btn-primary mb-4">Tạo tài khoản</button>

                    <div class="divider">
                        <span class="divider-text">Hoặc đăng ký bằng</span>
                    </div>

                    <div class="flex flex-col gap-3 mt-3 mb-4">
                        <a href="{{ route('auth.google') }}" class="social-btn google-btn">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.24 10.667v2.828h4.243c-.171 1.086-1.286 3.171-4.243 3.171-2.554 0-4.629-2.114-4.629-4.714s2.075-4.714 4.629-4.714c1.454 0 2.418.614 2.971 1.143l2.029-1.971C15.8 4.286 14.086 3 12.24 3 7.589 3 4 6.589 4 11.24s3.589 8.24 8.24 8.24c4.771 0 7.943-3.357 7.943-8.24 0-.554-.057-1.086-.143-1.6h-7.8z"/>
                            </svg>
                            Đăng ký bằng Google
                        </a>
                        <a href="#" class="social-btn facebook-btn">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.987h-2.54V12h2.54V9.845c0-2.509 1.493-3.89 3.776-3.89 1.094 0 2.24.195 2.24.195v2.459h-1.264c-1.245 0-1.637.772-1.637 1.562V12h2.779l-.444 2.892h-2.335v6.987C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                            Đăng ký bằng Facebook
                        </a>
                    </div>

                    <div class="mt-5 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Đã có tài khoản?
                            <a href="{{ route('login') }}" class="text-link">Đăng nhập</a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const registerForm = document.getElementById('register-form');
        const submitButton = document.querySelector('button[type="submit"]');
        
        submitButton.addEventListener('click', function() {
            registerForm.submit();
        });
    });
</script>
@endsection
