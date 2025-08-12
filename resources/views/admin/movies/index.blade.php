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

        .movie-poster-small {
            width: 45px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .movie-poster-small:hover {
            transform: scale(1.05);
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

        .btn {
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary-orange);
            border: none;
            border-radius: 50px;
        }

        .btn-primary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }

        .btn-success {
            background: var(--primary-teal);
            border: none;
            border-radius: 50px;
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
        }

        .btn-outline-primary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
        }

        .view-detail-btn, .edit-btn, .edit-disabled-btn {
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
            background: var(--primary-orange);
            border: 1px solid var(--primary-orange);
            color: white;
        }

        .edit-btn:hover {
            background: var(--primary-teal);
            border-color: var(--primary-teal);
            color: white;
            transform: scale(1.1);
        }

        .edit-disabled-btn {
            background: #DC3545;
            border: 1px solid #DC3545;
            color: white;
            cursor: not-allowed;
            opacity: 1;
        }

        .edit-disabled-btn:hover {
            background: #c82333;
            border-color: #bd2130;
            color: white;
            transform: none;
        }

        .view-detail-btn i, .edit-btn i, .edit-disabled-btn i {
            font-size: 14px;
            color: white !important;
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

        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
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
            color: var(--primary-teal);
            transition: var(--transition);
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

            .movie-poster-small {
                width: 35px;
                height: 50px;
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

    <!-- Alert Messages -->
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
                    @if(session('movie_created'))
                        <small class="d-block mt-1 text-success-emphasis">
                            <i class="fas fa-info-circle me-1"></i>
                            Phim mới đã được thêm vào đầu danh sách bên dưới.
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
                        <i class="fas fa-film me-2"></i>
                        Quản lý phim
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý toàn bộ danh sách phim trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary btn-lg rounded-pill">
                        <i class="fas fa-plus-circle me-2"></i> Thêm phim mới
                    </a>
                    <a href="{{ route('admin.directors.create') }}" class="btn btn-success btn-lg rounded-pill">
                        <i class="fas fa-user-plus me-2"></i> Thêm đạo diễn
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
                                <i class="fas fa-film fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng phim</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $movies->total() }}</h3>
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
                                <i class="fas fa-play-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Đang chiếu</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-orange);">{{ $movies->where('status', 'showing')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-orange); width: {{ $movies->total() > 0 ? ($movies->where('status', 'showing')->count() / $movies->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Sắp chiếu</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--accent-yellow);">{{ $movies->where('status', 'upcoming')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--accent-yellow); width: {{ $movies->total() > 0 ? ($movies->where('status', 'upcoming')->count() / $movies->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-stop-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Đã kết thúc</h6>
                            <h3 class="mb-0 fw-bold" style="color: #dc3545);">{{ $movies->where('status', 'ended')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: #dc3545; width: {{ $movies->total() > 0 ? ($movies->where('status', 'ended')->count() / $movies->total()) * 100 : 0 }}%"></div>
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
                                Danh sách phim
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.movies.index') }}">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text rounded-start-pill">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="search" name="query" 
                                               class="form-control ps-0" 
                                               placeholder="Tìm kiếm phim..." 
                                               value="{{ request('query') }}">
                                        <select name="status" class="form-select rounded-end-pill">
                                            <option value="">Tất cả</option>
                                            <option value="showing" {{ request('status') == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
                                            <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
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
                                            <i class="fas fa-flag me-1"></i> Trạng thái
                                        </h6>
                                        <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id')])) }}" 
                                           class="dropdown-item rounded-3 {{ !request('status') ? 'active' : '' }}">Tất cả</a>
                                        @foreach (['showing', 'upcoming', 'ended'] as $status)
                                            <a href="{{ route('admin.movies.index', array_filter(['status' => $status, 'query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id')])) }}" 
                                               class="dropdown-item rounded-3 {{ request('status') == $status ? 'active' : '' }}">
                                                {{ $status == 'showing' ? 'Đang chiếu' : ($status == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc') }}
                                            </a>
                                        @endforeach

                                        <div class="dropdown-divider my-3"></div>
                                        <h6 class="dropdown-header fw-bold" style="color: var(--primary-orange);">
                                            <i class="fas fa-globe me-1"></i> Quốc gia
                                        </h6>
                                        <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id')])) }}" 
                                           class="dropdown-item rounded-3 {{ !request('country_id') ? 'active' : '' }}">Tất cả</a>
                                        @foreach ($countries as $country)
                                            <a href="{{ route('admin.movies.index', array_filter(['country_id' => $country->id, 'query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id')])) }}" 
                                               class="dropdown-item rounded-3 {{ request('country_id') == $country->id ? 'active' : '' }}">{{ $country->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <form id="delete-selected-form" action="{{ route('admin.movies.bulkDelete') }}" method="POST" style="display: none;" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-movie-ids">
                        <div class="alert alert-warning border-0 rounded-4 d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle text-warning fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <strong>Xóa nhiều phim đã chọn</strong>
                                <p class="mb-0 small">Bạn có chắc muốn xóa các phim đã chọn?</p>
                            </div>
                            <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('Bạn có chắc muốn xóa các phim đã chọn?')">
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
                                            <input type="checkbox" class="form-check-input" id="checkAllMovies">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-film me-1" style="color: var(--primary-teal);"></i> Phim
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-user-tie me-1" style="color: var(--primary-orange);"></i> Đạo diễn
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-users me-1" style="color: var(--accent-yellow);"></i> Diễn viên
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-tags me-1" style="color: var(--primary-teal);"></i> Thể loại
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-clock me-1" style="color: var(--primary-orange);"></i> Thời lượng
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-flag me-1" style="color: var(--accent-yellow);"></i> Trạng thái
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-plus me-1" style="color: var(--primary-teal);"></i> Thời gian tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-orange);"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movies as $index => $movie)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                @php
                                                    $canBeDeleted = $movie->canBeEdited();
                                                    $currentStatus = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                    if ($movie->end_date && $movie->end_date < now()) {
                                                        $currentStatus = 'ended';
                                                    }
                                                    
                                                    $disableReason = '';
                                                    if (!$canBeDeleted) {
                                                        if ($currentStatus === 'ended') {
                                                            $disableReason = 'Phim đã kết thúc, không thể xóa';
                                                        } elseif ($currentStatus === 'showing') {
                                                            $disableReason = 'Phim đang chiếu, không thể xóa';
                                                        } elseif ($movie->hasBookedTickets()) {
                                                            $disableReason = 'Phim đã có vé được đặt, không thể xóa';
                                                        }
                                                    }
                                                @endphp
                                                @if(!$canBeDeleted)
                                                    <input type="checkbox" class="form-check-input" disabled title="{{ $disableReason }}">
                                                @else
                                                    <input type="checkbox" class="form-check-input movie-checkbox" value="{{ $movie->id }}">
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('assets/images/movie-placeholder.png')) }}" 
                                                         alt="{{ $movie->name }}" 
                                                         class="movie-poster-small">
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold" style="color: var(--primary-teal);">{{ $movie->name }}</h6>
                                                    @if(!$movie->canBeEdited())
                                                        @if($movie->hasBookedTickets())
                                                            <span class="badge bg-warning-subtle text-warning rounded-pill">
                                                                <i class="fas fa-ticket-alt me-1"></i>Có vé đặt
                                                            </span>
                                                        @elseif($currentStatus === 'showing')
                                                            <span class="badge bg-success-subtle text-success rounded-pill">
                                                                <i class="fas fa-play-circle me-1"></i>Đang chiếu
                                                            </span>
                                                        @elseif($currentStatus === 'ended')
                                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                                                <i class="fas fa-stop-circle me-1"></i>Đã kết thúc
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-tie text-primary me-2" style="color: var(--primary-orange);"></i>
                                                <span class="fw-medium">{{ $movie->director?->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($movie->actors->isNotEmpty())
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($movie->actors->take(2) as $actor)
                                                        <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $actor->name }}</span>
                                                    @endforeach
                                                    @if($movie->actors->count() > 2)
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill" 
                                                              title="{{ $movie->actors->pluck('name')->join(', ') }}">
                                                            +{{ $movie->actors->count() - 2 }} khác
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($movie->genres->isNotEmpty())
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($movie->genres->take(2) as $genre)
                                                        <span class="badge bg-info-subtle text-info rounded-pill">{{ $genre->name }}</span>
                                                    @endforeach
                                                    @if($movie->genres->count() > 2)
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill" 
                                                              title="{{ $movie->genres->pluck('name')->join(', ') }}">
                                                            +{{ $movie->genres->count() - 2 }} khác
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-warning me-2" style="color: var(--accent-yellow);"></i>
                                                <span class="fw-medium">{{ $movie->duration_minutes }} phút</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                if ($movie->end_date && $movie->end_date < now()) {
                                                    $statusValue = 'ended';
                                                }
                                                
                                                $statusLabel = $statusValue == 'showing' ? 'Đang chiếu' : ($statusValue == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc');
                                            @endphp
                                            @if($statusValue === 'showing')
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="fas fa-play-circle me-1"></i>{{ $statusLabel }}
                                                </span>
                                            @elseif($statusValue === 'upcoming')
                                                <span class="badge bg-warning rounded-pill px-3 py-2">
                                                    <i class="fas fa-clock me-1"></i>{{ $statusLabel }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                                    <i class="fas fa-stop-circle me-1"></i>{{ $statusLabel }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-plus text-info me-2" style="color: var(--accent-yellow);"></i>
                                                <span class="text-muted">{{ $movie->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.movies.show', $movie->id) }}"
                                                   class="btn btn-sm view-detail-btn" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @php
                                                    $canBeEdited = $movie->canBeEdited();
                                                    $disableReason = '';
                                                    if (!$canBeEdited) {
                                                        if ($currentStatus === 'ended') {
                                                            $disableReason = 'Phim đã kết thúc, không thể chỉnh sửa';
                                                        } elseif ($currentStatus === 'showing') {
                                                            $disableReason = 'Phim đang chiếu, không thể chỉnh sửa';
                                                        } elseif ($movie->hasBookedTickets()) {
                                                            $disableReason = 'Phim đã có vé được đặt, không thể chỉnh sửa';
                                                        }
                                                    }
                                                @endphp
                                                @if(!$canBeEdited)
                                                    <span class="btn btn-sm edit-disabled-btn" 
                                                          title="{{ $disableReason }}"
                                                          data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                @else
                                                    <a href="{{ route('admin.movies.edit', $movie->id) }}"
                                                       class="btn btn-sm edit-btn" 
                                                       title="Chỉnh sửa"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($movies->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-film text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có phim nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm phim mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($movies->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $movies->firstItem() }}-{{ $movies->lastItem() }} trong tổng {{ $movies->total() }} phim
                            </div>
                            <div>
                                {{ $movies->appends(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id')])->links('pagination::bootstrap-5') }}
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
        const checked = document.querySelectorAll('.movie-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        form.style.display = checked.length > 0 ? 'block' : 'none';
    }   

    // Check all movies
    document.getElementById('checkAllMovies')?.addEventListener('change', function() {
        document.querySelectorAll('.movie-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    // Individual checkbox change
    document.querySelectorAll('.movie-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    // Handle bulk delete form submission
    document.getElementById('delete-selected-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.movie-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-movie-ids').value = checked.join(',');
    });
});
</script>
@endsection