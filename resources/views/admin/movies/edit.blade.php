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
                    <h4 class="card-title">Chỉnh sửa phim: {{ $movie->name }}</h4>
                </div>
                <div class="card-body">
                    <form id="movieEditForm" action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="director_id" class="form-label">
                                        <i class="bx bx-user-voice me-2 text-primary"></i>Đạo diễn 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="director_id" id="director_id" class="form-select @error('director_id') is-invalid @enderror" required>
                                        <option value="">🎬 Chọn đạo diễn</option>
                                        @foreach ($directors as $director)
                                            <option value="{{ $director->id }}" {{ old('director_id', $movie->director_id) == $director->id ? 'selected' : '' }}>
                                                👨‍💼 {{ $director->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('director_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="actor_ids" class="form-label">
                                        <i class="bx bx-group me-2 text-success"></i>Diễn viên 
                                        <span class="text-danger">*</span>
                                        <small class="text-muted">(Có thể chọn nhiều)</small>
                                    </label>
                                    <select name="actor_ids[]" id="actor_ids" class="form-select select2 @error('actor_ids') is-invalid @enderror" multiple required>
                                        @foreach ($actors as $actor)
                                            <option value="{{ $actor->id }}" {{ in_array($actor->id, old('actor_ids', $movie->actors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                🎭 {{ $actor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <i class="bx bx-info-circle me-1"></i>Gõ để tìm kiếm diễn viên
                                    </div>
                                    @error('actor_ids')
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
                        <!-- <div class="row">
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
                        </div> -->
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
                                    <label for="genre_ids" class="form-label">
                                        <i class="bx bx-category me-2 text-info"></i>Thể loại 
                                        <span class="text-danger">*</span>
                                        <small class="text-muted">(Có thể chọn nhiều)</small>
                                    </label>
                                    <select name="genre_ids[]" id="genre_ids" class="form-select select2 @error('genre_ids') is-invalid @enderror" multiple required>
                                        @foreach ($genres as $genre)
                                            <option value="{{ $genre->id }}" {{ in_array($genre->id, old('genre_ids', $movie->genres->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                🎨 {{ $genre->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <i class="bx bx-info-circle me-1"></i>Gõ để tìm kiếm thể loại
                                    </div>
                                    @error('genre_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="average_rating" class="form-label">Điểm đánh giá (0-10)</label>
                                    <input type="number" name="average_rating" id="average_rating" class="form-control @error('average_rating') is-invalid @enderror" value="{{ old('average_rating', $movie->average_rating) }}" step="0.1" min="0" max="10">
                                    @error('average_rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div> -->
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
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary">Hủy</a>
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
    min-height: 42px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.select2-container--default .select2-selection--multiple:focus-within {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    background: #ffffff;
}

.select2-container--default .select2-selection--single {
    min-height: 42px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.select2-container--default .select2-selection--single:focus,
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    background: #ffffff;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding-left: 16px;
    color: #374151;
    font-weight: 500;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px;
    right: 12px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 4px 12px;
    margin: 3px 4px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
    transition: all 0.2s ease;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(79, 70, 229, 0.3);
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    margin-right: 6px;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    font-size: 1rem;
    opacity: 0.8;
    transition: opacity 0.2s ease;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    opacity: 1;
    background: rgba(255,255,255,0.2);
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    flex-wrap: wrap;
    gap: 2px;
    padding: 4px;
    min-height: 30px;
}

.select2-container {
    width: 100% !important;
}

.select2-dropdown {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    margin-top: 4px;
}

.select2-container--default .select2-results__option {
    padding: 12px 16px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
}

.select2-container--default .select2-results__option:last-child {
    border-bottom: none;
}

.select2-container--default .select2-results__option--highlighted {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #fff;
    transform: translateX(4px);
}

.select2-container--default .select2-search--dropdown {
    padding: 12px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.9rem;
    width: 100%;
    transition: border-color 0.2s ease;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #4f46e5;
    outline: none;
}

.img-thumbnail {
    max-width: 150px;
    margin-top: 10px;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

/* Custom styles cho form labels */
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.form-label i {
    font-size: 1.1rem;
}

.form-text {
    color: #6b7280;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.text-danger {
    color: #dc3545 !important;
}

/* Responsive cho mobile */
@media (max-width: 768px) {
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        font-size: 0.75rem;
        padding: 2px 6px;
    }
    
    .img-thumbnail {
        max-width: 120px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Khởi tạo Select2 cho thể loại
    $('#genre_ids').select2({
        placeholder: '🎨 Chọn thể loại phim...',
        theme: 'bootstrap4',
        allowClear: true,
        width: 'resolve',
        closeOnSelect: false,
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span class="d-flex align-items-center"><i class="bx bx-category me-2 text-info"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) {
            return data.text;
        }
    });

    // Khởi tạo Select2 cho diễn viên
    $('#actor_ids').select2({
        placeholder: '🎭 Chọn diễn viên...',
        theme: 'bootstrap4',
        allowClear: true,
        width: 'resolve',
        closeOnSelect: false,
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span class="d-flex align-items-center"><i class="bx bx-user me-2 text-success"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) {
            return data.text;
        }
    });

    // Khởi tạo Select2 cho đạo diễn
    $('#director_id').select2({
        placeholder: '🎬 Chọn đạo diễn...', 
        theme: 'bootstrap4',
        allowClear: true,
        width: 'resolve',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span class="d-flex align-items-center"><i class="bx bx-user-voice me-2 text-primary"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) {
            return data.text;
        }
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

@push('scripts')
<script>
function previewPoster(event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('poster-preview').src = URL.createObjectURL(file);
    }
}
</script>
@endpush