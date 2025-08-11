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
                            Danh mục sản phẩm
                        </h4>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <span class="input-group-text bg-white border-teal" style="border-color: #0D9488;">
                                    <iconify-icon icon="solar:magnifier-bold" class="text-teal fs-18"></iconify-icon>
                                </span>
                                <input type="text" class="form-control border-teal" placeholder="Tìm kiếm danh mục..." style="border-color: #0D9488;">
                            </div>
                            @can('create product category')
                                <a href="{{ route('admin.product-categories.create') }}" class="btn btn-sm btn-teal text-white" data-bs-toggle="tooltip" title="Thêm danh mục mới" style="background-color: #0D9488; border: none;">
                                    <iconify-icon icon="solar:add-circle-bold" class="fs-18 me-1"></iconify-icon>
                                    Thêm
                                </a>
                            @endcan
                            @can('delete product category')
                                <a href="{{ route('admin.product-categories.trash') }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Xem thùng rác" style="border-color: #FACC15; color: #FACC15;">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-bold" class="fs-18"></iconify-icon>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead style="background-color: #0D9488; color: white; position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th class="ps-4" style="width: 10%;">
                                            <iconify-icon icon="solar:hashtag-bold" class="fs-16 me-1"></iconify-icon>ID
                                        </th>
                                        <th style="width: 25%;">
                                            <iconify-icon icon="solar:text-field-bold" class="fs-16 me-1"></iconify-icon>Tên danh mục
                                        </th>
                                        <th style="width: 35%;">
                                            <iconify-icon icon="solar:document-bold" class="fs-16 me-1"></iconify-icon>Mô tả
                                        </th>
                                        <th style="width: 20%;">
                                            <iconify-icon icon="solar:calendar-bold" class="fs-16 me-1"></iconify-icon>Ngày tạo
                                        </th>
                                        <th style="width: 10%;">
                                            <iconify-icon icon="solar:settings-bold" class="fs-16 me-1"></iconify-icon>Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr class="align-middle" style="transition: background-color 0.3s;">
                                            <td class="ps-4">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                            <td>
                                                <iconify-icon icon="solar:tag-bold" class="fs-16 me-2 text-orange" style="color: #F97316;"></iconify-icon>
                                                <span class="fw-medium">{{ $category->name }}</span>
                                            </td>
                                            <td>{{ Str::limit($category->description, 50) }}</td>
                                            <td>
                                                <iconify-icon icon="solar:clock-circle-bold" class="fs-16 me-2 text-muted"></iconify-icon>
                                                {{ $category->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @can('edit product category')
                                                        <a href="{{ route('admin.product-categories.edit', $category->id) }}"
                                                           class="btn btn-sm btn-outline-teal" data-bs-toggle="tooltip" title="Chỉnh sửa danh mục"
                                                           style="border-color: #0D9488; color: #0D9488;">
                                                            <iconify-icon icon="solar:pen-2-bold" class="fs-18"></iconify-icon>
                                                        </a>
                                                    @endcan
                                                    @can('delete product category')
                                                        <form action="{{ route('admin.product-categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Xóa danh mục"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')" style="border-color: #FACC15; color: #FACC15;">
                                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-bold" class="fs-18"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <iconify-icon icon="solar:box-linear" class="fs-24 me-2"></iconify-icon>
                                                Không có danh mục nào
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
                                Hiển thị {{ $categories->firstItem() }} - {{ $categories->lastItem() }} của {{ $categories->total() }} danh mục
                            </div>
                            <div>
                                {{ $categories->links('pagination::bootstrap-5') }}
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
            .btn-outline-warning {
                border-color: #FACC15;
                color: #FACC15;
                transition: all 0.3s ease;
            }
            .btn-outline-warning:hover {
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
        </style>
    @endpush
@endsection