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
                        Chi tiết điểm thưởng
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.points.index') }}">Quản lý điểm</a></li>
                            <li class="breadcrumb-item active">Chi tiết điểm thưởng</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.points.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Info Card -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title rounded-circle bg-primary text-white fs-2">
                            {{ substr($point->user->name ?? 'U', 0, 1) }}
                        </div>
                    </div>
                    <h5 class="mb-1 fw-bold">{{ $point->user->name ?? 'N/A' }}</h5>
                    <p class="text-muted mb-3">{{ $point->user->email ?? '-' }}</p>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-2">
                                <h4 class="fw-bold text-primary mb-0">{{ $point->total_points }}</h4>
                                <p class="text-muted mb-0 small">Điểm hiện tại</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <h4 class="fw-bold text-success mb-0">{{ $point->user_id ?? $point->customer_id }}</h4>
                                <p class="text-muted mb-0 small">ID Khách hàng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Point Summary Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>
                        Thông tin điểm thưởng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Ngày hết hạn:</span>
                        @if($point->points_expiry_date)
                            @php
                                $expiryDate = \Carbon\Carbon::parse($point->points_expiry_date);
                                $isExpiringSoon = $expiryDate->diffInDays(now()) <= 30;
                            @endphp
                            <span class="badge {{ $isExpiringSoon ? 'bg-danger' : 'bg-success' }}">
                                {{ $expiryDate->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Ngày tạo:</span>
                        <span class="fw-semibold">{{ $point->created_at ? $point->created_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Cập nhật cuối:</span>
                        <span class="fw-semibold">{{ $point->updated_at ? $point->updated_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Card -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-clock-history me-2 text-primary"></i>
                            Lịch sử giao dịch điểm
                        </h5>
                        <span class="badge bg-primary-subtle text-primary">
                            {{ $point->histories()->count() }} giao dịch
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold">Thời gian</th>
                                    <th class="border-0 fw-semibold">Điểm thay đổi</th>
                                    <th class="border-0 fw-semibold">Loại giao dịch</th>
                                    <th class="border-0 fw-semibold">Mô tả</th>
                                    <th class="border-0 fw-semibold">Đơn hàng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($point->histories()->orderByDesc('created_at')->get() as $history)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">{{ $history->created_at ? $history->created_at->format('d/m/Y') : '-' }}</span>
                                                <small class="text-muted">{{ $history->created_at ? $history->created_at->format('H:i') : '' }}</small>
                                            </div>
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
                                            <span class="text-muted">{{ $history->description ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($history->booking_id)
                                                <a href="{{ route('admin.bookings.show', $history->booking_id) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-receipt me-1"></i>
                                                    #{{ $history->booking_id }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-clock-history display-4 d-block mb-3"></i>
                                                <h6>Chưa có lịch sử giao dịch</h6>
                                                <p class="mb-0">Khách hàng này chưa có giao dịch điểm nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection