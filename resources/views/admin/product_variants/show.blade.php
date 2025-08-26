@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        @if ($productVariant->image_url)
                            <img src="{{ asset('storage/' . $productVariant->image_url) }}" alt="{{ $productVariant->sku }}" class="img-fluid mt-2" style="max-width: 200px;">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                        <div class="mt-3">
                            <h4>Biến thể #{{ $productVariant->id }}</h4>
                            <p class="text-muted">Thông tin chi tiết về biến thể <strong>{{ $productVariant->sku }}</strong>.</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <a href="{{ route('admin.product-variants.edit', $productVariant->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon> Sửa
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.product-variants.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
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
                    <h4 class="badge bg-info text-light fs-14 py-1 px-2">Chi tiết biến thể sản phẩm</h4>
                    <p class="mb-1">
                        <span class="fs-24 text-dark fw-medium">{{ $productVariant->sku }}</span>
                    </p>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:hashtag-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                ID: <span class="text-muted">{{ $productVariant->id }}</span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:box-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Sản phẩm: <span class="text-muted">{{ $productVariant->product->name ?? 'Không xác định' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:money-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Giá: <span class="text-muted">{{ number_format($productVariant->price, 0, ',', '.') }}₫</span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:cart-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Tồn kho: <span class="text-muted">{{ $productVariant->stock_quantity }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:check-circle-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Trạng thái:
                                <span class="badge {{ $productVariant->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $productVariant->is_active ? 'Hoạt động' : 'Ngừng' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:calendar-add-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Ngày tạo: <span class="text-muted">{{ $productVariant->created_at ? $productVariant->created_at->format('d/m/Y H:i') : 'Không xác định' }}</span>
                            </p>
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:calendar-mark-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Ngày cập nhật: <span class="text-muted">{{ $productVariant->updated_at ? $productVariant->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</span>
                            </p>
                        </div>
                    </div>

                    <h4 class="text-dark fw-medium mt-4 d-flex justify-content-between align-items-center">
                        <span>
                            <iconify-icon icon="solar:list-broken" class="align-middle fs-18 me-1"></iconify-icon>
                            Danh sách thuộc tính & giá trị
                        </span>
                        <a href="{{ route('admin.product-variants.create') }}" class="btn btn-sm btn-primary">
                            <iconify-icon icon="solar:add-circle-broken" class="align-middle fs-18 me-1"></iconify-icon>
                            Thêm biến thể
                        </a>
                    </h4>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>
                                        <iconify-icon icon="solar:hashtag-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        #
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:text-field-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Thuộc tính
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:text-field-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Giá trị
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productVariant->productVariantOptions as $key => $option)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $option->attributeValue->attribute->name ?? '' }}</td>
                                    <td>{{ $option->attributeValue->value ?? '' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Không có thuộc tính nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                    <p class="text-muted">
                        Biến thể <strong>{{ $productVariant->sku }}</strong> thuộc sản phẩm <strong>{{ $productVariant->product->name ?? 'Không xác định' }}</strong>.
                        Hiện có <strong>{{ $productVariant->productVariantOptions->count() }}</strong> thuộc tính liên quan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection