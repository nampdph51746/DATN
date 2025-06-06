@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Danh Sách Tất Cả Phim</h4>
                        <form id="delete-selected-form" action="{{ route('admin.movies.bulkDelete') }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="ids" id="selected-movie-ids">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các phim đã chọn?')">
                                        Xóa đã chọn
                                    </button>
                                </form>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.movies.create') }}" class="btn btn-sm btn-primary">
                                Thêm Phim
                            </a>
                                <a href="{{ route('admin.genres.index') }}" class="btn btn-sm btn-primary">
                                    Thể loại phim
                                </a>
                                    <a href="{{ route('admin.age_limits.index') }}" class="btn btn-sm btn-primary">
                                        Độ tuổi
                                    </a>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Tháng Này
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#!" class="dropdown-item">Tải Xuống</a>
                                <a href="#!" class="dropdown-item">Xuất Dữ Liệu</a>
                                <a href="#!" class="dropdown-item">Nhập Dữ Liệu</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkAllMovies">
                                                <label class="form-check-label" for="checkAllMovies"></label>
                                            </div>
                                        </th>
                                        <th>Phim</th>
                                        <th>Thể loại</th>
                                        <th>Đạo Diễn</th>
                                        <th>Thời Lượng</th>
                                        <th>Ngày Phát Hành</th>
                                        <th>Ngôn Ngữ</th>
                                        <th>Đánh Giá</th>
                                        <th>Độ tuổi</th>
                                        <th>Trạng Thái</th>

                                        <th class="text-center" style="width: 120px;">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movies as $movie)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input movie-checkbox"
                                                        value="{{ $movie->id }}">
                                                    <label class="form-check-label"
                                                        for="movieCheck{{ $movie->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                        <a href="{{ route('admin.movies.show', $movie->id) }}">
                                                            <img src="{{ $movie->poster_url ? asset($movie->poster_url) : asset('assets/images/posters/default.png') }}"
                                                                alt="" class="avatar-md" style="cursor:pointer;">
                                                        </a>
                                                    </div>
                                                    <p class="text-dark fw-medium fs-15 mb-0">{{ $movie->name }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                @if($movie->genres && $movie->genres->count())
                                                    @foreach($movie->genres as $genre)
                                                        <span class="badge bg-info text-dark">{{ $genre->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Chưa có</span>
                                                @endif
                                            </td>
                                            <td>{{ $movie->director }}</td>
                                            <td>{{ $movie->duration_minutes }} phút</td>
                                            <td>{{ \Carbon\Carbon::parse($movie->release_date)->format('d-m-Y') }}</td>
                                            <td>{{ $movie->language }}</td>
                                            <td>{{ $movie->average_rating }}</td>
                                            <td>{{ $movie->ageLimit->name ?? 'Chưa có' }}</td>
                                            <td>{{ $movie->status }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-soft-primary btn-sm"
                                                        title="Sửa">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        {{ $movies->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Khi check/uncheck sẽ hiện hoặc ẩn nút xóa
    function updateDeleteButton() {
        const checked = document.querySelectorAll('.movie-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }

    // Chọn tất cả
    document.getElementById('checkAllMovies').addEventListener('change', function() {
        document.querySelectorAll('.movie-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    // Check từng phim
    document.querySelectorAll('.movie-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    // Khi submit form xóa, lấy id các phim đã chọn
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
