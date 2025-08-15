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
                            <h4 class="card-title mb-2 text-dark fw-bold">Tổng loại ghế</h4>
                            <p class="fw-bold fs-3 mb-0 text-primary">{{ $seatTypes->count() }}</p>
                        </div>
                        <div class="avatar-xl bg-primary bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:armchair-2-broken" class="fs-48 text-primary"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Loại ghế mới nhất</h4>
                            <p class="fw-bold fs-3 mb-0 text-success">{{ $seatTypes->first()->name ?? 'N/A' }}</p>
                        </div>
                        <div class="avatar-xl bg-success bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:armchair-broken" class="fs-48 text-success"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Giá modifier trung bình</h4>
                            <p class="fw-bold fs-3 mb-0 text-warning">{{ number_format($seatTypes->avg('price_modifier'), 2) }}</p>
                        </div>
                        <div class="avatar-xl bg-warning bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:money-bag-broken" class="fs-48 text-warning"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Loại ghế cũ nhất</h4>
                            <p class="fw-bold fs-3 mb-0 text-info">{{ $seatTypes->sortBy('created_at')->first()->name ?? 'N/A' }}</p>
                        </div>
                        <div class="avatar-xl bg-info bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:calendar-broken" class="fs-48 text-info"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seat Types List Section -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-dark text-white d-flex justify-content-between align-items-center gap-3">
                <h4 class="mb-0 fw-bold">Tất cả loại ghế</h4>
                <div class="d-flex gap-2">
                    @can('create seat type')
                        <a href="{{ route('seat-type.create') }}" class="btn btn-primary rounded-pill d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:add-circle-broken" class="fs-20"></iconify-icon>
                            Thêm loại ghế
                        </a>
                    @endcan
                    @can('delete seat type')
                        <a href="{{ route('seat-type.trash') }}" class="btn btn-outline-danger rounded-pill" title="Thùng rác">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-20"></iconify-icon>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Form (Optional, add if needed) -->
                <!-- <form method="GET" class="mb-4 d-flex flex-wrap gap-3 align-items-end">
                    ...
                </form> -->

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
                                <th class="py-3">Tên <iconify-icon icon="solar:user-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Giá modifier <iconify-icon icon="solar:money-bag-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Mã màu <iconify-icon icon="solar:palette-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Mô tả <iconify-icon icon="solar:notebook-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Tạo lúc <iconify-icon icon="solar:calendar-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Hành động <iconify-icon icon="solar:settings-broken" class="fs-18"></iconify-icon></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($seatTypes as $seatType)
                                <tr class="transition-all duration-300 hover:shadow-md hover:bg-gray-50">
                                    <td class="py-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck{{ $seatType->id }}">
                                            <label class="form-check-label" for="customCheck{{ $seatType->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center" style="background-color: {{ $seatType->color_code }};">
                                                <span class="text-white fs-20">{{ substr($seatType->name, 0, 1) }}</span>
                                            </div>
                                            <p class="text-dark fw-medium fs-16 mb-0">{{ $seatType->name }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 fw-medium">{{ number_format($seatType->price_modifier, 2) }}</td>
                                    <td class="py-3">
                                        <span class="badge px-3 py-2 rounded-pill" style="background-color: {{ $seatType->color_code }}; color: #fff;">
                                            {{ $seatType->color_code }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ Str::limit($seatType->description, 50) }}</td>
                                    <td class="py-3 text-nowrap">{{ $seatType->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('seat-type.edit', $seatType->id) }}" class="btn btn-outline-primary btn-sm rounded-pill" title="Sửa">
                                                <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                            </a>
                                            @can('delete seat type')
                                                <form action="{{ route('seat-type.destroy', $seatType->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Bạn có chắc chắn muốn xóa loại ghế này?')" title="Xóa">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endcan
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
                    {{ $seatTypes->links('pagination::bootstrap-5') }}
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
        .btn-outline-primary:hover, .btn-outline-danger:hover {
            color: #fff !important;
        }
        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
@endsection