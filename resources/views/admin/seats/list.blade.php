@extends('layouts.admin.admin')

@section('content')

    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Danh sách ghế ngồi</h4>

                        <form class="app-search d-none d-md-block ms-2" method="GET" action="{{ route('admin.seats.index') }}">
                            <div class="position-relative">
                                <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-2" placeholder="Tìm kiếm ghế..." autocomplete="off" value="{{ request('query') }}">
                                <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                            </div>
                        </form>

                        <a href="{{ route('admin.seats.create') }}" class="btn btn-sm btn-primary">
                            Thêm ghế ngồi
                        </a>

                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
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
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Phòng chiếu</th>
                                        <th>Loại ghế</th>
                                        <th>Hàng ghế</th>
                                        <th>Số ghế</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seats as $seat)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input" id="customCheck2-{{ $seat->id }}">
                                                <label class="form-check-label" for="customCheck2-{{ $seat->id }}"> </label>
                                            </div>
                                        </td>
                                        <td>{{ $seat->id }}</td>
                                        <td>{{ $seat->room->name }}</td>
                                        <td>
                                            <span class="badge p-1" style="background-color: {{ $seat->seatType->color_code }}; color: #fff;">
                                                {{ $seat->seatType->name }}
                                            </span>
                                        </td>
                                        <td>{{ $seat->row_char }}</td>
                                        <td>{{ $seat->seat_number }}</td>
                                        @php
                                            $statusColors = [
                                                'available' => 'bg-success',
                                                'reserved'  => 'bg-secondary',
                                                'booked'    => 'bg-warning',
                                            ];
                                            $statusValue = is_object($seat->status) ? $seat->status->value : $seat->status;
                                        @endphp

                                        <td>
                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-danger' }}">
                                                {{ ucfirst($statusValue) }}
                                            </span>
                                        </td>
                                        <td>{{ $seat->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.seats.show', $seat->id) }}" class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.seats.edit', $seat->id) }}" class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <!-- <form action="{{ route('admin.seats.destroy', $seat->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa ghế này?')">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form> -->
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-end">
                            {{ $seats->appends(['query' => request('query'), 'seat_type_id' => request('seat_type_id')])->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection