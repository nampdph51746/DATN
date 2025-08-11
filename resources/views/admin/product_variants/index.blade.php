@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl py-4">
        @include('admin.partials.notifications')

        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header d-flex justify-content-between align-items-center gap-2 p-3" style="background: linear-gradient(135deg, #F97316, #FACC15); color: white;">
                        <h4 class="card-title mb-0 d-flex align-items-center flex-grow-1">
                            <iconify-icon icon="solar:box-bold" class="fs-22 me-2"></iconify-icon>
                            Danh sách sản phẩm biến thể
                        </h4>
                        <div class="d-flex gap-2 align-items-center">
                            <form action="{{ route('admin.product-variants.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                <div class="input-group input-group-sm" style="max-width: 300px;">
                                    <span class="input-group-text bg-white border-teal" style="border-color: #0D9488;">
                                        <iconify-icon icon="solar:magnifier-bold" class="text-teal fs-18"></iconify-icon>
                                    </span>
                                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control border-teal" placeholder="Tìm SKU hoặc tên sản phẩm..." style="border-color: #0D9488;">
                                </div>
                                <button type="submit" class="btn btn-sm btn-teal text-white" style="background-color: #0D9488; border: none;">
                                    <iconify-icon icon="solar:magnifier-bold" class="fs-18"></iconify-icon>
                                    Tìm
                                </button>
                            </form>
                            @if(isset($products) && $products->isNotEmpty())
                                <form action="{{ route('admin.product-variants.index') }}" method="GET">
                                    <div class="dropdown">
                                        <select name="product_id" class="form-select form-select-sm border-teal" style="border-color: #0D9488; color: #0D9488;" onchange="this.form.submit()">
                                            <option value="">Tất cả sản phẩm</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            @endif
                            @can('create product variant')
                                <a href="{{ route('admin.product-variants.create') }}" class="btn btn-sm btn-teal text-white" data-bs-toggle="tooltip" title="Thêm biến thể mới" style="background-color: #0D9488; border: none;">
                                    <iconify-icon icon="solar:add-circle-bold" class="fs-18 me-1"></iconify-icon>
                                    Thêm
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead style="background-color: #0D9488; color: white; position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th class="ps-4" style="width: 8%;">
                                            <iconify-icon icon="solar:hashtag-bold" class="fs-16 me-1"></iconify-icon>ID
                                        </th>
                                        <th style="width: 15%;">
                                            <iconify-icon icon="solar:image-bold" class="fs-16 me-1"></iconify-icon>Ảnh sản phẩm
                                        </th>
                                        <th style="width: 20%;">
                                            <iconify-icon icon="solar:box-bold" class="fs-16 me-1"></iconify-icon>Sản phẩm
                                        </th>
                                        <th style="width: 12%;">
                                            <iconify-icon icon="solar:barcode-bold" class="fs-16 me-1"></iconify-icon>SKU
                                        </th>
                                        <th style="width: 15%;">
                                            <iconify-icon icon="solar:money-bag-bold" class="fs-16 me-1"></iconify-icon>Giá
                                        </th>
                                        <th style="width: 10%;">
                                            <iconify-icon icon="solar:cart-bold" class="fs-16 me-1"></iconify-icon>Tồn kho
                                        </th>
                                        <th style="width: 15%;">
                                            <iconify-icon icon="solar:calendar-bold" class="fs-16 me-1"></iconify-icon>Ngày tạo
                                        </th>
                                        <th style="width: 10%;">
                                            <iconify-icon icon="solar:settings-bold" class="fs-16 me-1"></iconify-icon>Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productVariants as $variant)
                                        <tr class="align-middle" style="transition: background-color 0.3s;">
                                            <td class="ps-4">{{ $loop->iteration + ($productVariants->currentPage() - 1) * $productVariants->perPage() }}</td>
                                            <td>
                                                @if ($variant->image_url)
                                                    <img src="{{ asset('storage/' . $variant->image_url) }}"
                                                         alt="{{ $variant->product->name ?? 'Không xác định' }}"
                                                         class="rounded" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 80px;">
                                                        <iconify-icon icon="solar:image-linear" class="text-muted fs-24"></iconify-icon>
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
                                                <iconify-icon icon="solar:clock-circle-bold" class="fs-16 me-2 text-muted"></iconify-icon>
                                                {{ $variant->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.product-variants.show', $variant->id) }}"
                                                       class="btn btn-sm btn-outline-yellow" data-bs-toggle="tooltip" title="Xem chi tiết biến thể"
                                                       style="border-color: #FACC15; color: #FACC15;">
                                                        <iconify-icon icon="solar:eye-bold" class="fs-18"></iconify-icon>
                                                    </a>
                                                    @can('edit product variant')
                                                        <a href="{{ route('admin.product-variants.edit', $variant->id) }}"
                                                           class="btn btn-sm btn-outline-teal" data-bs-toggle="tooltip" title="Chỉnh sửa biến thể"
                                                           style="border-color: #0D9488; color: #0D9488;">
                                                            <iconify-icon icon="solar:pen-2-bold" class="fs-18"></iconify-icon>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <iconify-icon icon="solar:box-linear" class="fs-24 me-2"></iconify-icon>
                                                Không có sản phẩm biến thể nào
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top bg-light p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Hiển thị {{ $productVariants->firstItem() }} - {{ $productVariants->lastItem() }} của {{ $productVariants->total() }} biến thể
                            </div>
                            <div>
                                {{ $productVariants->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize Bootstrap tooltips
            document.addEventListener('DOMContentLoaded', function () {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });

            // Add hover effect for table rows
            document.querySelectorAll('tbody tr').forEach(row => {
                row.addEventListener('mouseenter', () => {
                    row.style.backgroundColor = '#FFF7ED'; // Light orange background on hover
                });
                row.addEventListener('mouseleave', () => {
                    row.style.backgroundColor = '';
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .table th, .table td {
                vertical-align: middle;
                padding: 12px;
            }
            .btn-teal {
                background-color: #0D9488;
                border-color: #0D9488;
                transition: all 0.3s ease;
            }
            .btn-teal:hover {
                background-color: #0B8278;
                border-color: #0B8278;
            }
            .btn-outline-teal {
                border-color: #0D9488;
                color: #0D9488;
                transition: all 0.3s ease;
            }
            .btn-outline-teal:hover {
                background-color: #0D9488;
                color: white;
            }
            .btn-outline-yellow {
                border-color: #FACC15;
                color: #FACC15;
                transition: all 0.3s ease;
            }
            .btn-outline-yellow:hover {
                background-color: #FACC15;
                color: white;
            }
            .border-teal {
                border-color: #0D9488 !important;
            }
            .pagination .page-link {
                color: #0D9488;
                border-radius: 6px;
                margin: 0 3px;
                transition: all 0.3s ease;
            }
            .pagination .page-item.active .page-link {
                background-color: #F97316;
                border-color: #F97316;
                color: white;
            }
            .pagination .page-link:hover {
                background-color: #0D9488;
                border-color: #0D9488;
                color: white;
            }
            .table thead th {
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .form-select {
                border-radius: 6px;
                transition: all 0.3s ease;
            }
            .form-select:focus {
                border-color: #F97316;
                box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
            }
        </style>
    @endpush
@endsection