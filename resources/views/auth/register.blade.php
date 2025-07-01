@extends('layouts.client.client')
@section('title', 'Đăng ký | CineVN')

@section('content')
<style>
    .register-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 2rem;
        background-color: #f9fafb;
        transition: background-color 0.3s;
    }

    .dark .register-container {
        background-color: #111827;
    }

    .register-box {
        max-width: 520px;
        margin: auto;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
        transition: background-color 0.3s, color 0.3s;
    }

    .dark .register-box {
        background-color: #1f2937;
        color: #f9fafb;
    }

    .register-box h2 {
        font-size: 24px;
        font-weight: bold;
        color: #1f2937;
    }

    .dark .register-box h2 {
        color: #f9fafb;
    }

    .form-label {
        font-size: 14px;
        color: #374151;
        margin-bottom: 6px;
    }

    .dark .form-label {
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

    .dark .form-control {
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
    }

    .google-btn {
        background-color: #db4437;
        color: #fff;
    }

    .facebook-btn {
        background-color: #4267B2;
        color: #fff;
    }

    .text-link {
        color: #3b82f6;
        font-weight: 500;
    }

    .text-link:hover {
        text-decoration: underline;
    }

    .text-danger {
        font-size: 13px;
    }
</style>

<div class="register-container">
    <div class="register-box">
        <h2 class="mb-3">Tạo tài khoản mới</h2>
        <p class="text-sm text-muted dark:text-gray-400 mb-4">Lần đầu sử dụng CineVN? Hãy tạo tài khoản ngay!</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label class="form-label" for="name">Họ và tên</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập họ tên"
                    value="{{ old('name') }}" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email"
                    value="{{ old('email') }}" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label" for="phone_number">Số điện thoại</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control"
                    placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}">
                @error('phone_number') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label" for="date_of_birth">Ngày sinh</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                    value="{{ old('date_of_birth') }}">
                @error('date_of_birth') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label" for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Tạo mật khẩu" required>
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    placeholder="Nhập lại mật khẩu" required>
                @error('password_confirmation') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary w-100">Tạo tài khoản</button>
            </div>
        </form>

        <div class="mt-4 text-center fw-semibold">
            <p>Hoặc đăng ký bằng</p>
        </div>

        <div class="d-grid gap-2 mt-2 mb-3">
            <a href="#" class="social-btn google-btn"><i class="fab fa-google"></i> Google</a>
            <a href="#" class="social-btn facebook-btn"><i class="fab fa-facebook-f"></i> Facebook</a>
        </div>

        <div class="mt-3 text-center">
            <p class="text-sm text-muted dark:text-gray-400">Đã có tài khoản?
                <a href="{{ route('login') }}" class="text-link">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>
@endsection
