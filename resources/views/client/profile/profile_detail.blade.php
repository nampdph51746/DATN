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
</style>

<div class="profile-container">
    @include('client.profile.menu')

    <div class="profile-box">
        <div class="profile-header">
            <h2>Cập nhật thông tin tài khoản</h2>
            <p class="profile-subtitle">Thông tin cá nhân của bạn được hiển thị bên dưới</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="padding-left: 18px; margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" id="profile-form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

        <div class="avatar-container">
                <div class="avatar">
                @if(Auth::user()->avatar_url)
                    <img src="{{ asset('storage/' . Auth::user()->avatar_url) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
                </div>
                <div>
                    <strong>{{ Auth::user()->name }}</strong>
                    <div style="font-size: 14px; color: gray;">
                        {{ Auth::user()->getRoleNames()->first() }}
                    </div>

                    
                    <input type="file" name="avatar" accept="image/*" style="margin-top:8px;">
                </div>
            </div>

            <div class="form-grid">
                <div>
                    <label class="info-label" for="name">Họ và tên</label>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ old('name', Auth::user()->name) }}" required>
                </div>

                <div>
                    <label class="info-label" for="phone_number">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone_number" id="phone_number"
                        value="{{ old('phone_number', Auth::user()->phone_number) }}">
                </div>

                <div>
                    <label class="info-label" for="date_of_birth">Ngày sinh</label>
                    <input type="date" class="form-control" name="date_of_birth" id="date_of_birth"
                        value="{{ old('date_of_birth', Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('Y-m-d') : '') }}">
                </div>

                <div>
                    <label class="info-label" for="address">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" id="address"
                        value="{{ old('address', Auth::user()->address) }}">
                </div>

                <div>
                    <label class="info-label" for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email"
                        value="{{ old('email', Auth::user()->email) }}" readonly>
                </div>
            </div>

            

            <button type="submit" class="btn-primary">💾 Cập nhật thông tin</button>
        </form>
    </div>
</div>

@include('client.footer.footer')
@endsection