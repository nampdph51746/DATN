@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        @include('admin.partials.notifications')

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : $movie->poster_url ?? asset('assets/images/movie-placeholder.png') }}"
                            alt="Movie Poster" class="img-fluid bg-light rounded">
                        <div class="mt-3">
                            <h4>{{ $movie->name }}</h4>
                            <p class="text-muted">Thông tin chi tiết về phim {{ $movie->name }}.</p>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <a href="{{ route('admin.movies.edit', ['id' => $movie->id]) }}"
                                    class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                    <i class="bx bx-edit fs-18"></i> Sửa
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('admin.movies.index') }}"
                                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
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
                                <p class="mb-0 fw-medium text-dark fs-16">Đạo diễn: <span
                                        class="text-muted">{{ $movie->director ?? 'N/A' }}</span></p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Diễn viên: <span
                                        class="text-muted">{{ $movie->actors ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Thời lượng: <span
                                        class="text-muted">{{ $movie->duration_minutes }} phút</span></p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Ngôn ngữ: <span
                                        class="text-muted">{{ $movie->language ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Ngày phát hành: <span
                                        class="text-muted">{{ $movie->release_date->format('d/m/Y') }}</span></p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Ngày kết thúc: <span
                                        class="text-muted">{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Quốc gia: <span
                                        class="text-muted">{{ $movie->country?->name ?? 'N/A' }}</span></p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Giới hạn tuổi: <span
                                        class="text-muted">{{ $movie->ageLimit?->name ?? ($movie->ageLimit?->label ?? 'N/A') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Thể loại:
                                    <span class="text-muted">
                                        @if ($movie->genres->isNotEmpty())
                                            {{ $movie->genres->pluck('name')->join(', ') }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Trạng thái:
                                    <span class="badge"
                                        style="background-color: {{ $statusColors[$movie->status->value ?? $movie->status] ?? '#6c757d' }}; color: #fff;">
                                        {{ ucfirst($movie->status->value ?? $movie->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Điểm đánh giá: <span
                                        class="text-muted">{{ $movie->average_rating ?? 'N/A' }}</span></p>
                            </div>
                            <div class="col-lg-6">
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
                                <p class="mb-0 fw-medium text-dark fs-16">Thời gian tạo: <span
                                        class="text-muted">{{ $movie->created_at ? $movie->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">Thời gian cập nhật: <span
                                        class="text-muted">{{ $movie->updated_at ? $movie->updated_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</span>
                                </p>
                            </div>
                        </div>
                        <h4 class="text-dark fw-medium mt-4">Mô tả:</h4>
                        <p class="text-muted">{{ $movie->description ?? 'Không có mô tả.' }}</p>
                        <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                        <p class="text-muted">Phim {{ $movie->name }} có thời lượng {{ $movie->duration_minutes }} phút,
                            phát hành ngày {{ $movie->release_date->format('d/m/Y') }}. Trạng thái hiện tại là
                            {{ ucfirst($movie->status->value ?? $movie->status) }}.</p>

                        <!-- Danh sách suất chiếu -->
                        <h4 class="badge bg-info text-light fs-14 py-1 px-2 mt-4">Danh sách suất chiếu</h4>

                        <!-- Form lọc suất chiếu mới -->
                        <form class="row g-2 align-items-end mb-3 p-3 rounded bg-light border" method="GET"
                            action="{{ route('admin.movies.show', ['id' => $movie->id]) }}">
                            <div class="col-md-4">
                                <label class="form-label mb-1 fw-medium text-dark" for="showtime_query"><i
                                        class="bx bx-search me-1"></i> Tìm kiếm</label>
                                <input type="search" name="showtime_query" id="showtime_query"
                                    class="form-control form-control-sm rounded-2" placeholder="Phòng, thời gian..."
                                    autocomplete="off" value="{{ request('showtime_query') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1 fw-medium text-dark" for="filter_room_id"><i
                                        class="bx bx-movie me-1"></i> Phòng chiếu</label>
                                <select name="room_id" id="filter_room_id"
                                    class="form-select form-select-sm rounded-2 select2-filter-room">
                                    <option value="">Tất cả phòng</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}"
                                            {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm w-100"><i
                                        class="bx bx-search-alt-2"></i></button>
                                <a href="{{ route('admin.movies.show', ['id' => $movie->id]) }}"
                                    class="btn btn-outline-secondary btn-sm w-100" title="Làm mới"><i
                                        class="bx bx-refresh"></i></a>
                            </div>
                        </form>
                        <!-- Select2 cho bộ lọc phòng chiếu trên form lọc -->
                        <script>
                            $(document).ready(function() {
                                $('.select2-filter-room').select2({
                                    placeholder: "Chọn phòng chiếu",
                                    allowClear: true,
                                    theme: 'bootstrap4',
                                    width: 'resolve',
                                    minimumResultsForSearch: 5
                                });
                            });
                        </script>

                        <!-- Form tạo suất chiếu tự động -->
                        <form action="{{ route('admin.showtimes.storeAuto') }}" method="POST"
                            class="p-3 rounded shadow-sm bg-white mb-3" id="createShowtimeForm"
                            style="border: 1px solid #e3e6ea;">
                            <div class="row g-3 align-items-end">
                                @csrf
                                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                <div class="col-md-5">
                                    <label for="roomSelect" class="form-label fw-medium text-dark mb-1"><i
                                            class="bx bx-movie fs-18 me-1"></i> Phòng chiếu</label>
                                    <select name="room_ids[]" class="form-control form-control-sm rounded-2" multiple
                                        required id="roomSelect">
                                        <option value="" disabled selected hidden>Chọn phòng chiếu...</option>
                                        @foreach ($rooms as $room)
                                            <option value="{{ $room->id }}">&#127910; {{ $room->name }}</option>
                                        @endforeach
                                    </select>
                                    <style>
                                        /* Select2 custom cho dropdown phòng chiếu */
                                        .select2-container--default .select2-selection--multiple {
                                            min-height: 44px;
                                            border: 1.5px solid #b6c2d1;
                                            border-radius: 0.5rem;
                                            background: #f8fafc;
                                            box-shadow: none;
                                            padding: 6px 10px;
                                            font-size: 1rem;
                                            transition: border-color .2s, box-shadow .2s;
                                        }

                                        .select2-container--default .select2-selection--multiple:focus {
                                            border-color: #0d6efd;
                                            box-shadow: 0 0 0 2px #0d6efd33;
                                        }

                                        .select2-container--default .select2-selection--multiple .select2-selection__choice {
                                            background-color: #eaf4ff;
                                            color: #0d6efd;
                                            border: 1px solid #b6c2d1;
                                            border-radius: 0.35rem;
                                            padding: 2px 10px 2px 8px;
                                            margin: 2px 3px;
                                            font-size: 0.95rem;
                                            font-weight: 500;
                                            display: flex;
                                            align-items: center;
                                            gap: 4px;
                                        }

                                        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                                            color: #dc3545;
                                            font-weight: bold;
                                            margin-right: 4px;
                                            cursor: pointer;
                                            font-size: 1.1em;
                                        }

                                        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
                                            display: flex;
                                            flex-wrap: wrap;
                                            gap: 6px;
                                        }

                                        .select2-container--default .select2-results__option {
                                            padding: 10px 20px;
                                            font-size: 1.08rem;
                                            border-radius: 0.35rem;
                                            transition: background .15s;
                                            display: flex;
                                            align-items: center;
                                            gap: 8px;
                                        }

                                        .select2-container--default .select2-results__option--highlighted {
                                            background: #eaf4ff;
                                            color: #0d6efd;
                                        }

                                        .select2-container--default .select2-results__option[aria-selected=true] {
                                            background: #0d6efd;
                                            color: #fff;
                                        }

                                        .form-label[for="roomSelect"] {
                                            color: #0d6efd;
                                            font-weight: 700;
                                            letter-spacing: 0.5px;
                                            font-size: 1.08rem;
                                            margin-bottom: 6px;
                                        }

                                        #roomSelect {
                                            border: none !important;
                                            box-shadow: none !important;
                                            background: transparent !important;
                                            font-size: 1rem;
                                        }

                                        /* Dropdown phòng chiếu đẹp hơn */
                                        #roomSelect {
                                            background: #f8fafc;
                                            border: 1.5px solid #b6c2d1;
                                            border-radius: 0.5rem;
                                            font-size: 1rem;
                                            min-height: 38px;
                                            transition: border-color .2s, box-shadow .2s;
                                        }

                                        #roomSelect:focus {
                                            border-color: #0d6efd;
                                            box-shadow: 0 0 0 2px #0d6efd33;
                                        }

                                        #roomSelect option {
                                            padding: 8px 16px;
                                            font-size: 1.08rem;
                                            display: flex;
                                            align-items: center;
                                            gap: 8px;
                                        }

                                        .form-label[for="roomSelect"] {
                                            color: #0d6efd;
                                            font-weight: 600;
                                            letter-spacing: 0.5px;
                                        }
                                    </style>
                                    @error('room_ids')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="date" class="form-label fw-medium text-dark mb-1"><i
                                            class="bx bx-calendar fs-18 me-1"></i> Ngày chiếu</label>
                                    <input type="date" name="date" id="date"
                                        class="form-control form-control-sm rounded-2"
                                        value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}"
                                        required>
                                    @error('date')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button"
                                        class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-2 rounded-2 shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#confirmShowtimeModal"
                                        style="transition: box-shadow .2s;">
                                        <i class="bx bx-plus fs-18"></i> <span class="fw-medium">Tạo suất chiếu</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Modal xác nhận tạo suất chiếu -->
                        <div class="modal fade" id="confirmShowtimeModal" tabindex="-1"
                            aria-labelledby="confirmShowtimeModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmShowtimeModalLabel">Xác nhận tạo suất chiếu
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Bạn có muốn tạo các suất chiếu sau cho phim "<strong
                                                id="modalMovieName"></strong>" vào ngày <strong id="modalDate"></strong>?
                                        </p>
                                        <p><strong>Phòng chiếu:</strong> <span id="modalRooms"></span></p>
                                        <p><strong>Khung giờ:</strong></p>
                                        <ul id="modalSlots"></ul>
                                        <p><strong>Tổng cộng:</strong> <span id="modalTotal"></span> suất chiếu mỗi phòng.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="button" class="btn btn-primary" id="confirmCreateShowtime">Xác
                                            nhận</button>
                                    </div>
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
                                        <th>Hành động</th>
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
                                                    $statusValue = is_object($showtime->status)
                                                        ? $showtime->status->value
                                                        : $showtime->status;
                                                @endphp
                                                <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                    {{ ucfirst($statusValue) }}
                                                </span>
                                            </td>
                                            <td>{{ $showtime->created_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.showtimes.edit', $showtime->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-edit fs-16"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Không có suất chiếu nào.</td>
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

    <!-- Thêm CSS và JS cho Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.6.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />

    <!-- CSS tùy chỉnh cho Select2 và Modal -->
    <style>
        /* Select2 multi-select với giao diện gọn hơn */
        .select2-container--default .select2-selection--multiple {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 4px 8px;
            background-color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 0.2rem;
            padding: 2px 6px;
            margin: 2px 3px;
            font-size: 0.85rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 4px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .select2-container {
            width: 100% !important;
            /* để theo chiều ngang container */
        }
    </style>

    <script>
        // Khởi tạo Select2 cho select phòng với icon và style đẹp hơn
        $('#roomSelect').select2({
            placeholder: "Chọn phòng (gõ để tìm)",
            closeOnSelect: false,
            allowClear: true,
            theme: 'bootstrap4',
            width: 'resolve',
            templateResult: function(data) {
                if (!data.id) return data.text;
                return $('<span><i class="bx bx-movie me-2 text-primary"></i>' + data.text + '</span>');
            },
            templateSelection: function(data) {
                if (!data.id) return data.text;
                return $('<span><i class="bx bx-check me-1 text-success"></i>' + data.text + '</span>');
            }
        });

        // Hàm tính toán các khung giờ suất chiếu dự kiến
        function getShowtimeSlots(date, durationMinutes) {
            const startOfDay = new Date(date);
            startOfDay.setHours(8, 0, 0, 0); // Bắt đầu từ 8:00 AM
            const endOfDay = new Date(date);
            endOfDay.setHours(22, 0, 0, 0); // Kết thúc lúc 10:00 PM
            const totalDuration = durationMinutes + 30; // Thời lượng phim + 30 phút đệm
            const slots = [];

            let currentTime = new Date(startOfDay);
            while (currentTime.getTime() + durationMinutes * 60 * 1000 <= endOfDay.getTime()) {
                const startTime = new Date(currentTime);
                const endTime = new Date(currentTime.getTime() + durationMinutes * 60 * 1000);
                slots.push(
                    `${startTime.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })} - ${endTime.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })}`
                    );
                currentTime.setMinutes(currentTime.getMinutes() + totalDuration);
            }
            return slots;
        }

        // Xử lý sự kiện mở modal xác nhận
        document.querySelector('[data-bs-target="#confirmShowtimeModal"]').addEventListener('click', function(e) {
            const form = document.getElementById('createShowtimeForm');
            const roomSelect = form.querySelector('select[name="room_ids[]"]');
            const selectedRooms = Array.from(roomSelect.selectedOptions).map(option => option.text);
            const dateInput = form.querySelector('input[name="date"]').value;
            const durationMinutes = {{ $movie->duration_minutes }};
            const movieName = "{{ addslashes($movie->name) }}";

            if (selectedRooms.length === 0) {
                // Hiển thị thông báo lỗi bằng modal
                const errorModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('errorModal'));
                document.getElementById('errorMessage').textContent = 'Vui lòng chọn ít nhất một phòng chiếu.';
                errorModal.show();
                // Ngăn không cho modal xác nhận hiện ra
                e.stopPropagation();
                e.preventDefault();
                return false;
            }

            const slots = getShowtimeSlots(dateInput, durationMinutes);
            if (slots.length === 0) {
                const errorModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('errorModal'));
                document.getElementById('errorMessage').textContent =
                    'Không thể tạo suất chiếu cho ngày này vì không có khung giờ hợp lệ.';
                errorModal.show();
                e.stopPropagation();
                e.preventDefault();
                return false;
            }

            // Điền thông tin vào modal xác nhận
            document.getElementById('modalMovieName').textContent = movieName;
            document.getElementById('modalDate').textContent = new Date(dateInput).toLocaleDateString('vi-VN');
            document.getElementById('modalRooms').textContent = selectedRooms.join(', ');
            const slotList = document.getElementById('modalSlots');
            slotList.innerHTML = '';
            slots.forEach(slot => {
                const li = document.createElement('li');
                li.textContent = slot;
                slotList.appendChild(li);
            });
            document.getElementById('modalTotal').textContent = slots.length;
            // Không cần gọi confirmModal.show() vì đã có data-bs-toggle="modal"
        });

        // Xử lý xác nhận tạo suất chiếu
        document.getElementById('confirmCreateShowtime').addEventListener('click', function() {
            document.getElementById('createShowtimeForm').submit();
        });
    </script>

    <!-- Modal thông báo lỗi -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Lỗi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
