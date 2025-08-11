@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-lg rounded-3 mb-5" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle bg-teal-light">
                        <span class="avatar-title rounded-circle bg-teal text-white">
                            <i class="fas fa-check-circle fs-5"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold text-teal">Thành công!</h6>
                    <p class="mb-0 text-dark">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="fas fa-globe-asia text-orange me-2"></i>
                        Quản lý quốc gia
                    </h4>
                    <p class="text-muted mb-0">Quản lý danh sách các quốc gia trong hệ thống</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.countries.trash') }}" class="btn btn-outline-warning btn-lg rounded-pill shadow-sm">
                        <i class="fas fa-trash-alt me-2"></i> Thùng rác
                    </a>
                    <a href="{{ route('admin.countries.create') }}" class="btn btn-teal btn-lg rounded-pill shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i> Thêm quốc gia
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5 g-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden bg-orange-light">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-orange-subtle">
                                <span class="avatar-title rounded-circle bg-orange text-white">
                                    <i class="fas fa-globe fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark fw-semibold">Tổng quốc gia</h6>
                            <h3 class="mb-0 fw-bold text-orange">{{ $countries->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 6px;">
                    <div class="progress-bar bg-orange" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden bg-teal-light">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-teal-subtle">
                                <span class="avatar-title rounded-circle bg-teal text-white">
                                    <i class="fas fa-check-circle fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark fw-semibold">Hoạt động</h6>
                            <h3 class="mb-0 fw-bold text-teal">{{ $countries->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 6px;">
                    <div class="progress-bar bg-teal" style="width: 85%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden bg-yellow-light">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-yellow-subtle">
                                <span class="avatar-title rounded-circle bg-yellow text-dark">
                                    <i class="fas fa-calendar-week fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark fw-semibold">Tuần này</h6>
                            <h3 class="mb-0 fw-bold text-yellow">3</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 6px;">
                    <div class="progress-bar bg-yellow" style="width: 60%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden bg-teal-light">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-teal-subtle">
                                <span class="avatar-title rounded-circle bg-teal text-white">
                                    <i class="fas fa-search fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-dark fw-semibold">Tìm kiếm</h6>
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
            <div class="card border-0 shadow-lg rounded-4 bg-white">
                <div class="card-header bg-white border-bottom-0 py-4 rounded-top-4">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="fas fa-table me-2 text-orange"></i>
                                Danh sách quốc gia
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <form action="{{ route('admin.countries.index') }}" method="GET" class="d-flex gap-2">
                                <div class="input-group">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                                           class="form-control rounded-pill border-teal-light"
                                           placeholder="Tìm tên quốc gia...">
                                    <button type="submit" class="btn btn-teal rounded-pill ms-2">
                                        <i class="fas fa-search"></i>
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
                                        <i class="fas fa-hashtag me-1 text-orange"></i> STT
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-globe-asia me-1 text-teal"></i> Tên quốc gia
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-code me-1 text-yellow"></i> Mã code
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-plus me-1 text-orange"></i> Thời gian tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-clock me-1 text-teal"></i> Cập nhật
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cog me-1 text-yellow"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countries as $key => $country)
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="badge bg-orange-light text-orange rounded-pill px-3 py-2 fw-semibold">
                                                    {{ $key + 1 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-sm rounded-circle bg-teal-light">
                                                        <span class="avatar-title rounded-circle bg-teal text-white">
                                                            <i class="fas fa-globe-asia"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $country->name }}</h6>
                                                    <small class="text-muted">ID: #{{ $country->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-yellow-light text-yellow rounded-pill px-3 py-2 fw-semibold">
                                                {{ $country->code }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-alt me-1 text-orange"></i>
                                                {{ $country->created_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $country->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <i class="fas fa-clock me-1 text-teal"></i>
                                                {{ $country->updated_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $country->updated_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                @if(Route::has('admin.countries.show'))
                                                <a href="{{ route('admin.countries.show', $country->id) }}"
                                                   class="btn btn-sm btn-orange rounded-pill view-detail-btn"
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @endif
                                                <a href="{{ route('admin.countries.edit', $country->id) }}"
                                                   class="btn btn-sm btn-teal rounded-pill edit-btn"
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
                                                <i class="fas fa-globe-asia text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có quốc gia nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm quốc gia mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($countries->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $countries->firstItem() }}-{{ $countries->lastItem() }} trong tổng {{ $countries->total() }} quốc gia
                            </div>
                            <div>
                                {{ $countries->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    --orange: #f97316;
    --teal: #14b8a6;
    --yellow: #facc15;
    --orange-light: #fff7ed;
    --teal-light: #ecfeff;
    --yellow-light: #fefce8;
    --orange-subtle: #fed7aa;
    --teal-subtle: #a5f3fc;
    --yellow-subtle: #fef08a;
}

/* General Card Styling */
.card {
    transition: all 0.3s ease;
    border: none !important;
    border-radius: 12px !important;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
}

/* Table Styling */
.table > :not(caption) > * > * {
    padding: 1.25rem 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: var(--teal-light);
    transform: translateX(4px);
}

/* Button Styling */
.btn {
    transition: all 0.3s ease;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.btn-lg {
    padding: 0.75rem 1.75rem;
    font-size: 1rem;
}

.btn-teal {
    background-color: var(--teal);
    border-color: var(--teal);
    color: white;
}

.btn-teal:hover {
    background-color: #0d9488;
    border-color: #0d9488;
    color: white;
}

.btn-orange {
    background-color: var(--orange);
    border-color: var(--orange);
    color: white;
}

.btn-orange:hover {
    background-color: #ea580c;
    border-color: #ea580c;
    color: white;
}

/* Avatar Styling */
.avatar-sm {
    width: 2.75rem;
    height: 2.75rem;
}

.avatar-lg {
    width: 4rem;
    height: 4rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Badge Styling */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 0.5rem 1rem;
}

/* Custom Backgrounds */
.bg-orange-light {
    background-color: var(--orange-light) !important;
}

.bg-teal-light {
    background-color: var(--teal-light) !important;
}

.bg-yellow-light {
    background-color: var(--yellow-light) !important;
}

.bg-orange-subtle {
    background-color: var(--orange-subtle) !important;
}

.bg-teal-subtle {
    background-color: var(--teal-subtle) !important;
}

.bg-yellow-subtle {
    background-color: var(--yellow-subtle) !important;
}

/* Action Buttons */
.view-detail-btn {
    background-color: var(--orange) !important;
    border: 1px solid var(--orange) !important;
    color: white !important;
    min-width: 40px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.view-detail-btn:hover {
    background-color: #ea580c !important;
    border-color: #ea580c !important;
    box-shadow: 0 4px 8px rgba(249, 115, 22, 0.3);
}

.edit-btn {
    background-color: var(--teal) !important;
    border: 1px solid var(--teal) !important;
    color: white !important;
    min-width: 40px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.edit-btn:hover {
    background-color: #0d9488 !important;
    border-color: #0d9488 !important;
    box-shadow: 0 4px 8px rgba(20, 184, 166, 0.3);
}

/* Icon Styling */
.view-detail-btn i,
.edit-btn i {
    font-size: 16px;
    color: white !important;
}

/* Input Styling */
.border-teal-light {
    border-color: var(--teal-light) !important;
}

.form-control:focus {
    border-color: var(--teal);
    box-shadow: 0 0 0 0.25rem rgba(20, 184, 166, 0.25);
}

/* Tooltip Styling */
.tooltip {
    font-size: 13px;
    font-weight: 500;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.75rem 0.5rem;
        font-size: 0.9rem;
    }

    .btn-lg {
        padding: 0.5rem 1.25rem;
        font-size: 0.9rem;
    }

    .view-detail-btn,
    .edit-btn {
        min-width: 36px;
        height: 32px;
    }

    .avatar-lg {
        width: 3.5rem;
        height: 3.5rem;
    }
}
</style>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});
</script>
@endsection