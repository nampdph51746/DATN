@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-lg rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle bg-success-subtle">
                        <span class="avatar-title rounded-circle bg-success text-white">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1 fw-bold text-success">Thành công!</h6>
                    <p class="mb-0 text-dark">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-circle bg-orange">
                    <i class="bi bi-buildings fs-3 text-white"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Quản lý thành phố</h4>
                    <p class="text-muted mb-0">Quản lý danh sách các thành phố trong hệ thống</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                <a href="{{ route('admin.cities.trash') }}" class="btn btn-outline-danger rounded-pill shadow-sm">
                    <i class="bi bi-trash3 me-2"></i> Thùng rác
                </a>
                <a href="{{ route('admin.cities.create') }}" class="btn btn-orange rounded-pill shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i> Thêm thành phố
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-orange-subtle">
                                <span class="avatar-title rounded-circle bg-orange text-white">
                                    <i class="bi bi-buildings fs-3"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-muted fw-semibold">Tổng thành phố</h6>
                            <h3 class="mb-0 fw-bold text-orange">{{ $cities->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 6px;">
                    <div class="progress-bar bg-orange" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-teal-subtle">
                                <span class="avatar-title rounded-circle bg-teal text-white">
                                    <i class="bi bi-check-circle fs-3"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-muted fw-semibold">Hoạt động</h6>
                            <h3 class="mb-0 fw-bold text-teal">{{ $cities->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 6px;">
                    <div class="progress-bar bg-teal" style="width: 85%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-white">
                                    <i class="bi bi-calendar-week fs-3"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-muted fw-semibold">Tuần này</h6>
                            <h3 class="mb-0 fw-bold text-warning">3</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-teal-subtle">
                                <span class="avatar-title rounded-circle bg-teal text-white">
                                    <i class="bi bi-search fs-3"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-muted fw-semibold">Tìm kiếm</h6>
                            <h3 class="mb-0 fw-bold text-teal">{{ request('keyword') ? 'Có' : 'Không' }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 6px;">
                    <div class="progress-bar bg-teal" style="width: {{ request('keyword') ? '100' : '0' }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4 rounded-top-3">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="bi bi-table me-2 text-orange"></i>
                                Danh sách thành phố
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4 mt-3 mt-md-0">
                            <form action="{{ route('admin.cities.index') }}" method="GET" class="d-flex gap-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-orange text-white border-0 rounded-start-pill">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                                           class="form-control rounded-end-pill border-orange"
                                           placeholder="Tìm tên thành phố...">
                                    <button type="submit" class="btn btn-orange rounded-pill ms-2">
                                        Tìm kiếm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-bold text-dark" style="width: 60px;">
                                        <i class="bi bi-hash me-1 text-orange"></i> STT
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-buildings me-1 text-orange"></i> Tên thành phố
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-globe me-1 text-teal"></i> Quốc gia
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-plus me-1 text-orange"></i> Thời gian tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-pencil-square me-1 text-teal"></i> Cập nhật
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="bi bi-gear me-1 text-orange"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $key => $city)
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="badge bg-orange-subtle text-orange rounded-pill px-3 py-2 fw-semibold">
                                                    {{ $key + 1 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm rounded-circle bg-orange-subtle">
                                                        <span class="avatar-title rounded-circle bg-orange text-white">
                                                            <i class="bi bi-buildings"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $city->name }}</h6>
                                                    <small class="text-muted">ID: #{{ $city->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-globe text-teal me-1"></i>
                                                <span class="fw-semibold">{{ $city->country->name ?? 'Không xác định' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <i class="bi bi-calendar-event me-1 text-orange"></i>
                                                {{ $city->created_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $city->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <i class="bi bi-clock-history me-1 text-teal"></i>
                                                {{ $city->updated_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $city->updated_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.cities.show', $city->id) }}"
                                                   class="btn btn-sm btn-orange rounded-pill shadow-sm"
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.cities.edit', $city->id) }}"
                                                   class="btn btn-sm btn-teal rounded-pill shadow-sm"
                                                   title="Chỉnh sửa"
                                                   data-bs-toggle="tooltip">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-buildings text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có thành phố nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm thành phố mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($cities->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="text-muted small">
                                Hiển thị {{ $cities->firstItem() }}-{{ $cities->lastItem() }} trong tổng {{ $cities->total() }} thành phố
                            </div>
                            <div>
                                {{ $cities->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Color Palette */
:root {
    --orange: #FF6B35;
    --teal: #00A7B5;
    --orange-subtle: rgba(255, 107, 53, 0.1);
    --teal-subtle: rgba(0, 167, 181, 0.1);
}

/* General Card Styling */
.card {
    transition: all 0.3s ease;
    border: none !important;
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

/* Table Styling */
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: var(--orange-subtle);
    transform: translateX(3px);
}

/* Button Styling */
.btn {
    transition: all 0.3s ease;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.btn-orange {
    background-color: var(--orange);
    border-color: var(--orange);
    color: white;
}

.btn-orange:hover {
    background-color: #e65a2f;
    border-color: #e65a2f;
    color: white;
}

.btn-teal {
    background-color: var(--teal);
    border-color: var(--teal);
    color: white;
}

.btn-teal:hover {
    background-color: #008f9b;
    border-color: #008f9b;
    color: white;
}

.btn-lg {
    padding: 0.75rem 1.75rem;
    font-size: 1rem;
}

/* Avatar and Icon Styling */
.avatar-sm {
    width: 2.25rem;
    height: 2.25rem;
}

.avatar-lg {
    width: 3.25rem;
    height: 3.25rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.icon-circle {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: var(--orange);
}

/* Input Group Styling */
.input-group-text {
    background-color: var(--orange);
    border-color: var(--orange);
}

.form-control {
    border-color: var(--orange-subtle);
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--orange);
    box-shadow: 0 0 0 0.2rem var(--orange-subtle);
}

/* Action Buttons */
.btn-sm {
    min-width: 38px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.btn-sm i {
    font-size: 1rem;
}

/* Tooltip Styling */
.tooltip {
    font-size: 0.85rem;
    --bs-tooltip-bg: var(--teal);
}

/* Badge Styling */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Subtle Backgrounds */
.bg-orange-subtle {
    background-color: var(--orange-subtle) !important;
}

.bg-teal-subtle {
    background-color: var(--teal-subtle) !important;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-lg {
        padding: 0.5rem 1.25rem;
        font-size: 0.9rem;
    }

    .btn-sm {
        min-width: 34px;
        height: 30px;
    }

    .avatar-lg {
        width: 2.75rem;
        height: 2.75rem;
    }

    .icon-circle {
        width: 2.5rem;
        height: 2.5rem;
    }
}
</style>

<!-- Font Awesome and Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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