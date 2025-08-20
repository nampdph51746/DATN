<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Vé - {{ $booking->booking_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            body { margin: 0; }
            .ticket { margin: 0; box-shadow: none !important; }
        }
        
        .ticket {
            width: 350px;
            margin: 20px auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            position: relative;
        }
        
        .ticket::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
        }
        
        .ticket::after {
            content: '';
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
        }
        
        .ticket-header {
            background: rgba(255,255,255,0.15);
            padding: 15px;
            text-align: center;
            border-bottom: 2px dashed rgba(255,255,255,0.3);
        }
        
        .ticket-body {
            padding: 20px;
            background: white;
            color: #333;
        }
        
        .food-voucher {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .qr-code {
            text-align: center;
            margin: 15px 0;
        }
        
        .qr-code canvas {
            border: 3px solid #f0f0f0;
            border-radius: 10px;
        }
        
        .ticket-info {
            font-size: 14px;
            line-height: 1.6;
        }
        
        .ticket-title {
            color: white;
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        
        .cinema-info {
            color: rgba(255,255,255,0.9);
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-4 no-print">
        <div class="text-center mb-4">
            <h2>In Vé - {{ $booking->booking_code }}</h2>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> In Tất Cả
            </button>
            <button onclick="window.close()" class="btn btn-secondary ms-2">
                <i class="bi bi-x-lg"></i> Đóng
            </button>
        </div>
    </div>

    <!-- Movie Tickets -->
    @foreach($tickets as $index => $ticket)
    <div class="ticket {{ $index > 0 ? 'page-break' : '' }}">
        <div class="ticket-header">
            <h3 class="ticket-title">🎬 VÉ XEM PHIM</h3>
            <div class="cinema-info">
                {{ $booking->showtime->cinema->name ?? 'N/A' }}
            </div>
        </div>
        
        <div class="ticket-body">
            <div class="text-center mb-3">
                <h5 class="fw-bold text-primary">{{ $booking->showtime->movie->name ?? 'N/A' }}</h5>
                <small class="text-muted">{{ $booking->booking_code }}</small>
            </div>
            
            <div class="row g-2 ticket-info">
                <div class="col-6">
                    <strong>Ghế:</strong><br>
                    <span class="badge bg-primary fs-6">
                        {{ $ticket->seat ? $ticket->seat->row_char . $ticket->seat->seat_number : 'N/A' }}
                    </span>
                </div>
                <div class="col-6">
                    <strong>Loại ghế:</strong><br>
                    {{ $ticket->getSeatTypeAttribute() ?? 'Thường' }}
                </div>
                <div class="col-6">
                    <strong>Ngày chiếu:</strong><br>
                    {{ $booking->showtime ? \Carbon\Carbon::parse($booking->showtime->start_date)->format('d/m/Y') : 'N/A' }}
                </div>
                <div class="col-6">
                    <strong>Giờ chiếu:</strong><br>
                    {{ $booking->showtime->start_time ?? 'N/A' }}
                </div>
                <div class="col-12">
                    <strong>Khách hàng:</strong><br>
                    {{ $booking->customer_name ?? 'N/A' }} - {{ $booking->customer_phone ?? 'N/A' }}
                </div>
            </div>
            
            <div class="qr-code">
                <div id="qr-ticket-{{ $ticket->id }}"></div>
                <small class="text-muted d-block mt-2">Mã vé: {{ $ticket->id }}</small>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Food Vouchers -->
    @foreach($bookingItems as $index => $item)
    <div class="ticket food-voucher page-break">
        <div class="ticket-header">
            <h3 class="ticket-title">🍿 VOUCHER ĐỒ ĂN</h3>
            <div class="cinema-info">
                {{ $booking->showtime->cinema->name ?? 'N/A' }}
            </div>
        </div>
        
        <div class="ticket-body">
            <div class="text-center mb-3">
                <h5 class="fw-bold text-danger">{{ $item->productVariant->product->name ?? 'N/A' }}</h5>
                <small class="text-muted">{{ $booking->booking_code }}</small>
            </div>
            
            <div class="row g-2 ticket-info">
                <div class="col-6">
                    <strong>Size:</strong><br>
                    <span class="badge bg-warning text-dark">
                        {{ $item->productVariant->size ?? 'N/A' }}
                    </span>
                </div>
                <div class="col-6">
                    <strong>Số lượng:</strong><br>
                    <span class="badge bg-info">{{ $item->quantity ?? 1 }}</span>
                </div>
                <div class="col-6">
                    <strong>Đơn giá:</strong><br>
                    {{ number_format($item->price ?? 0) }}đ
                </div>
                <div class="col-6">
                    <strong>Thành tiền:</strong><br>
                    {{ number_format(($item->price ?? 0) * ($item->quantity ?? 1)) }}đ
                </div>
                <div class="col-12">
                    <strong>Khách hàng:</strong><br>
                    {{ $booking->customer_name ?? 'N/A' }} - {{ $booking->customer_phone ?? 'N/A' }}
                </div>
            </div>
            
            <div class="qr-code">
                <div id="qr-food-{{ $item->id }}"></div>
                <small class="text-muted d-block mt-2">Mã voucher: FOOD{{ $item->id }}</small>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        // Generate QR codes for tickets
        @foreach($tickets as $ticket)
        QRCode.toCanvas(document.getElementById('qr-ticket-{{ $ticket->id }}'), 'TICKET{{ $ticket->id }}', {
            width: 120,
            height: 120,
            margin: 1,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        });
        @endforeach

        // Generate QR codes for food items
        @foreach($bookingItems as $item)
        QRCode.toCanvas(document.getElementById('qr-food-{{ $item->id }}'), 'FOOD{{ $item->id }}', {
            width: 120,
            height: 120,
            margin: 1,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        });
        @endforeach

        // Auto print when page loads (optional)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 1000);
        // };
    </script>
</body>
</html>
