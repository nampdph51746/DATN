@extends('layouts.client.client')
@section('title', 'Thông tin tài khoản | CineVN')

@section('content')
<style>
    .profile-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin: 100px auto 40px auto;
        max-width: 1000px;
    }

    .profile-box {
        flex: 1;
        background: white;
        border-radius: 0 12px 12px 0;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.07);
    }

    [data-theme="dark"] .profile-box {
        background-color: #1f2937;
        color: #f9fafb;
    }

    .profile-header {
        margin-bottom: 1rem;
    }

    .profile-header h2 {
        font-size: 22px;
        font-weight: bold;
        margin: 0;
    }

    .profile-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    [data-theme="dark"] .profile-subtitle {
        color: #9ca3af;
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
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
        color: #6b7280;
    }

    [data-theme="dark"] .avatar {
        background-color: #4b5563;
        color: #d1d5db;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.2rem 2rem;
    }

    .info-label {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 0.65rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
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

    .btn-primary {
        background-color: #3b82f6;
        color: #fff;
        font-weight: 600;
        padding: 0.75rem;
        border-radius: 8px;
        border: none;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        width: 100%;
        margin-top: 1.5rem;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .section-divider {
        margin: 2.5rem 0;
        border-top: 1px solid #e5e7eb;
    }
</style>

<div class="profile-container">
    @include('client.profile.menu')

    <div class="profile-box">
        <div class="profile-header">
            <h2>Đổi mật khẩu</h2>
            <p class="profile-subtitle">Cập nhật mật khẩu để bảo mật tài khoản của bạn</p>
        </div>

     {{-- Hiển thị success --}}
        @if(isset($success))
            <div class="alert alert-success">{{ $success }}</div>
        @endif

        {{-- Hiển thị lỗi --}}
        @if(isset($error))
            <div class="alert alert-danger">{{ $error }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="padding-left: 18px; margin: 0;">
                    @foreach($errors->all() as $errorItem)
                        <li>{{ $errorItem }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Form đổi mật khẩu --}}


        <form method="POST" action="{{ route('profile.change-password.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-grid">
                <div>
                    <label class="info-label" for="old_password">Mật khẩu cũ</label>
                    <input type="password" class="form-control" name="old_password" id="old_password" required>
                </div>
                <br>

                <div>
                    <label class="info-label" for="new_password">Mật khẩu mới</label>
                    <input type="password" class="form-control" name="new_password" id="new_password" required>
                </div>

                <div>
                    <label class="info-label" for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
                </div>
            </div>

            <button type="submit" class="btn-primary"
                onclick="return confirm('Bạn có chắc chắn muốn đổi mật khẩu không?')">
                🔒 Đổi mật khẩu
            </button>
        </form>
    </div>
</div>

@include('client.footer.footer')
@endsection
