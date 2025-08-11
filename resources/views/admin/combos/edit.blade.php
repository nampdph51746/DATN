@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Simple Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="simple-header text-center py-4">
                <h1 class="display-6 fw-bold text-orange mb-0">
                    <i class="fas fa-edit me-3"></i>
                    Chỉnh sửa combo
                </h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Combo Preview Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card sticky-top" style="top: 2rem;">
                <div class="card-header bg-gradient-warning text-white text-center py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-eye me-2"></i>
                        Xem trước combo
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="combo-preview-container mb-4">
                        <img src="{{ $combo->comboProductVariant && $combo->comboProductVariant->image_url ? asset('storage/' . $combo->comboProductVariant->image_url) : asset('assets/images/default.png') }}" 
                             alt="Combo Preview" 
                             class="combo-preview-image rounded-4 shadow-sm" 
                             id="previewImage">
                        <div class="upload-overlay" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-camera"></i>
                            <p class="mt-2 mb-0">Nhấn để tải ảnh</p>
                        </div>
                    </div>
                    
                    <div class="preview-info">
                        <h4 class="combo-preview-title fw-bold text-dark" id="previewName">
                            {{ $combo->name ?? 'Combo' }}
                        </h4>
                        
                        <div class="preview-meta mt-3">
                            <div class="meta-item mb-2">
                                <small class="text-muted">ID:</small>
                                <span class="badge bg-primary-subtle text-primary ms-2">
                                    #{{ $combo->id }}
                                </span>
                            </div>
                            <div class="meta-item mb-2">
                                <small class="text-muted">Giá:</small>
                                <span class="badge bg-success-subtle text-success ms-2" id="previewPrice">
                                    {{ number_format($combo->price, 0, ',', '.') }}₫
                                </span>
                            </div>
                            <div class="meta-item">
                                <small class="text-muted">Tồn kho:</small>
                                <span class="badge bg-warning-subtle text-warning ms-2" id="previewStock">
                                    {{ $combo->stock_quantity }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning btn-lg rounded-pill shadow-sm" onclick="submitForm()">
                            <i class="fas fa-save me-2"></i>
                            Cập nhật combo
                        </button>
                        <a href="{{ isset($combo->comboProductVariant->product_id) ? route('admin.products.show', $combo->comboProductVariant->product_id) : route('admin.combos.index') }}" 
                           class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('admin.combos.update', $combo->id) }}" method="POST" enctype="multipart/form-data" id="comboForm">
                @csrf
                @method('PUT')
                
                <!-- Image Upload Section -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-images me-2"></i>
                            Cập nhật ảnh combo
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
                                <h4 class="upload-title mb-2">Cập nhật ảnh combo</h4>
                                <p class="upload-subtitle text-muted mb-3">
                                    Kéo thả hoặc nhấn để chọn ảnh mới
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

                <!-- Basic Information -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                    <div class="card-header bg-gradient-info text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin combo
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Combo Name -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="combo-name" 
                                           name="name" 
                                           value="{{ old('name', $combo->name) }}" 
                                           placeholder="Tên combo"
                                           required
                                           onkeyup="updatePreview()">
                                    <label for="combo-name">
                                        <i class="fas fa-tag me-2"></i>
                                        Tên combo
                                    </label>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Product Selection -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" 
                                            id="product-id" 
                                            name="product_id" 
                                            onchange="loadVariants(this)"
                                            required>
                                        <option value="">Chọn sản phẩm</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    {{ old('product_id', $combo->comboProductVariant->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="product-id">
                                        <i class="fas fa-box me-2"></i>
                                        Sản phẩm
                                    </label>
                                </div>
                                @error('product_id')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Variant Selection -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" 
                                            id="combo-product-variant-id" 
                                            name="combo_product_variant_id" 
                                            onchange="updatePreview()"
                                            required>
                                        <option value="">Chọn biến thể</option>
                                        @if($combo->comboProductVariant && $combo->comboProductVariant->product)
                                            @foreach ($combo->comboProductVariant->product->productVariants as $variant)
                                                <option value="{{ $variant->id }}" 
                                                        {{ old('combo_product_variant_id', $combo->combo_product_variant_id) == $variant->id ? 'selected' : '' }}>
                                                    {{ $variant->sku }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="combo-product-variant-id">
                                        <i class="fas fa-cogs me-2"></i>
                                        Biến thể đại diện
                                    </label>
                                </div>
                                @error('combo_product_variant_id')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Price & Stock -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="number" 
                                           class="form-control form-control-lg" 
                                           id="combo-price" 
                                           name="price" 
                                           value="{{ old('price', $combo->price) }}" 
                                           placeholder="Giá combo"
                                           min="0" 
                                           step="1000"
                                           required
                                           onkeyup="updatePreview()">
                                    <label for="combo-price">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        Giá combo (VNĐ)
                                    </label>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="number" 
                                           class="form-control form-control-lg" 
                                           id="combo-stock" 
                                           name="stock_quantity" 
                                           value="{{ old('stock_quantity', $combo->stock_quantity) }}" 
                                           placeholder="Tồn kho"
                                           min="0" 
                                           required
                                           onkeyup="updatePreview()">
                                    <label for="combo-stock">
                                        <i class="fas fa-boxes me-2"></i>
                                        Tồn kho
                                    </label>
                                </div>
                                @error('stock_quantity')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" 
                                            id="is-active" 
                                            name="is_active">
                                        <option value="1" {{ old('is_active', $combo->is_active) == 1 ? 'selected' : '' }}>
                                            ✅ Hoạt động
                                        </option>
                                        <option value="0" {{ old('is_active', $combo->is_active) == 0 ? 'selected' : '' }}>
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
                        </div>
                    </div>
                </div>

                <!-- Combo Items -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                    <div class="card-header bg-gradient-success text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-list me-2"></i>
                            Các mục trong combo
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @error('items')
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-3" id="items-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 35%">
                                            <i class="fas fa-box me-2"></i>Sản phẩm
                                        </th>
                                        <th style="width: 35%">
                                            <i class="fas fa-cogs me-2"></i>Biến thể
                                        </th>
                                        <th style="width: 20%">
                                            <i class="fas fa-sort-numeric-up me-2"></i>Số lượng
                                        </th>
                                        <th style="width: 10%" class="text-center">
                                            <i class="fas fa-tools me-2"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    @foreach ($combo->comboPackageItems as $index => $item)
                                        <tr class="item-row">
                                            <td>
                                                <select class="form-select product-select" 
                                                        name="items[{{ $index }}][product_id]" 
                                                        onchange="loadItemVariants(this)" 
                                                        required>
                                                    <option value="">Chọn sản phẩm</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" 
                                                                {{ old("items.$index.product_id", $item->itemProductVariant->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("items.$index.product_id")
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select class="form-select variant-select" 
                                                        name="items[{{ $index }}][item_product_variant_id]" 
                                                        required>
                                                    <option value="">Chọn biến thể</option>
                                                    @if($item->itemProductVariant && $item->itemProductVariant->product)
                                                        @foreach ($item->itemProductVariant->product->productVariants as $variant)
                                                            <option value="{{ $variant->id }}" 
                                                                    {{ old("items.$index.item_product_variant_id", $item->item_product_variant_id) == $variant->id ? 'selected' : '' }}>
                                                                {{ $variant->sku }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error("items.$index.item_product_variant_id")
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control" 
                                                       name="items[{{ $index }}][quantity]" 
                                                       value="{{ old("items.$index.quantity", $item->quantity) }}" 
                                                       min="1" 
                                                       required>
                                                @error("items.$index.quantity")
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm btn-remove-item" 
                                                        onclick="removeItemRow(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <button type="button" class="btn btn-success rounded-pill px-4" onclick="addItemRow()">
                            <i class="fas fa-plus me-2"></i>Thêm mục combo
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
:root {
    --orange-primary: #ff6b35;
    --orange-secondary: #ff8c42;
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
}

/* Simple Header */
.simple-header {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
    border-radius: 1rem;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
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

/* Modern Cards */
.modern-card {
    border: none !important;
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
}

.bg-gradient-info {
    background: linear-gradient(135deg, var(--info-color), #0891b2);
}

.bg-gradient-success {
    background: linear-gradient(135deg, var(--success-color), #059669);
}

/* Preview */
.combo-preview-container {
    position: relative;
    display: inline-block;
    width: 100%;
    max-width: 250px;
}

.combo-preview-image {
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

.upload-icon {
    font-size: 3rem;
    color: var(--primary-color);
}

/* Forms */
.form-control,
.form-select {
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    transform: translateY(-2px);
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

.bg-success-subtle {
    background-color: rgba(16, 185, 129, 0.1) !important;
    color: var(--success-color) !important;
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
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
    border-color: var(--warning-color);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706, var(--warning-color));
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
}

/* Invalid feedback */
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

/* Responsive */
@media (max-width: 768px) {
    .simple-header {
        padding: 2rem !important;
    }
    
    .simple-header h1 {
        font-size: 2rem;
    }
    
    .sticky-top {
        position: relative;
        top: auto;
    }
}
</style>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

function updatePreview() {
    // Update name
    const name = document.getElementById('combo-name')?.value || 'Combo';
    document.getElementById('previewName').textContent = name;

    // Update price
    const price = document.getElementById('combo-price')?.value || '0';
    document.getElementById('previewPrice').textContent = new Intl.NumberFormat('vi-VN').format(price) + '₫';

    // Update stock
    const stock = document.getElementById('combo-stock')?.value || '0';
    document.getElementById('previewStock').textContent = stock;
}

function submitForm() {
    const comboForm = document.getElementById('comboForm');
    
    if (comboForm.checkValidity()) {
        // Add loading state
        const submitBtn = event.target;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...';
        submitBtn.disabled = true;
        
        comboForm.submit();
    } else {
        comboForm.reportValidity();
    }
}

function loadVariants(select) {
    const variantSelect = document.getElementById('combo-product-variant-id');
    if (!variantSelect) return;
    
    variantSelect.innerHTML = '<option value="">Chọn biến thể</option>';
    const productId = select.value;
    
    if (productId) {
        fetch(`/admin/products/${productId}/variants`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                data.forEach(variant => {
                    const option = document.createElement('option');
                    option.value = variant.id;
                    option.text = variant.sku;
                    variantSelect.appendChild(option);
                });
            }
            updatePreview();
        })
        .catch(error => console.error('Error loading variants:', error));
    }
}

function loadItemVariants(select) {
    const row = select.closest('.item-row');
    const variantSelect = row.querySelector('.variant-select');
    variantSelect.innerHTML = '<option value="">Chọn biến thể</option>';
    
    const productId = select.value;
    if (productId) {
        fetch(`/admin/products/${productId}/variants`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                data.forEach(variant => {
                    const option = document.createElement('option');
                    option.value = variant.id;
                    option.text = variant.sku;
                    variantSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error loading item variants:', error));
    }
}

function addItemRow() {
    const container = document.getElementById('items-container');
    const rows = container.querySelectorAll('.item-row');
    const newRow = rows[0].cloneNode(true);
    const index = rows.length;

    // Update names
    newRow.querySelector('.product-select').name = `items[${index}][product_id]`;
    newRow.querySelector('.variant-select').name = `items[${index}][item_product_variant_id]`;
    newRow.querySelector('input[type="number"]').name = `items[${index}][quantity]`;

    // Reset values
    newRow.querySelector('.product-select').selectedIndex = 0;
    newRow.querySelector('.variant-select').innerHTML = '<option value="">Chọn biến thể</option>';
    newRow.querySelector('input[type="number"]').value = 1;

    // Remove error messages
    newRow.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

    container.appendChild(newRow);
    updateRemoveButtons();
}

function removeItemRow(btn) {
    const row = btn.closest('.item-row');
    row.remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row, idx) => {
        const btn = row.querySelector('.btn-remove-item');
        btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        
        // Update names
        row.querySelector('.product-select').name = `items[${idx}][product_id]`;
        row.querySelector('.variant-select').name = `items[${idx}][item_product_variant_id]`;
        row.querySelector('input[type="number"]').name = `items[${idx}][quantity]`;
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateRemoveButtons();
    
    // Drag and drop for upload zone
    const uploadZone = document.getElementById('uploadZone');
    const imageInput = document.getElementById('imageInput');

    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.style.borderColor = '#3b82f6';
        uploadZone.style.background = 'rgba(59, 130, 246, 0.05)';
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.style.borderColor = '#cbd5e1';
        uploadZone.style.background = '#f8fafc';
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.style.borderColor = '#cbd5e1';
        uploadZone.style.background = '#f8fafc';
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            imageInput.files = files;
            previewImage({ target: { files: files } });
        }
    });

    uploadZone.addEventListener('click', function() {
        imageInput.click();
    });

    // Add event listeners for real-time preview updates
    ['combo-name', 'combo-price', 'combo-stock'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
        }
    });

    // Initial preview update
    updatePreview();
});
</script>
@endsection