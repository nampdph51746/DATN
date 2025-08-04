<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt vé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 680px;
            margin: 20px auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #5a67d8 0%, #9f7aea 100%);
            color: white;
            padding: 2.5rem;
            text-align: center;
        }
        .header h1 {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
        }
        .booking-code {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: inline-block;
            transition: transform 0.2s ease;
        }
        .booking-code:hover {
            transform: scale(1.05);
        }
        .content {
            padding: 2.5rem;
            background: white;
        }
        .greeting {
            font-size: 1.25rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 1.5rem;
        }
        .ticket-info {
            background: #f9fafb;
            border-radius: 8px;
            padding: 1.5rem;
            border-left: 5px solid #5a67d8;
            margin: 1.5rem 0;
        }
        .ticket-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #edf2f7;
        }
        .info-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 1.125rem;
            color: #5a67d8;
        }
        .label {
            font-weight: 600;
            color: #718096;
        }
        .value {
            color: #2d3748;
        }
        .barcode-section {
            text-align: center;
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 8px;
        }
        .barcode-title {
            font-size: 1rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 1rem;
        }
        .barcode-image {
            max-width: 300px;
            height: auto;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem;
            background: white;
        }
        .instructions {
            background: #ebf8ff;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 5px solid #3182ce;
        }
        .instructions h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #2b6cb0;
            margin-top: 0;
        }
        .instructions ul {
            padding-left: 1.5rem;
            color: #4a5568;
        }
        .footer {
            background: #f9fafb;
            padding: 1.5rem;
            text-align: center;
            color: #718096;
            font-size: 0.875rem;
            border-top: 1px solid #edf2f7;
        }
        .footer strong {
            color: #2d3748;
        }
        @media (max-width: 576px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .header, .content, .footer {
                padding: 1.5rem;
            }
            .header h1 {
                font-size: 1.5rem;
            }
            .booking-code {
                font-size: 1.25rem;
            }
            .info-row {
                flex-direction: column;
                gap: 0.5rem;
            }
            .barcode-image {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎬 Xác nhận đặt vé thành công</h1>
            <div class="booking-code">{{ $booking->booking_code }}</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Xin chào <strong>{{ $booking->user->name }}</strong>,
            </div>
            
            <p class="mb-4">Cảm ơn bạn đã đặt vé tại <strong>{{ config('app.name', 'Cinema Booking System') }}</strong>! 
            Đơn hàng của bạn đã được xác nhận và thanh toán thành công.</p>

            <!-- Thông tin vé -->
            <div class="ticket-info">
                <h3 class="mb-3 text-primary">📋 Thông tin đặt vé</h3>
                

                @foreach($booking->tickets as $ticket)
                <div class="ticket-card" style="position:relative;">
                    <h4 class="mb-3 text-dark">{{ $ticket->showtime->movie->name }}</h4>
                    <div class="info-row">
                        <span class="label">📅 Ngày chiếu:</span>
                        <span class="value">{{ $ticket->showtime->start_time->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">⏰ Giờ chiếu:</span>
                        <span class="value">{{ $ticket->showtime->start_time->format('H:i') }} - {{ $ticket->showtime->end_time->format('H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">🏢 Rạp:</span>
                        <span class="value">{{ $ticket->showtime->room->cinema->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">🪑 Phòng:</span>
                        <span class="value">{{ $ticket->showtime->room->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">💺 Ghế:</span>
                        <span class="value">{{ $ticket->seat->row_char }}{{ $ticket->seat->seat_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">💰 Giá vé:</span>
                        <span class="value">{{ number_format($ticket->price_at_purchase, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div style="position:absolute; bottom:12px; right:12px; text-align:right;">
                        @if(!empty($ticketQrs[$ticket->id]))
                        <img src="data:image/png;base64,{{ $ticketQrs[$ticket->id] }}" alt="QR Vé" style="width:80px; height:80px; border:1px solid #e2e8f0; border-radius:6px; background:#fff;">
                        <div style="font-size:10px; color:#888;">Vé #{{ $ticket->id }}</div>
                        @endif
                    </div>
                </div>
                @endforeach

                <!-- Thông tin combo (nếu có) -->

                @if($booking->bookingItems->count() > 0)
                <div class="mt-4" style="position:relative;">
                    <h4 class="text-primary mb-3">🍿 Combo đã chọn:</h4>
                    @foreach($booking->bookingItems as $item)
                    <div class="info-row">
                        <span class="label">{{ $item->productVariant->product->name ?? 'Sản phẩm' }} (x{{ $item->quantity }})</span>
                        <span class="value">{{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }} VNĐ</span>
                    </div>
                    @endforeach
                    <div style="position:absolute; bottom:12px; right:12px; text-align:right;">
                        @if(!empty($foodQr))
                        <img src="data:image/png;base64,{{ $foodQr }}" alt="QR Combo" style="width:80px; height:80px; border:1px solid #e2e8f0; border-radius:6px; background:#fff;">
                        <div style="font-size:10px; color:#888;">Combo</div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tổng tiền -->
                <div class="info-row mt-4 pt-3 border-top border-primary">
                    <span class="label">💳 Tổng thanh toán:</span>
                    <span class="value">{{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>

            <!-- QR Code -->
            @if($qrcode)
            <div class="barcode-section">
                <div class="barcode-title">
                    <strong>📱 Mã QR vé điện tử</strong><br>
                    <small>Vui lòng xuất trình mã QR này tại quầy để nhận vé</small>
                </div>
                <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" class="barcode-image">
                <div class="mt-2 font-monospace text-secondary">{{ $booking->booking_code }}</div>
            </div>
            @endif

            <!-- Hướng dẫn -->
            <div class="instructions">
                <h3>📝 Hướng dẫn sử dụng vé</h3>
                <ul class="mb-0">
                    <li>Vui lòng có mặt tại rạp <strong>15 phút trước</strong> giờ chiếu</li>
                    <li>Xuất trình email này hoặc quét mã QR tại quầy để nhận vé giấy</li>
                    <li>Mang theo giấy tờ tùy thân để đối chiếu</li>
                    <li>Vé đã mua không thể hoàn trả hoặc đổi lịch chiếu</li>
                    <li>Liên hệ hotline nếu cần hỗ trợ: <strong>1900-xxxx</strong></li>
                </ul>
            </div>

            <p class="text-secondary mt-4">
                Chúc bạn có những phút giây giải trí thú vị! 🎉
            </p>
            <a href="https://x.ai" class="btn btn-primary mt-3">Xem thêm về chúng tôi</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>{{ config('app.name', 'Cinema Booking System') }}</strong><br>
            Email này được gửi tự động, vui lòng không trả lời.<br>
            © {{ date('Y') }} All rights reserved.
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>