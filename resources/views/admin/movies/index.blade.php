@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Danh sách phim</h4>

                    <form class="app-search d-none d-md-block ms-2" method="GET" action="{{ route('admin.movies.index') }}">
                        <div class="position-relative">
                            <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-2" placeholder="Tìm kiếm phim (tên, đạo diễn, diễn viên, ngôn ngữ)..." autocomplete="off" value="{{ request('query') }}">
                            <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                        </div>
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
                                    <th>ID</th>
                                    <th>Tên phim</th>
                                    <th>Đạo diễn</th>
                                    <th>Thời lượng (phút)</th>
                                    <th>Ngày phát hành</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Ngôn ngữ</th>
                                    <th>Quốc gia</th>
                                    <th>Giới hạn tuổi</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movies as $movie)
                                    <tr>
                                        <td>{{ $movie->id }}</td>
                                        <td>{{ $movie->name }}</td>
                                        <td>{{ $movie->director ?? 'N/A' }}</td>
                                        <td>{{ $movie->duration_minutes }}</td>
                                        <td>{{ $movie->release_date->format('d/m/Y') }}</td>
                                        <td>{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>{{ $movie->language ?? 'N/A' }}</td>
                                        <td>{{ $movie->country?->name ?? 'N/A' }}</td>
                                        <td>{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'bg-success',
                                                    'inactive' => 'bg-secondary',
                                                    'upcoming' => 'bg-warning',
                                                    'ended' => 'bg-danger',
                                                ];
                                                $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                            @endphp
                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                {{ ucfirst($statusValue) }}
                                            </span>
                                        </td>
                                        <td>{{ $movie->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.movies.show', $movie->id) }}" class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phim này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm">
                                                        <iconify-icon icon="solar:trash-bin-trash-broken" class="align-middle fs-18"></iconify-icon>
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
                    <div class="d-flex justify-content-end">
                        {{ $movies->appends(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection