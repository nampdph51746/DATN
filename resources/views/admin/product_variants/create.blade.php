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
                    Thêm biến thể
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
                        Xem trước biến thể
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
                        <h4 class="product-preview-title fw-bold text-dark" id="previewSku">
                            Biến thể mới
                        </h4>
                        
                        <div class="preview-meta mt-3">
                            <div class="meta-item mb-2">
                                <small class="text-muted">Sản phẩm:</small>
                                <span class="badge bg-primary-subtle text-primary ms-2" id="previewProduct">
                                    {{ $selectedProduct->name ?? 'Chưa chọn' }}
                                </span>
                            </div>
                            <div class="meta-item mb-2">
                                <small class="text-muted">Giá:</small>
                                <span class="badge bg-success-subtle text-success ms-2" id="previewPrice">
                                    0₫
                                </span>
                            </div>
                            <div class="meta-item">
                                <small class="text-muted">Tồn kho:</small>
                                <span class="badge bg-warning-subtle text-warning ms-2" id="previewStock">
                                    0
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg rounded-pill shadow-sm" onclick="submitForm()">
                            <i class="fas fa-save me-2"></i>
                            Tạo biến thể
                        </button>
                        <a href="{{ $selectedProduct ? route('admin.products.show', $selectedProduct->id) : route('admin.products.index') }}" 
                           class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-times me-2"></i>
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Form -->
        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('admin.product-variants.store') }}" method="POST" enctype="multipart/form-data" id="variantForm">
                @csrf
                
                <!-- Image Upload Section -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-images me-2"></i>
                            Hình ảnh biến thể
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="upload-zone rounded-4" id="uploadZone">
                            <input name="image_url" type="file" id="imageInput" 
                                   accept="image/*" onchange="previewImage(event)" style="display: none;" />
                            
                            <div class="upload-content text-center">
                                <div class="upload-icon mb-3">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h4 class="upload-title mb-2">Tải ảnh biến thể</h4>
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
                                        Định dạng: JPG, PNG, GIF. Kích thước đề xuất: 800x800px
                                    </small>
                                </div>
                            </div>
                        </div>
                        @error('image_url')
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Product Information -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                    <div class="card-header bg-gradient-info text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin cơ bản
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Product Selection -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    @if(isset($selectedProductId) && $selectedProductId && $selectedProduct)
                                        <input type="hidden" name="product_id" value="{{ $selectedProductId }}">
                                        <input type="text" 
                                               class="form-control form-control-lg" 
                                               value="{{ $selectedProduct->name }}" 
                                               readonly
                                               style="background-color: #f8f9fa;">
                                        <label>
                                            <i class="fas fa-box me-2"></i>
                                            Sản phẩm (đã chọn)
                                        </label>
                                    @else
                                        <select class="form-select form-select-lg" 
                                                id="product-id" 
                                                name="product_id" 
                                                onchange="updatePreview()">
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-name="{{ $product->name }}"
                                                        data-sku="{{ $product->sku }}"
                                                        {{ old('product_id', $selectedProductId) == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="product-id">
                                            <i class="fas fa-box me-2"></i>
                                            Sản phẩm
                                        </label>
                                    @endif
                                </div>
                                @error('product_id')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" 
                                            id="is-active" 
                                            name="is_active">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>
                                            ✅ Hoạt động
                                        </option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
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

                            <!-- Default Price & Stock -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="number" 
                                           class="form-control form-control-lg" 
                                           id="default-price" 
                                           name="default_price" 
                                           value="{{ old('default_price') }}" 
                                           placeholder="Giá mặc định"
                                           step="1000"
                                           min="0"
                                           onkeyup="updatePreview()">
                                    <label for="default-price">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        Giá mặc định (VNĐ)
                                    </label>
                                </div>
                                @error('default_price')
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
                                           id="default-stock" 
                                           name="default_stock_quantity" 
                                           value="{{ old('default_stock_quantity') }}" 
                                           placeholder="Tồn kho mặc định"
                                           min="0"
                                           onkeyup="updatePreview()">
                                    <label for="default-stock">
                                        <i class="fas fa-boxes me-2"></i>
                                        Tồn kho mặc định
                                    </label>
                                </div>
                                @error('default_stock_quantity')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attributes Section -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                    <div class="card-header bg-gradient-warning text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-tags me-2"></i>
                            Thuộc tính & Giá trị
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->has('attribute_values'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $errors->first('attribute_values') }}
                            </div>
                        @endif
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-3" id="attributes-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">
                                            <i class="fas fa-tag me-2"></i>Thuộc tính
                                        </th>
                                        <th style="width: 50%">
                                            <i class="fas fa-list me-2"></i>Giá trị thuộc tính
                                        </th>
                                        <th style="width: 10%" class="text-center">
                                            <i class="fas fa-cogs me-2"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="attributes-container">
                                    <tr class="attribute-row">
                                        <td>
                                            <select class="form-select attribute-select" name="attributes[]" onchange="loadAttributeValues(this)">
                                                <option value="">Chọn thuộc tính</option>
                                                @foreach ($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}" 
                                                            data-values='@json($attribute->attributeValues->map(fn($value) => ["id" => $value->id, "value" => $value->value]))'>
                                                        {{ $attribute->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select value-select" multiple name="attribute_values[0][]" required>
                                                <option value="">Chọn giá trị thuộc tính</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove-attribute" 
                                                    style="display:none;" onclick="removeAttributeRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <button type="button" class="btn btn-success rounded-pill px-4" onclick="addAttributeRow()">
                            <i class="fas fa-plus me-2"></i>Thêm thuộc tính
                        </button>
                    </div>
                </div>

                <!-- Variants Preview -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                    <div class="card-header bg-gradient-secondary text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-list-alt me-2"></i>
                            Danh sách biến thể sẽ tạo
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" id="variants-preview">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-barcode me-2"></i>SKU</th>
                                        <th><i class="fas fa-tags me-2"></i>Thuộc tính</th>
                                        <th><i class="fas fa-money-bill me-2"></i>Giá (VNĐ)</th>
                                        <th><i class="fas fa-boxes me-2"></i>Tồn kho</th>
                                    </tr>
                                </thead>
                                <tbody id="variants-preview-body">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Chọn thuộc tính để xem danh sách biến thể
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --secondary-color: #6b7280;
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

/* Gradient Headers */
.bg-gradient-success {
    background: linear-gradient(135deg, var(--success-color), #059669);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
}

.bg-gradient-info {
    background: linear-gradient(135deg, var(--info-color), #0891b2);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, var(--secondary-color), #4b5563);
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

/* Preview Components */
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

/* Form Controls */
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

/* Responsive */
@media (max-width: 768px) {
    .simple-header {
        padding: 2rem !important;
    }
    
    .simple-header h1 {
        font-size: 2rem;
    }
    
    .upload-zone {
        padding: 2rem 1rem;
    }
    
    .sticky-top {
        position: relative;
        top: auto;
    }
}
</style>

<script>
// Get the correct SKU for preview
const productSku = @if($selectedProduct) "{{ $selectedProduct->sku }}" @else "PRODUCT" @endif;

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
    // Update product name
    const productSelect = document.getElementById('product-id');
    if (productSelect) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productName = selectedOption ? selectedOption.text : @if($selectedProduct) "{{ $selectedProduct->name }}" @else "Chưa chọn" @endif;
        document.getElementById('previewProduct').textContent = productName;
    }

    // Update price
    const price = document.getElementById('default-price').value || '0';
    document.getElementById('previewPrice').textContent = new Intl.NumberFormat('vi-VN').format(price) + '₫';

    // Update stock
    const stock = document.getElementById('default-stock').value || '0';
    document.getElementById('previewStock').textContent = stock;

    // Update variants preview
    updateVariantsPreview();
}

function loadAttributeValues(select) {
    const valueSelect = select.closest('.attribute-row').querySelector('.value-select');
    valueSelect.innerHTML = '<option value="">Chọn giá trị thuộc tính</option>';
    
    const selectedOption = select.options[select.selectedIndex];
    if (selectedOption && selectedOption.value) {
        try {
            const values = JSON.parse(selectedOption.getAttribute('data-values') || '[]');
            values.forEach(value => {
                const option = document.createElement('option');
                option.value = value.id;
                option.text = value.value;
                valueSelect.appendChild(option);
            });
        } catch (e) {
            console.error('Error parsing attribute values:', e);
        }
    }
    updateVariantsPreview();
}

function addAttributeRow() {
    const container = document.getElementById('attributes-container');
    const firstRows = container.querySelectorAll('.attribute-row');
    const newRow = firstRows[0].cloneNode(true);

    // Update name for select
    const index = firstRows.length;
    newRow.querySelector('.value-select').name = `attribute_values[${index}][]`;

    // Reset selects
    newRow.querySelector('.attribute-select').selectedIndex = 0;
    const valueSelect = newRow.querySelector('.value-select');
    valueSelect.innerHTML = '<option value="">Chọn giá trị thuộc tính</option>';

    // Show remove button
    newRow.querySelector('.btn-remove-attribute').style.display = 'inline-block';

    container.appendChild(newRow);
    updateRemoveButtons();
    updateVariantsPreview();
}

function removeAttributeRow(btn) {
    const row = btn.closest('.attribute-row');
    row.remove();
    updateRemoveButtons();
    updateVariantsPreview();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('#attributes-container .attribute-row');
    rows.forEach((row, idx) => {
        const btn = row.querySelector('.btn-remove-attribute');
        btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        row.querySelector('.value-select').name = `attribute_values[${idx}][]`;
    });
}

function updateVariantsPreview() {
    const variantsBody = document.getElementById('variants-preview-body');
    variantsBody.innerHTML = '';

    // Get all selected attribute values
    const attributeRows = document.querySelectorAll('.attribute-row');
    const selectedValues = [];

    attributeRows.forEach(row => {
        const valueSelect = row.querySelector('.value-select');
        const attributeSelect = row.querySelector('.attribute-select');
        const attributeName = attributeSelect.selectedOptions[0]?.text || 'Unknown';
        const selectedOptions = Array.from(valueSelect.selectedOptions)
            .filter(opt => opt.value)
            .map(opt => ({
                id: opt.value,
                value: opt.text,
                attribute: attributeName
            }));
        if (selectedOptions.length > 0) {
            selectedValues.push(selectedOptions);
        }
    });

    // Generate combinations
    let combinations = [];
    if (selectedValues.length > 0) {
        const generateCombinations = (values, index = 0, current = []) => {
            if (index === values.length) {
                combinations.push(current);
                return;
            }
            values[index].forEach(val => {
                generateCombinations(values, index + 1, [...current, val]);
            });
        };
        generateCombinations(selectedValues);
    }

    // Get default values
    const defaultPrice = document.getElementById('default-price').value || 0;
    const defaultStock = document.getElementById('default-stock').value || 0;

    if (combinations.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td colspan="4" class="text-center text-muted py-4">
                <i class="fas fa-info-circle me-2"></i>
                Chọn thuộc tính để xem danh sách biến thể
            </td>
        `;
        variantsBody.appendChild(row);
        return;
    }

    // Display combinations
    combinations.forEach((combination, index) => {
        if (combination.length !== selectedValues.length) return;

        const skuParts = combination.map(val => val.value.replace(/\s+/g, '-').toLowerCase());
        const sku = productSku + '-' + skuParts.join('-');
        const attributesText = combination.map(val => `${val.attribute}: ${val.value}`).join(', ');
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="fw-bold">${sku.toUpperCase()}</td>
            <td>${attributesText}</td>
            <td>
                <input type="number" name="variant_prices[${index}]" value="${defaultPrice}" 
                       class="form-control" step="1000" placeholder="Giá (VNĐ)">
            </td>
            <td>
                <input type="number" name="variant_stocks[${index}]" value="${defaultStock}" 
                       class="form-control" placeholder="Tồn kho">
            </td>
        `;
        variantsBody.appendChild(row);

        // Add hidden inputs for attribute values
        combination.forEach((val, attrIndex) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `attribute_values[${attrIndex}][${index}]`;
            input.value = val.id;
            variantsBody.appendChild(input);
        });
    });
}

function submitForm() {
    const variantForm = document.getElementById('variantForm');
    if (variantForm.checkValidity()) {
        // Add loading state
        const submitBtn = event.target;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tạo...';
        submitBtn.disabled = true;
        
        variantForm.submit();
    } else {
        variantForm.reportValidity();
    }
}

// Initialize events
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners
    document.querySelectorAll('.value-select').forEach(select => {
        select.addEventListener('change', updateVariantsPreview);
    });

    document.getElementById('default-price').addEventListener('input', updatePreview);
    document.getElementById('default-stock').addEventListener('input', updatePreview);

    const productSelect = document.getElementById('product-id');
    if (productSelect) {
        productSelect.addEventListener('change', updatePreview);
    }

    // Initial preview update
    updatePreview();

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
});
</script>
@endsection