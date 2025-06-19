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
                            @foreach (['showing', 'upcoming', 'ended'] as $status)
                                <a href="{{ route('admin.movies.index', ['status' => $status]) }}" class="dropdown-item {{ request('status') == $status ? 'active' : '' }}">
                                    {{ ucfirst($status) }}
                                </a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Quốc gia</h6>
                            @foreach ($countries as $country)
                                <a href="{{ route('admin.movies.index', ['country_id' => $country->id]) }}" class="dropdown-item {{ request('country_id') == $country->id ? 'active' : '' }}">
                                    {{ $country->name }}
                                </a>
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
                                    <th>Phim & Poster</th>
                                    <th>Đạo Diễn</th>
                                    <th>Thời Lượng</th>
                                    <th>Ngày Phát Hành</th>
                                    <th>Quốc Gia</th>
                                    <th>Đánh Giá</th>
                                    <th>Độ tuổi</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-center" style="width: 120px;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movies as $index => $movie)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input movie-checkbox" value="{{ $movie->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                    <a href="{{ route('admin.movies.show', $movie->id) }}">
                                                        <img
                                                            src="{{ $movie->poster_url ? asset('storage/'.$movie->poster_url) : asset('assets/images/default-poster.png') }}"
                                                            alt="Poster"
                                                            class="rounded bg-light"
                                                            style="width: 48px; height: 64px; object-fit: cover;">
                                                    </a>
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.movies.show', $movie->id) }}" class="fw-bold text-dark fs-15 mb-0">{{ $movie->name }}</a>
                                                    <div class="text-muted small">
                                                        @if($movie->genres && $movie->genres->count())
                                                            @foreach($movie->genres as $genre)
                                                                <span class="badge bg-info text-dark">{{ $genre->name }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">Chưa có thể loại</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $movie->director }}</td>
                                        <td>{{ $movie->duration_minutes }} phút</td>
                                        <td>
                                            {{ $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td>{{ $movie->country->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge p-1 bg-light text-dark fs-12 me-1">
                                                <i class="bi bi-star-fill text-warning"></i> {{ $movie->average_rating ?? 'N/A' }}
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
                        {{ $movies->appends(request()->query())->links('pagination::bootstrap-5') }}
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
