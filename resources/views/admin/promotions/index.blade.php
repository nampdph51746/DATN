@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Danh sách khuyến mãi</h4>
                    <div class="d-flex gap-2 align-items-center">
                        <form method="GET" action="{{ route('promotions.index') }}" class="d-flex align-items-center gap-2">
                            <input type="text" name="search" placeholder="Tìm kiếm tên hoặc mã KM" value="{{ request('search') }}" class="form-control form-control-sm" style="width: 200px;">
                            <button type="submit" class="btn btn-sm btn-primary">Tìm</button>
                        </form>
                        <a href="{{ route('promotions.create') }}" class="btn btn-sm btn-primary">Thêm mới</a>
                        <a href="{{ route('promotions.trashed') }}" class="btn btn-outline-danger btn-sm">Xem đã xoá mềm</a>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                Lọc
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <form method="GET" action="{{ route('promotions.index') }}" class="p-2">
                                    <div class="mb-2">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select name="status" id="status" class="form-control form-control-sm">
                                            <option value="">Chọn trạng thái</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="discount_type" class="form-label">Loại giảm giá</label>
                                        <select name="discount_type" id="discount_type" class="form-control form-control-sm">
                                            <option value="">Chọn loại giảm giá</option>
                                            @foreach($discountTypes as $type)
                                                <option value="{{ $type->value }}" {{ request('discount_type') == $type->value ? 'selected' : '' }}>{{ $type->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="name" class="form-label">Tên khuyến mãi</label>
                                        <input type="text" name="name" id="name" placeholder="Tên khuyến mãi" value="{{ request('name') }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-2">
                                        <label for="code" class="form-label">Mã KM</label>
                                        <input type="text" name="code" id="code" placeholder="Mã KM" value="{{ request('code') }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-2">
                                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                        <input type="date" name="start_date" id="start_date" placeholder="Ngày bắt đầu" value="{{ request('start_date') }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-2">
                                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                                        <input type="date" name="end_date" id="end_date" placeholder="Ngày kết thúc" value="{{ request('end_date') }}" class="form-control form-control-sm">
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
                                    <th>ID</th>
                                    <th>Tên khuyến mãi</th>
                                    <th>Mã KM</th>
                                    <th>Loại giảm giá</th>
                                    <th>Giá trị giảm</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promotions as $promotion)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck{{ $promotion->id }}">
                                            <label class="form-check-label" for="customCheck{{ $promotion->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $promotion->id }}</td>
                                    <td>{{ $promotion->name }}</td>
                                    <td>{{ $promotion->code }}</td>
                                    <td>{{ $promotion->discount_type }}</td>
                                    <td>{{ number_format($promotion->discount_value, 2) }}</td>
                                    <td>{{ $promotion->start_date->format('d/m/Y') }}</td>
                                    <td>{{ $promotion->end_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $promotion->status == 'active' ? 'bg-success' : ($promotion->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $promotion->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('promotions.show', $promotion->id) }}" class="btn btn-light btn-sm">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Xoá mềm?')">
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
                        {{ $promotions->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection