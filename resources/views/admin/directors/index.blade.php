@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Alert Messages với animation -->
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
                    @if(session('director_created'))
                        <small class="d-block mt-1 text-success-emphasis">
                            <i class="bi bi-info-circle me-1"></i>
                            Đạo diễn mới đã được thêm vào đầu danh sách bên dưới.
                        </small>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle bg-danger-subtle">
                        <span class="avatar-title rounded-circle bg-danger text-white">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Có lỗi xảy ra!</h6>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-person-video text-primary me-2"></i>
                        Quản lý đạo diễn
                    </h4>
                    <p class="text-muted mb-0">Quản lý toàn bộ danh sách đạo diễn trong hệ thống</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.directors.create') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Thêm đạo diễn mới
                    </a>
                    <a href="{{ route('admin.movies.create') }}" class="btn btn-success btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-film me-2"></i> Thêm phim
                    </a>
                    <a href="{{ route('admin.actors.create') }}" class="btn btn-info btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-people-fill me-2"></i> Thêm diễn viên
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-white">
                                    <i class="bi bi-people fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng đạo diễn</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $directors->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success text-white">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Đang hoạt động</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $directors->where('is_active', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: {{ $directors->total() > 0 ? ($directors->where('is_active', true)->count() / $directors->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-white">
                                    <i class="bi bi-pause-circle fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Ngưng hoạt động</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $directors->where('is_active', false)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: {{ $directors->total() > 0 ? ($directors->where('is_active', false)->count() / $directors->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-white">
                                    <i class="bi bi-film fs-4"></i>
                                </span>
                        </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng phim đã làm</h6>
                            <h3 class="mb-0 fw-bold text-info">{{ $directors->sum(function($director) { return $director->movies->count(); }) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-info" style="width: 85%"></div>
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
                                <i class="bi bi-table me-2 text-primary"></i>
                                Danh sách đạo diễn
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.directors.index') }}">
                                    <div class="input-group input-group-lg" style="min-width: 300px;">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-pill">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="search" name="query" 
                                               class="form-control border-start-0 border-end-0 ps-0" 
                                               placeholder="Tìm kiếm đạo diễn..." 
                                               value="{{ request('query') }}">
                                        <select name="status" class="form-select border-start-0 rounded-end-pill">
                                            <option value="">Tất cả</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </form>

                                <!-- Advanced Filter Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-lg dropdown-toggle rounded-pill" 
                                            type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-funnel me-1"></i> Lọc nâng cao
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end p-4 shadow border-0 rounded-4" style="min-width: 300px;">
                                        <h6 class="dropdown-header fw-bold text-primary">
                                            <i class="bi bi-check-circle me-1"></i> Trạng thái
                                        </h6>
                                        <a href="{{ route('admin.directors.index', array_filter(['query' => request('query')])) }}" 
                                           class="dropdown-item rounded-3 {{ !request('status') ? 'active' : '' }}">
                                            Tất cả
                                        </a>
                                        <a href="{{ route('admin.directors.index', array_filter(['status' => 'active', 'query' => request('query')])) }}" 
                                           class="dropdown-item rounded-3 {{ request('status') == 'active' ? 'active' : '' }}">
                                            Đang hoạt động
                                        </a>
                                        <a href="{{ route('admin.directors.index', array_filter(['status' => 'inactive', 'query' => request('query')])) }}" 
                                           class="dropdown-item rounded-3 {{ request('status') == 'inactive' ? 'active' : '' }}">
                                            Ngưng hoạt động
                                        </a>

                                        <div class="dropdown-divider my-3"></div>
                                        <h6 class="dropdown-header fw-bold text-success">
                                            <i class="bi bi-globe me-1"></i> Quốc tịch
                                        </h6>
                                        <a href="{{ route('admin.directors.index', array_filter(['query' => request('query'), 'status' => request('status')])) }}" 
                                           class="dropdown-item rounded-3 {{ !request('nationality') ? 'active' : '' }}">Tất cả</a>
                                        @php
                                            $allDirectors = \App\Models\Director::all();
                                            $nationalities = $allDirectors->pluck('nationality')->filter()->unique()->sort();
                                        @endphp
                                        @foreach ($nationalities as $nationality)
                                            <a href="{{ route('admin.directors.index', array_filter(['nationality' => $nationality, 'query' => request('query'), 'status' => request('status')])) }}" 
                                               class="dropdown-item rounded-3 {{ request('nationality') == $nationality ? 'active' : '' }}">{{ $nationality }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <form id="delete-selected-form" action="{{ route('admin.directors.bulkDelete') }}" method="POST" style="display: none;" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-director-ids">
                        <div class="alert alert-warning border-0 rounded-4 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <strong>Xóa nhiều đạo diễn đã chọn</strong>
                                <p class="mb-0 small">Bạn có chắc muốn xóa các đạo diễn đã chọn?</p>
                            </div>
                            <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('Bạn có chắc muốn xóa các đạo diễn đã chọn?')">
                                <i class="bi bi-trash me-1"></i> Xóa đã chọn
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4" style="width: 50px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkAllDirectors">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-person-video me-1"></i> Đạo diễn
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-globe me-1"></i> Quốc tịch
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-event me-1"></i> Ngày sinh
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-film me-1"></i> Số phim
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-flag me-1"></i> Trạng thái
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-plus me-1"></i> Thời gian tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="bi bi-gear me-1"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($directors as $director)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                @if($director->movies->count() == 0)
                                                    <input type="checkbox" class="form-check-input director-checkbox" value="{{ $director->id }}">
                                                @else
                                                    <input type="checkbox" class="form-check-input" disabled title="Không thể xóa do có phim liên kết">
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="position-relative">
                                                        <img src="{{ $director->image_path ? Storage::url($director->image_path) : asset('assets/images/director-placeholder.png') }}" 
                                                             alt="{{ $director->name }}" 
                                                             class="director-avatar rounded-circle shadow-sm">
                                                        @if($director->is_active)
                                                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border-2 border-white rounded-circle">
                                                                <span class="visually-hidden">Active</span>
                                                            </span>
                                                        @else
                                                            <span class="position-absolute bottom-0 end-0 p-1 bg-danger border-2 border-white rounded-circle">
                                                                <span class="visually-hidden">Inactive</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $director->name }}</h6>
                                                    @if($director->movies->count() > 0)
                                                        <span class="badge bg-info-subtle text-info rounded-pill">
                                                            <i class="bi bi-film me-1"></i>Có {{ $director->movies->count() }} phim
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($director->nationality)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-globe text-primary me-2"></i>
                                                    <span class="fw-medium">{{ $director->nationality }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($director->birth_date)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-calendar-event text-success me-2"></i>
                                                    <div>
                                                        <span class="fw-medium">{{ $director->birth_date->format('d/m/Y') }}</span>
                                                        <small class="d-block text-muted">{{ $director->birth_date->age }} tuổi</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-film text-info me-2"></i>
                                                <span class="fw-medium">{{ $director->movies->count() }} phim</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($director->is_active)
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i>Hoạt động
                                                </span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                                    <i class="bi bi-x-circle me-1"></i>Ngưng hoạt động
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $director->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm dropdown-toggle rounded-pill" 
                                                        type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">
                                                    <li>
                                                        <a class="dropdown-item rounded-3" href="{{ route('admin.directors.show', $director->id) }}">
                                                            <i class="bi bi-eye text-primary me-2"></i>
                                                            Xem chi tiết
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item rounded-3" href="{{ route('admin.directors.edit', $director->id) }}">
                                                            <i class="bi bi-pencil-square text-warning me-2"></i>
                                                            Chỉnh sửa
                                                        </a>
                                                    </li>
                                                    @if($director->movies->count() == 0)
                                                        <li>
                                                            <form action="{{ route('admin.directors.destroy', $director->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa đạo diễn này?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item rounded-3 text-danger">
                                                                    <i class="bi bi-trash3 text-danger me-2"></i>
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <span class="dropdown-item-text text-muted rounded-3">
                                                                <i class="bi bi-lock text-muted me-2"></i>
                                                                Không thể xóa do có phim liên kết
                                                            </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-person-video text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có đạo diễn nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm đạo diễn mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($directors->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $directors->firstItem() }}-{{ $directors->lastItem() }} trong tổng {{ $directors->total() }} đạo diễn
                            </div>
                            <div>
                                {{ $directors->appends(['query' => request('query'), 'status' => request('status'), 'nationality' => request('nationality')])->links('pagination::bootstrap-5') }}
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
<style>
/* Modern UI Styles */
.director-avatar {
    width: 45px;
    height: 45px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.director-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

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
    background-color: #f8f9fa;
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

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
}

.rounded-pill {
    border-radius: 50rem !important;
}

.rounded-4 {
    border-radius: 0.75rem !important;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-lg {
    width: 3.5rem;
    height: 3.5rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 0.5rem;
    margin: 2px 0;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    transform: translateX(4px);
}

.dropdown-item.active {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
}

.input-group-lg > .form-control,
.input-group-lg > .form-select,
.input-group-lg > .input-group-text {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.alert {
    border-radius: 0.75rem;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.progress {
    background-color: rgba(0,0,0,0.05);
}

@media (max-width: 768px) {
    .director-avatar {
        width: 35px;
        height: 35px;
    }
    
    .table td, .table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.85rem;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateDeleteButton() {
        const checked = document.querySelectorAll('.director-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        if (checked.length > 0) {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }   

    document.getElementById('checkAllDirectors')?.addEventListener('change', function() {
        document.querySelectorAll('.director-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    document.querySelectorAll('.director-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    document.getElementById('delete-selected-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.director-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-director-ids').value = checked.join(',');
    });
});
</script>
@endsection