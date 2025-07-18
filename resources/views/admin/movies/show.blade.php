@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('assets/images/movie-placeholder.png')) }}" alt="Movie Poster" class="img-fluid bg-light rounded">
                    <div class="mt-3">
                        <h4>{{ $movie->name }}</h4>
                        <p class="text-muted">Thông tin chi tiết về phim {{ $movie->name }}.</p>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <a href="{{ route('admin.movies.edit', ['id' => $movie->id]) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="bx bx-edit fs-18"></i> Sửa
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="bx bx-arrow-back fs-18"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="badge bg-info text-light fs-14 py-1 px-2">Chi tiết</h4>
                    <p class="mb-1">
                        <span class="fs-24 text-dark fw-medium">{{ $movie->name }}</span>
                    </p>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Đạo diễn: <span class="text-muted">{{ $movie->director ?? 'N/A' }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Diễn viên: <span class="text-muted">{{ $movie->actors ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời lượng: <span class="text-muted">{{ $movie->duration_minutes }} phút</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Ngôn ngữ: <span class="text-muted">{{ $movie->language ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Ngày phát hành: <span class="text-muted">{{ $movie->release_date->format('d/m/Y') }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Ngày kết thúc: <span class="text-muted">{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Quốc gia: <span class="text-muted">{{ $movie->country?->name ?? 'N/A' }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Giới hạn tuổi: <span class="text-muted">{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Trạng thái: 
                                <span class="badge" style="background-color: {{ $statusColors[$movie->status->value ?? $movie->status] ?? '#6c757d' }}; color: #fff;">
                                    {{ ucfirst($movie->status->value ?? $movie->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Điểm đánh giá: <span class="text-muted">{{ $movie->average_rating ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian tạo: <span class="text-muted">{{ $movie->created_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian cập nhật: <span class="text-muted">{{ $movie->updated_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-12">
                            <p class="mb-0 fw-medium text-dark fs-16">Trailer: 
                                <span class="text-muted">
                                    @if ($movie->trailer_url)
                                        <a href="{{ $movie->trailer_url }}" target="_blank">Xem trailer</a>
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
<<<<<<< Updated upstream
=======
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Ảnh: 
                                <span class="text-muted">
                                    @if ($movie->image_path)
                                        <a href="{{ Storage::url($movie->image_path) }}" target="_blank">Xem ảnh</a>
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian tạo: <span class="text-muted">{{ $movie->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian cập nhật: <span class="text-muted">{{ $movie->updated_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>
>>>>>>> Stashed changes
                    <h4 class="text-dark fw-medium mt-4">Mô tả:</h4>
                    <p class="text-muted">{{ $movie->description ?? 'Không có mô tả.' }}</p>
                    <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                    <p class="text-muted">Phim {{ $movie->name }} có thời lượng {{ $movie->duration_minutes }} phút, phát hành ngày {{ $movie->release_date->format('d/m/Y') }}. Trạng thái hiện tại là {{ ucfirst($movie->status->value ?? $movie->status) }}.</p>

                    <!-- Danh sách suất chiếu -->
                    <h4 class="badge bg-info text-light fs-14 py-1 px-2 mt-4">Danh sách suất chiếu</h4>

                    <!-- Form lọc suất chiếu -->
                    <div class="d-flex justify-content-between align-items-center gap-1 mb-3">
                        <form class="app-search d-none d-md-block" method="GET" action="{{ route('admin.movies.show', ['id' => $movie->id]) }}">
                            <div class="position-relative">
                                <input type="search" name="showtime_query" class="form-control form-control-sm ps-5 pe-3 rounded-2" placeholder="Tìm kiếm suất chiếu (phòng, thời gian)..." autocomplete="off" value="{{ request('showtime_query') }}">
                                <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                                <input type="date" name="start_date" class="form-control form-control-sm w-auto mt-2" value="{{ request('start_date') }}" placeholder="Chọn ngày bắt đầu">
                                @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                Lọc suất chiếu
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- Lọc theo trạng thái -->
                                <h6 class="dropdown-header">Trạng thái</h6>
                                <a href="{{ route('admin.movies.show', array_merge(['id' => $movie->id], array_filter(['showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ !request('showtime_status') || request('showtime_status') == 'all' ? 'active' : '' }}">Tất cả</a>
                                @foreach (['scheduled', 'ongoing', 'completed', 'cancelled'] as $status)
                                    <a href="{{ route('admin.movies.show', array_merge(['id' => $movie->id], array_filter(['showtime_status' => $status, 'showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ request('showtime_status') == $status ? 'active' : '' }}">{{ ucfirst($status) }}</a>
                                @endforeach

                                <!-- Lọc theo phòng chiếu -->
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Phòng chiếu</h6>
                                <a href="{{ route('admin.movies.show', array_merge(['id' => $movie->id], array_filter(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ !request('room_id') ? 'active' : '' }}">Tất cả</a>
                                @foreach ($rooms as $room)
                                    <a href="{{ route('admin.movies.show', array_merge(['id' => $movie->id], array_filter(['room_id' => $room->id, 'showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ request('room_id') == $room->id ? 'active' : '' }}">{{ $room->name }}</a>
                                @endforeach
                            </div>
                        </div>
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

<<<<<<< Updated upstream
                    <div class="d-flex justify-content-between align-items-center gap-1 mb-3">
                        <form class="app-search d-none d-md-block" method="GET" action="{{ route('admin.movies.show', $movie->id) }}">
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
                                <a href="{{ route('admin.movies.show', array_merge(['id' => $movie->id], array_filter(['showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ !request('showtime_status') || request('showtime_status') == 'all' ? 'active' : '' }}">Tất cả</a>
                                @foreach (['scheduled', 'ongoing', 'completed', 'cancelled'] as $status)
                                    <a href="{{ route('admin.movies.show', array_merge(['id' => $movie->id], array_filter(['showtime_status' => $status, 'showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ request('showtime_status') == $status ? 'active' : '' }}">{{ ucfirst($status) }}</a>
                                @endforeach

                                <!-- Lọc theo phòng chiếu -->
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Phòng chiếu</h6>
                                <a href="{{ route('admin.movies.show', array_merge(['id' => $movie->id], array_filter(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ !request('room_id') ? 'active' : '' }}">Tất cả</a>
                                @foreach ($rooms as $room)
                                    <a href="{{ route('admin.movies.show', array_merge(['id' => $movie->id], array_filter(['room_id' => $room->id, 'showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" class="dropdown-item {{ request('room_id') == $room->id ? 'active' : '' }}">{{ $room->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
=======
                    <!-- Modal xác nhận tạo suất chiếu -->
                    <div class="modal fade" id="confirmShowtimeModal" tabindex="-1" aria-labelledby="confirmShowtimeModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmShowtimeModalLabel">Xác nhận tạo suất chiếu</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Bạn có muốn tạo các suất chiếu sau cho phim "<strong id="modalMovieName"></strong>" vào ngày <strong id="modalDate"></strong>?</p>
                                    <p><strong>Phòng chiếu:</strong> <span id="modalRooms"></span></p>
                                    <p><strong>Khung giờ:</strong></p>
                                    <ul id="modalSlots"></ul>
                                    <p><strong>Tổng cộng:</strong> <span id="modalTotal"></span> suất chiếu mỗi phòng.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="button" class="btn btn-primary" id="confirmCreateShowtime">Xác nhận</button>
                                </div>
                            </div>
                        </div>
                    </div>

>>>>>>> Stashed changes
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
                                        <td>{{ number_format($showtime->base_price, 0, ',', '.') }}</td>
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
<<<<<<< Updated upstream
                                        <td>{{ $showtime->created_at->format('d/m/Y H:i') }}</td>
=======
                                        <td>{{ $showtime->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-edit fs-16"></i>
                                            </a>
                                        </td>
>>>>>>> Stashed changes
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

<script>
    // Cập nhật URL nút "Tạo suất chiếu" khi thay đổi ngày
    document.getElementById('showtime_date').addEventListener('change', function() {
        const date = this.value;
        const btn = document.getElementById('create_showtime_btn');
        const baseUrl = "{{ route('admin.showtimes.create', ['movie_id' => $movie->id]) }}";
        btn.href = `${baseUrl}&date=${date}`;
    });
<<<<<<< Updated upstream
=======
    document.getElementById('modalTotal').textContent = slots.length;

    // Hiển thị modal xác nhận
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmShowtimeModal'), {});
    confirmModal.show();
});

// Xử lý xác nhận tạo suất chiếu
document.getElementById('confirmCreateShowtime').addEventListener('click', function() {
    document.getElementById('createShowtimeForm').submit();
});
>>>>>>> Stashed changes
</script>

@endsection