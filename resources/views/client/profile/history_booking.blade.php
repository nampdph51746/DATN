@extends('layouts.client.client')

@section('title', 'Lịch sử đặt vé | CineVN')

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

    .detail-btn {
        display: inline-block;
        padding: 10px 20px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6); /* Gradient màu tím nổi bật */
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); /* Shadow nhẹ */
        margin-top: 10px;
    }

    .detail-btn:hover {
        background: linear-gradient(90deg, #4f46e5, #7c3aed); /* Gradient đậm hơn khi hover */
        transform: translateY(-2px); /* Nâng lên khi hover */
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.4); /* Shadow đậm hơn */
    }

    [data-theme="dark"] .detail-btn {
        background: linear-gradient(90deg, #818cf8, #a78bfa);
    }

    [data-theme="dark"] .detail-btn:hover {
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
    }
</style>

<div class="history-container">
    @include('client.profile.menu')

    <div class="history-box">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:15px;">
            <i class="fas fa-history" style="color:#6366f1;"></i> Lịch sử đặt vé
        </h3>

        @forelse($bookings as $booking)
    @php
        $status = $booking->status instanceof \BackedEnum ? $booking->status->value : $booking->status;

        // Gom các vé theo showtime_id hoặc lấy vé đầu tiên để lấy info
        $groupedTickets = $booking->tickets->groupBy('showtime_id');
    @endphp

    @foreach($groupedTickets as $tickets)
        @php
            $firstTicket = $tickets->first();
            $movie = $firstTicket->showtime?->movie;
            $cinema = $firstTicket->seat?->room?->cinema?->name ?? 'Chưa xác định';
            $room = $firstTicket->seat?->room?->name ?? 'Chưa xác định';
            $showDate = $firstTicket->showtime?->show_date;
            $showTime = $firstTicket->showtime?->show_time;
            $seats = $tickets->pluck('seat.seat_number')->filter()->implode(', ');
        @endphp

        <div class="ticket-card">
            <div class="ticket-poster">
                <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('client_assets/assets/images/default-movie.jpg')) }}" alt="{{ $movie?->title ?? 'Poster' }}">
            </div>
            <div class="ticket-info">
                <div class="ticket-meta">
                    🆔 Mã đặt vé: <strong>{{ $booking->booking_code }}</strong>
                    <span class="status-badge status-{{ strtolower($status) }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
                <div class="ticket-title">{{ $movie?->name ?? 'Chưa xác định' }}</div>
                <div class="ticket-meta">
                    📅 {{ $showDate ? \Carbon\Carbon::parse($showDate)->format('d/m/Y') : '' }}
                    | ⏰ {{ $showTime ?? '' }}
                </div>
                <div class="ticket-meta">🎬 {{ $cinema }} - {{ $room }}</div>
                <div class="ticket-meta">💺 Ghế: {{ $seats ?: 'Chưa chọn' }}</div>
                <div class="ticket-price">💰 {{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</div>
                <a href="{{ route('client.booking.detail', $booking->id) }}" class="detail-btn">Xem chi tiết</a>
            </div>
        </div>
    @endforeach

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