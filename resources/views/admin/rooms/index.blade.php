@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Alert Messages -->
    @include('admin.partials.notifications')

    <!-- Hero Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="hero-section position-relative overflow-hidden rounded-5 p-5">
                <div class="hero-bg"></div>
                <div class="hero-content position-relative z-index-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="hero-text">
                            <h1 class="display-5 fw-bold text-white mb-3">
                                <i class="bi bi-door-open me-3"></i>
                                Quản lý phòng chiếu
                            </h1>
                            <p class="lead text-white-50 mb-0">Hệ thống quản lý tập trung các phòng chiếu trong rạp
                            </p>
                        </div>
                        <div class="hero-actions d-flex gap-3">
                            @can('create room')
                            <a href="{{ route('admin.rooms.create') }}"
                                class="btn btn-light btn-lg rounded-pill px-4 shadow">
                                <i class="bi bi-plus-circle me-2 text-orange"></i>
                                <span class="text-orange fw-bold">Thêm phòng mới</span>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="hero-pattern"></div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-section rounded-4 p-4">
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <form method="GET" action="{{ route('admin.rooms.index') }}" class="search-form">
                            <div class="search-input-group">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" name="search" value="{{ request('search') }}" class="search-input"
                                    placeholder="Tìm kiếm phòng chiếu...">
                                <button type="submit" class="search-btn">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4">
                        <form method="GET" action="{{ route('admin.rooms.index') }}">
                            <div class="filter-group">
                                <label class="filter-label">
                                    <i class="bi bi-funnel me-2"></i>Lọc theo loại phòng
                                </label>
                                <select name="room_type_id" class="filter-select" onchange="this.form.submit()">
                                    <option value="">-- Tất cả loại phòng --</option>
                                    @foreach ($roomTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 text-end">
                        <div class="stats-summary">
                            <span class="stats-item">
                                <i class="bi bi-door-open text-orange"></i>
                                <strong>{{ $rooms->total() }}</strong> phòng
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms Grid -->
    <div class="row">
        @forelse($rooms as $room)
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="room-card h-100">
                <div class="room-card-header">
                    <div class="room-icon">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="room-status">
                        @if($room->status === 'active')
                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle"></i> Hoạt động
                        </span>
                        @elseif($room->status === 'maintenance')
                        <span class="status-badge status-maintenance">
                            <i class="bi bi-tools"></i> Bảo trì
                        </span>
                        @else
                        <span class="status-badge status-inactive">
                            <i class="bi bi-x-circle"></i> Không hoạt động
                        </span>
                        @endif
                    </div>
                </div>

                <div class="room-card-body">
                    <h5 class="room-name">{{ $room->name }}</h5>
                    <div class="room-info">
                        <div class="info-item">
                            <i class="bi bi-building text-orange"></i>
                            <span>{{ $room->cinema->name }}</span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-grid-3x3 text-info"></i>
                            <span>{{ $room->roomType->name }}</span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-people text-success"></i>
                            <span>{{ $room->capacity }} ghế</span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-hash text-muted"></i>
                            <span>ID: {{ $room->id }}</span>
                        </div>
                    </div>
                </div>

                <div class="room-card-footer">
                    <div class="action-buttons">
                        <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-outline-orange btn-sm rounded-pill"
                            title="Xem chi tiết">
                            <i class="bi bi-eye"></i>
                        </a>
                        @can('edit room')
                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-outline-info btn-sm rounded-pill"
                            title="Chỉnh sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        @endcan
                    </div>
                    <div class="room-meta">
                        <small class="text-muted">
                            <i class="bi bi-calendar3"></i>
                            {{ $room->created_at->format('d/m/Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state text-center py-5">
                <div class="empty-icon">
                    <i class="bi bi-door-open"></i>
                </div>
                <h4 class="empty-title">Chưa có phòng chiếu nào</h4>
                <p class="empty-description">Hãy thêm phòng chiếu đầu tiên để bắt đầu quản lý</p>
                @can('create room')
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-orange rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Thêm phòng mới
                </a>
                @endcan
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($rooms->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <div class="pagination-wrapper">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-info">
                        <span class="text-muted">
                            Hiển thị {{ $rooms->firstItem() }}-{{ $rooms->lastItem() }}
                            trong tổng {{ $rooms->total() }} phòng chiếu
                        </span>
                    </div>
                    <div class="pagination-links">
                        {{ $rooms->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
:root {
    --orange-primary: #f86c2c;
    --orange-secondary: #ff8547;
    --orange-light: #ffab7a;
    --orange-dark: #e55a1f;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--orange-primary) 0%, var(--orange-dark) 100%);
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

.text-orange {
    color: var(--orange-primary) !important;
}

/* Filter Section */
.filter-section {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(248, 108, 44, 0.1);
    box-shadow: 0 4px 20px rgba(248, 108, 44, 0.1);
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 1rem;
    color: var(--orange-primary);
    font-size: 1.1rem;
    z-index: 3;
}

.search-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    padding-right: 4rem;
    border: 2px solid #e5e7eb;
    border-radius: 2rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: var(--orange-primary);
    box-shadow: 0 0 0 3px rgba(248, 108, 44, 0.1);
}

.search-btn {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    background: var(--orange-primary);
    border: none;
    border-radius: 50%;
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: var(--orange-dark);
    transform: translateY(-50%) scale(1.05);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 1rem;
    font-size: 0.95rem;
    background: white;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: var(--orange-primary);
    box-shadow: 0 0 0 3px rgba(248, 108, 44, 0.1);
}

.stats-summary {
    display: flex;
    justify-content: end;
    gap: 1rem;
}

.stats-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    color: #374151;
}

/* Room Cards */
.room-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(248, 108, 44, 0.1);
    overflow: hidden;
}

.room-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(248, 108, 44, 0.15);
}

.room-card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    display: flex;
    justify-content: between;
    align-items: center;
    position: relative;
}

.room-icon {
    width: 4rem;
    height: 4rem;
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 4px 12px rgba(248, 108, 44, 0.3);
}

.room-status {
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
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-active {
    background: rgba(34, 197, 94, 0.9);
    color: white;
}

.status-maintenance {
    background: rgba(251, 191, 36, 0.9);
    color: white;
}

.status-inactive {
    background: rgba(156, 163, 175, 0.9);
    color: white;
}

.room-card-body {
    padding: 1.5rem;
}

.room-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
}

.room-info {
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
    width: 1.2rem;
    text-align: center;
}

.room-card-footer {
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

.btn-orange {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-orange:hover {
    background: linear-gradient(135deg, var(--orange-dark), var(--orange-primary));
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(248, 108, 44, 0.3);
    color: white;
}

.btn-outline-orange {
    border-color: var(--orange-primary);
    color: var(--orange-primary);
}

.btn-outline-orange:hover {
    background: var(--orange-primary);
    border-color: var(--orange-primary);
    color: white;
}

.room-meta {
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
    color: var(--orange-light);
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

    .filter-section {
        padding: 1.5rem !important;
    }

    .filter-section .row {
        gap: 1rem;
    }

    .stats-summary {
        justify-content: start;
        margin-top: 1rem;
    }
}
</style>
@endsection