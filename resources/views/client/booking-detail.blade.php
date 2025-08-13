@extends('layouts.client.client')

@section('title', 'Chi tiết đặt vé | CineVN')

@section('content')
<style>
    .detail-container {
        display: flex;
        justify-content: center;
        margin: 60px auto;
        max-width: 1000px;
    }

    .detail-box {
        flex: 1;
        background: white;
        border-radius: 0 12px 12px 0;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    [data-theme="dark"] .detail-box {
        background-color: #1f2937;
        color: #f9fafb;
    }

    .detail-card {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 1.2rem;
        background: #fafafa;
    }

    [data-theme="dark"] .detail-card {
        background: #374151;
        border-color: #4b5563;
    }

    .detail-item {
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
    }

    [data-theme="dark"] .detail-item {
        color: #ccc;
    }

    .detail-title {
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 12px;
    }

    .detail-price {
        font-weight: 700;
        color: #e11d48;
        margin-top: 12px;
    }

    .back-btn {
        display: inline-block;
        padding: 8px 16px;
        background-color: #6366f1;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .back-btn:hover {
        background-color: #4f46e5;
    }
</style>

<div class="detail-container">
    @include('client.profile.menu')

    <div class="detail-box">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:15px;">
            <i class="fas fa-info-circle" style="color:#6366f1;"></i> Chi tiết đặt vé
        </h3>

        @if($booking)
            <div class="detail-card">
                <div class="detail-item">
                    🆔 Mã đặt vé: <strong>{{ $booking->booking_code }}</strong>
                    <span class="status-badge status-{{ strtolower($booking->status) }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                @foreach($booking->tickets as $ticket)
                    @php
                        $movie = $ticket->showtime?->movie;
                        $cinema = $ticket->seat?->room?->cinema?->name ?? 'Chưa xác định';
                        $room = $ticket->seat?->room?->name ?? 'Chưa xác định';
                        $showDate = $ticket->showtime?->show_date;
                        $showTime = $ticket->showtime?->show_time;
                        $seats = $ticket->seat?->seat_number ?? 'Chưa chọn';
                    @endphp
                    <div class="detail-title">{{ $movie?->name ?? 'Chưa xác định' }}</div>
                    <div class="detail-item">
                        📅 {{ $showDate ? \Carbon\Carbon::parse($showDate)->format('d/m/Y') : '' }}
                        | ⏰ {{ $showTime ?? '' }}
                    </div>
                    <div class="detail-item">🎬 {{ $cinema }} - {{ $room }}</div>
                    <div class="detail-item">💺 Ghế: {{ $seats }}</div>
                @endforeach
                <div class="detail-price">💰 {{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</div>
            </div>
            <a href="{{ route('client.history') }}" class="back-btn">Quay lại</a>
        @else
            <p>Không tìm thấy thông tin đặt vé.</p>
        @endif
    </div>
</div>

@include('client.footer.footer')
@endsection