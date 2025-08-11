@extends('layouts.admin.admin')

@section('content')
    <div class="container-fluid px-4 py-5 bg-light">
        @include('admin.partials.notifications')

        <!-- Dashboard Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Tổng khuyến mãi</h4>
                            <p class="fw-bold fs-3 mb-0 text-primary">{{ $promotions->count() }}</p>
                        </div>
                        <div class="avatar-xl bg-primary bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:tag-broken" class="fs-48 text-primary"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Khuyến mãi active</h4>
                            <p class="fw-bold fs-3 mb-0 text-success">{{ $promotions->where('status', 'active')->count() }}</p>
                        </div>
                        <div class="avatar-xl bg-success bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:check-circle-broken" class="fs-48 text-success"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Khuyến mãi pending</h4>
                            <p class="fw-bold fs-3 mb-0 text-warning">{{ $promotions->where('status', 'pending')->count() }}</p>
                        </div>
                        <div class="avatar-xl bg-warning bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:clock-circle-broken" class="fs-48 text-warning"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Khuyến mãi inactive</h4>
                            <p class="fw-bold fs-3 mb-0 text-danger">{{ $promotions->where('status', 'inactive')->count() }}</p>
                        </div>
                        <div class="avatar-xl bg-danger bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:close-circle-broken" class="fs-48 text-danger"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotions List Section -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-dark text-white d-flex justify-content-between align-items-center gap-3">
                <h4 class="mb-0 fw-bold">Danh sách khuyến mãi</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary rounded-pill d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:add-circle-broken" class="fs-20"></iconify-icon>
                        Thêm mới
                    </a>
                    <a href="{{ route('admin.promotions.trashed') }}" class="btn btn-outline-danger rounded-pill d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-20"></iconify-icon>
                        Xem đã xoá mềm
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter -->
                <div class="mb-4 d-flex justify-content-between align-items-end gap-3">
                    <form method="GET" action="{{ route('admin.promotions.index') }}" class="flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-3">
                                <iconify-icon icon="solar:search-broken" class="text-muted fs-22"></iconify-icon>
                            </span>
                            <input type="text" name="search" placeholder="Tìm kiếm tên hoặc mã KM" value="{{ request('search') }}" class="form-control border-start-0 rounded-end-3">
                            <button type="submit" class="btn btn-primary rounded-pill ms-2 d-flex align-items-center gap-2">
                                <iconify-icon icon="solar:filter-broken" class="fs-20"></iconify-icon>
                                Tìm
                            </button>
                        </div>
                    </form>
                    <div class="dropdown">
                        <a href="#" class="btn btn-outline-light rounded-pill dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                            <iconify-icon icon="solar:settings-broken" class="fs-20"></iconify-icon>
                            Lọc nâng cao
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="width: 300px;">
                            <form method="GET" action="{{ route('admin.promotions.index') }}">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Chọn trạng thái</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="discount_type" class="form-label">Loại giảm giá</label>
                                    <select name="discount_type" id="discount_type" class="form-select">
                                        <option value="">Chọn loại giảm giá</option>
                                        @foreach($discountTypes as $type)
                                            <option value="{{ $type->value }}" {{ request('discount_type') == $type->value ? 'selected' : '' }}>{{ $type->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên khuyến mãi</label>
                                    <input type="text" name="name" id="name" placeholder="Tên khuyến mãi" value="{{ request('name') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="code" class="form-label">Mã KM</label>
                                    <input type="text" name="code" id="code" placeholder="Mã KM" value="{{ request('code') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 rounded-pill">Áp dụng lọc</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Promotions Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered border-light align-middle rounded-3">
                        <thead class="table-dark">
                            <tr>
                                <th class="py-3" style="width: 20px;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1"></label>
                                    </div>
                                </th>
                                <th class="py-3">ID <iconify-icon icon="solar:hashtag-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Tên khuyến mãi <iconify-icon icon="solar:label-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Mã KM <iconify-icon icon="solar:code-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Hạng KH <iconify-icon icon="solar:user-check-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Loại giảm giá <iconify-icon icon="solar:discount-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Giá trị giảm <iconify-icon icon="solar:money-bag-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Ngày bắt đầu <iconify-icon icon="solar:calendar-add-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Ngày kết thúc <iconify-icon icon="solar:calendar-mark-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Trạng thái <iconify-icon icon="solar:traffic-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Thao tác <iconify-icon icon="solar:settings-broken" class="fs-18"></iconify-icon></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promotions as $promotion)
                                <tr class="transition-all duration-300 hover:shadow-md hover:bg-gray-50">
                                    <td class="py-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck{{ $promotion->id }}">
                                            <label class="form-check-label" for="customCheck{{ $promotion->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="py-3 fw-medium">#{{ $promotion->id }}</td>
                                    <td class="py-3">{{ $promotion->name }}</td>
                                    <td class="py-3">{{ $promotion->code }}</td>
                                    <td class="py-3">
                                        @if($promotion->rank)
                                            <span class="badge bg-info-subtle text-info fw-medium px-3 py-2 rounded-pill">{{ $promotion->rank->name }}</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary fw-medium px-3 py-2 rounded-pill">Tất cả</span>
                                        @endif
                                    </td>
                                    <td class="py-3">{{ $promotion->discount_type }}</td>
                                    <td class="py-3 text-nowrap">{{ number_format($promotion->discount_value, 2) }}</td>
                                    <td class="py-3 text-nowrap">{{ $promotion->start_date->format('d/m/Y') }}</td>
                                    <td class="py-3 text-nowrap">{{ $promotion->end_date->format('d/m/Y') }}</td>
                                    <td class="py-3">
                                        <span class="badge {{ $promotion->status == 'active' ? 'bg-success-subtle text-success' : ($promotion->status == 'pending' ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }} fw-medium px-3 py-2 rounded-pill">
                                            {{ ucfirst($promotion->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.promotions.show', $promotion->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Xem">
                                                <iconify-icon icon="solar:eye-broken" class="fs-18"></iconify-icon>
                                            </a>
                                            <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-outline-primary btn-sm rounded-pill" title="Sửa">
                                                <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                            </a>
                                            <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Xoá mềm?')" title="Xoá">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
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
            <div class="card-footer border-top bg-light">
                <div class="d-flex justify-content-end">
                    {{ $promotions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Enhanced Styling -->
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
        .bg-gradient {
            background: linear-gradient(90deg, rgba(33, 37, 41, 1) 0%, rgba(52, 58, 64, 1) 100%);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.9rem;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
        .form-control, .form-select, .btn {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, rgba(0, 86, 179, 1) 0%, rgba(0, 123, 255, 1) 100%);
        }
        .btn-outline-primary:hover, .btn-outline-danger:hover, .btn-outline-info:hover {
            color: #fff !important;
        }
        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
@endsection