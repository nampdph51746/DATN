@extends('layouts.client.client')
@section('title', 'Lịch sử đặt vé | CineVN')

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
        min-width: 0;
    }
    .ticket-title {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ticket-meta {
        font-size: 14px;
        color: #555;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    [data-theme="dark"] .ticket-meta {
        color: #ccc;
    }
    .ticket-price {
        font-weight: 700;
        color: #e11d48;
        margin-top: 8px;
    }
    .status-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
        color: white;
        margin-left: 6px;
    }
    .status-pending { background: #f59e0b; }
    .status-confirmed { background: #10b981; }
    .status-cancelled { background: #ef4444; }
    .status-refunded { background: #3b82f6; }
    .status-expired { background: #6b7280; }
</style>

<div class="history-container">
    @include('client.profile.menu')

    <div class="history-box">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:15px;">
            <i class="fas fa-history" style="color:#6366f1;"></i> Lịch sử đặt vé
        </h3>

        @forelse($bookings as $booking)
            @php
                $firstTicket = $booking->tickets->first();
                $movie = $firstTicket?->showtime?->movie;
                $seats = $booking->tickets->pluck('seat.seat_number')->filter()->implode(', ');
                $cinema = $firstTicket?->seat?->room?->cinema?->name ?? 'Chưa xác định';
                $room = $firstTicket?->seat?->room?->name ?? 'Chưa xác định';
                $showDate = $firstTicket?->showtime?->show_date;
                $showTime = $firstTicket?->showtime?->show_time;
                $status = $booking->status instanceof \BackedEnum ? $booking->status->value : $booking->status;
            @endphp

            <div class="ticket-card">
                <div class="ticket-poster">
                    <img src="{{ $movie?->poster_url ?? asset('images/default-poster.jpg') }}" alt="{{ $movie?->title ?? 'Poster' }}">
                </div>
                <div class="ticket-info">
                    <div class="ticket-meta">
                        🆔 Mã đặt vé: <strong>{{ $booking->booking_code }}</strong>
                        <span class="status-badge status-{{ strtolower($status) }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    <div class="ticket-title">{{ $movie?->title ?? 'Chưa xác định' }}</div>
                    <div class="ticket-meta">
                        📅 {{ $showDate ? \Carbon\Carbon::parse($showDate)->format('d/m/Y') : '' }}
                        | ⏰ {{ $showTime ?? '' }}
                    </div>
                    <div class="ticket-meta">🎬 {{ $cinema }} - {{ $room }}</div>
                    <div class="ticket-meta">💺 Ghế: {{ $seats ?: 'Chưa chọn' }}</div>
                    <div class="ticket-price">💰 {{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</div>
                </div>
            </div>
        @empty
            <p>Chưa có giao dịch nào.</p>
        @endforelse

        <div style="margin-top:20px;">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

@include('client.footer.footer')
@endsection