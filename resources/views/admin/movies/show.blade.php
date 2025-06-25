@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <!-- Cột trái: Poster và mô tả -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                   <img
                        src="{{ $movie->poster_url ? asset('storage/'.$movie->poster_url) : asset('assets/images/default-poster.png') }}"
                        alt="Poster phim"
                        style="max-width: 600px; max-height: 320px; object-fit: cover; border-radius: 8px;"
>
                    <h4 class="mb-2">{{ $movie->name }}</h4>
                    <span class="badge bg-info mb-2">{{ ucfirst(is_object($movie->status) ? $movie->status->value : $movie->status) }}</span>
                    <div class="mb-2">
                        @if($movie->genres && $movie->genres->count())
                            @foreach($movie->genres as $genre)
                                <span class="badge bg-secondary">{{ $genre->name }}</span>
                            @endforeach
                        @endif
                    </div>
                    <p class="text-muted">{{ $movie->description }}</p>
                </div>
                <div class="card-footer border-top bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-primary w-100">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cột phải: Thông tin chi tiết -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="card-title mb-0">Thông tin chi tiết</h4>
                </div>
                <div class="card-body">
                    <div class="row h-100">
                        <!-- Cột trái -->
                        <div class="col-md-6 border-end h-100 d-flex flex-column">
                            <ul class="list-unstyled mb-0 flex-grow-1">
                                <li class="mb-3"><i class="bi bi-person-video2 me-1"></i> <span class="fw-semibold text-dark">Đạo diễn:</span> {{ $movie->director ?? 'N/A' }}</li>
                                <li class="mb-3"><i class="bi bi-people-fill me-1"></i> <span class="fw-semibold text-dark">Diễn viên:</span> {{ $movie->actors ?? 'N/A' }}</li>
                                <li class="mb-3"><i class="bi bi-clock me-1"></i> <span class="fw-semibold text-dark">Thời lượng:</span> {{ $movie->duration_minutes ?? 'N/A' }} phút</li>
                                <li class="mb-3"><i class="bi bi-calendar-event me-1"></i> <span class="fw-semibold text-dark">Ngày phát hành:</span>
                                    {{ $movie->release_date ? $movie->release_date->format('d/m/Y') : 'N/A' }}
                                </li>
                                <li class="mb-3"><i class="bi bi-calendar-x me-1"></i> <span class="fw-semibold text-dark">Ngày kết thúc:</span>
                                    {{ $movie->end_date ? $movie->end_date->format('d/m/Y') : 'N/A' }}
                                </li>
                            </ul>
                        </div>
                        <!-- Cột phải -->
                        <div class="col-md-6 h-100 d-flex flex-column">
                            <ul class="list-unstyled mb-0 flex-grow-1">
                                <li class="mb-3"><i class="bi bi-translate me-1"></i> <span class="fw-semibold text-dark">Ngôn ngữ:</span> {{ $movie->language ?? 'N/A' }}</li>
                                <li class="mb-3"><i class="bi bi-geo-alt me-1"></i> <span class="fw-semibold text-dark">Quốc gia:</span> {{ $movie->country?->name ?? 'N/A' }}</li>
                                <li class="mb-3"><i class="bi bi-person-badge me-1"></i> <span class="fw-semibold text-dark">Giới hạn tuổi:</span> {{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</li>
                                <li class="mb-3"><i class="bi bi-award me-1"></i> <span class="fw-semibold text-dark">Trạng thái:</span>
                                    <span class="badge" style="background-color: {{ $movie->status == 'active' ? '#28a745' : ($movie->status == 'upcoming' ? '#ffc107' : ($movie->status == 'ended' ? '#dc3545' : '#6c757d')) }}; color: #fff;">
                                        {{ ucfirst(is_object($movie->status) ? $movie->status->value : $movie->status) }}
                                    </span>
                                </li>
                                <li class="mb-3"><i class="bi bi-star-fill text-warning me-1"></i> <span class="fw-semibold text-dark">Điểm đánh giá:</span> {{ $movie->average_rating ?? 'N/A' }}</li>
                                <li class="mb-3"><i class="bi bi-play-circle me-1"></i> <span class="fw-semibold text-dark">Trailer:</span>
                                    @if ($movie->trailer_url)
                                        <a href="{{ $movie->trailer_url }}" target="_blank">Xem trailer</a>
                                    @else
                                        <span class="text-muted">Chưa có</span>
                                    @endif
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <span class="fw-semibold text-dark">Ghi chú:</span>
                                    <span class="text-muted d-block mt-1">
                                        Phim {{ $movie->name }} có thời lượng {{ $movie->duration_minutes }} phút, phát hành ngày {{ $movie->release_date->format('d/m/Y') }}. Trạng thái hiện tại là {{ ucfirst(is_object($movie->status) ? $movie->status->value : $movie->status) }}.
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr class="my-3">
                    <!-- Danh sách suất chiếu -->
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="mb-0 fw-semibold text-dark">Danh sách suất chiếu</h5>
                        </div>
                        <!-- Form tạo suất chiếu tự động -->
                        <form action="{{ route('admin.showtimes.storeAuto') }}" method="POST" class="d-flex align-items-center gap-2 mb-3">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                            <select name="room_id" class="form-control form-control-sm w-auto" required>
                                <option value="">Chọn phòng</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="date" name="date" class="form-control form-control-sm w-auto" value="{{ now()->format('Y-m-d') }}" required>
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                                <i class="bx bx-plus fs-18"></i> Tạo suất chiếu
                            </button>
                        </form>
                        <div class="d-flex justify-content-between align-items-center gap-1 mb-3">
                            <form class="app-search d-none d-md-block" method="GET" action="{{ route('admin.movies.show', ['movie' => $movie->id]) }}">
                                <div class="position-relative">
                                    <input type="search" name="showtime_query" class="form-control form-control-sm ps-5 pe-3 rounded-2" placeholder="Tìm kiếm suất chiếu (phòng, thời gian)..." autocomplete="off" value="{{ request('showtime_query') }}">
                                    <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                                </div>
                            </form>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                    Lọc suất chiếu
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- Lọc theo trạng thái -->
                                    <h6 class="dropdown-header">Trạng thái</h6>
                                    <a href="{{ route('admin.movies.show', array_merge(['movie' => $movie->id], array_filter(['showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ !request('showtime_status') || request('showtime_status') == 'all' ? 'active' : '' }}">Tất cả</a>
                                    @foreach (['scheduled', 'ongoing', 'completed', 'cancelled'] as $status)
                                        <a href="{{ route('admin.movies.show', array_merge(['movie' => $movie->id], array_filter(['showtime_status' => $status, 'showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ request('showtime_status') == $status ? 'active' : '' }}">{{ ucfirst($status) }}</a>
                                    @endforeach

                                    <!-- Lọc theo phòng chiếu -->
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">Phòng chiếu</h6>
                                    <a href="{{ route('admin.movies.show', array_merge(['movie' => $movie->id], array_filter(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ !request('room_id') ? 'active' : '' }}">Tất cả</a>
                                    @foreach ($rooms as $room)
                                        <a href="{{ route('admin.movies.show', array_merge(['movie' => $movie->id], array_filter(['room_id' => $room->id, 'showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ request('room_id') == $room->id ? 'active' : '' }}">{{ $room->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Phòng chiếu</th>
                                        <th>Thời gian bắt đầu</th>
                                        <th>Thời gian kết thúc</th>
                                        <th>Giá vé (VNĐ)</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($showtimes as $showtime)
                                        <tr>
                                            <td>{{ $showtime->id }}</td>
                                            <td>{{ $showtime->room?->name ?? 'N/A' }}</td>
                                            <td>{{ $showtime->start_time->format('d/m/Y H:i') }}</td>
                                            <td>{{ $showtime->end_time->format('d/m/Y H:i') }}</td>
                                            <td>{{ number_format($showtime->base_price, 2) }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'scheduled' => 'bg-primary',
                                                        'ongoing' => 'bg-success',
                                                        'completed' => 'bg-secondary',
                                                        'cancelled' => 'bg-danger',
                                                    ];
                                                    $statusValue = is_object($showtime->status) ? $showtime->status->value : $showtime->status;
                                                @endphp
                                                <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                    {{ ucfirst($statusValue) }}
                                                </span>
                                            </td>
                                            <td>{{ $showtime->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có suất chiếu nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-end">
                                {{ $showtimes->appends(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'room_id' => request('room_id'), 'start_date' => request('start_date')])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Cập nhật URL nút "Tạo suất chiếu" khi thay đổi ngày
    document.getElementById('showtime_date').addEventListener('change', function() {
        const date = this.value;
        const btn = document.getElementById('create_showtime_btn');
        const baseUrl = "{{ route('admin.showtimes.create', ['movie_id' => $movie->id]) }}";
        btn.href = `${baseUrl}&date=${date}`;
    });
</script>

@endsection