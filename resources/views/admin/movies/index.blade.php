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
                    @if(session('movie_created'))
                        <small class="d-block mt-1 text-success-emphasis">
                            <i class="bi bi-info-circle me-1"></i>
                            Phim mới đã được thêm vào đầu danh sách bên dưới.
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
                        <i class="bi bi-film text-primary me-2"></i>
                        Quản lý phim
                    </h4>
                    <p class="text-muted mb-0">Quản lý toàn bộ danh sách phim trong hệ thống</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Thêm phim mới
                    </a>
                    <a href="{{ route('admin.directors.create') }}" class="btn btn-success btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-person-plus me-2"></i> Thêm đạo diễn
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
                                    <i class="bi bi-film fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng phim</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $movies->total() }}</h3>
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
                                    <i class="bi bi-play-circle fs-4"></i>
                                </span>
                        </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Đang chiếu</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $movies->where('status', 'showing')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: {{ $movies->total() > 0 ? ($movies->where('status', 'showing')->count() / $movies->total()) * 100 : 0 }}%"></div>
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
                                    <i class="bi bi-clock fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Sắp chiếu</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $movies->where('status', 'upcoming')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: {{ $movies->total() > 0 ? ($movies->where('status', 'upcoming')->count() / $movies->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-danger-subtle">
                                <span class="avatar-title rounded-circle bg-danger text-white">
                                    <i class="bi bi-stop-circle fs-4"></i>
                                </span>
                        </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Đã kết thúc</h6>
                            <h3 class="mb-0 fw-bold text-danger">{{ $movies->where('status', 'ended')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-danger" style="width: {{ $movies->total() > 0 ? ($movies->where('status', 'ended')->count() / $movies->total()) * 100 : 0 }}%"></div>
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
                                Danh sách phim
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.movies.index') }}">
                                    <div class="input-group input-group-lg" style="min-width: 300px;">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-pill">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="search" name="query" 
                                               class="form-control border-start-0 border-end-0 ps-0" 
                                               placeholder="Tìm kiếm phim..." 
                                               value="{{ request('query') }}">
                                        <select name="status" class="form-select border-start-0 rounded-end-pill">
                                            <option value="">Tất cả</option>
                                            <option value="showing" {{ request('status') == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
                                            <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
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
                                        <!-- Filter content giữ nguyên logic -->
                                        <h6 class="dropdown-header fw-bold text-primary">
                                            <i class="bi bi-check-circle me-1"></i> Trạng thái
                                        </h6>
                                        <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" 
                                           class="dropdown-item rounded-3 {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">
                                            Tất cả
                                        </a>
                                        @foreach (['showing', 'upcoming', 'ended'] as $status)
                                            <a href="{{ route('admin.movies.index', array_filter(['status' => $status, 'query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" 
                                               class="dropdown-item rounded-3 {{ request('status') == $status ? 'active' : '' }}">
                                                {{ $status == 'showing' ? 'Đang chiếu' : ($status == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc') }}
                                            </a>
                                        @endforeach

                                        <div class="dropdown-divider my-3"></div>
                                        <h6 class="dropdown-header fw-bold text-success">
                                            <i class="bi bi-globe me-1"></i> Quốc gia
                                        </h6>
                                        <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" 
                                           class="dropdown-item rounded-3 {{ !request('country_id') ? 'active' : '' }}">Tất cả</a>
                                        @foreach ($countries as $country)
                                            <a href="{{ route('admin.movies.index', array_filter(['country_id' => $country->id, 'query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" 
                                               class="dropdown-item rounded-3 {{ request('country_id') == $country->id ? 'active' : '' }}">{{ $country->name }}</a>
                                        @endforeach

                                        <div class="dropdown-divider my-3"></div>
                                        <h6 class="dropdown-header fw-bold text-warning">
                                            <i class="bi bi-shield-check me-1"></i> Giới hạn độ tuổi
                                        </h6>
                                        <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" 
                                           class="dropdown-item rounded-3 {{ !request('age_limit_id') ? 'active' : '' }}">Tất cả</a>
                                        @foreach ($ageLimits as $ageLimit)
                                            <a href="{{ route('admin.movies.index', array_filter(['age_limit_id' => $ageLimit->id, 'query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" 
                                               class="dropdown-item rounded-3 {{ request('age_limit_id') == $ageLimit->id ? 'active' : '' }}">{{ $ageLimit->name ?? $ageLimit->label }}</a>
                                        @endforeach

                                        <div class="dropdown-divider my-3"></div>
                                        <h6 class="dropdown-header fw-bold text-info">
                                            <i class="bi bi-tags me-1"></i> Thể loại
                                        </h6>
                                        <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" 
                                           class="dropdown-item rounded-3 {{ !request('genre_id') ? 'active' : '' }}">Tất cả</a>
                                        @foreach ($genres as $genre)
                                            <a href="{{ route('admin.movies.index', array_filter(['genre_id' => $genre->id, 'query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" 
                                               class="dropdown-item rounded-3 {{ request('genre_id') == $genre->id ? 'active' : '' }}">{{ $genre->name }}</a>
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
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <strong>Xóa nhiều phim đã chọn</strong>
                                <p class="mb-0 small">Bạn có chắc muốn xóa các phim đã chọn?</p>
                            </div>
                            <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('Bạn có chắc muốn xóa các phim đã chọn?')">
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
                                            <input type="checkbox" class="form-check-input" id="checkAllMovies">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-film me-1"></i> Phim
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-person-video me-1"></i> Đạo diễn
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-people me-1"></i> Diễn viên
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-tags me-1"></i> Thể loại
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-clock me-1"></i> Thời lượng
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-event me-1"></i> Ngày phát hành
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-x me-1"></i> Ngày kết thúc
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-translate me-1"></i> Ngôn ngữ
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-globe me-1"></i> Quốc gia
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-shield-check me-1"></i> Giới hạn tuổi
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
                                                         class="movie-poster-small rounded-3 shadow-sm">
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $movie->name }}</h6>
                                                    @if(!$movie->canBeEdited())
                                                        @if($movie->hasBookedTickets())
                                                            <span class="badge bg-warning-subtle text-warning rounded-pill">
                                                                <i class="bi bi-ticket-fill me-1"></i>Có vé đặt
                                                            </span>
                                                        @elseif($currentStatus === 'showing')
                                                            <span class="badge bg-success-subtle text-success rounded-pill">
                                                                <i class="bi bi-play-circle-fill me-1"></i>Đang chiếu
                                                            </span>
                                                        @elseif($currentStatus === 'ended')
                                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                                                <i class="bi bi-stop-circle-fill me-1"></i>Đã kết thúc
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-video text-primary me-2"></i>
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
                                                <i class="bi bi-clock text-warning me-2"></i>
                                                <span class="fw-medium">{{ $movie->duration_minutes }} phút</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $movie->release_date->format('d/m/Y') }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $movie->language ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $movie->country?->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                if ($movie->end_date && $movie->end_date < now()) {
                                                    $statusValue = 'ended';
                                                }
                                                
                                                $statusColors = [
                                                    'showing' => 'bg-success',
                                                    'upcoming' => 'bg-warning',
                                                    'ended' => 'bg-danger',
                                                ];
                                                $statusLabel = $statusValue == 'showing' ? 'Đang chiếu' : ($statusValue == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc');
                                                
                                                $autoEnded = ($movie->end_date && $movie->end_date < now() && $statusValue === 'ended' && (is_object($movie->status) ? $movie->status->value : $movie->status) !== 'ended');
                                            @endphp
                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }} rounded-pill px-3 py-2">
                                                {{ $statusLabel }}
                                                @if($autoEnded)
                                                    <i class="bi bi-clock-history ms-1" title="Tự động cập nhật do đã quá ngày kết thúc"></i>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $movie->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.movies.show', $movie) }}"

                                                   class="btn btn-sm rounded-3 view-detail-btn" 
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
                                                    <span class="btn btn-sm rounded-3 edit-disabled-btn" 
                                                          title="{{ $disableReason }}"
                                                          data-bs-toggle="tooltip"
                                                          style="background-color: #dc3545 !important; border-color: #dc3545 !important; color: white !important;">
                                                        <i class="fas fa-edit" style="color: white !important;"></i>
                                                    </span>
                                                @else
                                                    <a href="{{ route('admin.movies.edit', $movie) }}"
                                                       class="btn btn-sm rounded-3 edit-btn" 
                                                       title="Chỉnh sửa"
                                                       data-bs-toggle="tooltip"
                                                       style="background-color: #212529 !important; border-color: #212529 !important; color: white !important;">
                                                        <i class="fas fa-edit" style="color: white !important;"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($movies->isEmpty())
                                    <tr>
                                        <td colspan="14" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-film text-muted fs-1 mb-3"></i>
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
                                {{ $movies->appends(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])->links('pagination::bootstrap-5') }}
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
<!-- Add Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Modern UI Styles */
.movie-poster-small {
    width: 45px;
    height: 65px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.movie-poster-small:hover {
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

/* Action Buttons */
.view-detail-btn {
    background-color: #6c757d !important;
    border: 1px solid #6c757d !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.view-detail-btn:hover {
    background-color: #5a6268 !important;
    border-color: #545b62 !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(108, 117, 125, 0.3);
}

/* EDIT BUTTON - MÀU ĐEN KHI CÓ THỂ SỬA: */
.edit-btn {
    background-color: #212529 !important;
    border: 1px solid #212529 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.edit-btn:hover {
    background-color: #343a40 !important;
    border-color: #343a40 !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(33, 37, 41, 0.3);
}

/* DISABLED EDIT BUTTON - MÀU ĐỎ KHI KHÔNG THỂ SỬA: */
.edit-disabled-btn {
    background-color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    color: white !important;
    min-width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: not-allowed;
    opacity: 1 !important; /* Thay đổi từ 0.8 thành 1 */
    transition: all 0.3s ease;
}

.edit-disabled-btn:hover {
    background-color: #c82333 !important;
    border-color: #bd2130 !important;
    color: white !important;
    opacity: 1 !important;
    box-shadow: 0 3px 6px rgba(220, 53, 69, 0.3);
    transform: none; /* Không có transform để nhấn mạnh là disabled */
}

/* ĐẢM BẢO ICON HIỂN THỊ ĐÚNG MÀU: */
.view-detail-btn i,
.edit-btn i {
    font-size: 14px;
    color: white !important;
}

.edit-disabled-btn i {
    font-size: 14px;
    color: white !important; /* Đảm bảo icon màu trắng trên nền đỏ */
}

/* Tooltip styling */
.tooltip {
    font-size: 12px;
}

/* Đảm bảo override mọi style Bootstrap có thể conflict */
.btn.edit-disabled-btn {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
}

.btn.edit-disabled-btn:hover,
.btn.edit-disabled-btn:focus,
.btn.edit-disabled-btn:active {
    background-color: #c82333 !important;
    border-color: #bd2130 !important;
    color: white !important;
}

/* Tất cả styles khác giữ nguyên... */
</style>

<!-- Initialize tooltips -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Logic giữ nguyên, chỉ cải thiện UI
    const autoEndedMovies = document.querySelectorAll('.badge.bg-danger i.bi-clock-history');
    if (autoEndedMovies.length > 0) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-4 mt-2';
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle bg-info-subtle">
                        <span class="avatar-title rounded-circle bg-info text-white">
                            <i class="bi bi-info-circle-fill"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Cập nhật tự động</h6>
                    <p class="mb-0">Có ${autoEndedMovies.length} phim đã được tự động cập nhật trạng thái thành "Kết thúc" do đã quá ngày kết thúc.</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    function updateDeleteButton() {
        const checked = document.querySelectorAll('.movie-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        if (checked.length > 0) {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }   

    document.getElementById('checkAllMovies')?.addEventListener('change', function() {
        document.querySelectorAll('.movie-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    document.querySelectorAll('.movie-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

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