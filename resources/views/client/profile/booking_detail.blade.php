@extends('layouts.client.client')

@section('title', 'Chi tiết đặt vé | CineVN')

@section('content')
    <style>
        .detail-container {
            display: flex;
            justify-content: center;
            margin: 40px auto;
            max-width: 900px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding: 20px;
        }

        .detail-box {
            flex: 1;
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s ease;
        }

        .detail-box:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .detail-header {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            align-items: center;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .poster-img {
            width: 140px;
            height: 200px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid #dee2e6;
        }

        .info-group {
            flex: 1;
        }

        .booking-code {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
            margin-left: 10px;
        }

        .status-pending {
            background: #ffc107;
        }

        .status-confirmed {
            background: #28a745;
        }

        .status-cancelled {
            background: #dc3545;
        }

        .status-refunded {
            background: #007bff;
        }

        .status-expired {
            background: #6c757d;
        }

        .movie-title {
            font-size: 24px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 10px;
        }

        .show-info {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .seats-list,
        .snacks-list {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .seats-list strong,
        .snacks-list strong {
            color: #212529;
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }

        .seat-details {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            margin: 5px 0;
            padding: 8px;
            background: #ffffff;
            border-radius: 4px;
            border-left: 4px solid #28a745;
        }

        .snacks-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 12px;
            background: #ffffff;
            border-radius: 6px;
            border-left: 4px solid #007bff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .snacks-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 12px;
            border: 1px solid #dee2e6;
            flex-shrink: 0;
        }

        .snacks-item-info {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            color: #495057;
        }

        @media (max-width: 640px) {
            .snacks-item {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .snacks-item img {
                margin-right: 0;
                margin-bottom: 8px;
                width: 80px;
                height: 80px;
            }
        }

        .snacks-list ul {
            padding-left: 20px;
            margin: 0;
        }

        .snacks-list li {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 15px;
            padding: 15px;
            background: #ffffff;
            border-radius: 6px;
            border-left: 4px solid #007bff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .variant-detail-badge {
            display: inline-block;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            margin-right: 8px;
            font-size: 13px;
            border: 1px solid #dee2e6;
        }

        .showtime-details {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #2196f3;
        }

        .showtime-details strong {
            color: #1976d2;
            font-size: 16px;
            display: block;
            margin-bottom: 10px;
        }

        .showtime-item {
            font-size: 14px;
            color: #424242;
            margin: 5px 0;
            font-weight: 500;
        }

        .detail-price {
            font-weight: 700;
            color: #dc3545;
            font-size: 20px;
            margin-top: 20px;
            text-align: right;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 25px;
            background: #007bff;
            color: #fff;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: background 0.3s ease;
            margin-top: 20px;
            text-align: center;
        }

        .back-btn:hover {
            background: #0056b3;
        }        @media (max-width: 640px) {
            .detail-container {
                margin: 20px;
                padding: 15px;
            }

            .detail-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .poster-img {
                width: 100%;
                height: auto;
                max-height: 300px;
            }

            .detail-price {
                text-align: left;
            }
        }
    </style>

    <div class="detail-container">
        @include('client.profile.menu')

        <div class="detail-box">
            <h3 style="font-size:20px; font-weight:700; margin-bottom:25px;">
                <i class="fas fa-info-circle" style="color:#007bff;"></i> Chi tiết đặt vé
            </h3>

            @if($booking)
                @php
                    $statusValue = $booking->status instanceof \BackedEnum ? $booking->status->value : $booking->status;
                    $firstTicket = $booking->tickets->first();
                    $movie = $firstTicket?->showtime?->movie;
                @endphp

                <div class="detail-header">
                    <img src="{{ $movie?->poster_url ?? ($movie?->image_path ? asset('storage/' . $movie->image_path) : asset('images/default-poster.jpg')) }}"
                        alt="{{ $movie?->name ?? 'Poster' }}" class="poster-img" />

                    <div class="info-group">
                        <div class="booking-code">
                            🆔 Mã đặt vé: <strong>{{ $booking->booking_code }}</strong>
                            <span class="status-badge status-{{ strtolower($statusValue) }}">
                                {{ ucfirst($statusValue) }}
                            </span>
                        </div>

                        <div class="movie-title">{{ $movie?->name ?? 'Chưa xác định' }}</div>

                        <div class="show-info">
                            📅
                            {{ $firstTicket?->showtime?->show_date ? \Carbon\Carbon::parse($firstTicket->showtime->show_date)->format('d/m/Y') : '' }}
                            | ⏰ {{ $firstTicket?->showtime?->formatted_start_time ?? '' }}
                        </div>

                        <div class="show-info">
                            🎬 {{ $firstTicket?->showtime?->room?->cinema?->name ?? 'Chưa xác định' }} -
                            {{ $firstTicket?->showtime?->room?->name ?? 'Chưa xác định' }}
                        </div>
                    </div>
                </div>

                <!-- Thông tin chi tiết về suất chiếu -->
                @if($firstTicket?->showtime)
                    <div class="showtime-details">
                        <strong>🎬 Thông tin suất chiếu</strong>
                        <div class="showtime-item">
                            📅 Ngày chiếu: {{ \Carbon\Carbon::parse($firstTicket->showtime->show_date)->format('d/m/Y') }}
                        </div>
                        <div class="showtime-item">
                            ⏰ Giờ chiếu: {{ $firstTicket->showtime->formatted_start_time ?? 'Chưa có thông tin' }}
                        </div>
                        <div class="showtime-item">
                            ⏱️ Thời lượng: {{ $movie?->duration_minutes ?? 'Chưa xác định' }} phút
                        </div>
                        <div class="showtime-item">
                            🏢 Rạp: {{ $firstTicket->showtime->room?->cinema?->name ?? 'Chưa xác định' }}
                        </div>
                        <div class="showtime-item">
                            🎪 Phòng chiếu: {{ $firstTicket->showtime->room?->name ?? 'Chưa xác định' }}
                        </div>
                    </div>
                @endif

                <div class="seats-list">
                    <strong>💺 Danh sách ghế đã đặt:</strong>
                    @if($booking->tickets && $booking->tickets->count() > 0)
                        @foreach($booking->tickets as $ticket)
                            @if($ticket->seat)
                                <div class="seat-details">
                                    🪑 Hàng {{ $ticket->seat->row_char ?? 'N/A' }} - Ghế {{ $ticket->seat->seat_number ?? 'N/A' }}
                                    | Loại: {{ $ticket->seat->seatType->name ?? 'Thường' }}
                                    | Giá: {{ number_format($ticket->price_at_purchase ?? 0, 0, ',', '.') }}đ
                                    | Trạng thái: 
                                    <span style="color: #28a745; font-weight: bold;">
                                        {{ $ticket->status->value ?? 'Valid' }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p>Không có thông tin ghế.</p>
                    @endif
                </div>

                @if($booking->bookingItems && $booking->bookingItems->count() > 0)
                    <div class="snacks-list">
                        <strong>🍿 Đồ ăn kèm:</strong>
                        @foreach($booking->bookingItems as $item)
                            @php
                                $productVariant = $item->productVariant;
                                $product = $productVariant?->product;
                                $productName = $product ? $product->name : 'Sản phẩm không xác định';
                                
                                // Lấy thông tin chi tiết biến thể
                                $variantDetails = [];
                                if ($productVariant && $productVariant->productVariantOptions) {
                                    foreach ($productVariant->productVariantOptions as $option) {
                                        $attributeValue = $option->attributeValue;
                                        $attribute = $attributeValue?->attribute;
                                        if ($attribute && $attributeValue) {
                                            $variantDetails[] = $attribute->name . ': ' . $attributeValue->value;
                                        }
                                    }
                                }
                                
                                // Fallback về tên biến thể nếu không có variant options
                                if (empty($variantDetails) && $productVariant) {
                                    $variantName = $productVariant->name ?? null;
                                    if ($variantName && $variantName !== $productName) {
                                        $variantDetails[] = $variantName;
                                    }
                                }
                                
                                $variantText = !empty($variantDetails) ? ' (' . implode(' | ', $variantDetails) . ')' : '';
                                
                                // Lấy ảnh sản phẩm - ưu tiên ảnh variant, sau đó ảnh product
                                $imageUrl = null;
                                if ($productVariant && $productVariant->image_url) {
                                    $imageUrl = asset('storage/' . $productVariant->image_url);
                                } elseif ($product && $product->image_url) {
                                    $imageUrl = asset('storage/' . $product->image_url);
                                } 
                            @endphp
                            <div class="snacks-item">
                                <img src="{{ $imageUrl }}" alt="{{ $productName }}">
                                <div class="snacks-item-info">
                                     {{ $productName }}{{ $variantText }}
                                    | Số lượng: {{ $item->quantity ?? 1 }}
                                    | Giá: {{ number_format($item->price_at_purchase ?? 0, 0, ',', '.') }}đ
                                    | Tổng: {{ number_format(($item->price_at_purchase ?? 0) * ($item->quantity ?? 1), 0, ',', '.') }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="snacks-list">
                        <strong>🍿 Đồ ăn kèm:</strong>
                        <p>Không có đồ ăn kèm trong đơn hàng này.</p>
                    </div>
                @endif

                <div class="detail-price">💰 {{ number_format($booking->final_amount, 0, ',', '.') }} VNĐ</div>

                <a href="/profile/history" class="back-btn">Quay lại</a>
            @else
                <p>Không tìm thấy thông tin đặt vé.</p>
            @endif
        </div>
    </div>

    @include('client.footer.footer')
@endsection