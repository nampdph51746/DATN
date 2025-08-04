@extends('layouts.client.client')

@section('content')
<style>
    body {
        background: linear-gradient(to bottom, #ffffff, #ffffff);
        font-family: 'Arial', sans-serif;
        color: #000000;
    }
    .main-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 20px;
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .banner-container {
        flex: 1;
        min-width: 300px;
        max-width: 500px;
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
    }

    .movie-poster {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .banner-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        padding: 20px;
    }

    .movie-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #ffd700;
        text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }

    .receipt-container {
        flex: 1;
        min-width: 300px;
        max-width: 400px;
        height: auto;
        max-height: 800px;
        overflow: hidden;
        border: 2px solid #e00e62;
        border-radius: 8px;
        background: linear-gradient(to bottom, #2a2a2a, #333333);
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
    }

    .receipt-container::before, .receipt-container::after {
        content: '';
        position: absolute;
        top: 0;
        width: 20px;
        height: 100%;
        background: repeating-radial-gradient(
            circle at 10px,
            transparent 0,
            transparent 10px,
            #2a2a2a 10px,
            #2a2a2a 20px
        );
    }

    .receipt-container::before {
        left: -10px;
    }

    .receipt-container::after {
        right: -10px;
    }

    .receipt-header {
        border-bottom: 2px dashed #e00e62;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }

    .receipt-section {
        border-bottom: 1px dashed #666666;
        padding-bottom: 8px;
        margin-bottom: 8px;
    }

    .ticket-item, .food-item {
        background-color: #3a3a3a;
        border: 1px solid #555555;
        border-radius: 4px;
        padding: 8px;
        margin-bottom: 8px;
        color: #ffffff;
    }

    .total-section {
        border-top: 2px dashed #e00e62;
        padding-top: 8px;
    }

    .footer-note {
        border-top: 1px dashed #666666;
        padding-top: 8px;
    }

    .back-link {
        color: #e00e62;
        font-size: 0.875rem;
        font-weight: medium;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: #70032f;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-confirmed {
        background: #28a745;
        color: #ffffff;
    }

    .status-pending {
        background: #ffc107;
        color: #1a1a1a;
    }

    .status-cancelled {
        background: #dc3545;
        color: #ffffff;
    }
</style>

<div class="main-container mx-auto px-4 py-4">
    <!-- Back Button -->
    <a href="{{ route('client.bookings.index') }}" class="back-link inline-block mb-3">
        ← Quay lại danh sách đặt vé
    </a>

    <div class="flex w-full gap-6">
        <!-- Movie Banner -->
        <div class="banner-container">
            <img src="{{ $booking->tickets->first()?->showtime?->movie?->poster_url ?? 'https://via.placeholder.com/500x750?text=Movie+Poster' }}"
                 alt="{{ $booking->tickets->first()?->showtime?->movie?->name ?? 'Movie Poster' }}"
                 class="movie-poster">
            <div class="banner-overlay">
                <h2 class="movie-title">{{ $booking->tickets->first()?->showtime?->movie?->name ?? 'Unknown Movie' }}</h2>
            </div>
        </div>

        <!-- Receipt Container -->
        <div class="receipt-container p-4 font-sans">
            <!-- Receipt Header -->
            <div class="receipt-header text-center">
                <h1 class="text-xl font-bold text-white">🎟 Biên Lai Đặt Vé</h1>
                <p class="text-xs text-gray-400">Cinema VN - Trải nghiệm điện ảnh đỉnh cao</p>
            </div>

            <!-- Booking Information -->
            <div class="receipt-section">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-semibold text-gray-300">📌 Mã Đơn: <span class="font-normal">{{ $booking->booking_code }}</span></p>
                        <p class="text-xs text-gray-400">📅 Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="status-badge @if($booking->status->value === 'confirmed') status-confirmed
                        @elseif($booking->status->value === 'pending') status-pending
                        @else status-cancelled @endif">
                        {{ ucfirst($booking->status->value) }}
                    </span>
                </div>
            </div>

            <!-- Ticket Information -->
            <div class="receipt-section">
                <h2 class="text-sm font-semibold text-gray-300 mb-2">🎬 Thông Tin Vé</h2>
               @foreach ($booking->tickets as $ticket)
                <div class="ticket-item">
                    <p class="text-xs text-gray-300"><span class="font-medium">Phim:</span> {{ $ticket->showtime?->movie?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-300"><span class="font-medium">Thời gian:</span> {{ optional($ticket->showtime)->start_time ? \Carbon\Carbon::parse($ticket->showtime->start_time)->format('d/m/Y H:i') : 'N/A' }}</p>
                    <p class="text-xs text-gray-300"><span class="font-medium">Phòng:</span> {{ $ticket->showtime?->room?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-300"><span class="font-medium">Ghế:</span> {{ $ticket->seat?->name ?? 'N/A' }} ({{ $ticket->seat?->seatType?->name ?? 'N/A' }})</p>
                    <p class="text-xs text-gray-300"><span class="font-medium">Giá:</span> {{ number_format($ticket->price_at_purchase, 0, ',', '.') }} VNĐ</p>

                </div>
            @endforeach
            </div>

            <!-- Food Items -->
            @if($booking->bookingItems->isNotEmpty())
                <div class="receipt-section">
                    <h2 class="text-sm font-semibold text-gray-300 mb-2">🍿 Đồ Ăn Đã Mua</h2>
                    @foreach ($booking->bookingItems as $item)
                        @php
                            $product = $item->productVariant?->product;
                        @endphp
                        <div class="food-item">
                            <p class="text-xs text-gray-300"><span class="font-medium">Tên:</span> {{ $product?->name ?? 'Không rõ' }}</p>
                            <p class="text-xs text-gray-300"><span class="font-medium">Số lượng:</span> {{ $item->quantity }}</p>
                            <p class="text-xs text-gray-300"><span class="font-medium">Giá:</span> {{ number_format($item->price_at_purchase ?? 0, 0, ',', '.') }} VNĐ</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Discount and Total -->
            <div class="total-section">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs font-semibold text-gray-300">Mã giảm giá:</p>
                    <p class="text-xs text-gray-300">{{ number_format($booking->discount_amount, 0, ',', '.') }} VNĐ</p>
                </div>
                <div class="flex justify-between items-center">
                    <h2 class="text-sm font-semibold text-gray-300">💰 Tổng Thanh Toán</h2>
                    <p class="text-sm font-bold text-white">{{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</p>
                </div>
            </div>
            <!-- Footer Note -->
            <div class="footer-note text-center mt-4">
                <p class="text-xs text-gray-400">Vui lòng giữ biên lai này để kiểm tra khi vào rạp.</p>
                <p class="text-xs text-gray-400">Hotline: 1900 1234 | Website: www.cinema.vn</p>
            </div>
        </div>
    </div>
</div>
@endsection