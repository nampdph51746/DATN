@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    @include('admin.partials.notifications')
    
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img id="imagePreview" src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('assets/images/movie-placeholder.png')) }}" alt="{{ $movie->name }}" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Cập nhật phim {{ $movie->name }}</h4>
                        <p class="text-muted">Chỉnh sửa thông tin phim {{ $movie->name }}.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="movieEditForm" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-save fs-18"></i> Lưu
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-x fs-18"></i> Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin phim</h4>
                </div>
                <div class="card-body">
                    <form id="movieEditForm" action="{{ route('admin.movies.update', ['id' => $movie->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên phim <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $movie->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="director" class="form-label">Đạo diễn</label>
                                    <input type="text" name="director" id="director" class="form-control @error('director') is-invalid @enderror" value="{{ old('director', $movie->director) }}">
                                    @error('director')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="actors" class="form-label">Diễn viên</label>
                                    <input type="text" name="actors" id="actors" class="form-control @error('actors') is-invalid @enderror" value="{{ old('actors', $movie->actors) }}">
                                    @error('actors')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="duration_minutes" class="form-label">Thời lượng (phút) <span class="text-danger">*</span></label>
                                    <input type="number" name="duration_minutes" id="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $movie->duration_minutes) }}" min="1" required {{ $movie->showtimes()->exists() ? 'readonly' : '' }}>
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($movie->showtimes()->exists())
                                        <small class="text-muted">Thời lượng không thể thay đổi vì phim đã có suất chiếu.</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="release_date" class="form-label">Ngày phát hành <span class="text-danger">*</span></label>
                                    <input type="date" name="release_date" id="release_date" class="form-control @error('release_date') is-invalid @enderror" value="{{ old('release_date', $movie->release_date->format('Y-m-d')) }}" required>
                                    @error('release_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $movie->end_date ? $movie->end_date->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="language" class="form-label">Ngôn ngữ</label>
                                    <input type="text" name="language" id="language" class="form-control @error('language') is-invalid @enderror" value="{{ old('language', $movie->language) }}">
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Quốc gia</label>
                                    <select name="country_id" id="country_id" class="form-control @error('country_id') is-invalid @enderror">
                                        <option value="">Chọn quốc gia</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id', $movie->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="age_limit_id" class="form-label">Giới hạn độ tuổi</label>
                                    <select name="age_limit_id" id="age_limit_id" class="form-control @error('age_limit_id') is-invalid @enderror">
                                        <option value="">Chọn giới hạn độ tuổi</option>
                                        @foreach ($ageLimits as $ageLimit)
                                            <option value="{{ $ageLimit->id }}" {{ old('age_limit_id', $movie->age_limit_id) == $ageLimit->id ? 'selected' : '' }}>{{ $ageLimit->name ?? $ageLimit->label }}</option>
                                        @endforeach
                                    </select>
                                    @error('age_limit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        @foreach (\App\Enums\MovieStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ old('status', $movie->status->value) == $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="poster_url" class="form-label">URL Poster</label>
                                    <input type="url" name="poster_url" id="poster_url" class="form-control @error('poster_url') is-invalid @enderror" value="{{ old('poster_url', $movie->poster_url) }}">
                                    @error('poster_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="trailer_url" class="form-label">URL Trailer</label>
                                    <input type="url" name="trailer_url" id="trailer_url" class="form-control @error('trailer_url') is-invalid @enderror" value="{{ old('trailer_url', $movie->trailer_url) }}">
                                    @error('trailer_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Ảnh phim</label>
                                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                                        <p class="text-muted">Ảnh xem trước:</p>
                                        <img id="imagePreviewSmall" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                    @if ($movie->image_path)
                                        <div class="mt-2">
                                            <p class="text-muted">Ảnh hiện tại: <a href="{{ Storage::url($movie->image_path) }}" target="_blank">Xem ảnh</a></p>
                                            <img src="{{ Storage::url($movie->image_path) }}" alt="Current Image" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="genre_ids" class="form-label">Thể loại <span class="text-danger">*</span></label>
                                    <select name="genre_ids[]" id="genre_ids" class="form-control select2 @error('genre_ids') is-invalid @enderror" multiple required>
                                        @foreach ($genres as $genre)
                                            <option value="{{ $genre->id }}" {{ in_array($genre->id, old('genre_ids', $movie->genres->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $genre->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('genre_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="average_rating" class="form-label">Điểm đánh giá (0-10)</label>
                                    <input type="number" name="average_rating" id="average_rating" class="form-control @error('average_rating') is-invalid @enderror" value="{{ old('average_rating', $movie->average_rating) }}" step="0.1" min="0" max="10">
                                    @error('average_rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $movie->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm CSS và JS cho Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.6.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<!-- CSS tùy chỉnh cho Select2 và xem trước ảnh -->
<style>
.select2-container--default .select2-selection--multiple {
    min-height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 4px 8px;
    background-color: #fff;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #0d6efd;
    color: #fff;
    border: none;
    border-radius: 0.2rem;
    padding: 2px 6px;
    margin: 2px 3px;
    font-size: 0.85rem;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    margin-right: 4px;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}
.select2-container {
    width: 100% !important;
}
.img-thumbnail {
    max-width: 150px;
    margin-top: 10px;
}
</style>

<script>
$(document).ready(function() {
    $('#genre_ids').select2({
        placeholder: 'Chọn thể loại (gõ để tìm)',
        theme: 'bootstrap4',
        allowClear: true,
        width: 'resolve',
    });
});

// Xem trước ảnh
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewSmall = document.getElementById('imagePreviewSmall');
    const previewContainer = document.getElementById('imagePreviewContainer');

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewSmall.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('assets/images/movie-placeholder.png')) }}";
        previewSmall.src = "";
        previewContainer.style.display = 'none';
        if (file) {
            alert('Vui lòng chọn file ảnh hợp lệ (jpeg, png, jpg, gif).');
        }
    }
});
</script>
@endsection