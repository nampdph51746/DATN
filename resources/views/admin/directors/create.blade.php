@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-success rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="bi bi-person-video fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Thêm đạo diễn mới</h2>
                                <p class="mb-0 opacity-90 fs-5">Nhập thông tin để thêm một đạo diễn mới vào hệ thống</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-light text-success rounded-pill px-4 py-3 fs-5 fw-semibold shadow">
                                <i class="bi bi-plus-circle me-2"></i>
                                Tạo mới
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-person-video" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Sidebar với Preview -->
        <div class="col-xl-3 col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <!-- Preview Card -->
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-image text-primary"></i>
                            Ảnh xem trước
                        </h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="position-relative d-inline-block">
                            <img id="imagePreview" 
                                 src="{{ asset('assets/images/director-placeholder.png') }}" 
                                 alt="Director Image" 
                                 class="img-fluid rounded-circle shadow-lg mb-3" 
                                 style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #fff;">
                            <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 shadow">
                                <i class="bi bi-camera text-white"></i>
                            </div>
                        </div>
                        <h6 class="fw-bold text-muted">Ảnh đạo diễn</h6>
                        <p class="text-muted small mb-0">JPG, PNG, GIF tối đa 2MB</p>
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
                            <button type="submit" form="directorCreateForm" 
                                    class="btn btn-success btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <i class="bi bi-check-circle"></i>
                                Thêm đạo diễn
                            </button>
                            
                            <a href="{{ route('admin.directors.index') }}" 
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
                        <div class="avatar-md rounded-circle bg-success-subtle">
                            <span class="avatar-title rounded-circle bg-success text-white">
                                <i class="bi bi-person-video"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark">Thông tin đạo diễn</h4>
                            <p class="text-muted mb-0">Nhập thông tin chi tiết của đạo diễn</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form id="directorCreateForm" action="{{ route('admin.directors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-primary-subtle">
                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                            <i class="bi bi-person-badge"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-primary">Thông tin cơ bản</h5>
                                        <p class="text-muted mb-0 small">Tên và thông tin cá nhân</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" id="name" 
                                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                               value="{{ old('name') }}" 
                                               placeholder="Tên đạo diễn" required maxlength="255">
                                        <label for="name">
                                            <i class="bi bi-person me-2 text-primary"></i>Tên đạo diễn <span class="text-danger">*</span>
                                        </label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="date" name="birth_date" id="birth_date" 
                                               class="form-control form-control-lg @error('birth_date') is-invalid @enderror" 
                                               value="{{ old('birth_date') }}">
                                        <label for="birth_date">
                                            <i class="bi bi-calendar-event me-2 text-success"></i>Ngày sinh
                                        </label>
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="text" name="nationality" id="nationality" 
                                               class="form-control form-control-lg @error('nationality') is-invalid @enderror" 
                                               value="{{ old('nationality') }}" 
                                               placeholder="Ví dụ: Việt Nam, Mỹ, Nhật Bản...">
                                        <label for="nationality">
                                            <i class="bi bi-globe me-2 text-info"></i>Quốc tịch
                                        </label>
                                        @error('nationality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="upload-container">
                                        <label for="image" class="form-label fw-bold mb-3">
                                            <i class="bi bi-cloud-upload me-2 text-success"></i>Ảnh đạo diễn
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Biography Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-info-subtle">
                                        <span class="avatar-title rounded-circle bg-info text-white">
                                            <i class="bi bi-journal-text"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-info">Tiểu sử</h5>
                                        <p class="text-muted mb-0 small">Thông tin về sự nghiệp và cuộc đời</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating">
                                <textarea name="biography" id="biography" 
                                          class="form-control @error('biography') is-invalid @enderror" 
                                          style="height: 150px;" 
                                          placeholder="Nhập tiểu sử của đạo diễn...">{{ old('biography') }}</textarea>
                                <label for="biography">
                                    <i class="bi bi-journal-text me-2 text-info"></i>Tiểu sử đạo diễn
                                </label>
                                @error('biography')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="form-section mb-4">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-warning-subtle">
                                        <span class="avatar-title rounded-circle bg-warning text-white">
                                            <i class="bi bi-toggles2"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-warning">Trạng thái</h5>
                                        <p class="text-muted mb-0 small">Cài đặt trạng thái hoạt động</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card bg-light-subtle border-0 rounded-3 p-4">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_active" id="is_active" 
                                           class="form-check-input" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label for="is_active" class="form-check-label fw-semibold">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Đạo diễn đang hoạt động
                                    </label>
                                    <div class="form-text">Bật tính năng này để đạo diễn có thể được chọn khi tạo phim mới</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <a href="{{ route('admin.directors.index') }}" 
                               class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-check-circle me-2"></i>Thêm đạo diễn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
/* Gradient Background */
.bg-gradient-success {
    background: linear-gradient(135deg, #52c41a 0%, #237804 100%);
}

/* Form Sections */
.form-section {
    position: relative;
    animation: fadeInUp 0.5s ease-out;
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

.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

/* Form Switch Enhancement */
.form-check-input:checked {
    background-color: var(--bs-success);
    border-color: var(--bs-success);
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

.form-section:nth-child(2) { animation-delay: 0.1s; }
.form-section:nth-child(3) { animation-delay: 0.2s; }
.form-section:nth-child(4) { animation-delay: 0.3s; }

/* Responsive */
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
}
</style>

<script>
// Xem trước ảnh
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "{{ asset('assets/images/director-placeholder.png') }}";
        if (file) {
            alert('Vui lòng chọn file ảnh hợp lệ (jpeg, png, jpg, gif).');
        }
    }
});
</script>
@endsection