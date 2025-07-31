@extends('layouts.client.client')
@section('title', 'Thông tin tài khoản | CineVN')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f5f5f5 100%);
        }
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #18181b 0%, #23272f 100%);
        }

        .profile-main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .profile-card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(31, 41, 55, 0.12);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            transition: background 0.3s;
        }

        [data-theme="dark"] .profile-card {
            background: #18181b;
            color: #e0e0e0;
        }

        @media (min-width: 900px) {
            .profile-card {
                flex-direction: row;
            }
        }

        .profile-sidebar {
            background: linear-gradient(135deg, #6366f1 0%, #2563eb 100%);
            color: #fff;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 300px;
            gap: 1.5rem;
        }

        [data-theme="dark"] .profile-sidebar {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        .profile-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-size: 2.5rem;
            color: #6366f1;
            border: 4px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        [data-theme="dark"] .profile-avatar {
            background: #18181b;
            color: #a5b4fc;
            border-color: #334155;
        }

        .profile-sidebar .profile-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            text-align: center;
        }

        .profile-sidebar .profile-role {
            font-size: 1rem;
            opacity: 0.85;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .profile-sidebar .profile-actions {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .profile-sidebar .btn {
            width: 100%;
            border-radius: 9999px;
            font-size: 1rem;
            padding: 0.75rem 0;
            font-weight: 500;
            border: none;
            transition: background 0.2s, color 0.2s;
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 1.5px solid #fff;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        [data-theme="dark"] .btn-outline {
            border-color: #e0e0e0;
            color: #e0e0e0;
        }

        [data-theme="dark"] .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            background: #4f46e5;
            color: #fff;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-password {
            background: #14b8a6;
            color: #fff;
        }

        .btn-password:hover {
            background: #0d9488;
        }

        .btn-danger {
            background: #ef4444;
            color: #fff;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .profile-content {
            flex: 1;
            padding: 2.5rem 2rem;
            background: transparent;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .profile-header {
            margin-bottom: 2rem;
            text-align: left;
        }

        .profile-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: #1e293b;
        }

        [data-theme="dark"] .profile-header h2 {
            color: #e0e0e0;
        }

        .profile-header .profile-subtitle {
            font-size: 1rem;
            color: #64748b;
            margin-top: 0.5rem;
        }

        [data-theme="dark"] .profile-header .profile-subtitle {
            color: #a1a1aa;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        [data-theme="dark"] .alert-success {
            background: #064e3b;
            color: #6ee7b7;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        [data-theme="dark"] .alert-danger {
            background: #7f1d1d;
            color: #fca5a5;
        }

        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .info-label {
            font-size: 1rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.25rem;
            display: block;
        }

        [data-theme="dark"] .info-label {
            color: #d1d5db;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1.5px solid #d1d5db;
            background: #f9fafb;
            color: #111827;
            font-size: 1rem;
            transition: border 0.2s;
        }

        [data-theme="dark"] .form-control {
            background: #23272f;
            color: #e0e0e0;
            border-color: #4b5563;
        }

        .form-control:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
        }

        .password-form {
            display: none;
            margin-top: 1.5rem;
            padding: 1.5rem 1.25rem;
            background: #f3f4f6;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        [data-theme="dark"] .password-form {
            background: #23272f;
            border-color: #4b5563;
        }

        .password-form.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="profile-main-container">
        <div class="profile-card">
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="profile-name">{{ Auth::user()->name }}</div>
                <div class="profile-role">{{ Auth::user()->getRoleNames()->first() }}</div>
                <div class="profile-actions">
                    <a href="{{ route('client.home') }}" class="btn btn-outline">Quay lại Trang Chủ</a>
                    <button type="submit" form="profile-form" class="btn btn-primary">Cập nhật thông tin</button>
                    <button type="button" class="btn btn-password" onclick="togglePasswordForm()">Đổi mật khẩu</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-danger" type="submit"
                            onclick="return confirm('Bạn có muốn đăng xuất không?')">Đăng xuất</button>
                    </form>
                </div>
            </div>
            <div class="profile-content">
                <div class="profile-header">
                    <h2>Thông tin tài khoản</h2>
                    <div class="profile-subtitle">Quản lý thông tin cá nhân hogar bạn</div>
                </div>

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

                <form method="POST" action="{{ route('profile.update') }}" class="profile-form" id="profile-form">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="info-label" for="name">Họ và tên</label>
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ old('name', Auth::user()->name) }}" required>
                    </div>

                    <div>
                        <label class="info-label" for="phone_number">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                            value="{{ old('phone_number', Auth::user()->phone_number) }}" required>
                    </div>

                    <div>
                        <label class="info-label" for="date_of_birth">Ngày sinh</label>
                        <input type="date" class="form-control" name="date_of_birth" id="date_of_birth"
                            value="{{ old('date_of_birth', Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('Y-m-d') : '') }}"
                            required>
                    </div>

                    <div>
                        <label class="info-label" for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            value="{{ old('email', Auth::user()->email) }}" readonly>
                    </div>
                </form>

                <div class="password-form" id="password-form">
                    <form method="POST" action="{{ route('profile.changePassword') }}">
                        @csrf
                        <div>
                            <label class="info-label" for="old_password">Mật khẩu cũ</label>
                            <input type="password" class="form-control" name="old_password" id="old_password" required>
                        </div>
                        <div>
                            <label class="info-label" for="new_password">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" required>
                        </div>
                        <div>
                            <label class="info-label" for="new_password_confirmation">Mật khẩu mới một lần nữa</label>
                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation
                            " required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top:1rem;"  onclick="return confirm('Bạn có chắc chắn muốn đổi mật khẩu không?')">Cập nhật mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordForm() {
            const form = document.getElementById('password-form');
            form.classList.toggle('active');
        }
    </script>
@endsection