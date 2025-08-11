@extends('layouts.client.client')
@section('title', 'Thông tin tài khoản | CineVN')

@section('content')
<style>
    .profile-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 4rem 2rem;
        background-color: #f9fafb;
        transition: background-color 0.3s;
    }

    [data-theme="dark"] .profile-container {
        background-color: #111827;
    }

    .profile-box {
        max-width: 720px;
        margin: 3rem auto;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
        transition: background-color 0.3s, color 0.3s;
    }

    [data-theme="dark"] .profile-box {
        background-color: #1f2937;
        color: #f9fafb;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .profile-box h2 {
        font-size: 24px;
        font-weight: bold;
        color: #1f2937;
        margin: 0;
    }

    [data-theme="dark"] .profile-box h2 {
        color: #f9fafb;
    }

    .profile-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    [data-theme="dark"] .profile-subtitle {
        color: #9ca3af;
    }

    .profile-content {
        display: flex;
        gap: 40px;
    }

    .profile-info {
        flex: 1;
    }

    .profile-actions {
        width: 280px;
        display: flex;
        flex-direction: column;
    }

    .info-item {
        margin-bottom: 1.5rem;
    }

    .info-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }

    [data-theme="dark"] .info-label {
        color: #d1d5db;
    }

    .info-value {
        font-size: 15px;
        color: #4b5563;
        padding: 0.5rem 0;
    }

    [data-theme="dark"] .info-value {
        color: #e5e7eb;
    }

    .btn {
        display: block;
        width: 100%;
        font-weight: 600;
        padding: 0.75rem;
        border-radius: 8px;
        border: none;
        text-align: center;
        transition: all 0.3s;
        margin-bottom: 1rem;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .btn-outline {
        background-color: transparent;
        color: #3b82f6;
        border: 1px solid #3b82f6;
    }

    .btn-outline:hover {
        background-color: rgba(59, 130, 246, 0.1);
    }

    [data-theme="dark"] .btn-outline {
        color: #93c5fd;
        border-color: #93c5fd;
    }

    [data-theme="dark"] .btn-outline:hover {
        background-color: rgba(147, 197, 253, 0.1);
    }

    .btn-danger {
        background-color: #ef4444;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }

    .avatar-container {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        font-size: 32px;
        color: #6b7280;
    }

    [data-theme="dark"] .avatar {
        background-color: #4b5563;
        color: #d1d5db;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-text {
        font-weight: 600;
        font-size: 18px;
    }

    .edit-link {
        font-size: 14px;
        color: #3b82f6;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .edit-link:hover {
        text-decoration: underline;
    }

    [data-theme="dark"] .edit-link {
        color: #93c5fd;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background-color: #fff;
        color: #111827;
        font-size: 15px;
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

    .alert {
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: 14px;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
}

[data-theme="dark"] .alert-success {
    background-color: #064e3b;
    color: #6ee7b7;
}

.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
}

[data-theme="dark"] .alert-danger {
    background-color: #7f1d1d;
    color: #fca5a5;
}

</style>

<div style="display: flex; flex-direction: row; gap: 0; align-items: stretch; justify-content: center; margin: 100px auto 40px auto; padding: 0; max-width: 1000px;">
    @include('client.profile.menu')

    <div class="profile-box" style="flex: 1; background: white; border-radius: 0 12px 12px 0; padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.07); margin: 0;">
        <div class="profile-header">
            <h2>Thông tin tài khoản</h2>
        </div>
        <p class="profile-subtitle">Thông tin cá nhân của bạn được hiển thị bên dưới</p>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-content">
            <form method="POST" action="{{ route('profile.update') }}" class="profile-info" id="profile-form">
                @csrf
                @method('PATCH')

                <div class="avatar-container">
                    <div class="avatar">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="Avatar">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="avatar-text">
                        {{ Auth::user()->name }}
                        <div class="info-value">
                            {{ Auth::user()->getRoleNames()->first() }}
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <label class="info-label" for="name">Họ và tên</label>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ old('name', Auth::user()->name) }}" required>
                </div>

                <div class="info-item">
                    <label class="info-label" for="phone_number">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone_number" id="phone_number"
                        value="{{ old('phone_number', Auth::user()->phone_number) }}">
                </div>

                <div class="info-item">
                    <label class="info-label" for="date_of_birth">Ngày sinh</label>
                    <input type="date" class="form-control" name="date_of_birth" id="date_of_birth"
                        value="{{ old('date_of_birth', Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('Y-m-d') : '') }}">
                </div>

                <div class="info-item">
                    <label class="info-label" for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email"
                        value="{{ old('email', Auth::user()->email) }}" readonly>
                </div>
            </form>

            <div class="profile-actions" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <a href="{{ route('client.home') }}" class="btn btn-outline">Quay lại Trang Chủ</a>
                <button type="submit" form="profile-form" class="btn btn-primary">Cập nhật thông tin</button>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button class="btn btn-danger w-full" type="submit" onclick="return confirm('Bạn có muốn đăng xuất không?')">Đăng xuất</button>
                </form>
            </div>
        </div>
    </div>
</div>

    @include('client.footer.footer')

@endsection