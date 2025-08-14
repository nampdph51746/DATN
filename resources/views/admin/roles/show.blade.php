@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Simple Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="simple-header text-center py-4">
                <h1 class="display-6 fw-bold text-orange mb-0">
                    <i class="fas fa-user-shield me-3"></i>
                    Chi tiết vai trò
                </h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Role Info Card -->
        <div class="col-xl-6 col-lg-8 mx-auto">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin vai trò
                        </h5>
                        <div class="role-status-badge">
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-crown me-1"></i>
                                {{ ucfirst($role->name) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="role-info-grid">
                        <!-- Role Name -->
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-tag text-primary me-2"></i>
                                Tên vai trò
                            </div>
                            <div class="info-value">
                                <span class="role-name-highlight">{{ $role->name }}</span>
                            </div>
                        </div>

                        <!-- Role ID -->
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-hashtag text-info me-2"></i>
                                ID vai trò
                            </div>
                            <div class="info-value">
                                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                    #{{ $role->id }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-align-left text-success me-2"></i>
                                Mô tả
                            </div>
                            <div class="info-value">
                                <div class="description-box">
                                    {{ $role->description ?: 'Chưa có mô tả' }}
                                </div>
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-plus text-warning me-2"></i>
                                Ngày tạo
                            </div>
                            <div class="info-value">
                                <div class="date-info">
                                    <span class="fw-bold">{{ $role->created_at->format('d/m/Y') }}</span>
                                    <small class="text-muted d-block">{{ $role->created_at->format('H:i:s') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Updated Date -->
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-check text-secondary me-2"></i>
                                Cập nhật cuối
                            </div>
                            <div class="info-value">
                                <div class="date-info">
                                    <span class="fw-bold">{{ $role->updated_at->format('d/m/Y') }}</span>
                                    <small class="text-muted d-block">{{ $role->updated_at->format('H:i:s') }}</small>
                                    <small class="text-info">{{ $role->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Card (nếu có) -->
        @if(isset($role->permissions) && $role->permissions->count() > 0)
        <div class="col-xl-6 col-lg-8 mx-auto">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-success text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-key me-2"></i>
                        Quyền hạn ({{ $role->permissions->count() }})
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="permissions-grid">
                        @foreach($role->permissions as $permission)
                            <div class="permission-item">
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                    <i class="fas fa-check me-1"></i>
                                    {{ $permission->name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Users with this Role Card (nếu có) -->
        @if(isset($role->users) && $role->users->count() > 0)
        <div class="col-xl-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-warning text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-users me-2"></i>
                        Người dùng có vai trò này ({{ $role->users->count() }})
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover modern-table mb-0">
                            <thead class="table-header-modern">
                                <tr>
                                    <th class="border-0 px-3 py-3">
                                        <i class="fas fa-user me-1 text-primary"></i>Tên
                                    </th>
                                    <th class="border-0 px-3 py-3">
                                        <i class="fas fa-envelope me-1 text-success"></i>Email
                                    </th>
                                    <th class="border-0 px-3 py-3">
                                        <i class="fas fa-calendar me-1 text-warning"></i>Ngày tham gia
                                    </th>
                                    <th class="border-0 px-3 py-3 text-center">
                                        <i class="fas fa-tools me-1 text-dark"></i>Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users->take(10) as $user)
                                    <tr class="table-row">
                                        <td class="px-3 py-3">
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
                                        <td class="px-3 py-3">
                                            <span class="text-dark">{{ $user->email }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="fw-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted d-block">{{ $user->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td class="px-3 py-3 text-center">
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
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="action-buttons text-center">
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg rounded-pill me-3">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
                @can('edit role')
                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-warning btn-lg rounded-pill me-3">
                        <i class="fas fa-edit me-2"></i>
                        Chỉnh sửa
                    </a>
                @endcan
                @can('delete role')
                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Bạn có chắc muốn xóa vai trò này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-lg rounded-pill">
                            <i class="fas fa-trash me-2"></i>
                            Xóa vai trò
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
:root {
    --orange-primary: #ff6b35;
    --orange-secondary: #ff8c42;
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --secondary-color: #6b7280;
    --dark-color: #1f2937;
}

/* Simple Header với màu cam */
.simple-header {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
    border-radius: 1rem;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    margin-bottom: 2rem;
}

.text-orange {
    color: white !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.simple-header i {
    background: rgba(255,255,255,0.2);
    padding: 0.5rem;
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    vertical-align: middle;
}

.simple-header:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
    transition: all 0.3s ease;
}

/* Modern Cards */
.modern-card {
    border: none !important;
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12) !important;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
}

.bg-gradient-success {
    background: linear-gradient(135deg, var(--success-color), #059669);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
}

/* Role Info Grid */
.role-info-grid {
    display: grid;
    gap: 2rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 1rem;
    border-left: 4px solid var(--primary-color);
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #f1f5f9;
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);
}

.info-label {
    font-weight: 600;
    color: var(--dark-color);
    min-width: 140px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}

.info-value {
    flex: 1;
    margin-left: 1rem;
}

.role-name-highlight {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-color);
    text-transform: capitalize;
}

.description-box {
    padding: 1rem;
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    line-height: 1.6;
    color: var(--dark-color);
    max-height: 150px;
    overflow-y: auto;
}

.date-info {
    color: var(--dark-color);
}

/* Badges */
.badge {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 2rem;
}

.bg-info-subtle {
    background-color: rgba(6, 182, 212, 0.1) !important;
    color: var(--info-color) !important;
}

.bg-success-subtle {
    background-color: rgba(16, 185, 129, 0.1) !important;
    color: var(--success-color) !important;
}

/* Permissions Grid */
.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.permission-item {
    transition: all 0.3s ease;
}

.permission-item:hover {
    transform: translateY(-2px);
}

/* Modern Table */
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
}

.table-header-modern {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.table-header-modern th {
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.8rem;
    color: var(--dark-color);
}

.table-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.table-row:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.03), rgba(59, 130, 246, 0.08));
    transform: translateX(3px);
    box-shadow: 0 2px 10px rgba(59, 130, 246, 0.1);
}

/* User Avatar */
.user-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1rem;
}

/* Action Buttons */
.view-btn {
    background-color: #06b6d4 !important;
    border: 1px solid #06b6d4 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 34px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border-radius: 6px;
    box-shadow: 0 3px 8px rgba(6, 182, 212, 0.2);
}

.view-btn:hover {
    background-color: #0891b2 !important;
    border-color: #0891b2 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
}

/* Buttons */
.btn {
    border-radius: 2rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-secondary {
    background: linear-gradient(135deg, var(--secondary-color), #4b5563);
    border-color: var(--secondary-color);
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
    border-color: var(--warning-color);
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-color), #dc2626);
    border-color: var(--danger-color);
}

/* Action Buttons Section */
.action-buttons {
    padding: 2rem;
    background: #f8fafc;
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
}

/* Responsive Design */
@media (max-width: 768px) {
    .simple-header {
        padding: 2rem !important;
    }
    
    .simple-header h1 {
        font-size: 2rem;
    }
    
    .simple-header i {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1.2rem;
    }

    .info-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .info-label {
        min-width: auto;
        margin-bottom: 0.5rem;
    }

    .info-value {
        margin-left: 0;
        width: 100%;
    }

    .permissions-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .action-buttons .btn:last-child {
        margin-bottom: 0;
    }
}

/* Animation effects */
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

.modern-card {
    animation: fadeInUp 0.6s ease-out;
}

.modern-card:nth-child(2) {
    animation-delay: 0.2s;
}

.modern-card:nth-child(3) {
    animation-delay: 0.4s;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Staggered animation for info items
    const infoItems = document.querySelectorAll('.info-item');
    infoItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.style.animation = 'fadeInUp 0.6s ease-out both';
    });

    // Animation for permission items
    const permissionItems = document.querySelectorAll('.permission-item');
    permissionItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.05}s`;
        item.style.animation = 'fadeInUp 0.4s ease-out both';
    });

    // Animation for table rows
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
        row.style.animation = 'fadeInUp 0.6s ease-out both';
    });
});
</script>
@endpush
@endsection
