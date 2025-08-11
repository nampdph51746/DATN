@extends('layouts.admin.admin')

@section('content')
    <div class="container-fluid px-4 py-5 bg-light">
        @include('admin.partials.notifications')

        <!-- Seats List Section -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-dark text-white d-flex justify-content-between align-items-center gap-3">
                <h4 class="mb-0 fw-bold">Danh sách ghế ngồi</h4>
                <div class="d-flex gap-2 align-items-center">
                    <form method="GET" action="{{ route('admin.seats.index') }}" class="d-flex align-items-center gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-3">
                                <iconify-icon icon="solar:search-broken" class="text-muted fs-22"></iconify-icon>
                            </span>
                            <input type="search" name="query" class="form-control border-start-0 rounded-end-3" placeholder="Tìm kiếm ghế..." value="{{ request('query') }}">
                        </div>
                    </form>
                    <a href="{{ route('admin.seats.create') }}" class="btn btn-primary rounded-pill d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:add-circle-broken" class="fs-20"></iconify-icon>
                        Thêm ghế ngồi
                    </a>
                    <div class="dropdown">
                        <a href="#" class="btn btn-outline-light rounded-pill dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                            <iconify-icon icon="solar:filter-broken" class="fs-20"></iconify-icon>
                            Loại ghế
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{ route('admin.seats.index', array_filter(['query' => request('query')])) }}" class="dropdown-item {{ !request('seat_type_id') ? 'active' : '' }}">Tất cả</a>
                            @foreach ($seatTypes as $seatType)
                                <a href="{{ route('admin.seats.index', array_filter(['seat_type_id' => $seatType->id, 'query' => request('query')])) }}" class="dropdown-item {{ request('seat_type_id') == $seatType->id ? 'active' : '' }}">{{ $seatType->name }}</a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <a href="#!" class="dropdown-item">Download</a>
                            <a href="#!" class="dropdown-item">Export</a>
                            <a href="#!" class="dropdown-item">Import</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered border-light align-middle rounded-3">
                        <thead class="table-dark">
                            <tr>
                                <th class="py-3" style="width: 20px;">
                                    <div class="form-check ms-1">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1"></label>
                                    </div>
                                </th>
                                <th class="py-3">ID <iconify-icon icon="solar:hashtag-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Phòng chiếu <iconify-icon icon="solar:cinema-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Loại ghế <iconify-icon icon="solar:armchair-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Hàng ghế <iconify-icon icon="solar:rows-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Số ghế <iconify-icon icon="solar:chair-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Trạng thái <iconify-icon icon="solar:traffic-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Tạo lúc <iconify-icon icon="solar:calendar-broken" class="fs-18"></iconify-icon></th>
                                <th class="py-3">Hành động <iconify-icon icon="solar:settings-broken" class="fs-18"></iconify-icon></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($seats as $seat)
                                <tr class="transition-all duration-300 hover:shadow-md hover:bg-gray-50">
                                    <td class="py-3">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2-{{ $seat->id }}">
                                            <label class="form-check-label" for="customCheck2-{{ $seat->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="py-3 fw-medium">{{ $seat->id }}</td>
                                    <td class="py-3">{{ $seat->room->name }}</td>
                                    <td class="py-3">
                                        <span class="badge px-3 py-2 rounded-pill" style="background-color: {{ $seat->seatType->color_code }}; color: #fff;">
                                            {{ $seat->seatType->name }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ $seat->row_char }}</td>
                                    <td class="py-3">{{ $seat->seat_number }}</td>
                                    @php
                                        $statusColors = [
                                            'available' => 'bg-success-subtle text-success',
                                            'reserved' => 'bg-secondary-subtle text-secondary',
                                            'booked' => 'bg-warning-subtle text-warning',
                                        ];
                                        $statusValue = is_object($seat->status) ? $seat->status->value : $seat->status;
                                    @endphp
                                    <td class="py-3">
                                        <span class="badge {{ $statusColors[$statusValue] ?? 'bg-danger-subtle text-danger' }} px-3 py-2 rounded-pill">
                                            {{ ucfirst($statusValue) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-nowrap">{{ $seat->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.seats.show', $seat->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Xem">
                                                <iconify-icon icon="solar:eye-broken" class="fs-18"></iconify-icon>
                                            </a>
                                            <a href="{{ route('admin.seats.edit', $seat->id) }}" class="btn btn-outline-primary btn-sm rounded-pill" title="Sửa">
                                                <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                            </a>
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
                    {{ $seats->appends(['query' => request('query'), 'seat_type_id' => request('seat_type_id')])->links('pagination::bootstrap-5') }}
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
        .btn-outline-info:hover, .btn-outline-primary:hover {
            color: #fff !important;
        }
        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item:hover {
            background-color: #e6f0fa;
        }
    </style>
@endsection