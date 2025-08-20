@extends('layouts.client.client')
@section('title', 'Thông tin chung | CineVN')

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
        text-align: center;
    }

    [data-theme="dark"] .profile-box {
        background-color: #1f2937;
        color: #f9fafb;
    }

    .avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 1rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e5e7eb;
        font-size: 40px;
        font-weight: bold;
        color: #6b7280;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    [data-theme="dark"] .avatar {
        background-color: #4b5563;
        color: #d1d5db;
    }

    .welcome-text {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        text-align: left;
    }

    .card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 1.2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    [data-theme="dark"] .card {
        background: #374151;
    }

    .card h4 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-item {
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        font-size: 15px;
        gap: 0.4rem;
    }

    .info-item i {
        color: #6366f1;
        font-size: 16px;
        width: 18px;
        text-align: center;
    }

    .label {
        font-weight: 600;
        color: #4b5563;
    }

    [data-theme="dark"] .label {
        color: #d1d5db;
    }

    .truncate {
        display: inline-block;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: bottom;
    }

    .rank-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: white;
    }
    .rank-dong { background: #b87333; }
    .rank-bac { background: #a1a1a1; }
    .rank-vang { background: #ffd700; color: #000; }
    .rank-bachkim { background: #e5e4e2; color: #000; }
    .rank-kimcuong { background: #0dcaf0; }
</style>

<div class="profile-container">
    @include('client.profile.menu')

    <div class="profile-box">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:5px;">
        <i class="fas fa-info-circle" style="color:#6366f1;"></i> Thông tin chung
    </h3>
    <p style="color:#6b7280; font-size:14px; margin-bottom:20px;">
        Thông tin của bạn được hiển thị bên dưới.
    </p>
        <div class="avatar">
           @if(Auth::user()->avatar_url)
            <img src="{{ asset('storage/' . Auth::user()->avatar_url) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
            @else
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            @endif
        </div>
        <div class="welcome-text">
            Xin chào, {{ $user->name }}!
        </div>

        <div class="info-grid">
            <!-- Thông tin tài khoản -->
            <div class="card">
                <h4><i class="fas fa-user"></i> Thông tin tài khoản</h4>
                <div class="info-item" title="{{ $user->name }}">
                    <i class="fas fa-id-card"></i>
                    <span class="label">Tên:</span> 
                    <span class="truncate">{{ $user->name }}</span>
                </div>
                <div class="info-item" title="{{ $user->email }}">
                    <i class="fas fa-envelope"></i>
                    <span class="label">Email:</span> 
                    <span class="truncate">{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <span class="label">Số điện thoại:</span> 
                    {{ $user->phone_number ?? 'Chưa cập nhật' }}
                </div>
            </div>

            <!-- Thông tin thành viên -->
            <div class="card">
                <h4><i class="fas fa-star"></i> Thông tin thành viên</h4>
                <div class="info-item">
                    <i class="fas fa-medal"></i>
                    <span class="label">Hạng:</span>
                    <span class="rank-badge rank-bac">{{ $rank ?? 'Thành viên' }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-coins"></i>
                    <span class="label">Tiêu:</span> {{ number_format($totalSpent, 0, ',', '.') }} VNĐ
                </div>
                <div class="info-item">
                    <i class="fas fa-gem"></i>
                    <span class="label">Điểm:</span> {{ number_format($totalPoints, 0, ',', '.') }} điểm
                </div>
            </div>
        </div>
    </div>
</div>

@include('client.footer.footer')
@endsection