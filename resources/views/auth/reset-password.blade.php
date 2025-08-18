@extends('layouts.client.client')
@section('title', 'Đặt lại mật khẩu | CineVN')

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
        max-width: 600px;
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

    [data-theme="dark"] .login-subtitle {
        color: #9ca3af;
    }

    .form-label {
        font-size: 15px;
        color: #374151;
        margin-bottom: 8px;
        font-weight: 500;
        display: block;
    }

    [data-theme="dark"] .form-label {
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
</style>

<div class="login-container">
    <div class="login-box">
        <h2>Đặt lại mật khẩu</h2>
        <p class="login-subtitle">Nhập mật khẩu mới của bạn để hoàn tất quá trình đặt lại.</p>

        <form method="POST" action="{{ route('password.reset.submit') }}">
            @csrf
            @method('PUT')

            {{-- token ẩn --}}
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            {{-- Email --}}
            <div class="form-group">
                {{-- <label for="email" class="form-label">Email</label> --}}
                <input
                    type="hidden"
                    id="email"
                    name="email"
                    value="{{ old('email', request()->email) }}"
                    class="form-control"
                    required
                    readonly
                >
            </div>

            {{-- Mật khẩu mới --}}
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu mới</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Nhập mật khẩu mới"
                    class="form-control"
                    required
                >
            </div>

            {{-- Xác nhận mật khẩu --}}
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Nhập lại mật khẩu"
                    class="form-control"
                    required
                >
            </div>

            <button type="submit" class="btn-primary">Đặt lại mật khẩu</button>
        </form>
    </div>
</div>
@endsection
