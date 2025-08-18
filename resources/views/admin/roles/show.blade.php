@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-info rounded-4 shadow-lg overflow-hidden position-relative">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="fas fa-user-shield fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chi tiết vai trò</h2>
                                <p class="mb-0 opacity-90 fs-5">Thông tin chi tiết của vai trò: {{ $role->name }}</p>
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
                    <i class="fas fa-user-shield" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Role Info Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-user-shield text-info"></i>
                        Thông tin vai trò
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-xl rounded-circle bg-gradient-info mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-crown text-white fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">{{ $role->name }}</h4>
                        <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2 shadow mb-2">
                            <i class="fas fa-hashtag me-1"></i>ID: {{ $role->id }}
                        </span>
                    </div>
                    <div class="row g-3 text-start">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-primary me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-align-left"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Mô tả</small>
                                    <div class="fw-semibold">{{ $role->description ?: 'Chưa có mô tả' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-warning me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày tạo</small>
                                    <div class="fw-semibold">{{ $role->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-secondary me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Cập nhật cuối</small>
                                    <div class="fw-semibold">{{ $role->updated_at->format('d/m/Y H:i') }}</div>
                                    <small class="text-info">{{ $role->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        @can('edit role')
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-lg rounded-pill">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                        </a>
                        @endcan
                        @can('delete role')
                        <form action="{{ route('roles.softDelete', $role->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vai trò này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg rounded-pill w-100">
                                <i class="fas fa-trash me-2"></i>Xóa vai trò
                            </button>
                        </form>
                        @endcan
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-success-subtle border-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-key text-success"></i>
                            Danh sách quyền hạn
                        </h5>
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            {{ $role->permissions->count() }} quyền
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($role->permissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-bold"><i class="fas fa-key me-1"></i>Tên quyền</th>
                                        <th class="border-0 fw-bold"><i class="fas fa-calendar-plus me-1"></i>Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->permissions as $permission)
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                <i class="fas fa-check me-1"></i>{{ $permission->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $permission->created_at ? $permission->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-key text-muted fs-1 mb-3"></i>
                            <h6 class="text-muted">Vai trò này chưa có quyền nào</h6>
                            <p class="text-muted small mb-0">Thêm quyền cho vai trò này để sử dụng chức năng hệ thống</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Users Section -->
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-warning-subtle border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-users text-warning"></i>
                        Người dùng có vai trò này
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($role->users) && $role->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-bold"><i class="fas fa-user me-1"></i>Tên</th>
                                        <th class="border-0 fw-bold"><i class="fas fa-envelope me-1"></i>Email</th>
                                        <th class="border-0 fw-bold"><i class="fas fa-calendar me-1"></i>Ngày tham gia</th>
                                        <th class="border-0 fw-bold text-center"><i class="fas fa-tools me-1"></i>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users->take(10) as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    @if($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                             alt="{{ $user->name }}" 
                                                             class="rounded-circle"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="avatar-placeholder">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $user->name }}</h6>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted d-block">{{ $user->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('users.show', $user->id) }}" 
                                               class="btn btn-sm rounded-3 view-btn"
                                               title="Xem chi tiết" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($role->users->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Và {{ $role->users->count() - 10 }} người dùng khác...
                                </small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users text-muted fs-1 mb-3"></i>
                            <h6 class="text-muted">Chưa có người dùng nào</h6>
                            <p class="text-muted small mb-0">Vai trò này chưa được gán cho người dùng nào</p>
                        </div>
                    @endif
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
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
.user-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #17a2b8, #138496);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1rem;
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
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}
.bg-info-subtle {
    background-color: rgba(6, 182, 212, 0.1) !important;
    color: #17a2b8 !important;
}
.bg-success-subtle {
    background-color: rgba(16, 185, 129, 0.1) !important;
    color: #10b981 !important;
}
.bg-warning-subtle {
    background-color: rgba(245, 158, 11, 0.1) !important;
    color: #f59e0b !important;
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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
