<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đồ ăn & Đồ uống - Đơn {{ $booking->booking_code }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; }
        .box { border: 2px solid #333; padding: 24px; width: 600px; margin: 0 auto; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #888; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="box">
        <h2>ĐỒ ĂN & ĐỒ UỐNG</h2>
        <div><b>Mã đơn:</b> {{ $booking->booking_code }}</div>
        <div><b>Khách hàng:</b> {{ $booking->customer_name ?? '' }}</div>
        <div><b>Số điện thoại:</b> {{ $booking->customer_phone ?? '' }}</div>
        <div><b>Ngày đặt:</b> {{ $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : '' }}</div>

        @if(!empty($qrCodeBase64))
            <div style="text-align:center; margin-bottom:16px;">
                <img src="{{ $qrCodeBase64 }}" alt="QR Code" width="120" height="120">
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($bookingItems as $i => $item)
                    @php
                        $subtotal = $item->quantity * $item->price_at_purchase;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $item->productVariant->name ?? '' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price_at_purchase, 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="total">Tổng cộng</td>
                    <td class="total">{{ number_format($total, 0, ',', '.') }} VNĐ</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
