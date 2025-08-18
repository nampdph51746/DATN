
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Vé xem phim - {{ $ticket->ticket_code }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; }
        .ticket-box { border: 2px solid #333; padding: 24px; width: 500px; margin: 0 auto; }
        h2 { text-align: center; }
        .info { margin-bottom: 10px; }
        .label { font-weight: bold; }
        .qr { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <h2>VÉ XEM PHIM</h2>
        <div class="info"><span class="label">Mã vé:</span> {{ $ticket->ticket_code }}</div>
        <div class="info"><span class="label">Tên phim:</span> {{ $ticket->showtime->movie->name ?? '' }}</div>
        <div class="info"><span class="label">Suất chiếu:</span> {{ $ticket->showtime->start_time ? $ticket->showtime->start_time->format('d/m/Y H:i') : '' }}</div>
        <div class="info"><span class="label">Rạp chiếu:</span> {{ $ticket->showtime->cinema->name ?? '' }}</div>
        <div class="info"><span class="label">Phòng chiếu:</span> {{ $ticket->showtime->room->name ?? '' }}</div>

        <div class="info"><span class="label">Ghế:</span> {{ $ticket->seat->name ?? ($ticket->seat->row_char . $ticket->seat->seat_number) }}</div>
        <div class="info"><span class="label">Khách hàng:</span> {{ $booking->customer_name ?? ($booking->user->name ?? '-') }}</div>
        <div class="info"><span class="label">Số điện thoại:</span> {{ $booking->customer_phone ?? ($booking->user->phone_number ?? '-') }}</div>
        <div class="info"><span class="label">Giá vé:</span> {{ number_format($ticket->price_at_purchase, 0, ',', '.') }} VNĐ</div>
        <div class="qr">
            @if(!empty($qrCodeBase64))
                <img src="{{ $qrCodeBase64 }}" alt="QR Code" width="120" height="120">
            @else
                <div>[QR CODE]</div>
            @endif
        </div>
        <div style="text-align:center; margin-top:20px; font-size:12px; color:#888;">Vui lòng xuất trình vé này khi vào rạp</div>
    </div>
</body>
</html>
