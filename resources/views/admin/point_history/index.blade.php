@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Lịch sử giao dịch điểm
                        </h4>
                        <p class="text-muted mb-0">Theo dõi toàn bộ lịch sử cộng/trừ điểm của khách hàng</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel me-1"></i>
                            Bộ lọc
                        </button>
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="bi bi-download me-1"></i>
                            Xuất Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success-subtle">
                                    <span class="avatar-title rounded-circle bg-success text-white">
                                        <i class="bi bi-plus-circle fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Tổng điểm cộng</h6>
                                <h4 class="mb-0 fw-bold text-success">{{ $histories->where('points_change', '>', 0)->sum('points_change') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-danger-subtle">
                                    <span class="avatar-title rounded-circle bg-danger text-white">
                                        <i class="bi bi-dash-circle fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Tổng điểm trừ</h6>
                                <h4 class="mb-0 fw-bold text-danger">{{ abs($histories->where('points_change', '<', 0)->sum('points_change')) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary-subtle">
                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                        <i class="bi bi-receipt fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Tổng giao dịch</h6>
                                <h4 class="mb-0 fw-bold">{{ $histories->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info-subtle">
                                    <span class="avatar-title rounded-circle bg-info text-white">
                                        <i class="bi bi-people fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Khách hàng tham gia</h6>
                                <h4 class="mb-0 fw-bold text-info">{{ $histories->unique('user_id')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="card-title mb-0 fw-semibold">
                                    <i class="bi bi-list-ul me-2 text-primary"></i>
                                    Chi tiết lịch sử giao dịch
                                </h5>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex gap-2 align-items-center">
                                    <!-- Quick filter buttons -->
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.point_history.index') }}" 
                                           class="btn {{ !request()->hasAny(['reason_type']) ? 'btn-primary' : 'btn-outline-primary' }}">
                                            Tất cả
                                        </a>
                                        <a href="{{ route('admin.point_history.index', ['reason_type' => 'earned']) }}" 
                                           class="btn {{ request('reason_type') == 'earned' ? 'btn-success' : 'btn-outline-success' }}">
                                            Cộng điểm
                                        </a>
                                        <a href="{{ route('admin.point_history.index', ['reason_type' => 'spent']) }}" 
                                           class="btn {{ request('reason_type') == 'spent' ? 'btn-warning' : 'btn-outline-warning' }}">
                                            Tiêu điểm
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-person-circle me-1"></i>
                                            Khách hàng
                                        </th>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-receipt me-1"></i>
                                            Đơn hàng
                                        </th>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-coin me-1"></i>
                                            Điểm thay đổi
                                        </th>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-tag me-1"></i>
                                            Loại giao dịch
                                        </th>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-chat-left-text me-1"></i>
                                            Mô tả
                                        </th>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Thời gian
                                        </th>
                                        <th class="border-0 fw-semibold text-dark text-center">
                                            <i class="bi bi-gear me-1"></i>
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($histories as $history)
                                        <tr class="border-bottom">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <div class="avatar-title rounded-circle bg-primary text-white">
                                                            {{ substr($history->user->name ?? 'U', 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $history->user->name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">
                                                            <i class="bi bi-hash"></i>
                                                            {{ $history->user_id }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($history->booking_id)
                                                    <a href="{{ route('admin.bookings.show', $history->booking_id) }}"
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-receipt me-1"></i>
                                                        #{{ $history->booking_id }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge fs-6 {{ $history->points_change > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                                                    {{ $history->points_change > 0 ? '+' : '' }}{{ number_format($history->points_change) }}
                                                </span>
                                            </td>
                                            <td>
                                                @switch($history->reason_type)
                                                    @case('earned')
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-plus-circle me-1"></i>
                                                            Cộng điểm
                                                        </span>
                                                        @break
                                                    @case('spent')
                                                        <span class="badge bg-warning">
                                                            <i class="bi bi-dash-circle me-1"></i>
                                                            Tiêu điểm
                                                        </span>
                                                        @break
                                                    @case('expired')
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-clock me-1"></i>
                                                            Hết hạn
                                                        </span>
                                                        @break
                                                    @case('adjusted')
                                                        <span class="badge bg-info">
                                                            <i class="bi bi-pencil me-1"></i>
                                                            Điều chỉnh
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-light text-dark">{{ $history->reason_type }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ Str::limit($history->description ?? '-', 50) }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold">{{ $history->created_at ? $history->created_at->format('d/m/Y') : '-' }}</span>
                                                    <small class="text-muted">{{ $history->created_at ? $history->created_at->format('H:i') : '' }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.point_history.show', $history->id) }}"
                                                   class="btn btn-sm rounded-3 view-detail-btn" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-clock-history display-4 d-block mb-3"></i>
                                                    <h6>Không có dữ liệu</h6>
                                                    <p class="mb-0">Chưa có lịch sử giao dịch điểm nào</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($histories->hasPages())
                        <div class="card-footer bg-white border-top py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Hiển thị {{ $histories->firstItem() }}-{{ $histories->lastItem() }} trong tổng {{ $histories->total() }} kết quả
                                </div>
                                <div>
                                    {{ $histories->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="bi bi-funnel me-2"></i>
                        Bộ lọc nâng cao
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET" action="{{ route('admin.point_history.index') }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="user_id" class="form-label fw-semibold">ID Khách hàng</label>
                                <input type="text" name="user_id" id="user_id" 
                                       class="form-control" placeholder="Nhập ID khách hàng" 
                                       value="{{ request('user_id') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="booking_id" class="form-label fw-semibold">ID Đơn hàng</label>
                                <input type="text" name="booking_id" id="booking_id" 
                                       class="form-control" placeholder="Nhập ID đơn hàng" 
                                       value="{{ request('booking_id') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="reason_type" class="form-label fw-semibold">Loại giao dịch</label>
                                <select name="reason_type" id="reason_type" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="earned" {{ request('reason_type') == 'earned' ? 'selected' : '' }}>Cộng điểm</option>
                                    <option value="spent" {{ request('reason_type') == 'spent' ? 'selected' : '' }}>Tiêu điểm</option>
                                    <option value="expired" {{ request('reason_type') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                    <option value="adjusted" {{ request('reason_type') == 'adjusted' ? 'selected' : '' }}>Điều chỉnh</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="date_range" class="form-label fw-semibold">Khoảng thời gian</label>
                                <select name="date_range" id="date_range" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="today">Hôm nay</option>
                                    <option value="yesterday">Hôm qua</option>
                                    <option value="this_week">Tuần này</option>
                                    <option value="last_week">Tuần trước</option>
                                    <option value="this_month">Tháng này</option>
                                    <option value="last_month">Tháng trước</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Áp dụng lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
