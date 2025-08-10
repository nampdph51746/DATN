@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle bg-success-subtle">
                        <span class="avatar-title rounded-circle bg-success text-white">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Thành công!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-buildings text-info me-2"></i>
                        Quản lý thành phố
                    </h4>
                    <p class="text-muted mb-0">Quản lý danh sách các thành phố trong hệ thống</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.cities.trash') }}" class="btn btn-outline-danger btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-trash3 me-2"></i> Thùng rác
                    </a>
                    <a href="{{ route('admin.cities.create') }}" class="btn btn-info btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Thêm thành phố
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-white">
                                    <i class="bi bi-buildings fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng thành phố</h6>
                            <h3 class="mb-0 fw-bold text-info">{{ $cities->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success text-white">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Hoạt động</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $cities->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 85%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-white">
                                    <i class="bi bi-calendar-week fs-4"></i>
                                </span>
                        </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tuần này</h6>
                            <h3 class="mb-0 fw-bold text-warning">3</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-white">
                                    <i class="bi bi-search fs-4"></i>
                                </span>
                        </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tìm kiếm</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ request('keyword') ? 'Có' : 'Không' }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-primary" style="width: {{ request('keyword') ? '100' : '0' }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 py-4 rounded-top-4">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="bi bi-table me-2 text-info"></i>
                                Danh sách thành phố
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <form action="{{ route('admin.cities.index') }}" method="GET" class="d-flex gap-2">
                                <div class="input-group">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                           class="form-control rounded-pill" 
                                           placeholder="Tìm tên thành phố...">
                                    <button type="submit" class="btn btn-info rounded-pill ms-2">
                                        <i class="bi bi-search"></i>
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
                                        <i class="bi bi-hash me-1"></i> STT
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-buildings me-1"></i> Tên thành phố
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-globe me-1"></i> Quốc gia
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-plus me-1"></i> Thời gian tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-pencil-square me-1"></i> Cập nhật
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="bi bi-gear me-1"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $key => $city)
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2 fw-semibold">
                                                    {{ $key + 1 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-sm rounded-circle bg-info-subtle">
                                                        <span class="avatar-title rounded-circle bg-info text-white">
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
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-globe text-primary me-2"></i>
                                                <span class="fw-semibold">{{ $city->country->name ?? 'Không xác định' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $city->created_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $city->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <i class="bi bi-clock-history me-1"></i>
                                                {{ $city->updated_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $city->updated_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.cities.show', $city->id) }}"
                                                   class="btn btn-sm rounded-3 view-detail-btn" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.cities.edit', $city->id) }}"
                                                   class="btn btn-sm rounded-3 edit-btn" 
                                                   title="Chỉnh sửa"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
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
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
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
/* Modern UI Styles */
.card {
    transition: all 0.3s ease;
    border: none !important;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
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

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-lg {
    width: 3.5rem;
    height: 3.5rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Action Buttons - GIỐNG TRANG MOVIES: */
.view-detail-btn {
    background-color: #6c757d !important;
    border: 1px solid #6c757d !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.view-detail-btn:hover {
    background-color: #5a6268 !important;
    border-color: #545b62 !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(108, 117, 125, 0.3);
}

.edit-btn {
    background-color: #212529 !important;
    border: 1px solid #212529 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.edit-btn:hover {
    background-color: #343a40 !important;
    border-color: #343a40 !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(33, 37, 41, 0.3);
}

.view-detail-btn i,
.edit-btn i {
    font-size: 14px;
    color: white !important;
}

/* Tooltip styling */
.tooltip {
    font-size: 12px;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}

.bg-primary-subtle {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.85rem;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
    
    .view-detail-btn,
    .edit-btn {
        min-width: 32px;
        height: 28px;
    }
}
</style>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection