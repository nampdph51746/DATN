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
                            Danh sách combo
                        </h4>
                        <div class="d-flex gap-2 align-items-center">
                            @can('create combo')
                                <a href="{{ route('admin.combos.create') }}" class="btn btn-sm btn-teal text-white" data-bs-toggle="tooltip" title="Thêm combo mới" style="background-color: #0D9488; border: none;">
                                    <iconify-icon icon="solar:add-circle-bold" class="fs-18 me-1"></iconify-icon>
                                    Thêm
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Form tìm kiếm và lọc -->
                        <form method="GET" action="{{ route('admin.combos.index') }}" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-teal" style="border-color: #0D9488;">
                                            <iconify-icon icon="solar:magnifier-bold" class="text-teal fs-18"></iconify-icon>
                                        </span>
                                        <input type="text" name="search" class="form-control border-teal" placeholder="Tìm kiếm theo SKU" value="{{ request('search') }}" style="border-color: #0D9488;">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if(isset($products) && $products->isNotEmpty())
                                        <select name="product_id" class="form-select form-select-sm border-teal" style="border-color: #0D9488; color: #0D9488;" data-choices data-placeholder="Chọn sản phẩm">
                                            <option value="">Tất cả sản phẩm</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="form-select form-select-sm border-teal" disabled>
                                            <option>Không có sản phẩm</option>
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="min_quantity" class="form-control form-control-sm border-teal" placeholder="Số lượng tối thiểu" value="{{ request('min_quantity') }}" min="1" style="border-color: #0D9488;">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-sm btn-teal text-white me-2" style="background-color: #0D9488; border: none;">
                                        <iconify-icon icon="solar:filter-bold" class="fs-18 me-1"></iconify-icon>
                                        Lọc
                                    </button>
                                    <a href="{{ route('admin.combos.index') }}" class="btn btn-sm btn-outline-yellow" style="border-color: #FACC15; color: #FACC15;">
                                        <iconify-icon icon="solar:restart-bold" class="fs-18 me-1"></iconify-icon>
                                        Xóa lọc
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Bảng danh sách combo -->
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
                                        <th style="width: 12%;">
                                            <iconify-icon icon="mdi:barcode" class="fs-16 me-1"></iconify-icon>SKU
                                        </th>
                                        <th style="width: 20%;">
                                            <iconify-icon icon="solar:text-field-bold" class="fs-16 me-1"></iconify-icon>Sản phẩm
                                        </th>
                                        <th style="width: 15%;">
                                            <iconify-icon icon="solar:box-bold" class="fs-16 me-1"></iconify-icon>Số lượng mục
                                        </th>
                                        <th style="width: 15%;">
                                            <iconify-icon icon="solar:calendar-bold" class="fs-16 me-1"></iconify-icon>Ngày tạo
                                        </th>
                                        <th style="width: 15%;">
                                            <iconify-icon icon="solar:settings-bold" class="fs-16 me-1"></iconify-icon>Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($combos as $combo)
                                        <tr class="align-middle" style="transition: background-color 0.3s;">
                                            <td class="ps-4">{{ $loop->iteration + ($combos->currentPage() - 1) * $combos->perPage() }}</td>
                                            <td>
                                                @if ($combo->image_url)
                                                    <img src="{{ asset('storage/' . $combo->image_url) }}"
                                                         alt="{{ $combo->sku }}"
                                                         class="rounded" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 80px;">
                                                        <iconify-icon icon="solar:image-linear" class="text-muted fs-24"></iconify-icon>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $combo->sku }}</td>
                                            <td>
                                                <span class="fw-medium">{{ $combo->product->name ?? 'Chưa có sản phẩm' }}</span>
                                            </td>
                                            <td>{{ $combo->comboPackageItems->sum('quantity') }}</td>
                                            <td>
                                                <iconify-icon icon="solar:clock-circle-bold" class="fs-16 me-2 text-muted"></iconify-icon>
                                                {{ $combo->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @can('edit combo')
                                                        <a href="{{ route('admin.combos.edit', $combo->id) }}"
                                                           class="btn btn-sm btn-outline-teal" data-bs-toggle="tooltip" title="Chỉnh sửa combo"
                                                           style="border-color: #0D9488; color: #0D9488;">
                                                            <iconify-icon icon="solar:pen-2-bold" class="fs-18"></iconify-icon>
                                                        </a>
                                                    @endcan
                                                    <a href="{{ route('admin.combos.show', $combo->id) }}"
                                                       class="btn btn-sm btn-outline-yellow" data-bs-toggle="tooltip" title="Xem chi tiết combo"
                                                       style="border-color: #FACC15; color: #FACC15;">
                                                        <iconify-icon icon="solar:eye-bold" class="fs-18"></iconify-icon>
                                                    </a>
                                                    @can('delete combo')
                                                        <form action="{{ route('admin.combos.destroy', $combo->id) }}" method="POST" class="d-inline"
                                                              onsubmit="return confirm('Bạn có chắc muốn xóa combo này?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Xóa combo"
                                                                    style="border-color: #F97316; color: #F97316;">
                                                                <iconify-icon icon="solar:trash-bin-trash-bold" class="fs-18"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <iconify-icon icon="solar:box-linear" class="fs-24 me-2"></iconify-icon>
                                                Không có combo nào
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
                                Hiển thị {{ $combos->firstItem() }} - {{ $combos->lastItem() }} của {{ $combos->total() }} combo
                            </div>
                            <div>
                                {{ $combos->links('pagination::bootstrap-5') }}
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
            .btn-outline-danger {
                border-color: #F97316;
                color: #F97316;
                transition: all 0.3s ease;
            }
            .btn-outline-danger:hover {
                background-color: #F97316;
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
            .form-control, .form-select {
                border-radius: 6px;
                transition: all 0.3s ease;
            }
            .form-control:focus, .form-select:focus {
                border-color: #F97316;
                box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
            }
        </style>
    @endpush
@endsection