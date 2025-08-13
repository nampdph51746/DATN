```blade
@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl px-4 py-5">
    @include('admin.partials.notifications')

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="avatar-lg rounded-circle bg-gradient-primary shadow">
                        <span class="avatar-title rounded-circle bg-primary text-white">
                            <i class="bi bi-plus-circle fs-2"></i>
                        </span>
                    </div>
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="bi bi-film me-2 text-primary"></i> Thêm phim mới
                    </h2>
                    <p class="text-muted mb-0 fs-5">Nhập thông tin chi tiết để thêm một phim mới vào hệ thống</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Preview -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top" style="top: 2rem;">
                <div class="card-body p-4 text-center bg-gradient-light">
                    <div class="position-relative d-inline-block mb-4">
                        <img id="imagePreview"
                             src="{{ asset('assets/images/movie-placeholder.png') }}"
                             alt="Movie Preview"
                             class="img-fluid rounded-4 shadow-lg border border-3 border-primary"
                             style="max-height: 350px; object-fit: cover; transition: transform 0.3s;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-gradient-success text-white rounded-pill px-3 py-2 fs-6 fw-semibold shadow">
                                <i class="bi bi-star-fill me-1"></i> Phim mới
                            </span>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">
                        <i class="bi bi-eye-fill text-primary me-1"></i> Xem trước poster
                    </h5>
                    <p class="text-muted mb-0 fs-6">Ảnh poster sẽ hiển thị ở đây khi bạn chọn file</p>
                </div>
                <div class="card-footer bg-white border-0 p-4 rounded-bottom-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <button type="submit" form="movieCreateForm"
                                    class="btn btn-gradient-primary w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill py-3 fw-semibold shadow">
                                <i class="bi bi-check-circle fs-5"></i> Lưu phim
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.movies.index') }}"
                               class="btn btn-gradient-secondary w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill py-3 fw-semibold shadow">
                                <i class="bi bi-x-circle fs-5"></i> Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-gradient-primary border-bottom-0 py-4 rounded-top-4 text-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-white shadow">
                                <span class="avatar-title rounded-circle bg-primary text-white">
                                    <i class="bi bi-film"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold">Thông tin phim</h5>
                            <p class="mb-0 small">Điền đầy đủ thông tin bên dưới</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 bg-gradient-light">
                    <form id="movieCreateForm" action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Thông tin cơ bản -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i> Thông tin cơ bản
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-film text-primary me-1"></i> Tên phim <span class="text-danger">*</span>
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
                                    <label for="duration_minutes" class="form-label">
                                        <i class="bi bi-clock text-warning me-1"></i> Thời lượng (phút) <span class="text-danger">*</span>
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

                        <!-- Đội ngũ sản xuất -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="bi bi-people me-2"></i> Đội ngũ sản xuất
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="director_ids" class="form-label">
                                        <i class="bi bi-person-video text-success me-1"></i> Đạo diễn <span class="text-danger">*</span>
                                    </label>
                                    <select name="director_ids[]" id="director_ids"
                                            class="form-control select2 @error('director_ids') is-invalid @enderror"
                                            multiple required data-placeholder="Chọn đạo diễn">
                                        @foreach ($directors as $director)
                                            <option value="{{ $director->id }}" {{ in_array($director->id, old('director_ids', [])) ? 'selected' : '' }}>
                                                {{ $director->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('director_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="actor_ids" class="form-label">
                                        <i class="bi bi-people-fill text-success me-1"></i> Diễn viên <span class="text-danger">*</span>
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
                                <i class="bi bi-calendar-event me-2"></i> Thông tin phát hành
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="release_date" class="form-label">
                                        <i class="bi bi-calendar-plus text-info me-1"></i> Ngày phát hành <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="release_date" id="release_date"
                                           class="form-control form-control-lg rounded-3 @error('release_date') is-invalid @enderror"
                                           value="{{ old('release_date', now()->format('Y-m-d')) }}" required>
                                    @error('release_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="end_date" class="form-label">
                                        <i class="bi bi-calendar-x text-info me-1"></i> Ngày kết thúc
                                    </label>
                                    <input type="date" name="end_date" id="end_date"
                                           class="form-control form-control-lg rounded-3 @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="language" class="form-label">
                                        <i class="bi bi-translate text-info me-1"></i> Ngôn ngữ <span class="text-danger">*</span>
                                    </label>
                                    <select name="language" id="language"
                                            class="form-control select2 @error('language') is-invalid @enderror" required>
                                        <option value="">Chọn ngôn ngữ</option>
                                        <option value="Tiếng Việt" {{ old('language') == 'Tiếng Việt' ? 'selected' : '' }}>Tiếng Việt</option>
                                        <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                        <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>French</option>
                                        <option value="Spanish" {{ old('language') == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                                        <option value="Chinese" {{ old('language') == 'Chinese' ? 'selected' : '' }}>Chinese</option>
                                        <option value="Japanese" {{ old('language') == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                                        <option value="Korean" {{ old('language') == 'Korean' ? 'selected' : '' }}>Korean</option>
                                        <option value="Other" {{ old('language') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="country_id" class="form-label">
                                        <i class="bi bi-globe text-info me-1"></i> Quốc gia <span class="text-danger">*</span>
                                    </label>
                                    <select name="country_id" id="country_id"
                                            class="form-control select2 @error('country_id') is-invalid @enderror" required>
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
                                <i class="bi bi-tags me-2"></i> Phân loại & trạng thái
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="age_limit_id" class="form-label">
                                        <i class="bi bi-shield-check text-warning me-1"></i> Giới hạn độ tuổi <span class="text-danger">*</span>
                                    </label>
                                    <select name="age_limit_id" id="age_limit_id"
                                            class="form-control select2 @error('age_limit_id') is-invalid @enderror" required>
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
                                    <label for="status" class="form-label">
                                        <i class="bi bi-flag text-warning me-1"></i> Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" id="status"
                                            class="form-control select2 @error('status') is-invalid @enderror" required>
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
                                    <label for="genre_ids" class="form-label">
                                        <i class="bi bi-bookmark-star text-warning me-1"></i> Thể loại <span class="text-danger">*</span>
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
                                <i class="bi bi-image me-2"></i> Media & đánh giá
                            </h6>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="poster_url" class="form-label">
                                        <i class="bi bi-link-45deg text-danger me-1"></i> URL Poster
                                    </label>
                                    <input type="url" name="poster_url" id="poster_url"
                                           class="form-control form-control-lg rounded-3 @error('poster_url') is-invalid @enderror"
                                           value="{{ old('poster_url') }}" placeholder="https://example.com/poster.jpg">
                                    @error('poster_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="trailer_url" class="form-label">
                                        <i class="bi bi-link-45deg text-danger me-1"></i> URL Trailer
                                    </label>
                                    <input type="url" name="trailer_url" id="trailer_url"
                                           class="form-control form-control-lg rounded-3 @error('trailer_url') is-invalid @enderror"
                                           value="{{ old('trailer_url') }}" placeholder="https://example.com/trailer.mp4">
                                    @error('trailer_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="image" class="form-label">
                                        <i class="bi bi-image-fill text-danger me-1"></i> Ảnh phim
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
                                    <label for="average_rating" class="form-label">
                                        <i class="bi bi-star-fill text-danger me-1"></i> Điểm đánh giá (0-10)
                                    </label>
                                    <select name="average_rating" id="average_rating"
                                            class="form-control select2 @error('average_rating') is-invalid @enderror">
                                        <option value="">Chọn điểm đánh giá</option>
                                        @for ($i = 0; $i <= 10; $i += 0.5)
                                            <option value="{{ $i }}" {{ old('average_rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('average_rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả phim -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bi bi-file-earmark-text me-2"></i> Mô tả phim
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
                            <button type="submit" class="btn btn-gradient-primary px-4 py-2 rounded-pill fw-bold shadow">
                                <i class="bi bi-plus-circle me-1"></i> Thêm phim
                            </button>
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-gradient-secondary px-4 py-2 rounded-pill fw-bold shadow">
                                <i class="bi bi-x-circle me-1"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 & Tailwind -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.6.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>

<!-- Custom Gradient Button & Card -->
<style>
.btn-gradient-primary {
    background: linear-gradient(90deg, #2563eb 0%, #9333ea 100%);
    color: #fff;
    border: none;
}
.btn-gradient-primary:hover {
    background: linear-gradient(90deg, #9333ea 0%, #2563eb 100%);
    color: #fff;
}
.btn-gradient-secondary {
    background: linear-gradient(90deg, #64748b 0%, #a3a3a3 100%);
    color: #fff;
    border: none;
}
.btn-gradient-secondary:hover {
    background: linear-gradient(90deg, #a3a3a3 0%, #64748b 100%);
    color: #fff;
}
.bg-gradient-primary {
    background: linear-gradient(90deg, #2563eb 0%, #9333ea 100%) !important;
}
.bg-gradient-success {
    background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%) !important;
}
.bg-gradient-light {
    background: linear-gradient(90deg, #f3f4f6 0%, #e0e7ff 100%) !important;
}
.shadow-lg {
    box-shadow: 0 8px 32px rgba(60,72,100,0.15) !important;
}
</style>

<!-- Select2 Customization -->
<style>
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple {
    min-height: 44px;
    border-radius: 0.75rem;
    background: #f3f4f6;
    font-size: 1rem;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(60,72,100,0.07);
    border: 1px solid #d1d5db;
}
.select2-container--default .select2-selection--single:focus-within,
.select2-container--default .select2-selection--multiple:focus-within {
    border-color: #9333ea;
    box-shadow: 0 0 0 2px #c7d2fe;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(90deg, #2563eb 0%, #9333ea 100%);
    color: #fff;
    border: none;
    border-radius: 0.5rem;
    padding: 0.25rem 0.75rem;
    margin: 0.15rem;
    font-size: 0.95rem;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(60,72,100,0.07);
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    font-weight: bold;
    opacity: 0.8;
    margin-right: 0.5rem;
}
.select2-dropdown {
    border-radius: 0.75rem;
    box-shadow: 0 8px 32px rgba(60,72,100,0.15);
    background: #fff;
    padding: 0.5rem;
    margin-top: 0.25rem;
    z-index: 50;
    animation: dropdownFadeIn 0.3s ease-in-out;
}
.select2-container--default .select2-results__option {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border-radius: 0.5rem;
    transition: all 0.15s;
}
.select2-container--default .select2-results__option--highlighted {
    background: linear-gradient(90deg, #2563eb 0%, #9333ea 100%);
    color: #fff;
}
.select2-container--default .select2-search--dropdown .select2-search__field {
    border-radius: 0.5rem;
    padding: 0.5rem;
    font-size: 1rem;
    border: 1px solid #d1d5db;
}
@keyframes dropdownFadeIn {
    from { opacity: 0; transform: translateY(-10px);}
    to { opacity: 1; transform: translateY(0);}
}
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}
.is-invalid + .select2-container .select2-selection--single,
.is-invalid + .select2-container .select2-selection--multiple {
    border-color: #ef4444;
}
.invalid-feedback {
    color: #ef4444;
    font-size: 0.95rem;
    margin-top: 0.25rem;
}
.img-thumbnail {
    max-width: 150px;
    margin-top: 0.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(60,72,100,0.07);
}
</style>

<script>
$(document).ready(function() {
    // Select2 Init
    $('#genre_ids').select2({
        placeholder: 'Chọn thể loại (gõ để tìm)',
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-dropdown',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bi bi-bookmark-star text-warning me-2"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) { return data.text; }
    });
    $('#actor_ids').select2({
        placeholder: 'Chọn diễn viên (gõ để tìm)',
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-dropdown',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bi bi-people-fill text-success me-2"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) { return data.text; }
    });
    $('#director_ids').select2({
        placeholder: 'Chọn đạo diễn (có thể chọn nhiều)',
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-dropdown',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bi bi-person-video text-success me-2"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) { return data.text; }
    });
    $('#language').select2({
        placeholder: 'Chọn ngôn ngữ',
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-dropdown',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bi bi-translate text-info me-2"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) { return data.text; }
    });
    $('#country_id').select2({
        placeholder: 'Chọn quốc gia',
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-dropdown',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bi bi-globe text-info me-2"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) { return data.text; }
    });
    $('#age_limit_id').select2({
        placeholder: 'Chọn giới hạn độ tuổi',
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-dropdown',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bi bi-shield-check text-warning me-2"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) { return data.text; }
    });
    $('#status').select2({
        placeholder: 'Chọn trạng thái',
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-dropdown',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bi bi-flag text-warning me-2"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) { return data.text; }
    });
    $('#average_rating').select2({
        placeholder: 'Chọn điểm đánh giá',
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-dropdown',
        templateResult: function(data) {
            if (data.loading) return data.text;
            return $('<span><i class="bi bi-star-fill text-warning me-2"></i>' + data.text + '</span>');
        },
        templateSelection: function(data) {
            return $('<span><i class="bi bi-star-fill text-warning me-1"></i>' + data.text + '</span>');
        }
    });

    // Form Validation
    $('#movieCreateForm').on('submit', function(e) {
        let isValid = true;
        let firstError = null;
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide();

        // Validate fields (same logic as before)
        const name = $('#name').val().trim();
        if (!name) { showFieldError('#name', 'Tên phim là trường bắt buộc'); isValid = false; }
        else if (name.length > 255) { showFieldError('#name', 'Tên phim không được vượt quá 255 ký tự'); isValid = false; }
        const directorIds = $('#director_ids').val();
        if (!directorIds || directorIds.length === 0) { showFieldError('#director_ids', 'Vui lòng chọn ít nhất một đạo diễn'); isValid = false; }
        const actorIds = $('#actor_ids').val();
        if (!actorIds || actorIds.length === 0) { showFieldError('#actor_ids', 'Vui lòng chọn ít nhất một diễn viên'); isValid = false; }
        const duration = $('#duration_minutes').val();
        if (!duration || duration < 1 || duration > 600) { showFieldError('#duration_minutes', 'Thời lượng phim phải từ 1 đến 600 phút'); isValid = false; }
        const language = $('#language').val();
        if (!language) { showFieldError('#language', 'Ngôn ngữ là trường bắt buộc'); isValid = false; }
        const countryId = $('#country_id').val();
        if (!countryId) { showFieldError('#country_id', 'Vui lòng chọn quốc gia'); isValid = false; }
        const ageLimitId = $('#age_limit_id').val();
        if (!ageLimitId) { showFieldError('#age_limit_id', 'Vui lòng chọn giới hạn độ tuổi'); isValid = false; }
        const status = $('#status').val();
        if (!status) { showFieldError('#status', 'Vui lòng chọn trạng thái phim'); isValid = false; }
        const genreIds = $('#genre_ids').val();
        if (!genreIds || genreIds.length === 0) { showFieldError('#genre_ids', 'Vui lòng chọn ít nhất một thể loại'); isValid = false; }
        const description = $('#description').val().trim();
        if (!description) { showFieldError('#description', 'Mô tả phim là trường bắt buộc'); isValid = false; }
        else if (description.length < 10) { showFieldError('#description', 'Mô tả phim phải có ít nhất 10 ký tự'); isValid = false; }
        const averageRating = $('#average_rating').val();
        if (averageRating && (averageRating < 0 || averageRating > 10)) { showFieldError('#average_rating', 'Điểm đánh giá phải từ 0 đến 10'); isValid = false; }

        if (!isValid) {
            e.preventDefault();
            if (firstError) {
                $('html, body').animate({ scrollTop: $(firstError).offset().top - 100 }, 300);
                $(firstError).focus();
            }
        }
        function showFieldError(fieldSelector, message) {
            if (!firstError) firstError = fieldSelector;
            $(fieldSelector).addClass('is-invalid');
            const $select2 = $(fieldSelector).next('.select2');
            if ($select2.length) { $select2.find('.select2-selection').addClass('is-invalid'); }
            $(fieldSelector).nextAll('.invalid-feedback').text(message).show();
        }
    });

    // Image Preview
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
            if (file) { alert('Vui lòng chọn file ảnh hợp lệ (jpeg, png, jpg, gif, webp).'); }
        }
    });
    $('.select2').on('select2:open', function() {
        $('.select2-dropdown').css('opacity', 0).animate({ opacity: 1 }, 300);
    });
});
</script>
@endsection