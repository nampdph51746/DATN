@extends('layouts.admin.admin')

@section('content')
    <div class="container-fluid">
        @include('admin.partials.notifications')

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">

                            <iconify-icon icon="solar:box-broken" class="fs-64 text-primary"></iconify-icon>
                            <div class="mt-3">

                                @if ($product->image_url)
                                    <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('assets/images/default.png') }}"
                                        alt="{{ $product->name }}" class="img-fluid mt-2" style="max-width: 200px;">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </div>
                            <div class="mt-3">
                                <h4>Sản phẩm #{{ $product->id }}</h4>
                                <p class="text-muted">Thông tin chi tiết về sản phẩm <strong>{{ $product->name }}</strong>.
                                </p>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon> Sửa
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('admin.products.index') }}"
                                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
                                    <iconify-icon icon="solar:arrow-left-broken" class="fs-18"></iconify-icon> Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="badge bg-info text-light fs-14 py-1 px-2">Chi tiết sản phẩm</h4>
                        <p class="mb-1">
                            <span class="fs-24 text-dark fw-medium">{{ $product->name }}</span>
                        </p>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:hashtag-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    ID: <span class="text-muted">{{ $product->id }}</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:tag-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                    Danh mục: <span
                                        class="text-muted">{{ $product->category ? $product->category->name : 'Không xác định' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:calendar-add-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    Thời gian tạo: <span
                                        class="text-muted">{{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : 'Không xác định' }}</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:calendar-mark-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    Thời gian cập nhật: <span
                                        class="text-muted">{{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:check-circle-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    Trạng thái: <span
                                        class="text-muted">{{ $product->is_active ? 'Hoạt động' : 'Không hoạt động' }}</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:box-minimalistic-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    Loại sản phẩm: <span class="text-muted">{{ $product->product_type }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:text-bold-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Mô tả:
                            </p>
                            <p class="text-muted mt-2">{!! $product->description ?: 'Không có mô tả' !!}</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-dark fw-medium mb-0">
                                <iconify-icon icon="solar:list-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                Danh sách biến thể sản phẩm
                            </h4>
                            <a href="{{ route('admin.product-variants.create', ['product_id' => $product->id]) }}" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                Thêm biến thể
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>
                                            <iconify-icon icon="solar:hashtag-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>
                                            #
                                        </th>
                                        <th>
                                            <iconify-icon icon="solar:text-field-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>
                                            Biến thể
                                        </th>
                                        <th>
                                            <iconify-icon icon="solar:calendar-add-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>
                                            Thời gian tạo
                                        </th>
                                        <th>
                                            <iconify-icon icon="solar:calendar-mark-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>
                                            Thời gian cập nhật
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($product->productVariants as $key => $variant)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $variant->name ?? ($variant->sku ?? 'Không có tên') }}</td>
                                            <td>{{ $variant->created_at ? $variant->created_at->format('d/m/Y H:i') : 'Không xác định' }}
                                            </td>
                                            <td>{{ $variant->updated_at ? $variant->updated_at->format('d/m/Y H:i') : 'Không xác định' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Không có biến thể sản phẩm
                                                nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                        <p class="text-muted">
                            Sản phẩm <strong>{{ $product->name }}</strong> thuộc danh mục
                            <strong>{{ $product->category ? $product->category->name : 'Không xác định' }}</strong>.
                            Hiện có
                            <strong>{{ $product->productVariants ? $product->productVariants->count() : 0 }}</strong> biến
                            thể sản phẩm liên quan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
