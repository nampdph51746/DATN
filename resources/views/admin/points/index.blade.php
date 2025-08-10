@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="bi bi-coin text-warning me-2"></i>
                            Quản lý điểm thưởng
                        </h4>
                        <p class="text-muted mb-0">Theo dõi và quản lý điểm thưởng của khách hàng</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel me-1"></i>
                            Bộ lọc
                        </button>
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="bi bi-download me-1"></i>
                            Xuất báo cáo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary-subtle">
                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                        <i class="bi bi-people fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Tổng khách hàng</h6>
                                <h4 class="mb-0 fw-bold">{{ $points->total() }}</h4>
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
                                <div class="avatar-sm rounded-circle bg-success-subtle">
                                    <span class="avatar-title rounded-circle bg-success text-white">
                                        <i class="bi bi-coin fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Tổng điểm đã phát</h6>
                                <h4 class="mb-0 fw-bold text-success">{{ $points->sum('total_points') ?? $points->sum('points') }}</h4>
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
                                <div class="avatar-sm rounded-circle bg-warning-subtle">
                                    <span class="avatar-title rounded-circle bg-warning text-white">
                                        <i class="bi bi-clock fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Điểm sắp hết hạn</h6>
                                <h4 class="mb-0 fw-bold text-warning">{{ $points->where('points_expiry_date', '<=', now()->addDays(30))->count() }}</h4>
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
                                        <i class="bi bi-graph-up fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Điểm TB/khách</h6>
                                <h4 class="mb-0 fw-bold text-info">{{ $points->count() > 0 ? number_format(($points->sum('total_points') ?? $points->sum('points')) / $points->count(), 0) : 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="card-title mb-0 fw-semibold">
                                    <i class="bi bi-table me-2 text-primary"></i>
                                    Danh sách điểm thưởng
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form method="GET" action="{{ route('admin.points.index') }}" class="d-flex gap-2">
                                    <div class="input-group input-group-sm" style="width: 300px;">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" 
                                               class="form-control border-start-0 ps-0" 
                                               placeholder="Tìm kiếm khách hàng hoặc điểm..." 
                                               value="{{ request('search') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        Tìm kiếm
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-4" style="width: 40px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheckAll">
                                            </div>
                                        </th>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-person-circle me-1"></i>
                                            Khách hàng
                                        </th>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-coin text-warning me-1"></i>
                                            Điểm hiện tại
                                        </th>
                                        <th class="border-0 fw-semibold text-dark">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Ngày hết hạn
                                        </th>
                                        <th class="border-0 fw-semibold text-dark text-center">
                                            <i class="bi bi-gear me-1"></i>
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($points as $point)
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="pointCheck{{ $point->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <div class="avatar-title rounded-circle bg-primary text-white">
                                                            {{ substr($point->user->name ?? 'U', 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $point->user->name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">
                                                            <i class="bi bi-hash"></i>
                                                            {{ $point->user_id ?? $point->customer_id }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-warning-subtle text-warning fs-6 fw-bold px-3 py-2">
                                                        <i class="bi bi-coin me-1"></i>
                                                        {{ number_format($point->total_points ?? $point->points) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($point->points_expiry_date)
                                                        @php
                                                            $expiryDate = \Carbon\Carbon::parse($point->points_expiry_date);
                                                            $isExpiringSoon = $expiryDate->diffInDays(now()) <= 30;
                                                        @endphp
                                                        <span class="badge {{ $isExpiringSoon ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} px-3 py-2">
                                                            <i class="bi bi-calendar-event me-1"></i>
                                                            {{ $expiryDate->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">
                                                            <i class="bi bi-dash"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.points.show', $point->id) }}">
                                                                <i class="bi bi-eye me-2"></i>
                                                                Xem chi tiết
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.point_history.index', ['user_id' => $point->user_id ?? $point->customer_id]) }}">
                                                                <i class="bi bi-clock-history me-2"></i>
                                                                Lịch sử điểm
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h6>Không có dữ liệu</h6>
                                                    <p class="mb-0">Chưa có khách hàng nào có điểm thưởng</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($points->hasPages())
                        <div class="card-footer bg-white border-top py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Hiển thị {{ $points->firstItem() }}-{{ $points->lastItem() }} trong tổng {{ $points->total() }} kết quả
                                </div>
                                <div>
                                    {{ $points->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="bi bi-funnel me-2"></i>
                        Bộ lọc nâng cao
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET" action="{{ route('admin.points.index') }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="customer_id" class="form-label fw-semibold">ID Khách hàng</label>
                                <input type="text" name="customer_id" id="customer_id" 
                                       class="form-control" placeholder="Nhập ID khách hàng" 
                                       value="{{ request('customer_id') }}">
                            </div>
                            <div class="col-12">
                                <label for="points" class="form-label fw-semibold">Số điểm</label>
                                <input type="number" name="points" id="points" 
                                       class="form-control" placeholder="Nhập số điểm" 
                                       value="{{ request('points') }}">
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
@endsection
