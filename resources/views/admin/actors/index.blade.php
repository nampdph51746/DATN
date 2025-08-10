@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Alert Messages với animation -->
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
                    @if(session('actor_created'))
                        <small class="d-block mt-1 text-success-emphasis">
                            <i class="bi bi-info-circle me-1"></i>
                            Diễn viên mới đã được thêm vào đầu danh sách bên dưới.
                        </small>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle bg-danger-subtle">
                        <span class="avatar-title rounded-circle bg-danger text-white">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Có lỗi xảy ra!</h6>
                    <p class="mb-0">{{ session('error') }}</p>
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
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        Quản lý diễn viên
                    </h4>
                    <p class="text-muted mb-0">Quản lý toàn bộ danh sách diễn viên trong hệ thống</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.actors.create') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Thêm diễn viên mới
                    </a>
                    <a href="{{ route('admin.movies.create') }}" class="btn btn-success btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-film me-2"></i> Thêm phim
                    </a>
                    <a href="{{ route('admin.directors.create') }}" class="btn btn-info btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-person-video me-2"></i> Thêm đạo diễn
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
                            <div class="avatar-lg rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-white">
                                    <i class="bi bi-people-fill fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng diễn viên</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $actors->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
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
                            <h6 class="mb-0 text-muted fw-semibold">Đang hoạt động</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $actors->where('is_active', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: {{ $actors->total() > 0 ? ($actors->where('is_active', true)->count() / $actors->total()) * 100 : 0 }}%"></div>
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
                                    <i class="bi bi-pause-circle fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Ngưng hoạt động</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $actors->where('is_active', false)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: {{ $actors->total() > 0 ? ($actors->where('is_active', false)->count() / $actors->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-white">
                                    <i class="bi bi-film fs-4"></i>
                                </span>
                        </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng phim đã tham gia</h6>
                            <h3 class="mb-0 fw-bold text-info">{{ $actors->sum(function($actor) { return $actor->movies->count(); }) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-info" style="width: 75%"></div>
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
                                <i class="bi bi-table me-2 text-primary"></i>
                                Danh sách diễn viên
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.actors.index') }}">
                                    <div class="input-group input-group-lg" style="min-width: 300px;">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-pill">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="search" name="query" 
                                               class="form-control border-start-0 ps-0" 
                                               placeholder="Tìm kiếm diễn viên..." 
                                               value="{{ request('query') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                        <i class="bi bi-search"></i>
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
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-people-fill me-1"></i> Diễn viên
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-globe me-1"></i> Quốc tịch
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-event me-1"></i> Ngày sinh
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-film me-1"></i> Số phim
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-flag me-1"></i> Trạng thái
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-plus me-1"></i> Thời gian tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="bi bi-gear me-1"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($actors as $actor)
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="position-relative">
                                                        <img src="{{ $actor->image_path ? Storage::url($actor->image_path) : asset('assets/images/actor-placeholder.png') }}" 
                                                             alt="{{ $actor->name }}" 
                                                             class="actor-avatar rounded-circle shadow-sm">
                                                        @if($actor->is_active)
                                                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border-2 border-white rounded-circle">
                                                                <span class="visually-hidden">Active</span>
                                                            </span>
                                                        @else
                                                            <span class="position-absolute bottom-0 end-0 p-1 bg-danger border-2 border-white rounded-circle">
                                                                <span class="visually-hidden">Inactive</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $actor->name }}</h6>
                                                    @if($actor->movies->count() > 0)
                                                        <span class="badge bg-info-subtle text-info rounded-pill">
                                                            <i class="bi bi-film me-1"></i>Có {{ $actor->movies->count() }} phim
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($actor->nationality)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-globe text-primary me-2"></i>
                                                    <span class="fw-medium">{{ $actor->nationality }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($actor->birth_date)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-calendar-event text-success me-2"></i>
                                                    <div>
                                                        <span class="fw-medium">{{ $actor->birth_date->format('d/m/Y') }}</span>
                                                        <small class="d-block text-muted">{{ $actor->birth_date->age }} tuổi</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-film text-info me-2"></i>
                                                <span class="fw-medium">{{ $actor->movies->count() }} phim</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($actor->is_active)
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i>Hoạt động
                                                </span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                                    <i class="bi bi-x-circle me-1"></i>Ngưng hoạt động
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $actor->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.actors.show', $actor->id) }}"

                                                   class="btn btn-sm rounded-3 view-detail-btn" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.actors.edit', $actor->id) }}"

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
                                        <td colspan="7" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-people-fill text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có diễn viên nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm diễn viên mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($actors->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $actors->firstItem() }}-{{ $actors->lastItem() }} trong tổng {{ $actors->total() }} diễn viên
                            </div>
                            <div>
                                {{ $actors->appends(['query' => request('query')])->links('pagination::bootstrap-5') }}
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
<style>
/* Modern UI Styles */
.actor-avatar {
    width: 45px;
    height: 45px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.actor-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.card {
    transition: all 0.3s ease;
    border: none !important;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Action Buttons */
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

/* EDIT BUTTON - MÀU ĐEN: */
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

/* ĐẢM BẢO ICON HIỂN THỊ ĐÚNG MÀU: */
.view-detail-btn i,
.edit-btn i {
    font-size: 14px;
    color: white !important;
}

/* Tooltip styling */
.tooltip {
    font-size: 12px;
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

.rounded-pill {
    border-radius: 50rem !important;
}

.rounded-4 {
    border-radius: 0.75rem !important;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
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

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 0.5rem;
    margin: 2px 0;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    transform: translateX(4px);
}

.input-group-lg > .form-control,
.input-group-lg > .form-select,
.input-group-lg > .input-group-text {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.alert {
    border-radius: 0.75rem;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.progress {
    background-color: rgba(0,0,0,0.05);
}

.bg-primary-subtle {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
}

@media (max-width: 768px) {
    .actor-avatar {
        width: 35px;
        height: 35px;
    }
    
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Khởi tạo tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection