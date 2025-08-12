@extends('layouts.client.client')
@section('title', 'Thông tin chung | CineVN')

@section('content')
<style>
    .profile-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin: 100px auto 40px auto;
        max-width: 800px;
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
        margin-bottom: 0.5rem;
    }

    .member-rank {
        font-size: 14px;
        color: #6b7280;
    }

    [data-theme="dark"] .member-rank {
        color: #9ca3af;
    }
</style>

<div class="profile-container">
    @include('client.profile.menu')

    <div class="profile-box">
        <div class="avatar">
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
            @else
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            @endif
        </div>
        <div class="welcome-text">
            Xin chào, {{ Auth::user()->name }}!
        </div>
        <div class="member-rank">
            Hạng thành viên: {{ Auth::user()->getRoleNames()->first() ?? 'Thành viên' }}
        </div>
    </div>
</div>

@include('client.footer.footer')
@endsection
