@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="detail-header rounded-5 p-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-eye me-3"></i>
                            Chi tiết phòng chiếu
                        </h1>
                        <p class="lead text-white-50 mb-0">Thông tin chi tiết: {{ $room->name }}</p>
                    </div>
                    <div class="header-actions d-flex gap-3">
                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-light btn-lg rounded-pill px-4">
                            <i class="bi bi-pencil-square me-2 text-orange"></i>
                            <span class="text-orange fw-bold">Chỉnh sửa</span>
                        </a>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Information -->
    <div class="row mb-5">
        <div class="col-xl-8">
            <div class="info-card">
                <div class="info-header">
                    <div class="info-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="info-title">Thông tin phòng chiếu</h5>
                        <p class="info-subtitle">Chi tiết cấu hình và thông số</p>
                    </div>
                </div>
                
                <div class="info-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-door-open text-orange"></i>
                                    <span>Tên phòng</span>
                                </div>
                                <div class="detail-value">{{ $room->name }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-building text-primary"></i>
                                    <span>Rạp chiếu</span>
                                </div>
                                <div class="detail-value">{{ $room->cinema->name }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-grid-3x3 text-info"></i>
                                    <span>Loại phòng</span>
                                </div>
                                <div class="detail-value">{{ $room->roomType->name }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-people text-success"></i>
                                    <span>Sức chứa</span>
                                </div>
                                <div class="detail-value">{{ $room->capacity }} ghế</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-toggles text-warning"></i>
                                    <span>Trạng thái</span>
                                </div>
                                <div class="detail-value">
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
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-hash text-secondary"></i>
                                    <span>ID Phòng</span>
                                </div>
                                <div class="detail-value">#{{ $room->id }}</div>
                            </div>
                        </div>
                        
                        @if($room->description)
                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-card-text text-muted"></i>
                                        <span>Mô tả</span>
                                    </div>
                                    <div class="detail-value">{{ $room->description }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="stats-card mb-4">
                <div class="stats-header">
                    <h6 class="stats-title">
                        <i class="bi bi-graph-up me-2"></i>Thống kê nhanh
                    </h6>
                </div>
                <div class="stats-body">
                    <div class="stat-item">
                        <div class="stat-icon bg-orange">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $room->created_at->format('d/m/Y') }}</div>
                            <div class="stat-label">Ngày tạo</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon bg-info">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $room->updated_at->format('d/m/Y') }}</div>
                            <div class="stat-label">Cập nhật lần cuối</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon bg-success">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $room->created_at->diffForHumans() }}</div>
                            <div class="stat-label">Tạo cách đây</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Info Card -->
            <div class="related-card">
                <div class="related-header">
                    <h6 class="related-title">
                        <i class="bi bi-link-45deg me-2"></i>Thông tin liên quan
                    </h6>
                </div>
                <div class="related-body">
                    <div class="related-item">
                        <div class="related-label">Rạp chiếu</div>
                        <div class="related-value">
                            <a href="#" class="related-link">
                                <i class="bi bi-building"></i>
                                {{ $room->cinema->name }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="related-item">
                        <div class="related-label">Thành phố</div>
                        <div class="related-value">
                            <span class="related-text">
                                <i class="bi bi-geo-alt"></i>
                                {{ $room->cinema->city->name ?? 'Chưa xác định' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="related-item">
                        <div class="related-label">Loại phòng</div>
                        <div class="related-value">
                            <span class="related-text">
                                <i class="bi bi-grid-3x3"></i>
                                {{ $room->roomType->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seat Management Section -->
    <div class="row">
        <div class="col-12">
            <div class="seats-management-card">
                <div class="seats-header">
                    <div class="seats-title-section">
                        <h5 class="seats-title">
                            <i class="bi bi-grid-3x3-gap me-2"></i>
                            Quản lý ghế ngồi
                        </h5>
                        <p class="seats-subtitle">Cấu hình và sắp xếp ghế trong phòng chiếu</p>
                    </div>
                    <div class="seats-actions">
                        <button type="button" class="btn btn-outline-orange btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#previewSeatsModal">
                            <i class="bi bi-eye me-2"></i>Xem trước ghế
                        </button>
                        <button type="button" class="btn btn-orange btn-sm rounded-pill">
                            <i class="bi bi-plus-circle me-2"></i>Cấu hình ghế
                        </button>
                    </div>
                </div>
                
                <div class="seats-body">
                    <div class="seats-placeholder">
                        <div class="placeholder-icon">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </div>
                        <h6 class="placeholder-title">Chưa có cấu hình ghế</h6>
                        <p class="placeholder-description">Hãy thiết lập sơ đồ ghế cho phòng chiếu này</p>
                        <button type="button" class="btn btn-orange rounded-pill px-4">
                            <i class="bi bi-gear me-2"></i>Thiết lập ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview ghế -->
<div class="modal fade" id="previewSeatsModal" tabindex="-1" aria-labelledby="previewSeatsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title fw-bold" id="previewSeatsModalLabel">
                    <i class="bi bi-eye me-2 text-orange"></i>
                    Xem trước sơ đồ ghế - {{ $room->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center py-5">
                    <i class="bi bi-grid-3x3-gap text-muted" style="font-size: 4rem;"></i>
                    <h6 class="text-muted mt-3">Chưa có sơ đồ ghế</h6>
                    <p class="text-muted">Vui lòng cấu hình ghế trước khi xem</p>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Đóng
                </button>
                <button type="button" class="btn btn-orange rounded-pill px-4">
                    <i class="bi bi-gear me-2"></i>Cấu hình ghế
                </button>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --orange-primary: #f86c2c;
    --orange-secondary: #ff8547;
    --orange-light: #ffab7a;
    --orange-dark: #e55a1f;
}

/* Header */
.detail-header {
    background: linear-gradient(135deg, var(--orange-primary) 0%, var(--orange-dark) 100%);
    position: relative;
    overflow: hidden;
}

.detail-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(248, 108, 44, 0.1);
    overflow: hidden;
}

.info-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.info-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.info-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

.info-body {
    padding: 2rem;
}

.detail-item {
    margin-bottom: 1.5rem;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
    margin-left: 1.5rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.status-maintenance {
    background: rgba(251, 191, 36, 0.1);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.2);
}

.status-inactive {
    background: rgba(156, 163, 175, 0.1);
    color: #9ca3af;
    border: 1px solid rgba(156, 163, 175, 0.2);
}

/* Stats Card */
.stats-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(248, 108, 44, 0.1);
    overflow: hidden;
}

.stats-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

.stats-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0;
}

.stats-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.stat-icon.bg-orange {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
}

.stat-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Related Card */
.related-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(248, 108, 44, 0.1);
    overflow: hidden;
}

.related-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

.related-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0;
}

.related-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.related-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.related-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
}

.related-link {
    color: var(--orange-primary);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.related-link:hover {
    color: var(--orange-dark);
    transform: translateX(2px);
}

.related-text {
    color: #1f2937;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Seats Management */
.seats-management-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(248, 108, 44, 0.1);
    overflow: hidden;
}

.seats-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: between;
    align-items: center;
}

.seats-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.seats-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

.seats-actions {
    display: flex;
    gap: 0.75rem;
}

.seats-body {
    padding: 3rem 2rem;
}

.seats-placeholder {
    text-align: center;
    padding: 2rem;
}

.placeholder-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1.5rem;
}

.placeholder-title {
    color: #374151;
    margin-bottom: 1rem;
}

.placeholder-description {
    color: #6b7280;
    margin-bottom: 2rem;
}

/* Buttons */
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

/* Modal */
.modal-content {
    border-radius: 1.5rem;
}

.modal-header {
    border-bottom: 1px solid #e2e8f0;
}

.modal-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    border-top: 1px solid #e2e8f0;
    padding: 1rem 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .detail-header {
        padding: 2rem !important;
    }
    
    .header-content h1 {
        font-size: 1.75rem;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
        gap: 0.75rem;
    }
    
    .info-body, .stats-body, .related-body {
        padding: 1.5rem;
    }
    
    .seats-header {
        padding: 1rem 1.5rem;
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .seats-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .seats-body {
        padding: 2rem 1.5rem;
    }
}
</style>
@endsection