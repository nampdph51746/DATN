@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-warning rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="bi bi-shield-plus fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Thêm giới hạn độ tuổi mới</h2>
                                <p class="mb-0 opacity-90 fs-5">Tạo giới hạn độ tuổi mới cho hệ thống phim</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-warning rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="bi bi-plus-circle me-2"></i>
                                Tạo mới
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-shield-plus" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form action="{{ route('admin.age_limits.store') }}" method="POST">
                @csrf
                
                <!-- Thông tin cơ bản -->
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-warning-subtle border-0 py-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-warning"></i>
                            Thông tin giới hạn độ tuổi
                        </h5>
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
                                           placeholder="Tên giới hạn"
                                           required>
                                    <label for="name">
                                        <i class="bi bi-shield-check me-2"></i>Tên giới hạn <span class="text-danger">*</span>
                                    </label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" 
                                           name="min_age" 
                                           id="min_age" 
                                           class="form-control @error('min_age') is-invalid @enderror" 
                                           value="{{ old('min_age') }}" 
                                           placeholder="Độ tuổi tối thiểu"
                                           min="0">
                                    <label for="min_age">
                                        <i class="bi bi-calendar-event me-2"></i>Độ tuổi tối thiểu
                                    </label>
                                    @error('min_age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="description" 
                                              id="description" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Nhập mô tả..."
                                              style="height: 120px;">{{ old('description') }}</textarea>
                                    <label for="description">
                                        <i class="bi bi-file-text me-2"></i>Mô tả (tuỳ chọn)
                                    </label>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.age_limits.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg rounded-pill">
                                <i class="bi bi-save me-2"></i>Lưu giới hạn
                            </button>
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
.bg-gradient-warning {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
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
    border-color: var(--bs-warning);
    box-shadow: 0 0 0 3px rgba(var(--bs-warning-rgb), 0.1);
    transform: translateY(-1px);
}

.form-floating > label {
    font-weight: 600;
    color: #6c757d;
    padding: 1rem 1rem;
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

/* Color Subtle Backgrounds */
.bg-warning-subtle {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}

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
@endsection
