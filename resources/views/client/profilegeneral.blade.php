@extends('layouts.client.client')
@section('title', 'Thông tin chung | CineVN')

@section('content')
<style>
    .profile-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin: 100px auto 40px auto;
        max-width: 900px;
    }

    .profile-box {
        flex: 1;
        background: white;
        border-radius: 0 12px 12px 0;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.07);
        text-align: center;
    }

    [data-theme="dark"] .profile-box {
        background-color: #1f2937;
        color: #f9fafb;
    }

    .avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 1rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e5e7eb;
        font-size: 36px;
        font-weight: bold;
        color: #6b7280;
    }

    [data-theme="dark"] .avatar {
        background-color: #4b5563;
        color: #d1d5db;
    }

    .welcome-text {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        text-align: left;
    }

    .info-section h4 {
        margin-bottom: 0.75rem;
        font-size: 16px;
        font-weight: 600;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.25rem;
    }

    .info-item {
        margin-bottom: 0.5rem;
        font-size: 14px;
    }

    .info-label {
        font-weight: 500;
        color: #6b7280;
        margin-right: 0.5rem;
    }

    [data-theme="dark"] .info-label {
        color: #9ca3af;
    }
</style>

<div class="profile-container">
    @include('client.profile.menu')

    <div class="profile-box">
        <div class="avatar">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <div class="welcome-text">
            Xin chào, {{ $user->name }}!
        </div>

        <div class="info-grid">
            <!-- Cột trái: Thông tin tài khoản -->
            <div class="info-section">
                <h4>Thông tin tài khoản</h4>
                <div class="info-item"><span class="info-label">Họ và tên:</span> {{ $user->name }}</div>
                <div class="info-item"><span class="info-label">Email:</span> {{ $user->email }}</div>
                <div class="info-item"><span class="info-label">Số điện thoại:</span> {{ $user->phone_number ?? 'Chưa cập nhật' }}</div>
            </div>

            <!-- Cột phải: Thông tin hạng -->
            <div class="info-section">
                <h4>Thông tin thành viên</h4>
                <div class="info-item"><span class="info-label">Hạng:</span> {{ $rank ?? 'Thành viên' }}</div>
                <div class="info-item"><span class="info-label">Tổng tiền đã tiêu:</span> {{ number_format(1250000, 0, ',', '.') }} VNĐ</div>
                <div class="info-item"><span class="info-label">Tổng điểm:</span> {{ number_format(250, 0, ',', '.') }} điểm</div>
            </div>
        </div>
    </div>
</div>

@include('client.footer.footer')
@endsection