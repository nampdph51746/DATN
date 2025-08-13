@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-info rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="fas fa-user-circle fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chi tiết khách hàng</h2>
                                <p class="mb-0 opacity-90 fs-5">Thông tin chi tiết của khách hàng: {{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-info rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="fas fa-info-circle me-2"></i>
                                Chi tiết
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="fas fa-user-circle" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- User Image & Basic Info -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle text-info"></i>
                        Thông tin khách hàng
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img src="{{ $user->avatar_url ? Storage::url($user->avatar_url) : asset('assets/images/user-placeholder.png') }}" 
                             alt="{{ $user->name }}" 
                             class="img-fluid rounded-4 shadow-lg" 
                             style="width: 100%; max-width: 220px; height: 220px; object-fit: cover; border: 4px solid #fff;">
                        <!-- Status Badge -->
                        <div class="position-absolute top-3 end-3">
                            @if($user->status && $user->status->value === 'active')
                                <span class="badge bg-success rounded-pill px-3 py-2 shadow">
                                    <i class="fas fa-check-circle me-1"></i>Hoạt động
                                </span>
                            @elseif($user->status && $user->status->value === 'inactive')
                                <span class="badge bg-secondary rounded-pill px-3 py-2 shadow">
                                    <i class="fas fa-user-slash me-1"></i>Không hoạt động
                                </span>
                            @elseif($user->status && $user->status->value === 'suspended')
                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow">
                                    <i class="fas fa-ban me-1"></i>Tạm khóa
                                </span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow">
                                    <i class="fas fa-question-circle me-1"></i>Không xác định
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <h4 class="fw-bold text-dark mb-3">{{ $user->name }}</h4>
                    
                    <div class="row g-3 text-start">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-primary me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Email</small>
                                    <div class="fw-semibold">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-success me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Số điện thoại</small>
                                    <div class="fw-semibold">{{ $user->phone_number ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-info me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Địa chỉ</small>
                                    <div class="fw-semibold">{{ $user->address ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-warning me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-birthday-cake"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày sinh</small>
                                    <div class="fw-semibold">
                                        {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-secondary me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày tạo</small>
                                    <div class="fw-semibold">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-lg rounded-pill">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-primary-subtle border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-id-card text-primary"></i>
                        Thông tin chi tiết
                    </h5>
                </div>
                <div class="card-body py-2">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="px-0"><span class="fw-semibold text-dark"><i class="fas fa-user-tag me-2 text-primary"></i>Vai trò</span></td>
                                    <td class="text-dark fw-medium px-0">
                                        @php $role = $user->roles->first(); @endphp
                                        {{ $role ? $role->name : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-0"><span class="fw-semibold text-dark"><i class="fas fa-medal me-2 text-success"></i>Cấp bậc</span></td>
                                    <td class="text-dark fw-medium px-0">
                                        {{ $user->customerRank ? $user->customerRank->name : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-0"><span class="fw-semibold text-dark"><i class="fas fa-envelope me-2 text-info"></i>Email xác thực</span></td>
                                    <td class="text-dark fw-medium px-0">
                                        @if(!is_null($user->email_verified_at))
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Đã xác thực</span>
                                        @else
                                            <span class="badge bg-warning"><i class="fas fa-times"></i> Chưa xác thực</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-0"><span class="fw-semibold text-dark"><i class="fas fa-calendar-plus me-2 text-secondary"></i>Ngày tạo</span></td>
                                    <td class="text-dark fw-medium px-0">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-0"><span class="fw-semibold text-dark"><i class="fas fa-calendar-check me-2 text-warning"></i>Cập nhật lần cuối</span></td>
                                    <td class="text-dark fw-medium px-0">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-0"><span class="fw-semibold text-dark"><i class="fas fa-sign-in-alt me-2 text-info"></i>Đăng nhập cuối</span></td>
                                    <td class="text-dark fw-medium px-0">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Activity Timeline -->
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-warning-subtle border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-clock text-warning"></i>
                        Lịch sử hoạt động
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Tạo tài khoản</h6>
                                <p class="mb-0 text-muted">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                        @if($user->updated_at && $user->updated_at != $user->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Cập nhật lần cuối</h6>
                                <p class="mb-0 text-muted">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($user->last_login_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Đăng nhập cuối</h6>
                                <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
.avatar-xl {
    width: 5rem;
    height: 5rem;
}
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
.timeline {
    position: relative;
    padding-left: 2rem;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #e9ecef, #dee2e6);
}
.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}
.timeline-marker {
    position: absolute;
    left: -2.25rem;
    top: 0.25rem;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.timeline-content {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border-left: 4px solid #dee2e6;
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
.card {
    transition: all 0.3s ease;
    border: none;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.sticky-top {
    top: 1rem;
}
.bg-primary-subtle {
    background-color: rgba(33, 150, 243, 0.1) !important;
}
.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    .card-body {
        padding: 1.5rem !important;
    }
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
    .timeline {
        padding-left: 1.5rem;
    }
    .timeline::before {
        left: 0.5rem;
    }
    .timeline-marker {
        left: -1.75rem;
        width: 1rem;
        height: 1rem;
    }
}
</style>
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection