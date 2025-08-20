<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Vé - {{ $booking->booking_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- QRious - Reliable QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            body { 
                margin: 0; 
                padding: 0;
                background: white !important;
            }
            .ticket { 
                margin: 10px auto !important; 
                box-shadow: none !important; 
                width: 300px !important;
                max-width: 100% !important;
                page-break-inside: avoid;
                border: 2px solid #333 !important;
                border-radius: 15px !important;
            }
            .ticket-header {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                border-bottom: 2px dashed #333 !important;
            }
            .food-voucher .ticket-header {
                background: #f093fb !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                border-bottom: 2px dashed #333 !important;
            }
            .ticket-body {
                border-top: none !important;
            }
            .qr-code canvas {
                border: 2px solid #333 !important;
                border-radius: 8px !important;
            }
            .ticket-info {
                border-top: 1px solid #eee !important;
                padding-top: 10px !important;
                margin-top: 10px !important;
            }
        }
        
        .ticket {
            width: 320px;
            max-width: 90%;
            margin: 20px auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 0;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
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
            padding: 12px 15px;
            text-align: center;
            border-bottom: 2px dashed rgba(255,255,255,0.3);
        }
        
        .ticket-body {
            padding: 15px;
            background: white;
            color: #333;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .food-voucher {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .qr-code {
            text-align: center;
            margin: 15px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .qr-code canvas {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            margin: 0 auto;
            display: block;
        }
        
        .ticket-info {
            font-size: 13px;
            line-height: 1.5;
            border-top: 1px solid #f0f0f0;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .ticket-title {
            color: white;
            font-size: 16px;
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
                    {{ $ticket->seat_type->name ?? 'Thường' }}
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
                    {{ $booking->customer_name ?: ($booking->user->name ?? 'Khách vãng lai') }} 
                    @if($booking->customer_phone)
                        - {{ $booking->customer_phone }}
                    @elseif($booking->user && $booking->user->phone)
                        - {{ $booking->user->phone }}
                    @endif
                </div>
            </div>
            
            <div class="qr-code">
                <canvas id="qr-ticket-{{ $ticket->id }}" width="100" height="100"></canvas>
                <small class="text-muted d-block mt-2 fw-medium">{{ $ticket->ticket_code ?? 'TICKET'.$ticket->id }}</small>
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
                        @if($item->productVariant && $item->productVariant->productVariantOptions->isNotEmpty())
                            @foreach($item->productVariant->productVariantOptions as $option)
                                {{ $option->attributeValue->value ?? '' }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        @else
                            {{ $item->productVariant->sku ?? 'Standard' }}
                        @endif
                    </span>
                </div>
                <div class="col-6">
                    <strong>Số lượng:</strong><br>
                    <span class="badge bg-info">{{ $item->quantity ?? 1 }}</span>
                </div>
                <div class="col-6">
                    <strong>Đơn giá:</strong><br>
                    {{ number_format($item->price_at_purchase ?? 0) }}đ
                </div>
                <div class="col-6">
                    <strong>Thành tiền:</strong><br>
                    {{ number_format(($item->price_at_purchase ?? 0) * ($item->quantity ?? 1)) }}đ
                </div>
                <div class="col-12">
                    <strong>Khách hàng:</strong><br>
                    {{ $booking->customer_name ?: ($booking->user->name ?? 'Khách vãng lai') }} 
                    @if($booking->customer_phone)
                        - {{ $booking->customer_phone }}
                    @elseif($booking->user && $booking->user->phone)
                        - {{ $booking->user->phone }}
                    @endif
                </div>
            </div>
            
            <div class="qr-code">
                <canvas id="qr-food-{{ $item->id }}" width="100" height="100"></canvas>
                <small class="text-muted d-block mt-2 fw-medium">FOOD{{ $item->id }}</small>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking QRCode library...');
            
            // Check if QRCode library is loaded
            if (typeof QRCode === 'undefined') {
                console.error('QRCode library not loaded!');
                return;
            }
            
            console.log('QRCode library loaded, generating QR codes...');
            
            // Generate QR codes for tickets
            @foreach($tickets as $ticket)
            try {
                const ticketCanvas{{ $ticket->id }} = document.getElementById('qr-ticket-{{ $ticket->id }}');
                console.log('Ticket canvas {{ $ticket->id }}:', ticketCanvas{{ $ticket->id }});
                if (ticketCanvas{{ $ticket->id }}) {
                    const ticketCode = '{{ $ticket->ticket_code ?? "TICKET".$ticket->id }}';
                    console.log('Generating QR for ticket code:', ticketCode);
                    QRCode.toCanvas(ticketCanvas{{ $ticket->id }}, ticketCode, {
                        width: 120,
                        height: 120,
                        margin: 1,
                        color: {
                            dark: '#000000',
                            light: '#FFFFFF'
                        }
                    }).then(() => {
                        console.log('QR ticket {{ $ticket->id }} generated successfully');
                    }).catch(err => {
                        console.error('Error generating QR ticket {{ $ticket->id }}:', err);
                    });
                } else {
                    console.error('Canvas element not found for ticket {{ $ticket->id }}');
                }
            } catch (error) {
                console.error('Error with ticket {{ $ticket->id }}:', error);
            }
            @endforeach

            // Generate QR codes for food items
            @foreach($bookingItems as $item)
            try {
                const foodCanvas{{ $item->id }} = document.getElementById('qr-food-{{ $item->id }}');
                console.log('Food canvas {{ $item->id }}:', foodCanvas{{ $item->id }});
                if (foodCanvas{{ $item->id }}) {
                    const foodCode = 'FOOD{{ $item->id }}';
                    console.log('Generating QR for food code:', foodCode);
                    QRCode.toCanvas(foodCanvas{{ $item->id }}, foodCode, {
                        width: 120,
                        height: 120,
                        margin: 1,
                        color: {
                            dark: '#000000',
                            light: '#FFFFFF'
                        }
                    }).then(() => {
                        console.log('QR food {{ $item->id }} generated successfully');
                    }).catch(err => {
                        console.error('Error generating QR food {{ $item->id }}:', err);
                    });
                } else {
                    console.error('Canvas element not found for food {{ $item->id }}');
                }
            } catch (error) {
                console.error('Error with food {{ $item->id }}:', error);
            }
            @endforeach
        });

        // Test QR generation immediately
        setTimeout(function() {
            console.log('Testing QR generation...');
            @foreach($tickets as $ticket)
            const testCanvas{{ $ticket->id }} = document.getElementById('qr-ticket-{{ $ticket->id }}');
            if (testCanvas{{ $ticket->id }}) {
                console.log('Found canvas for ticket {{ $ticket->id }}, generating...');
                if (typeof QRCode !== 'undefined') {
                    QRCode.toCanvas(testCanvas{{ $ticket->id }}, '{{ $ticket->ticket_code ?? "TICKET".$ticket->id }}', function (error) {
                        if (error) console.error('Error:', error);
                        else console.log('Success for ticket {{ $ticket->id }}');
                    });
                } else {
                    console.error('QRCode library not available');
                    // Fallback: hiển thị text
                    testCanvas{{ $ticket->id }}.style.border = '2px solid #ddd';
                    testCanvas{{ $ticket->id }}.style.display = 'flex';
                    testCanvas{{ $ticket->id }}.style.alignItems = 'center';
                    testCanvas{{ $ticket->id }}.style.justifyContent = 'center';
                    testCanvas{{ $ticket->id }}.innerHTML = '<small>{{ $ticket->ticket_code ?? "TICKET".$ticket->id }}</small>';
                }
            }
            @endforeach
        }, 2000);

        // Auto print when page loads (optional)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 1000);
        // };
    </script>

    <!-- QRious Implementation -->
    <script>
        window.addEventListener('load', function() {
            console.log('🚀 Page loaded, generating QR codes with QRious...');
            
            // Check if QRious is available
            if (typeof QRious === 'undefined') {
                console.error('❌ QRious library not loaded!');
                return;
            }
            
            console.log('✅ QRious library available');
            
            // Generate QR codes for tickets
            @foreach($tickets as $ticket)
            setTimeout(function() {
                const ticketCanvas = document.getElementById('qr-ticket-{{ $ticket->id }}');
                if (ticketCanvas) {
                    const ticketCode = '{{ $ticket->ticket_code ?? "TICKET".$ticket->id }}';
                    console.log('Generating ticket QR:', ticketCode);
                    
                    new QRious({
                        element: ticketCanvas,
                        value: ticketCode,
                        size: 100,
                        level: 'M'
                    });
                    
                    console.log('✅ Ticket QR generated:', ticketCode);
                }
            }, {{ $loop->index * 200 + 500 }});
            @endforeach

            // Generate QR codes for food items
            @foreach($bookingItems as $item)
            setTimeout(function() {
                const foodCanvas = document.getElementById('qr-food-{{ $item->id }}');
                if (foodCanvas) {
                    const foodCode = 'FOOD{{ $item->id }}';
                    console.log('Generating food QR:', foodCode);
                    
                    new QRious({
                        element: foodCanvas,
                        value: foodCode,
                        size: 100,
                        level: 'M'
                    });
                    
                    console.log('✅ Food QR generated:', foodCode);
                }
            }, {{ ($tickets->count() + $loop->index) * 200 + 1000 }});
            @endforeach
        });
    </script>
</body>
</html>
