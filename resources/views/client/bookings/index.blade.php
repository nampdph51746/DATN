@extends('layouts.client.client')

@section('content')
<style>
body {
    background-color: #f9fafb; /* màu nền sáng, nhẹ nhàng */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #222; /* chữ tối dễ đọc */
    margin: 0;
}

.container-content {
    max-width: 960px;
    margin: 0 auto;
    padding: 100px 20px 60px; /* padding-top đủ cao tránh trùng navbar, bạn có thể tăng giảm */
    box-sizing: border-box;
}

.page-title {
    font-size: 2.4rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 40px;
    color: #f39c12; /* cam vàng sáng */
    letter-spacing: 2px;
    text-shadow: 0 0 3px #f39c12aa;
}

.ticket-card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    padding: 20px 24px;
    margin-bottom: 24px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: default;
}

.ticket-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 20px rgba(243, 156, 18, 0.3);
}

.flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.items-start {
    align-items: flex-start;
}

.mb-4 {
    margin-bottom: 1rem;
}

.booking-code {
    font-size: 1.2rem;
    font-weight: 700;
    color: #f39c12; /* màu điểm nhấn */
    user-select: text;
}

.created-at {
    font-size: 0.9rem;
    color: #555;
    margin-top: 6px;
}

.status-badge {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: capitalize;
    min-width: 110px;
    text-align: center;
    user-select: none;
    transition: background-color 0.3s ease, color 0.3s ease;
    box-shadow: 0 0 5px #0001;
}

.status-confirmed {
    background-color: #27ae60;
    color: #fff;
    box-shadow: 0 0 8px #27ae6099;
}

.status-pending {
    background-color: #f39c12;
    color: #1c1c1c;
    box-shadow: 0 0 8px #f39c1299;
}

.status-cancelled {
    background-color: #e74c3c;
    color: #fff;
    box-shadow: 0 0 8px #e74c3c99;
}

.total-amount {
    font-size: 1.1rem;
    font-weight: 600;
    color: #222;
    user-select: text;
}

.total-amount span {
    color: #f39c12;
    font-weight: 700;
}

.details-button {
    padding: 10px 22px;
    background-color: #f39c12;
    color: #fff;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(243, 156, 18, 0.5);
    transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    user-select: none;
}

.details-button:hover,
.details-button:focus {
    background-color: #d17c0f;
    color: #fff;
    box-shadow: 0 6px 18px rgba(209, 124, 15, 0.75);
}

.empty-message {
    text-align: center;
    padding: 50px 20px;
    background-color: #fff3cd; /* nền vàng nhạt báo trống */
    border-radius: 12px;
    color: #856404; /* màu chữ nâu đậm */
    font-size: 1.2rem;
    font-style: italic;
    box-shadow: inset 0 0 15px #fff8dc;
    user-select: none;
    margin-top: 40px;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 32px;
    gap: 8px;
}

.pagination a,
.pagination span {
    padding: 10px 16px;
    background-color: #f9fafb;
    border: 1.5px solid #f39c12;
    color: #f39c12;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    user-select: none;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.pagination a:hover {
    background-color: #f39c12;
    color: #fff;
}

.pagination .current {
    background-color: #f39c12;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 0 12px #f39c12cc;
}

body.dark .ticket-card {
    background: #1e1e1e;
    border: 1px solid #444;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.7);
}

body.dark .booking-code {
    color: #f4c430;
}

body.dark .created-at {
    color: #bbb;
}

body.dark .status-confirmed {
    background: #27ae60;
    color: #eee;
}

body.dark .status-pending {
    background: #f1c40f;
    color: #222;
}

body.dark .status-cancelled {
    background: #e74c3c;
    color: #eee;
}

body.dark .total-amount {
    color: #f4c430;
}

body.dark .details-button {
    background: #f4c430;
    color: #1c2526;
}

body.dark .details-button:hover {
    background: #d4a017;
}

body.dark .empty-message {
    background: #2a2e2f;
    color: #a0a0a0;
}

body[data-theme="dark"] {
  background-color: #121212; /* hoặc màu tối bạn muốn */
  color: #eee;
}

body[data-theme="dark"] .ticket-card {
  background-color: #1e1e1e;
  border-color: #444;
  box-shadow: 0 4px 12px rgba(0,0,0,0.7);
}

.empty-message {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 300px; /* chiều cao đủ để trang không bị ngắn */
    background-color: #fff3cd; /* nền vàng nhạt nổi bật */
    border-radius: 12px;
    color: #856404; /* màu chữ nâu đậm */
    font-size: 1.5rem;
    font-style: italic;
    box-shadow: inset 0 0 15px #fff8dc;
    user-select: none;
    margin: 40px 0;
    text-align: center;
    padding: 0 20px;
}
</style>
<section class="w3l-grids">

<div class="container-content mx-auto px-4 mt-12 py-10">
    <h1 class="page-title">🎬 Lịch Sử Đặt Vé</h1>

    @forelse ($bookings as $booking)
        <div class="ticket-card">
            <div class="flex justify-between items-start mb-4 flex-wrap">
                <div class="flex flex-col">
                    <span class="booking-code" aria-label="Mã đơn đặt vé">
                        🆔 Mã Đơn: <strong>{{ $booking->booking_code }}</strong>
                    </span>
                    <span class="created-at" aria-label="Ngày đặt vé">📅 Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <span class="status-badge @if($booking->status->value === 'confirmed') status-confirmed
                    @elseif($booking->status->value === 'pending') status-pending
                    @else status-cancelled @endif">
                    {{ ucfirst($booking->status->value) }}
                </span>
            </div>

            <div class="flex justify-between items-center flex-wrap gap-4">
                <span class="total-amount" aria-label="Tổng tiền đặt vé">💰 Tổng tiền: 
                    <strong>{{ number_format($booking->final_amount) }} VNĐ</strong>
                </span>

                <a href="{{ route('client.bookings.show', $booking->id) }}" class="details-button" aria-label="Xem chi tiết đơn đặt vé">
                    Xem chi tiết
                </a>
            </div>
        </div>
    @empty
        <div class="empty-message" role="alert" aria-live="polite">
            <p>Bạn chưa có đơn đặt vé nào.</p>
        </div>
    @endforelse

    <div class="pagination mt-6">
        {{ $bookings->links('pagination::bootstrap-4') }}
    </div>
</div>
</section>
@include('client.footer.footer')

@endsection