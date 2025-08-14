@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Rest of content remains the same -->
    <div class="row g-4">
        <!-- Product Preview Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card sticky-top" style="top: 2rem;">
                <div class="card-header bg-gradient-warning text-white text-center py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-eye me-2"></i>
                        Xem trước cập nhật
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="product-preview-container mb-4">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/default.png') }}" 
                             alt="Preview" 
                             class="product-preview-image rounded-4 shadow-sm" 
                             id="previewImage">
                        <div class="upload-overlay" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-camera"></i>
                            <p class="mt-2 mb-0">Nhấn để đổi ảnh</p>
                        </div>
                    </div>
                    
                    <div class="preview-info">
                        <h4 class="product-preview-title fw-bold text-dark" id="previewName">
                            {{ $product->name }}
                        </h4>
                        
                        <div class="preview-meta mt-3">
                            <div class="meta-item mb-2">
                                <small class="text-muted">SKU:</small>
                                <span class="badge bg-primary-subtle text-primary ms-2" id="previewSku">
                                    {{ $product->sku }}
                                </span>
                            </div>
                            <div class="meta-item mb-2">
                                <small class="text-muted">Danh mục:</small>
                                <span class="badge bg-info-subtle text-info ms-2" id="previewCategory">
                                    {{ $product->category->name ?? 'Chưa có' }}
                                </span>
                            </div>
                            <div class="meta-item mb-2">
                                <small class="text-muted">Loại:</small>
                                <span class="badge bg-warning-subtle text-warning ms-2" id="previewType">
                                    @switch($product->product_type)
                                        @case('food') 🍔 Thức ăn @break
                                        @case('drink') 🥤 Đồ uống @break
                                        @case('combo') 📦 Combo @break
                                        @default Chưa có
                                    @endswitch
                                </span>
                            </div>
                            <div class="meta-item">
                                <small class="text-muted">Trạng thái:</small>
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}-subtle text-{{ $product->is_active ? 'success' : 'secondary' }} ms-2" id="previewStatus">
                                    {{ $product->is_active ? '✅ Hoạt động' : '⏸️ Không hoạt động' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning btn-lg rounded-pill shadow-sm" onclick="submitForm()">
                            <i class="fas fa-save me-2"></i>
                            Cập nhật sản phẩm
                        </button>
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-times me-2"></i>
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Form -->
        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf
                @method('PUT')
                
                <!-- Image Upload Section -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-images me-2"></i>
                            Cập nhật hình ảnh
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="current-image mb-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-image me-2 text-primary"></i>
                                Ảnh hiện tại:
                            </h6>
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/default.png') }}" 
                                 alt="Current Image" 
                                 class="img-thumbnail rounded-4 shadow-sm" 
                                 style="max-width: 200px; height: 150px; object-fit: cover;">
                        </div>

                        <div class="upload-zone rounded-4" id="uploadZone">
                            <input name="image" type="file" id="imageInput" 
                                   accept="image/*" onchange="previewImage(event)" style="display: none;" />
                            
                            <div class="upload-content text-center">
                                <div class="upload-icon mb-3">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h4 class="upload-title mb-2">Tải ảnh mới</h4>
                                <p class="upload-subtitle text-muted mb-3">
                                    Kéo thả hoặc nhấn để chọn ảnh thay thế
                                </p>
                                <button type="button" class="btn btn-primary rounded-pill px-4" 
                                        onclick="document.getElementById('imageInput').click()">
                                    <i class="fas fa-folder-open me-2"></i>
                                    Chọn ảnh mới
                                </button>
                                <div class="upload-note mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Để trống nếu không muốn thay đổi ảnh
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
                            Cập nhật thông tin
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
                                           value="{{ old('sku', $product->sku) }}" 
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
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                           value="{{ old('name', $product->name) }}" 
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
                                        <option value="food" {{ old('product_type', $product->product_type) == 'food' ? 'selected' : '' }}>
                                            🍔 Thức ăn
                                        </option>
                                        <option value="drink" {{ old('product_type', $product->product_type) == 'drink' ? 'selected' : '' }}>
                                            🥤 Đồ uống
                                        </option>
                                        <option value="combo" {{ old('product_type', $product->product_type) == 'combo' ? 'selected' : '' }}>
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
                                            name="is_active"
                                            onchange="updatePreview()">
                                        <option value="">Chọn trạng thái</option>
                                        <option value="1" {{ old('is_active', $product->is_active) == '1' ? 'selected' : '' }}>
                                            ✅ Hoạt động
                                        </option>
                                        <option value="0" {{ old('is_active', $product->is_active) == '0' ? 'selected' : '' }}>
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
                                              placeholder="Mô tả sản phẩm">{{ old('description', $product->description) }}</textarea>
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
/* Same CSS as create.blade.php with these modifications */
.hero-section {
    background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
}

.hero-icon-wrapper {
    color: var(--warning-color);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
}

.current-image {
    padding: 1rem;
    background: rgba(245, 158, 11, 0.05);
    border-radius: 1rem;
    border-left: 4px solid var(--warning-color);
}

/* Use same styles from create.blade.php */
</style>

<script>
// Same JavaScript functions as create.blade.php with these additions:

function updatePreview() {
    // Update name
    const name = document.getElementById('product-name').value || 'Sản phẩm';
    document.getElementById('previewName').textContent = name;

    // Update SKU
    const sku = document.getElementById('product-sku').value || 'Chưa có';
    document.getElementById('previewSku').textContent = sku;

    // Update category
    const categorySelect = document.getElementById('product-categories');
    const categoryText = categorySelect.options[categorySelect.selectedIndex]?.text || 'Chưa có';
    document.getElementById('previewCategory').textContent = categoryText;

    // Update type
    const typeSelect = document.getElementById('product-type');
    const typeText = typeSelect.options[typeSelect.selectedIndex]?.text || 'Chưa có';
    document.getElementById('previewType').textContent = typeText;

    // Update status
    const statusSelect = document.getElementById('is-active');
    const statusText = statusSelect.options[statusSelect.selectedIndex]?.text || 'Chưa có';
    const statusValue = statusSelect.value;
    const statusBadge = document.getElementById('previewStatus');
    statusBadge.textContent = statusText;
    statusBadge.className = `badge ms-2 bg-${statusValue == '1' ? 'success' : 'secondary'}-subtle text-${statusValue == '1' ? 'success' : 'secondary'}`;
}

function submitForm() {
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...';
    submitBtn.disabled = true;

    document.getElementById('productForm').submit();
}

// Rest of JavaScript same as create.blade.php
</script>
@endsection
