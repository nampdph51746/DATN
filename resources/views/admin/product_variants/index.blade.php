@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <style>
        :root {
            --primary-orange: #FF6F00;
            --primary-teal: #00ACC1;
            --accent-yellow: #FFCA28;
            --neutral-bg: #F8FAFC;
            --card-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
            --transition: all 0.3s ease;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--neutral-bg);
        }
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
        }
        .avatar-lg {
            width: 4rem;
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            border-radius: 50%;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .table > :not(caption) > * > * {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        .table tbody tr {
            transition: var(--transition);
        }
        .table tbody tr:hover {
            background: rgba(0, 172, 193, 0.05);
            transform: translateX(2px);
        }
        .btn-primary {
            background: var(--primary-orange);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background: var(--primary-teal);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }
        .btn-success {
            background: var(--primary-teal);
            border: none;
            border-radius: 50px;
            transition: var(--transition);
        }
        .btn-success:hover {
            background: var(--accent-yellow);
            color: #1a202c;
            transform: translateY(-2px);
        }
        .btn-info {
            background: var(--accent-yellow);
            border: none;
            border-radius: 50px;
            color: #1a202c;
            transition: var(--transition);
        }
        .btn-info:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }
        .view-detail-btn, .edit-btn {
            border-radius: 8px;
            min-width: 36px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .view-detail-btn {
            background: var(--primary-teal);
            border: 1px solid var(--primary-teal);
            color: white;
        }
        .view-detail-btn:hover {
            background: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: #1a202c;
            transform: scale(1.1);
        }
        .edit-btn {
            background: #212529;
            border: 1px solid #212529;
            color: white;
        }
        .edit-btn:hover {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
            transform: scale(1.1);
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }
        .form-control:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.2);
        }
        .input-group-text {
            background: var(--neutral-bg);
            border-color: #e0e0e0;
            border-radius: 8px;
        }
        .pagination {
            --bs-pagination-border-radius: 8px;
        }
        .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 3px;
            transition: var(--transition);
            color: var(--primary-teal);
        }
        .page-link:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }
        .page-item.active .page-link {
            background: var(--primary-teal);
            border: none;
            color: white;
        }
        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card, .alert, .table tbody tr {
            animation: fadeInUp 0.6s ease-out;
        }
        @media (max-width: 768px) {
            .table-responsive { font-size: 0.9rem; }
            .avatar-lg { width: 3rem; height: 3rem; font-size: 1.4rem; }
            .btn-lg { padding: 0.5rem 1rem; font-size: 0.9rem; }
            .view-detail-btn, .edit-btn { min-width: 32px; height: 28px; }
        }
    </style>

    @include('admin.partials.notifications')

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-boxes me-2"></i>
                        Quản lý sản phẩm biến thể
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý toàn bộ sản phẩm biến thể trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <form action="{{ route('admin.product-variants.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text rounded-start-pill">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control ps-0" placeholder="Tìm SKU hoặc tên sản phẩm...">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    @if(isset($products) && $products->isNotEmpty())
                        <form action="{{ route('admin.product-variants.index') }}" method="GET">
                            <select name="product_id" class="form-select form-select-lg border-teal" style="color: var(--primary-teal);" onchange="this.form.submit()">
                                <option value="">Tất cả sản phẩm</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                    @can('create product variant')
                        <a href="{{ route('admin.product-variants.create') }}" class="btn btn-success btn-lg rounded-pill">
                            <i class="fas fa-plus-circle me-2"></i> Thêm biến thể mới
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg">
                            <i class="fas fa-boxes fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng biến thể</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $productVariants->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-teal); width: 100%"></div>
                </div>
            </div>
        </div>
        <!-- Có thể thêm các stats khác nếu cần -->
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-table me-2"></i>
                                Danh sách sản phẩm biến thể
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-hashtag me-1" style="color: var(--primary-teal);"></i> ID
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-image me-1" style="color: var(--primary-orange);"></i> Ảnh sản phẩm
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-box-open me-1" style="color: var(--accent-yellow);"></i> Sản phẩm
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-barcode me-1" style="color: var(--primary-teal);"></i> SKU
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-money-bill-wave me-1" style="color: var(--accent-yellow);"></i> Giá
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-cart-plus me-1" style="color: var(--primary-orange);"></i> Tồn kho
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-alt me-1" style="color: var(--primary-teal);"></i> Ngày tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-orange);"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productVariants as $variant)
                                    <tr class="border-bottom border-light">
                                        <td>{{ $loop->iteration + ($productVariants->currentPage() - 1) * $productVariants->perPage() }}</td>
                                        <td>
                                            @if ($variant->image_url)
                                                <img src="{{ asset('storage/' . $variant->image_url) }}"
                                                     alt="{{ $variant->product->name ?? 'Không xác định' }}"
                                                     class="rounded" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 80px;">
                                                    <i class="fas fa-image text-muted fs-3"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $variant->product->name ?? 'Không xác định' }}</span>
                                        </td>
                                        <td>{{ $variant->sku ?? 'Chưa có SKU' }}</td>
                                        <td>{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ $variant->stock_quantity }}</td>
                                        <td>
                                            <span class="text-muted"><i class="fas fa-calendar-alt me-2"></i>{{ $variant->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.product-variants.show', $variant->id) }}"
                                                   class="btn btn-sm view-detail-btn" data-bs-toggle="tooltip" title="Xem chi tiết biến thể">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('edit product variant')
                                                    <a href="{{ route('admin.product-variants.edit', $variant->id) }}"
                                                       class="btn btn-sm edit-btn" data-bs-toggle="tooltip" title="Chỉnh sửa biến thể">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-box-open fs-1 mb-2"></i>
                                            Không có sản phẩm biến thể nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                @if($productVariants->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $productVariants->firstItem() }}-{{ $productVariants->lastItem() }} trong tổng {{ $productVariants->total() }} biến thể
                            </div>
                            <div>
                                {{ $productVariants->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -30px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe table rows and cards
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(row);
    });

    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>
@endsection