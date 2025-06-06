@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                {{-- Thông báo  --}}
                @include('admin.partials.alert')
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Danh sách khuyến mãi theo hạng khách hàng</h4>
                    <div class="d-flex gap-2 align-items-center">
                        <form method="GET" action="{{ route('customer_rank_promotions.index') }}" class="d-flex align-items-center gap-2">
                            <input type="text" name="search" placeholder="Tìm kiếm ID hạng hoặc ID KM" value="{{ request('search') }}" class="form-control form-control-sm" style="width: 200px;">
                            <button type="submit" class="btn btn-sm btn-primary">Tìm</button>
                        </form>
                        {{-- <a href="{{ route('customer_rank_promotions.create') }}" class="btn btn-sm btn-primary">Thêm mới</a> --}}
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                Lọc
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <form method="GET" action="{{ route('customer_rank_promotions.index') }}" class="p-2">
                                    <div class="mb-2">
                                        <label for="customer_rank_id" class="form-label">ID Hạng khách hàng</label>
                                        <input type="text" name="customer_rank_id" id="customer_rank_id" placeholder="ID Hạng khách hàng" value="{{ request('customer_rank_id') }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-2">
                                        <label for="promotion_id" class="form-label">ID Khuyến mãi</label>
                                        <input type="text" name="promotion_id" id="promotion_id" placeholder="ID Khuyến mãi" value="{{ request('promotion_id') }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-2">
                                        <label for="description" class="form-label">Mô tả</label>
                                        <input type="text" name="description" id="description" placeholder="Mô tả" value="{{ request('description') }}" class="form-control form-control-sm">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Lọc</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1"></label>
                                        </div>
                                    </th>
                                    <th>ID Hạng KH</th>
                                    <th>Mã giảm giá</th>
                                    <th>Mô tả</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck{{ $item->customer_rank_id }}_{{ $item->promotion_id }}">
                                            <label class="form-check-label" for="customCheck{{ $item->customer_rank_id }}_{{ $item->promotion_id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $item->customer_rank_id }}</td>
                                    <td>{{ $item->discount_code }}</td>
                                    <td>{{ $item->description ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('customer_rank_promotions.show', [$item->customer_rank_id, $item->promotion_id]) }}" class="btn btn-light btn-sm">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <a href="{{ route('customer_rank_promotions.edit', [$item->customer_rank_id, $item->promotion_id]) }}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <form action="{{ route('customer_rank_promotions.destroy', [$item->customer_rank_id, $item->promotion_id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Xóa vĩnh viễn?')">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <nav aria-label="Page navigation example">
                        {{ $items->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection