@extends('layouts.client.client')

@section('title', 'Lịch sử đặt vé | CineVN')

@section('content')
<style>
.member-card {
    max-width: 350px;
    border-radius: 12px;
    padding: 20px;
    color: white;
    background: linear-gradient(135deg, #FFD700, #FFA500);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
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
.member-card .card-footer {
    margin-top: 10px;
    font-size: 12px;
    opacity: 0.9;
}
</style>

<div class="history-container">
    @include('client.profile.menu')

    <div class="member-card">
    <div class="card-header">
        <h3 class="rank-name">{{ $rank->name }}</h3>
        <span class="discount">{{ $rank->discount_percentage }}% OFF</span>
    </div>
    <div class="card-body">
        <p><strong>Hạng ID:</strong> {{ $rank->id }}</p>
        <p><strong>Số điểm tối thiểu:</strong> {{ number_format($rank->min_points_required) }}</p>
        <p><strong>Mô tả:</strong> {{ $rank->description ?? 'Không có mô tả' }}</p>
    </div>
    <div class="card-footer">
        <small>Đạt hạng này khi tích đủ {{ number_format($rank->min_points_required) }} điểm</small>
    </div>
</div>
</div>

@include('client.footer.footer')
@endsection