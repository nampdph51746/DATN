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
                    Tạo combo
                </h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Combo Preview -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card sticky-top" style="top: 2rem;">
                <div class="card-header bg-gradient-success text-white text-center py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-eye me-2"></i>
                        Xem trước combo
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="combo-preview-container mb-4">
                        <img src="{{ asset('assets/images/default.png') }}" 
                             alt="Combo Preview" 
                             class="combo-preview-image rounded-4 shadow-sm" 
                             id="previewImage">
                    </div>
                    
                    <div class="preview-info">
                        <h4 class="combo-preview-title fw-bold text-dark" id="previewSku">
                            Combo mới
                        </h4>
                        
                        <div class="preview-meta mt-3">
                            <div class="meta-item mb-2">
                                <small class="text-muted">Sản phẩm gốc:</small>
                                <span class="badge bg-primary-subtle text-primary ms-2" id="previewProduct">
                                    @if($selectedProduct)
                                        {{ $selectedProduct->name }}
                                    @else
                                        Chưa chọn
                                    @endif
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
                            Tạo combo
                        </button>
                        <a href="{{ isset($productId) && $productId ? route('admin.products.show', $productId) : route('admin.combos.index') }}" 
                           class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-times me-2"></i>
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Combo Form -->
        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('admin.combos.store') }}" method="POST" id="comboForm">
                @csrf
                
                <!-- Basic Information -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin cơ bản
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
                                           value="{{ old('name') }}" 
                                           placeholder="Tên combo"
                                           maxlength="255" 
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
                                    @if($selectedProduct)
                                        <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">
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
                                                onchange="loadVariants(this)"
                                                required>
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
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

                            <!-- Variant Selection -->
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    @if($selectedVariant)
                                        <input type="hidden" name="combo_product_variant_id" value="{{ $selectedVariant->id }}">
                                        <input type="text" 
                                               class="form-control form-control-lg" 
                                               value="{{ $selectedVariant->sku }}" 
                                               readonly
                                               style="background-color: #f8f9fa;">
                                        <label>
                                            <i class="fas fa-cogs me-2"></i>
                                            Biến thể đại diện (đã chọn)
                                        </label>
                                    @else
                                        <select class="form-select form-select-lg" 
                                                id="combo-product-variant-id" 
                                                name="combo_product_variant_id" 
                                                onchange="updatePreview()"
                                                required>
                                            <option value="">Chọn biến thể</option>
                                        </select>
                                        <label for="combo-product-variant-id">
                                            <i class="fas fa-cogs me-2"></i>
                                            Biến thể đại diện
                                        </label>
                                    @endif
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
                                           value="{{ old('price') }}" 
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
                                           value="{{ old('stock_quantity', 1) }}" 
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
                        </div>
                    </div>
                </div>

                <!-- Combo Items -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                    <div class="card-header bg-gradient-warning text-white py-4">
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
                                    <tr class="item-row">
                                        <td>
                                            <select class="form-select product-select" 
                                                    name="items[0][product_id]" 
                                                    onchange="loadItemVariants(this)" 
                                                    required>
                                                <option value="">Chọn sản phẩm</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            {{ ($selectedProduct && $selectedProduct->id == $product->id) ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select variant-select" 
                                                    name="items[0][item_product_variant_id]" 
                                                    required>
                                                <option value="">Chọn biến thể</option>
                                                @if($selectedVariant)
                                                    <option value="{{ $selectedVariant->id }}" selected>
                                                        {{ $selectedVariant->sku }}
                                                    </option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="items[0][quantity]" 
                                                   value="1" 
                                                   min="1" 
                                                   required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm btn-remove-item" 
                                                    style="display:none;" 
                                                    onclick="removeItemRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
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

.bg-gradient-success {
    background: linear-gradient(135deg, var(--success-color), #059669);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
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

.preview-meta .meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
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
// Biến toàn cục
const selectedVariantId = @if($selectedVariant) "{{ $selectedVariant->id }}" @else null @endif;
const selectedProductId = @if($selectedProduct) "{{ $selectedProduct->id }}" @else null @endif;

function submitForm() {
    const comboForm = document.getElementById('comboForm');
    
    // Validate form trước khi gửi
    if (!comboForm.checkValidity()) {
        comboForm.reportValidity();
        return;
    }

    // Kiểm tra duplicate combo
    const payload = {
        product_variant_id: document.querySelector('input[name="combo_product_variant_id"]')?.value || 
                           document.getElementById('combo-product-variant-id')?.value,
        items: getComboItems()
    };

    fetch('/admin/combos/check-duplicate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.duplicate) {
            alert('Combo này đã tồn tại. Vui lòng chọn sản phẩm hoặc biến thể khác.');
        } else {
            // Add loading state
            const submitBtn = event.target;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tạo...';
            submitBtn.disabled = true;
            
            comboForm.submit();
        }
    })
    .catch(error => {
        console.error('Error checking duplicate:', error);
        // Nếu lỗi API thì vẫn cho submit
        comboForm.submit();
    });
}

function getComboItems() {
    const rows = document.querySelectorAll('.item-row');
    const items = [];
    
    rows.forEach(row => {
        const productId = row.querySelector('.product-select').value;
        const variantId = row.querySelector('.variant-select').value;
        const quantity = row.querySelector('input[type="number"]').value;
        
        if (productId && variantId && quantity) {
            items.push({
                productId: productId,
                variantId: variantId,
                quantity: parseInt(quantity)
            });
        }
    });
    
    return items;
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

    // Show remove button
    newRow.querySelector('.btn-remove-item').style.display = 'inline-block';

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

function updatePreview() {
    // Update name
    const name = document.getElementById('combo-name')?.value || 'Combo mới';
    document.getElementById('previewSku').textContent = name;

    // Update price
    const price = document.getElementById('combo-price')?.value || '0';
    document.getElementById('previewPrice').textContent = new Intl.NumberFormat('vi-VN').format(price) + '₫';

    // Update stock
    const stock = document.getElementById('combo-stock')?.value || '0';
    document.getElementById('previewStock').textContent = stock;

    // Update product name
    const productSelect = document.getElementById('product-id');
    if (productSelect) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productName = selectedOption ? selectedOption.text : (selectedProductId ? document.getElementById('previewProduct').textContent : 'Chưa chọn');
        document.getElementById('previewProduct').textContent = productName;
    }

    // Update preview image if variant is selected
    const variantSelect = document.getElementById('combo-product-variant-id');
    if (variantSelect && variantSelect.value) {
        // Có thể fetch ảnh của variant tại đây nếu cần
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateRemoveButtons();
    
    // Load variants for selected product
    const productSelect = document.getElementById('product-id');
    if (productSelect && productSelect.value) {
        loadVariants(productSelect);
    }

    // Auto-set first item if there's a selected variant
    if (selectedVariantId && selectedProductId) {
        const container = document.getElementById('items-container');
        const firstRow = container.querySelector('.item-row');
        
        // Set product
        firstRow.querySelector('.product-select').value = selectedProductId;
        
        // Load and set variant
        loadItemVariants(firstRow.querySelector('.product-select')).then(() => {
            firstRow.querySelector('.variant-select').value = selectedVariantId;
        });
    }

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