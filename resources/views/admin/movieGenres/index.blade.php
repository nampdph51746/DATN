@extends('layouts.admin.admin')

@section('content')
<!-- <div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh sách thể loại phim</h4>
                    <div class="d-flex gap-2">
                        <form id="delete-selected-genre-form" action="{{ route('admin.genres.bulkDelete') }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="ids" id="selected-genre-ids">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các thể loại đã chọn?')">
                                Xóa đã chọn
                            </button>
                        </form>
                        <a href="{{ route('admin.genres.create') }}" class="btn btn-primary btn-sm">Thêm thể loại</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkAllGenres">
                                        <label class="form-check-label" for="checkAllGenres"></label>
                                    </div>
                                </th>
                                <th>Tên thể loại</th>
                                <th>Mô tả</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($genres as $genre)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input genre-checkbox" value="{{ $genre->id }}" id="genreCheck{{ $genre->id }}">
                                            <label class="form-check-label" for="genreCheck{{ $genre->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $genre->name }}</td>
                                    <td>{{ $genre->description }}</td>
                                    <td>
                                        <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                        <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-2">
                        {{ $genres->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="container-fluid">

     <div class="row">
          <div class="col-xl-12">
               <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh sách thể loại phim</h4>
                    <div class="d-flex gap-2">
                        <form id="delete-selected-genre-form" action="{{ route('admin.genres.bulkDelete') }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="ids" id="selected-genre-ids">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các thể loại đã chọn?')">
                                Xóa đã chọn
                            </button>
                        </form>
                        <a href="{{ route('admin.genres.create') }}" class="btn btn-primary btn-sm">Thêm thể loại</a>
                    </div>
                </div>
                <div>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <!-- <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkAllGenres">
                                        <label class="form-check-label" for="checkAllGenres"></label>
                                    </div>
                                </th>
                                <th>Tên thể loại</th>
                                <th>Mô tả</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($genres as $genre)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input genre-checkbox" value="{{ $genre->id }}" id="genreCheck{{ $genre->id }}">
                                            <label class="form-check-label" for="genreCheck{{ $genre->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $genre->name }}</td>
                                    <td>{{ $genre->description }}</td>
                                    <td>
                                        <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                        <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> -->

                    <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>#</th>
                                    <th>Tên thể loại</th>
                                    <th>Mô tả</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($genres as $key => $genre)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $genre->name }}</td>
                                    <td>{{ $genre->description }}</td>                            
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                            </a>
                                            <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Không có thể loại nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    <div class="card-footer border-top">
                         <div class="d-flex justify-content-end mt-3">
                              {!! $genres->links('pagination::bootstrap-4') !!}
                         </div>
                    </div>
                </div>
            </div>
          </div>
     </div>
</div>
@endsection

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

    document.getElementById('checkAllGenres').addEventListener('change', function() {
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