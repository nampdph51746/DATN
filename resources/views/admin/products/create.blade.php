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
                    Thêm sản phẩm
                </h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Product Preview Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card sticky-top" style="top: 2rem;">
                <div class="card-header bg-gradient-success text-white text-center py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-eye me-2"></i>
                        Xem trước sản phẩm
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="product-preview-container mb-4">
                        <img src="{{ asset('assets/images/default.png') }}" 
                             alt="Preview" 
                             class="product-preview-image rounded-4 shadow-sm" 
                             id="previewImage">
                        <div class="upload-overlay" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-camera"></i>
                            <p class="mt-2 mb-0">Nhấn để tải ảnh</p>
                        </div>
                    </div>
                    
                    <div class="preview-info">
                        <h4 class="product-preview-title fw-bold text-dark" id="previewName">
                            Sản phẩm mới
                        </h4>
                        
                        <div class="preview-meta mt-3">
                            <div class="meta-item mb-2">
                                <small class="text-muted">SKU:</small>
                                <span class="badge bg-primary-subtle text-primary ms-2" id="previewSku">
                                    Chưa có
                                </span>
                            </div>
                            <div class="meta-item mb-2">
                                <small class="text-muted">Danh mục:</small>
                                <span class="badge bg-info-subtle text-info ms-2" id="previewCategory">
                                    Chưa chọn
                                </span>
                            </div>
                            <div class="meta-item">
                                <small class="text-muted">Loại:</small>
                                <span class="badge bg-warning-subtle text-warning ms-2" id="previewType">
                                    Chưa chọn
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg rounded-pill shadow-sm" onclick="submitForm()">
                            <i class="fas fa-save me-2"></i>
                            Tạo sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-times me-2"></i>
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Form -->
        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf
                
                <!-- Image Upload Section -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-images me-2"></i>
                            Hình ảnh sản phẩm
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="upload-zone rounded-4" id="uploadZone">
                            <input name="image" type="file" id="imageInput" 
                                   accept="image/*" onchange="previewImage(event)" style="display: none;" />
                            
                            <div class="upload-content text-center">
                                <div class="upload-icon mb-3">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h4 class="upload-title mb-2">Tải ảnh sản phẩm</h4>
                                <p class="upload-subtitle text-muted mb-3">
                                    Kéo thả hoặc nhấn để chọn ảnh
                                </p>
                                <button type="button" class="btn btn-primary rounded-pill px-4" 
                                        onclick="document.getElementById('imageInput').click()">
                                    <i class="fas fa-folder-open me-2"></i>
                                    Chọn ảnh
                                </button>
                                <div class="upload-note mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Định dạng: JPG, PNG, GIF. Kích thước đề xuất: 1600x1200px
                                    </small>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Product Information -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                    <div class="card-header bg-gradient-info text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin sản phẩm
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- SKU & Category -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="product-sku" 
                                           name="sku" 
                                           value="{{ old('sku') }}" 
                                           placeholder="Mã sản phẩm"
                                           onkeyup="updatePreview()">
                                    <label for="product-sku">
                                        <i class="fas fa-barcode me-2"></i>
                                        Mã sản phẩm (SKU)
                                    </label>
                                </div>
                                @error('sku')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" 
                                            id="product-categories" 
                                            name="category_id" 
                                            onchange="updatePreview()">
                                        <option value="">Chọn danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    data-name="{{ $category->name }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="product-categories">
                                        <i class="fas fa-layer-group me-2"></i>
                                        Danh mục sản phẩm
                                    </label>
                                </div>
                                @error('category_id')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Name & Type -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="product-name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Tên sản phẩm"
                                           onkeyup="updatePreview()">
                                    <label for="product-name">
                                        <i class="fas fa-tag me-2"></i>
                                        Tên sản phẩm
                                    </label>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" 
                                            id="product-type" 
                                            name="product_type" 
                                            onchange="updatePreview()">
                                        <option value="">Chọn loại sản phẩm</option>
                                        <option value="food" {{ old('product_type') == 'food' ? 'selected' : '' }}>
                                            🍔 Thức ăn
                                        </option>
                                        <option value="drink" {{ old('product_type') == 'drink' ? 'selected' : '' }}>
                                            🥤 Đồ uống
                                        </option>
                                        <option value="combo" {{ old('product_type') == 'combo' ? 'selected' : '' }}>
                                            📦 Combo
                                        </option>
                                    </select>
                                    <label for="product-type">
                                        <i class="fas fa-cubes me-2"></i>
                                        Loại sản phẩm
                                    </label>
                                </div>
                                @error('product_type')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Status & Description -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" 
                                            id="is-active" 
                                            name="is_active">
                                        <option value="">Chọn trạng thái</option>
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>
                                            ✅ Hoạt động
                                        </option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>
                                            ⏸️ Không hoạt động
                                        </option>
                                    </select>
                                    <label for="is-active">
                                        <i class="fas fa-toggle-on me-2"></i>
                                        Trạng thái
                                    </label>
                                </div>
                                @error('is_active')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <textarea class="form-control" 
                                              id="product-description" 
                                              name="description" 
                                              style="height: 100px" 
                                              placeholder="Mô tả sản phẩm">{{ old('description') }}</textarea>
                                    <label for="product-description">
                                        <i class="fas fa-align-left me-2"></i>
                                        Mô tả sản phẩm
                                    </label>
                                </div>
                                @error('description')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    --light-bg: #f8fafc;
    --border-color: #e5e7eb;
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

.bg-gradient-info {
    background: linear-gradient(135deg, var(--info-color), #0891b2);
}

/* Product Preview */
.product-preview-container {
    position: relative;
    display: inline-block;
    width: 100%;
    max-width: 250px;
}

.product-preview-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border: 3px solid #e5e7eb;
    transition: all 0.3s ease;
}

.upload-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    cursor: pointer;
    border-radius: 1rem;
}

.upload-overlay:hover {
    opacity: 1;
}

.upload-overlay i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.preview-meta .meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Upload Zone */
.upload-zone {
    border: 2px dashed #cbd5e1;
    background: #f8fafc;
    padding: 3rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-zone:hover {
    border-color: var(--primary-color);
    background: rgba(59, 130, 246, 0.05);
}

.upload-zone.dragover {
    border-color: var(--success-color);
    background: rgba(16, 185, 129, 0.1);
    transform: scale(1.02);
}

.upload-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.upload-title {
    color: var(--dark-color);
    font-weight: 600;
}

.upload-subtitle {
    font-size: 1rem;
}

.upload-note {
    padding: 1rem;
    background: rgba(59, 130, 246, 0.05);
    border-radius: 0.5rem;
    border-left: 4px solid var(--primary-color);
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
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select ~ label {
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

.bg-info-subtle {
    background-color: rgba(6, 182, 212, 0.1) !important;
    color: var(--info-color) !important;
}

.bg-warning-subtle {
    background-color: rgba(245, 158, 11, 0.1) !important;
    color: var(--warning-color) !important;
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

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1e40af, var(--primary-color));
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
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

/* Sticky Top */
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

    .upload-zone {
        padding: 2rem 1rem;
    }

    .upload-icon {
        font-size: 2rem;
    }

    .sticky-top {
        position: relative;
        top: auto;
    }

    .product-preview-image {
        height: 150px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Drag and Drop functionality
    const uploadZone = document.getElementById('uploadZone');
    const imageInput = document.getElementById('imageInput');

    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            imageInput.files = files;
            previewImage({ target: { files: files } });
        }
    });

    uploadZone.addEventListener('click', function() {
        imageInput.click();
    });
});

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function updatePreview() {
    // Update name
    const name = document.getElementById('product-name').value || 'Sản phẩm mới';
    document.getElementById('previewName').textContent = name;

    // Update SKU
    const sku = document.getElementById('product-sku').value || 'Chưa có';
    document.getElementById('previewSku').textContent = sku;

    // Update category
    const categorySelect = document.getElementById('product-categories');
    const categoryText = categorySelect.options[categorySelect.selectedIndex]?.text || 'Chưa chọn';
    document.getElementById('previewCategory').textContent = categoryText;

    // Update type
    const typeSelect = document.getElementById('product-type');
    const typeText = typeSelect.options[typeSelect.selectedIndex]?.text || 'Chưa chọn';
    document.getElementById('previewType').textContent = typeText;
}

function submitForm() {
    // Add loading state
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tạo...';
    submitBtn.disabled = true;

    // Submit form
    document.getElementById('productForm').submit();
}

// Real-time preview updates
document.addEventListener('DOMContentLoaded', function() {
    const inputs = ['product-name', 'product-sku', 'product-categories', 'product-type'];
    inputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        }
    });
});
</script>
@endsection