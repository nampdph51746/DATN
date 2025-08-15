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

    <!-- Trash Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="trash-header rounded-5 p-5">
                <div class="trash-bg"></div>
                <div class="trash-content position-relative z-index-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="trash-text">
                            <h1 class="display-5 fw-bold text-white mb-3">
                                <i class="bi bi-trash3 me-3"></i>
                                Thùng rác - Rạp chiếu
                            </h1>
                            <p class="lead text-white-50 mb-0">Khôi phục hoặc xóa vĩnh viễn các rạp chiếu đã bị xóa</p>
                        </div>
                        <div class="trash-actions">
                            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-light btn-lg rounded-pill px-4 shadow">
                                <i class="bi bi-arrow-left me-2 text-danger"></i>
                                <span class="text-danger fw-bold">Quay lại</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="trash-pattern"></div>
            </div>
        </div>
    </div>

    <!-- Trash Stats -->
    <div class="row mb-5">
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="trash-stats-card h-100">
                <div class="trash-stats-body">
                    <div class="trash-stats-icon bg-danger">
                        <i class="bi bi-trash3"></i>
                    </div>
                    <div class="trash-stats-content">
                        <h3 class="trash-stats-number">{{ $cinemas->total() }}</h3>
                        <p class="trash-stats-label">Rạp đã xóa</p>
                        <div class="trash-stats-trend">
                            <i class="bi bi-trash text-danger"></i>
                            <span class="text-danger">Deleted</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-section rounded-4 p-4">
                <form action="{{ route('admin.cinemas.trash') }}" method="GET" class="search-form">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="search-input-group">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" 
                                       name="keyword" 
                                       value="{{ request('keyword') }}" 
                                       class="search-input"
                                       placeholder="Tìm kiếm rạp chiếu đã xóa...">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-danger rounded-pill px-4">
                                    <i class="bi bi-search me-2"></i>Tìm kiếm
                                </button>
                                @if(request('keyword'))
                                    <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-outline-secondary rounded-pill px-4">
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

    <!-- Deleted Cinemas Grid -->
    <div class="row">
        @forelse($cinemas as $cinema)
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="deleted-cinema-card h-100">
                    <div class="deleted-cinema-header">
                        @if($cinema->image_url)
                            <img src="{{ asset('assets/' . $cinema->image_url) }}" 
                                 alt="{{ $cinema->name }}" 
                                 class="deleted-cinema-image">
                        @else
                            <div class="deleted-cinema-placeholder">
                                <i class="bi bi-camera-reels"></i>
                            </div>
                        @endif
                        <div class="deleted-overlay">
                            <span class="deleted-badge">
                                <i class="bi bi-trash3"></i> Đã xóa
                            </span>
                        </div>
                    </div>
                    
                    <div class="deleted-cinema-body">
                        <h5 class="deleted-cinema-name">{{ $cinema->name }}</h5>
                        <div class="deleted-cinema-info">
                            <div class="info-item">
                                <i class="bi bi-geo-alt text-danger"></i>
                                <span>{{ Str::limit($cinema->address, 40, '...') }}</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-buildings text-secondary"></i>
                                <span>{{ $cinema->city->name ?? 'Chưa xác định' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-calendar-x text-danger"></i>
                                <span>Xóa: {{ $cinema->deleted_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="deleted-cinema-footer">
                        <div class="action-buttons">
                            <form action="{{ route('admin.cinemas.restore', $cinema->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn khôi phục rạp chiếu này?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-outline-success btn-sm rounded-pill" 
                                        title="Khôi phục">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Khôi phục
                                </button>
                            </form>
                            <form action="{{ route('admin.cinemas.forceDelete', $cinema->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn rạp chiếu này? Hành động này không thể hoàn tác!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm rounded-pill" 
                                        title="Xóa vĩnh viễn">
                                    <i class="bi bi-trash3 me-1"></i>Xóa vĩnh viễn
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-trash text-center py-5">
                    <div class="empty-icon">
                        <i class="bi bi-trash3"></i>
                    </div>
                    <h4 class="empty-title">Thùng rác trống</h4>
                    <p class="empty-description">Không có rạp chiếu nào bị xóa</p>
                    <a href="{{ route('admin.cinemas.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
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
                                trong tổng {{ $cinemas->total() }} rạp chiếu đã xóa
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
/* Trash Header */
.trash-header {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    position: relative;
    overflow: hidden;
    min-height: 200px;
}

.trash-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: inherit;
    z-index: 1;
}

.trash-content {
    position: relative;
    z-index: 2;
}

.trash-pattern {
    position: absolute;
    bottom: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"%3E%3Cpath fill="%23dc2626" d="M100 0L120 80H200L130 130L150 210L100 160L50 210L70 130L0 80H80L100 0Z" /%3E%3C/svg%3E') no-repeat;
    z-index: 0;
    opacity: 0.15;
}

/* Trash Stats Card */
.trash-stats-card {
    background: #fff;
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.trash-stats-card:hover {
    transform: translateY(-5px);
}

.trash-stats-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.trash-stats-content {
    text-align: center;
}

.trash-stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.trash-stats-label {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Search Section */
.search-section {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.search-input-group {
    position: relative;
}

.search-icon {
    position: absolute;
    top: 50%;
    left: 1rem;
    transform: translateY(-50%);
    color: #6c757d;
}

.search-input {
    padding-left: 3rem;
    height: 50px;
    border: 1px solid #ced4da;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.search-input:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
}

/* Deleted Cinema Card */
.deleted-cinema-card {
    background: #fff;
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.deleted-cinema-card:hover {
    transform: translateY(-5px);
}

.deleted-cinema-header {
    position: relative;
    overflow: hidden;
    height: 150px;
}

.deleted-cinema-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.deleted-cinema-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    font-size: 2rem;
    color: #adb5bd;
}

.deleted-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(220, 38, 38, 0.8);
    color: #fff;
    font-weight: 500;
    letter-spacing: 0.05em;
    opacity: 0;
    transition: opacity 0.3s;
}

.deleted-cinema-card:hover .deleted-cinema-image {
    transform: scale(1.1);
}

.deleted-cinema-card:hover .deleted-overlay {
    opacity: 1;
}

.deleted-cinema-body {
    padding: 1.5rem;
}

.deleted-cinema-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.deleted-cinema-info {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.info-item i {
    font-size: 1.25rem;
    margin-right: 0.5rem;
    color: #dc2626;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-rounded-pill {
    border-radius: 50rem;
}

/* Empty Trash */
.empty-trash {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-icon {
    font-size: 3rem;
    color: #adb5bd;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.empty-description {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 1.5rem;
}

/* Pagination */
.pagination-wrapper {
    padding: 1rem 0;
}

.pagination-info {
    font-size: 0.875rem;
    color: #6c757d;
}

.pagination-links {
    display: flex;
    gap: 0.5rem;
}

.pagination-links .page-link {
    display: block;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    background: #f8f9fa;
    color: #dc2626;
    transition: background 0.3s, color 0.3s;
}

.pagination-links .page-link:hover {
    background: #dc2626;
    color: #fff;
}

.pagination-links .page-link.active {
    background: #dc2626;
    color: #fff;
    pointer-events: none;
}
</style>
@endsection