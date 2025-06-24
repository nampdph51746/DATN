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
                            Danh sách combo
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.combos.create') }}" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                Thêm Combo
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Form tìm kiếm và lọc -->
                        <form method="GET" action="{{ route('admin.combos.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo SKU" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="product_id" class="form-control" data-choices data-placeholder="Chọn sản phẩm">
                                        <option value="">Tất cả sản phẩm</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="min_quantity" class="form-control" placeholder="Số lượng tối thiểu" value="{{ request('min_quantity') }}" min="1">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Lọc</button>
                                    <a href="{{ route('admin.combos.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                                </div>
                            </div>
                        </form>

                        <!-- Bảng danh sách combo -->
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th><iconify-icon icon="solar:hashtag-broken" class="align-middle fs-16 me-1"></iconify-icon>ID</th>
                                        <th><iconify-icon icon="solar:image-broken" class="align-middle fs-16 me-1"></iconify-icon>Ảnh</th>
                                        <th><iconify-icon icon="mdi:barcode" class="align-middle fs-16 me-1"></iconify-icon>SKU</th>
                                        <th><iconify-icon icon="solar:text-field-broken" class="align-middle fs-16 me-1"></iconify-icon>Sản phẩm</th>
                                        <th><iconify-icon icon="solar:box-broken" class="align-middle fs-16 me-1"></iconify-icon>Số lượng mục</th>
                                        <th><iconify-icon icon="solar:calendar-broken" class="align-middle fs-16 me-1"></iconify-icon>Ngày tạo</th>
                                        <th><iconify-icon icon="solar:settings-broken" class="align-middle fs-16 me-1"></iconify-icon>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($combos as $combo)
                                        <tr>
                                            <td>{{ $loop->iteration + ($combos->currentPage() - 1) * $combos->perPage() }}</td>
                                            <td>
                                                @if ($combo->image_url)
                                                    <img src="{{ asset('storage/' . $combo->image_url) }}" alt="{{ $combo->sku }}" style="max-width: 100px; max-height: 50px;">
                                                @else
                                                    <span class="text-muted">Không có ảnh</span>
                                                @endif
                                            </td>
                                            <td>{{ $combo->sku }}</td>
                                            <td>{{ $combo->product->name ?? 'Chưa có sản phẩm' }}</td>
                                            <td>{{ $combo->comboPackageItems->sum('quantity') }}</td>
                                            <td>{{ $combo->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.combos.edit', $combo->id) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.combos.show', $combo->id) }}" class="btn btn-soft-info btn-sm" title="Xem chi tiết">
                                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <form action="{{ route('admin.combos.destroy', $combo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa combo này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa">
                                                            <iconify-icon icon="solar:trash-bin-trash-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($combos->isEmpty())
                                <div class="text-center py-3 text-muted">Không có combo nào.</div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-end">
                            {{ $combos->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection