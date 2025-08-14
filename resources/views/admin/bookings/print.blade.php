<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vé đặt - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .ticket-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
        }
        
        .ticket-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="rgba(37,99,235,0.05)"/></svg>') repeat;
            pointer-events: none;
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .ticket-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            opacity: 0.5;
        }
        
        .ticket-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .ticket-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .ticket-code {
            font-size: 1.5rem;
            font-weight: 600;
            opacity: 0.95;
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            display: inline-block;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .ticket-body {
            padding: 3rem 2rem;
            position: relative;
            z-index: 1;
        }
        
        .booking-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 1.5rem;
            border: 2px solid #e2e8f0;
            position: relative;
        }
        
        .booking-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="1" fill="rgba(37,99,235,0.1)"/><circle cx="80" cy="80" r="1" fill="rgba(37,99,235,0.1)"/></svg>') repeat;
            opacity: 0.3;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            position: relative;
            z-index: 1;
        }
        
        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-label::before {
            content: '';
            width: 8px;
            height: 8px;
            background: #2563eb;
            border-radius: 50%;
        }
        
        .info-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .tickets-section, .products-section {
            margin-bottom: 3rem;
        }
        
        .section-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #2563eb;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .section-title::before {
            content: '';
            width: 12px;
            height: 12px;
            background: #2563eb;
            border-radius: 50%;
        }
        
        .ticket-item {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 1.5rem;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .ticket-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), transparent);
            border-radius: 0 0 0 100%;
        }
        
        .ticket-item:hover {
            border-color: #2563eb;
            box-shadow: 0 12px 35px rgba(37, 99, 235, 0.15);
            transform: translateY(-2px);
        }
        
        .ticket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .ticket-detail {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .ticket-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .ticket-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .movie-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #2563eb;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .seat-info {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .price-info {
            font-size: 1.25rem;
            font-weight: 800;
            color: #059669;
            text-align: right;
        }
        
        .product-item {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .product-item:hover {
            border-color: #2563eb;
            transform: translateX(5px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.1);
        }
        
        .product-info {
            flex: 1;
        }
        
        .product-name {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-size: 1.125rem;
        }
        
        .product-details {
            font-size: 0.95rem;
            color: #6b7280;
            font-weight: 500;
        }
        
        .product-price {
            font-weight: 800;
            color: #2563eb;
            font-size: 1.25rem;
        }
        
        .summary-section {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 1.5rem;
            padding: 2rem;
            border: 2px solid #93c5fd;
            position: relative;
            overflow: hidden;
        }
        
        .summary-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .summary-row {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(37, 99, 235, 0.2);
            position: relative;
            z-index: 1;
        }
        
        .summary-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e40af;
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        
        .summary-label {
            font-weight: 700;
            color: #1e40af;
            font-size: 1.125rem;
        }
        
        .summary-value {
            font-weight: 800;
            color: #1e40af;
            font-size: 1.125rem;
        }
        
        .footer-note {
            text-align: center;
            margin-top: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 1.5rem;
            font-size: 1rem;
            color: #92400e;
            border: 2px solid #f59e0b;
            position: relative;
            overflow: hidden;
        }
        
        .footer-note::before {
            content: '⚠️';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: #f59e0b;
            padding: 0.5rem;
            border-radius: 50%;
            font-size: 1.5rem;
        }
        
        .footer-note strong {
            color: #92400e;
            font-weight: 800;
        }
        
        .print-date {
            text-align: right;
            font-size: 0.875rem;
            color: #9ca3af;
            margin-top: 2rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            border: 1px dashed #d1d5db;
        }
        
        .barcode-section {
            text-align: center;
            margin: 2rem 0;
            padding: 1.5rem;
            background: white;
            border-radius: 1rem;
            border: 2px dashed #2563eb;
        }
        
        .barcode-placeholder {
            font-size: 2rem;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }
        
        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: 0.1em;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .ticket-container {
                box-shadow: none;
                max-width: none;
                border-radius: 0;
            }
            
            .ticket-item:hover {
                border-color: #e2e8f0;
                box-shadow: none;
                transform: none;
            }
            
            .product-item:hover {
                border-color: #e2e8f0;
                transform: none;
                box-shadow: none;
            }
        }
        
        @media (max-width: 768px) {
            .ticket-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .ticket-header {
                padding: 2rem 1rem;
            }
            
            .ticket-title {
                font-size: 2rem;
            }
            
            .ticket-body {
                padding: 2rem 1rem;
            }
            
            .booking-info {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1.5rem;
            }
            
            .ticket-grid {
                grid-template-columns: 1fr;
            }
            
            .product-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                text-align: left;
            }
            
            .summary-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
                text-align: left;
            }
            
            .summary-row:last-child {
                flex-direction: row;
                justify-content: between;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <h1 class="ticket-title">🎬 VÉ ĐẶT PHIM</h1>
            <div class="ticket-code">{{ $booking->booking_code }}</div>
        </div>
        
        <!-- Body -->
        <div class="ticket-body">
            <!-- Booking Information -->
            <div class="booking-info">
                <div class="info-item">
                    <div class="info-label">👤 Khách hàng</div>
                    <div class="info-value">{{ $booking->user->name ?? 'Người dùng #' . $booking->user_id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">📅 Ngày đặt</div>
                    <div class="info-value">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">🏷️ Trạng thái</div>
                    <div class="info-value">
                        @php
                            $status = is_object($booking->status) ? $booking->status->value : (string) $booking->status;
                        @endphp
                        {{ ucfirst($status) }}
                    </div>
                </div>
                @if($booking->user->email)
                    <div class="info-item">
                        <div class="info-label">📧 Email</div>
                        <div class="info-value">{{ $booking->user->email }}</div>
                    </div>
                @endif
            </div>
            
            <!-- Barcode Section -->
            <div class="barcode-section">
                <div class="barcode-placeholder">|||||||||||||||||||||||||||||||</div>
                <div class="barcode-text">{{ $booking->booking_code }}</div>
            </div>
            
            <!-- Tickets Section -->
            @if ($tickets && $tickets->count() > 0)
                <div class="tickets-section">
                    <h2 class="section-title">🎫 Danh sách vé ({{ $tickets->count() }} vé)</h2>
                    
                    @foreach ($tickets as $index => $ticket)
                        <div class="ticket-item">
                            <div class="movie-title">
                                🎬 {{ optional($ticket->showtime->movie)->name ?? 'Không rõ phim' }}
                            </div>
                            
                            <div class="ticket-grid">
                                <div class="ticket-detail">
                                    <div class="ticket-label">Mã vé</div>
                                    <div class="ticket-value">{{ $ticket->ticket_code }}</div>
                                </div>
                                
                                <div class="ticket-detail">
                                    <div class="ticket-label">Suất chiếu</div>
                                    <div class="ticket-value">
                                        {{ optional($ticket->showtime)->start_time ? $ticket->showtime->start_time->format('d/m/Y H:i') : 'Không rõ' }}
                                    </div>
                                </div>
                                
                                <div class="ticket-detail">
                                    <div class="ticket-label">Phòng chiếu</div>
                                    <div class="ticket-value">
                                        🚪 {{ optional($ticket->showtime->room)->name ?? 'Không rõ' }}
                                    </div>
                                </div>
                                
                                <div class="ticket-detail">
                                    <div class="ticket-label">Ghế ngồi</div>
                                    <div class="ticket-value">
                                        <span class="seat-info">
                                            {{ $ticket->seat ? $ticket->seat->row_char . $ticket->seat->seat_number : 'Không rõ' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="ticket-detail">
                                    <div class="ticket-label">Giá vé</div>
                                    <div class="ticket-value price-info">
                                        💰 {{ number_format($ticket->price_at_purchase, 0, ',', '.') }} đ
                                    </div>
                                </div>
                                
                                <div class="ticket-detail">
                                    <div class="ticket-label">Trạng thái vé</div>
                                    <div class="ticket-value">
                                        @php
                                            $ticketStatus = is_object($ticket->status) ? $ticket->status->value : (string) $ticket->status;
                                        @endphp
                                        ✅ {{ ucfirst($ticketStatus) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Products Section -->
            @if ($booking->bookingItems && $booking->bookingItems->count() > 0)
                <div class="products-section">
                    <h2 class="section-title">🍿 Sản phẩm đã đặt ({{ $booking->bookingItems->count() }} sản phẩm)</h2>
                    
                    @foreach ($booking->bookingItems as $item)
                        <div class="product-item">
                            <div class="product-info">
                                <div class="product-name">🛍️ {{ $item->productVariant->product->name ?? 'Sản phẩm không rõ' }}</div>
                                <div class="product-details">
                                    📋 SKU: {{ $item->productVariant->sku ?? 'N/A' }} | 
                                    📦 Số lượng: {{ $item->quantity }} | 
                                    💵 Đơn giá: {{ number_format($item->price_at_purchase, 0, ',', '.') }} đ
                                </div>
                            </div>
                            <div class="product-price">
                                {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }} đ
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Summary Section -->
            <div class="summary-section">
                <div class="summary-row">
                    <span class="summary-label">💰 Tổng tiền trước giảm giá:</span>
                    <span class="summary-value">{{ number_format($booking->total_amount_before_discount, 0, ',', '.') }} đ</span>
                </div>
                
                @if($booking->discount_amount > 0)
                    <div class="summary-row">
                        <span class="summary-label">🎟️ Giảm giá:</span>
                        <span class="summary-value">-{{ number_format($booking->discount_amount, 0, ',', '.') }} đ</span>
                    </div>
                @endif
                
                <div class="summary-row">
                    <span class="summary-label">🏆 TỔNG THANH TOÁN:</span>
                    <span class="summary-value">{{ number_format($booking->final_amount, 0, ',', '.') }} đ</span>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer-note">
                <strong>📝 LƯU Ý QUAN TRỌNG:</strong><br>
                Vui lòng mang theo vé này và giấy tờ tùy thân khi đến rạp. 
                Vé không thể hoàn trả sau khi đã xuất. 
                Vé có hiệu lực trong ngày chiếu được ghi trên vé.
                <br><br>
                <strong>🙏 Xin chân thành cảm ơn quý khách!</strong>
            </div>
            
            <div class="print-date">
                🖨️ In lúc: {{ now()->format('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>