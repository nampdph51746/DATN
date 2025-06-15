@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">
                        <iconify-icon icon="solar:box-broken" class="align-middle fs-18 me-2"></iconify-icon>
                        Danh sách sản phẩm biến thể
                    </h4>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.product-variants.index') }}" method="GET" class="d-flex align-items-center gap-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light-subtle">
                                    <iconify-icon icon="solar:magnifer-broken" class="fs-16"></iconify-icon>
                                </span>
                                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm SKU hoặc tên sản phẩm...">
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                              
                                Tìm
                            </button>
                        </form>
                        <a href="{{ route('admin.product-variants.create') }}" class="btn btn-sm btn-primary">
                            <iconify-icon icon="solar:add-circle-broken" class="align-middle fs-18 me-1"></iconify-icon>
                            Thêm biến thể
                        </a>
                    
                    </div>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>
                                        <iconify-icon icon="solar:hashtag-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        ID
                                    </th>
                                    <th>
                                        <iconify-icon icon="material-symbols:image" class="align-middle fs-16 me-1"></iconify-icon>
                                        Ảnh sản phẩm
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:box-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Sản phẩm
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:barcode-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        SKU
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:money-bag-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Giá
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:cart-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Tồn kho
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:calendar-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Ngày tạo
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:settings-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productVariants as $variant)
                                    <tr>
                                        <td>{{ $loop->iteration + ($productVariants->currentPage() - 1) * $productVariants->perPage() }}</td>
                                        <td>
                                                @if ($variant->image_url)
                                                    <img src="{{ asset('storage/' . $variant->image_url) }}" alt="{{ $variant->product->name }}" style="max-width: 200px; max-height: 100px;">
                                                @else
                                                    <span class="text-muted">Không có ảnh</span>
                                                @endif
                                            </td>
                                        <td>
                                            <iconify-icon icon="solar:box-broken" class="align-middle fs-16 me-1 text-primary"></iconify-icon>
                                            {{ $variant->product->name ?? 'Không xác định' }}
                                        </td>
                                        <td>{{ $variant->sku ?? 'Chưa có SKU' }}</td>
                                        <td>{{ number_format($variant->price, 2) }} VNĐ</td>
                                        <td>{{ $variant->stock_quantity }}</td>
                                        <td>
                                            <iconify-icon icon="solar:clock-circle-broken" class="align-middle fs-16 me-1 text-muted"></iconify-icon>
                                            {{ $variant->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.product-variants.show', $variant->id) }}" class="btn btn-soft-info btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.product-variants.edit', $variant->id) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có sản phẩm biến thể nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $productVariants->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection