@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            @if(session('movie_created'))
                <small class="d-block mt-1 text-success-emphasis">
                    <i class="bi bi-info-circle me-1"></i>
                    Phim mới đã được thêm vào đầu danh sách bên dưới.
                </small>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center gap-2">
                    <h4 class="card-title flex-grow-1 mb-0">Danh Sách Phim</h4>
                    <form id="delete-selected-form" action="{{ route('admin.movies.bulkDelete') }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-movie-ids">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các phim đã chọn?')">
                            Xóa đã chọn
                        </button>
                    </form>
                    <form class="d-flex align-items-center gap-2 ms-2" method="GET" action="{{ route('admin.movies.index') }}" style="min-width:320px;">
                        <div class="position-relative flex-grow-1">
                            <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-2"
                                placeholder="Tìm kiếm tên phim..." autocomplete="off" value="{{ request('query') }}">
                            <iconify-icon icon="solar:magnifer-linear"
                                class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"
                                style="font-size: 16px;"></iconify-icon>
                        </div>
                        <select name="status" class="form-select form-select-sm" style="width:120px;">
                            <option value="">Tất cả trạng thái</option>
                            <option value="showing" {{ request('status') == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
                            <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.movies.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Thêm phim
                    </a>
                    <a href="{{ route('admin.directors.create') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-person-plus me-1"></i> Thêm đạo diễn
                    </a>
                    <a href="{{ route('admin.actors.create') }}" class="btn btn-sm btn-info">
                        <i class="bi bi-people-fill me-1"></i> Thêm diễn viên
                    </a>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                            Lọc
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Trạng thái</h6>
                                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">Tất cả</a>
                            @foreach (['showing', 'upcoming', 'ended'] as $status)
                                <a href="{{ route('admin.movies.index', array_filter(['status' => $status, 'query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('status') == $status ? 'active' : '' }}">
                                    {{ $status == 'showing' ? 'Đang chiếu' : ($status == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc') }}
                                </a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Quốc gia</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('country_id') ? 'active' : '' }}">Tất cả</a>
                            @foreach ($countries as $country)
                                <a href="{{ route('admin.movies.index', array_filter(['country_id' => $country->id, 'query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('country_id') == $country->id ? 'active' : '' }}">{{ $country->name }}</a>
                            @endforeach

                            <!-- Lọc theo giới hạn độ tuổi -->
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Giới hạn độ tuổi</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('age_limit_id') ? 'active' : '' }}">Tất cả</a>
                            @foreach ($ageLimits as $ageLimit)
                                <a href="{{ route('admin.movies.index', array_filter(['age_limit_id' => $ageLimit->id, 'query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('age_limit_id') == $ageLimit->id ? 'active' : '' }}">{{ $ageLimit->name ?? $ageLimit->label }}</a>
                            @endforeach

                            <!-- Lọc theo thể loại -->
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Thể loại</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('genre_id') ? 'active' : '' }}">Tất cả</a>
                            @foreach ($genres as $genre)
                                <a href="{{ route('admin.movies.index', array_filter(['genre_id' => $genre->id, 'query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('genre_id') == $genre->id ? 'active' : '' }}">{{ $genre->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="checkAllMovies">
                                        </div>
                                    </th>
                                    <th>Tên phim</th>
                                    <th>Đạo diễn</th>
                                    <th>Diễn viên</th>
                                    <th>Thể loại</th>
                                    <th>Thời lượng (phút)</th>
                                    <th>Ngày phát hành</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Ngôn ngữ</th>
                                    <th>Quốc gia</th>
                                    <th>Giới hạn tuổi</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian tạo</th>
                                    <th class="text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movies as $index => $movie)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                @php
                                                    $canBeDeleted = $movie->canBeEdited(); // Dùng chung logic với edit
                                                    $currentStatus = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                    // Kiểm tra trạng thái thực tế dựa trên ngày kết thúc
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
                                            {{ $movie->name }}
                                            @if(!$movie->canBeEdited())
                                                @if($movie->hasBookedTickets())
                                                    <span class="badge bg-warning-subtle text-warning ms-2" style="font-size: 0.7rem;">
                                                        <i class="bi bi-ticket-fill me-1"></i>Có vé đặt
                                                    </span>
                                                @elseif($currentStatus === 'showing')
                                                    <span class="badge bg-success-subtle text-success ms-2" style="font-size: 0.7rem;">
                                                        <i class="bi bi-play-circle-fill me-1"></i>Đang chiếu
                                                    </span>
                                                @elseif($currentStatus === 'ended')
                                                    <span class="badge bg-secondary-subtle text-secondary ms-2" style="font-size: 0.7rem;">
                                                        <i class="bi bi-stop-circle-fill me-1"></i>Đã kết thúc
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $movie->director?->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($movie->actors->isNotEmpty())
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($movie->actors->take(3) as $actor)
                                                        <span class="badge bg-primary-subtle text-primary" style="font-size: 0.75rem;">{{ $actor->name }}</span>
                                                    @endforeach
                                                    @if($movie->actors->count() > 3)
                                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.75rem;" title="{{ $movie->actors->pluck('name')->join(', ') }}">+{{ $movie->actors->count() - 3 }} khác</span>
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
                                                        <span class="badge bg-info-subtle text-info" style="font-size: 0.75rem;">{{ $genre->name }}</span>
                                                    @endforeach
                                                    @if($movie->genres->count() > 2)
                                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.75rem;" title="{{ $movie->genres->pluck('name')->join(', ') }}">+{{ $movie->genres->count() - 2 }} khác</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $movie->duration_minutes }}</td>
                                        <td>{{ $movie->release_date->format('d/m/Y') }}</td>
                                        <td>{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>{{ $movie->language ?? 'N/A' }}</td>
                                        <td>{{ $movie->country?->name ?? 'N/A' }}</td>
                                        <td>{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                // Kiểm tra trạng thái thực tế dựa trên ngày kết thúc
                                                $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                
                                                // Nếu có ngày kết thúc và đã qua ngày hiện tại, tự động coi là 'ended'
                                                if ($movie->end_date && $movie->end_date < now()) {
                                                    $statusValue = 'ended';
                                                }
                                                
                                                $statusColors = [
                                                    'showing' => 'bg-success',
                                                    'upcoming' => 'bg-warning',
                                                    'ended' => 'bg-danger',
                                                ];
                                                $statusLabel = $statusValue == 'showing' ? 'Đang chiếu' : ($statusValue == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc');
                                                
                                                // Hiển thị thông báo nếu trạng thái tự động thay đổi
                                                $autoEnded = ($movie->end_date && $movie->end_date < now() && $statusValue === 'ended' && (is_object($movie->status) ? $movie->status->value : $movie->status) !== 'ended');
                                            @endphp
                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                {{ $statusLabel }}
                                                @if($autoEnded)
                                                    <i class="bi bi-clock-history ms-1" title="Tự động cập nhật do đã quá ngày kết thúc"></i>
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $movie->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.movies.show', ['id' => $movie->id]) }}" class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                @php
                                                    $canBeEdited = $movie->canBeEdited();
                                                    $currentStatus = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                    // Kiểm tra trạng thái thực tế dựa trên ngày kết thúc
                                                    if ($movie->end_date && $movie->end_date < now()) {
                                                        $currentStatus = 'ended';
                                                    }
                                                    
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
                                                    <button class="btn btn-soft-secondary btn-sm" disabled title="{{ $disableReason }}">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                @else
                                                    <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa phim">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($movies->isEmpty())
                                    <tr>
                                        <td colspan="14" class="text-center text-muted">Không có phim nào.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $movies->appends(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* CSS cho ảnh poster nhỏ gọn */
.movie-poster-small {
    width: 45px;
    height: 65px;
    object-fit: cover;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.movie-poster-small:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* Responsive cho mobile */
@media (max-width: 768px) {
    .movie-poster-small {
        width: 35px;
        height: 50px;
    }
    
    .table td, .table th {
        padding: 0.3rem 0.2rem;
        font-size: 0.8rem;
    }
}

/* Style cho table */
.table-responsive {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    border-top: none;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

/* Alert styles */
.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d1edff 0%, #a8e6cf 100%);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

/* Style cho nút disabled và checkbox disabled */
.btn-soft-secondary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}

.form-check-input:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Style cho badges trong table */
.table .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}

/* Responsive cho badges */
@media (max-width: 768px) {
    .table .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    
    .d-flex.flex-wrap.gap-1 {
        gap: 0.25rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Kiểm tra và hiển thị thông báo cho phim tự động cập nhật trạng thái
    const autoEndedMovies = document.querySelectorAll('.badge.bg-danger i.bi-clock-history');
    if (autoEndedMovies.length > 0) {
        // Tạo thông báo nhỏ cho người dùng
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-info alert-dismissible fade show mt-2';
        alertDiv.innerHTML = `
            <i class="bi bi-info-circle-fill me-2"></i>
            Có ${autoEndedMovies.length} phim đã được tự động cập nhật trạng thái thành "Kết thúc" do đã quá ngày kết thúc.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Thêm thông báo vào đầu container
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Tự động ẩn thông báo sau 5 giây
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
            form.style.display = 'inline-block';
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