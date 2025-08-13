@extends('layouts.client.client')

@section('title', 'Thẻ thành viên | CineVN')

@section('content')
<style>
.history-container {
    display: flex;
    justify-content: center;
    margin: 100px auto 60px auto;
    max-width: 1000px;
}
.history-box {
    flex: 1;
    background: white;
    border-radius: 0 12px 12px 0;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
[data-theme="dark"] .history-box {
    background-color: #1f2937;
    color: #f9fafb;
}
.member-card {
    border-radius: 12px;
    padding: 20px;
    color: white;
    background: linear-gradient(135deg, #FFD700, #FFA500);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    width: 100%; /* Chiếm full box */
    animation: fadeInUp 0.5s ease-out;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.member-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.member-card .rank-name {
    font-size: 22px;
    font-weight: bold;
}
.member-card .discount {
    background: rgba(0,0,0,0.3);
    padding: 4px 10px;
    border-radius: 8px;
    font-weight: bold;
}
.member-card .card-body {
    margin-top: 15px;
    font-size: 14px;
}
.member-card .card-body p {
    margin-bottom: 6px;
}
.member-card .card-footer {
    margin-top: 10px;
    font-size: 12px;
    opacity: 0.9;
}
</style>

@php
    $rankColors = [
        'Đồng' => 'linear-gradient(135deg, #CD7F32, #A0522D)',
        'Bạc' => 'linear-gradient(135deg, #C0C0C0, #A9A9A9)',
        'Vàng' => 'linear-gradient(135deg, #FFD700, #FFA500)',
        'Bạch Kim' => 'linear-gradient(135deg, #E5E4E2, #C0C0C0)',
        'Kim Cương' => 'linear-gradient(135deg, #00BFFF, #1E90FF)',
        'Lục Bảo' => 'linear-gradient(135deg, #50C878, #228B22)',
    ];
    $bgColor = $rankColors[$currentRank->name ?? 'Đồng'] ?? 'linear-gradient(135deg, #FFD700, #FFA500)';
@endphp

<div class="history-container">
    @include('client.profile.menu')

    <div class="history-box">
        <h3 style="font-size:22px; font-weight:700; margin-bottom:20px;">
            <i class="fas fa-id-card" style="color:#e11d48;"></i> Thẻ Thành Viên
        </h3>

        <div class="member-card" style="background: {{ $bgColor }};">
            <div class="card-header">
                <h3 class="rank-name">{{ $currentRank->name ?? 'Chưa có hạng' }}</h3>
                <span class="discount">{{ $currentRank->discount_percentage ?? 0 }}% OFF</span>
            </div>
            <div class="card-body">
                <p><strong>Điểm hiện tại:</strong> {{ number_format($currentPoints) }}</p>
                @php
                    $nextRank = $ranks->firstWhere('min_points_required', '>', $currentPoints);
                @endphp
                @if($nextRank)
                    <p><strong>Điểm cần để lên hạng {{ $nextRank->name }}:</strong> {{ number_format($nextRank->min_points_required - $currentPoints) }}</p>
                @else
                    <p><strong>Bạn đã đạt hạng cao nhất!</strong></p>
                @endif
                <p><strong>Mô tả:</strong> {{ $currentRank->description ?? 'Không có mô tả' }}</p>
            </div>
            <div class="card-footer">
                <small>Hạng này yêu cầu tối thiểu {{ number_format($currentRank->min_points_required ?? 0) }} điểm</small>
            </div>
        </div>
    </div>
</div>

@include('client.footer.footer')
@endsection