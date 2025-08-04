@extends('layouts.client.client')

@section('content')
<style>
    body {
        background: #ffffff;
        font-family: 'Helvetica Neue', Arial, sans-serif;

    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px;
        color: #f4c430;
        letter-spacing: 1px;
    }

    .ticket-card {
        background: #000000;
        border: 1px solid #3a3e3f;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .ticket-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .booking-code {
        font-size: 1.1rem;
        font-weight: 600;
        color: #ffffff;
    }

    .booking-code span {
        color: #f4c430;
    }

    .created-at {
        font-size: 0.85rem;
        color: #a0a0a0;
        margin-top: 4px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-confirmed {
        background: #2ecc71;
        color: #ffffff;
    }

    .status-pending {
        background: #f1c40f;
        color: #1c2526;
    }

    .status-cancelled {
        background: #e74c3c;
        color: #ffffff;
    }

    .total-amount {
        font-size: 1rem;
        color: #ffffff;
    }

    .total-amount span {
        font-weight: 600;
        color: #f4c430;
    }

    .details-button {
        padding: 8px 16px;
        background: #f4c430;
        color: #1c2526;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: background 0.2s ease;
    }

    .details-button:hover {
        background: #d4a017;
    }

    .empty-message {
        text-align: center;
        padding: 30px;
        background: #2a2e2f;
        border-radius: 8px;
        color: #a0a0a0;
        font-size: 1.1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination a, .pagination span {
        padding: 8px 12px;
        margin: 0 4px;
        background: #2a2e2f;
        border: 1px solid #f4c430;
        color: #f4c430;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.85rem;
        transition: background 0.2s ease;
    }

    .pagination a:hover {
        background: #f4c430;
        color: #1c2526;
    }

    .pagination .current {
        background: #f4c430;
        color: #1c2526;
        font-weight: 600;
    }
</style>

<div class="container mx-auto px-4 mt-[50px] py-10">
    <h1 class="page-title">🎬 Lịch Sử Đặt Vé</h1>

    @forelse ($bookings as $booking)
        <div class="ticket-card">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="booking-code">
                        🆔 Mã Đơn: <span>{{ $booking->booking_code }}</span>
                    </p>
                    <p class="created-at">📅 Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span class="status-badge @if($booking->status->value === 'confirmed') status-confirmed
                    @elseif($booking->status->value === 'pending') status-pending
                    @else status-cancelled @endif">
                    {{ ucfirst($booking->status->value) }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <p class="total-amount">💰 Tổng tiền: 
                    <span>{{ number_format($booking->final_amount) }} VNĐ</span>
                </p>

                <a href="{{ route('client.bookings.show', $booking->id) }}" class="details-button">
                    Xem chi tiết
                </a>
            </div>
        </div>
    @empty
        <div class="empty-message">
            <p>Bạn chưa có đơn đặt vé nào.</p>
        </div>
    @endforelse

    <div class="pagination">
        {{ $bookings->links() }}
    </div>
</div>
@endsection