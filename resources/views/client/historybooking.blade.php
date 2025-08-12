@extends('layouts.client.client')
@section('title', 'Lịch sử giao dịch | CineVN')

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
    .ticket-card {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 1.2rem;
        background: #fafafa;
    }
    [data-theme="dark"] .ticket-card {
        background: #374151;
        border-color: #4b5563;
    }
    .ticket-poster {
        width: 100px;
        height: 140px;
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
    }
    .ticket-poster img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .ticket-info {
        flex: 1;
    }
    .ticket-title {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 6px;
    }
    .ticket-meta {
        font-size: 14px;
        color: #555;
        margin-bottom: 4px;
    }
    [data-theme="dark"] .ticket-meta {
        color: #ccc;
    }
    .ticket-price {
        font-weight: 700;
        color: #e11d48;
        margin-top: 8px;
    }
    .view-btn {
        display: inline-block;
        background: #6366f1;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        margin-top: 8px;
    }
    .view-btn:hover {
        background: #4f46e5;
    }
</style>

<div class="history-container">
    @include('client.profile.menu')

    <div class="history-box">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:15px;">
            <i class="fas fa-history" style="color:#6366f1;"></i> Lịch sử giao dịch
        </h3>

        @forelse($orders as $order)
            <div class="ticket-card">
                <div class="ticket-poster">
                    <img src="{{ $order->movie->poster_url }}" alt="{{ $order->movie->title }}">
                </div>
                <div class="ticket-info">
                    <div class="ticket-meta">Mã đặt vé: <strong>{{ $order->booking_code }}</strong> 
                        <span style="color:green;">({{ $order->status_text }})</span>
                    </div>
                    <div class="ticket-title">{{ $order->movie->title }}</div>
                    <div class="ticket-meta">{{ \Carbon\Carbon::parse($order->show_date)->format('d/m/Y') }} 
                        | {{ $order->show_time }}</div>
                    <div class="ticket-meta">{{ $order->cinema_name }} - {{ $order->room_name }}</div>
                    <div class="ticket-meta">Ghế: {{ implode(', ', $order->seats) }}</div>
                    <div class="ticket-price">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</div>
                    <a href="{{ route('orders.show', $order->id) }}" class="view-btn">Xem</a>
                </div>
            </div>
        @empty
            <p>Chưa có giao dịch nào.</p>
        @endforelse
    </div>
</div>

@include('client.footer.footer')
@endsection