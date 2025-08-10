@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl px-4">
    @include('admin.partials.notifications')
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="avatar-lg rounded-circle bg-primary-subtle">
                        <span class="avatar-title rounded-circle bg-primary text-white">
                            <i class="bi bi-plus-circle fs-3"></i>
                        </span>
                    </div>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Thêm phim mới</h4>
                    <p class="text-muted mb-0">Nhập thông tin để thêm một phim mới vào hệ thống</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Sidebar với preview -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 2rem;">
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img id="imagePreview" 
                             src="{{ asset('assets/images/movie-placeholder.png') }}" 
                             alt="Movie Preview" 
                             class="img-fluid rounded-4 shadow-sm" 
                             style="max-height: 400px; object-fit: cover; transition: transform 0.3s ease;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6 fw-semibold">
                                <i class="bi bi-plus-circle me-1"></i>
                                Phim mới
                            </span>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Xem trước phim</h5>
                    <p class="text-muted mb-0 fs-6">Ảnh poster sẽ hiển thị ở đây khi bạn chọn file</p>
                </div>
                <div class="card-footer bg-light border-0 p-4 rounded-bottom-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <button type="submit" form="movieCreateForm" 
                                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill py-3 fw-semibold">
                                <i class="bi bi-check-circle fs-5"></i> 
                                Lưu phim
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.movies.index') }}" 
                               class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill py-3 fw-semibold">
                                <i class="bi bi-x-circle fs-5"></i> 
                                Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 py-4 rounded-top-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-white">
                                    <i class="bi bi-film"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Thông tin phim</h5>
                            <p class="text-muted mb-0 small">Điền đầy đủ thông tin bên dưới</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="movieCreateForm" action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Thông tin cơ bản -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Thông tin cơ bản
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-film text-primary me-1"></i>
                                        Tên phim <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required maxlength="255" 
                                           placeholder="Nhập tên phim...">
                                    <div class="form-text">Tối đa 255 ký tự</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="duration_minutes" class="form-label fw-semibold">
                                        <i class="bi bi-clock text-warning me-1"></i>
                                        Thời lượng (phút) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="duration_minutes" id="duration_minutes" 
                                           class="form-control form-control-lg rounded-3 @error('duration_minutes') is-invalid @enderror" 
                                           value="{{ old('duration_minutes') }}" min="1" max="600" required
                                           placeholder="90">
                                    <div class="form-text">Từ 1 đến 600 phút</div>
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Đạo diễn và diễn viên -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="bi bi-people me-2"></i>
                                Đội ngũ sản xuất
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="director_id" class="form-label fw-semibold">
                                        <i class="bi bi-person-video text-success me-1"></i>
                                        Đạo diễn <span class="text-danger">*</span>
                                    </label>
                                    <select name="director_id" id="director_id" 
                                            class="form-control form-control-lg rounded-3 @error('director_id') is-invalid @enderror" required>
                                        <option value="">Chọn đạo diễn</option>
                                        @foreach ($directors as $director)
                                            <option value="{{ $director->id }}" {{ old('director_id') == $director->id ? 'selected' : '' }}>
                                                {{ $director->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('director_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="actor_ids" class="form-label fw-semibold">
                                        <i class="bi bi-people-fill text-success me-1"></i>
                                        Diễn viên <span class="text-danger">*</span>
                                    </label>
                                    <select name="actor_ids[]" id="actor_ids" 
                                            class="form-control select2 @error('actor_ids') is-invalid @enderror" multiple required>
                                        @foreach ($actors as $actor)
                                            <option value="{{ $actor->id }}" {{ in_array($actor->id, old('actor_ids', [])) ? 'selected' : '' }}>
                                                {{ $actor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('actor_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin phát hành -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-info mb-3">
                                <i class="bi bi-calendar-event me-2"></i>
                                Thông tin phát hành
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="release_date" class="form-label fw-semibold">
                                        <i class="bi bi-calendar-plus text-info me-1"></i>
                                        Ngày phát hành <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="release_date" id="release_date" 
                                           class="form-control form-control-lg rounded-3 @error('release_date') is-invalid @enderror" 
                                           value="{{ old('release_date', now()->format('Y-m-d')) }}" required>
                                    @error('release_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="end_date" class="form-label fw-semibold">
                                        <i class="bi bi-calendar-x text-info me-1"></i>
                                        Ngày kết thúc
                                    </label>
                                    <input type="date" name="end_date" id="end_date" 
                                           class="form-control form-control-lg rounded-3 @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="language" class="form-label fw-semibold">
                                        <i class="bi bi-translate text-info me-1"></i>
                                        Ngôn ngữ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="language" id="language" 
                                           class="form-control form-control-lg rounded-3 @error('language') is-invalid @enderror" 
                                           value="{{ old('language') }}" required placeholder="Ví dụ: Tiếng Việt, English">
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="country_id" class="form-label fw-semibold">
                                        <i class="bi bi-globe text-info me-1"></i>
                                        Quốc gia <span class="text-danger">*</span>
                                    </label>
                                    <select name="country_id" id="country_id" 
                                            class="form-control form-control-lg rounded-3 @error('country_id') is-invalid @enderror" required>
                                        <option value="">Chọn quốc gia</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Phân loại và trạng thái -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-warning mb-3">
                                <i class="bi bi-tags me-2"></i>
                                Phân loại và trạng thái
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="age_limit_id" class="form-label fw-semibold">
                                        <i class="bi bi-shield-check text-warning me-1"></i>
                                        Giới hạn độ tuổi <span class="text-danger">*</span>
                                    </label>
                                    <select name="age_limit_id" id="age_limit_id" 
                                            class="form-control form-control-lg rounded-3 @error('age_limit_id') is-invalid @enderror" required>
                                        <option value="">Chọn giới hạn độ tuổi</option>
                                        @foreach ($ageLimits as $ageLimit)
                                            <option value="{{ $ageLimit->id }}" {{ old('age_limit_id') == $ageLimit->id ? 'selected' : '' }}>
                                                {{ $ageLimit->name ?? $ageLimit->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('age_limit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="status" class="form-label fw-semibold">
                                        <i class="bi bi-flag text-warning me-1"></i>
                                        Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" id="status" 
                                            class="form-control form-control-lg rounded-3 @error('status') is-invalid @enderror" required>
                                        <option value="">Chọn trạng thái</option>
                                        <option value="showing" {{ old('status') == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                                        <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
                                        <option value="ended" {{ old('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <label for="genre_ids" class="form-label fw-semibold">
                                        <i class="bi bi-bookmark-star text-warning me-1"></i>
                                        Thể loại <span class="text-danger">*</span>
                                    </label>
                                    <select name="genre_ids[]" id="genre_ids" 
                                            class="form-control select2 @error('genre_ids') is-invalid @enderror" multiple required>
                                        @foreach ($genres as $genre)
                                            <option value="{{ $genre->id }}" {{ in_array($genre->id, old('genre_ids', [])) ? 'selected' : '' }}>
                                                {{ $genre->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('genre_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Media và đánh giá -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-danger mb-3">
                                <i class="bi bi-image me-2"></i>
                                Media và đánh giá
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="poster_url" class="form-label fw-semibold">
                                        <i class="bi bi-link-45deg text-danger me-1"></i>
                                        URL Poster
                                    </label>
                                    <input type="url" name="poster_url" id="poster_url" 
                                           class="form-control form-control-lg rounded-3 @error('poster_url') is-invalid @enderror" 
                                           value="{{ old('poster_url') }}" placeholder="https://example.com/poster.jpg">
                                    @error('poster_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="trailer_url" class="form-label fw-semibold">
                                        <i class="bi bi-link-45deg text-danger me-1"></i>
                                        URL Trailer
                                    </label>
                                    <input type="url" name="trailer_url" id="trailer_url" 
                                           class="form-control form-control-lg rounded-3 @error('trailer_url') is-invalid @enderror" 
                                           value="{{ old('trailer_url') }}" placeholder="https://example.com/trailer.mp4">
                                    @error('trailer_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="image" class="form-label fw-semibold">
                                        <i class="bi bi-image-fill text-danger me-1"></i>
                                        Ảnh phim
                                    </label>
                                    <input type="file" name="image" id="image" 
                                           class="form-control form-control-lg rounded-3 @error('image') is-invalid @enderror" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                                        <p class="text-muted">Ảnh xem trước:</p>
                                        <img id="imagePreviewSmall" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="average_rating" class="form-label fw-semibold">
                                        <i class="bi bi-star-fill text-danger me-1"></i>
                                        Điểm đánh giá (0-10)
                                    </label>
                                    <input type="number" name="average_rating" id="average_rating" 
                                           class="form-control form-control-lg rounded-3 @error('average_rating') is-invalid @enderror" 
                                           value="{{ old('average_rating') }}" step="0.1" min="0" max="10">
                                    @error('average_rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả phim -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Mô tả phim
                            </h6>
                            <div class="form-floating">
                                <textarea name="description" id="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="5" required minlength="10" maxlength="5000" 
                                          placeholder="Nhập mô tả chi tiết về phim...">{{ old('description') }}</textarea>
                                <label for="description" class="form-label">Nhập mô tả chi tiết về phim...</label>
                                <div class="form-text">Từ 10 đến 5000 ký tự</div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Thêm phim</button>
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
    min-height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    padding: 4px 8px;
    background-color: #fff;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.select2-container--default .select2-selection--multiple:focus-within {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.select2-container--default .select2-selection--single {
    min-height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    background-color: #fff;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
    color: #495057;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
    right: 10px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #0d6efd;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    padding: 3px 8px;
    margin: 2px 3px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    margin-right: 4px;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    font-size: 1rem;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    opacity: 1;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    padding: 2px;
}

.select2-container {
    width: 100% !important;
}

.select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.select2-container--default .select2-results__option {
    padding: 8px 12px;
    font-size: 0.875rem;
    transition: background-color 0.15s ease;
}

.select2-container--default .select2-results__option--highlighted {
    background-color: #0d6efd;
    color: #fff;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 6px 12px;
    font-size: 0.875rem;
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
    margin-bottom: 0.5rem;
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
        placeholder: 'Chọn thể loại (gõ để tìm)',
        theme: 'bootstrap4',
        allowClear: true,
        width: 'resolve',
    });

    // Khởi tạo Select2 cho diễn viên
    $('#actor_ids').select2({
        placeholder: 'Chọn diễn viên (gõ để tìm)',
        theme: 'bootstrap4',
        allowClear: true,
        width: 'resolve',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bx bx-user me-2 text-primary"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) {
            return data.text;
        }
    });

    // Khởi tạo Select2 cho đạo diễn
    $('#director_id').select2({
        placeholder: 'Chọn đạo diễn (gõ để tìm)', 
        theme: 'bootstrap4',
        allowClear: true,
        width: 'resolve',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bx bx-user-voice me-2 text-success"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) {
            return data.text;
        }
    });

    // Validation cho form
    $('#movieCreateForm').on('submit', function(e) {
        let isValid = true;
        let firstError = null;

        // Reset tất cả lỗi cũ
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide();

        // Validate tên phim
        const name = $('#name').val().trim();
        if (!name) {
            showFieldError('#name', 'Tên phim là trường bắt buộc');
            isValid = false;
        } else if (name.length > 255) {
            showFieldError('#name', 'Tên phim không được vượt quá 255 ký tự');
            isValid = false;
        }

        // Validate đạo diễn
        const directorId = $('#director_id').val();
        if (!directorId) {
            showFieldError('#director_id', 'Vui lòng chọn đạo diễn');
            isValid = false;
        }

        // Validate diễn viên
        const actorIds = $('#actor_ids').val();
        if (!actorIds || actorIds.length === 0) {
            showFieldError('#actor_ids', 'Vui lòng chọn ít nhất một diễn viên');
            isValid = false;
        }

        // Validate thời lượng
        const duration = $('#duration_minutes').val();
        if (!duration || duration < 1 || duration > 600) {
            showFieldError('#duration_minutes', 'Thời lượng phim phải từ 1 đến 600 phút');
            isValid = false;
        }

        // Validate ngôn ngữ
        const language = $('#language').val().trim();
        if (!language) {
            showFieldError('#language', 'Ngôn ngữ là trường bắt buộc');
            isValid = false;
        }

        // Validate quốc gia
        const countryId = $('#country_id').val();
        if (!countryId) {
            showFieldError('#country_id', 'Vui lòng chọn quốc gia');
            isValid = false;
        }

        // Validate giới hạn độ tuổi
        const ageLimitId = $('#age_limit_id').val();
        if (!ageLimitId) {
            showFieldError('#age_limit_id', 'Vui lòng chọn giới hạn độ tuổi');
            isValid = false;
        }

        // Validate trạng thái
        const status = $('#status').val();
        if (!status) {
            showFieldError('#status', 'Vui lòng chọn trạng thái phim');
            isValid = false;
        }

        // Validate thể loại
        const genreIds = $('#genre_ids').val();
        if (!genreIds || genreIds.length === 0) {
            showFieldError('#genre_ids', 'Vui lòng chọn ít nhất một thể loại');
            isValid = false;
        }

        // Validate mô tả
        const description = $('#description').val().trim();
        if (!description) {
            showFieldError('#description', 'Mô tả phim là trường bắt buộc');
            isValid = false;
        } else if (description.length < 10) {
            showFieldError('#description', 'Mô tả phim phải có ít nhất 10 ký tự');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            // Scroll đến lỗi đầu tiên
            if (firstError) {
                $('html, body').animate({
                    scrollTop: $(firstError).offset().top - 100
                }, 300);
                $(firstError).focus();
            }
        }

        function showFieldError(fieldSelector, message) {
            if (!firstError) firstError = fieldSelector;
            $(fieldSelector).addClass('is-invalid');
            $(fieldSelector).next('.invalid-feedback').text(message).show();
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
            if (previewSmall) previewSmall.src = e.target.result;
            if (previewContainer) previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "{{ asset('assets/images/movie-placeholder.png') }}";
        if (previewSmall) previewSmall.src = "";
        if (previewContainer) previewContainer.style.display = 'none';
        if (file) {
            alert('Vui lòng chọn file ảnh hợp lệ (jpeg, png, jpg, gif, webp).');
        }
    }
});
</script>
@endsection