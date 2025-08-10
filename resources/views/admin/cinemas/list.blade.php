@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-lg rounded-5 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-md rounded-circle bg-success bg-opacity-20 backdrop-blur">
                        <span class="avatar-title rounded-circle text-success">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold text-success">Thành công!</h6>
                    <p class="mb-0 text-dark">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Hero Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="hero-section position-relative overflow-hidden rounded-5 p-5">
                <div class="hero-bg"></div>
                <div class="hero-content position-relative z-index-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="hero-text">
                            <h1 class="display-5 fw-bold text-white mb-3">
                                <i class="bi bi-camera-reels me-3"></i>
                                Quản lý rạp chiếu
                            </h1>
                            <p class="lead text-white-50 mb-0">Hệ thống quản lý tập trung các rạp chiếu phim</p>
                        </div>
                        <div class="hero-actions d-flex gap-3">
                            <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                                <i class="bi bi-trash3 me-2"></i>
                                Thùng rác
                            </a>
                            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-light btn-lg rounded-pill px-4 shadow">
                                <i class="bi bi-plus-circle me-2 text-purple"></i>
                                <span class="text-purple fw-bold">Thêm rạp mới</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="hero-pattern"></div>
            </div>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="row mb-5">
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-purple">
                        <i class="bi bi-camera-reels"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $cinemas->total() }}</h3>
                        <p class="stats-label">Tổng rạp chiếu</p>
                        <div class="stats-trend">
                            <i class="bi bi-trending-up text-success"></i>
                            <span class="text-success">+12%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $cinemas->where('status', 'active')->count() }}</h3>
                        <p class="stats-label">Đang hoạt động</p>
                        <div class="stats-trend">
                            <i class="bi bi-trending-up text-success"></i>
                            <span class="text-success">+8%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $cinemas->where('status', 'inactive')->count() }}</h3>
                        <p class="stats-label">Tạm dừng</p>
                        <div class="stats-trend">
                            <i class="bi bi-dash text-warning"></i>
                            <span class="text-warning">0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-info">
                        <i class="bi bi-search"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ request('keyword') ? 'ON' : 'OFF' }}</h3>
                        <p class="stats-label">Tìm kiếm</p>
                        <div class="stats-trend">
                            @if(request('keyword'))
                                <i class="bi bi-search text-info"></i>
                                <span class="text-info">Active</span>
                            @else
                                <i class="bi bi-circle text-muted"></i>
                                <span class="text-muted">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-section rounded-4 p-4">
                <form action="{{ route('admin.cinemas.index') }}" method="GET" class="search-form">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="search-input-group">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" 
                                       name="keyword" 
                                       value="{{ request('keyword') }}" 
                                       class="search-input"
                                       placeholder="Tìm kiếm rạp chiếu theo tên, địa chỉ...">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-purple rounded-pill px-4">
                                    <i class="bi bi-search me-2"></i>Tìm kiếm
                                </button>
                                @if(request('keyword'))
                                    <a href="{{ route('admin.cinemas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="bi bi-x-circle me-2"></i>Xóa bộ lọc
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
    <div class="row">
        @forelse($cinemas as $cinema)
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="cinema-card h-100">
                    <div class="cinema-card-header">
                        @if($cinema->image_url)
                            <img src="{{ asset('assets/' . $cinema->image_url) }}" 
                                 alt="{{ $cinema->name }}" 
                                 class="cinema-image">
                        @else
                            <div class="cinema-placeholder">
                                <i class="bi bi-camera-reels"></i>
                            </div>
                        @endif
                        <div class="cinema-status">
                            @if($cinema->status == 'active')
                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle"></i> Hoạt động
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="bi bi-pause-circle"></i> Tạm dừng
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="cinema-card-body">
                        <h5 class="cinema-name">{{ $cinema->name }}</h5>
                        <div class="cinema-info">
                            <div class="info-item">
                                <i class="bi bi-geo-alt text-purple"></i>
                                <span>{{ Str::limit($cinema->address, 40, '...') }}</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-buildings text-info"></i>
                                <span>{{ $cinema->city->name ?? 'Chưa xác định' }}</span>
                            </div>
                            @if($cinema->hotline)
                                <div class="info-item">
                                    <i class="bi bi-telephone text-success"></i>
                                    <span>{{ $cinema->hotline }}</span>
                                </div>
                            @endif
                            @if($cinema->opening_hours)
                                <div class="info-item">
                                    <i class="bi bi-clock text-warning"></i>
                                    <span>{{ $cinema->opening_hours }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="cinema-card-footer">
                        <div class="action-buttons">
                            <a href="{{ route('admin.cinemas.show', $cinema->id) }}" 
                               class="btn btn-outline-purple btn-sm rounded-pill" 
                               title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" 
                               class="btn btn-outline-info btn-sm rounded-pill" 
                               title="Chỉnh sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.cinemas.destroy', $cinema->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa rạp chiếu này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm rounded-pill" 
                                        title="Xóa">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                        <div class="cinema-meta">
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i>
                                {{ $cinema->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state text-center py-5">
                    <div class="empty-icon">
                        <i class="bi bi-camera-reels"></i>
                    </div>
                    <h4 class="empty-title">Chưa có rạp chiếu nào</h4>
                    <p class="empty-description">Hãy thêm rạp chiếu đầu tiên để bắt đầu quản lý</p>
                    <a href="{{ route('admin.cinemas.create') }}" class="btn btn-purple rounded-pill px-4">
                        <i class="bi bi-plus-circle me-2"></i>Thêm rạp mới
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($cinemas->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="pagination-wrapper">
                    <div class="d-flex justify-content-between align-items-center">
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
    --purple-primary: #8b5cf6;
    --purple-secondary: #a78bfa;
    --purple-light: #c4b5fd;
    --purple-dark: #7c3aed;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--purple-primary) 0%, var(--purple-dark) 100%);
    min-height: 200px;
    position: relative;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
}

.hero-pattern {
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.z-index-2 {
    z-index: 2;
}

.text-purple {
    color: var(--purple-primary) !important;
}

/* Stats Cards */
.stats-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
}

.stats-card-body {
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.stats-icon {
    width: 4rem;
    height: 4rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stats-icon.bg-purple {
    background: linear-gradient(135deg, var(--purple-primary), var(--purple-dark));
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1f2937;
}

.stats-label {
    color: #6b7280;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.stats-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Search Section */
.search-section {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(139, 92, 246, 0.1);
    box-shadow: 0 4px 20px rgba(139, 92, 246, 0.1);
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
    font-size: 1.1rem;
}

.search-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #e5e7eb;
    border-radius: 2rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: var(--purple-primary);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
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
    box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    color: white;
}

/* Cinema Cards */
.cinema-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(139, 92, 246, 0.1);
    overflow: hidden;
}

.cinema-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(139, 92, 246, 0.15);
}

.cinema-card-header {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.cinema-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.cinema-card:hover .cinema-image {
    transform: scale(1.05);
}

.cinema-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--purple-light), var(--purple-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
}

.cinema-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.status-active {
    background: rgba(34, 197, 94, 0.9);
    color: white;
}

.status-inactive {
    background: rgba(156, 163, 175, 0.9);
    color: white;
}

.cinema-card-body {
    padding: 1.5rem;
}

.cinema-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
}

.cinema-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.info-item i {
    font-size: 1rem;
}

.cinema-card-footer {
    padding: 1.5rem;
    border-top: 1px solid #f3f4f6;
    display: flex;
    justify-content: between;
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
    background: rgba(255, 255, 255, 0.5);
    border-radius: 1.5rem;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--purple-light);
    margin-bottom: 1.5rem;
}

.empty-title {
    color: #1f2937;
    margin-bottom: 1rem;
}

.empty-description {
    color: #6b7280;
    margin-bottom: 2rem;
}

/* Pagination */
.pagination-wrapper {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

/* Avatar */
.avatar-md {
    width: 3rem;
    height: 3rem;
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
        padding: 2rem !important;
    }
    
    .hero-text h1 {
        font-size: 2rem;
    }
    
    .hero-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .stats-card-body {
        padding: 1.5rem;
        flex-direction: column;
        text-align: center;
    }
    
    .search-section {
        padding: 1.5rem !important;
    }
    
    .search-form .row {
        gap: 1rem;
    }
    
    .search-form .col-lg-4 {
        margin-top: 1rem;
    }
}
</style>
@endsection