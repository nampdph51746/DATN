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
                    <h4 class="card-title flex-grow-1 mb-0">Danh sách thể loại phim</h4>
                    <form id="delete-selected-genre-form" action="{{ route('admin.genres.bulkDelete') }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-genre-ids">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các thể loại đã chọn?')">
                            Xóa đã chọn
                        </button>
                    </form>
                    <form class="d-flex align-items-center gap-2 ms-2" method="GET" action="{{ route('admin.genres.index') }}" style="min-width:320px;">
                        <div class="position-relative flex-grow-1">
                            <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-2"
                                placeholder="Tìm kiếm thể loại..." autocomplete="off" value="{{ request('query') }}">
                            <iconify-icon icon="solar:magnifer-linear"
                                class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"
                                style="font-size: 16px;"></iconify-icon>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.genres.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Thêm thể loại
                    </a>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="checkAllGenres">
                                        </div>
                                    </th>
                                    <th>Tên thể loại</th>
                                    <th>Mô tả</th>
                                    <th>Thời gian tạo</th>
                                    <th>Thời gian cập nhật</th>
                                    <th class="text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($genres as $genre)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input genre-checkbox" value="{{ $genre->id }}" id="genreCheck{{ $genre->id }}">
                                            </div>
                                        </td>
                                        <td class="fw-semibold">{{ $genre->name }}</td>
                                        <td>{{ $genre->description }}</td>
                                        <td>
                                            {{ $genre->created_at ? $genre->created_at->format('d/m/Y H:i') : '' }}
                                        </td>
                                        <td>
                                            {{ $genre->updated_at ? $genre->updated_at->format('d/m/Y H:i') : '' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-soft-primary btn-sm" title="Sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($genres->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Không có thể loại nào.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $genres->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    function updateDeleteGenreButton() {
        const checked = document.querySelectorAll('.genre-checkbox:checked');
        const form = document.getElementById('delete-selected-genre-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }

    document.getElementById('checkAllGenres')?.addEventListener('change', function() {
        document.querySelectorAll('.genre-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteGenreButton();
    });

    document.querySelectorAll('.genre-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteGenreButton);
    });

    document.getElementById('delete-selected-genre-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.genre-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-genre-ids').value = checked.join(',');
    });
});
</script>
@endsection