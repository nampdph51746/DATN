<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Xác nhận mua vé xem phim</title>
  <style>
    body {
      font-family: 'Inter', 'Helvetica Neue', sans-serif;
      background-color: #ffffff;
      margin: 0;
      padding: 0;
      line-height: 1.5;
    }

    .email-container {
      max-width: 600px;
      margin: 40px auto;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      overflow: hidden;
      border: 1px solid #e0e0e0;
    }

    .email-header {
      background-color: #000000;
      color: #ffffff;
      padding: 30px;
      text-align: center;
    }

    .email-header h2 {
      margin: 0;
      font-size: 24px;
      font-weight: 600;
    }

    .email-body {
      padding: 40px;
      color: #1a1a1a;
    }

    .email-body p {
      margin: 0 0 16px;
      font-size: 16px;
    }

    .info {
      margin-bottom: 24px;
      line-height: 1.8;
      font-size: 15px;
    }

    .info strong {
      display: inline-block;
      width: 140px;
      color: #000000;
      font-weight: 600;
    }

    .ticket-btn {
      display: inline-block;
      margin-top: 24px;
      background-color: #000000;
      color: #ffffff;
      padding: 14px 28px;
      text-decoration: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 500;
      transition: background-color 0.3s ease;
    }

    .ticket-btn:hover {
      background-color: #1a1a1a;
    }

    .footer {
      padding: 20px;
      background-color: #fafafa;
      text-align: center;
      font-size: 13px;
      color: #4a4a4a;
      border-top: 1px solid #e0e0e0;
    }

    .footer a {
      color: #000000;
      text-decoration: none;
      font-weight: 500;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="email-header">
      <h2>Xác nhận mua vé thành công!</h2>
    </div>

    <div class="email-body">
      <p>Chào <strong>{{ $tickets->first()->booking->user->name ?? 'Khách hàng' }}</strong>,</p>
      <p>Cảm ơn bạn đã đặt vé xem phim tại <strong>CineVN</strong>.</p>

      <div class="info">
        <p><strong>Mã booking:</strong> {{ $booking->booking_code ?? '' }}</p>
        <p><strong>Tên phim:</strong> {{ $tickets->first()->showtime->movie->name ?? 'N/A' }}</p>
        <p><strong>Suất chiếu:</strong>
          {{ $tickets->first()->showtime->start_time ? \Carbon\Carbon::parse($tickets->first()->showtime->start_time)->format('d/m/Y H:i') : 'N/A' }}
        </p>
        <p><strong>Phòng chiếu:</strong> {{ $tickets->first()->showtime->room->name ?? 'N/A' }}</p>
        <p><strong>Ghế:</strong>
          {{ $tickets->map(fn($t) => $t->seat->row_char . $t->seat->seat_number)->implode(', ') }}
        </p>
        @if($booking->bookingItems && $booking->bookingItems->count())
          <p><strong>Sản phẩm đã đặt:</strong>
            @foreach($booking->bookingItems as $item)
              <br>- {{ $item->productVariant->name ?? '' }} x{{ $item->quantity }} ({{ number_format($item->price_at_purchase, 0, ',', '.') }} VND)
            @endforeach
          </p>
        @endif
        <p><strong>Tổng tiền:</strong> {{ number_format($booking->final_amount, 0, ',', '.') }} VND</p>
        <p><strong>Thời gian mua:</strong>
          {{ $tickets->first()->created_at ? $tickets->first()->created_at->format('H:i - d/m/Y') : '' }}
        </p>
      </div>

      <a href="#" class="ticket-btn">Xem vé của bạn</a>
    </div>

    <div class="footer">
      Đây là email tự động, vui lòng không trả lời. Mọi thắc mắc xin liên hệ: <a href="mailto:support@cinevn.vn">support@cinevn.vn</a> | Hotline: <a href="tel:+84247109368">+84 247 109 368</a><br>
      CineVN - Rạp chiếu phim của bạn
    </div>
  </div>
</body>
</html>