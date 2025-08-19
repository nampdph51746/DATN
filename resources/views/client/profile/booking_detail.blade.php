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

        .seats-list p,
        .snacks-list ul {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            margin: 0;
        }

        .snacks-list ul {
            padding-left: 20px;
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
        }

        @media (max-width: 640px) {
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

                    // Lấy danh sách ghế, lọc null, unique rồi sắp xếp theo hàng ghế (chữ cái) rồi số ghế
                    $seats = $booking->tickets
                        ->map(fn($t) => $t->seat ? $t->seat->row . $t->seat->seat_number : null)
                        ->filter()
                        ->unique()
                        ->sort(function ($a, $b) {
                            preg_match('/([A-Za-z]+)(\d+)/', $a, $matchA);
                            preg_match('/([A-Za-z]+)(\d+)/', $b, $matchB);

                            $rowA = $matchA[1] ?? '';
                            $numA = intval($matchA[2] ?? 0);
                            $rowB = $matchB[1] ?? '';
                            $numB = intval($matchB[2] ?? 0);

                            $cmpRow = strcmp($rowA, $rowB);
                            if ($cmpRow !== 0)
                                return $cmpRow;
                            return $numA <=> $numB;
                        })
                        ->values();
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
                            | ⏰ {{ $firstTicket?->showtime?->show_time ?? '' }}
                        </div>

                        <div class="show-info">
                            🎬 {{ $firstTicket->seat?->room?->cinema?->name ?? 'Chưa xác định' }} -
                            {{ $firstTicket->seat?->room?->name ?? 'Chưa xác định' }}
                        </div>
                    </div>
                </div>

                <div class="seats-list">
                    <strong>💺 Danh sách ghế đã đặt:</strong>
                    <p>{{ $seats->join(', ') }}</p>
                </div>

                @if($booking->items->count() > 0)
                    <div class="snacks-list">
                        <strong>🍿 Đồ ăn kèm:</strong>
                        <ul>
                            @foreach($booking->items as $item)
                                <li>{{ $item->productVariant->product->name ?? 'Chưa xác định' }} x {{ $item->quantity ?? 1 }}</li>
                            @endforeach
                        </ul>
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