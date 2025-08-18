@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-primary rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="bi bi-pencil-square fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chỉnh sửa phim</h2>
                                <p class="mb-0 opacity-90 fs-5">Cập nhật thông tin phim "{{ $movie->name }}"</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-warning rounded-pill px-4 py-3 fs-5 fw-semibold shadow">
                                <i class="bi bi-clock-history me-2"></i>
                                Đang chỉnh sửa
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-pencil-square" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Sidebar với Movie Preview -->
        <div class="col-xl-3 col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <!-- Movie Preview Card -->
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-image text-primary"></i>
                            Ảnh phim hiện tại
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="position-relative">
                            <img id="imagePreview" 
                                 src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('assets/images/movie-placeholder.png')) }}" 
                                 alt="{{ $movie->name }}" 
                                 class="img-fluid w-100 rounded-bottom-4" 
                                 style="height: 400px; object-fit: cover;">
                            
                            <!-- Overlay gradient -->
                            <div class="position-absolute bottom-0 start-0 end-0 bg-gradient-dark rounded-bottom-4" 
                                 style="background: linear-gradient(transparent, rgba(0,0,0,0.8)); height: 100px;">
                            </div>
                            
                            <!-- Movie info overlay -->
                            <div class="position-absolute bottom-0 start-0 end-0 p-4 text-white">
                                <h6 class="fw-bold mb-1">{{ $movie->name }}</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small">
                                        <i class="bi bi-clock me-1"></i>{{ $movie->duration_minutes }}min
                                    </span>
                                    <span class="badge bg-info rounded-pill px-2 py-1 small">
                                        <i class="bi bi-star me-1"></i>{{ $movie->average_rating ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons Card -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge text-warning"></i>
                            Thao tác
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <button type="submit" form="movieEditForm" 
                                    class="btn btn-primary btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <i class="bi bi-check-circle"></i>
                                Lưu thay đổi
                            </button>
                            
                            <a href="{{ route('admin.movies.show', $movie->id) }}" 
                               class="btn btn-outline-info btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-eye"></i>
                                Xem chi tiết
                            </a>
                            
                            <a href="{{ route('admin.movies.index') }}" 
                               class="btn btn-outline-secondary btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-arrow-left"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="col-xl-9 col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md rounded-circle bg-primary-subtle">
                            <span class="avatar-title rounded-circle bg-primary text-white">
                                <i class="bi bi-film"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark">Thông tin phim</h4>
                            <p class="text-muted mb-0">Cập nhật thông tin chi tiết của phim</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form id="movieEditForm" action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-primary-subtle">
                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                            <i class="bi bi-info-circle"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-primary">Thông tin cơ bản</h5>
                                        <p class="text-muted mb-0 small">Tên phim, đạo diễn và thông tin chính</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="bi bi-film text-primary me-1"></i>
                                            Tên phim <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" id="name"
                                               class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                                               value="{{ old('name', $movie->name) }}" required maxlength="255"
                                               placeholder="Nhập tên phim...">
                                        <div class="form-text">
                                            Tối đa 255 ký tự.
                                            <span id="duplicate-movie-count" class="text-danger fw-bold ms-2">
                                                @if(isset($duplicateCount) && $duplicateCount > 0)
                                                    Đã có {{ $duplicateCount }} phim trùng tên này!
                                                @endif
                                            </span>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="director_ids" class="form-label fw-bold mb-3">
                                            <i class="bi bi-person-video me-2 text-success"></i>Đạo diễn <span class="text-danger">*</span>
                                        </label>
                                        <select name="director_ids[]" id="director_ids" 
                                                class="form-control select2 @error('director_ids') is-invalid @enderror" 
                                                multiple required>
                                            @foreach ($directors as $director)
                                                <option value="{{ $director->id }}" 
                                                    {{ in_array($director->id, old('director_ids', $movie->directors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                    {{ $director->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('director_ids')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="actor_ids" class="form-label fw-bold mb-3">
                                            <i class="bi bi-people me-2 text-info"></i>Diễn viên <span class="text-danger">*</span>
                                        </label>
                                        <select name="actor_ids[]" id="actor_ids" 
                                                class="form-control select2 @error('actor_ids') is-invalid @enderror" 
                                                multiple required>
                                            @foreach ($actors as $actor)
                                                <option value="{{ $actor->id }}" 
                                                        {{ in_array($actor->id, old('actor_ids', $movie->actors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                    {{ $actor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('actor_ids')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="number" name="duration_minutes" id="duration_minutes" 
                                               class="form-control form-control-lg @error('duration_minutes') is-invalid @enderror" 
                                               value="{{ old('duration_minutes', $movie->duration_minutes) }}" 
                                               min="1" placeholder="Thời lượng" required 
                                               {{ $movie->showtimes()->exists() ? 'readonly' : '' }}>
                                        <label for="duration_minutes">
                                            <i class="bi bi-clock me-2 text-warning"></i>Thời lượng (phút) <span class="text-danger">*</span>
                                        </label>
                                        @error('duration_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if ($movie->showtimes()->exists())
                                            <div class="form-text text-warning">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Thời lượng không thể thay đổi vì phim đã có suất chiếu.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Location Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-success-subtle">
                                        <span class="avatar-title rounded-circle bg-success text-white">
                                            <i class="bi bi-calendar-event"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-success">Thời gian & Vị trí</h5>
                                        <p class="text-muted mb-0 small">Ngày phát hành, quốc gia và ngôn ngữ</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="date" name="release_date" id="release_date" 
                                               class="form-control form-control-lg @error('release_date') is-invalid @enderror" 
                                               value="{{ old('release_date', $movie->release_date->format('Y-m-d')) }}" required>
                                        <label for="release_date">
                                            <i class="bi bi-calendar-plus me-2 text-primary"></i>Ngày phát hành <span class="text-danger">*</span>
                                        </label>
                                        @error('release_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="date" name="end_date" id="end_date" 
                                               class="form-control form-control-lg @error('end_date') is-invalid @enderror" 
                                               value="{{ old('end_date', $movie->end_date ? $movie->end_date->format('Y-m-d') : '') }}">
                                        <label for="end_date">
                                            <i class="bi bi-calendar-x me-2 text-danger"></i>Ngày kết thúc
                                        </label>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="text" name="language" id="language" 
                                               class="form-control form-control-lg @error('language') is-invalid @enderror" 
                                               value="{{ old('language', $movie->language) }}" placeholder="Ngôn ngữ">
                                        <label for="language">
                                            <i class="bi bi-translate me-2 text-info"></i>Ngôn ngữ
                                        </label>
                                        @error('language')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <select name="country_id" id="country_id" 
                                                class="form-select form-control-lg @error('country_id') is-invalid @enderror">
                                            <option value="">Chọn quốc gia</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('country_id', $movie->country_id) == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="country_id">
                                            <i class="bi bi-globe me-2 text-primary"></i>Quốc gia
                                        </label>
                                        @error('country_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Classification & Status Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-warning-subtle">
                                        <span class="avatar-title rounded-circle bg-warning text-white">
                                            <i class="bi bi-shield-check"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-warning">Phân loại & Trạng thái</h5>
                                        <p class="text-muted mb-0 small">Giới hạn độ tuổi, thể loại và trạng thái phim</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <select name="age_limit_id" id="age_limit_id" 
                                                class="form-select form-control-lg @error('age_limit_id') is-invalid @enderror">
                                            <option value="">Chọn giới hạn độ tuổi</option>
                                            @foreach ($ageLimits as $ageLimit)
                                                <option value="{{ $ageLimit->id }}" {{ old('age_limit_id', $movie->age_limit_id) == $ageLimit->id ? 'selected' : '' }}>
                                                    {{ $ageLimit->name ?? $ageLimit->label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="age_limit_id">
                                            <i class="bi bi-person-check me-2 text-warning"></i>Giới hạn độ tuổi
                                        </label>
                                        @error('age_limit_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <select name="status" id="status" 
                                                class="form-select form-control-lg @error('status') is-invalid @enderror" required>
                                            @foreach (\App\Enums\MovieStatus::cases() as $status)
                                                <option value="{{ $status->value }}" {{ old('status', $movie->status->value) == $status->value ? 'selected' : '' }}>
                                                    {{ ucfirst($status->value) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="status">
                                            <i class="bi bi-flag me-2 text-primary"></i>Trạng thái <span class="text-danger">*</span>
                                        </label>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="genre_ids" class="form-label fw-bold mb-3">
                                            <i class="bi bi-bookmark-star me-2 text-success"></i>Thể loại <span class="text-danger">*</span>
                                        </label>
                                        <select name="genre_ids[]" id="genre_ids" 
                                                class="form-control select2 @error('genre_ids') is-invalid @enderror" 
                                                multiple required>
                                            @foreach ($genres as $genre)
                                                <option value="{{ $genre->id }}" 
                                                        {{ in_array($genre->id, old('genre_ids', $movie->genres->pluck('id')->toArray())) ? 'selected' : '' }}>
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
                        </div>

                        <!-- Media & Links Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-info-subtle">
                                        <span class="avatar-title rounded-circle bg-info text-white">
                                            <i class="bi bi-collection-play"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-info">Media & Liên kết</h5>
                                        <p class="text-muted mb-0 small">Ảnh, poster và trailer</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="url" name="poster_url" id="poster_url" 
                                               class="form-control form-control-lg @error('poster_url') is-invalid @enderror" 
                                               value="{{ old('poster_url', $movie->poster_url) }}" placeholder="URL Poster">
                                        <label for="poster_url">
                                            <i class="bi bi-image me-2 text-primary"></i>URL Poster
                                        </label>
                                        @error('poster_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="url" name="trailer_url" id="trailer_url" 
                                               class="form-control form-control-lg @error('trailer_url') is-invalid @enderror" 
                                               value="{{ old('trailer_url', $movie->trailer_url) }}" placeholder="URL Trailer">
                                        <label for="trailer_url">
                                            <i class="bi bi-play-circle me-2 text-danger"></i>URL Trailer
                                        </label>
                                        @error('trailer_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="upload-container">
                                        <label for="image" class="form-label fw-bold mb-3">
                                            <i class="bi bi-cloud-upload me-2 text-success"></i>Ảnh phim mới
                                        </label>
                                        <div class="upload-area rounded-3 border-2 border-dashed border-primary p-4 text-center bg-light-subtle">
                                            <input type="file" name="image" id="image" 
                                                   class="form-control @error('image') is-invalid @enderror" 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif" style="display: none;">
                                            <div class="upload-content" onclick="document.getElementById('image').click()">
                                                <i class="bi bi-cloud-upload fs-1 text-primary mb-3"></i>
                                                <h6 class="fw-bold text-primary">Nhấp để chọn ảnh</h6>
                                                <p class="text-muted small mb-0">JPG, PNG, GIF tối đa 2MB</p>
                                            </div>
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Preview Container -->
                                        <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                                            <div class="preview-card bg-light rounded-3 p-3">
                                                <p class="text-muted mb-2 small fw-bold">
                                                    <i class="bi bi-eye me-1"></i>Ảnh xem trước:
                                                </p>
                                                <img id="imagePreviewSmall" class="img-thumbnail rounded-3 shadow-sm" style="max-width: 150px;">
                                            </div>
                                        </div>
                                        
                                        @if ($movie->image_path)
                                            <div class="mt-3">
                                                <div class="current-image-card bg-light rounded-3 p-3">
                                                    <p class="text-muted mb-2 small fw-bold">
                                                        <i class="bi bi-image me-1"></i>Ảnh hiện tại: 
                                                        <a href="{{ Storage::url($movie->image_path) }}" target="_blank" class="text-primary">Xem ảnh</a>
                                                    </p>
                                                    <img src="{{ Storage::url($movie->image_path) }}" alt="Current Image" 
                                                         class="img-thumbnail rounded-3 shadow-sm" style="max-width: 150px;">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="number" name="average_rating" id="average_rating" 
                                               class="form-control form-control-lg @error('average_rating') is-invalid @enderror" 
                                               value="{{ old('average_rating', $movie->average_rating) }}" 
                                               step="0.1" min="0" max="10" placeholder="Điểm đánh giá">
                                        <label for="average_rating">
                                            <i class="bi bi-star me-2 text-warning"></i>Điểm đánh giá (0-10)
                                        </label>
                                        @error('average_rating')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="form-section mb-4">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-secondary-subtle">
                                        <span class="avatar-title rounded-circle bg-secondary text-white">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-secondary">Mô tả</h5>
                                        <p class="text-muted mb-0 small">Nội dung và câu chuyện của phim</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating">
                                <textarea name="description" id="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          style="height: 120px;" placeholder="Mô tả phim">{{ old('description', $movie->description) }}</textarea>
                                <label for="description">
                                    <i class="bi bi-journal-text me-2 text-secondary"></i>Mô tả phim
                                </label>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <a href="{{ route('admin.movies.index') }}" 
                               class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-check-circle me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS và JavaScript không thay đổi -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.6.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<style>
/* Gradient Background */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-dark {
    background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.8));
}

/* Form Sections */
.form-section {
    position: relative;
}

.section-header {
    position: relative;
}

.section-header::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
    border-radius: 2px;
}

/* Form Controls */
.form-floating > .form-control,
.form-floating > .form-select {
    height: calc(3.5rem + 2px);
    font-size: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.1);
    transform: translateY(-1px);
}

.form-floating > label {
    font-weight: 600;
    color: #6c757d;
    padding: 1rem 1rem;
}

/* Select2 Styling */
.select2-container--default .select2-selection--multiple {
    min-height: 56px;
    border: 2px solid #e9ecef !important;
    border-radius: 0.75rem !important;
    padding: 8px 12px;
    background-color: #fff;
    transition: all 0.3s ease;
}

.select2-container--default .select2-selection--multiple:focus-within,
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: var(--bs-primary) !important;
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.1);
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: var(--bs-primary);
    color: #fff;
    border: none;
    border-radius: 0.5rem;
    padding: 6px 12px;
    margin: 4px 6px 4px 0;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    opacity: 1;
}

.select2-container {
    width: 100% !important;
}

.select2-dropdown {
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-top: none;
}

.select2-container--default .select2-results__option {
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.select2-container--default .select2-results__option--highlighted {
    background-color: var(--bs-primary);
    color: #fff;
}

/* Upload Area */
.upload-area {
    cursor: pointer;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.upload-area:hover {
    border-color: var(--bs-primary) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.upload-content {
    cursor: pointer;
}

/* Preview Cards */
.preview-card,
.current-image-card {
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.preview-card:hover,
.current-image-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Avatar Sizes */
.avatar-xl {
    width: 5rem;
    height: 5rem;
}

.avatar-md {
    width: 3rem;
    height: 3rem;
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Button Enhancements */
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
    border-width: 2px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1rem;
}

/* Card Enhancements */
.card {
    transition: all 0.3s ease;
    border: none !important;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15) !important;
}

/* Sticky Position */
.sticky-top {
    position: sticky;
    z-index: 1020;
}

/* Color Subtle Backgrounds */
.bg-primary-subtle {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

.bg-secondary-subtle {
    background-color: rgba(var(--bs-secondary-rgb), 0.1) !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
    
    .btn-lg {
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .form-floating > .form-control,
    .form-floating > .form-select {
        height: calc(3rem + 2px);
    }
    
    .select2-container--default .select2-selection--multiple {
        min-height: 48px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-section {
    animation: fadeInUp 0.5s ease-out;
}

.form-section:nth-child(2) { animation-delay: 0.1s; }
.form-section:nth-child(3) { animation-delay: 0.2s; }
.form-section:nth-child(4) { animation-delay: 0.3s; }
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
