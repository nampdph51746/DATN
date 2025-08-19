@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title text-muted mb-2 fs-14 text-uppercase fw-semibold">Chờ xác nhận</h5>
                            <h3 class="mb-0 fw-bold text-dark">{{ $bookings->where('status', 'pending')->count() }}</h3>
                            <small class="text-muted">đơn đặt vé</small>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-lg bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:clock-circle-bold" class="fs-28 text-warning"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title text-muted mb-2 fs-14 text-uppercase fw-semibold">Đã xác nhận</h5>
                            <h3 class="mb-0 fw-bold text-dark">{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                            <small class="text-muted">đơn đặt vé</small>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-lg bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:clipboard-check-bold" class="fs-28 text-success"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title text-muted mb-2 fs-14 text-uppercase fw-semibold">Đã hủy</h5>
                            <h3 class="mb-0 fw-bold text-dark">{{ $bookings->where('status', 'cancelled')->count() }}</h3>
                            <small class="text-muted">đơn đặt vé</small>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-lg bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:cart-cross-bold" class="fs-28 text-danger"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title text-muted mb-2 fs-14 text-uppercase fw-semibold">Tổng doanh thu</h5>
                            <h3 class="mb-0 fw-bold text-primary">
                                {{ number_format($bookings->where('status', 'confirmed')->sum('final_amount') / 1000000, 1) }}M
                            </h3>
                            <small class="text-muted">VNĐ</small>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:dollar-minimalistic-bold" class="fs-28 text-primary"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: #ff6633; border-bottom: 1px solid #e55a2b;">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <h4 class="card-title mb-0 fw-semibold" style="color: #333333; font-weight: 600;">🎫 Danh sách đơn đặt vé</h4>
                            <small class="text-muted" style="color: #666666;">Quản lý tất cả đơn đặt vé trong hệ thống</small>
                        </div>
                        <div class="col-md-8">
                            <form method="GET" class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <div class="position-relative">
                                    <input type="search" name="search" class="form-control form-control-sm ps-4 pe-3" 
                                           style="min-width: 280px;" placeholder="Tìm mã đặt vé, tên khách hàng..." 
                                           autocomplete="off" value="{{ request('search') }}">
                                    <iconify-icon icon="solar:magnifer-linear"
                                        class="position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"
                                        style="font-size: 14px;"></iconify-icon>
                                </div>
                                
                                <select name="status" class="form-select form-select-sm" style="width:140px;">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                                
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3" style="width: 80px;">
                                        <span class="text-muted fw-semibold fs-12 text-uppercase">ID</span>
                                    </th>
                                    <th class="px-4 py-3" style="width: 180px;">
                                        <span class="text-muted fw-semibold fs-12 text-uppercase">Mã đặt vé</span>
                                    </th>
                                    <th class="px-4 py-3" style="width: 200px;">
                                        <span class="text-muted fw-semibold fs-12 text-uppercase">Khách hàng</span>
                                    </th>
                                    <th class="px-4 py-3" style="width: 200px;">
                                        <span class="text-muted fw-semibold fs-12 text-uppercase">Phim & Suất chiếu</span>
                                    </th>
                                    <th class="px-4 py-3" style="width: 120px;">
                                        <span class="text-muted fw-semibold fs-12 text-uppercase">Số vé</span>
                                    </th>
                                    <th class="px-4 py-3" style="width: 140px;">
                                        <span class="text-muted fw-semibold fs-12 text-uppercase">Tổng tiền</span>
                                    </th>
                                    <th class="px-4 py-3" style="width: 120px;">
                                        <span class="text-muted fw-semibold fs-12 text-uppercase">Trạng thái</span>
                                    </th>
                                    <th class="px-4 py-3 text-center" style="width: 140px;">
                                        <span class="text-muted fw-semibold fs-12 text-uppercase">Hành động</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                    @php
                                        $firstTicket = $booking->tickets->first();
                                        $showtime = $firstTicket ? $firstTicket->showtime : null;
                                        $movie = $showtime ? $showtime->movie : null;
                                    @endphp
                                    <tr class="border-bottom">
                                        <td class="px-4 py-3">
                                            <span class="text-muted fw-medium">#{{ $booking->id }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:ticket-bold" class="fs-20 text-primary"></iconify-icon>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $booking->booking_code }}</h6>
                                                    <small class="text-muted">{{ $booking->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <h6 class="mb-1 fw-medium">{{ $booking->user->name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $booking->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($movie && $showtime)
                                                <div>
                                                    <h6 class="mb-1 fw-medium text-truncate" style="max-width: 180px;" title="{{ $movie->name }}">
                                                        {{ $movie->name }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ $showtime->start_time->format('d/m H:i') }} - {{ $showtime->room->cinema->name }}
                                                    </small>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-center">
                                                <span class="badge bg-info-subtle text-info fs-13 px-3 py-2">
                                                    {{ $booking->tickets->count() }} vé
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <h6 class="mb-0 fw-bold text-success">
                                                    {{ number_format($booking->final_amount, 0, ',', '.') }}đ
                                                </h6>
                                                @if($booking->discount_amount > 0)
                                                    <small class="text-muted">
                                                        Giảm: {{ number_format($booking->discount_amount, 0, ',', '.') }}đ
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @switch($booking->status?->value)
                                                @case('pending')
                                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 fs-12 fw-medium">
                                                        <i class="bi bi-clock me-1"></i>Chờ xác nhận
                                                    </span>
                                                @break
                                                @case('confirmed')
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 fs-12 fw-medium">
                                                        <i class="bi bi-check-circle me-1"></i>Đã xác nhận
                                                    </span>
                                                @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 fs-12 fw-medium">
                                                        <i class="bi bi-x-circle me-1"></i>Đã hủy
                                                    </span>
                                                @break
                                                @default
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 fs-12 fw-medium">
                                                        Không xác định
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                                    class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-bold" class="fs-16"></iconify-icon>
                                                </a>
                                                @php
                                                    $showtimeStart = $firstTicket ? $firstTicket->showtime->start_time : null;
                                                @endphp
                                                <a href="{{ route('admin.bookings.print', $booking->booking_code) }}" target="_blank"
                                                    class="btn btn-primary btn-sm print-ticket-btn"
                                                    data-showtime-start="{{ $showtimeStart ? $showtimeStart->toISOString() : '' }}"
                                                    data-booking-code="{{ $booking->booking_code }}"
                                                    title="In vé">
                                                    <iconify-icon icon="solar:printer-bold" class="fs-16"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($bookings->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <iconify-icon icon="solar:ticket-broken" class="fs-48 text-muted mb-3"></iconify-icon>
                                                <h6 class="text-muted">Không tìm thấy đơn đặt vé nào</h6>
                                                <small class="text-muted">Thử thay đổi bộ lọc hoặc tìm kiếm khác</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Hiển thị {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} trong {{ $bookings->total() }} kết quả
                        </div>
                        <div>
                            {{ $bookings->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Statistics Cards */
.stats-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.avatar-lg {
    width: 4rem;
    height: 4rem;
}

/* Enhanced table styling */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #6c757d;
    border-top: none;
    border-bottom: 1px solid #dee2e6;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #f1f3f4;
    border-bottom: none;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

/* Card enhancements */
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    background: #ffffff;
    border-bottom: 1px solid #f1f3f4;
    padding: 1.5rem;
}

/* Form controls */
.form-control-sm, .form-select-sm {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.form-control-sm:focus, .form-select-sm:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

/* Button enhancements */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.825rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

.btn-light {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #6c757d;
}

.btn-light:hover {
    background: #e9ecef;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-outline-secondary {
    border-color: #e9ecef;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
    transform: translateY(-1px);
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
}

/* Status badges - keeping original colors */
.bg-warning-subtle {
    background: rgba(255, 193, 7, 0.1) !important;
    color: #ff6b35 !important;
}

.bg-success-subtle {
    background: rgba(25, 135, 84, 0.1) !important;
    color: #198754 !important;
}

.bg-danger-subtle {
    background: rgba(220, 53, 69, 0.1) !important;
    color: #dc3545 !important;
}

.bg-info-subtle {
    background: rgba(13, 202, 240, 0.1) !important;
    color: #0dcaf0 !important;
}

.bg-secondary-subtle {
    background: rgba(108, 117, 125, 0.1) !important;
    color: #6c757d !important;
}

/* Alert styling */
.alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

/* Avatar styling */
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

/* Empty state */
.table tbody tr td iconify-icon {
    opacity: 0.3;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .stats-card .card-body {
        padding: 1.5rem 1rem;
    }
    
    .avatar-lg {
        width: 3rem;
        height: 3rem;
    }
    
    .table td, .table th {
        padding: 0.75rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.3rem 0.6rem;
    }
}

/* Loading state */
.table tbody tr.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Truncate text */
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const printButtons = document.querySelectorAll('.print-ticket-btn');
    
    // Function để xử lý click event cho nút bị vô hiệu hóa
    function handleDisabledButtonClick(e, message) {
        e.preventDefault();
        e.stopPropagation();
        Swal.fire({
            icon: 'warning',
            title: 'Không thể in vé',
            text: message,
            confirmButtonText: 'Đã hiểu',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }
    
    printButtons.forEach(button => {
        const showtimeStartStr = button.getAttribute('data-showtime-start');
        const bookingCode = button.getAttribute('data-booking-code');
        
        if (!showtimeStartStr) {
            // Nếu không có thời gian suất chiếu, vô hiệu hóa nút
            button.disabled = true;
            button.classList.remove('btn-primary');
            button.classList.add('btn-secondary');
            button.title = 'Không tìm thấy thông tin suất chiếu';
            button.removeAttribute('href');
            button.onclick = function(e) {
                return handleDisabledButtonClick(e, 'Không tìm thấy thông tin suất chiếu cho đơn đặt vé này.');
            };
            return;
        }
        
        const showtimeStart = new Date(showtimeStartStr);
        const currentTime = new Date();
        
        // Kiểm tra nếu suất chiếu đã bắt đầu
        // if (currentTime >= showtimeStart) {
        //     button.disabled = true;
        //     button.classList.remove('btn-primary');
        //     button.classList.add('btn-secondary');
        //     button.title = 'Không thể in vé sau khi suất chiếu đã bắt đầu. Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN');
        //     button.removeAttribute('href');
        //     button.onclick = function(e) {
        //         return handleDisabledButtonClick(e, 'Không thể in vé sau khi suất chiếu đã bắt đầu.\nSuất chiếu: ' + showtimeStart.toLocaleString('vi-VN'));
        //     };
        //     return;
        // }
        
        // Kiểm tra nếu còn NHIỀU HƠN 1 tiếng trước suất chiếu (không cho phép in)
        // const oneHourBeforeShowtime = new Date(showtimeStart.getTime() - (60 * 60 * 1000));
        // if (currentTime < oneHourBeforeShowtime) {
        //     button.disabled = true;
        //     button.classList.remove('btn-primary');
        //     button.classList.add('btn-warning');
        //     button.title = 'Vé chỉ có thể in trong vòng 1 tiếng trước suất chiếu. Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN');
        //     button.removeAttribute('href');
        //     button.onclick = function(e) {
        //         return handleDisabledButtonClick(e, 'Vé chỉ có thể in trong vòng 1 tiếng trước suất chiếu.\nSuất chiếu: ' + showtimeStart.toLocaleString('vi-VN'));
        //     };
        //     return;
        // }
        
        // Nếu trong khoảng thời gian cho phép in vé (từ 1 tiếng trước suất chiếu đến khi suất chiếu bắt đầu)
        button.title = 'In vé (Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN') + ')';
    });
    
    // Cập nhật trạng thái nút mỗi phút
    setInterval(() => {
        printButtons.forEach(button => {
            const showtimeStartStr = button.getAttribute('data-showtime-start');
            const originalHref = button.getAttribute('href') || button.dataset.originalHref;
            
            if (!showtimeStartStr) return;
            
            const showtimeStart = new Date(showtimeStartStr);
            const currentTime = new Date();
            
            // Lưu href gốc nếu chưa có
            if (!button.dataset.originalHref && button.getAttribute('href')) {
                button.dataset.originalHref = button.getAttribute('href');
            }
            
            // Kiểm tra nếu suất chiếu đã bắt đầu
            // if (currentTime >= showtimeStart) {
            //     button.disabled = true;
            //     button.classList.remove('btn-primary', 'btn-warning');
            //     button.classList.add('btn-secondary');
            //     button.title = 'Không thể in vé sau khi suất chiếu đã bắt đầu. Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN');
            //     button.removeAttribute('href');
            //     button.onclick = function(e) {
            //         return handleDisabledButtonClick(e, 'Không thể in vé sau khi suất chiếu đã bắt đầu.\nSuất chiếu: ' + showtimeStart.toLocaleString('vi-VN'));
            //     };
            //     return;
            // }
            
            // Kiểm tra nếu còn NHIỀU HƠN 1 tiếng trước suất chiếu (không cho phép in)
            // const oneHourBeforeShowtime = new Date(showtimeStart.getTime() - (60 * 60 * 1000));
            // if (currentTime < oneHourBeforeShowtime) {
            //     button.disabled = true;
            //     button.classList.remove('btn-primary', 'btn-secondary');
            //     button.classList.add('btn-warning');
            //     button.title = 'Vé chỉ có thể in trong vòng 1 tiếng trước suất chiếu. Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN');
            //     button.removeAttribute('href');
            //     button.onclick = function(e) {
            //         return handleDisabledButtonClick(e, 'Vé chỉ có thể in trong vòng 1 tiếng trước suất chiếu.\nSuất chiếu: ' + showtimeStart.toLocaleString('vi-VN'));
            //     };
            //     return;
            // }
            
            // Nếu trong khoảng thời gian cho phép in vé (từ 1 tiếng trước suất chiếu đến khi suất chiếu bắt đầu)
            // if (button.disabled) {
            //     button.disabled = false;
            //     button.classList.remove('btn-secondary', 'btn-warning');
            //     button.classList.add('btn-primary');
            //     button.title = 'In vé (Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN') + ')';
            //     // Khôi phục href gốc
            //     if (button.dataset.originalHref) {
            //         button.setAttribute('href', button.dataset.originalHref);
            //     }
            //     button.onclick = null; // Xóa event handler cũ
            // }
        });
    }, 60000); // Cập nhật mỗi phút
});
</script>
@endpush