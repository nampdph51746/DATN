@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center gap-3 py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:videocamera-record-bold" class="text-white fs-18"></iconify-icon>
                            </div>
                        </div>
                        <div>
                            <h4 class="card-title mb-0 text-dark fw-semibold">Quản lý phim</h4>
                            <p class="text-muted mb-0 small">Danh sách tất cả phim trong hệ thống</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <form class="app-search d-none d-md-block" method="GET" action="{{ route('admin.movies.index') }}">
                            <div class="position-relative">
                                <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-pill border-0 bg-light" placeholder="Tìm kiếm phim..." autocomplete="off" value="{{ request('query') }}" style="width: 280px;">
                                <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                            </div>
                        </form>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <iconify-icon icon="solar:filter-linear" style="font-size: 16px;"></iconify-icon>
                                <span class="d-none d-sm-inline">Lọc</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 200px;">
                                <!-- Lọc theo trạng thái -->
                                <div class="px-3 py-2 border-bottom">
                                    <h6 class="dropdown-header px-0 text-primary fw-semibold mb-2">
                                        <iconify-icon icon="solar:play-circle-linear" class="me-1"></iconify-icon>
                                        Trạng thái
                                    </h6>
                                    <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item rounded {{ !request('status') || request('status') == 'all' ? 'active bg-primary text-white' : '' }}">Tất cả</a>
                                    @foreach (['showing', 'upcoming', 'ended'] as $status)
                                        <a href="{{ route('admin.movies.index', array_filter(['status' => $status, 'query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item rounded {{ request('status') == $status ? 'active bg-primary text-white' : '' }}">
                                            {{ $status == 'showing' ? 'Đang chiếu' : ($status == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc') }}
                                        </a>
                                    @endforeach
                                </div>

                                <!-- Lọc theo quốc gia -->
                                <div class="px-3 py-2 border-bottom">
                                    <h6 class="dropdown-header px-0 text-primary fw-semibold mb-2">
                                        <iconify-icon icon="solar:global-linear" class="me-1"></iconify-icon>
                                        Quốc gia
                                    </h6>
                                    <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item rounded {{ !request('country_id') ? 'active bg-primary text-white' : '' }}">Tất cả</a>
                                    @foreach ($countries as $country)
                                        <a href="{{ route('admin.movies.index', array_filter(['country_id' => $country->id, 'query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item rounded {{ request('country_id') == $country->id ? 'active bg-primary text-white' : '' }}">{{ $country->name }}</a>
                                    @endforeach
                                </div>

                                <!-- Lọc theo giới hạn độ tuổi -->
                                <div class="px-3 py-2 border-bottom">
                                    <h6 class="dropdown-header px-0 text-primary fw-semibold mb-2">
                                        <iconify-icon icon="solar:shield-warning-linear" class="me-1"></iconify-icon>
                                        Giới hạn độ tuổi
                                    </h6>
                                    <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item rounded {{ !request('age_limit_id') ? 'active bg-primary text-white' : '' }}">Tất cả</a>
                                    @foreach ($ageLimits as $ageLimit)
                                        <a href="{{ route('admin.movies.index', array_filter(['age_limit_id' => $ageLimit->id, 'query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item rounded {{ request('age_limit_id') == $ageLimit->id ? 'active bg-primary text-white' : '' }}">{{ $ageLimit->name ?? $ageLimit->label }}</a>
                                    @endforeach
                                </div>

                                <!-- Lọc theo thể loại -->
                                <div class="px-3 py-2">
                                    <h6 class="dropdown-header px-0 text-primary fw-semibold mb-2">
                                        <iconify-icon icon="solar:tag-linear" class="me-1"></iconify-icon>
                                        Thể loại
                                    </h6>
                                    <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item rounded {{ !request('genre_id') ? 'active bg-primary text-white' : '' }}">Tất cả</a>
                                    @foreach ($genres as $genre)
                                        <a href="{{ route('admin.movies.index', array_filter(['genre_id' => $genre->id, 'query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item rounded {{ request('genre_id') == $genre->id ? 'active bg-primary text-white' : '' }}">{{ $genre->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.movies.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                            <iconify-icon icon="solar:add-circle-linear" style="font-size: 16px;"></iconify-icon>
                            <span class="d-none d-sm-inline">Thêm phim</span>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4" style="width: 60px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th class="border-0" style="width: 120px;">Ảnh</th>
                                    <th class="border-0 fw-semibold">Thông tin phim</th>
                                    <th class="border-0 fw-semibold" style="width: 120px;">Thời lượng</th>
                                    <th class="border-0 fw-semibold" style="width: 120px;">Ngày phát hành</th>
                                    <th class="border-0 fw-semibold" style="width: 120px;">Ngày kết thúc</th>
                                    <th class="border-0 fw-semibold" style="width: 100px;">Trạng thái</th>
                                    <th class="border-0 fw-semibold text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($movies as $movie)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $movie->id }}">
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="position-relative">
                                                <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ? $movie->poster_url : asset('client_assets/assets/images/default-movie.jpg')) }}" 
                                                     alt="{{ $movie->name }}" 
                                                     class="rounded shadow-sm" 
                                                     style="width: 80px; height: 120px; object-fit: cover;"
                                                     loading="lazy">
                                                <div class="position-absolute top-0 start-0 m-1">
                                                    <span class="badge bg-dark bg-opacity-75 text-white small">ID: {{ $movie->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 fw-semibold text-dark">{{ $movie->name }}</h6>
                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                    @if($movie->director)
                                                        <span class="badge bg-light text-dark border">
                                                            <iconify-icon icon="solar:user-linear" class="me-1" style="font-size: 12px;"></iconify-icon>
                                                            {{ $movie->director }}
                                                        </span>
                                                    @endif
                                                    @if($movie->language)
                                                        <span class="badge bg-light text-dark border">
                                                            <iconify-icon icon="solar:global-linear" class="me-1" style="font-size: 12px;"></iconify-icon>
                                                            {{ $movie->language }}
                                                        </span>
                                                    @endif
                                                    @if($movie->country)
                                                        <span class="badge bg-light text-dark border">
                                                            <iconify-icon icon="solar:flag-linear" class="me-1" style="font-size: 12px;"></iconify-icon>
                                                            {{ $movie->country->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($movie->genres->take(3) as $genre)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary small">{{ $genre->name }}</span>
                                                    @endforeach
                                                    @if($movie->genres->count() > 3)
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary small">+{{ $movie->genres->count() - 3 }}</span>
                                                    @endif
                                                </div>
                                                @if($movie->ageLimit)
                                                    <div class="mt-2">
                                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                                            <iconify-icon icon="solar:shield-warning-linear" class="me-1" style="font-size: 12px;"></iconify-icon>
                                                            {{ $movie->ageLimit->name ?? $movie->ageLimit->label }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <iconify-icon icon="solar:clock-circle-linear" class="text-muted me-1" style="font-size: 16px;"></iconify-icon>
                                                <span class="fw-medium">{{ $movie->duration_minutes }}</span>
                                                <small class="text-muted ms-1">phút</small>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $movie->release_date->format('d/m/Y') }}</span>
                                                <small class="text-muted">{{ $movie->release_date->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @if($movie->end_date)
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium text-dark">{{ $movie->end_date->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ $movie->end_date->diffForHumans() }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">Chưa xác định</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $statusConfig = [
                                                    'showing' => [
                                                        'color' => 'success',
                                                        'icon' => 'solar:play-circle-bold',
                                                        'label' => 'Đang chiếu'
                                                    ],
                                                    'upcoming' => [
                                                        'color' => 'warning',
                                                        'icon' => 'solar:clock-circle-bold',
                                                        'label' => 'Sắp chiếu'
                                                    ],
                                                    'ended' => [
                                                        'color' => 'danger',
                                                        'icon' => 'solar:stop-circle-bold',
                                                        'label' => 'Kết thúc'
                                                    ],
                                                ];
                                                $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                $config = $statusConfig[$statusValue] ?? ['color' => 'secondary', 'icon' => 'solar:question-circle-bold', 'label' => 'Không xác định'];
                                            @endphp
                                            <span class="badge bg-{{ $config['color'] }} bg-opacity-10 text-{{ $config['color'] }} d-flex align-items-center gap-1" style="width: fit-content;">
                                                <iconify-icon icon="{{ $config['icon'] }}" style="font-size: 12px;"></iconify-icon>
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.movies.show', ['id' => $movie->id]) }}" 
                                                   class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                                   style="width: 32px; height: 32px;"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-linear" style="font-size: 16px;"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.movies.edit', ['id' => $movie->id]) }}" 
                                                   class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                                   style="width: 32px; height: 32px;"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-linear" style="font-size: 16px;"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="mb-3">
                                                    <iconify-icon icon="solar:videocamera-record-broken" class="text-muted" style="font-size: 64px;"></iconify-icon>
                                                </div>
                                                <h5 class="text-muted mb-2">Không có phim nào</h5>
                                                <p class="text-muted mb-3">Hiện tại chưa có phim nào trong hệ thống</p>
                                                <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">
                                                    <iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
                                                    Thêm phim đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($movies->hasPages())
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center text-muted">
                        <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                        <small>
                            Hiển thị {{ $movies->firstItem() ?? 0 }} đến {{ $movies->lastItem() ?? 0 }} 
                            trong tổng số {{ $movies->total() }} phim
                        </small>
                    </div>
                    <div>
                        {{ $movies->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.dropdown-item {
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

.dropdown-item.active {
    background-color: var(--bs-primary) !important;
    color: white !important;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

.form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .d-none.d-md-block {
        display: none !important;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.6875rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Individual checkbox change handler
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
            selectAllCheckbox.checked = checkedBoxes.length === rowCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < rowCheckboxes.length;
        });
    });

    // Auto-submit search form with debounce
    const searchInput = document.querySelector('input[name="query"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
});
</script>
@endsection