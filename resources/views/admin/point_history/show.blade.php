@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-receipt-cutoff text-primary me-2"></i>
                        Chi tiết giao dịch điểm
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.point_history.index') }}">Lịch sử điểm</a></li>
                            <li class="breadcrumb-item active">Chi tiết giao dịch</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.point_history.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <!-- Transaction Details Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>
                            Thông tin giao dịch
                        </h5>
                        <div>
                            @switch($history->reason_type)
                                @case('earned')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Giao dịch cộng điểm
                                    </span>
                                    @break
                                @case('spent')
                                    <span class="badge bg-warning fs-6 px-3 py-2">
                                        <i class="bi bi-dash-circle me-1"></i>
                                        Giao dịch tiêu điểm
                                    </span>
                                    @break
                                @case('expired')
                                    <span class="badge bg-secondary fs-6 px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>
                                        Điểm hết hạn
                                    </span>
                                    @break
                                @case('adjusted')
                                    <span class="badge bg-info fs-6 px-3 py-2">
                                        <i class="bi bi-pencil me-1"></i>
                                        Điều chỉnh điểm
                                    </span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark fs-6 px-3 py-2">{{ $history->reason_type }}</span>
                            @endswitch
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Customer Info Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted mb-3 fw-semibold">
                                <i class="bi bi-person-circle me-2"></i>
                                Thông tin khách hàng
                            </h6>
                            <div class="bg-light rounded p-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-lg me-3">
                                        <div class="avatar-title rounded-circle bg-primary text-white fs-3">
                                            {{ substr($history->user->name ?? 'U', 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-bold">{{ $history->user->name ?? 'N/A' }}</h5>
                                        <div class="d-flex gap-3 text-muted">
                                            <span>
                                                <i class="bi bi-hash me-1"></i>
                                                ID: {{ $history->user_id }}
                                            </span>
                                            @if($history->user->email)
                                                <span>
                                                    <i class="bi bi-envelope me-1"></i>
                                                    {{ $history->user->email }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3 fw-semibold">
                                <i class="bi bi-coin me-2"></i>
                                Chi tiết điểm thay đổi
                            </h6>
                            <div class="bg-light rounded p-3 mb-4">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <span class="display-6 fw-bold {{ $history->points_change > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $history->points_change > 0 ? '+' : '' }}{{ number_format($history->points_change) }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-0">Điểm thay đổi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3 fw-semibold">
                                <i class="bi bi-receipt me-2"></i>
                                Thông tin đơn hàng
                            </h6>
                            <div class="bg-light rounded p-3 mb-4">
                                @if($history->booking_id)
                                    <div class="text-center">
                                        <a href="{{ route('admin.bookings.show', $history->booking_id) }}" 
                                           class="btn btn-primary btn-lg">
                                            <i class="bi bi-receipt me-2"></i>
                                            Xem đơn hàng #{{ $history->booking_id }}
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="bi bi-dash-circle display-6 d-block mb-2"></i>
                                        <p class="mb-0">Không có đơn hàng liên quan</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-3 fw-semibold">
                                <i class="bi bi-info-square me-2"></i>
                                Thông tin bổ sung
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="width: 180px;">
                                                <i class="bi bi-chat-left-text me-2"></i>
                                                Mô tả giao dịch:
                                            </td>
                                            <td class="fw-semibold">
                                                {{ $history->description ?? 'Không có mô tả' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">
                                                <i class="bi bi-calendar-event me-2"></i>
                                                Thời gian thực hiện:
                                            </td>
                                            <td class="fw-semibold">
                                                {{ $history->created_at ? $history->created_at->format('d/m/Y H:i:s') : 'Không xác định' }}
                                                @if($history->created_at)
                                                    <small class="text-muted ms-2">
                                                        ({{ $history->created_at->diffForHumans() }})
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">
                                                <i class="bi bi-database me-2"></i>
                                                ID Giao dịch:
                                            </td>
                                            <td class="fw-semibold">
                                                <code class="bg-light px-2 py-1 rounded">#{{ $history->id }}</code>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('admin.points.show', $history->user_id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-coin me-1"></i>
                            Xem điểm khách hàng
                        </a>
                        <a href="{{ route('admin.point_history.index', ['user_id' => $history->user_id]) }}" class="btn btn-outline-info">
                            <i class="bi bi-clock-history me-1"></i>
                            Lịch sử khách hàng
                        </a>
                        @if($history->booking_id)
                            <a href="{{ route('admin.bookings.show', $history->booking_id) }}" class="btn btn-outline-success">
                                <i class="bi bi-receipt me-1"></i>
                                Chi tiết đơn hàng
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection