@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
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
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 172, 193, 0.15);
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
        }
        .avatar-sm {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }
        .avatar-sm:hover {
            transform: scale(1.1);
        }
        .table > :not(caption) > * > * {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        .table tbody tr {
            transition: var(--transition);
        }
        .table tbody tr:hover {
            background: rgba(0, 172, 193, 0.05);
            transform: translateX(2px);
        }
        .btn-primary {
            background: var(--primary-orange);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background: var(--primary-teal);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }
        .btn-success {
            background: var(--primary-teal);
            border: none;
            border-radius: 50px;
            transition: var(--transition);
        }
        .btn-success:hover {
            background: var(--accent-yellow);
            color: #1a202c;
            transform: translateY(-2px);
        }
        .btn-info {
            background: var(--accent-yellow);
            border: none;
            border-radius: 50px;
            color: #1a202c;
            transition: var(--transition);
        }
        .btn-info:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }
        .view-detail-btn, .edit-btn {
            border-radius: 8px;
            min-width: 36px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .view-detail-btn {
            background: var(--primary-teal);
            border: 1px solid var(--primary-teal);
            color: white;
        }
        .view-detail-btn:hover {
            background: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: #1a202c;
            transform: scale(1.1);
        }
        .edit-btn {
            background: #212529;
            border: 1px solid #212529;
            color: white;
        }
        .edit-btn:hover {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
            transform: scale(1.1);
        }
        .badge {
            font-size: 0.95rem;
            border-radius: 50px;
            padding: 0.5em 1em;
        }
        .badge-success { background: var(--primary-teal); color: #fff; }
        .badge-secondary { background: #6c757d; color: #fff; }
        .badge-warning { background: var(--accent-yellow); color: #1a202c; }
        .badge-danger { background: #dc3545; color: #fff; }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.2);
        }
        .input-group-text {
            background: var(--neutral-bg);
            border-color: #e0e0e0;
            border-radius: 8px;
        }
        .pagination {
            --bs-pagination-border-radius: 8px;
        }
        .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 3px;
            transition: var(--transition);
            color: var(--primary-teal);
        }
        .page-link:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }
        .page-item.active .page-link {
            background: var(--primary-teal);
            border: none;
            color: white;
        }
        @media (max-width: 768px) {
            .table-responsive { font-size: 0.9rem; }
            .avatar-sm { width: 35px; height: 35px; }
            .btn-lg { padding: 0.5rem 1rem; font-size: 0.9rem; }
            .view-detail-btn, .edit-btn { min-width: 32px; height: 28px; }
        }
    </style>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-users me-2"></i>
                        Quản lý khách hàng
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý toàn bộ danh sách khách hàng trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg rounded-pill">
                        <i class="fas fa-user-plus me-2"></i> Thêm khách hàng mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg" style="background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));">
                                <i class="fas fa-users fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng khách hàng</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $users->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-teal); width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg" style="background: linear-gradient(135deg, var(--primary-teal), var(--primary-orange));">
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Hoạt động</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-orange);">{{ $users->where('status->value', 'active')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-orange); width: {{ $users->total() > 0 ? ($users->where('status->value', 'active')->count() / $users->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg" style="background: linear-gradient(135deg, var(--accent-yellow), var(--primary-teal));">
                                <i class="fas fa-user-slash fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Không hoạt động</h6>
                            <h3 class="mb-0 fw-bold" style="color: #6c757d;">{{ $users->where('status->value', 'inactive')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: #6c757d; width: {{ $users->total() > 0 ? ($users->where('status->value', 'inactive')->count() / $users->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg" style="background: linear-gradient(135deg, #dc3545, var(--primary-teal));">
                                <i class="fas fa-ban fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tạm khóa</h6>
                            <h3 class="mb-0 fw-bold" style="color: #dc3545;">{{ $users->where('status->value', 'suspended')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: #dc3545; width: {{ $users->total() > 0 ? ($users->where('status->value', 'suspended')->count() / $users->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-table me-2"></i>
                                Danh sách khách hàng
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('users.index') }}">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text rounded-start-pill">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="search" name="search" 
                                               class="form-control ps-0" 
                                               placeholder="Tìm kiếm theo tên hoặc email" 
                                               value="{{ request('search') }}">
                                        <select name="role" class="form-select">
                                            <option value="">Tất cả vai trò</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="rank" class="form-select">
                                            <option value="">Tất cả cấp bậc</option>
                                            @foreach($ranks as $rank)
                                                <option value="{{ $rank->id }}" {{ request('rank') == $rank->id ? 'selected' : '' }}>
                                                    {{ $rank->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="status" class="form-select">
                                            <option value="">Tất cả trạng thái</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Tạm khóa</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                        <i class="fas fa-filter"></i> Lọc
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                                        <i class="fas fa-redo"></i> Đặt lại
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAllUsers">
                                        </div>
                                    </th>
                                    <th><i class="fas fa-user-circle me-1 text-primary"></i> Ảnh đại diện</th>
                                    <th><i class="fas fa-user me-1 text-info"></i> Tên người dùng</th>
                                    <th><i class="fas fa-id-card me-1 text-warning"></i> Mã người dùng</th>
                                    <th><i class="fas fa-medal me-1 text-success"></i> Cấp bậc</th>
                                    <th><i class="fas fa-user-tag me-1 text-primary"></i> Vai trò</th>
                                    <th><i class="fas fa-envelope me-1 text-info"></i> Email</th>
                                    <th><i class="fas fa-map-marker-alt me-1 text-warning"></i> Địa chỉ</th>
                                    <th><i class="fas fa-birthday-cake me-1 text-success"></i> Ngày sinh</th>
                                    <th><i class="fas fa-calendar-plus me-1 text-primary"></i> Ngày tạo</th>
                                    <th><i class="fas fa-flag me-1 text-danger"></i> Trạng thái</th>
                                    <th class="text-center pe-4"><i class="fas fa-cogs me-1 text-primary"></i> Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <img src="{{ Storage::url($user->avatar_url) }}" class="avatar-sm" alt="Ảnh đại diện">
                                        </td>
                                        <td>
                                            <span class="fw-semibold"><i class="fas fa-user text-info me-1"></i>{{ $user->name }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><i class="fas fa-id-card text-warning me-1"></i>{{ $user->id }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><i class="fas fa-medal text-success me-1"></i>{{ optional($user->customerRank)->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><i class="fas fa-user-tag text-primary me-1"></i>{{ $user->getRoleNames()->first() ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><i class="fas fa-envelope text-info me-1"></i>{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><i class="fas fa-map-marker-alt text-warning me-1"></i>{{ $user->address ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><i class="fas fa-birthday-cake text-success me-1"></i>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><i class="fas fa-calendar-plus text-primary me-1"></i>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if($user->status->value === 'active')
                                                <span class="badge badge-success"><i class="fas fa-check-circle me-1"></i>Hoạt động</span>
                                            @elseif($user->status->value === 'inactive')
                                                <span class="badge badge-secondary"><i class="fas fa-user-slash me-1"></i>Không hoạt động</span>
                                            @elseif($user->status->value === 'suspended')
                                                <span class="badge badge-danger"><i class="fas fa-ban me-1"></i>Tạm khóa</span>
                                            @else
                                                <span class="badge badge-danger"><i class="fas fa-question-circle me-1"></i>Không xác định</span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm view-detail-btn" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm edit-btn" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-users text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có khách hàng nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm khách hàng mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $users->firstItem() }}-{{ $users->lastItem() }} trong tổng {{ $users->total() }} khách hàng
                            </div>
                            <div>
                                {{ $users->links('pagination::bootstrap-5') }}
                            </div>
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
    // Select all users
    document.getElementById('selectAllUsers')?.addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
});
</script>
@endsection