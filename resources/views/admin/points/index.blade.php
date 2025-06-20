@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow-sm">
                    {{-- Thông báo --}}
                    @include('admin.partials.alert')
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 bg-primary text-white">
                        <h4 class="card-title flex-grow-1 mb-0">
                            <iconify-icon icon="solar:gift-bold" class="fs-3 me-2"></iconify-icon>
                            Danh sách điểm thưởng
                        </h4>
                        <div class="d-flex gap-2 align-items-center">
                            <form method="GET" action="{{ route('admin.points.index') }}" class="d-flex align-items-center gap-2">
                                <input type="text" name="search" placeholder="Tìm kiếm ID khách hàng hoặc điểm" value="{{ request('search') }}" class="form-control form-control-sm" style="width: 200px;">
                                <button type="submit" class="btn btn-sm btn-light">
                                    <iconify-icon icon="solar:magnifer-linear" class="fs-5"></iconify-icon> Tìm
                                </button>
                            </form>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                    <iconify-icon icon="solar:filter-bold" class="fs-5"></iconify-icon> Lọc
                                </a>
                                <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 250px;">
                                    <form method="GET" action="{{ route('admin.points.index') }}">
                                        <div class="mb-2">
                                            <label for="customer_id" class="form-label">ID Khách hàng</label>
                                            <input type="text" name="customer_id" id="customer_id" placeholder="ID Khách hàng" value="{{ request('customer_id') }}" class="form-control form-control-sm">
                                        </div>
                                        <div class="mb-2">
                                            <label for="points" class="form-label">Điểm</label>
                                            <input type="text" name="points" id="points" placeholder="Điểm" value="{{ request('points') }}" class="form-control form-control-sm">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <iconify-icon icon="solar:filter-bold" class="fs-5"></iconify-icon> Lọc
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 40px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheckAll">
                                        </div>
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:user-bold" class="fs-5 me-1"></iconify-icon>
                                        Khách hàng
                                    </th>
                                    <th>
                                        <iconify-icon icon="mdi:coin" class="fs-5 me-1 text-warning"></iconify-icon>
                                        Điểm xu
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:calendar-bold" class="fs-5 me-1"></iconify-icon>
                                        Ngày hết hạn
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:history-bold" class="fs-5 me-1"></iconify-icon>
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($points as $point)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="pointCheck{{ $point->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">
                                                <iconify-icon icon="solar:user-circle-bold" class="fs-5 text-primary me-1"></iconify-icon>
                                                {{ $point->user->name ?? 'N/A' }}
                                            </span>
                                            <br>
                                            <small class="text-muted">ID: {{ $point->user_id ?? $point->customer_id }}</small>
                                        </td>
                                        <td class="fw-bold text-warning">
                                            <iconify-icon icon="mdi:coin" class="fs-6 text-warning me-1"></iconify-icon>
                                            {{ $point->total_points ?? $point->points }}
                                        </td>
                                        <td>
                                            <iconify-icon icon="solar:calendar-bold" class="fs-6 me-1"></iconify-icon>
                                            {{ $point->points_expiry_date }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.points.show', $point->id) }}"
                                                    class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-bold" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.point_history.index', ['user_id' => $point->user_id ?? $point->customer_id]) }}"
                                                    class="btn btn-soft-primary btn-sm" title="Lịch sử điểm">
                                                    <iconify-icon icon="solar:history-bold" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light">
                        {{ $points->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection