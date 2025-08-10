<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Vé đặt - {{ $booking->booking_code }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 14px; }
        .ticket { border: 1px solid #333; padding: 16px; margin-bottom: 24px; border-radius: 8px; }
        .qr { text-align: right; }
        .ticket-title { font-size: 18px; font-weight: bold; margin-bottom: 8px; }
        .info { margin-bottom: 6px; }
        .food-section { border: 1px dashed #888; padding: 12px; margin-top: 32px; border-radius: 8px; background: #f8f8f8; }
        .food-title { font-size: 16px; font-weight: bold; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Vé đặt - Mã: {{ $booking->booking_code }}</h2>
    <p>Khách hàng: {{ $booking->user->name ?? 'Người dùng #' . $booking->user_id }}</p>
    <p>Ngày đặt: {{ $booking->created_at->format('d/m/Y H:i') }}</p>


    @foreach ($tickets as $ticket)
        <div class="ticket">
            <div class="ticket-title">VÉ XEM PHIM</div>
            <div class="info"><span class="label">Mã vé:</span> {{ $ticket->ticket_code }}</div>
            <div class="info"><span class="label">Tên phim:</span> {{ $ticket->showtime->movie->name ?? '' }}</div>
            <div class="info"><span class="label">Suất chiếu:</span> {{ $ticket->showtime->start_time ? (is_object($ticket->showtime->start_time) ? $ticket->showtime->start_time->format('d/m/Y H:i') : date('d/m/Y H:i', strtotime($ticket->showtime->start_time))) : '' }}</div>
            <div class="info"><span class="label">Rạp chiếu:</span> {{ $ticket->showtime->cinema->name ?? '' }}</div>
            <div class="info"><span class="label">Phòng chiếu:</span> {{ $ticket->showtime->room->name ?? '' }}</div>
            <div class="info"><span class="label">Ghế:</span> {{ $ticket->seat->name ?? ($ticket->seat->row_char . $ticket->seat->seat_number) }}</div>
            <div class="info"><span class="label">Khách hàng:</span> {{ $booking->customer_name ?? ($booking->user->name ?? '-') }}</div>
            <div class="info"><span class="label">Số điện thoại:</span> {{ $booking->customer_phone ?? ($booking->user->phone_number ?? '-') }}</div>
            <div class="info"><span class="label">Giá vé:</span> {{ number_format($ticket->price_at_purchase, 0, ',', '.') }} VNĐ</div>
            <div class="qr">
                @if(isset($ticketQRCodes[$ticket->id]) && $ticketQRCodes[$ticket->id])
                    <img src="{{ $ticketQRCodes[$ticket->id] }}" width="100" height="100" alt="QR vé" />
                @endif
            </div>
        </div>
    @endforeach

    @if($foodDrinks && $foodDrinks->count() > 0)
        <div class="food-section">
            <div class="food-title">Đồ ăn & Đồ uống</div>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foodDrinks as $item)
                        <tr>
                            <td>{{ $item->productVariant->product->name ?? '' }}</td>
                            <td>{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="qr" style="margin-top: 10px;">
                @if(isset($foodDrinksQRCode) && $foodDrinksQRCode)
                    <img src="{{ $foodDrinksQRCode }}" width="100" height="100" alt="QR đồ ăn" />
                @endif
            </div>
        </div>
    @endif
</body>
</html>