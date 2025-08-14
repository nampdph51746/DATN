@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle bg-success-subtle">
                        <span class="avatar-title rounded-circle bg-success text-white">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Thành công!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-danger rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="bi bi-trash3 fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Thùng rác - Quốc gia</h2>
                                <p class="mb-0 opacity-90 fs-5">Danh sách các quốc gia đã bị xóa</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-danger rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="bi bi-trash3 me-2"></i>
                                Thùng rác
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-trash3" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-danger-subtle">
                                <span class="avatar-title rounded-circle bg-danger text-white">
                                    <i class="bi bi-trash3 fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Đã xóa</h6>
                            <h3 class="mb-0 fw-bold text-danger">{{ $countries->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 py-4 rounded-top-4">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="bi bi-table me-2 text-danger"></i>
                                Danh sách quốc gia đã xóa
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <form action="{{ route('admin.countries.trash') }}" method="GET" class="d-flex gap-2">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                           class="form-control rounded-pill" 
                                           placeholder="Tìm tên quốc gia...">
                                    <button type="submit" class="btn btn-danger rounded-pill">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-secondary rounded-pill">
                                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-bold text-dark" style="width: 60px;">
                                        <i class="bi bi-hash me-1"></i> STT
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-globe me-1"></i> Tên quốc gia
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-code-square me-1"></i> Mã code
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-x me-1"></i> Thời gian xóa
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="bi bi-gear me-1"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countries as $key => $country)
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 fw-semibold">
                                                    {{ $key + 1 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-sm rounded-circle bg-danger-subtle">
                                                        <span class="avatar-title rounded-circle bg-danger text-white">
                                                            <i class="bi bi-globe"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $country->name }}</h6>
                                                    <small class="text-muted">ID: #{{ $country->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 fw-semibold">
                                                {{ $country->code }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <i class="bi bi-clock-history me-1 text-danger"></i>
                                                {{ $country->deleted_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $country->deleted_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="dropdown position-static">
                                                <button class="btn btn-light btn-sm dropdown-toggle rounded-pill border-0 shadow-sm" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false"
                                                        data-bs-auto-close="true">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4">
                                                    <li>
                                                        <form action="{{ route('admin.countries.restore', $country->id) }}" 
                                                              method="POST" 
                                                              class="d-inline w-100">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="dropdown-item rounded-3 py-2 text-success w-100 border-0 bg-transparent"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục quốc gia này?')">
                                                                <i class="bi bi-arrow-clockwise text-success me-2"></i>
                                                                <span>Khôi phục</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.countries.forceDelete', $country->id) }}" 
                                                              method="POST" 
                                                              class="d-inline w-100"
                                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn quốc gia này? Hành động này không thể hoàn tác!')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="dropdown-item rounded-3 py-2 text-danger w-100 border-0 bg-transparent">
                                                                <i class="bi bi-trash3 text-danger me-2"></i>
                                                                <span>Xóa vĩnh viễn</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-trash3 text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Thùng rác trống</h6>
                                                <p class="text-muted small mb-0">Không có quốc gia nào bị xóa</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($countries->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $countries->firstItem() }}-{{ $countries->lastItem() }} trong tổng {{ $countries->total() }} quốc gia đã xóa
                            </div>
                            <div>
                                {{ $countries->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Gradient Background */
.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
}

/* Modern UI Styles */
.card {
    transition: all 0.3s ease;
    border: none !important;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #fff5f5;
    transform: translateX(2px);
}

.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-lg {
    width: 3.5rem;
    height: 3.5rem;
}

.avatar-xl {
    width: 5rem;
    height: 5rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Enhanced Dropdown Styles */
.dropdown {
    position: static !important;
}

.dropdown-menu {
    border: none !important;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
    z-index: 9999 !important;
    min-width: 180px;
    padding: 0.5rem 0;
    margin-top: 0.5rem !important;
}

.dropdown-menu.show {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 0.5rem;
    margin: 2px 8px;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
    transform: translateX(4px);
    color: inherit;
}

.dropdown-item:active {
    background: linear-gradient(135deg, #e9ecef, #dee2e6) !important;
}

.dropdown-divider {
    margin: 0.5rem 0;
    border-top: 1px solid #e9ecef;
}

.dropdown-toggle::after {
    display: none;
}

.dropdown-toggle:focus {
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.25) !important;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.bg-danger-subtle {
    background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
}

.bg-secondary-subtle {
    background-color: rgba(var(--bs-secondary-rgb), 0.1) !important;
}

/* Table overflow fix */
.table-responsive {
    overflow-x: auto;
    overflow-y: visible;
}

/* Ensure dropdown appears above other elements */
.table tbody tr:last-child .dropdown-menu {
    transform: translateY(-100%);
    margin-top: -0.5rem !important;
}

@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.85rem;
    }
    
    .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .dropdown-menu {
        min-width: 160px;
        font-size: 0.85rem;
    }
}
</style>
@endsection