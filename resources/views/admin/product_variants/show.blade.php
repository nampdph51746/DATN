@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    @include('admin.partials.notifications')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    @if ($productVariant->image_url)
                        <img src="{{ asset('storage/' . $productVariant->image_url) }}" alt="{{ $productVariant->sku }}" class="img-fluid mt-2" style="max-width: 200px;">
                    @else
                        <span class="text-muted">Không có ảnh</span>
                    @endif
                    <div class="mt-3">
                        <h4>SKU: {{ $productVariant->sku }}</h4>
                        <p class="text-muted">Sản phẩm: <strong>{{ $productVariant->product->name ?? 'Không xác định' }}</strong></p>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <a href="{{ route('admin.product-variants.edit', $productVariant->id) }}" class="btn btn-primary w-100 mb-2">
                        <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon> Sửa
                    </a>
                    <a href="{{ route('admin.product-variants.index') }}" class="btn btn-outline-secondary w-100">
                        <iconify-icon icon="solar:arrow-left-broken" class="fs-18"></iconify-icon> Quay lại
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="badge bg-info text-light fs-14 py-1 px-2">Chi tiết biến thể sản phẩm</h4>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <p><strong>SKU:</strong> {{ $productVariant->sku }}</p>
                            <p><strong>Giá:</strong> {{ number_format($productVariant->price, 0, ',', '.') }}₫</p>
                            <p><strong>Tồn kho:</strong> {{ $productVariant->stock_quantity }}</p>
                            <p><strong>Trạng thái:</strong>
                                <span class="badge {{ $productVariant->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $productVariant->is_active ? 'Hoạt động' : 'Ngừng' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p><strong>Ngày tạo:</strong> {{ $productVariant->created_at ? $productVariant->created_at->format('d/m/Y H:i') : 'Không xác định' }}</p>
                            <p><strong>Ngày cập nhật:</strong> {{ $productVariant->updated_at ? $productVariant->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</p>
                        </div>
                    </div>
                    <hr>
                    <h5>Thuộc tính biến thể:</h5>
                    <ul>
                        @foreach ($productVariant->productVariantOptions as $option)
                            <li>
                                <strong>{{ $option->attributeValue->attribute->name ?? '' }}:</strong>
                                {{ $option->attributeValue->value ?? '' }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
