@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.movies.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Thêm Phim
                        </a>
                        <a href="{{ route('admin.genres.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-tags"></i> Thể loại phim
                        </a>
                        <a href="{{ route('admin.age_limits.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-person-badge"></i> Độ tuổi
                        </a>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                            Lọc
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Trạng thái</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">Tất cả</a>
                            @foreach (['active', 'inactive', 'upcoming', 'ended'] as $status)
                                <a href="{{ route('admin.movies.index', array_filter(['status' => $status, 'query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('status') == $status ? 'active' : '' }}">{{ ucfirst($status) }}</a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Quốc gia</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('country_id') ? 'active' : '' }}">Tất cả</a>
                            @foreach ($countries as $country)
                                <a href="{{ route('admin.movies.index', array_filter(['country_id' => $country->id, 'query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('country_id') == $country->id ? 'active' : '' }}">{{ $country->name }}</a>
                            @endforeach

                            <!-- Lọc theo giới hạn độ tuổi -->
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Giới hạn độ tuổi</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('age_limit_id') ? 'active' : '' }}">Tất cả</a>
                            @foreach ($ageLimits as $ageLimit)
                                <a href="{{ route('admin.movies.index', array_filter(['age_limit_id' => $ageLimit->id, 'query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('age_limit_id') == $ageLimit->id ? 'active' : '' }}">{{ $ageLimit->name ?? $ageLimit->label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên phim</th>
                                    <th>Đạo diễn</th>
                                    <th>Thời lượng (phút)</th>
                                    <th>Ngày phát hành</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Ngôn ngữ</th>
                                    <th>Quốc gia</th>
                                    <th>Giới hạn tuổi</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movies as $index => $movie)
                                    <tr>
                                        <td>{{ $movie->id }}</td>
                                        <td>{{ $movie->name }}</td>
                                        <td>{{ $movie->director ?? 'N/A' }}</td>
                                        <td>{{ $movie->duration_minutes }}</td>
                                        <td>{{ $movie->release_date->format('d/m/Y') }}</td>
                                        <td>{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>{{ $movie->language ?? 'N/A' }}</td>
                                        <td>{{ $movie->country?->name ?? 'N/A' }}</td>
                                        <td>{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'bg-success',
                                                    'inactive' => 'bg-secondary',
                                                    'upcoming' => 'bg-warning',
                                                    'ended' => 'bg-danger',
                                                ];
                                                $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                            @endphp
                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                {{ ucfirst($statusValue) }}
                                            </span>
                                        </td>
                                        <td>{{ $movie->ageLimit->name ?? 'Chưa có' }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst(is_object($movie->status) ? $movie->status->value : $movie->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-soft-primary btn-sm" title="Sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc muốn xóa phim này không?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($movies->isEmpty())
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">Không có phim nào.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $movies->appends(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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
