@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <style>
        :root {
            --primary-orange: #FF6F00;
            --primary-teal: #00ACC1;
            --accent-yellow: #FFCA28;
            --neutral-bg: #F8FAFC;
            --card-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
            --transition: all 0.3s ease;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--neutral-bg);
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .card-header.bg-light {
            background: rgba(0, 172, 193, 0.1) !important;
            color: var(--primary-teal);
            border-bottom: 1px solid rgba(0, 172, 193, 0.2);
        }

        .card-header.bg-white {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal)) !important;
            color: white;
        }

        .breadcrumb {
            background: none;
            padding: 0;
        }

        .breadcrumb-item a {
            color: var(--primary-teal);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: var(--primary-orange);
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        .btn {
            font-weight: 600;
            transition: var(--transition);
            border-radius: 50px;
        }

        .btn-outline-secondary {
            border-color: var(--primary-teal);
            color: var(--primary-teal);
        }

        .btn-outline-secondary:hover {
            background: var(--primary-teal);
            border-color: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .btn-outline-primary:hover {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
            transform: scale(1.05);
        }

        .avatar-lg {
            width: 5rem;
            height: 5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            border-radius: 50%;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            box-shadow: 0 8px 25px rgba(0, 172, 193, 0.3);
            margin: 0 auto;
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(255, 111, 0, 0.1), rgba(0, 172, 193, 0.1));
            border-radius: 12px;
            padding: 1.5rem;
            margin: 0.5rem 0;
            transition: var(--transition);
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .table > :not(caption) > * > * {
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background: rgba(0, 172, 193, 0.05);
            transform: translateX(3px);
        }

        .badge {
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
        }

        .badge.bg-primary {
            background: var(--primary-teal) !important;
        }

        .badge.bg-success {
            background: var(--success-gradient) !important;
            border: none;
        }

        .badge.bg-warning {
            background: var(--warning-gradient) !important;
            border: none;
            color: white;
        }

        .badge.bg-danger {
            background: var(--danger-gradient) !important;
            border: none;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, var(--primary-teal), var(--accent-yellow)) !important;
            border: none;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, #6c757d, #495057) !important;
            border: none;
        }

        .badge.bg-primary-subtle {
            background: rgba(0, 172, 193, 0.1) !important;
            color: var(--primary-teal) !important;
        }

        .badge.bg-success-subtle {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #059669 !important;
        }

        .badge.bg-danger-subtle {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #dc2626 !important;
        }

        .text-primary {
            color: var(--primary-teal) !important;
        }

        .text-success {
            color: #059669 !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .table-light {
            background: rgba(0, 172, 193, 0.05) !important;
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--primary-teal);
            opacity: 0.3;
        }

        .point-change-positive {
            background: var(--success-gradient) !important;
            color: white !important;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
        }

        .point-change-negative {
            background: var(--danger-gradient) !important;
            color: white !important;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: #374151;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card, .stats-card {
            animation: fadeInUp 0.6s ease-out;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .avatar-lg {
                width: 4rem;
                height: 4rem;
                font-size: 1.5rem;
            }

            .stats-card {
                padding: 1rem;
                margin: 0.25rem 0;
            }

            .table-responsive {
                font-size: 0.875rem;
            }
        }

        .user-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 2rem;
            text-align: center;
        }

        .stats-grid {
            background: white;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            padding: 1.5rem;
        }
    </style>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-coins me-2"></i>
                        Chi tiết điểm thưởng
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.points.index') }}">
                                    <i class="fas fa-award me-1"></i>
                                    Quản lý điểm
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Chi tiết điểm thưởng</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.points.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>
                        Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Info Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 mb-4">
                <div class="user-header">
                    <div class="avatar-lg mb-3">
                        {{ substr($point->user->name ?? 'U', 0, 1) }}
                    </div>
                    <h5 class="mb-1 fw-bold">{{ $point->user->name ?? 'Khách hàng không xác định' }}</h5>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-envelope me-1"></i>
                        {{ $point->user->email ?? 'Không có email' }}
                    </p>
                </div>
                
                <div class="stats-grid">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="fas fa-coins fs-3" style="color: var(--primary-teal);"></i>
                                </div>
                                <h3 class="fw-bold mb-1" style="color: var(--primary-teal);">
                                    {{ number_format($point->total_points) }}
                                </h3>
                                <p class="text-muted mb-0 small fw-semibold">Điểm hiện tại</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="fas fa-user-tag fs-3" style="color: var(--primary-orange);"></i>
                                </div>
                                <h3 class="fw-bold mb-1" style="color: var(--primary-orange);">
                                    #{{ $point->user_id ?? $point->customer_id }}
                                </h3>
                                <p class="text-muted mb-0 small fw-semibold">ID Khách hàng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Point Summary Card -->
            <div class="card border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin chi tiết
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar-times me-1 text-danger"></i>
                            Ngày hết hạn
                        </span>
                        @if($point->points_expiry_date)
                            @php
                                $expiryDate = \Carbon\Carbon::parse($point->points_expiry_date);
                                $isExpiringSoon = $expiryDate->diffInDays(now()) <= 30;
                                $isExpired = $expiryDate->isPast();
                            @endphp
                            <span class="badge {{ $isExpired ? 'bg-danger' : ($isExpiringSoon ? 'bg-warning' : 'bg-success') }}">
                                <i class="fas fa-clock me-1"></i>
                                {{ $expiryDate->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-infinity me-1"></i>
                                Không giới hạn
                            </span>
                        @endif
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar-plus me-1 text-success"></i>
                            Ngày tạo
                        </span>
                        <span class="info-value">
                            {{ $point->created_at ? $point->created_at->format('d/m/Y H:i') : 'Không rõ' }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-sync-alt me-1 text-primary"></i>
                            Cập nhật cuối
                        </span>
                        <span class="info-value">
                            {{ $point->updated_at ? $point->updated_at->diffForHumans() : 'Chưa cập nhật' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Card -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2"></i>
                            Lịch sử giao dịch điểm
                        </h5>
                        <span class="badge bg-white text-dark px-3 py-2">
                            <i class="fas fa-chart-line me-1"></i>
                            {{ isset($histories) ? $histories->count() : \App\Models\PointHistory::where('user_id', $point->user_id)->count() }} giao dịch
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-bold">
                                        <i class="fas fa-clock me-1" style="color: var(--primary-teal);"></i>
                                        Thời gian
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="fas fa-exchange-alt me-1" style="color: var(--primary-orange);"></i>
                                        Điểm thay đổi
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="fas fa-tags me-1" style="color: var(--accent-yellow);"></i>
                                        Loại giao dịch
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="fas fa-align-left me-1" style="color: var(--primary-teal);"></i>
                                        Mô tả
                                    </th>
                                    <th class="border-0 fw-bold">
                                        <i class="fas fa-receipt me-1" style="color: var(--primary-orange);"></i>
                                        Đơn hàng
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(isset($histories) ? $histories : \App\Models\PointHistory::where('user_id', $point->user_id)->orderByDesc('created_at')->get() as $history)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-calendar-day text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $history->created_at ? $history->created_at->format('d/m/Y') : 'Không rõ' }}</div>
                                                    <small class="text-muted">{{ $history->created_at ? $history->created_at->format('H:i:s') : '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $history->points_change > 0 ? 'point-change-positive' : 'point-change-negative' }}">
                                                <i class="fas {{ $history->points_change > 0 ? 'fa-plus' : 'fa-minus' }} me-1"></i>
                                                {{ $history->points_change > 0 ? '+' : '' }}{{ number_format($history->points_change) }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($history->reason_type)
                                                @case('earned')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-plus-circle me-1"></i>
                                                        Cộng điểm
                                                    </span>
                                                    @break
                                                @case('spent')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-minus-circle me-1"></i>
                                                        Tiêu điểm
                                                    </span>
                                                    @break
                                                @case('expired')
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Hết hạn
                                                    </span>
                                                    @break
                                                @case('adjusted')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-edit me-1"></i>
                                                        Điều chỉnh
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-question me-1"></i>
                                                        {{ $history->reason_type }}
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $history->description ?? 'Không có mô tả' }}</span>
                                        </td>
                                        <td>
                                            @if($history->booking_id)
                                                <a href="{{ route('admin.bookings.show', $history->booking_id) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    #{{ $history->booking_id }}
                                                </a>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-minus"></i>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <i class="fas fa-history"></i>
                                                <h6 class="fw-bold text-muted">Chưa có lịch sử giao dịch</h6>
                                                <p class="text-muted mb-0">Khách hàng này chưa có giao dịch điểm nào trong hệ thống.</p>
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

@section('scripts')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading animation to external links
    document.querySelectorAll('a[href*="bookings"]').forEach(link => {
        link.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang tải...';
        });
    });
});
</script>
@endsection