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
            box-shadow: 0 12px 30px rgba(0, 172, 193, 0.15);
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
        }
        .badge {
            font-size: 0.95rem;
            border-radius: 50px;
            padding: 0.5em 1em;
        }
        .badge-active {
            background: var(--primary-teal);
            color: #fff;
        }
        .badge-pending {
            background: var(--accent-yellow);
            color: #1a202c;
        }
        .badge-inactive {
            background: #dc3545;
            color: #fff;
        }
        .btn {
            font-weight: 600;
            transition: var(--transition);
            border-radius: 50px;
        }
        .btn-primary {
            background: var(--primary-orange);
            border: none;
        }
        .btn-primary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }
        .btn-success {
            background: var(--primary-teal);
            border: none;
        }
        .btn-success:hover {
            background: var(--accent-yellow);
            color: #1a202c;
            transform: translateY(-2px);
        }
        .btn-outline-primary {
            border-color: var(--primary-teal);
            color: var(--primary-teal);
        }
        .btn-outline-primary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
        }
        .btn-outline-danger:hover, .btn-outline-info:hover {
            color: #fff !important;
        }
        .table > :not(caption) > * > * {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        .table tbody tr {
            transition: var(--transition);
        }
        .table tbody tr:hover {
            background: rgba(0, 172, 193, 0.05);
            transform: translateX(2px);
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.2);
        }
        .input-group-text {
            background: var(--neutral-bg);
            border-color: #e0e0e0;
            border-radius: 8px;
        }
        .pagination {
            --bs-pagination-border-radius: 8px;
        }
        .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 3px;
            color: var(--primary-teal);
            transition: var(--transition);
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
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            .input-group {
                min-width: 100% !important;
            }
        }
    </style>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-tags me-2"></i>
                        Quản lý khuyến mãi
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý toàn bộ chương trình khuyến mãi trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary btn-lg rounded-pill">
                        <i class="fas fa-plus-circle me-2"></i> Thêm khuyến mãi
                    </a>
                    <a href="{{ route('admin.promotions.trashed') }}" class="btn btn-outline-danger btn-lg rounded-pill">
                        <i class="fas fa-trash-alt me-2"></i> Đã xoá mềm
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg" style="background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));">
                                <i class="fas fa-tags fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng khuyến mãi</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $promotions->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-teal); width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg" style="background: linear-gradient(135deg, var(--primary-teal), var(--primary-orange));">
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Active</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-orange);">{{ $promotions->where('status', 'active')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-orange); width: {{ $promotions->total() > 0 ? ($promotions->where('status', 'active')->count() / $promotions->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg" style="background: linear-gradient(135deg, var(--accent-yellow), var(--primary-teal));">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Pending</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--accent-yellow);">{{ $promotions->where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--accent-yellow); width: {{ $promotions->total() > 0 ? ($promotions->where('status', 'pending')->count() / $promotions->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg" style="background: linear-gradient(135deg, #dc3545, var(--primary-teal));">
                                <i class="fas fa-times-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Inactive</h6>
                            <h3 class="mb-0 fw-bold" style="color: #dc3545;">{{ $promotions->where('status', 'inactive')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: #dc3545; width: {{ $promotions->total() > 0 ? ($promotions->where('status', 'inactive')->count() / $promotions->total()) * 100 : 0 }}%"></div>
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
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-table me-2"></i>
                                Danh sách khuyến mãi
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.promotions.index') }}">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text rounded-start-pill">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="search" name="search" 
                                               class="form-control ps-0" 
                                               placeholder="Tìm kiếm khuyến mãi..." 
                                               value="{{ request('search') }}">
                                        <select name="status" class="form-select rounded-end-pill">
                                            <option value="">Tất cả</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4" style="width: 50px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkAllPromotions">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-hashtag me-1" style="color: var(--primary-teal);"></i> ID
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-tag me-1" style="color: var(--primary-orange);"></i> Tên KM
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-barcode me-1" style="color: var(--accent-yellow);"></i> Mã KM
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-user-check me-1" style="color: var(--primary-teal);"></i> Hạng KH
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-percent me-1" style="color: var(--primary-orange);"></i> Loại giảm giá
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-money-bill-wave me-1" style="color: var(--accent-yellow);"></i> Giá trị giảm
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-plus me-1" style="color: var(--primary-teal);"></i> Ngày bắt đầu
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-check me-1" style="color: var(--primary-orange);"></i> Ngày kết thúc
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-traffic-light me-1" style="color: var(--accent-yellow);"></i> Trạng thái
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-orange);"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promotions as $promotion)
                                    @php
                                        $statusClass = $promotion->status === 'active' ? 'badge-active' : ($promotion->status === 'pending' ? 'badge-pending' : 'badge-inactive');
                                    @endphp
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input promotion-checkbox" value="{{ $promotion->id }}">
                                            </div>
                                        </td>
                                        <td class="fw-medium">#{{ $promotion->id }}</td>
                                        <td>{{ $promotion->name }}</td>
                                        <td>{{ $promotion->code }}</td>
                                        <td>
                                            @if($promotion->rank)
                                                <span class="badge bg-info-subtle text-info fw-medium px-3 py-2 rounded-pill">{{ $promotion->rank->name }}</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary fw-medium px-3 py-2 rounded-pill">Tất cả</span>
                                            @endif
                                        </td>
                                        <td>{{ $promotion->discount_type }}</td>
                                        <td class="text-nowrap">{{ number_format($promotion->discount_value, 2) }}</td>
                                        <td class="text-nowrap">{{ $promotion->start_date->format('d/m/Y') }}</td>
                                        <td class="text-nowrap">{{ $promotion->end_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($promotion->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.promotions.show', $promotion->id) }}"
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Chỉnh sửa"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xoá mềm?')" title="Xoá">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($promotions->isEmpty())
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-tags text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có khuyến mãi nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm khuyến mãi mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($promotions->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $promotions->firstItem() }}-{{ $promotions->lastItem() }} trong tổng {{ $promotions->total() }} khuyến mãi
                            </div>
                            <div>
                                {{ $promotions->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
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

    // Check all promotions
    document.getElementById('checkAllPromotions')?.addEventListener('change', function() {
        document.querySelectorAll('.promotion-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
});
</script>
@endsection