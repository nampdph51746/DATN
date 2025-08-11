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
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .alert-success {
            background: rgba(0, 172, 193, 0.1);
            color: var(--primary-teal);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #DC3545;
        }

        .director-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .director-avatar:hover {
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

        .btn-outline-primary {
            border-color: var(--primary-teal);
            color: var(--primary-teal);
            border-radius: 50px;
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background: var(--primary-teal);
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

        .avatar-lg {
            width: 4rem;
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            border-radius: 50%;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 1rem;
        }

        .dropdown-item {
            border-radius: 8px;
            margin: 2px 0;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: rgba(0, 172, 193, 0.1);
            transform: translateX(4px);
        }

        .dropdown-item.active {
            background: var(--primary-teal);
            color: white;
        }

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

        .form-check-input:checked {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card, .alert, .table tbody tr {
            animation: fadeInUp 0.6s ease-out;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .avatar-lg {
                width: 3rem;
                height: 3rem;
                font-size: 1.4rem;
            }

            .director-avatar {
                width: 35px;
                height: 35px;
            }

            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .input-group {
                min-width: 100% !important;
            }
        }
    </style>

    <!-- Alert Messages với animation -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle">
                        <i class="fas fa-check-circle fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Thành công!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                    @if(session('director_created'))
                        <small class="d-block mt-1 text-success-emphasis">
                            <i class="fas fa-info-circle me-1"></i>
                            Đạo diễn mới đã được thêm vào đầu danh sách bên dưới.
                        </small>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle">
                        <i class="fas fa-exclamation-triangle fs-5"></i>
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
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-person-video2 me-2"></i>
                        Quản lý đạo diễn
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý toàn bộ danh sách đạo diễn trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.directors.create') }}" class="btn btn-primary btn-lg rounded-pill">
                        <i class="fas fa-plus-circle me-2"></i> Thêm đạo diễn mới
                    </a>
                    <a href="{{ route('admin.movies.create') }}" class="btn btn-success btn-lg rounded-pill">
                        <i class="fas fa-film me-2"></i> Thêm phim
                    </a>
                    <a href="{{ route('admin.actors.create') }}" class="btn btn-info btn-lg rounded-pill">
                        <i class="fas fa-users me-2"></i> Thêm diễn viên
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
                            <div class="avatar-lg">
                                <i class="fas fa-users fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng đạo diễn</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $directors->total() }}</h3>
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
                            <div class="avatar-lg">
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Đang hoạt động</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-orange);">{{ $directors->where('is_active', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-orange); width: {{ $directors->total() > 0 ? ($directors->where('is_active', true)->count() / $directors->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-pause-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Ngưng hoạt động</h6>
                            <h3 class="mb-0 fw-bold" style="color: #DC3545;">{{ $directors->where('is_active', false)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: #DC3545; width: {{ $directors->total() > 0 ? ($directors->where('is_active', false)->count() / $directors->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-film fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng phim đã làm</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--accent-yellow);">{{ $directors->sum(function($director) { return $director->movies->count(); }) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--accent-yellow); width: 85%"></div>
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
                                Danh sách đạo diễn
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.directors.index') }}">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text rounded-start-pill">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="search" name="query" 
                                               class="form-control ps-0" 
                                               placeholder="Tìm kiếm đạo diễn..." 
                                               value="{{ request('query') }}">
                                        <select name="status" class="form-select rounded-end-pill">
                                            <option value="">Tất cả</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>

                                <!-- Advanced Filter Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-lg dropdown-toggle rounded-pill" 
                                            type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-filter me-1"></i> Lọc nâng cao
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end p-4">
                                        <h6 class="dropdown-header fw-bold" style="color: var(--primary-teal);">
                                            <i class="fas fa-check-circle me-1"></i> Trạng thái
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
                                        <h6 class="dropdown-header fw-bold" style="color: var(--primary-orange);">
                                            <i class="fas fa-globe me-1"></i> Quốc tịch
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
                            <i class="fas fa-exclamation-triangle text-warning fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <strong>Xóa nhiều đạo diễn đã chọn</strong>
                                <p class="mb-0 small">Bạn có chắc muốn xóa các đạo diễn đã chọn?</p>
                            </div>
                            <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('Bạn có chắc muốn xóa các đạo diễn đã chọn?')">
                                <i class="fas fa-trash me-1"></i> Xóa đã chọn
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
                                        <i class="fas fa-person-video2 me-1" style="color: var(--primary-teal);"></i> Đạo diễn
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-globe me-1" style="color: var(--primary-orange);"></i> Quốc tịch
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-alt me-1" style="color: var(--accent-yellow);"></i> Ngày sinh
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-film me-1" style="color: var(--primary-teal);"></i> Số phim
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-flag me-1" style="color: var(--primary-orange);"></i> Trạng thái
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-plus me-1" style="color: var(--accent-yellow);"></i> Thời gian tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-teal);"></i> Thao tác
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
                                                        <img src="{{ $director->image_path ? Storage::url($director->image_path) : asset('assets/images/d directors-placeholder.png') }}" 
                                                             alt="{{ $director->name }}" 
                                                             class="director-avatar">
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
                                                    <h6 class="mb-1 fw-bold" style="color: var(--primary-teal);">{{ $director->name }}</h6>
                                                    @if($director->movies->count() > 0)
                                                        <span class="badge bg-info-subtle text-info rounded-pill">
                                                            <i class="fas fa-film me-1"></i>Có {{ $director->movies->count() }} phim
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($director->nationality)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-globe text-primary me-2"></i>
                                                    <span class="fw-medium">{{ $director->nationality }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($director->birth_date)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar-alt text-success me-2"></i>
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
                                                <i class="fas fa-film text-info me-2"></i>
                                                <span class="fw-medium">{{ $director->movies->count() }} phim</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($director->is_active)
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i>Hoạt động
                                                </span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                                    <i class="fas fa-times-circle me-1"></i>Ngưng hoạt động
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $director->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.directors.show', $director->id) }}"
                                                   class="btn btn-sm view-detail-btn" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.directors.edit', $director->id) }}"
                                                   class="btn btn-sm edit-btn" 
                                                   title="Chỉnh sửa"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-person-video2 text-muted fs-1 mb-3"></i>
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
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Update delete button visibility
    function updateDeleteButton() {
        const checked = document.querySelectorAll('.director-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        form.style.display = checked.length > 0 ? 'block' : 'none';
    }   

    // Check all directors
    document.getElementById('checkAllDirectors')?.addEventListener('change', function() {
        document.querySelectorAll('.director-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    // Individual checkbox change
    document.querySelectorAll('.director-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    // Handle bulk delete form submission
    document.getElementById('delete-selected-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.director-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-director-ids').value = checked.join(',');
    });

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

    // Observe table rows and cards
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(row);
    });

    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>
@endsection