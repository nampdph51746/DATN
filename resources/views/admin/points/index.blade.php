@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
    <style>
        :root {
            --primary-orange: #FF6F00;
            --primary-teal: #00ACC1;
            --accent-yellow: #FFCA28;
            --neutral-bg: #F8FAFC;
            --card-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
            --transition: all 0.3s ease;
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
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
        }

        .table > :not(caption) > * > * {
            padding: 1.2rem 1.5rem;
            border-bottom-width: 1px;
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background: rgba(0, 172, 193, 0.05);
            transform: translateY(-2px);
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: var(--primary-orange);
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--primary-teal);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }

        .btn-outline-primary {
            border-color: var(--primary-teal);
            color: var(--primary-teal);
            border-radius: 50px;
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
        }

        .view-detail-btn, .history-btn {
            border-radius: 8px;
            min-width: 36px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .view-detail-btn {
            background: var(--primary-teal);
            border: 1px solid var(--primary-teal);
            color: white;
        }

        .view-detail-btn:hover {
            background: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: #1a202c;
            transform: scale(1.1);
        }

        .history-btn {
            background: var(--accent-yellow);
            border: 1px solid var(--accent-yellow);
            color: #1a202c;
        }

        .history-btn:hover {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
            transform: scale(1.1);
        }

        .pagination {
            --bs-pagination-border-radius: 8px;
        }

        .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 3px;
            transition: var(--transition);
            color: var(--primary-teal);
        }

        .page-link:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: var(--primary-teal);
            border: none;
            color: white;
        }

        .form-check-input:checked {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .modal-content {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.2);
        }

        .input-group-text {
            background: var(--neutral-bg);
            border-color: #e0e0e0;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card, .table tbody tr {
            animation: fadeInUp 0.6s ease-out;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .avatar-sm {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .btn-primary, .btn-outline-primary {
                padding: 0.5rem 1rem;
            }

            .input-group {
                width: 100% !important;
            }
        }
    </style>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-coins me-2"></i>
                        Quản lý điểm thưởng
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Theo dõi và quản lý điểm thưởng của khách hàng
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-1"></i>
                        Bộ lọc
                    </button>
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="fas fa-download me-1"></i>
                        Xuất báo cáo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <i class="fas fa-users fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted">Tổng khách hàng</h6>
                            <h4 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $points->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <i class="fas fa-coins fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted">Tổng điểm đã phát</h6>
                            <h4 class="mb-0 fw-bold" style="color: var(--accent-yellow);">{{ $points->sum('total_points') ?? $points->sum('points') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <i class="fas fa-clock fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted">Điểm sắp hết hạn</h6>
                            <h4 class="mb-0 fw-bold" style="color: #DC3545;">{{ $points->where('points_expiry_date', '<=', now()->addDays(30))->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <i class="fas fa-chart-line fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted">Điểm TB/khách</h6>
                            <h4 class="mb-0 fw-bold" style="color: var(--primary-orange);">{{ $points->count() > 0 ? number_format(($points->sum('total_points') ?? $points->sum('points')) / $points->count(), 0) : 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="fas fa-table me-2"></i>
                                Danh sách điểm thưởng
                            </h5>
                        </div>
                        <div class="col-auto">
                            <form method="GET" action="{{ route('admin.points.index') }}" class="d-flex gap-2">
                                <div class="input-group input-group-sm" style="width: 300px;">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
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
                                        <i class="fas fa-user-circle me-1" style="color: var(--primary-teal);"></i>
                                        Khách hàng
                                    </th>
                                    <th class="border-0 fw-semibold text-dark">
                                        <i class="fas fa-coins me-1" style="color: var(--accent-yellow);"></i>
                                        Điểm hiện tại
                                    </th>
                                    <th class="border-0 fw-semibold text-dark">
                                        <i class="fas fa-calendar-alt me-1" style="color: var(--primary-orange);"></i>
                                        Ngày hết hạn
                                    </th>
                                    <th class="border-0 fw-semibold text-dark text-center">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-teal);"></i>
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
                                                    {{ substr($point->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold" style="color: var(--primary-teal);">{{ $point->user->name ?? 'N/A' }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-hashtag"></i>
                                                        {{ $point->user_id ?? $point->customer_id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-warning-subtle text-warning fs-6 fw-bold px-3 py-2">
                                                    <i class="fas fa-coins me-1"></i>
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
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $expiryDate->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-dash"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.points.show', $point->id) }}"
                                                   class="btn btn-sm view-detail-btn" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.point_history.index', ['user_id' => $point->user_id ?? $point->customer_id]) }}"
                                                   class="btn btn-sm history-btn" 
                                                   title="Lịch sử điểm"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox display-4 d-block mb-3" style="color: var(--primary-teal);"></i>
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

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fas fa-filter me-2"></i>
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
                            <i class="fas fa-search me-1"></i>
                            Áp dụng lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Check all checkboxes
    document.getElementById('customCheckAll')?.addEventListener('change', function() {
        document.querySelectorAll('.form-check-input').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -30px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe table rows and cards
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(row);
    });

    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>
@endsection