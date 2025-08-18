@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Simple Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="simple-header text-center py-4">
                <h1 class="display-6 fw-bold text-orange mb-0">
                    <i class="fas fa-plus-circle me-3"></i>
                    Tạo danh mục sản phẩm
                </h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Preview Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card sticky-top" style="top: 2rem;">
                <div class="card-header bg-gradient-success text-white text-center py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-eye me-2"></i>
                        Xem trước danh mục
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="category-preview-icon mb-4">
                        <div class="preview-icon-container">
                            <i class="fas fa-layer-group text-primary"></i>
                        </div>
                    </div>
                    
                    <div class="preview-info">
                        <h4 class="category-preview-title fw-bold text-dark" id="previewName">
                            Danh mục mới
                        </h4>
                        
                        <div class="preview-description mt-3">
                            <small class="text-muted">Mô tả:</small>
                            <p class="preview-desc-text text-secondary" id="previewDescription">
                                Chưa có mô tả
                            </p>
                        </div>

                        <div class="preview-meta mt-4">
                            <div class="meta-badge">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Danh mục mới
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg rounded-pill shadow-sm" onclick="submitForm()">
                            <i class="fas fa-save me-2"></i>
                            Tạo danh mục
                        </button>
                        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-times me-2"></i>
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin danh mục
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.product-categories.store') }}" method="POST" id="categoryForm">
                        @csrf
                        <div class="row g-4">
                            <!-- Category Name -->
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           id="category-name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Tên danh mục"
                                           maxlength="255" 
                                           required
                                           onkeyup="updatePreview()">
                                    <label for="category-name">
                                        <i class="fas fa-tag me-2"></i>
                                        Tên danh mục
                                    </label>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Category Description -->
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="category-description" 
                                              name="description" 
                                              style="height: 120px" 
                                              placeholder="Mô tả danh mục"
                                              maxlength="1000"
                                              onkeyup="updatePreview()">{{ old('description') }}</textarea>
                                    <label for="category-description">
                                        <i class="fas fa-align-left me-2"></i>
                                        Mô tả danh mục
                                    </label>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    @error('description')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="text-muted ms-auto">
                                        <span id="charCount">0</span>/1000 ký tự
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Category Guidelines -->
                        <div class="category-guidelines mt-4">
                            <div class="alert alert-info border-0 rounded-3" style="background: rgba(6, 182, 212, 0.1);">
                                <h6 class="alert-heading mb-3">
                                    <i class="fas fa-lightbulb me-2 text-info"></i>
                                    Hướng dẫn tạo danh mục
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="guideline-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Tên danh mục nên ngắn gọn và dễ hiểu</span>
                                        </div>
                                        <div class="guideline-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Sử dụng từ khóa phù hợp với sản phẩm</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="guideline-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Mô tả chi tiết giúp khách hàng hiểu rõ hơn</span>
                                        </div>
                                        <div class="guideline-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Tránh trùng lặp với danh mục đã có</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sample Categories -->
                        <div class="sample-categories mt-4">
                            <h6 class="mb-3">
                                <i class="fas fa-star me-2 text-warning"></i>
                                Gợi ý danh mục phổ biến:
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" 
                                        onclick="fillSample('Đồ ăn nhanh', 'Các món ăn nhanh như burger, pizza, sandwich')">
                                    Đồ ăn nhanh
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm rounded-pill" 
                                        onclick="fillSample('Đồ uống', 'Nước ngọt, trà, cà phê và các loại đồ uống khác')">
                                    Đồ uống
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm rounded-pill" 
                                        onclick="fillSample('Combo tiết kiệm', 'Các combo ưu đãi cho gia đình và nhóm bạn')">
                                    Combo tiết kiệm
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm rounded-pill" 
                                        onclick="fillSample('Món chính', 'Các món ăn chính trong bữa ăn')">
                                    Món chính
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" 
                                        onclick="fillSample('Tráng miệng', 'Bánh kẹo, kem và các món tráng miệng')">
                                    Tráng miệng
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --orange-primary: #ff6b35;
    --orange-secondary: #ff8c42;
    --orange-light: #ffa726;
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --dark-color: #1f2937;
}

/* Simple Header với màu cam */
.simple-header {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
    border-radius: 1rem;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    margin-bottom: 2rem;
}

.text-orange {
    color: white !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.simple-header i {
    background: rgba(255,255,255,0.2);
    padding: 0.5rem;
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    vertical-align: middle;
}

.simple-header:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
    transition: all 0.3s ease;
}

/* Modern Cards */
.modern-card {
    border: none !important;
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, var(--success-color), #059669);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
}

/* Category Preview */
.category-preview-icon {
    display: flex;
    justify-content: center;
    align-items: center;
}

.preview-icon-container {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    transition: all 0.3s ease;
}

.preview-icon-container i {
    font-size: 3rem;
    color: white;
}

.preview-icon-container:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
}

.category-preview-title {
    font-size: 1.5rem;
    line-height: 1.3;
    margin-bottom: 1rem;
}

.preview-desc-text {
    font-size: 0.95rem;
    line-height: 1.5;
    max-height: 80px;
    overflow-y: auto;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border-left: 4px solid var(--info-color);
}

/* Form Controls */
.form-control,
.form-select {
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    transform: translateY(-2px);
}

.form-floating > label {
    font-weight: 500;
    color: #6b7280;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    color: var(--primary-color);
}

/* Invalid Feedback */
.invalid-feedback {
    display: block;
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: rgba(239, 68, 68, 0.1);
    border-radius: 0.5rem;
    border-left: 4px solid var(--danger-color);
}

/* Guidelines */
.category-guidelines .alert {
    border-left: 4px solid var(--info-color);
}

.guideline-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

/* Sample Categories */
.sample-categories .btn {
    transition: all 0.3s ease;
}

.sample-categories .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

/* Badges */
.badge {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 2rem;
}

.bg-primary-subtle {
    background-color: rgba(59, 130, 246, 0.1) !important;
    color: var(--primary-color) !important;
}

/* Buttons */
.btn {
    border-radius: 2rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color), #059669);
    border-color: var(--success-color);
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, var(--success-color));
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

.btn-outline-secondary {
    border-color: #6b7280;
    color: #6b7280;
}

.btn-outline-secondary:hover {
    background: #6b7280;
    border-color: #6b7280;
    color: white;
}

/* Sticky positioning */
.sticky-top {
    position: sticky;
    top: 2rem;
    z-index: 1020;
}

/* Responsive Design */
@media (max-width: 768px) {
    .simple-header {
        padding: 2rem !important;
    }
    
    .simple-header h1 {
        font-size: 2rem;
    }
    
    .simple-header i {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1.2rem;
    }

    .preview-icon-container {
        width: 100px;
        height: 100px;
    }

    .preview-icon-container i {
        font-size: 2.5rem;
    }

    .sticky-top {
        position: relative;
        top: auto;
    }

    .sample-categories .btn {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
    }
}

/* Animation effects */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: fadeInUp 0.6s ease-out;
}

.modern-card:nth-child(2) {
    animation-delay: 0.2s;
}
</style>

<script>
function updatePreview() {
    // Update category name
    const name = document.getElementById('category-name').value || 'Danh mục mới';
    document.getElementById('previewName').textContent = name;

    // Update description
    const description = document.getElementById('category-description').value || 'Chưa có mô tả';
    document.getElementById('previewDescription').textContent = description;

    // Update character count
    const charCount = document.getElementById('category-description').value.length;
    document.getElementById('charCount').textContent = charCount;

    // Change color based on character count
    const charCountElement = document.getElementById('charCount');
    if (charCount > 800) {
        charCountElement.style.color = '#ef4444';
    } else if (charCount > 600) {
        charCountElement.style.color = '#f59e0b';
    } else {
        charCountElement.style.color = '#6b7280';
    }
}

function fillSample(name, description) {
    document.getElementById('category-name').value = name;
    document.getElementById('category-description').value = description;
    updatePreview();

    // Add visual feedback
    const form = document.getElementById('categoryForm');
    form.style.transform = 'scale(1.01)';
    setTimeout(() => {
        form.style.transform = 'scale(1)';
    }, 200);
}

function submitForm() {
    const categoryForm = document.getElementById('categoryForm');
    
    if (!categoryForm.checkValidity()) {
        categoryForm.reportValidity();
        return;
    }

    // Add loading state
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tạo...';
    submitBtn.disabled = true;

    categoryForm.submit();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for real-time preview updates
    document.getElementById('category-name').addEventListener('input', updatePreview);
    document.getElementById('category-description').addEventListener('input', updatePreview);

    // Initial preview update
    updatePreview();

    // Add smooth transitions for form elements
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        control.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Add hover effects for sample buttons
    const sampleButtons = document.querySelectorAll('.sample-categories .btn');
    sampleButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.05)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
@endsection