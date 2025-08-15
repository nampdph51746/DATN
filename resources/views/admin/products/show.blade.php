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
                    Chi tiết
                </h1>
            </div>
        </div>
    </div>

    <!-- Product Content -->
    <div class="row g-4">
        <!-- Product Image & Info Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-image me-2"></i>
                        Hình ảnh sản phẩm
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="product-image-container mb-4">
                        @if ($product->image_url)
                            <img src="{{ asset('storage/' . $product->image_url) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image rounded-4 shadow-sm">
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-image"></i>
                                <p class="mt-3 text-muted">Không có ảnh</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="product-basic-info">
                        <div class="product-id-badge mb-3">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold">
                                <i class="fas fa-hashtag me-1"></i>
                                ID: {{ $product->id }}
                            </span>
                        </div>
                        
                        <h4 class="product-title mb-3 fw-bold text-dark">{{ $product->name }}</h4>
                        
                        <div class="product-meta d-flex justify-content-center gap-3 mb-4">
                            <div class="meta-item">
                                <small class="text-muted d-block">Danh mục</small>
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $product->category ? $product->category->name : 'Không xác định' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="product-status mb-4">
                            @if($product->is_active)
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
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden modern-card">
                <div class="card-header bg-gradient-light border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-list-alt me-2 text-primary"></i>
                        Thông tin chi tiết
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <!-- Product Info Grid -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-primary">
                                    <i class="fas fa-barcode"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">Mã SKU</h6>
                                    <p class="info-value">{{ $product->sku ?? 'Chưa có' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-success">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">Loại sản phẩm</h6>
                                    <p class="info-value">
                                        @switch($product->product_type)
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
                                                {{ $product->product_type }}
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="info-content">
                                    <h6 class="info-label">Ngày tạo</h6>
                                    <p class="info-value">
                                        {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : 'Không xác định' }}
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
                                        {{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : 'Không xác định' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="description-section mb-5">
                        <h6 class="section-title">
                            <i class="fas fa-align-left me-2 text-primary"></i>
                            Mô tả sản phẩm
                        </h6>
                        <div class="description-content">
                            {!! $product->description ?: '<p class="text-muted fst-italic">Chưa có mô tả cho sản phẩm này.</p>' !!}
                        </div>
                    </div>

                    <!-- Product Variants Section -->
                    <div class="variants-section mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="section-title mb-0">
                                <i class="fas fa-cogs me-2 text-primary"></i>
                                Danh sách biến thể ({{ $product->productVariants->count() }})
                            </h6>
                            <a href="{{ route('admin.product-variants.create', ['product_id' => $product->id]) }}" 
                               class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="fas fa-plus me-1"></i>Thêm biến thể
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover modern-table mb-0">
                                <thead class="table-header-modern">
                                    <tr>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-hashtag me-1 text-primary"></i>STT
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-barcode me-1 text-success"></i>SKU
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-calendar me-1 text-info"></i>Ngày tạo
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-clock me-1 text-warning"></i>Cập nhật
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-toggle-on me-1 text-secondary"></i>Trạng thái
                                        </th>
                                        <th class="border-0 px-3 py-3 text-center">
                                            <i class="fas fa-tools me-1 text-dark"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($product->productVariants as $key => $variant)
                                        <tr class="table-row">
                                            <td class="px-3 py-3">
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                    {{ $key + 1 }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="fw-bold text-dark">{{ $variant->sku }}</span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="datetime-info">
                                                    <div class="date-primary">
                                                        {{ $variant->created_at ? $variant->created_at->format('d/m/Y') : 'N/A' }}
                                                    </div>
                                                    <small class="time-secondary text-muted">
                                                        {{ $variant->created_at ? $variant->created_at->format('H:i') : '' }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="datetime-info">
                                                    <div class="date-primary">
                                                        {{ $variant->updated_at ? $variant->updated_at->format('d/m/Y') : 'N/A' }}
                                                    </div>
                                                    <small class="time-secondary text-muted">
                                                        {{ $variant->updated_at ? $variant->updated_at->format('H:i') : '' }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                @if($variant->is_active)
                                                    <span class="status-badge status-active">
                                                        <i class="fas fa-check-circle me-1"></i>Hoạt động
                                                    </span>
                                                @else
                                                    <span class="status-badge status-inactive">
                                                        <i class="fas fa-pause-circle me-1"></i>Không hoạt động
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('admin.product-variants.edit', $variant->id) }}" 
                                                       class="btn btn-sm rounded-3 edit-btn"
                                                       title="Chỉnh sửa" data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.combos.create', ['product_id' => $product->id, 'combo_product_variant_id' => $variant->id]) }}" 
                                                       class="btn btn-sm rounded-3 combo-btn"
                                                       title="Tạo combo" data-bs-toggle="tooltip">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <div class="empty-icon">
                                                        <i class="fas fa-cogs"></i>
                                                    </div>
                                                    <h6 class="mt-3 text-muted">Chưa có biến thể nào</h6>
                                                    <p class="text-muted mb-3">Hãy tạo biến thể đầu tiên cho sản phẩm này</p>
                                                    <a href="{{ route('admin.product-variants.create', ['product_id' => $product->id]) }}" 
                                                       class="btn btn-primary rounded-pill">
                                                        <i class="fas fa-plus me-1"></i>Thêm biến thể
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Combos Section -->
                    <div class="combos-section">
                        <h6 class="section-title mb-4">
                            <i class="fas fa-boxes me-2 text-primary"></i>
                            Danh sách combo liên quan
                        </h6>

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
                                            <i class="fas fa-cog me-1 text-info"></i>Biến thể
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-money-bill me-1 text-warning"></i>Giá
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-cubes me-1 text-secondary"></i>Tồn kho
                                        </th>
                                        <th class="border-0 px-3 py-3">
                                            <i class="fas fa-list me-1 text-purple"></i>Số items
                                        </th>
                                        <th class="border-0 px-3 py-3 text-center">
                                            <i class="fas fa-tools me-1 text-dark"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $combos = \App\Models\Combo::whereIn('combo_product_variant_id', $product->productVariants->pluck('id'))
                                            ->with(['comboProductVariant', 'comboPackageItems'])
                                            ->get();
                                    @endphp
                                    @forelse($combos as $idx => $combo)
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
                                                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                                    {{ $combo->comboProductVariant ? $combo->comboProductVariant->sku : '-' }}
                                                </span>
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
                                                <span class="badge bg-purple-subtle text-purple px-3 py-2 rounded-pill">
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
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <div class="empty-icon">
                                                        <i class="fas fa-boxes"></i>
                                                    </div>
                                                    <h6 class="mt-3 text-muted">Chưa có combo nào</h6>
                                                    <p class="text-muted mb-0">Chưa có combo nào cho các biến thể này</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

/* Product Image */
.product-image-container {
    position: relative;
}

.product-image {
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

.product-title {
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

/* Section Titles */
.section-title {
    color: var(--dark-color);
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f1f5f9;
}

/* Description Content */
.description-content {
    background: #f8fafc;
    border-radius: 1rem;
    padding: 1.5rem;
    border-left: 4px solid var(--primary-color);
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

.datetime-info {
    line-height: 1.3;
}

.date-primary {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.time-secondary {
    font-size: 0.75rem;
}

/* Action Buttons */
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

.combo-btn {
    background-color: #8b5cf6 !important;
    border: 1px solid #8b5cf6 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 34px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 3px 8px rgba(139, 92, 246, 0.2);
}

.combo-btn:hover {
    background-color: #7c3aed !important;
    border-color: #7c3aed !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
}

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

.edit-btn i,
.combo-btn i,
.view-btn i {
    font-size: 12px;
    color: white !important;
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

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(180deg); }
}

@keyframes pulse {
    0%, 100% { 
        transform: scale(1);
        opacity: 0.1;
    }
    50% { 
        transform: scale(1.1);
        opacity: 0.3;
    }
}

@keyframes iconBounce {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-3px); }
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

    .product-image {
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

    .edit-btn,
    .combo-btn,
    .view-btn {
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
});

// CSS animation for fadeInUp
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
`;
document.head.appendChild(style);
</script>
@endsection