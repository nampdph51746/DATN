@extends('layouts.client.client')

@section('title', 'Chi tiết đặt vé | CineVN')

@section('content')
<style>
.detail-container {
    display: flex;
    justify-content: center;
    margin: 60px auto;
    max-width: 900px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    background-color: #f9fafb;
    padding: 25px 30px;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.detail-box {
    flex: 1;
    background: white;
    border-radius: 14px;
    padding: 2rem 2.5rem;
    box-shadow: 0 10px 28px rgba(99, 102, 241, 0.18);
    transition: box-shadow 0.3s ease;
}

.detail-box:hover {
    box-shadow: 0 12px 35px rgba(99, 102, 241, 0.3);
}

.detail-header {
    display: flex;
    gap: 24px;
    margin-bottom: 30px;
    align-items: center;
}

.poster-img {
    width: 160px;
    height: 230px;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.3);
    object-fit: cover;
    flex-shrink: 0;
}

.info-group {
    flex: 1;
}

.booking-code {
    font-weight: 700;
    font-size: 20px;
    margin-bottom: 10px;
}

.status-badge {
    display: inline-block;
    padding: 6px 18px;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    letter-spacing: 1px;
    margin-left: 12px;
}

.status-pending { background: #fbbf24; box-shadow: 0 4px 12px #fbbf24aa; }
.status-confirmed { background: #10b981; box-shadow: 0 4px 12px #10b981aa; }
.status-cancelled { background: #ef4444; box-shadow: 0 4px 12px #ef4444aa; }
.status-refunded { background: #3b82f6; box-shadow: 0 4px 12px #3b82f6aa; }
.status-expired { background: #6b7280; box-shadow: 0 4px 12px #6b7280aa; }

.movie-title {
    font-size: 26px;
    font-weight: 900;
    color: #4f46e5;
    margin-bottom: 12px;
    /* Không đổ bóng */
}

.show-info {
    font-size: 16px;
    color: #4b5563;
    margin-bottom: 6px;
    font-weight: 600;
}

.seats-list,
.snacks-list {
    margin-top: 25px;
    padding-left: 20px;
    border-left: 4px solid #6366f1;
}

.seats-list strong,
.snacks-list strong {
    color: #4f46e5;
    font-size: 18px;
    margin-bottom: 14px;
    display: inline-block;
    /* Không đổ bóng */
}

.seats-list p,
.snacks-list ul {
    font-size: 16px;
    font-weight: 600;
    color: #4b5563;
    margin-top: 8px;
    margin-bottom: 0;
    /* Không đổ bóng */
}

.detail-price {
    font-weight: 900;
    color: #dc2626;
    font-size: 24px;
    margin-top: 30px;
    text-align: right;
    /* Không đổ bóng */
}

.back-btn {
    display: inline-block;
    padding: 14px 32px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6);
    color: white;
    border-radius: 30px;
    font-weight: 700;
    font-size: 16px;
    text-decoration: none;
    box-shadow: 0 7px 18px rgba(99, 102, 241, 0.5);
    transition: all 0.4s ease;
    margin-top: 40px;
    text-align: center;
    width: fit-content;
}

.back-btn:hover {
    background: linear-gradient(90deg, #4f46e5, #7c3aed);
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.7);
    transform: translateY(-4px);
}

@media (max-width: 640px) {
    .detail-container {
        margin: 20px 10px;
        padding: 15px;
        max-width: 100%;
    }
    .detail-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    .poster-img {
        width: 100%;
        height: auto;
        max-height: 320px;
    }
    .detail-price {
        text-align: left;
    }
}
</style>

<div class="detail-container">
    @include('client.profile.menu')

    <div class="detail-box">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:25px;">
            <i class="fas fa-info-circle" style="color:#6366f1;"></i> Chi tiết đặt vé
        </h3>

        @if($booking)
            @php
                $statusValue = $booking->status instanceof \BackedEnum ? $booking->status->value : $booking->status;
                $firstTicket = $booking->tickets->first();
                $movie = $firstTicket?->showtime?->movie;

                // Lấy danh sách ghế, lọc null, unique rồi sắp xếp theo hàng ghế (chữ cái) rồi số ghế
                $seats = $booking->tickets
                    ->map(fn($t) => $t->seat?->seat_number)
                    ->filter()
                    ->unique()
                    ->sort(function($a, $b) {
                        preg_match('/([A-Za-z]+)(\d+)/', $a, $matchA);
                        preg_match('/([A-Za-z]+)(\d+)/', $b, $matchB);

                        $rowA = $matchA[1] ?? '';
                        $numA = intval($matchA[2] ?? 0);
                        $rowB = $matchB[1] ?? '';
                        $numB = intval($matchB[2] ?? 0);

                        $cmpRow = strcmp($rowA, $rowB);
                        if ($cmpRow !== 0) return $cmpRow;
                        return $numA <=> $numB;
                    })
                    ->values();
            @endphp

            <div class="detail-header">
                <img src="{{ $movie?->poster_url ?? ($movie?->image_path ? asset('storage/' . $movie->image_path) : asset('images/default-poster.jpg')) }}" alt="{{ $movie?->name ?? 'Poster' }}" class="poster-img" />

                <div class="info-group">
                    <div class="booking-code">
                        🆔 Mã đặt vé: <strong>{{ $booking->booking_code }}</strong>
                        <span class="status-badge status-{{ strtolower($statusValue) }}">
                            {{ ucfirst($statusValue) }}
                        </span>
                    </div>

                    <div class="movie-title">{{ $movie?->name ?? 'Chưa xác định' }}</div>

                    <div class="show-info">
                        📅 {{ $firstTicket?->showtime?->show_date ? \Carbon\Carbon::parse($firstTicket->showtime->show_date)->format('d/m/Y') : '' }}
                        | ⏰ {{ $firstTicket?->showtime?->show_time ?? '' }}
                    </div>

                    <div class="show-info">
                        🎬 {{ $firstTicket->seat?->room?->cinema?->name ?? 'Chưa xác định' }} - {{ $firstTicket->seat?->room?->name ?? 'Chưa xác định' }}
                    </div>
                </div>
            </div>

            <div class="seats-list">
                <strong>💺 Danh sách ghế đã đặt:</strong>
                <p>{{ $seats->join(', ') }}</p>
            </div>

            @if($booking->items->count() > 0)
                <div class="snacks-list">
                    <strong>🍿 Đồ ăn kèm:</strong>
                    <ul>
                        @foreach($booking->items as $item)
                            <li>{{ $item->productVariant->product->name ?? 'Chưa xác định' }} x {{ $item->quantity ?? 1 }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="detail-price">💰 {{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</div>

            <a href="/profile/history" class="back-btn">Quay lại</a>
        @else
            <p>Không tìm thấy thông tin đặt vé.</p>
        @endif
    </div>
</div>

@include('client.footer.footer')
@endsection
