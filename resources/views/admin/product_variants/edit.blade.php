@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-warning rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chỉnh sửa biến thể sản phẩm</h2>
                                <p class="mb-0 opacity-90 fs-5">Cập nhật thông tin biến thể: {{ $productVariant->sku }}</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-warning rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="fas fa-edit me-2"></i>
                                Chỉnh sửa
                            </span>
                        </div>
                    </div>
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
                        <i class="fas fa-image text-warning"></i>
                        Xem trước ảnh
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img id="imagePreview"
                             src="{{ $productVariant->image_url ? asset('storage/' . $productVariant->image_url) : asset('assets/images/default.png') }}"
                             alt="Variant Preview"
                             class="img-fluid rounded-4 shadow-lg"
                             style="width: 100%; max-width: 280px; height: 320px; object-fit: cover; border: 4px solid #fff;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 rounded-4 d-flex align-items-center justify-content-center opacity-0 transition-opacity"
                             id="uploadOverlay"
                             style="transition: opacity 0.3s ease;">
                            <div class="text-white text-center">
                                <i class="fas fa-cloud-upload-alt fs-1 mb-2"></i>
                                <p class="mb-0 fw-semibold">Thay đổi ảnh</p>
                            </div>
                        </div>
                    </div>
                    <div class="upload-area border-2 border-dashed border-warning rounded-4 p-4 bg-warning-subtle cursor-pointer"
                         onclick="document.getElementById('imageInput').click()">
                        <div class="upload-content text-center">
                            <i class="fas fa-cloud-upload-alt text-warning fs-1 mb-3"></i>
                            <h6 class="fw-bold text-warning mb-2">Thay đổi ảnh biến thể</h6>
                            <p class="text-muted small mb-0">
                                Kéo thả hoặc click để chọn ảnh mới<br>
                                <em>JPG, PNG, GIF tối đa 2MB</em>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="submit" form="variantForm" class="btn btn-warning btn-lg rounded-pill">
                            <i class="fas fa-save me-2"></i>Cập nhật biến thể
                        </button>
                        <a href="{{ isset($selectedProductId) ? route('admin.products.show', $selectedProductId) : route('admin.products.index') }}" class="btn btn-outline-info btn-lg rounded-pill">
                            <i class="fas fa-eye me-2"></i>Xem sản phẩm
                        </a>
                        <a href="{{ route('admin.product-variants.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="col-xl-8 col-lg-7">
            <form action="{{ route('admin.product-variants.update', $productVariant->id) }}" method="POST" enctype="multipart/form-data" id="variantForm">
                @csrf
                @method('PUT')
                <!-- Thông tin cơ bản -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-primary-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-box-open text-primary"></i>
                                Thông tin biến thể
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text"
                                           name="sku"
                                           id="sku"
                                           class="form-control @error('sku') is-invalid @enderror"
                                           value="{{ old('sku', $productVariant->sku) }}"
                                           placeholder="SKU biến thể"
                                           required>
                                    <label for="sku">
                                        <i class="fas fa-barcode me-2"></i>SKU <span class="text-danger">*</span>
                                    </label>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number"
                                           name="price"
                                           id="price"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', $productVariant->price) }}"
                                           placeholder="Giá biến thể"
                                           step="0.01"
                                           required>
                                    <label for="price">
                                        <i class="fas fa-money-bill-wave me-2"></i>Giá (VNĐ) <span class="text-danger">*</span>
                                    </label>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number"
                                           name="stock_quantity"
                                           id="stock_quantity"
                                           class="form-control @error('stock_quantity') is-invalid @enderror"
                                           value="{{ old('stock_quantity', $productVariant->stock_quantity) }}"
                                           placeholder="Tồn kho"
                                           required>
                                    <label for="stock_quantity">
                                        <i class="fas fa-cart-plus me-2"></i>Tồn kho <span class="text-danger">*</span>
                                    </label>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select id="is_active" name="is_active" class="form-select">
                                        <option value="1" {{ old('is_active', $productVariant->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active', $productVariant->is_active) == 0 ? 'selected' : '' }}>Ngừng</option>
                                    </select>
                                    <label for="is_active">
                                        <i class="fas fa-toggle-on me-2"></i>Trạng thái
                                    </label>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="file"
                                       name="image"
                                       id="imageInput"
                                       class="form-control d-none"
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-floating">
                                    <div class="form-control d-flex align-items-center" style="height: calc(3.5rem + 2px);">
                                        <span class="text-muted" id="imageFileName">
                                            {{ $productVariant->image_url ? 'Ảnh hiện tại: ' . basename($productVariant->image_url) : 'Chưa chọn file ảnh' }}
                                        </span>
                                    </div>
                                    <label>
                                        <i class="fas fa-image me-2"></i>Ảnh biến thể
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thuộc tính & Giá trị -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-info-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-list-alt text-info"></i>
                                Thuộc tính & Giá trị
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label mb-2">Thuộc tính & Giá trị thuộc tính</label>
                        @if ($errors->has('attribute_values'))
                            <div class="alert alert-danger py-2 mb-2">
                                {{ $errors->first('attribute_values') }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" id="attributes-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Thuộc tính</th>
                                        <th style="width: 50%">Giá trị thuộc tính</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="attributes-container">
                                    @foreach ($productVariant->productVariantOptions as $index => $option)
                                        <tr class="attribute-row">
                                            <td>
                                                <select class="form-control attribute-select" name="attributes[]" onchange="loadAttributeValues(this)">
                                                    <option value="">Chọn thuộc tính</option>
                                                    @foreach ($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}" data-values='@json($attribute->attributeValues)' {{ $attribute->id == $option->attributeValue->attribute_id ? 'selected' : '' }}>
                                                            {{ $attribute->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control value-select" name="attribute_values[]" required>
                                                    <option value="">Chọn giá trị thuộc tính</option>
                                                    @foreach ($attributes->firstWhere('id', $option->attributeValue->attribute_id)->attributeValues as $value)
                                                        <option value="{{ $value->id }}" {{ $value->id == $option->attribute_value_id ? 'selected' : '' }}>
                                                            {{ $value->value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-attribute" style="{{ $index > 0 ? 'display:inline-block' : 'display:none' }}" onclick="removeAttributeRow(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($productVariant->productVariantOptions->isEmpty())
                                        <tr class="attribute-row">
                                            <td>
                                                <select class="form-control attribute-select" name="attributes[]" onchange="loadAttributeValues(this)">
                                                    <option value="">Chọn thuộc tính</option>
                                                    @foreach ($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}" data-values='@json($attribute->attributeValues)'>
                                                            {{ $attribute->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control value-select" name="attribute_values[]" required>
                                                    <option value="">Chọn giá trị thuộc tính</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-attribute" style="display:none;" onclick="removeAttributeRow(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-success mt-2" onclick="addAttributeRow()">
                            <i class="fas fa-plus"></i> Thêm thuộc tính
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
}
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
.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}
.upload-area:hover {
    background-color: rgba(var(--bs-warning-rgb), 0.15) !important;
    border-color: var(--bs-warning) !important;
    transform: translateY(-2px);
}
.upload-content {
    pointer-events: none;
}
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
.card {
    transition: all 0.3s ease;
    border: none;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.sticky-top {
    top: 1rem;
}
.bg-primary-subtle {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}
.bg-warning-subtle {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}
.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}
.bg-success-subtle {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}
.form-check-input:checked {
    background-color: var(--bs-success);
    border-color: var(--bs-success);
}
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
    const imageInput = document.getElementById('imageInput');
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
    imagePreview.addEventListener('click', function() {
        imageInput.click();
    });

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
            imagePreview.src = "{{ $productVariant->image_url ? asset('storage/' . $productVariant->image_url) : asset('assets/images/default.png') }}";
            imageFileName.textContent = "{{ $productVariant->image_url ? 'Ảnh hiện tại: ' . basename($productVariant->image_url) : 'Chưa chọn file ảnh' }}";
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

    // Attribute table logic
    window.loadAttributeValues = function(select) {
        const valueSelect = select.closest('.attribute-row').querySelector('.value-select');
        valueSelect.innerHTML = '<option value="">Chọn giá trị thuộc tính</option>';
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const values = JSON.parse(selectedOption.getAttribute('data-values') || '[]');
            values.forEach(value => {
                const option = document.createElement('option');
                option.value = value.id;
                option.text = value.value;
                valueSelect.appendChild(option);
            });
        }
    };

    window.addAttributeRow = function() {
        const container = document.getElementById('attributes-container');
        const firstRow = container.querySelector('.attribute-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('.attribute-select').selectedIndex = 0;
        const valueSelect = newRow.querySelector('.value-select');
        valueSelect.innerHTML = '<option value="">Chọn giá trị thuộc tính</option>';
        newRow.querySelector('.btn-remove-attribute').style.display = 'inline-block';

        container.appendChild(newRow);
        updateRemoveButtons();
    };

    window.removeAttributeRow = function(btn) {
        const row = btn.closest('.attribute-row');
        row.remove();
        updateRemoveButtons();
    };

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#attributes-container .attribute-row');
        rows.forEach((row, idx) => {
            const btn = row.querySelector('.btn-remove-attribute');
            btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }
});
</script>

@endsection