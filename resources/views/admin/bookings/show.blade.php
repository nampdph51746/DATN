@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="detail-header rounded-5 p-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-ticket-detailed me-3"></i>
                            Chi tiết đơn đặt vé
                        </h1>
                        <p class="lead text-white-50 mb-0">Mã đơn: {{ $booking->booking_code }}</p>
                    </div>
                    <div class="header-actions d-flex gap-3">
                        <a href="#!" class="btn btn-light btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-return-left me-2 text-blue"></i>
                            <span class="text-blue fw-bold">Hoàn tiền</span>
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Booking Summary -->
            <div class="summary-card mb-4">
                <div class="summary-header">
                    <div class="summary-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="summary-title-section">
                        <h5 class="summary-title">Thông tin đơn đặt vé</h5>
                        <div class="booking-badges">
                            @if ($booking->payments->first() && (is_object($booking->payments->first()->status) ? $booking->payments->first()->status->value : (string) $booking->payments->first()->status) === 'completed')
                                <span class="status-badge status-paid">
                                    <i class="bi bi-check-circle"></i> Đã thanh toán
                                </span>
                            @else
                                <span class="status-badge status-unpaid">
                                    <i class="bi bi-x-circle"></i> Chưa thanh toán
                                </span>
                            @endif
                            <span class="status-badge status-{{ is_object($booking->status) ? $booking->status->value : (string) $booking->status }}">
                                <i class="bi bi-bookmark"></i>
                                {{ ucfirst(is_object($booking->status) ? $booking->status->value : (string) $booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="summary-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-ticket-perforated text-blue"></i>
                                    <span>Mã đặt vé</span>
                                </div>
                                <div class="info-value">{{ $booking->booking_code }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-person text-success"></i>
                                    <span>Khách hàng</span>
                                </div>
                                <div class="info-value">{{ $booking->user->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-cash-coin text-warning"></i>
                                    <span>Tổng tiền trước giảm</span>
                                </div>
                                <div class="info-value">{{ number_format($booking->total_amount_before_discount, 0, ',', '.') }} đ</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-percent text-info"></i>
                                    <span>Giảm giá</span>
                                </div>
                                <div class="info-value text-danger">{{ number_format($booking->discount_amount, 0, ',', '.') }} đ</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-currency-dollar text-primary"></i>
                                    <span>Tổng thanh toán</span>
                                </div>
                                <div class="info-value text-primary fw-bold">{{ number_format($booking->final_amount, 0, ',', '.') }} đ</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-calendar-event text-secondary"></i>
                                    <span>Ngày đặt</span>
                                </div>
                                <div class="info-value">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        
                        @if($booking->notes)
                            <div class="col-12">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-chat-left-text text-muted"></i>
                                        <span>Ghi chú</span>
                                    </div>
                                    <div class="info-value">{{ $booking->notes }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Progress Timeline -->
            <div class="progress-card mb-4">
                <div class="progress-header">
                    <h5 class="progress-title">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                        Tiến trình đặt vé
                    </h5>
                </div>
                
                <div class="progress-body">
                    @php
                        $statusValue = is_object($booking->status) ? $booking->status->value : (string) $booking->status;
                        $paymentCompleted = $booking->payments->first() && (is_object($booking->payments->first()->status) ? $booking->payments->first()->status->value : (string) $booking->payments->first()->status) === 'completed';
                        
                        $steps = [
                            ['label' => 'Đặt vé', 'icon' => 'bi-cart-plus', 'status' => 'completed'],
                            ['label' => 'Thanh toán', 'icon' => 'bi-credit-card', 'status' => $paymentCompleted ? 'completed' : ($statusValue === 'pending' ? 'pending' : 'completed')],
                            ['label' => 'Xác nhận', 'icon' => 'bi-check-circle', 'status' => $statusValue === 'confirmed' ? 'completed' : ($statusValue === 'pending' ? 'pending' : 'completed')],
                            ['label' => 'Hoàn tất', 'icon' => 'bi-flag-fill', 'status' => $statusValue === 'completed' ? 'completed' : 'pending']
                        ];
                    @endphp
                    
                    <div class="timeline-wrapper">
                        @foreach($steps as $index => $step)
                            <div class="timeline-step {{ $step['status'] }}">
                                <div class="timeline-icon">
                                    <i class="bi {{ $step['icon'] }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-label">{{ $step['label'] }}</div>
                                    @if($step['status'] === 'completed')
                                        <div class="timeline-status text-success">
                                            <i class="bi bi-check-lg"></i> Hoàn thành
                                        </div>
                                    @else
                                        <div class="timeline-status text-warning">
                                            <i class="bi bi-clock"></i> Đang chờ
                                        </div>
                                    @endif
                                </div>
                                @if($index < count($steps) - 1)
                                    <div class="timeline-connector {{ $steps[$index + 1]['status'] === 'completed' ? 'completed' : 'pending' }}"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tickets List -->
            <div class="tickets-card mb-4">
                <div class="tickets-header">
                    <div class="tickets-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <div>
                        <h5 class="tickets-title">Danh sách vé</h5>
                        <p class="tickets-subtitle">{{ $booking->tickets->count() }} vé được đặt</p>
                    </div>
                </div>
                
                <div class="tickets-body">
                    @if ($booking->tickets->isEmpty())
                        <div class="empty-tickets">
                            <i class="bi bi-ticket-perforated text-muted"></i>
                            <p class="text-muted mb-0">Không có vé nào trong đơn này</p>
                        </div>
                    @else
                        <div class="tickets-grid">
                            @foreach ($booking->tickets as $index => $ticket)
                                <div class="ticket-item">
                                    <div class="ticket-header">
                                        <div class="ticket-number">#{{ $index + 1 }}</div>
                                        @php
                                            $ticketStatusValue = is_object($ticket->status) ? $ticket->status->value : (string) $ticket->status;
                                        @endphp
                                        <span class="ticket-status status-{{ $ticketStatusValue }}">
                                            {{ ucfirst($ticketStatusValue) }}
                                        </span>
                                    </div>
                                    <div class="ticket-content">
                                        <div class="ticket-info">
                                            <div class="ticket-code">{{ $ticket->ticket_code }}</div>
                                            <div class="movie-name">{{ optional($ticket->showtime->movie)->name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="showtime-info">
                                            <div class="showtime-date">
                                                <i class="bi bi-calendar3"></i>
                                                {{ optional($ticket->showtime)->start_time ? $ticket->showtime->start_time->format('d/m/Y H:i') : 'N/A' }}
                                            </div>
                                            @if (optional($ticket->showtime)->room)
                                                <div class="room-info">
                                                    <i class="bi bi-door-open"></i>
                                                    Phòng: {{ $ticket->showtime->room->name }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="seat-price">
                                            <div class="seat-info">
                                                <i class="bi bi-geo-alt"></i>
                                                Ghế: {{ $ticket->seat ? $ticket->seat->row_char . $ticket->seat->seat_number : 'N/A' }}
                                            </div>
                                            <div class="price-info">
                                                {{ number_format($ticket->price_at_purchase, 0, ',', '.') }} đ
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Products List -->
            <div class="products-card">
                <div class="products-header">
                    <div class="products-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div>
                        <h5 class="products-title">Sản phẩm đã đặt</h5>
                        <p class="products-subtitle">{{ $booking->bookingItems->count() }} sản phẩm</p>
                    </div>
                </div>
                
                <div class="products-body">
                    @if ($booking->bookingItems->isEmpty())
                        <div class="empty-products">
                            <i class="bi bi-bag text-muted"></i>
                            <p class="text-muted mb-0">Không có sản phẩm nào trong đơn này</p>
                        </div>
                    @else
                        <div class="products-grid">
                            @foreach ($booking->bookingItems as $index => $item)
                                <div class="product-item">
                                    <div class="product-image">
                                        @if ($item->productVariant->product->image_url)
                                            <img src="{{ asset($item->productVariant->product->image_url) }}" alt="Ảnh sản phẩm">
                                        @else
                                            <div class="product-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-content">
                                        <div class="product-name">{{ $item->productVariant->product->name ?? 'N/A' }}</div>
                                        <div class="product-variant">SKU: {{ $item->productVariant->sku ?? 'N/A' }}</div>
                                        <div class="product-quantity">Số lượng: {{ $item->quantity }}</div>
                                        <div class="product-price">{{ number_format($item->price_at_purchase, 0, ',', '.') }} đ</div>
                                        @php
                                            $type = $item->productVariant->product->product_type;
                                            $typeStr = is_object($type) ? $type->value : $type;
                                        @endphp
                                        <span class="product-type">{{ ucfirst($typeStr ?? 'Không rõ') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Payment Summary -->
            @php
                $payment = $booking->payments->first();
            @endphp
            @if ($payment)
                <div class="payment-card mb-4">
                    <div class="payment-header">
                        <h5 class="payment-title">
                            <i class="bi bi-credit-card me-2"></i>
                            Tóm tắt thanh toán
                        </h5>
                    </div>
                    
                    <div class="payment-body">
                        <div class="payment-details">
                            <div class="payment-row">
                                <div class="payment-label">
                                    <i class="bi bi-hash"></i>
                                    <span>Mã giao dịch</span>
                                </div>
                                <div class="payment-value">{{ $payment->transaction_id_gateway ?? '---' }}</div>
                            </div>
                            
                            <div class="payment-row">
                                <div class="payment-label">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Trạng thái</span>
                                </div>
                                @php
                                    $statusValue = is_object($payment->status) ? $payment->status->value : (string) $payment->status;
                                @endphp
                                <div class="payment-value">
                                    <span class="payment-status status-{{ $statusValue }}">
                                        {{ ucfirst($statusValue) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="payment-row">
                                <div class="payment-label">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>Thời gian thanh toán</span>
                                </div>
                                <div class="payment-value">
                                    {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'Chưa thanh toán' }}
                                </div>
                            </div>
                            
                            @if ($payment->payment_details)
                                <div class="payment-row">
                                    <div class="payment-label">
                                        <i class="bi bi-file-text"></i>
                                        <span>Chi tiết</span>
                                    </div>
                                    <div class="payment-value">
                                        <button class="btn btn-outline-primary btn-sm rounded-pill" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#paymentDetailModal{{ $payment->id }}">
                                            <i class="bi bi-eye me-1"></i>Xem
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="payment-footer">
                        <div class="payment-total">
                            <div class="total-amount">
                                <span class="total-label">Tổng tiền</span>
                                <span class="total-value">{{ number_format($payment->amount, 0, ',', '.') }} đ</span>
                            </div>
                            <div class="payment-method">
                                <span class="method-label">Phương thức</span>
                                <span class="method-value">{{ $payment->paymentMethod->name ?? 'Không rõ' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Details Modal -->
                @if ($payment->payment_details)
                    <div class="modal fade" id="paymentDetailModal{{ $payment->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content rounded-4">
                                <div class="modal-header border-0 bg-light">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-file-text me-2 text-blue"></i>
                                        Chi tiết thanh toán
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <pre class="payment-details-json">{{ json_encode(json_decode($payment->payment_details), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                                <div class="modal-footer border-0 bg-light">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-2"></i>Đóng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-warning rounded-4">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Chưa có thông tin thanh toán nào cho đơn hàng này.
                </div>
            @endif

            <!-- Promotion Info -->
            @if ($booking->promotion)
                <div class="promotion-card">
                    <div class="promotion-header">
                        <h5 class="promotion-title">
                            <i class="bi bi-gift me-2"></i>
                            Khuyến mãi áp dụng
                        </h5>
                    </div>
                    
                    <div class="promotion-body">
                        <div class="promotion-details">
                            <div class="promotion-name">{{ $booking->promotion->name }}</div>
                            @if($booking->promotion->code)
                                <div class="promotion-code">Mã: {{ $booking->promotion->code }}</div>
                            @endif
                            <div class="promotion-description">{!! nl2br(e($booking->promotion->description)) !!}</div>
                            
                            <div class="promotion-info">
                                <div class="promotion-type">
                                    <span class="type-label">Loại giảm giá:</span>
                                    <span class="type-value">
                                        {{ $booking->promotion->discount_type == 'percentage' ? 'Phần trăm' : 'Giá cố định' }}
                                    </span>
                                </div>
                                
                                <div class="promotion-value">
                                    <span class="value-label">Giá trị giảm:</span>
                                    <span class="value-amount">
                                        {{ number_format($booking->promotion->discount_value, 2, ',', '.') }}
                                        {{ $booking->promotion->discount_type == 'percentage' ? '%' : 'đ' }}
                                    </span>
                                </div>
                                
                                @if ($booking->promotion->max_discount_amount)
                                    <div class="promotion-max">
                                        <span class="max-label">Giảm tối đa:</span>
                                        <span class="max-amount">{{ number_format($booking->promotion->max_discount_amount, 2, ',', '.') }} đ</span>
                                    </div>
                                @endif
                                
                                <div class="promotion-period">
                                    <span class="period-label">Thời gian:</span>
                                    <span class="period-value">
                                        {{ \Carbon\Carbon::parse($booking->promotion->start_date)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($booking->promotion->end_date)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
:root {
    --blue-primary: #2563eb;
    --blue-secondary: #3b82f6;
    --blue-light: #60a5fa;
    --blue-dark: #1d4ed8;
}

.text-blue {
    color: var(--blue-primary) !important;
}

/* Header */
.detail-header {
    background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
    position: relative;
    overflow: hidden;
}

.detail-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}

/* Summary Card */
.summary-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.summary-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.summary-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.summary-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.booking-badges {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid;
}

.status-paid {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border-color: rgba(34, 197, 94, 0.2);
}

.status-unpaid {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.2);
}

.status-pending {
    background: rgba(251, 191, 36, 0.1);
    color: #fbbf24;
    border-color: rgba(251, 191, 36, 0.2);
}

.status-confirmed, .status-completed {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border-color: rgba(34, 197, 94, 0.2);
}

.summary-body {
    padding: 2rem;
}

.info-item {
    margin-bottom: 1.5rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.info-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
    margin-left: 1.5rem;
}

/* Progress Card */
.progress-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.progress-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

.progress-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0;
}

.progress-body {
    padding: 2rem;
}

.timeline-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 1rem;
}

.timeline-step {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 120px;
    flex-shrink: 0;
}

.timeline-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.timeline-step.completed .timeline-icon {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.timeline-step.pending .timeline-icon {
    background: #f3f4f6;
    color: #9ca3af;
    border: 2px solid #e5e7eb;
}

.timeline-content {
    text-align: center;
}

.timeline-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
}

.timeline-status {
    font-size: 0.75rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

.timeline-connector {
    position: absolute;
    top: 1.75rem;
    left: calc(100% - 0.5rem);
    width: 2rem;
    height: 2px;
    z-index: 1;
}

.timeline-connector.completed {
    background: linear-gradient(90deg, #22c55e, #16a34a);
}

.timeline-connector.pending {
    background: #e5e7eb;
}

/* Tickets Card */
.tickets-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.tickets-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.tickets-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.tickets-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.tickets-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

.tickets-body {
    padding: 2rem;
}

.tickets-grid {
    display: grid;
    gap: 1rem;
}

.ticket-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.ticket-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: var(--blue-light);
}

.ticket-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.ticket-number {
    background: var(--blue-primary);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.ticket-status {
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.ticket-content {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.ticket-code {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
}

.movie-name {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
}

.showtime-info, .seat-price {
    display: flex;
    justify-content: between;
    align-items: center;
    font-size: 0.875rem;
}

.showtime-date, .room-info, .seat-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
}

.price-info {
    font-weight: 700;
    color: var(--blue-primary);
}

.empty-tickets {
    text-align: center;
    padding: 3rem 2rem;
    color: #9ca3af;
}

.empty-tickets i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

/* Products Card */
.products-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.products-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.products-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.products-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.products-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

.products-body {
    padding: 2rem;
}

.products-grid {
    display: grid;
    gap: 1rem;
}

.product-item {
    display: flex;
    gap: 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 1rem;
    transition: all 0.3s ease;
}

.product-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: var(--blue-light);
}

.product-image {
    width: 4rem;
    height: 4rem;
    border-radius: 0.75rem;
    overflow: hidden;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-placeholder {
    width: 100%;
    height: 100%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 1.5rem;
}

.product-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.product-name {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
}

.product-variant, .product-quantity {
    font-size: 0.875rem;
    color: #6b7280;
}

.product-price {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--blue-primary);
}

.product-type {
    padding: 0.25rem 0.5rem;
    background: var(--blue-light);
    color: white;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    align-self: flex-start;
    margin-top: 0.5rem;
}

.empty-products {
    text-align: center;
    padding: 3rem 2rem;
    color: #9ca3af;
}

.empty-products i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

/* Payment Card */
.payment-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.payment-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

.payment-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0;
}

.payment-body {
    padding: 1.5rem;
}

.payment-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-row {
    display: flex;
    justify-content: between;
    align-items: center;
}

.payment-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.payment-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

.payment-status {
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.payment-footer {
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
}

.payment-total {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.total-amount, .payment-method {
    display: flex;
    justify-content: between;
    align-items: center;
}

.total-label, .method-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.total-value {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--blue-primary);
}

.method-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

.payment-details-json {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem;
    font-size: 0.875rem;
    color: #374151;
    white-space: pre-wrap;
    max-height: 400px;
    overflow-y: auto;
}

/* Promotion Card */
.promotion-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.promotion-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-bottom: 1px solid #f59e0b;
}

.promotion-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 0;
}

.promotion-body {
    padding: 1.5rem;
}

.promotion-name {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.promotion-code {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    color: #f59e0b;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.promotion-description {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.promotion-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.promotion-type, .promotion-value, .promotion-max, .promotion-period {
    display: flex;
    justify-content: between;
    align-items: center;
    font-size: 0.875rem;
}

.type-label, .value-label, .max-label, .period-label {
    color: #6b7280;
    font-weight: 500;
}

.type-value, .value-amount, .max-amount, .period-value {
    font-weight: 600;
    color: #1f2937;
}

/* Responsive */
@media (max-width: 768px) {
    .detail-header {
        padding: 2rem !important;
    }
    
    .header-content h1 {
        font-size: 1.75rem;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
        gap: 0.75rem;
    }
    
    .summary-header, .tickets-header, .products-header {
        padding: 1rem 1.5rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .summary-body, .tickets-body, .products-body {
        padding: 1.5rem;
    }
    
    .timeline-wrapper {
        flex-direction: column;
        align-items: stretch;
        gap: 1.5rem;
    }
    
    .timeline-step {
        flex-direction: row;
        align-items: center;
        min-width: auto;
    }
    
    .timeline-connector {
        display: none;
    }
    
    .booking-badges {
        justify-content: center;
    }
    
    .payment-row, .total-amount, .payment-method {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>
@endsection