<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt vé</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .header .booking-code {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            display: inline-block;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }
        .ticket-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            color: #667eea;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
        }
        .barcode-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .barcode-title {
            font-size: 16px;
            margin-bottom: 15px;
            color: #666;
        }
        .barcode-image {
            max-width: 300px;
            height: auto;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        .instructions {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .instructions h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer strong {
            color: #333;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 0;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
                gap: 5px;
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
            
            <p>Cảm ơn bạn đã đặt vé tại <strong>{{ config('app.name', 'Cinema Booking System') }}</strong>! 
            Đơn hàng của bạn đã được xác nhận và thanh toán thành công.</p>

            <!-- Thông tin vé -->
            <div class="ticket-info">
                <h3 style="margin-top: 0; color: #667eea;">📋 Thông tin đặt vé</h3>
                
                @foreach($booking->tickets as $ticket)
                <div style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border: 1px solid #e0e0e0;">
                    <h4 style="margin: 0 0 10px 0; color: #333;">🎭 {{ $ticket->showtime->movie->name }}</h4>
                    
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
                </div>
                @endforeach

                <!-- Thông tin combo (nếu có) -->
                @if($booking->bookingItems->count() > 0)
                <div style="margin-top: 20px;">
                    <h4 style="color: #667eea;">🍿 Combo đã chọn:</h4>
                    @foreach($booking->bookingItems as $item)
                    <div class="info-row">
                        <span class="label">{{ $item->productVariant->product->name ?? 'Sản phẩm' }} (x{{ $item->quantity }})</span>
                        <span class="value">{{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }} VNĐ</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Tổng tiền -->
                <div class="info-row" style="margin-top: 20px; padding-top: 15px; border-top: 2px solid #667eea;">
                    <span class="label">💳 Tổng thanh toán:</span>
                    <span class="value">{{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>

            <!-- Barcode -->
            @if($barcode)
            <div class="barcode-section">
                <div class="barcode-title">
                    <strong>📱 Mã vé điện tử</strong><br>
                    <small>Vui lòng xuất trình mã này tại quầy để nhận vé</small>
                </div>
                <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" class="barcode-image">
                <div style="margin-top: 10px; font-family: monospace; font-size: 14px; color: #666;">
                    {{ $booking->booking_code }}
                </div>
            </div>
            @endif

            <!-- Hướng dẫn -->
            <div class="instructions">
                <h3>📝 Hướng dẫn sử dụng vé</h3>
                <ul>
                    <li>Vui lòng có mặt tại rạp <strong>15 phút trước</strong> giờ chiếu</li>
                    <li>Xuất trình email này hoặc mã barcode tại quầy để nhận vé giấy</li>
                    <li>Mang theo giấy tờ tùy thân để đối chiếu</li>
                    <li>Vé đã mua không thể hoàn trả hoặc đổi lịch chiếu</li>
                    <li>Liên hệ hotline nếu cần hỗ trợ: <strong>1900-xxxx</strong></li>
                </ul>
            </div>

            <p style="color: #666; margin-top: 30px;">
                Chúc bạn có những phút giây giải trí thú vị! 🎉
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>{{ config('app.name', 'Cinema Booking System') }}</strong><br>
            Email này được gửi tự động, vui lòng không trả lời.<br>
            © {{ date('Y') }} All rights reserved.
        </div>
    </div>
</body>
</html>
