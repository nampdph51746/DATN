@extends('layouts.client.client')

@section('title', 'Thẻ thành viên | CineVN')

@section('content')
<style>
.history-container {
    display: flex;
    justify-content: center;
    margin: 60px auto;
    max-width: 1000px;
}
.history-box {
    flex: 1;
    background: white;
    border-radius: 0 12px 12px 0;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
[data-theme="dark"] .history-box {
    background-color: #1f2937;
    color: #f9fafb;
}
.progress-bar-wrapper {
    position: relative;
    height: 12px;
    background: #ddd;
    border-radius: 10px;
    margin-top: 20px;
}
.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #ffb400, #ff6600);
    border-radius: 10px;
    transition: width 0.5s ease;
}
.rank-marker {
    position: absolute;
    top: -25px;
    text-align: center;
    transform: translateX(-50%);
}
.rank-marker .dot {
    width: 10px;
    height: 10px;
    background: #000;
    border-radius: 50%;
    margin: 4px auto 0;
}
.rank-marker span {
    font-size: 11px;
    white-space: nowrap;
}
</style>

<div class="history-container">
    @include('client.profile.menu')

    <div class="history-box">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:15px;">
            <i class="fas fa-id-card" style="color:#e11d48;"></i> Thẻ Thành Viên
        </h3>

        @php
            // Lấy điểm số, tránh lỗi khi points là model
            $currentPoints = is_numeric($user->points) ? $user->points : ($user->points->points ?? 0);
            $maxPoints = max(1, $ranks->max('min_points_required'));
            $percent = min(100, ($currentPoints / $maxPoints) * 100);
        @endphp

        <p>Cấp độ thẻ: <strong>{{ $user->rank->name ?? 'Chưa có hạng' }}</strong></p>

        {{-- Thanh tiến trình --}}
        <div class="progress-bar-wrapper">
            <div class="progress-fill" style="width: {{ $percent }}%;"></div>

            @foreach($ranks as $rank)
                @php
                    $left = ($rank->min_points_required / $maxPoints) * 100;
                @endphp
                <div class="rank-marker" style="left: {{ $left }}%;">
                    <span>{{ $rank->name }}</span>
                    <div class="dot"></div>
                </div>
            @endforeach
        </div>

        <p style="margin-top: 10px;">Điểm hiện tại: <strong>{{ number_format($currentPoints) }}</strong></p>
    </div>
</div>

@include('client.footer.footer')
@endsection
