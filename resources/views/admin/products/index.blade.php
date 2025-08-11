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
                            Danh sách sản phẩm
                        </h4>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <span class="input-group-text bg-white border-teal" style="border-color: #0D9488;">
                                    <iconify-icon icon="solar:magnifier-bold" class="text-teal fs-18"></iconify-icon>
                                </span>
                                <input type="text" class="form-control border-teal" placeholder="Tìm kiếm sản phẩm..." style="border-color: #0D9488;">
                            </div>
                            @if(isset($categories) && $categories->isNotEmpty())
                                <form action="{{ route('admin.products.index') }}" method="GET">
                                    <div class="dropdown">
                                        <select name="category_id" class="form-select form-select-sm border-teal" style="border-color: #0D9488; color: #0D9488;" onchange="this.form.submit()">
                                            <option value="">Tất cả danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            @endif
                            @can('create product')
                                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-teal text-white" data-bs-toggle="tooltip" title="Thêm sản phẩm mới" style="background-color: #0D9488; border: none;">
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
                                            <iconify-icon icon="solar:image-bold" class="fs-16 me-1"></iconify-icon>Ảnh
                                        </th>
                                        <th style="width: 20%;">
                                            <iconify-icon icon="solar:text-field-bold" class="fs-16 me-1"></iconify-icon>Tên sản phẩm
                                        </th>
                                        <th style="width: 12%;">
                                            <iconify-icon icon="mdi:barcode" class="fs-16 me-1"></iconify-icon>SKU
                                        </th>
                                        <th style="width: 25%;">
                                            <iconify-icon icon="solar:document-bold" class="fs-16 me-1"></iconify-icon>Mô tả
                                        </th>
                                        <th style="width: 15%;">
                                            <iconify-icon icon="solar:tag-bold" class="fs-16 me-1"></iconify-icon>Danh mục
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
                                    @forelse ($products as $product)
                                        <tr class="align-middle" style="transition: background-color 0.3s;">
                                            <td class="ps-4">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                            <td>
                                                @if ($product->image_url)
                                                    <img src="{{ asset('storage/' . $product->image_url) }}"
                                                         alt="{{ $product->name }}"
                                                         class="rounded" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 80px;">
                                                        <iconify-icon icon="solar:image-linear" class="text-muted fs-24"></iconify-icon>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-medium">{{ $product->name }}</span>
                                            </td>
                                            <td>{{ $product->sku ?? 'Chưa có SKU' }}</td>
                                            <td>{{ Str::limit($product->description, 50) }}</td>
                                            <td>{{ $product->category->name ?? 'Chưa có danh mục' }}</td>
                                            <td>
                                                <iconify-icon icon="solar:clock-circle-bold" class="fs-16 me-2 text-muted"></iconify-icon>
                                                {{ $product->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @can('edit product')
                                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                                           class="btn btn-sm btn-outline-teal" data-bs-toggle="tooltip" title="Chỉnh sửa sản phẩm"
                                                           style="border-color: #0D9488; color: #0D9488;">
                                                            <iconify-icon icon="solar:pen-2-bold" class="fs-18"></iconify-icon>
                                                        </a>
                                                    @endcan
                                                    <a href="{{ route('admin.products.show', $product->id) }}"
                                                       class="btn btn-sm btn-outline-yellow" data-bs-toggle="tooltip" title="Xem chi tiết sản phẩm"
                                                       style="border-color: #FACC15; color: #FACC15;">
                                                        <iconify-icon icon="solar:eye-bold" class="fs-18"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <iconify-icon icon="solar:box-linear" class="fs-24 me-2"></iconify-icon>
                                                Không có sản phẩm nào
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
                                Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} của {{ $products->total() }} sản phẩm
                            </div>
                            <div>
                                {{ $products->links('pagination::bootstrap-5') }}
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