@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-warning rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="fas fa-ticket-alt fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chi tiết vé</h2>
                                <p class="mb-0 opacity-90 fs-5">Thông tin chi tiết vé #{{ $ticket->id }}</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-warning rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="fas fa-info-circle me-2"></i>
                                Chi tiết
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="fas fa-ticket-alt" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Ticket Info Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-ticket-alt text-warning"></i>
                        Thông tin vé
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <div class="avatar-xl rounded-circle bg-warning text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="font-size:2.5rem;">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <!-- Status Badge -->
                        <div class="position-absolute top-3 end-3">
                            @php
                                $status = $ticket->status instanceof \App\Enums\TicketStatus ? $ticket->status->value : $ticket->status;
                                $statusClass = $status === 'valid' ? 'bg-success' : ($status === 'used' ? 'bg-info' : ($status === 'cancelled' ? 'bg-danger' : 'bg-secondary'));
                                $statusText = $status === 'valid' ? 'Chưa sử dụng' : ($status === 'used' ? 'Đã sử dụng' : ($status === 'cancelled' ? 'Đã hủy' : 'Không xác định'));
                                $statusIcon = $status === 'valid' ? 'fa-check-circle' : ($status === 'used' ? 'fa-calendar-check' : ($status === 'cancelled' ? 'fa-times-circle' : 'fa-question-circle'));
                            @endphp
                            <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 shadow">
                                <i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}
                            </span>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Mã vé: {{ $ticket->ticket_code ?? '-' }}</h4>
                    <div class="row g-3 text-start">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-primary me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-film"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Suất chiếu</small>
                                    <div class="fw-semibold">{{ $ticket->showtime_id ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-success me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-chair"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ghế</small>
                                    <div class="fw-semibold">{{ $ticket->seat_id ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-info me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-shopping-cart"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Đơn hàng</small>
                                    <div class="fw-semibold">{{ $ticket->booking_id ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-warning me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày đặt</small>
                                    <div class="fw-semibold">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-secondary me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày cập nhật</small>
                                    <div class="fw-semibold">{{ $ticket->updated_at ? $ticket->updated_at->format('d/m/Y H:i') : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <a href="{{ route('admin.tickets.print', $ticket->ticket_code) }}" target="_blank" class="btn btn-info btn-lg rounded-pill">
                            <i class="fas fa-print me-2"></i>In vé
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Showtime & Booking Info -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-primary-subtle border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-film text-primary"></i>
                        Thông tin suất chiếu
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-film"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="fw-semibold">ID Suất chiếu: {{ $ticket->showtime_id ?? '-' }}</div>
                                    @if($ticket->showtime)
                                        <div class="text-muted small">Thời gian chiếu: <i class="fas fa-clock"></i> {{ $ticket->showtime->start_time ? $ticket->showtime->start_time->format('d/m/Y H:i') : '-' }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-chair"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="fw-semibold">Ghế: {{ $ticket->seat_id ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        @if($ticket->showtime)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar-sm rounded-circle bg-warning">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-film"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="fw-semibold">Phim: {{ $ticket->showtime->movie->title ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-shopping-cart"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="fw-semibold">Đơn hàng: {{ $ticket->booking_id ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-warning-subtle border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-clock text-warning"></i>
                        Lịch sử vé
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Tạo vé</h6>
                                <p class="mb-0 text-muted">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>
                        @if($ticket->updated_at && $ticket->updated_at != $ticket->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Cập nhật lần cuối</h6>
                                <p class="mb-0 text-muted">{{ $ticket->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($ticket->status == 'used' && $ticket->showtime)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Đã sử dụng</h6>
                                <p class="mb-0 text-muted">Suất chiếu: {{ $ticket->showtime->start_time ? $ticket->showtime->start_time->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #FF6F00 0%, #00ACC1 100%);
}
.avatar-xl {
    width: 5rem;
    height: 5rem;
}
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
.timeline {
    position: relative;
    padding-left: 2rem;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #e9ecef, #dee2e6);
}
.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}
.timeline-marker {
    position: absolute;
    left: -2.25rem;
    top: 0.25rem;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.timeline-content {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border-left: 4px solid #dee2e6;
}
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
}
.card {
    transition: all 0.3s ease;
    border: none;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.sticky-top {
    top: 1rem;
}
.bg-primary-subtle {
    background-color: rgba(33, 150, 243, 0.1) !important;
}
.bg-warning-subtle {
    background-color: rgba(255, 111, 0, 0.1) !important;
}
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    .card-body {
        padding: 1.5rem !important;
    }
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
    .timeline {
        padding-left: 1.5rem;
    }
    .timeline::before {
        left: 0.5rem;
    }
    .timeline-marker {
        left: -1.75rem;
        width: 1rem;
        height: 1rem;
    }
}
</style>
@endsection