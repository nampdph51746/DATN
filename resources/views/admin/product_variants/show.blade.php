@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Simple Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="simple-header text-center py-4">
                <h1 class="display-6 fw-bold text-orange mb-0">
                    <i class="fas fa-eye me-3"></i>
                    Chi tiết biến thể
                </h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Product Variant Image & Info -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-cogs me-2"></i>
                        Thông tin biến thể
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <!-- Variant Image -->
                    <div class="variant-image-container mb-4">
                        @if ($productVariant->image_url)
                            <img src="{{ asset('storage/' . $productVariant->image_url) }}" 
                                 alt="{{ $productVariant->sku }}" 
                                 class="variant-image rounded-4 shadow-sm">
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-cogs"></i>
                                <p class="mt-3 text-muted">Không có ảnh biến thể</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Variant Basic Info -->
                    <div class="variant-basic-info">
                        <div class="variant-id-badge mb-3">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold">
                                <i class="fas fa-hashtag me-1"></i>
                                ID: {{ $productVariant->id }}
                            </span>
                        </div>
                        
                        <h4 class="variant-sku mb-3 fw-bold text-dark">{{ $productVariant->sku }}</h4>
                        
                        <div class="variant-meta d-flex justify-content-center gap-3 mb-4">
                            <div class="meta-item">
                                <small class="text-muted d-block">Giá bán</small>
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    {{ number_format($productVariant->price, 0, ',', '.') }}₫
                                </span>
                            </div>
                        </div>

                        <div class="variant-stock mb-4">
                            <small class="text-muted d-block mb-2">Tồn kho</small>
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-4 py-2 fs-6">
                                <i class="fas fa-boxes me-2"></i>
                                {{ $productVariant->stock_quantity }}
                            </span>
                        </div>
                        
                        <div class="variant-status mb-4">
                            @if($productVariant->is_active)
                                <span class="status-badge status-active">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Đang hoạt động
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-pause-circle me-1"></i>
                                    Không hoạt động
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Actions Footer -->
                <div class="card-footer bg-light border-0 py-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.product-variants.edit', $productVariant->id) }}" 
                           class="btn btn-warning btn-lg rounded-pill shadow-sm">
                            <i class="fas fa-edit me-2"></i>
                            Chỉnh sửa biến thể
                        </a>
                        <a href="{{ route('admin.products.show', $productVariant->product->id) }}" 
                           class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>
                            Về sản phẩm gốc
                        </a>
                        <a href="{{ route('admin.product-variants.index') }}" 
                           class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-list me-2"></i>
                            Danh sách biến thể
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-xl-8 col-lg-7">
            <!-- Product Information -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                <div class="card-header bg-gradient-info text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-box me-2"></i>
                        Thông tin sản phẩm gốc
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-primary">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">Tên sản phẩm</h6>
                                    <p class="info-value">{{ $productVariant->product->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-success">
                                    <i class="fas fa-barcode"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">SKU sản phẩm</h6>
                                    <p class="info-value">{{ $productVariant->product->sku ?? 'Chưa có' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">Danh mục</h6>
                                    <p class="info-value">
                                        {{ $productVariant->product->category ? $productVariant->product->category->name : 'Không xác định' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-warning">
                                    <i class="fas fa-cubes"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">Loại sản phẩm</h6>
                                    <p class="info-value">
                                        @switch($productVariant->product->product_type)
                                            @case('food')
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                                    <i class="fas fa-utensils me-1"></i>Thức ăn
                                                </span>
                                            @break
                                            @case('drink')
                                                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                                    <i class="fas fa-glass-water me-1"></i>Đồ uống
                                                </span>
                                            @break
                                            @case('combo')
                                                <span class="badge bg-purple-subtle text-purple px-3 py-2 rounded-pill">
                                                    <i class="fas fa-boxes me-1"></i>Combo
                                                </span>
                                            @break
                                            @default
                                                {{ $productVariant->product->product_type }}
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variant Attributes -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                <div class="card-header bg-gradient-success text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-tags me-2"></i>
                        Thuộc tính biến thể
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($productVariant->productVariantOptions->count() > 0)
                        <div class="row g-3">
                            @foreach($productVariant->productVariantOptions->groupBy('attributeValue.attribute.name') as $attributeName => $options)
                                <div class="col-md-6">
                                    <div class="attribute-group">
                                        <div class="attribute-header mb-3">
                                            <h6 class="attribute-name">
                                                <i class="fas fa-tag me-2 text-primary"></i>
                                                {{ $attributeName }}
                                            </h6>
                                        </div>
                                        <div class="attribute-values">
                                            @foreach($options as $option)
                                                <span class="badge bg-primary-subtle text-primary me-2 mb-2 px-3 py-2 rounded-pill">
                                                    <i class="fas fa-check me-1"></i>
                                                    {{ $option->attributeValue->value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state text-center py-4">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h6 class="text-muted">Chưa có thuộc tính nào</h6>
                            <p class="text-muted mb-0">Biến thể này chưa được thiết lập thuộc tính</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Variant Details -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card mb-4">
                <div class="card-header bg-gradient-secondary text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Chi tiết biến thể
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">Ngày tạo</h6>
                                    <p class="info-value">
                                        {{ $productVariant->created_at ? $productVariant->created_at->format('d/m/Y H:i') : 'Không xác định' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">Cập nhật lần cuối</h6>
                                    <p class="info-value">
                                        {{ $productVariant->updated_at ? $productVariant->updated_at->format('d/m/Y H:i') : 'Không xác định' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Combos -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-purple text-white py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-boxes me-2"></i>
                        Combo liên quan
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $relatedCombos = \App\Models\Combo::where('combo_product_variant_id', $productVariant->id)
                            ->with(['comboPackageItems'])
                            ->get();
                    @endphp
                    
                    @if($relatedCombos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover modern-table mb-0">
                                <thead class="table-header-modern">
                                    <tr>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-hashtag me-1 text-primary"></i>STT
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-box me-1 text-success"></i>Tên combo
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-money-bill me-1 text-warning"></i>Giá
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-cubes me-1 text-info"></i>Tồn kho
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-list me-1 text-secondary"></i>Số items
                                        </th>
                                        <th class="border-0 px-3 py-3 text-center">
                                            <i class="fas fa-tools me-1 text-dark"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($relatedCombos as $idx => $combo)
                                        <tr class="table-row">
                                            <td class="px-3 py-3">
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                    {{ $idx + 1 }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <h6 class="mb-0 fw-bold text-dark">{{ $combo->name }}</h6>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="fw-bold text-success">
                                                    {{ number_format($combo->price, 0, ',', '.') }}₫
                                                </span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                                    {{ $combo->stock_quantity }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                                    {{ $combo->comboPackageItems->count() }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('admin.combos.show', $combo->id) }}" 
                                                       class="btn btn-sm rounded-3 view-btn"
                                                       title="Xem chi tiết" data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.combos.edit', $combo->id) }}" 
                                                       class="btn btn-sm rounded-3 edit-btn"
                                                       title="Chỉnh sửa" data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-4">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <h6 class="text-muted">Chưa có combo nào</h6>
                            <p class="text-muted mb-3">Biến thể này chưa được sử dụng trong combo nào</p>
                            <a href="{{ route('admin.combos.create', ['product_id' => $productVariant->product->id, 'combo_product_variant_id' => $productVariant->id]) }}" 
                               class="btn btn-primary rounded-pill">
                                <i class="fas fa-plus me-1"></i>Tạo combo
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
    --orange-dark: #e55a2b;
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --purple-color: #8b5cf6;
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
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
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

.bg-gradient-secondary {
    background: linear-gradient(135deg, var(--secondary-color), #4b5563);
}

.bg-gradient-purple {
    background: linear-gradient(135deg, var(--purple-color), #7c3aed);
}

/* Product Variant Image */
.variant-image-container {
    position: relative;
}

.variant-image {
    width: 100%;
    max-width: 250px;
    height: 200px;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.no-image-placeholder {
    width: 250px;
    height: 200px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-radius: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 3rem;
    margin: 0 auto;
}

.variant-sku {
    font-size: 1.5rem;
    line-height: 1.3;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 600;
    border: 1px solid;
}

.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: #065f46;
    border-color: rgba(16, 185, 129, 0.3);
}

.status-inactive {
    background: rgba(107, 114, 128, 0.1);
    color: #374151;
    border-color: rgba(107, 114, 128, 0.3);
}

/* Info Items */
.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 1rem;
    border: 1px solid #f1f5f9;
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-color: var(--primary-color);
}

.info-icon {
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

.info-icon.bg-primary {
    background: linear-gradient(135deg, var(--primary-color), #1e40af);
}

.info-icon.bg-success {
    background: linear-gradient(135deg, var(--success-color), #059669);
}

.info-icon.bg-info {
    background: linear-gradient(135deg, var(--info-color), #0891b2);
}

.info-icon.bg-warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
}

.info-label {
    color: #6b7280;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.info-value {
    color: var(--dark-color);
    font-weight: 600;
    font-size: 1rem;
    margin: 0;
}

/* Attribute Groups */
.attribute-group {
    background: #f8fafc;
    border-radius: 1rem;
    padding: 1.5rem;
    border-left: 4px solid var(--primary-color);
    transition: all 0.3s ease;
}

.attribute-group:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.attribute-name {
    color: var(--dark-color);
    font-weight: 700;
    margin-bottom: 0.5rem;
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

.edit-btn {
    background-color: #f59e0b !important;
    border: 1px solid #f59e0b !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 34px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 3px 8px rgba(245, 158, 11, 0.2);
}

.edit-btn:hover {
    background-color: #d97706 !important;
    border-color: #d97706 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
}

/* Badge Colors */
.bg-purple-subtle {
    background-color: rgba(139, 92, 246, 0.1) !important;
}

.text-purple {
    color: #7c3aed !important;
}

.bg-primary-subtle {
    background-color: rgba(59, 130, 246, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(16, 185, 129, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(245, 158, 11, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(6, 182, 212, 0.1) !important;
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

.btn-outline-primary {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
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

    .variant-image {
        max-width: 200px;
        height: 160px;
    }

    .no-image-placeholder {
        width: 200px;
        height: 160px;
        font-size: 2rem;
    }

    .info-item {
        padding: 1rem;
    }

    .info-icon {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1rem;
    }

    .table td, .table th {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }

    .view-btn,
    .edit-btn {
        min-width: 30px;
        height: 26px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Staggered animation for table rows
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
        row.style.animation = 'fadeInUp 0.6s ease-out both';
    });

    // Animation for info items
    const infoItems = document.querySelectorAll('.info-item');
    infoItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.style.animation = 'slideInFromLeft 0.6s ease-out both';
    });

    // Animation for attribute groups
    const attributeGroups = document.querySelectorAll('.attribute-group');
    attributeGroups.forEach((group, index) => {
        group.style.animationDelay = `${index * 0.15}s`;
        group.style.animation = 'slideInFromRight 0.6s ease-out both';
    });
});

// CSS animations
const style = document.createElement('style');
style.textContent = `
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
    
    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInFromRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection