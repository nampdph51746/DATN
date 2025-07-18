@extends('layouts.client.client')
@section('title', 'Thông tin tài khoản | CineVN')

@section('content')
<style>
    .profile-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 2rem;
        background-color: #f9fafb;
        transition: background-color 0.3s;
    }

    .dark .profile-container {
        background-color: #111827;
    }

    .profile-box {
        max-width: 520px;
        margin: auto;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
        transition: background-color 0.3s, color 0.3s;
    }

    .dark .profile-box {
        background-color: #1f2937;
        color: #f9fafb;
    }

    .profile-box h2 {
        font-size: 24px;
        font-weight: bold;
        color: #1f2937;
    }

    .dark .profile-box h2 {
        color: #f9fafb;
    }

    .profile-box .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }

    .dark .form-label {
        color: #d1d5db;
    }

    .profile-box p {
        margin-bottom: 1rem;
        font-size: 15px;
        color: #4b5563;
    }

    .dark .profile-box p {
        color: #e5e7eb;
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

    .btn-soft-primary {
        background-color: #e0e7ff;
        color: #1d4ed8;
        font-weight: 600;
        padding: 0.75rem;
        border-radius: 8px;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-soft-primary:hover {
        background-color: #c7d2fe;
    }

    .btn-danger {
        background-color: #ef4444;
        color: #fff;
        font-weight: 600;
        padding: 0.75rem;
        border-radius: 8px;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }
</style>

<div class="profile-container">
    <div class="profile-box">
        <h2 class="mb-3">Thông tin tài khoản</h2>
        <p class="text-sm text-muted dark:text-gray-400 mb-4">Thông tin cá nhân của bạn được hiển thị bên dưới.</p>

        <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <p>{{ Auth::user()->name }}</p>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <p>{{ Auth::user()->email }}</p>
        </div>

        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <p>{{ Auth::user()->phone_number ?? 'Chưa cập nhật' }}</p>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <p>{{ Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('d/m/Y') : 'Chưa cập nhật' }}</p>
        </div>

        <div class="mb-4">
            <label class="form-label">Vai trò</label>
            <p>{{ Auth::user()->role }}</p>
        </div>

        @if (Auth::check() && in_array(Auth::user()->role, ['admin', 'staff']))
            <div class="mb-3 d-grid">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Đi đến Trang Admin</a>
            </div>
        @endif

        <div class="mb-3 d-grid">
            <a href="{{ route('client.home') }}" class="btn btn-soft-primary">Quay lại Trang Chủ</a>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="d-grid">
                <button class="btn btn-danger" type="submit">Đăng xuất</button>
            </div>
        </form>
    </div>
</div>
@endsection
