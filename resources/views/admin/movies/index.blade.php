@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
    {{-- Phim đang chiếu --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Phim đang chiếu</h4>
                        <p class="text-muted fw-medium fs-22 mb-0">{{ $movieStats['active'] }}</p>
                    </div>
                    <div>
                        <div class="avatar-md bg-success bg-opacity-10 rounded">
                            <iconify-icon icon="mdi:movie-open" class="fs-32 text-success avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sắp chiếu --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Sắp chiếu</h4>
                        <p class="text-muted fw-medium fs-22 mb-0">{{ $movieStats['upcoming'] }}</p>
                    </div>
                    <div>
                        <div class="avatar-md bg-warning bg-opacity-10 rounded">
                            <iconify-icon icon="mdi:clock-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Đã kết thúc --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Đã kết thúc</h4>
                        <p class="text-muted fw-medium fs-22 mb-0">{{ $movieStats['ended'] }}</p>
                    </div>
                    <div>
                        <div class="avatar-md bg-danger bg-opacity-10 rounded">
                            <iconify-icon icon="mdi:movie-off" class="fs-32 text-danger avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ngưng chiếu --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Ngưng chiếu</h4>
                        <p class="text-muted fw-medium fs-22 mb-0">{{ $movieStats['inactive'] }}</p>
                    </div>
                    <div>
                        <div class="avatar-md bg-secondary bg-opacity-10 rounded">
                            <iconify-icon icon="mdi:close-circle-outline" class="fs-32 text-secondary avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Danh sách phim</h4>

                    <form action="{{ route('admin.movies.index') }}" method="GET" class="d-flex align-items-center ms-2">
                        <input type="text" name="query" value="{{ request('query') }}" class="form-control form-control-sm" placeholder="Tìm kiếm phim (tên, đạo diễn, diễn viên, ngôn ngữ)...">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                    </form>


                    <a href="{{ route('admin.movies.create') }}" class="btn btn-sm btn-primary">
                        Thêm phim
                    </a>

                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                            Lọc
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- Lọc theo trạng thái -->
                            <h6 class="dropdown-header">Trạng thái</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">Tất cả</a>
                            @foreach (['active', 'inactive', 'upcoming', 'ended'] as $status)
                            <a href="{{ route('admin.movies.index', array_filter(['status' => $status, 'query' => request('query'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('status') == $status ? 'active' : '' }}">{{ ucfirst($status) }}</a>
                            @endforeach

                            <!-- Lọc theo quốc gia -->
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Quốc gia</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('country_id') ? 'active' : '' }}">Tất cả</a>
                            @foreach ($countries as $country)
                            <a href="{{ route('admin.movies.index', array_filter(['country_id' => $country->id, 'query' => request('query'), 'status' => request('status'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('country_id') == $country->id ? 'active' : '' }}">{{ $country->name }}</a>
                            @endforeach

                            <!-- Lọc theo giới hạn độ tuổi -->
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Giới hạn độ tuổi</h6>
                            <a href="{{ route('admin.movies.index', array_filter(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ !request('age_limit_id') ? 'active' : '' }}">Tất cả</a>
                            @foreach ($ageLimits as $ageLimit)
                            <a href="{{ route('admin.movies.index', array_filter(['age_limit_id' => $ageLimit->id, 'query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])) }}" class="dropdown-item {{ request('age_limit_id') == $ageLimit->id ? 'active' : '' }}">{{ $ageLimit->name ?? $ageLimit->label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên phim</th>
                                    <th>Đạo diễn</th>
                                    <th>Thời lượng (phút)</th>
                                    <th>Ngày phát hành</th>
                                    <!-- <th>Ngày kết thúc</th> -->
                                    <!-- <th>Ngôn ngữ</th>
                                    <th>Quốc gia</th>
                                    <th>Giới hạn tuổi</th> -->
                                    <th>Trạng thái</th>
                                    <!-- <th>Thời gian tạo</th> -->
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movies as $movie)
                                <tr>
                                    <td>{{ ($movies->currentPage() - 1) * $movies->perPage() + $loop->iteration }}</td>
                                    <td>{{ $movie->name }}</td>
                                    <td>{{ $movie->director ?? 'N/A' }}</td>
                                    <td>{{ $movie->duration_minutes }}</td>
                                    <td>{{ $movie->release_date->format('d/m/Y') }}</td>
                                    <!-- <td>{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</td> -->
                                    <!-- <td>{{ $movie->language ?? 'N/A' }}</td>
                                        <td>{{ $movie->country?->name ?? 'N/A' }}</td>
                                        <td>{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</td> -->
                                    <td>
                                        @php
                                        $statusColors = [
                                        'active' => 'bg-success',
                                        'inactive' => 'bg-secondary',
                                        'upcoming' => 'bg-warning',
                                        'ended' => 'bg-danger',
                                        ];

                                        $statusLabels = [
                                        'active' => 'Đang chiếu',
                                        'inactive' => 'Ngừng chiếu',
                                        'upcoming' => 'Sắp chiếu',
                                        'ended' => 'Đã kết thúc',
                                        ];

                                        $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                        @endphp
                                        <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                            {{ $statusLabels[$statusValue] ?? 'Không rõ' }}
                                        </span>
                                    </td>

                                    <!-- <td>{{ $movie->created_at->format('d/m/Y H:i') }}</td> -->
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.movies.show', $movie->id) }}" class="btn btn-light btn-sm">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $movies->appends(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection