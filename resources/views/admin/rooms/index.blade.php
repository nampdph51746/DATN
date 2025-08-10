@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
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
                                <i class="fas fa-door-open me-3"></i>
                                Quản lý phòng chiếu
                            </h1>
                            <p class="lead text-white-50 mb-0">
                                <i class="fas fa-cog me-2"></i>
                                Hệ thống quản lý tập trung các phòng chiếu trong rạp
                            </p>
                        </div>
                        <div class="hero-actions d-flex gap-3">
                            @can('create room')
                            <a href="{{ route('admin.rooms.create') }}"
                               class="btn btn-light btn-lg rounded-pill px-4 shadow hero-btn">
                                <i class="fas fa-plus-circle me-2 text-orange"></i>
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

    <!-- Stats Dashboard -->
    <div class="row mb-5">
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon bg-orange">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $rooms->total() }}</h3>
                        <p class="stats-label">Tổng phòng chiếu</p>
                        <div class="stats-trend">
                            <i class="fas fa-trending-up text-success"></i>
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
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $rooms->where('status', 'active')->count() }}</h3>
                        <p class="stats-label">Đang hoạt động</p>
                        <div class="stats-trend">
                            <i class="fas fa-trending-up text-success"></i>
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
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $rooms->where('status', 'maintenance')->count() }}</h3>
                        <p class="stats-label">Đang bảo trì</p>
                        <div class="stats-trend">
                            <i class="fas fa-minus text-warning"></i>
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
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ request('search') ? 'ON' : 'OFF' }}</h3>
                        <p class="stats-label">Tìm kiếm</p>
                        <div class="stats-trend">
                            @if(request('search'))
                                <i class="fas fa-search text-info"></i>
                                <span class="text-info">Active</span>
                            @else
                                <i class="fas fa-circle text-muted"></i>
                                <span class="text-muted">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-section rounded-4 p-4">
                <div class="row align-items-center">
                    <!-- Search Box -->
                    <div class="col-lg-5">
                        <form method="GET" action="{{ route('admin.rooms.index') }}" class="search-form">
                            <div class="search-input-group">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="search-input"
                                       placeholder="Tìm kiếm phòng chiếu theo tên...">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Filter Dropdown -->
                    <div class="col-lg-4">
                        <form method="GET" action="{{ route('admin.rooms.index') }}">
                            <div class="filter-group">
                                <label class="filter-label">
                                    <i class="fas fa-filter me-2"></i>Lọc theo loại phòng
                                </label>
                                <select name="room_type_id" class="filter-select" onchange="this.form.submit()">
                                    <option value="">
                                        <i class="fas fa-list"></i> -- Tất cả loại phòng --
                                    </option>
                                    @foreach ($roomTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Clear Filter -->
                    <div class="col-lg-3 text-end">
                        @if(request('search') || request('room_type_id'))
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="fas fa-times me-2"></i>Xóa bộ lọc
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="fas fa-table me-2 text-orange"></i>
                            Danh sách phòng chiếu
                        </h5>
                        <div class="table-controls d-flex gap-2">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ $rooms->total() }} phòng
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 modern-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-4 py-3" style="width: 50px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                            <label class="form-check-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                        <i class="fas fa-door-open me-2 text-orange"></i>Tên phòng
                                    </th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                        <i class="fas fa-building me-2 text-info"></i>Rạp chiếu
                                    </th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                        <i class="fas fa-th-large me-2 text-success"></i>Loại phòng
                                    </th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                        <i class="fas fa-users me-2 text-primary"></i>Sức chứa
                                    </th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                        <i class="fas fa-signal me-2 text-warning"></i>Trạng thái
                                    </th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                        <i class="fas fa-hashtag me-2 text-muted"></i>ID
                                    </th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-dark text-center" style="width: 120px;">
                                        <i class="fas fa-cogs me-2 text-secondary"></i>Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $room)
                                <tr class="table-row">
                                    <td class="px-4 py-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="room{{ $room->id }}">
                                            <label class="form-check-label" for="room{{ $room->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="room-avatar me-3">
                                                <i class="fas fa-door-open"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-dark">{{ $room->name }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ $room->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="cinema-info">
                                            <span class="fw-medium text-dark">{{ $room->cinema->name }}</span>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                Cinema ID: {{ $room->cinema->id }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $room->roomType->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="capacity-display">
                                            <span class="fw-bold text-primary fs-5">{{ $room->capacity }}</span>
                                            <small class="text-muted d-block">ghế</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($room->status === 'active')
                                            <span class="status-badge status-active">
                                                <i class="fas fa-check-circle"></i>
                                                Hoạt động
                                            </span>
                                        @elseif ($room->status === 'maintenance')
                                            <span class="status-badge status-maintenance">
                                                <i class="fas fa-tools"></i>
                                                Bảo trì
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="fas fa-times-circle"></i>
                                                Ngưng hoạt động
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="id-badge">
                                            <i class="fas fa-hashtag me-1"></i>
                                            {{ $room->id }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.rooms.show', $room->id) }}"
                                               class="btn btn-sm rounded-3 view-detail-btn" 
                                               title="Xem chi tiết"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit room')
                                                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                                   class="btn btn-sm rounded-3 edit-btn" 
                                                   title="Chỉnh sửa"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-door-open"></i>
                                            </div>
                                            <h5 class="mt-3 text-muted">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Chưa có phòng chiếu nào
                                            </h5>
                                            <p class="text-muted mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Hãy thêm phòng chiếu đầu tiên để bắt đầu quản lý
                                            </p>
                                            @can('create room')
                                            <a href="{{ route('admin.rooms.create') }}" class="btn btn-orange rounded-pill">
                                                <i class="fas fa-plus-circle me-1"></i> Thêm phòng chiếu
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($rooms->hasPages())
                <div class="card-footer bg-transparent border-0 pt-0">
                    <div class="d-flex justify-content-center">
                        {{ $rooms->links('pagination::bootstrap-5') }}
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

.hero-btn {
    transition: all 0.3s ease;
    border: 2px solid rgba(255,255,255,0.2);
}

.hero-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(248, 108, 44, 0.3);
    border-color: rgba(255,255,255,0.4);
}

/* Stats Cards */
.stats-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1.5rem;
    transition: all 0.4s ease;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.stats-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 50px rgba(248, 108, 44, 0.15);
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
    border-radius: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-icon.bg-orange {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1f2937;
    background: linear-gradient(135deg, #1f2937, #374151);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
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
    backdrop-filter: blur(15px);
    border: 1px solid rgba(248, 108, 44, 0.1);
    box-shadow: 0 8px 30px rgba(248, 108, 44, 0.1);
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 1.2rem;
    color: var(--orange-primary);
    font-size: 1.1rem;
    z-index: 3;
}

.search-input {
    width: 100%;
    padding: 1.2rem 1.2rem 1.2rem 3.5rem;
    padding-right: 4.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 2rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.search-input:focus {
    outline: none;
    border-color: var(--orange-primary);
    box-shadow: 0 0 0 4px rgba(248, 108, 44, 0.1), 0 8px 25px rgba(248, 108, 44, 0.15);
    transform: translateY(-2px);
}

.search-btn {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    border: none;
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(248, 108, 44, 0.3);
}

.search-btn:hover {
    background: linear-gradient(135deg, var(--orange-dark), var(--orange-primary));
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(248, 108, 44, 0.4);
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
    padding: 1rem 1.2rem;
    border: 2px solid #e5e7eb;
    border-radius: 1.2rem;
    font-size: 0.95rem;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.filter-select:focus {
    outline: none;
    border-color: var(--orange-primary);
    box-shadow: 0 0 0 3px rgba(248, 108, 44, 0.1);
    transform: translateY(-1px);
}

/* Modern Table */
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.table-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.table-row:hover {
    background: linear-gradient(135deg, rgba(248, 108, 44, 0.03), rgba(248, 108, 44, 0.08));
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(248, 108, 44, 0.1);
}

.room-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 4px 15px rgba(248, 108, 44, 0.3);
}

.cinema-info {
    line-height: 1.4;
}

.capacity-display {
    text-align: center;
}

.status-badge {
    padding: 0.6rem 1.2rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.status-active {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status-maintenance {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.status-inactive {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.id-badge {
    background: #f3f4f6;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
}

/* Action Buttons */
.view-detail-btn {
    background-color: #6c757d !important;
    border: 1px solid #6c757d !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 38px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
}

.view-detail-btn:hover {
    background-color: #5a6268 !important;
    border-color: #545b62 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
}

.edit-btn {
    background-color: #212529 !important;
    border: 1px solid #212529 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 38px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 4px 10px rgba(33, 37, 41, 0.2);
}

.edit-btn:hover {
    background-color: #343a40 !important;
    border-color: #343a40 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(33, 37, 41, 0.3);
}

.view-detail-btn i,
.edit-btn i {
    font-size: 14px;
    color: white !important;
}

.btn-orange {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(248, 108, 44, 0.3);
}

.btn-orange:hover {
    background: linear-gradient(135deg, var(--orange-dark), var(--orange-primary));
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(248, 108, 44, 0.4);
    color: white;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--orange-light);
    margin-bottom: 1.5rem;
}

/* Form Controls */
.form-check-input:checked {
    background-color: var(--orange-primary);
    border-color: var(--orange-primary);
    box-shadow: 0 0 0 3px rgba(248, 108, 44, 0.1);
}

/* Tooltip styling */
.tooltip {
    font-size: 12px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        padding: 2rem !important;
        min-height: 150px;
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
        gap: 1rem;
    }

    .stats-icon {
        width: 3rem;
        height: 3rem;
        font-size: 1.25rem;
    }

    .stats-number {
        font-size: 2rem;
    }

    .search-section {
        padding: 1.5rem !important;
    }

    .search-section .row {
        gap: 1rem;
    }

    .modern-table {
        font-size: 0.875rem;
    }

    .view-detail-btn,
    .edit-btn {
        min-width: 32px;
        height: 28px;
    }

    .room-avatar {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
}

/* Loading Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.table-row {
    animation: fadeInUp 0.3s ease-out;
}

.table-row:nth-child(even) {
    animation-delay: 0.1s;
}

.table-row:nth-child(odd) {
    animation-delay: 0.2s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const roomCheckboxes = document.querySelectorAll('input[id^="room"]');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            roomCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Individual checkbox handling
    roomCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(roomCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(roomCheckboxes).some(cb => cb.checked);
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
        });
    });

    // Smooth scroll for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Auto-submit search after typing (debounced)
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 1000);
        });
    }
});
</script>
@endsection