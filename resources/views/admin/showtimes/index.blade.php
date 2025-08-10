@extends('layouts.admin.admin')

@section('content')
    <div class="container-fluid">
        @include('admin.partials.notifications')

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Danh sách suất chiếu</h4>

                        <form class="app-search d-none d-md-block ms-2" method="GET" action="{{ route('admin.showtimes.index') }}">
                            <div class="position-relative">
                                <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-2" placeholder="Tìm kiếm suất chiếu..." autocomplete="off" value="{{ request('query') }}">
                                <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                            </div>
                        </form>

                        <a href="{{ route('admin.showtimes.create') }}" class="btn btn-sm btn-primary">
                            Thêm suất chiếu
                        </a>

                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                Lọc
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Theo trạng thái</h6>
                                <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'status' => 'all'])) }}" class="dropdown-item {{ request('status', 'all') == 'all' ? 'active' : '' }}">Tất cả</a>
                                <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'status' => 'scheduled'])) }}" class="dropdown-item {{ request('status') == 'scheduled' ? 'active' : '' }}">Scheduled</a>
                                <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'status' => 'ongoing'])) }}" class="dropdown-item {{ request('status') == 'ongoing' ? 'active' : '' }}">Ongoing</a>
                                <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'status' => 'completed'])) }}" class="dropdown-item {{ request('status') == 'completed' ? 'active' : '' }}">Completed</a>
                                <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'status' => 'cancelled'])) }}" class="dropdown-item {{ request('status') == 'cancelled' ? 'active' : '' }}">Cancelled</a>

                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Theo phim</h6>
                                <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'movie_id' => null])) }}" class="dropdown-item {{ !request('movie_id') ? 'active' : '' }}">Tất cả</a>
                                @foreach (\App\Models\Movie::all() as $movie)
                                    <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'movie_id' => $movie->id, 'status' => request('status')])) }}" class="dropdown-item {{ request('movie_id') == $movie->id ? 'active' : '' }}">{{ $movie->name }}</a>
                                @endforeach

                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Theo phòng</h6>
                                <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'room_id' => null])) }}" class="dropdown-item {{ !request('room_id') ? 'active' : '' }}">Tất cả</a>
                                @foreach (\App\Models\Room::all() as $room)
                                    <a href="{{ route('admin.showtimes.index', array_filter(['query' => request('query'), 'room_id' => $room->id, 'status' => request('status'), 'movie_id' => request('movie_id')])) }}" class="dropdown-item {{ request('room_id') == $room->id ? 'active' : '' }}">{{ $room->name }}</a>
                                @endforeach

                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Theo thời gian</h6>
                                <form class="px-2">
                                    <div class="mb-2">
                                        <label for="start_date" class="form-label fs-12">Từ ngày</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label for="end_date" class="form-label fs-12">Đến ngày</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary w-100">Áp dụng</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Phim</th>
                                        <th>Phòng chiếu</th>
                                        <th>Thời gian bắt đầu</th>
                                        <th>Thời gian kết thúc</th>
                                        <th>Giá vé cơ bản</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($showtimes as $showtime)
                                        <tr>
                                            <td>{{ $showtime->id }}</td>
                                            <td>{{ $showtime->movie->name ?? 'Không xác định' }}</td>
                                            <td>{{ $showtime->room->name ?? 'Không xác định' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($showtime->start_time)->format('d/m/Y H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($showtime->end_time)->format('d/m/Y H:i') }}</td>
                                            <td>{{ number_format($showtime->base_price, 2) }} VNĐ</td>
                                            @php
                                                $statusValue = $showtime->status->value;
                                                $statusColor = $showtime->status->color();
                                            @endphp
                                            <td>
                                                <span class="badge {{ $statusColor }}">
                                                    {{ ucfirst($statusValue) }}
                                                </span>
                                            </td>
                                            <td>{{ $showtime->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.showtimes.show', $showtime->id) }}" class="btn btn-light btn-sm">
                                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Không có suất chiếu nào được tìm thấy.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer border-top">
                            <div class="d-flex justify-content-end">
                                {{ $showtimes->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection