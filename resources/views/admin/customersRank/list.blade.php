@extends('layouts.admin.admin')

@section('content')
    <!-- Bắt đầu Container Fluid -->
    <div class="container-xxl">

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card overflow-hiddenCoupons">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center p-3">
                    <h5 class="card-title mb-0">Quản lý Hạng Khách Hàng</h5>
                    <form action="{{ route('customers-rank.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-auto" style="min-width: 250px;">
                            <input type="search" name="keyword" class="form-control" placeholder="Tìm kiếm theo tên hạng..."
                                autocomplete="off" value="{{ request('keyword') }}">
                        </div>

                        <div class="col-auto" style="min-width: 180px;">
                            <select name="percentage_order" class="form-select">
                                <option value="">Thứ tự % giảm giá</option>
                                <option value="desc" {{ request('percentage_order') == 'desc' ? 'selected' : '' }}>Giảm dần
                                </option>
                                <option value="asc" {{ request('percentage_order') == 'asc' ? 'selected' : '' }}>Tăng dần
                                </option>
                            </select>
                        </div>

                        <div class="col-auto d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill me-1"></i> Lọc
                            </button>
                            <a href="{{ route('customers-rank.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Đặt lại
                            </a>
                        </div>
                    </form>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Điểm Tối Thiểu</th>
                                    <th>Phần Trăm Giảm Giá</th>
                                    <th>Mô Tả</th>
                                    <th>Ngày Tạo</th>
                                    <th>Ngày Cập Nhật</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customerRanks as $customerRank)
                                    <tr>
                                        <td>{{ $customerRank->id }}</td>
                                        <td>{{ $customerRank->name }}</td>
                                        <td>{{ $customerRank->min_points_required }}</td>
                                        <td>{{ $customerRank->discount_percentage }}%</td>
                                        <td>{{ $customerRank->description }}</td>
                                        <td>{{ $customerRank->created_at }}</td>
                                        <td>{{ $customerRank->updated_at }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('customers-rank.show', $customerRank->id) }}"
                                                    class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>

                                                <a href="{{ route('customers-rank.edit', $customerRank->id) }}"
                                                    class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>

                                                <form action="{{ route('customers-rank.softDelete', $customerRank->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc muốn xóa hạng khách hàng này không?')">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- kết thúc table-responsive -->
                </div>
                <div class="row g-0 align-items-center justify-content-between text-center text-sm-start p-3 border-top">
                    <div class="col-sm">
                        <div class="text-muted">
                            Hiển thị
                            <span class="fw-semibold">{{ $customerRanks->firstItem() ?? 0 }}</span>
                            đến
                            <span class="fw-semibold">{{ $customerRanks->lastItem() ?? 0 }}</span>
                            trong tổng số
                            <span class="fw-semibold">{{ $customerRanks->total() }}</span> kết quả
                        </div>
                    </div>
                    <div class="col-sm-auto mt-3 mt-sm-0">
                        {{ $customerRanks->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc Container Fluid -->
@endsection