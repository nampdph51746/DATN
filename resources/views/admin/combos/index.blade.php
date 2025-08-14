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
        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }
        .alert-success {
            background: rgba(0, 172, 193, 0.1);
            color: var(--primary-teal);
        }
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #DC3545;
        }
        .combo-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }
        .combo-avatar:hover {
            transform: scale(1.1);
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
            .combo-avatar { width: 35px; height: 35px; }
            .avatar-lg { width: 3rem; height: 3rem; font-size: 1.4rem; }
            .btn-lg { padding: 0.5rem 1rem; font-size: 0.9rem; }
            .input-group { min-width: 100% !important; }
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
                        Quản lý combo sản phẩm
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý toàn bộ combo sản phẩm trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @can('create combo')
                        <a href="{{ route('admin.combos.create') }}" class="btn btn-primary btn-lg rounded-pill">
                            <i class="fas fa-plus-circle me-2"></i> Thêm combo mới
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
                            <h6 class="mb-0 text-muted fw-semibold">Tổng combo</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $combos->total() }}</h3>
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
                                Danh sách combo sản phẩm
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.combos.index') }}">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text rounded-start-pill">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="search" name="search" 
                                               class="form-control ps-0" 
                                               placeholder="Tìm kiếm combo..." 
                                               value="{{ request('search') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
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
                                        <i class="fas fa-image me-1" style="color: var(--primary-orange);"></i> Ảnh
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-box-open me-1" style="color: var(--accent-yellow);"></i> Tên combo
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-box me-1" style="color: var(--primary-teal);"></i> Sản phẩm gốc
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-money-bill-wave me-1" style="color: var(--accent-yellow);"></i> Giá
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-cubes me-1" style="color: var(--primary-orange);"></i> Tồn kho
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-list me-1" style="color: var(--primary-teal);"></i> Số mục
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-orange);"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($combos as $combo)
                                    <tr class="border-bottom border-light">
                                        <td>{{ $loop->iteration + ($combos->currentPage() - 1) * $combos->perPage() }}</td>
                                        <td>
                                            @if ($combo->comboProductVariant && $combo->comboProductVariant->image_url)
                                                <img src="{{ asset('storage/' . $combo->comboProductVariant->image_url) }}"
                                                     alt="{{ $combo->name }}"
                                                     class="combo-avatar">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-image text-muted fs-3"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $combo->name ?? 'Combo không tên' }}</div>
                                            @if($combo->comboProductVariant)
                                                <small class="text-muted">SKU: {{ $combo->comboProductVariant->sku }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($combo->comboProductVariant && $combo->comboProductVariant->product)
                                                <span class="fw-medium">{{ $combo->comboProductVariant->product->name }}</span>
                                            @else
                                                <span class="text-muted">Không xác định</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                {{ number_format($combo->price, 0, ',', '.') }}₫
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                                {{ $combo->stock_quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                                {{ $combo->comboPackageItems->count() }}
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.combos.show', $combo->id) }}"
                                                   class="btn btn-sm view-detail-btn" data-bs-toggle="tooltip" title="Xem chi tiết combo">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('edit combo')
                                                    <a href="{{ route('admin.combos.edit', $combo->id) }}"
                                                       class="btn btn-sm edit-btn" data-bs-toggle="tooltip" title="Chỉnh sửa combo">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete combo')
                                                    <form action="{{ route('admin.combos.destroy', $combo->id) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('Bạn có chắc muốn xóa combo này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Xóa combo">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-box-open fs-1 mb-2"></i>
                                            Không có combo nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                @if($combos->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $combos->firstItem() }}-{{ $combos->lastItem() }} trong tổng {{ $combos->total() }} combo
                            </div>
                            <div>
                                {{ $combos->links('pagination::bootstrap-5') }}
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