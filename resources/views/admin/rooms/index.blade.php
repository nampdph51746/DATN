@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

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

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-teal) 100%);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            min-height: 220px;
            padding: 2.5rem;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="heroGrid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23heroGrid)"/></svg>');
            opacity: 0.5;
        }

        .hero-pattern {
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        .hero-decoration-1, .hero-decoration-2 {
            position: absolute;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            animation: pulse 5s ease-in-out infinite;
        }

        .hero-decoration-1 {
            top: 15%;
            left: 5%;
            width: 100px;
            height: 100px;
        }

        .hero-decoration-2 {
            bottom: 20%;
            right: 15%;
            width: 70px;
            height: 70px;
            animation-delay: 2s;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 3.5rem;
            background: rgba(255,255,255,0.9);
            border-radius: 12px;
            margin-right: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: var(--primary-orange);
            font-size: 1.6rem;
            transition: var(--transition);
        }

        .hero-icon-wrapper-small {
            width: 2.2rem;
            height: 2.2rem;
            font-size: 1rem;
            margin-right: 0.5rem;
            background: rgba(255,255,255,0.9);
            border-radius: 8px;
        }

        .hero-btn {
            background: var(--accent-yellow);
            color: #1a202c;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .hero-btn:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 111, 0, 0.3);
        }

        .hero-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .hero-btn:hover::before {
            left: 100%;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
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
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            background: linear-gradient(135deg, var(--primary-teal), var(--primary-orange));
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-orange);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #4a5568;
            font-weight: 600;
            font-size: 1rem;
        }

        .stats-trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Search Section */
        .search-section {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
        }

        .search-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input {
            border-radius: 50px;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 3rem;
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            color: var(--primary-teal);
            font-size: 1.2rem;
        }

        .search-btn {
            position: absolute;
            right: 0.5rem;
            background: var(--primary-orange);
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            color: white;
            transition: var(--transition);
        }

        .search-btn:hover {
            background: var(--primary-teal);
            transform: scale(1.1);
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .filter-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.5rem;
            transition: var(--transition);
        }

        .filter-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.2);
        }

        /* Table */
        .modern-table {
            background: white;
            border-radius: var(--border-radius);
        }

        .modern-table th, .modern-table td {
            padding: 1.2rem;
            vertical-align: middle;
        }

        .table-row:hover {
            background: rgba(0, 172, 193, 0.05);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-active {
            background: rgba(0, 172, 193, 0.1);
            color: var(--primary-teal);
        }

        .status-maintenance {
            background: rgba(255, 202, 40, 0.1);
            color: var(--accent-yellow);
        }

        .status-inactive {
            background: rgba(220, 53, 69, 0.1);
            color: #DC3545;
        }

        .view-detail-btn, .edit-btn {
            background: var(--primary-teal);
            color: white;
            border-radius: 8px;
            transition: var(--transition);
        }

        .view-detail-btn:hover, .edit-btn:hover {
            background: var(--primary-orange);
            transform: scale(1.1);
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(180deg); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.1; }
            50% { transform: scale(1.15); opacity: 0.25; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stats-card, .table-row {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem;
                min-height: 180px;
            }

            .hero-text h1 {
                font-size: 2rem;
            }

            .stats-card-body {
                padding: 1.5rem;
                gap: 1rem;
            }

            .stats-icon {
                width: 3rem;
                height: 3rem;
                font-size: 1.4rem;
            }

            .stats-number {
                font-size: 2rem;
            }
        }
    </style>

    <!-- Hero Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="hero-section position-relative overflow-hidden">
                <div class="hero-bg"></div>
                <div class="hero-content">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="hero-text">
                            <h1 class="display-5 fw-bold text-white mb-3">
                                <span class="hero-icon-wrapper">
                                    <i class="fas fa-theater-masks"></i>
                                </span>
                                Quản lý phòng chiếu
                            </h1>
                            <p class="lead text-white mb-0">
                                <span class="hero-icon-wrapper-small">
                                    <i class="fas fa-tachometer-alt"></i>
                                </span>
                                Hệ thống quản lý tập trung các phòng chiếu
                            </p>
                        </div>
                        <div class="hero-actions d-flex gap-3">
                            @can('create room')
                            <a href="{{ route('admin.rooms.create') }}"
                               class="btn hero-btn">
                                <i class="fas fa-plus-circle me-2"></i>
                                <span class="fw-bold">Thêm phòng mới</span>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="hero-pattern"></div>
                <div class="hero-decoration-1"></div>
                <div class="hero-decoration-2"></div>
            </div>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="row mb-5">
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-theater-masks"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $rooms->total() }}</h3>
                        <p class="stats-label">Tổng phòng chiếu</p>
                        <div class="stats-trend">
                            <i class="fas fa-trending-up text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $rooms->where('status', 'active')->count() }}</h3>
                        <p class="stats-label">Đang hoạt động</p>
                        <div class="stats-trend">
                            <i class="fas fa-trending-up text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $rooms->where('status', 'maintenance')->count() }}</h3>
                        <p class="stats-label">Đang bảo trì</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stats-card h-100">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ request('search') ? 'ON' : 'OFF' }}</h3>
                        <p class="stats-label">Tìm kiếm</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-section">
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
            <div class="card border-0 rounded-4 overflow-hidden">
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
                                        <i class="fas fa-theater-masks me-2 text-orange"></i>Tên phòng
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
                                                <i class="fas fa-theater-masks text-orange"></i>
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
                                               class="btn btn-sm view-detail-btn" 
                                               title="Xem chi tiết"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit room')
                                                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                                   class="btn btn-sm edit-btn" 
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
                                                <i class="fas fa-theater-masks text-orange"></i>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroPattern = document.querySelector('.hero-pattern');
        if (heroPattern) {
            heroPattern.style.transform = `translateY(${scrolled * 0.4}px) rotate(${scrolled * 0.08}deg)`;
        }
    });

    // Enhanced hover effects for stats cards
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255, 255, 255, 1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.background = 'white';
        });
    });

    // Auto-submit search with loading effect
    const searchInput = document.querySelector('input[name="search"]');
    const searchBtn = document.querySelector('.search-btn');
    if (searchInput && searchBtn) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            searchTimeout = setTimeout(() => {
                searchBtn.innerHTML = '<i class="fas fa-arrow-right"></i>';
                if (this.value.length > 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 1000);
        });
    }

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -30px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe table rows
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(row);
    });
});
</script>
@endsection