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
                            Danh sách sản phẩm
                        </h4>
                        <div class="d-flex gap-2 align-items-center">
                            {{-- Form lọc sản phẩm theo danh mục --}}
                            <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex align-items-center">
                                <select name="category_id" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach(\App\Models\ProductCategory::all() as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            @can('create product')
                                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                                    <iconify-icon icon="solar:add-circle-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                    Thêm sản phẩm
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th><iconify-icon icon="solar:hashtag-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>ID</th>
                                        <th><iconify-icon icon="solar:image-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>Ảnh</th>
                                        <th><iconify-icon icon="solar:text-field-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>Tên sản phẩm</th>
                                        <th>
                                            <iconify-icon icon="mdi:barcode"
                                                class="align-middle fs-16 me-1"></iconify-icon>SKU
                                        </th>
                                        <th>
                                            <iconify-icon icon="solar:money-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>Giá gốc
                                        </th>
                                        <th><iconify-icon icon="solar:document-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>Mô tả</th>
                                        <th><iconify-icon icon="solar:tag-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>Danh mục</th>
                                        <th><iconify-icon icon="solar:cart-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>Trạng thái đặt hàng</th>
                                        <th><iconify-icon icon="solar:calendar-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>Ngày tạo</th>
                                        <th><iconify-icon icon="solar:settings-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                            </td>
                                            <td>
                                                @if ($product->image_url)
                                                    <img src="{{ asset('storage/' . $product->image_url) }}"
                                                        alt="{{ $product->name }}"
                                                        style="max-width: 200px; max-height: 100px;">
                                                @else
                                                    <span class="text-muted">Không có ảnh</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $product->name }}
                                            </td>
                                            <td>
                                                {{ $product->sku ?? 'Chưa có SKU' }}
                                            </td>
                                            <td>{{ number_format($product->base_price, 0, ',', '.') }}₫</td>
                                            <td>{{ Str::limit($product->description, 50) }}</td>
                                            <td>{{ $product->category->name ?? 'Chưa có danh mục' }}</td>
                                            <td>
                                                @php
                                                    $totalBookedQuantity = $product->productVariants()
                                                        ->withSum(['bookingItems' => function($query) {
                                                            $query->whereHas('booking', function($bookingQuery) {
                                                                $bookingQuery->whereIn('status', ['confirmed', 'completed']);
                                                            });
                                                        }], 'quantity')
                                                        ->get()
                                                        ->sum('booking_items_sum_quantity');
                                                @endphp
                                                @if ($totalBookedQuantity > 0)
                                                    <span class="badge bg-warning text-dark">{{ $totalBookedQuantity }} đã đặt</span>
                                                    <small class="text-muted d-block">Không thể sửa</small>
                                                @else
                                                    <span class="badge bg-success">Chưa có đơn</span>
                                                    <small class="text-muted d-block">Có thể sửa</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $product->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if (!$product->canBeEdited())
                                                        <span class="btn btn-soft-secondary btn-sm disabled" 
                                                              title="Không thể chỉnh sửa - sản phẩm đã có đơn đặt hàng">
                                                            <iconify-icon icon="solar:pen-2-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </span>
                                                    @else
                                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                                            class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                                            <iconify-icon icon="solar:pen-2-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                    @endif
                                                    
                                                    <a href="{{ route('admin.products.show', $product->id) }}"
                                                        class="btn btn-soft-info btn-sm" title="Xem chi tiết">
                                                        <iconify-icon icon="solar:eye-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-end">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .btn-soft-secondary {
        background: rgba(107, 114, 128, 0.1);
        border: 1px solid rgba(107, 114, 128, 0.2);
        color: #6b7280;
        cursor: not-allowed;
    }

    .btn-soft-secondary.disabled {
        opacity: 0.6;
    }

    .table td img {
        border-radius: 8px;
        object-fit: cover;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-weight: 500;
    }
</style>
@endsection