@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl px-4 py-4">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="detail-header rounded-5 p-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-eye me-3"></i>
                            Chi tiết rạp chiếu
                        </h1>
                        <p class="lead text-white-50 mb-0">Thông tin đầy đủ về rạp chiếu {{ $cinema->name }}</p>
                    </div>
                    <div class="header-actions d-flex gap-3">
                        <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" class="btn btn-light btn-lg rounded-pill px-4">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>
                            <span class="text-primary fw-bold">Chỉnh sửa</span>
                        </a>
                        <a href="{{ route('admin.cinemas.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-5 align-items-start">
        <!-- Left Column: Image & Basic Info -->
        <div class="col-lg-4">
            <!-- Cinema Image -->
            <div class="image-card rounded-4 mb-4">
                @if($cinema->image_url)
                    <img src="{{ asset('assets/' . $cinema->image_url) }}" 
                         alt="{{ $cinema->name }}" 
                         class="cinema-detail-image">
                @else
                    <div class="cinema-placeholder-detail">
                        <i class="bi bi-camera-reels"></i>
                        <span>Chưa có ảnh</span>
                    </div>
                @endif
                <div class="image-overlay">
                    <div class="status-badge-large">
                        @if($cinema->status == 'active')
                            <span class="badge bg-success bg-opacity-90 text-white px-4 py-3 rounded-pill">
                                <i class="bi bi-check-circle me-2"></i>Đang hoạt động
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-90 text-white px-4 py-3 rounded-pill">
                                <i class="bi bi-pause-circle me-2"></i>Tạm dừng
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="quick-stats-card rounded-4 mb-4">
                <div class="quick-stats-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up me-2 text-primary"></i>
                        Thống kê nhanh
                    </h5>
                </div>
                <div class="quick-stats-body">
                    <div class="stat-item">
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Ngày tạo</div>
                            <div class="stat-value">{{ $cinema->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon bg-success">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Cập nhật cuối</div>
                            <div class="stat-value">{{ $cinema->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon bg-info">
                            <i class="bi bi-buildings"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Thành phố</div>
                            <div class="stat-value">{{ $cinema->city->name ?? 'Chưa xác định' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Detailed Information -->
        <div class="col-lg-8">
            <!-- Cinema Name & Description -->
            <div class="info-card rounded-4 mb-4">
                <div class="info-header">
                    <div class="info-icon bg-purple">
                        <i class="bi bi-camera-reels"></i>
                    </div>
                    <div>
                        <h3 class="cinema-title">{{ $cinema->name }}</h3>
                        <p class="cinema-subtitle">{{ $cinema->city->name ?? 'Chưa xác định' }} • ID: #{{ $cinema->id }}</p>
                    </div>
                </div>
                
                @if($cinema->description)
                    <div class="info-body">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="bi bi-card-text me-2 text-primary"></i>Mô tả
                        </h6>
                        <p class="description-text">{{ $cinema->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Contact Information -->
            <div class="info-card rounded-4 mb-4">
                <div class="info-header">
                    <div class="info-icon bg-success">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <h5 class="info-title">Thông tin liên hệ</h5>
                        <p class="info-subtitle">Các phương thức liên hệ với rạp chiếu</p>
                    </div>
                </div>
                
                <div class="info-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="contact-content">
                                    <div class="contact-label">Địa chỉ</div>
                                    <div class="contact-value">{{ $cinema->address ?: 'Chưa cập nhật' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="contact-content">
                                    <div class="contact-label">Hotline</div>
                                    <div class="contact-value">
                                        @if($cinema->hotline)
                                            <a href="tel:{{ $cinema->hotline }}" class="contact-link">{{ $cinema->hotline }}</a>
                                        @else
                                            Chưa cập nhật
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="contact-content">
                                    <div class="contact-label">Email</div>
                                    <div class="contact-value">
                                        @if($cinema->email)
                                            <a href="mailto:{{ $cinema->email }}" class="contact-link">{{ $cinema->email }}</a>
                                        @else
                                            Chưa cập nhật
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="contact-content">
                                    <div class="contact-label">Giờ hoạt động</div>
                                    <div class="contact-value">{{ $cinema->opening_hours ?: 'Chưa cập nhật' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location & Map -->
            @if($cinema->map_url)
                <div class="info-card rounded-4 mb-4">
                    <div class="info-header">
                        <div class="info-icon bg-info">
                            <i class="bi bi-map"></i>
                        </div>
                        <div>
                            <h5 class="info-title">Vị trí & Bản đồ</h5>
                            <p class="info-subtitle">Xem vị trí trên Google Maps</p>
                        </div>
                    </div>
                    
                    <div class="info-body">
                        <div class="map-container">
                            <a href="{{ $cinema->map_url }}" target="_blank" class="map-link">
                                <div class="map-placeholder">
                                    <i class="bi bi-map fs-1 text-info"></i>
                                    <h6 class="mt-3 mb-2">Xem trên Google Maps</h6>
                                    <p class="text-muted small">Click để mở bản đồ trong tab mới</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- System Information -->
            <div class="info-card rounded-4">
                <div class="info-header">
                    <div class="info-icon bg-secondary">
                        <i class="bi bi-gear"></i>
                    </div>
                    <div>
                        <h5 class="info-title">Thông tin hệ thống</h5>
                        <p class="info-subtitle">Dữ liệu kỹ thuật và cập nhật</p>
                    </div>
                </div>
                
                <div class="info-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="system-item">
                                <span class="system-label">ID rạp chiếu:</span>
                                <span class="system-value">#{{ $cinema->id }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="system-item">
                                <span class="system-label">Trạng thái:</span>
                                <span class="system-value">
                                    @if($cinema->status == 'active')
                                        <span class="text-success fw-bold">Hoạt động</span>
                                    @else
                                        <span class="text-warning fw-bold">Tạm dừng</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="system-item">
                                <span class="system-label">Ngày tạo:</span>
                                <span class="system-value">{{ $cinema->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="system-item">
                                <span class="system-label">Cập nhật cuối:</span>
                                <span class="system-value">{{ $cinema->updated_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --purple-primary: #8b5cf6;
    --purple-secondary: #a78bfa;
    --purple-light: #c4b5fd;
    --purple-dark: #7c3aed;
}

/* Header */
.detail-header {
    background: linear-gradient(135deg, var(--purple-primary) 0%, var(--purple-dark) 100%);
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

/* Image Card */
.image-card {
    position: relative;
    overflow: hidden;
    background: white;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.1);
}

.cinema-detail-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.cinema-placeholder-detail {
    width: 100%;
    height: 300px;
    background: linear-gradient(135deg, var(--purple-light), var(--purple-secondary));
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.cinema-placeholder-detail span {
    font-size: 1rem;
    margin-top: 1rem;
    font-weight: 600;
}

.image-overlay {
    position: absolute;
    bottom: 1rem;
    left: 1rem;
    right: 1rem;
    display: flex;
    justify-content: center;
}

/* Quick Stats */
.quick-stats-card {
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(139, 92, 246, 0.1);
    overflow: hidden;
}

.quick-stats-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

.quick-stats-body {
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
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

/* Info Cards */
.info-card {
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(139, 92, 246, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(139, 92, 246, 0.15);
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
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.info-icon.bg-purple {
    background: linear-gradient(135deg, var(--purple-primary), var(--purple-dark));
}

.cinema-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.cinema-subtitle {
    color: #6b7280;
    margin-bottom: 0;
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

.description-text {
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 0;
}

/* Contact Items */
.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 1rem;
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: #f3f4f6;
    transform: translateY(-1px);
}

.contact-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: var(--purple-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.contact-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.contact-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

.contact-link {
    color: var(--purple-primary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-link:hover {
    color: var(--purple-dark);
}

/* Map */
.map-container {
    border-radius: 1rem;
    overflow: hidden;
}

.map-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.map-placeholder {
    height: 200px;
    background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.map-placeholder:hover {
    background: linear-gradient(135deg, #b3e5fc 0%, #81d4fa 100%);
}

/* System Info */
.system-item {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.system-item:last-child {
    border-bottom: none;
}

.system-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.system-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
    margin-left: auto;
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
        margin-top: 1rem;
    }
    
    .cinema-detail-image,
    .cinema-placeholder-detail {
        height: 200px;
    }
    
    .info-header {
        padding: 1rem 1.5rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .info-body {
        padding: 1.5rem;
    }
    
    .quick-stats-body {
        padding: 1rem;
    }
}
</style>
@endsection