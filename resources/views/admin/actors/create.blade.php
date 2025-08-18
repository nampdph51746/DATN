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
                                    <i class="bi bi-person-plus fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Thêm diễn viên mới</h2>
                                <p class="mb-0 opacity-90 fs-5">Tạo thông tin diễn viên mới trong hệ thống</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-primary rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="bi bi-plus-circle me-2"></i>
                                Tạo mới
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-person-plus" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Image Preview Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-image text-primary"></i>
                        Xem trước ảnh
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img id="imagePreview" 
                             src="{{ asset('assets/images/actor-placeholder.png') }}" 
                             alt="Actor Preview" 
                             class="img-fluid rounded-4 shadow-lg" 
                             style="width: 100%; max-width: 280px; height: 320px; object-fit: cover; border: 4px solid #fff;">
                        
                        <!-- Upload overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 rounded-4 d-flex align-items-center justify-content-center opacity-0 transition-opacity" 
                             id="uploadOverlay" 
                             style="transition: opacity 0.3s ease;">
                            <div class="text-white text-center">
                                <i class="bi bi-cloud-upload fs-1 mb-2"></i>
                                <p class="mb-0 fw-semibold">Thêm ảnh</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="upload-area border-2 border-dashed border-primary rounded-4 p-4 bg-primary-subtle cursor-pointer" 
                         onclick="document.getElementById('image').click()">
                        <div class="upload-content text-center">
                            <i class="bi bi-cloud-upload text-primary fs-1 mb-3"></i>
                            <h6 class="fw-bold text-primary mb-2">Chọn ảnh diễn viên</h6>
                            <p class="text-muted small mb-0">
                                Kéo thả hoặc click để chọn ảnh<br>
                                <em>JPG, PNG, GIF tối đa 2MB</em>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="submit" form="actorCreateForm" class="btn btn-primary btn-lg rounded-pill">
                            <i class="bi bi-save me-2"></i>Lưu diễn viên
                        </button>
                        <a href="{{ route('admin.actors.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="bi bi-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="col-xl-8 col-lg-7">
            <form id="actorCreateForm" action="{{ route('admin.actors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Thông tin cơ bản -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-primary-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-person text-primary"></i>
                                Thông tin cơ bản
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" 
                                           placeholder="Tên diễn viên"
                                           required>
                                    <label for="name">
                                        <i class="bi bi-person me-2"></i>Tên diễn viên <span class="text-danger">*</span>
                                    </label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" 
                                           name="birth_date" 
                                           id="birth_date" 
                                           class="form-control @error('birth_date') is-invalid @enderror" 
                                           value="{{ old('birth_date') }}" 
                                           placeholder="Ngày sinh">
                                    <label for="birth_date">
                                        <i class="bi bi-calendar-event me-2"></i>Ngày sinh
                                    </label>
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           name="nationality" 
                                           id="nationality" 
                                           class="form-control @error('nationality') is-invalid @enderror" 
                                           value="{{ old('nationality') }}" 
                                           placeholder="Quốc tịch">
                                    <label for="nationality">
                                        <i class="bi bi-globe me-2"></i>Quốc tịch
                                    </label>
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       class="form-control @error('image') is-invalid @enderror d-none" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-floating">
                                    <div class="form-control d-flex align-items-center" style="height: calc(3.5rem + 2px);">
                                        <span class="text-muted" id="imageFileName">Chưa chọn file ảnh</span>
                                    </div>
                                    <label>
                                        <i class="bi bi-image me-2"></i>Ảnh diễn viên
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiểu sử -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-info-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-journal-text text-info"></i>
                                Tiểu sử
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-floating">
                            <textarea name="biography" 
                                      id="biography" 
                                      class="form-control @error('biography') is-invalid @enderror" 
                                      placeholder="Nhập tiểu sử của diễn viên..."
                                      style="height: 150px;">{{ old('biography') }}</textarea>
                            <label for="biography">
                                <i class="bi bi-journal-text me-2"></i>Tiểu sử diễn viên
                            </label>
                            @error('biography')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cài đặt -->
                <div class="card border-0 shadow-lg rounded-4 form-section">
                    <div class="card-header bg-warning-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-gear text-warning"></i>
                                Cài đặt
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   class="form-check-input" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label fw-semibold">
                                <i class="bi bi-toggle-on me-2"></i>Kích hoạt diễn viên
                            </label>
                            <div class="form-text">Diễn viên sẽ hiển thị trong hệ thống khi được kích hoạt</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
/* Gradient Background */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.15) !important;
    border-color: var(--bs-primary) !important;
    transform: translateY(-2px);
}

.upload-content {
    pointer-events: none;
}

/* Avatar Sizes */
.avatar-xl {
    width: 5rem;
    height: 5rem;
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
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
}

/* Card Enhancements */
.card {
    transition: all 0.3s ease;
    border: none;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Sticky Position */
.sticky-top {
    top: 1rem;
}

/* Color Subtle Backgrounds */
.bg-primary-subtle {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
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
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imageFileName = document.getElementById('imageFileName');
    const uploadOverlay = document.getElementById('uploadOverlay');

    // Show overlay on hover
    imagePreview.addEventListener('mouseenter', function() {
        uploadOverlay.style.opacity = '1';
    });

    imagePreview.addEventListener('mouseleave', function() {
        uploadOverlay.style.opacity = '0';
    });

    // Click on image to trigger file input
    imagePreview.addEventListener('click', function() {
        imageInput.click();
    });

    // Handle file selection
    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imageFileName.textContent = file.name;
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = "{{ asset('assets/images/actor-placeholder.png') }}";
            imageFileName.textContent = 'Chưa chọn file ảnh';
            if (file) {
                alert('Vui lòng chọn file ảnh hợp lệ (jpeg, png, jpg, gif).');
                imageInput.value = '';
            }
        }
    });

    // Drag and drop functionality
    const uploadArea = document.querySelector('.upload-area');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    uploadArea.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            imageInput.dispatchEvent(new Event('change'));
        }
    });
});
</script>
@endsection