<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt vé</title>
    <style>
        body {
            font-family: 'Arial', 'Helvetica Neue', sans-serif;
            background: #f5f5f5;
            color: #333;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        h2, h3, h4 {
            margin: 10px 0;
            color: #d62828; /* red highlight */
            font-weight: bold;
        }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .ticket-card {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 12px;
            margin-top: 10px;
            background: #fafafa;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #222;
        }
        .qr {
            margin: 15px 0;
            text-align: center;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }
        .qr img {
            max-width: 160px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #d62828;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #a71d1d;
        }
        p.footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🎬 Xác nhận đặt vé thành công</h2>

        <p class="mb-4">
            Chào {{ $booking->user->name }}, cảm ơn bạn đã đặt vé tại 
            <strong>{{ config('app.name', 'Cinema Booking System') }}</strong>! 
            Đơn hàng của bạn đã được xác nhận và thanh toán thành công.
        </p>

        <!-- Thông tin vé -->
        <div class="ticket-info">
            <h3 class="mb-3">📋 Thông tin đặt vé</h3>

            <div class="ticket-card">
                <h4 class="mb-3">🎟️ Thông tin đơn hàng</h4>

                <div class="info-row">
                    <span class="label">🎬 Phim:</span>
                    <span class="value">{{ $booking->tickets->first()->showtime->movie->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">📅 Ngày chiếu:</span>
                    <span class="value">{{ $booking->tickets->first()->showtime->start_time->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">⏰ Giờ chiếu:</span>
                    <span class="value">
                        {{ $booking->tickets->first()->showtime->start_time->format('H:i') }}
                        - {{ $booking->tickets->first()->showtime->end_time->format('H:i') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">🏢 Rạp:</span>
                    <span class="value">{{ $booking->tickets->first()->showtime->room->cinema->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">🪑 Phòng:</span>
                    <span class="value">{{ $booking->tickets->first()->showtime->room->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">💺 Số ghế:</span>
                    <span class="value">{{ $booking->tickets->count() }} ghế</span>
                </div>
                @if($booking->bookingItems->count() > 0)
                <div class="info-row">
                    <span class="label">🍿 Combo:</span>
                    <span class="value">{{ $booking->bookingItems->count() }} món</span>
                </div>
                @endif
            </div>
            <!-- Tổng tiền -->
            <div class="info-row" style="margin-top:12px; border-top:1px solid #ddd; padding-top:8px;">
                <span class="label">💳 Tổng thanh toán:</span>
                <span class="value"><strong>{{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</strong></span>
            </div>
        </div>

        <!-- QR cho booking -->
        <div class="qr">
            <p>📌 Mã đặt vé: <strong>{{ $booking->booking_code }}</strong></p>
            <img src="{{ $message->embed($qrPaths['booking']) }}" alt="Booking QR">
        </div>
        <a href="#" class="btn">📄 Xem chi tiết</a>

        <p class="footer">
            👉 Vui lòng đưa mã QR này tại quầy để nhận vé và combo của bạn.  
            © {{ date('Y') }} {{ config('app.name', 'Cinema Booking System') }}. All rights reserved.
        </p>
    </div>
</body>
</html>