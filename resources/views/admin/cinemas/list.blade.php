@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-lg rounded-4 mb-5" role="alert">
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="avatar-md rounded-circle bg-success bg-opacity-10">
                        <span class="avatar-title rounded-circle text-success">
                            <i class="bi bi-check-circle-fill fs-4"></i>
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

    <!-- Hero Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="hero-section position-relative overflow-hidden rounded-4 p-5">
                <div class="hero-bg"></div>
                <div class="hero-content position-relative z-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="hero-text">
                            <h1 class="display-5 fw-bold text-white mb-2">
                                <i class="bi bi-film me-3"></i> Quản lý rạp chiếu
                            </h1>
                            <p class="lead text-white-75 mb-0">Tối ưu hóa việc quản lý hệ thống rạp chiếu phim</p>
                        </div>
                        <div class="hero-actions d-flex gap-3 flex-wrap">
                            <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-outline-light rounded-pill px-4 shadow-sm">
                                <i class="bi bi-trash3-fill me-2"></i> Thùng rác
                            </a>
                            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-purple rounded-pill px-4 shadow-sm">
                                <i class="bi bi-plus-circle-fill me-2"></i> Thêm rạp mới
                            </a>
                        </div>
                    </div>
                </div>
                <div class="hero-pattern"></div>
            </div>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="row mb-5 g-4">
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-purple">
                        <i class="bi bi-film fs-2"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $cinemas->total() }}</h3>
                        <p class="stats-label">Tổng rạp chiếu</p>
                        <div class="stats-trend">
                            <i class="bi bi-arrow-up-circle text-success"></i>
                            <span class="text-success">+12%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-check-circle-fill fs-2"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $cinemas->where('status', 'active')->count() }}</h3>
                        <p class="stats-label">Đang hoạt động</p>
                        <div class="stats-trend">
                            <i class="bi bi-arrow-up-circle text-success"></i>
                            <span class="text-success">+8%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-pause-circle-fill fs-2"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $cinemas->where('status', 'inactive')->count() }}</h3>
                        <p class="stats-label">Tạm dừng</p>
                        <div class="stats-trend">
                            <i class="bi bi-dash-circle text-warning"></i>
                            <span class="text-warning">0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-info">
                        <i class="bi bi-search fs-2"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ request('keyword') ? 'ON' : 'OFF' }}</h3>
                        <p class="stats-label">Tìm kiếm</p>
                        <div class="stats-trend">
                            @if(request('keyword'))
                                <i class="bi bi-search text-info"></i>
                                <span class="text-info">Đang bật</span>
                            @else
                                <i class="bi bi-circle text-muted"></i>
                                <span class="text-muted">Đang tắt</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="search-section rounded-4 p-4 shadow-lg">
                <form action="{{ route('admin.cinemas.index') }}" method="GET" class="search-form">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-8 col-md-7">
                            <div class="search-input-group">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" 
                                       name="keyword" 
                                       value="{{ request('keyword') }}" 
                                       class="search-input"
                                       placeholder="Tìm kiếm theo tên rạp, địa chỉ...">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5">
                            <div class="d-flex gap-3 flex-wrap">
                                <button type="submit" class="btn btn-purple rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-search me-2"></i> Tìm kiếm
                                </button>
                                @if(request('keyword'))
                                    <a href="{{ route('admin.cinemas.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                                        <i class="bi bi-x-circle-fill me-2"></i> Xóa bộ lọc
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cinema Grid -->
    <div class="row g-4">
        @forelse($cinemas as $cinema)
            <div class="col-xl-4 col-lg-6">
                <div class="cinema-card h-100">
                    <div class="cinema-card-header">
                        @if($cinema->image_url)
                            <img src="{{ asset('assets/' . $cinema->image_url) }}" 
                                 alt="{{ $cinema->name }}" 
                                 class="cinema-image">
                        @else
                            <div class="cinema-placeholder">
                                <i class="bi bi-film fs-1"></i>
                            </div>
                        @endif
                        <div class="cinema-status">
                            @if($cinema->status == 'active')
                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle-fill me-1"></i> Hoạt động
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="bi bi-pause-circle-fill me-1"></i> Tạm dừng
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="cinema-card-body">
                        <h5 class="cinema-name">{{ $cinema->name }}</h5>
                        <div class="cinema-info">
                            <div class="info-item">
                                <i class="bi bi-geo-alt-fill text-purple"></i>
                                <span>{{ Str::limit($cinema->address, 35, '...') }}</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-buildings-fill text-info"></i>
                                <span>{{ $cinema->city->name ?? 'Chưa xác định' }}</span>
                            </div>
                            @if($cinema->hotline)
                                <div class="info-item">
                                    <i class="bi bi-telephone-fill text-success"></i>
                                    <span>{{ $cinema->hotline }}</span>
                                </div>
                            @endif
                            @if($cinema->opening_hours)
                                <div class="info-item">
                                    <i class="bi bi-clock-fill text-warning"></i>
                                    <span>{{ $cinema->opening_hours }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="cinema-card-footer">
                        <div class="action-buttons">
                            <a href="{{ route('admin.cinemas.show', $cinema->id) }}" 
                               class="btn btn-outline-purple btn-sm rounded-pill shadow-sm" 
                               title="Xem chi tiết" data-bs-toggle="tooltip">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" 
                               class="btn btn-outline-info btn-sm rounded-pill shadow-sm" 
                               title="Chỉnh sửa" data-bs-toggle="tooltip">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.cinemas.destroy', $cinema->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa rạp chiếu này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm rounded-pill shadow-sm" 
                                        title="Xóa" data-bs-toggle="tooltip">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                        <div class="cinema-meta">
                            <small class="text-muted">
                                <i class="bi bi-calendar3-event me-1"></i>
                                {{ $cinema->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state text-center py-5 rounded-4 shadow-lg">
                    <div class="empty-icon">
                        <i class="bi bi-film fs-1 text-purple"></i>
                    </div>
                    <h4 class="empty-title">Chưa có rạp chiếu nào</h4>
                    <p class="empty-description">Thêm rạp chiếu mới để bắt đầu quản lý hệ thống</p>
                    <a href="{{ route('admin.cinemas.create') }}" class="btn btn-purple rounded-pill px-4 shadow-sm">
                        <i class="bi bi-plus-circle-fill me-2"></i> Thêm rạp mới
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($cinemas->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="pagination-wrapper rounded-4 p-4 shadow-lg">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="pagination-info">
                            <span class="text-muted">
                                Hiển thị {{ $cinemas->firstItem() }}-{{ $cinemas->lastItem() }} 
                                trong tổng {{ $cinemas->total() }} rạp chiếu
                            </span>
                        </div>
                        <div class="pagination-links">
                            {{ $cinemas->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
:root {
    --purple-primary: #6B46C1; /* Modern Purple */
    --purple-secondary: #9F7AEA;
    --purple-light: #D6BCFA;
    --purple-dark: #553C9A;
    --purple-subtle: rgba(107, 70, 193, 0.1);
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--purple-primary) 0%, var(--purple-dark) 100%);
    min-height: 180px;
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="12" height="12" patternUnits="userSpaceOnUse"><path d="M 12 0 L 0 0 0 12" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.8;
}

.hero-pattern {
    position: absolute;
    top: -30%;
    right: -15%;
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.z-2 {
    z-index: 2;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

/* Stats Cards */
.stats-card {
    background: linear-gradient(145deg, #ffffff, #f9fafb);
    border-radius: 1rem;
    border: 1px solid var(--purple-subtle);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(107, 70, 193, 0.15);
}

.stats-card-body {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.stats-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stats-icon.bg-purple {
    background: linear-gradient(135deg, var(--purple-primary), var(--purple-dark));
}

.stats-number {
    font-size: 2.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.25rem;
}

.stats-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.stats-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Search Section */
.search-section {
    background: linear-gradient(145deg, #ffffff, #f9fafb);
    border: 1px solid var(--purple-subtle);
    box-shadow: 0 6px 20px rgba(107, 70, 193, 0.1);
}

.search-input-group {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--purple-primary);
    font-size: 1rem;
}

.search-input {
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 2px solid var(--purple-subtle);
    border-radius: 2rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    border-color: var(--purple-primary);
    box-shadow: 0 0 0 3px var(--purple-subtle);
    outline: none;
}

.btn-purple {
    background: linear-gradient(135deg, var(--purple-primary), var(--purple-dark));
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-purple:hover {
    background: linear-gradient(135deg, var(--purple-dark), var(--purple-primary));
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(107, 70, 193, 0.3);
    color: white;
}

/* Cinema Cards */
.cinema-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--purple-subtle);
    transition: all 0.3s ease;
    overflow: hidden;
}

.cinema-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(107, 70, 193, 0.15);
}

.cinema-card-header {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.cinema-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.cinema-card:hover .cinema-image {
    transform: scale(1.1);
}

.cinema-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--purple-light), var(--purple-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.cinema-status {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 1.5rem;
    font-size: 0.85rem;
    font-weight: 600;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.status-active {
    background: rgba(16, 185, 129, 0.9);
    color: white;
}

.status-inactive {
    background: rgba(107, 114, 128, 0.9);
    color: white;
}

.cinema-card-body {
    padding: 1.25rem;
}

.cinema-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.75rem;
}

.cinema-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #6b7280;
}

.info-item i {
    font-size: 1rem;
}

.cinema-card-footer {
    padding: 1.25rem;
    border-top: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-outline-purple {
    border-color: var(--purple-primary);
    color: var(--purple-primary);
}

.btn-outline-purple:hover {
    background: var(--purple-primary);
    border-color: var(--purple-primary);
    color: white;
}

.cinema-meta {
    margin-left: auto;
}

/* Empty State */
.empty-state {
    background: linear-gradient(145deg, #ffffff, #f9fafb);
    border: 1px solid var(--purple-subtle);
    box-shadow: 0 6px 20px rgba(107, 70, 193, 0.1);
}

.empty-icon {
    font-size: 3.5rem;
    color: var(--purple-primary);
    margin-bottom: 1rem;
}

.empty-title {
    color: #111827;
    margin-bottom: 0.75rem;
}

.empty-description {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

/* Pagination */
.pagination-wrapper {
    background: linear-gradient(145deg, #ffffff, #f9fafb);
    border: 1px solid var(--purple-subtle);
    box-shadow: 0 6px 20px rgba(107, 70, 193, 0.1);
}

/* Avatar */
.avatar-md {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        padding: 1.5rem;
    }
    
    .hero-text h1 {
        font-size: 1.75rem;
    }
    
    .hero-actions {
        flex-direction: column;
        width: 100%;
        gap: 1rem;
    }
    
    .stats-card-body {
        padding: 1.25rem;
        flex-direction: column;
        text-align: center;
    }
    
    .search-section {
        padding: 1.25rem;
    }
    
    .search-form .col-lg-4 {
        margin-top: 1rem;
    }

    .cinema-card-header {
        height: 150px;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.stats-card, .cinema-card, .search-section, .pagination-wrapper, .empty-state {
    animation: fadeIn 0.5s ease-out;
}
</style>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

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