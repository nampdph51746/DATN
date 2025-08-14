@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Alert Messages -->
    @include('admin.partials.notifications')

    <!-- Hero Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="hero-section position-relative overflow-hidden rounded-4 p-5">
                <div class="hero-bg"></div>
                <div class="hero-content position-relative z-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="hero-text">
                            <h1 class="display-5 fw-bold text-white mb-2">
                                <i class="bi bi-ticket-perforated-fill me-3"></i> Quản lý đặt vé
                            </h1>
                            <p class="lead text-white-75 mb-0">Quản lý tập trung và tối ưu hóa đơn đặt vé</p>
                        </div>
                        <div class="hero-actions d-flex gap-3 flex-wrap">
                            <button class="btn btn-outline-light rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="bi bi-funnel-fill me-2"></i> Bộ lọc nâng cao
                            </button>
                            <button class="btn btn-blue rounded-pill px-4 shadow-sm">
                                <i class="bi bi-download me-2"></i> Xuất báo cáo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="hero-pattern"></div>
            </div>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="row mb-5 g-4">
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-blue">
                        <i class="bi bi-ticket-perforated-fill fs-2"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $bookings->total() }}</h3>
                        <p class="stats-label">Tổng đơn đặt</p>
                    </div>
                </div>
                <div class="stats-overlay"></div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-check-circle-fill fs-2"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $bookings->where('payment_status', 'paid')->count() }}</h3>
                        <p class="stats-label">Đã thanh toán</p>
                    </div>
                </div>
                <div class="stats-overlay"></div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-clock-fill fs-2"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $bookings->where('payment_status', 'pending')->count() }}</h3>
                        <p class="stats-label">Chờ thanh toán</p>
                        <div class="stats-trend">
                        </div>
                    </div>
                </div>
                <div class="stats-overlay"></div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-danger">
                        <i class="bi bi-x-circle-fill fs-2"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $bookings->where('payment_status', 'failed')->count() }}</h3>
                        <p class="stats-label">Thất bại</p>
                        <div class="stats-trend">
                        </div>
                    </div>
                </div>
                <div class="stats-overlay"></div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="filter-section rounded-4 p-4 shadow-lg">
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="filter-form">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-4 col-md-6">
                            <div class="search-input-group">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="search-input"
                                       placeholder="Tìm theo mã đặt, email, số điện thoại...">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="filter-group">
                                <label class="filter-label">
                                    <i class="bi bi-funnel-fill me-2"></i> Trạng thái thanh toán
                                </label>
                                <select name="payment_status" class="filter-select">
                                    <option value="">-- Tất cả --</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="filter-group">
                                <label class="filter-label">
                                    <i class="bi bi-calendar-date me-2"></i> Ngày đặt
                                </label>
                                <input type="date" name="date" value="{{ request('date') }}" class="filter-select">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-blue rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-search me-2"></i> Tìm
                                </button>
                                @if(request()->anyFilled(['search', 'payment_status', 'date']))
                                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bookings Grid -->
    <div class="row g-4">
        @forelse($bookings as $booking)
            <div class="col-xl-6 col-lg-12">
                <div class="booking-card h-100">
                    <div class="booking-card-header">
                        <div class="booking-info-main">
                            <div class="booking-code">
                                <i class="bi bi-qr-code-scan text-blue"></i>
                                <span class="code-text">#{{ $booking->booking_code }}</span>
                            </div>
                            <div class="booking-status">
                                @if($booking->payment_status == 'paid')
                                    <span class="status-badge status-paid">
                                        <i class="bi bi-check-circle-fill me-1"></i> Đã thanh toán
                                    </span>
                                @elseif($booking->payment_status == 'pending')
                                    <span class="status-badge status-pending">
                                        <i class="bi bi-clock-fill me-1"></i> Chờ thanh toán
                                    </span>
                                @else
                                    <span class="status-badge status-failed">
                                        <i class="bi bi-x-circle-fill me-1"></i> Thất bại
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="booking-amount">
                            <span class="amount-label">Tổng tiền</span>
                            <span class="amount-value">{{ number_format($booking->total_amount) }}đ</span>
                        </div>
                    </div>
                    
                    <div class="booking-card-body">
                        <div class="customer-info">
                            <div class="customer-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="customer-details">
                                <h6 class="customer-name">{{ $booking->user_name ?? 'Khách hàng' }}</h6>
                                <div class="customer-contact">
                                    @if($booking->user_email)
                                        <span class="contact-item">
                                            <i class="bi bi-envelope-fill text-muted"></i>
                                            {{ $booking->user_email }}
                                        </span>
                                    @endif
                                    @if($booking->user_phone)
                                        <span class="contact-item">
                                            <i class="bi bi-telephone-fill text-muted"></i>
                                            {{ $booking->user_phone }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="movie-info">
                            <div class="movie-poster">
                                @if(isset($booking->showtime->movie->poster))
                                    <img src="{{ asset($booking->showtime->movie->poster) }}" alt="Movie poster">
                                @else
                                    <div class="poster-placeholder">
                                        <i class="bi bi-film fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="movie-details">
                                <h6 class="movie-title">{{ $booking->showtime->movie->title ?? 'Phim không xác định' }}</h6>
                                <div class="showtime-info">
                                    <div class="info-item">
                                        <i class="bi bi-calendar-date text-blue"></i>
                                        <span>{{ $booking->showtime->start_time ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="bi bi-geo-alt-fill text-orange"></i>
                                        <span>{{ $booking->showtime->room->cinema->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="bi bi-door-open-fill text-success"></i>
                                        <span>{{ $booking->showtime->room->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tickets-summary">
                            <div class="tickets-count">
                                <i class="bi bi-ticket-perforated-fill text-purple"></i>
                                <span>{{ $booking->tickets->count() }} vé</span>
                            </div>
                            <div class="seats-list">
                                <span class="seats-label">Ghế:</span>
                                <span class="seats-numbers">
                                    {{ $booking->tickets->pluck('seat_number')->implode(', ') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="booking-card-footer">
                        <div class="booking-meta">
                            <small class="text-muted">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $booking->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                               class="btn btn-outline-blue btn-sm rounded-pill shadow-sm" 
                               title="Xem chi tiết" data-bs-toggle="tooltip">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" 
                               class="btn btn-outline-warning btn-sm rounded-pill shadow-sm" 
                               title="Chỉnh sửa" data-bs-toggle="tooltip">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="{{ route('admin.bookings.print', $booking->id) }}" 
                               class="btn btn-outline-success btn-sm rounded-pill shadow-sm" 
                               title="In vé" data-bs-toggle="tooltip" target="_blank">
                                <i class="bi bi-printer-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state text-center py-5 rounded-4 shadow-lg">
                    <div class="empty-icon">
                        <i class="bi bi-ticket-perforated-fill fs-1 text-blue"></i>
                    </div>
                    <h4 class="empty-title">Chưa có đơn đặt vé nào</h4>
                    <p class="empty-description">Các đơn đặt vé sẽ hiển thị tại đây khi có khách hàng thực hiện đặt vé</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="pagination-wrapper rounded-4 p-4 shadow-lg">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="pagination-info">
                            <span class="text-muted">
                                Hiển thị {{ $bookings->firstItem() }}-{{ $bookings->lastItem() }} 
                                trong tổng {{ $bookings->total() }} đơn đặt vé
                            </span>
                        </div>
                        <div class="pagination-links">
                            {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
:root {
    --blue-primary: #3B82F6;
    --blue-secondary: #60A5FA;
    --blue-light: #93C5FD;
    --blue-dark: #1D4ED8;
    --blue-subtle: rgba(59, 130, 246, 0.1);
    --purple: #6B46C1;
    --orange: #F59E0B;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
    min-height: 180px;
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="12" height="12" patternUnits="userSpaceOnUse"><path d="M 12 0 L 0 0 0 12" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.9;
}

.hero-pattern {
    position: absolute;
    top: -30%;
    right: -15%;
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.z-2 {
    z-index: 2;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

/* Stats Cards */
.stats-card {
    background: linear-gradient(145deg, #ffffff, #f9fafb);
    border-radius: 1rem;
    border: 1px solid var(--blue-subtle);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(59, 130, 246, 0.15);
}

.stats-card-body {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 2;
}

.stats-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.stats-icon.bg-blue {
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
}

.stats-number {
    font-size: 2.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.25rem;
}

.stats-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.stats-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
}

.stats-overlay {
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--blue-subtle), transparent);
    border-radius: 50%;
    transform: translate(20px, -20px);
}

/* Filter Section */
.filter-section {
    background: linear-gradient(145deg, #ffffff, #f9fafb);
    border: 1px solid var(--blue-subtle);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.1);
}

.search-input-group {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--blue-primary);
    font-size: 1rem;
}

.search-input {
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 2px solid var(--blue-subtle);
    border-radius: 2rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    border-color: var(--blue-primary);
    box-shadow: 0 0 0 3px var(--blue-subtle);
    outline: none;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid var(--blue-subtle);
    border-radius: 1rem;
    font-size: 0.95rem;
    background: white;
    transition: all 0.3s ease;
}

.filter-select:focus {
    border-color: var(--blue-primary);
    box-shadow: 0 0 0 3px var(--blue-subtle);
    outline: none;
}

.btn-blue {
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-blue:hover {
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-primary));
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    color: white;
}

/* Booking Cards */
.booking-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--blue-subtle);
    transition: all 0.3s ease;
    overflow: hidden;
}

.booking-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(59, 130, 246, 0.15);
}

.booking-card-header {
    background: linear-gradient(145deg, #f8fafc, #f1f5f9);
    padding: 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.booking-info-main {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.booking-code {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: #111827;
    font-size: 1.1rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 1.5rem;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(8px);
}

.status-paid {
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-failed {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.booking-amount {
    text-align: right;
}

.amount-label {
    display: block;
    font-size: 0.85rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.amount-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--blue-primary);
}

.booking-card-body {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.customer-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-light), var(--blue-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.customer-name {
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
}

.customer-contact {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6b7280;
}

.movie-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.movie-poster {
    width: 3.5rem;
    height: 5rem;
    border-radius: 0.5rem;
    overflow: hidden;
    flex-shrink: 0;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.booking-card:hover .movie-poster img {
    transform: scale(1.1);
}

.poster-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e5e7eb, #d1d5db);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.movie-title {
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
}

.showtime-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6b7280;
}

.tickets-summary {
    background: var(--blue-subtle);
    border-radius: 0.75rem;
    padding: 0.75rem;
    border: 1px solid var(--blue-subtle);
}

.tickets-count {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.seats-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.seats-label {
    color: #6b7280;
    font-weight: 500;
}

.seats-numbers {
    color: #111827;
    font-weight: 600;
}

.booking-card-footer {
    padding: 1.25rem;
    border-top: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-outline-blue {
    border-color: var(--blue-primary);
    color: var(--blue-primary);
}

.btn-outline-blue:hover {
    background: var(--blue-primary);
    border-color: var(--blue-primary);
    color: white;
}

/* Empty State */
.empty-state {
    background: linear-gradient(145deg, #ffffff, #f9fafb);
    border: 1px solid var(--blue-subtle);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.1);
}

.empty-icon {
    font-size: 3.5rem;
    color: var(--blue-primary);
    margin-bottom: 1rem;
}

.empty-title {
    color: #111827;
    margin-bottom: 0.75rem;
}

.empty-description {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

/* Pagination */
.pagination-wrapper {
    background: linear-gradient(145deg, #ffffff, #f9fafb);
    border: 1px solid var(--blue-subtle);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.1);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.stats-card, .booking-card, .filter-section, .pagination-wrapper, .empty-state {
    animation: fadeIn 0.5s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        padding: 1.5rem;
    }
    
    .hero-text h1 {
        font-size: 1.75rem;
    }
    
    .hero-actions {
        flex-direction: column;
        width: 100%;
        gap: 1rem;
    }
    
    .stats-card-body {
        padding: 1.25rem;
        flex-direction: column;
        text-align: center;
    }
    
    .filter-section {
        padding: 1.25rem;
    }
    
    .booking-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .movie-info {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl, {
            placement: 'top',
            trigger: 'hover'
        });
    });
});
</script>
@endsection