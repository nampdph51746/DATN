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
                    Chỉnh sửa danh mục sản phẩm
                </h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Preview Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card sticky-top" style="top: 2rem;">
                <div class="card-header bg-gradient-warning text-white text-center py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-eye me-2"></i>
                        Xem trước thay đổi
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="category-preview-icon mb-4">
                        <div class="preview-icon-container">
                            <i class="fas fa-layer-group text-white"></i>
                        </div>
                    </div>
                    
                    <div class="preview-info">
                        <h4 class="category-preview-title fw-bold text-dark" id="previewName">
                            {{ $category->name }}
                        </h4>
                        
                        <div class="preview-description mt-3">
                            <small class="text-muted">Mô tả:</small>
                            <p class="preview-desc-text text-secondary" id="previewDescription">
                                {{ $category->description ?: 'Chưa có mô tả' }}
                            </p>
                        </div>

                        <div class="preview-meta mt-4">
                            <div class="row g-2">
                                <div class="col-12">
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill mb-2">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        Đang chỉnh sửa
                                    </span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Tạo lúc:</small>
                                    <div class="fw-semibold text-dark">
                                        {{ $category->created_at ? $category->created_at->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Cập nhật:</small>
                                    <div class="fw-semibold text-dark">
                                        {{ $category->updated_at ? $category->updated_at->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning btn-lg rounded-pill shadow-sm" onclick="submitForm()">
                            <i class="fas fa-save me-2"></i>
                            Cập nhật danh mục
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
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin danh mục
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.product-categories.update', $category->id) }}" method="POST" id="categoryForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Category Name -->
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           id="category-name" 
                                           name="name" 
                                           value="{{ old('name', $category->name) }}" 
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
                                              onkeyup="updatePreview()">{{ old('description', $category->description) }}</textarea>
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
                                        <span id="charCount">{{ strlen(old('description', $category->description ?? '')) }}</span>/1000 ký tự
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Category Stats -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                <div class="card-header bg-gradient-info text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống kê danh mục
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="stat-item">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="stat-label">Tổng sản phẩm</h6>
                                    <p class="stat-value">{{ $category->products->count() ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-item">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="stat-label">Đang hoạt động</h6>
                                    <p class="stat-value">
                                        {{ $category->products ? $category->products->where('is_active', 1)->count() : 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-item">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-pause-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="stat-label">Không hoạt động</h6>
                                    <p class="stat-value">
                                        {{ $category->products ? $category->products->where('is_active', 0)->count() : 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-secondary text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2"></i>
                        Sản phẩm gần đây
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($category->products && $category->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover modern-table mb-0">
                                <thead class="table-header-modern">
                                    <tr>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-image me-1 text-primary"></i>Ảnh
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-tag me-1 text-success"></i>Tên sản phẩm
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-money-bill me-1 text-warning"></i>Giá
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-toggle-on me-1 text-info"></i>Trạng thái
                                        </th>
                                        <th class="border-0 px-3 py-3 text-center">
                                            <i class="fas fa-tools me-1 text-dark"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products->take(5) as $product)
                                        <tr class="table-row">
                                            <td class="px-3 py-3">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="rounded-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-3" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3">
                                                <h6 class="mb-0 fw-bold text-dark">{{ $product->name }}</h6>
                                                <small class="text-muted">{{ $product->sku ?? 'N/A' }}</small>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="fw-bold text-success">
                                                    {{ number_format($product->price ?? 0, 0, ',', '.') }}₫
                                                </span>
                                            </td>
                                            <td class="px-3 py-3">
                                                @if($product->is_active)
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                        <i class="fas fa-check me-1"></i>Hoạt động
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                                        <i class="fas fa-pause me-1"></i>Tạm dừng
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                <a href="{{ route('admin.products.show', $product->id) }}" 
                                                   class="btn btn-sm rounded-3 view-btn"
                                                   title="Xem chi tiết" data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($category->products->count() > 5)
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Và {{ $category->products->count() - 5 }} sản phẩm khác...
                                </small>
                            </div>
                        @endif
                    @else
                        <div class="empty-state text-center py-4">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h6 class="text-muted">Chưa có sản phẩm nào</h6>
                            <p class="text-muted mb-3">Danh mục này chưa có sản phẩm nào</p>
                            <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}" 
                               class="btn btn-primary rounded-pill">
                                <i class="fas fa-plus me-1"></i>Thêm sản phẩm
                            </a>
                        </div>
                    @endif
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
    --secondary-color: #6b7280;
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

.bg-gradient-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
}

.bg-gradient-info {
    background: linear-gradient(135deg, var(--info-color), #0891b2);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, var(--secondary-color), #4b5563);
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
    background: linear-gradient(135deg, var(--warning-color), #d97706);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    transition: all 0.3s ease;
}

.preview-icon-container i {
    font-size: 3rem;
    color: white;
}

.preview-icon-container:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 35px rgba(245, 158, 11, 0.4);
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

/* Stat Items */
.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 1rem;
    border: 1px solid #f1f5f9;
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-color: var(--primary-color);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.stat-icon.bg-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
}

.stat-icon.bg-success {
    background: linear-gradient(135deg, var(--success-color), #059669);
}

.stat-icon.bg-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
}

.stat-label {
    color: #6b7280;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.stat-value {
    color: var(--dark-color);
    font-weight: 700;
    font-size: 1.5rem;
    margin: 0;
}

/* Modern Table */
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
}

.table-header-modern {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.table-header-modern th {
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.8rem;
    color: var(--dark-color);
}

.table-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.table-row:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.03), rgba(59, 130, 246, 0.08));
    transform: translateX(3px);
    box-shadow: 0 2px 10px rgba(59, 130, 246, 0.1);
}

/* Action Buttons */
.view-btn {
    background-color: #06b6d4 !important;
    border: 1px solid #06b6d4 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 34px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 3px 8px rgba(6, 182, 212, 0.2);
}

.view-btn:hover {
    background-color: #0891b2 !important;
    border-color: #0891b2 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
}

/* Badges */
.badge {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 2rem;
}

.bg-warning-subtle {
    background-color: rgba(245, 158, 11, 0.1) !important;
    color: var(--warning-color) !important;
}

.bg-success-subtle {
    background-color: rgba(16, 185, 129, 0.1) !important;
    color: var(--success-color) !important;
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

.btn-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
    border-color: var(--warning-color);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706, var(--warning-color));
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
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

/* Empty State */
.empty-state {
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
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

    .stat-item {
        padding: 1rem;
    }

    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1rem;
    }

    .stat-value {
        font-size: 1.2rem;
    }

    .table td, .table th {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }

    .view-btn {
        min-width: 30px;
        height: 26px;
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

.modern-card:nth-child(3) {
    animation-delay: 0.4s;
}
</style>

<script>
function updatePreview() {
    // Update category name
    const name = document.getElementById('category-name').value || '{{ $category->name }}';
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

function submitForm() {
    const categoryForm = document.getElementById('categoryForm');
    
    if (!categoryForm.checkValidity()) {
        categoryForm.reportValidity();
        return;
    }

    // Add loading state
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...';
    submitBtn.disabled = true;

    categoryForm.submit();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

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

    // Staggered animation for table rows
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
        row.style.animation = 'fadeInUp 0.6s ease-out both';
    });

    // Animation for stat items
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.style.animation = 'fadeInUp 0.6s ease-out both';
    });
});
</script>
@endsection