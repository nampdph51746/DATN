@extends('layouts.admin.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl">
    @include('admin.partials.notifications')

    <!-- Header Section -->
    <div class="card border-0 shadow-sm mb-4" style="background: #ff6633;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div style="color: #333333;">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="bg-white bg-opacity-20 rounded-circle p-2">
                            <i class="bi bi-camera-reels-fill fs-24" style="color: #333333; font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1" style="color: #333333;">Chi tiết phim</h4>
                            <h6 class="fw-semibold mb-0" style="color: #555555;">{{ $movie->name }}</h6>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.movies.edit', $movie) }}" class="btn btn-light btn-lg rounded-pill shadow-sm" style="color: #333333;">
                        <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-dark btn-lg rounded-pill" style="border-color: #333333; color: #333333;">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Movie Info -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <!-- Movie Poster -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            @php
                                $posterUrl = null;
                                if ($movie->image_path) {
                                    $posterUrl = Storage::url($movie->image_path);
                                } elseif ($movie->poster_url) {
                                    $posterUrl = $movie->poster_url;
                                } else {
                                    $posterUrl = asset('client_assets/assets/images/movie-placeholder.png');
                                }
                            @endphp
                            <div class="poster-frame position-relative">
                                <img src="{{ $posterUrl }}" 
                                     alt="{{ $movie->name }}" 
                                     class="img-fluid rounded-4 shadow-lg movie-poster-detail" 
                                     style="max-height: 400px; width: 100%; object-fit: cover; transition: all 0.3s ease;">
                                
                                <!-- Status Badge -->
                                <div class="position-absolute top-0 end-0 m-3">
                                    @php
                                        $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                        if ($movie->end_date && $movie->end_date < now()) {
                                            $statusValue = 'ended';
                                        }
                                        
                                        $statusConfig = [
                                            'showing' => ['class' => 'bg-success', 'icon' => 'bx-play-circle', 'label' => 'Đang chiếu'],
                                            'upcoming' => ['class' => 'bg-warning', 'icon' => 'bx-time-five', 'label' => 'Sắp chiếu'],
                                            'ended' => ['class' => 'bg-danger', 'icon' => 'bx-stop-circle', 'label' => 'Kết thúc'],
                                        ];
                                        $config = $statusConfig[$statusValue] ?? ['class' => 'bg-secondary', 'icon' => 'bx-help-circle', 'label' => 'Không xác định'];
                                    @endphp
                                    <span class="badge {{ $config['class'] }} px-3 py-2 rounded-pill shadow">
                                        <i class="bi bi-play-circle me-1"></i>{{ $config['label'] }}
                                    </span>
                                </div>

                                <!-- Play Button Overlay -->
                                @if ($movie->trailer_url)
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <a href="{{ $movie->trailer_url }}" target="_blank" 
                                           class="btn btn-danger btn-lg rounded-circle shadow-lg play-btn" 
                                           style="width: 60px; height: 60px; opacity: 0.9;">
                                            <i class="bi bi-play-fill fs-24"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Movie Title -->
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark mb-3">{{ $movie->name }}</h4>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                <i class="bi bi-globe me-1"></i>{{ $movie->language ?? 'N/A' }}
                            </span>
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                <i class="bi bi-flag me-1"></i>{{ $movie->country?->name ?? 'N/A' }}
                            </span>
                            @if($movie->ageLimit)
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                    <i class="bi bi-person me-1"></i>{{ $movie->ageLimit->name ?? $movie->ageLimit->label }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded-4 border">
                                <i class="bi bi-clock text-muted fs-28 mb-2"></i>
                                <div class="fw-bold text-dark fs-18">{{ $movie->duration_minutes }}</div>
                                <small class="text-muted fw-semibold">phút</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded-4 border">
                                <i class="bi bi-star text-muted fs-28 mb-2"></i>
                                <div class="fw-bold text-dark fs-18">{{ $movie->average_rating ? number_format($movie->average_rating, 1) : 'N/A' }}</div>
                                <small class="text-muted fw-semibold">đánh giá</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded-4 border">
                                <i class="bi bi-camera-reels text-muted fs-28 mb-2"></i>
                                <div class="fw-bold text-dark fs-18">{{ $movie->showtimes->count() }}</div>
                                <small class="text-muted fw-semibold">suất chiếu</small>
                            </div>
                        </div>
                    </div>

                    <!-- Movie Genres -->
                    @if($movie->genres->isNotEmpty())
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-tags text-muted fs-20"></i>
                                <h6 class="fw-semibold mb-0 text-dark">Thể loại</h6>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($movie->genres as $genre)
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">
                                        <i class="bi bi-tag me-1"></i>{{ $genre->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="d-grid gap-2">
                        @if ($movie->trailer_url)
                            <a href="{{ $movie->trailer_url }}" target="_blank" 
                               class="btn btn-danger btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-play-circle me-2"></i>Xem Trailer
                            </a>
                        @endif
                        
                        <div class="row g-2">
                            <div class="col-12">
                                <a href="{{ route('admin.movies.edit', $movie) }}" 
                                   class="btn btn-outline-success btn-lg rounded-pill w-100">
                                    <i class="bx bx-edit me-1"></i>Chỉnh sửa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details & Showtimes -->
        <div class="col-xl-8 col-lg-7">
            <!-- Movie Details Card với Accordion -->
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                <i class="bx bx-info-circle text-warning fs-20"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-warning">Thông tin chi tiết</h5>
                        </div>
                        <button class="btn btn-outline-warning rounded-pill collapsed" type="button" id="accordionToggleBtn">
                            <i class="bx bx-chevron-down fs-18" id="accordionIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="movieDetailsAccordion">
                    <div class="card-body pt-3">
                        <!-- Thông tin cơ bản -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="info-card p-3 h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bx bx-user-circle text-primary fs-20"></i>
                                        <strong class="text-primary">Đạo diễn</strong>
                                    </div>
                                    <p class="mb-0 fs-6">{{ $movie->director?->name ?? 'Chưa cập nhật' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bx bx-calendar text-success fs-20"></i>
                                        <strong class="text-success">Ngày phát hành</strong>
                                    </div>
                                    <p class="mb-0 fs-6">{{ $movie->release_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bx bx-calendar-x text-danger fs-20"></i>
                                        <strong class="text-danger">Ngày kết thúc</strong>
                                    </div>
                                    <p class="mb-0 fs-6">{{ $movie->end_date?->format('d/m/Y') ?? 'Chưa xác định' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bx bx-time text-warning fs-20"></i>
                                        <strong class="text-warning">Cập nhật cuối</strong>
                                    </div>
                                    <p class="mb-0 fs-6">{{ $movie->updated_at ? $movie->updated_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Diễn viên -->
                        @if($movie->actors->isNotEmpty())
                            <div class="mb-4">
                                <div class="info-card p-3">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <i class="bx bx-group text-info fs-20"></i>
                                        <strong class="text-info">Diễn viên chính</strong>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($movie->actors as $actor)
                                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                                <i class="bx bx-user me-1"></i>{{ $actor->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Mô tả phim -->
                        @if($movie->description)
                            <div class="mb-0">
                                <div class="info-card p-3">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <i class="bx bx-detail text-secondary fs-20"></i>
                                        <strong class="text-secondary">Nội dung phim</strong>
                                    </div>
                                    <p class="mb-0 text-muted lh-lg">{{ $movie->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Showtimes Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                <i class="bx bx-movie text-warning fs-20"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-warning">Quản lý suất chiếu</h5>
                                <small class="text-muted">Tạo và quản lý lịch chiếu phim</small>
                            </div>
                        </div>
                        <div class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                            <i class="bx bx-time me-1"></i>Tổng: {{ $movie->showtimes->count() }} suất
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <!-- Form lọc suất chiếu -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <form class="position-relative" method="GET" action="{{ route('admin.movies.show', $movie) }}">
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-light border-0 rounded-start-3">
                                        <i class="bx bx-search text-primary"></i>
                                    </span>
                                    <input type="search" 
                                           name="showtime_query" 
                                           class="form-control border-0 ps-2" 
                                           placeholder="Tìm kiếm suất chiếu (phòng, thời gian)..." 
                                           value="{{ request('showtime_query') }}">
                                    <input type="date" 
                                           name="start_date" 
                                           class="form-control border-0 border-start" 
                                           value="{{ request('start_date') }}" 
                                           title="Chọn ngày bắt đầu">
                                    <button type="submit" class="btn btn-primary rounded-end-3">
                                        <i class="bx bx-search me-1"></i>Tìm
                                    </button>
                                </div>
                                @error('start_date')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle w-100 rounded-3 shadow-sm" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    <i class="bx bx-filter-alt me-2"></i>Bộ lọc nâng cao
                                </button>
                                <div class="dropdown-menu dropdown-menu-end w-100 p-3 shadow-lg border-0 rounded-4">
                                    <!-- Lọc theo trạng thái -->
                                    <h6 class="dropdown-header fw-semibold text-primary border-bottom pb-2">
                                        <i class="bx bx-check-circle me-1"></i>Trạng thái suất chiếu
                                    </h6>
                                    <a href="{{ route('admin.movies.show', $movie) }}?{{ http_build_query(array_filter(['showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')])) }}" 
                                       class="dropdown-item rounded-3 py-2 {{ !request('showtime_status') || request('showtime_status') == 'all' ? 'active bg-primary text-white' : '' }}">
                                        <i class="bx bx-list-ul me-2"></i>Tất cả
                                    </a>
                                    @foreach (['scheduled' => 'Đã lên lịch', 'ongoing' => 'Đang chiếu', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy', 'postponed' => 'Hoãn chiếu'] as $status => $label)
                                        <a href="{{ route('admin.movies.show', $movie) }}?{{ http_build_query(array_filter(['showtime_status' => $status, 'showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')])) }}" 
                                           class="dropdown-item rounded-3 py-2 {{ request('showtime_status') == $status ? 'active bg-primary text-white' : '' }}">
                                            <i class="bx bx-{{ $status == 'scheduled' ? 'calendar' : ($status == 'ongoing' ? 'play-circle' : ($status == 'completed' ? 'check-circle' : ($status == 'cancelled' ? 'x-circle' : 'time-five'))) }} me-2"></i>{{ $label }}
                                        </a>
                                    @endforeach

                                    <!-- Lọc theo phòng chiếu -->
                                    <div class="dropdown-divider my-3"></div>
                                    <h6 class="dropdown-header fw-semibold text-success border-bottom pb-2">
                                        <i class="bx bx-movie me-1"></i>Phòng chiếu
                                    </h6>
                                    <a href="{{ route('admin.movies.show', $movie) }}?{{ http_build_query(array_filter(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')])) }}" 
                                       class="dropdown-item rounded-3 py-2 {{ !request('room_id') ? 'active bg-success text-white' : '' }}">
                                        <i class="bx bx-list-ul me-2"></i>Tất cả phòng
                                    </a>
                                    @foreach ($rooms as $room)
                                        <a href="{{ route('admin.movies.show', $movie) }}?{{ http_build_query(array_filter(['room_id' => $room->id, 'showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')])) }}" 
                                           class="dropdown-item rounded-3 py-2 {{ request('room_id') == $room->id ? 'active bg-success text-white' : '' }}">
                                            <i class="bx bx-movie me-2"></i>{{ $room->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Form tạo suất chiếu tự động -->
                        <div class="card border-0 bg-gradient-light shadow-sm mb-4">
                            <div class="card-header text-white border-0 rounded-top-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bx bx-plus-circle me-2"></i> Tạo suất chiếu tự động
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.showtimes.storeAuto') }}" method="POST" id="createShowtimeForm">
                                    @csrf
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    
                                    <div class="row g-4 align-items-end">
                                        <div class="col-md-4">
                                            <label for="roomSelect" class="form-label fw-semibold text-primary">
                                                <i class="bx bx-movie me-1"></i> Phòng chiếu
                                            </label>
                                            <select name="room_ids[]" class="form-control rounded-3" multiple required id="roomSelect">
                                                <option value="" disabled selected hidden>Chọn phòng chiếu...</option>
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->id }}">🎬 {{ $room->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('room_ids')
                                                <small class="text-danger mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="date" class="form-label fw-semibold text-success">
                                                <i class="bx bx-calendar me-1"></i> Ngày chiếu
                                            </label>
                                            <input type="date" 
                                                   name="date" 
                                                   id="date" 
                                                   class="form-control rounded-3" 
                                                   value="{{ now()->format('Y-m-d') }}" 
                                                   min="{{ now()->format('Y-m-d') }}" 
                                                   required>
                                            @error('date')
                                                <small class="text-danger mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="max_showtimes" class="form-label fw-semibold text-warning">
                                                <i class="bx bx-time me-1"></i> Số suất
                                            </label>
                                            <select name="max_showtimes" id="max_showtimes" class="form-control rounded-3">
                                                <option value="auto">🎯 Tự động (tối đa)</option>
                                                <option value="1">1️⃣ 1 suất</option>
                                                <option value="2">2️⃣ 2 suất</option>
                                                <option value="3">3️⃣ 3 suất</option>
                                                <option value="4">4️⃣ 4 suất</option>
                                                <option value="5">5️⃣ 5 suất</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="d-flex gap-2">
                                                <button type="button" 
                                                        class="btn btn-primary btn-lg flex-fill rounded-3 shadow-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#confirmShowtimeModal">
                                                    <i class="bx bx-plus fs-18"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-warning rounded-3" 
                                                        onclick="debugSlots()" 
                                                        title="Debug slots">
                                                    <i class="bx bx-bug fs-16"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                            @error('room_ids')
                                <small class="text-danger small">{{ $message }}</small>
                            @enderror
                            <!-- <div class="col-md-4">
                                <label for="date" class="form-label fw-medium text-dark mb-1"><i class="bx bx-calendar fs-18 me-1"></i> Ngày chiếu</label>
                                <input type="date" name="date" id="date" class="form-control form-control-sm rounded-2" value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}" required>
                                @error('date')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="max_showtimes" class="form-label fw-medium text-dark mb-1"><i class="bx bx-time fs-18 me-1"></i> Số suất</label>
                                <select name="max_showtimes" id="max_showtimes" class="form-control form-control-sm rounded-2">
                                    <option value="auto">Tự động (tối đa)</option>
                                    <option value="1">1 suất</option>
                                    <option value="2">2 suất</option>
                                    <option value="3">3 suất</option>
                                    <option value="4">4 suất</option>
                                    <option value="5">5 suất</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-2 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmShowtimeModal" style="transition: box-shadow .2s;">
                                    <i class="bx bx-plus fs-18"></i> <span class="fw-medium">Tạo suất chiếu</span>
                                </button>
                            </div> -->
                        </div>
                    </form>

                    <!-- Modal xác nhận tạo suất chiếu -->
                    <div class="modal fade" id="confirmShowtimeModal" tabindex="-1" aria-labelledby="confirmShowtimeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary bg-gradient text-white">
                                    <h5 class="modal-title fw-semibold" id="confirmShowtimeModalLabel">
                                        <i class="bx bx-check-circle me-2"></i> Xác nhận tạo suất chiếu
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="alert alert-info border-0 bg-light-info">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bx bx-info-circle text-info fs-20"></i>
                                            <p class="mb-0">Bạn có muốn tạo các suất chiếu sau cho phim "<strong class="text-primary" id="modalMovieName"></strong>" vào ngày <strong class="text-success" id="modalDate"></strong>?</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body p-3">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="bx bx-movie me-1"></i> Phòng chiếu
                                                    </h6>
                                                    <p class="mb-0 fw-medium" id="modalRooms"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body p-3">
                                                    <h6 class="text-warning mb-2">
                                                        <i class="bx bx-cog me-1"></i> Chế độ tạo
                                                    </h6>
                                                    <p class="mb-0 fw-medium" id="modalMode"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <h6 class="text-success mb-3">
                                            <i class="bx bx-time me-1"></i> Khung giờ chiếu dự kiến
                                        </h6>
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <ul class="list-unstyled mb-0" id="modalSlots"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 text-center">
                                        <div class="badge bg-success bg-gradient text-white fs-6 py-2 px-4 rounded-pill">
                                            <i class="bx bx-check me-1"></i> Tổng cộng: <strong id="modalTotal"></strong> suất chiếu mỗi phòng
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-warning border-0 bg-light-warning mt-4">
                                        <div class="d-flex gap-2">
                                            <i class="bx bx-info-circle text-warning fs-20 mt-1"></i>
                                            <div>
                                                <h6 class="text-warning mb-1">Lưu ý quan trọng</h6>
                                                <small class="text-muted">Hệ thống sẽ kiểm tra xung đột với các suất chiếu hiện có và chỉ tạo những suất không bị trùng lịch.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                        <i class="bx bx-x me-1"></i> Hủy bỏ
                                    </button>
                                    <button type="button" class="btn btn-primary rounded-pill px-4" id="confirmCreateShowtime">
                                        <i class="bx bx-check me-1"></i> Xác nhận tạo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Bảng suất chiếu -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0 fw-semibold text-dark py-3 ps-4">ID</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Phòng chiếu</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Thời gian bắt đầu</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Thời gian kết thúc</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Giá vé (VNĐ)</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Trạng thái</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Thời gian tạo</th>
                                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($showtimes as $showtime)
                                                <tr class="showtime-row border-bottom" 
                                                    data-date="{{ $showtime->start_time->format('Y-m-d') }}"
                                                    data-start-time="{{ $showtime->start_time->toISOString() }}"
                                                    data-end-time="{{ $showtime->end_time->toISOString() }}">
                                                    <td class="ps-4 fw-medium text-primary">#{{ $showtime->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bx bx-movie text-primary fs-18"></i>
                                                            <span class="fw-medium">{{ $showtime->room?->name ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bx bx-time text-success fs-16"></i>
                                                            <span>{{ $showtime->start_time->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bx bx-time-five text-warning fs-16"></i>
                                                            <span>{{ $showtime->end_time->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold text-success">{{ number_format($showtime->base_price, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusColors = [
                                                                'scheduled' => 'bg-primary',
                                                                'ongoing' => 'bg-success',
                                                                'completed' => 'bg-secondary',
                                                                'cancelled' => 'bg-danger',
                                                                'postponed' => 'bg-warning',
                                                            ];
                                                            $statusValue = is_object($showtime->status) ? $showtime->status->value : $showtime->status;
                                                        @endphp
                                                        <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }} rounded-pill px-3 py-2">
                                                            {{ ucfirst($statusValue) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $showtime->created_at ? $showtime->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</small>
                                                    </td>
                                                    <td class="pe-4">
                                                        <div class="d-flex gap-1">
                                                            @php
                                                                $hasConfirmedTickets = $showtime->tickets->count() > 0;
                                                            @endphp
                                                            
                                                            @if(in_array($statusValue, ['ongoing', 'completed', 'cancelled']) || $hasConfirmedTickets)
                                                                @if($hasConfirmedTickets && !in_array($statusValue, ['ongoing', 'completed', 'cancelled']))
                                                                    <span class="btn btn-sm btn-outline-secondary rounded-pill disabled" 
                                                                          title="Không thể chỉnh sửa vì đã có {{ $showtime->tickets->count() }} vé được đặt và xác nhận">
                                                                        <i class="bx bx-edit fs-16"></i>
                                                                    </span>
                                                                @else
                                                                    <span class="btn btn-sm btn-outline-secondary rounded-pill disabled" 
                                                                          title="Không thể chỉnh sửa suất chiếu đã bắt đầu/hoàn thành/hủy">
                                                                        <i class="bx bx-edit fs-16"></i>
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" 
                                                                   class="btn btn-sm btn-outline-primary rounded-pill" 
                                                                   title="Chỉnh sửa">
                                                                    <i class="bx bx-edit fs-16"></i>
                                                                </a>
                                                            @endif
                                                            
                                                            @if($statusValue === 'scheduled')
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-warning rounded-pill" 
                                                                        onclick="postponeShowtime({{ $showtime->id }})"
                                                                        title="Hoãn suất chiếu">
                                                                    <i class="bx bx-time-five fs-16"></i>
                                                                </button>
                                                                
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger rounded-pill" 
                                                                        onclick="cancelShowtime({{ $showtime->id }})"
                                                                        title="Hủy suất chiếu">
                                                                    <i class="bx bx-x fs-16"></i>
                                                                </button>
                                                            @endif
                                                            
                                                            @if($statusValue === 'postponed')
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-success rounded-pill" 
                                                                        onclick="reactivateShowtime({{ $showtime->id }})"
                                                                        title="Kích hoạt lại">
                                                                    <i class="bx bx-check fs-16"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-5">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="bx bx-movie text-muted fs-48 mb-3"></i>
                                                            <h6 class="text-muted">Không có suất chiếu nào</h6>
                                                            <p class="text-muted small mb-0">Hãy tạo suất chiếu mới cho phim này</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        @if($showtimes->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $showtimes->appends(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'room_id' => request('room_id'), 'start_date' => request('start_date')])->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm CSS và JS cho Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Thêm SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSS tùy chỉnh -->
<style>
/* Accordion styling - FORCE EVERYTHING */
.collapse {
    /* Bỏ transition để tránh conflict với Bootstrap */
}

.collapse.show {
    display: block !important;
    height: auto !important;
    max-height: none !important;
    overflow: visible !important;
    opacity: 1 !important;
}

.collapse:not(.show) {
    display: none !important;
}

.collapsing {
    height: auto !important;
    transition: none !important;
    display: block !important;
}

/* Force show cho accordion content */
#movieDetailsAccordion.show {
    display: block !important;
    height: auto !important;
    max-height: none !important;
    overflow: visible !important;
    opacity: 1 !important;
    visibility: visible !important;
}

#movieDetailsAccordion.show .card-body {
    display: block !important;
    height: auto !important;
    opacity: 1 !important;
}

.btn[data-bs-toggle="collapse"] {
    transition: all 0.2s ease;
}

.btn[data-bs-toggle="collapse"] i {
    transition: transform 0.3s ease;
}

.btn[data-bs-toggle="collapse"]:not(.collapsed) i {
    transform: rotate(180deg);
}

.btn[data-bs-toggle="collapse"].collapsed i {
    transform: rotate(0deg);
}

.btn[data-bs-toggle="collapse"]:hover {
    background-color: #fff3cd;
    border-color: #ffc107;
}

/* Poster frame styling */
.poster-frame {
    overflow: hidden;
    border-radius: 1rem;
}

.poster-frame img {
    transition: all 0.4s ease;
}

.poster-frame:hover img {
    transform: scale(1.05);
}

.play-btn {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    background: rgba(220, 53, 69, 0.9) !important;
}

.play-btn:hover {
    transform: scale(1.1);
    background: rgba(220, 53, 69, 1) !important;
}

/* Info detail cards */
.info-detail-card {
    padding: 1.25rem;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 1rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.info-detail-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 0.9);
}

/* Enhanced button styling */
.btn {
    transition: all 0.3s ease;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}

/* Custom warning color for better contrast */
.text-warning {
    color: #e97e0f !important;
    font-weight: 600;
}

.bg-warning-subtle {
    background-color: #fef3c7 !important;
}

.bg-warning-subtle .text-warning {
    color: #92400e !important;
}

/* Warning elements styling */
.bg-warning.bg-opacity-10 {
    background-color: rgba(249, 115, 22, 0.1) !important;
}

i.text-warning {
    color: #f97316 !important;
}

h5.text-warning, h6.text-warning {
    color: #ea580c !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

/* Gradient button effects */
.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    border: none;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    border-color: transparent;
}

.btn-outline-warning:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-color: transparent;
    color: white;
}

.btn-outline-success:hover {
    background: linear-gradient(135deg, #55a3ff 0%, #003d82 100%);
    border-color: transparent;
}

/* Badge improvements */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}

.badge.rounded-pill {
    padding: 0.5rem 1rem;
}

/* Card enhancements */
.card {
    transition: all 0.3s ease;
    border: none;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

/* Input styling */
.form-control {
    border: 2px solid #f1f3f4;
    transition: all 0.3s ease;
    font-weight: 500;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: scale(1.02);
}

.input-group {
    border-radius: 1rem;
    overflow: hidden;
}

.input-group-text {
    background: #f8f9fa;
    border: 2px solid #f1f3f4;
    border-right: none;
}

/* Dropdown enhancements */
.dropdown-menu {
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
}

.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 0.75rem;
    margin: 2px 0;
    font-weight: 500;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    transform: translateX(5px);
    padding-left: 1.5rem;
}

.dropdown-item.active {
    background: linear-gradient(135deg, #667eea, #764ba2) !important;
}

/* Table styling */
.table > :not(caption) > * > * {
    padding: 1.25rem 1rem;
    border-bottom: 1px solid #f1f3f4;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    transform: translateX(3px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

/* Header gradient background */
.card-header {
    background: transparent;
}

/* Status badges with icons */
.badge .bx {
    font-size: 0.9em;
}

/* Button hover effects for warning */
.btn-warning:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
}

/* Quick stats styling */
.col-4 > div {
    transition: all 0.3s ease;
}

.col-4 > div:hover {
    transform: translateY(-3px) scale(1.02);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .container-xxl {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .row.g-4 {
        gap: 1.5rem !important;
    }
    
    .btn-lg {
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
    }
    
    .poster-frame img {
        max-height: 300px !important;
    }
}

/* Animation for page load */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

.card:nth-child(2) {
    animation-delay: 0.1s;
}

.card:nth-child(3) {
    animation-delay: 0.2s;
}

/* Select2 enhancements */
.select2-container--default .select2-selection--multiple {
    min-height: 50px;
    border: 2px solid #f1f3f4;
    border-radius: 1rem;
    background: linear-gradient(145deg, #f8f9fa, #ffffff);
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.select2-container--default .select2-selection--multiple:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: #ffffff;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    border: none;
    border-radius: 0.75rem;
    padding: 6px 15px;
    margin: 3px 5px;
    font-weight: 600;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.select2-dropdown {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(10px);
}

/* Modal improvements */
.modal-content {
    border: none;
    border-radius: 1.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
}

.modal-header {
    border-bottom: 2px solid #f1f3f4;
    border-radius: 1.5rem 1.5rem 0 0;
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.modal-footer {
    border-top: 2px solid #f1f3f4;
    border-radius: 0 0 1.5rem 1.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - Setting up accordion');
    
    const button = document.getElementById('accordionToggleBtn');
    const target = document.getElementById('movieDetailsAccordion');
    const icon = document.getElementById('accordionIcon');
    
    console.log('Elements check:', { button: !!button, target: !!target, icon: !!icon });
    
    if (button && target && icon) {
        // Debug initial state
        console.log('Initial target state:', {
            classes: target.className,
            display: getComputedStyle(target).display,
            height: getComputedStyle(target).height,
            visibility: getComputedStyle(target).visibility
        });
        
        // Manual toggle thay vì dựa vào Bootstrap
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Manual button clicked');
            
            const isCurrentlyHidden = target.style.display === 'none' || 
                                    getComputedStyle(target).display === 'none' ||
                                    !target.classList.contains('show');
            
            console.log('Currently hidden:', isCurrentlyHidden);
            
            if (isCurrentlyHidden) {
                // Show accordion - FORCE IT!
                console.log('Showing accordion manually');
                target.style.display = 'block !important';
                target.style.height = 'auto !important';
                target.style.maxHeight = 'none !important';
                target.style.overflow = 'visible !important';
                target.style.visibility = 'visible !important';
                target.style.opacity = '1 !important';
                target.classList.add('show');
                target.classList.remove('collapse', 'collapsing');
                
                // Remove any inline height that might be set
                target.style.removeProperty('height');
                target.style.height = 'auto';
                
                button.classList.remove('collapsed');
                button.setAttribute('aria-expanded', 'true');
                icon.style.transform = 'rotate(180deg)';
                
                // Force recalculate
                target.offsetHeight; // trigger reflow
                
                setTimeout(() => {
                    console.log('Accordion shown - new state:', {
                        classes: target.className,
                        display: getComputedStyle(target).display,
                        height: getComputedStyle(target).height,
                        scrollHeight: target.scrollHeight + 'px'
                    });
                }, 50);
            } else {
                // Hide accordion - FORCE CLOSE!
                console.log('Hiding accordion manually');
                target.style.display = 'none !important';
                target.style.height = '0px !important';
                target.style.maxHeight = '0px !important';
                target.style.overflow = 'hidden !important';
                target.style.opacity = '0 !important';
                target.classList.remove('show');
                target.classList.add('collapse');
                
                button.classList.add('collapsed');
                button.setAttribute('aria-expanded', 'false');
                icon.style.transform = 'rotate(0deg)';
                
                console.log('Accordion hidden');
            }
        });
        
        console.log('Manual accordion ready');
    } else {
        console.error('Accordion elements missing');
    }
});

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

// Hàm tính toán các khung giờ suất chiếu dự kiến với logic làm tròn
function getShowtimeSlots(date, durationMinutes, maxShowtimes = 'auto') {
    console.log('getShowtimeSlots được gọi với:', { date, durationMinutes, maxShowtimes });
    
    const selectedDate = new Date(date);
    const now = new Date();
    const endOfDay = new Date(selectedDate);
    endOfDay.setHours(23, 0, 0, 0); // Kết thúc lúc 11:00 PM
    
    let startOfDay;
    
    // Xác định thời gian bắt đầu tương tự như logic backend
    if (now.toDateString() === selectedDate.toDateString()) {
        // Nếu là ngày hiện tại, bắt đầu từ thời gian hiện tại + 30 phút
        startOfDay = new Date(now);
        
        // Làm tròn thời gian để hợp lý hơn (làm tròn lên 5 phút gần nhất)
        let minutes = startOfDay.getMinutes();
        let roundedMinutes = Math.ceil(minutes / 5) * 5;
        
        if (roundedMinutes >= 60) {
            startOfDay.setHours(startOfDay.getHours() + 1, 0, 0, 0);
        } else {
            startOfDay.setMinutes(roundedMinutes, 0, 0);
        }
        
        // Thêm 30 phút buffer
        startOfDay.setMinutes(startOfDay.getMinutes() + 30);
    } else {
        // Nếu là ngày khác, bắt đầu từ 8:00 AM
        startOfDay = new Date(selectedDate);
        startOfDay.setHours(8, 0, 0, 0);
    }    console.log('Start of day:', startOfDay);
    console.log('End of day:', endOfDay);
    
    // Lấy danh sách suất chiếu hiện có cho ngày được chọn
    const existingShowtimes = getExistingShowtimes(date);
    console.log('Existing showtimes:', existingShowtimes);
    
    // Tạo danh sách khoảng thời gian bận và sắp xếp theo thời gian
    const busyIntervals = existingShowtimes.map(showtime => ({
        start: new Date(showtime.start_time),
        end: new Date(new Date(showtime.end_time).getTime() + 30 * 60 * 1000) // thêm 30 phút buffer
    })).sort((a, b) => a.start - b.start);
    
    console.log('Busy intervals:', busyIntervals);
    
    const slots = [];
    let currentTime = new Date(startOfDay);
    let showtimeCount = 0;
    
    // Tìm slot trống từ startOfDay đến endOfDay (giống logic backend)
    while (currentTime.getTime() + durationMinutes * 60 * 1000 <= endOfDay.getTime()) {
        // Kiểm tra giới hạn số suất
        if (maxShowtimes !== 'auto' && showtimeCount >= parseInt(maxShowtimes)) {
            break;
        }
        
        const proposedStart = new Date(currentTime);
        const proposedEnd = new Date(currentTime.getTime() + durationMinutes * 60 * 1000);
        
        console.log(`Checking slot: ${proposedStart.toLocaleTimeString()} - ${proposedEnd.toLocaleTimeString()}`);
        
        // Kiểm tra conflict với các khoảng thời gian bận
        let conflictInterval = null;
        for (const busy of busyIntervals) {
            if (proposedStart < busy.end && proposedEnd > busy.start) {
                conflictInterval = busy;
                console.log('Conflict found with:', busy);
                break;
            }
        }
        
        if (conflictInterval) {
            // Nhảy đến sau khoảng thời gian bận và làm tròn
            currentTime = new Date(conflictInterval.end);
            
            // Làm tròn thời gian để hợp lý hơn (làm tròn lên 5 phút gần nhất)
            let minutes = currentTime.getMinutes();
            let roundedMinutes = Math.ceil(minutes / 5) * 5;
            
            if (roundedMinutes >= 60) {
                currentTime.setHours(currentTime.getHours() + 1, 0, 0, 0);
            } else {
                currentTime.setMinutes(roundedMinutes, 0, 0);
            }
            console.log(`Jumped to: ${currentTime.toLocaleTimeString()}`);
        } else {
            const startTimeStr = proposedStart.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
            const endTimeStr = proposedEnd.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
            const slotStr = `${startTimeStr} - ${endTimeStr}`;
            
            slots.push(slotStr);
            console.log('Added slot:', slotStr);
            
            // Thêm suất chiếu mới vào danh sách bận để tránh trùng lặp
            busyIntervals.push({
                start: new Date(proposedStart),
                end: new Date(proposedEnd.getTime() + 30 * 60 * 1000) // buffer 30 phút
            });
            
            // Sắp xếp lại danh sách theo thời gian
            busyIntervals.sort((a, b) => a.start - b.start);
            
            // Di chuyển đến thời gian tiếp theo và làm tròn
            currentTime = new Date(proposedEnd.getTime() + 30 * 60 * 1000);
            
            // Làm tròn thời gian bắt đầu tiếp theo để hợp lý hơn (làm tròn lên 5 phút gần nhất)
            let minutes = currentTime.getMinutes();
            let roundedMinutes = Math.ceil(minutes / 5) * 5;
            
            if (roundedMinutes >= 60) {
                currentTime.setHours(currentTime.getHours() + 1, 0, 0, 0);
            } else {
                currentTime.setMinutes(roundedMinutes, 0, 0);
            }
            
            showtimeCount++;
        }
    }
    
    console.log('Final slots:', slots);
    return slots;
}

// Hàm lấy suất chiếu hiện có cho ngày được chọn
function getExistingShowtimes(date) {
    const showtimes = [];
    console.log('Looking for showtimes on date:', date);
    
    // Lấy từ bảng hiện tại trên trang cho ngày được chọn
    const showtimeRows = document.querySelectorAll('.showtime-row');
    console.log('Found showtime rows:', showtimeRows.length);
    
    showtimeRows.forEach((row, index) => {
        const rowDate = row.getAttribute('data-date');
        const startTime = row.getAttribute('data-start-time');
        const endTime = row.getAttribute('data-end-time');
        
        console.log(`Row ${index}: date=${rowDate}, start=${startTime}, end=${endTime}`);
        
        if (rowDate === date && startTime && endTime) {
            showtimes.push({
                start_time: startTime,
                end_time: endTime
            });
            console.log(`Added showtime: ${startTime} to ${endTime}`);
        }
    });
    
    console.log('Final existing showtimes:', showtimes);
    return showtimes;
}

// Hàm debug để test logic slots
function debugSlots() {
    const dateInput = document.querySelector('input[name="date"]').value;
    const durationMinutes = {{ $movie->duration_minutes }};
    console.log('=== DEBUG SLOTS ===');
    console.log('Date:', dateInput);
    console.log('Duration:', durationMinutes);
    
    const slots = getShowtimeSlots(dateInput, durationMinutes, 'auto');
    console.log('Generated slots:', slots);
    
    alert('Check console for debug info. Generated ' + slots.length + ' slots: ' + JSON.stringify(slots));
}

// Xử lý sự kiện mở modal xác nhận
document.querySelector('[data-bs-target="#confirmShowtimeModal"]').addEventListener('click', function(e) {
    const form = document.getElementById('createShowtimeForm');
    const roomSelect = form.querySelector('select[name="room_ids[]"]');
    const selectedRooms = Array.from(roomSelect.selectedOptions).map(option => option.text);
    const dateInput = form.querySelector('input[name="date"]').value;
    const maxShowtimes = form.querySelector('select[name="max_showtimes"]').value;
    const durationMinutes = {{ $movie->duration_minutes }};
    const movieName = "{{ addslashes($movie->name) }}";

    if (selectedRooms.length === 0) {
        // Hiển thị thông báo lỗi bằng modal
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {});
        document.getElementById('errorMessage').textContent = 'Vui lòng chọn ít nhất một phòng chiếu.';
        errorModal.show();
        return;
    }

    const slots = getShowtimeSlots(dateInput, durationMinutes, maxShowtimes);
    if (slots.length === 0) {
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {});
        document.getElementById('errorMessage').textContent = 'Không thể tạo suất chiếu cho ngày này vì không có khung giờ hợp lệ.';
        errorModal.show();
        return;
    }

    // Điền thông tin vào modal xác nhận
    document.getElementById('modalMovieName').textContent = movieName;
    document.getElementById('modalDate').textContent = new Date(dateInput).toLocaleDateString('vi-VN');
    document.getElementById('modalRooms').textContent = selectedRooms.join(', ');
    document.getElementById('modalMode').textContent = maxShowtimes === 'auto' ? 'Tự động (tối đa)' : `Giới hạn ${maxShowtimes} suất`;
    const slotList = document.getElementById('modalSlots');
    slotList.innerHTML = '';
    slots.forEach((slot, index) => {
        const li = document.createElement('li');
        li.className = 'd-flex align-items-center gap-2 mb-2';
        li.innerHTML = `
            <span class="badge bg-primary rounded-pill">${index + 1}</span>
            <i class="bx bx-time text-success"></i>
            <span class="fw-medium">${slot}</span>
        `;
        slotList.appendChild(li);
    });
    document.getElementById('modalTotal').textContent = slots.length;

    // Hiển thị modal xác nhận
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmShowtimeModal'), {});
    confirmModal.show();
});

// Xử lý xác nhận tạo suất chiếu
document.getElementById('confirmCreateShowtime').addEventListener('click', function() {
    document.getElementById('createShowtimeForm').submit();
});

// Hàm hoãn suất chiếu
function postponeShowtime(showtimeId) {
    Swal.fire({
        title: 'Xác nhận hoãn suất chiếu',
        text: 'Suất chiếu sẽ được đánh dấu là "hoãn". Nếu có vé đã đặt, bạn cần thông báo cho khách hàng về việc này.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Đồng ý hoãn',
        cancelButtonText: 'Hủy bỏ'
    }).then((result) => {
        if (result.isConfirmed) {
            updateShowtimeStatus(showtimeId, 'postponed');
        }
    });
}

// Hàm hủy suất chiếu
function cancelShowtime(showtimeId) {
    Swal.fire({
        title: 'Xác nhận hủy suất chiếu',
        html: `
            <p>⚠️ <strong>Cảnh báo:</strong> Hành động này sẽ hủy hoàn toàn suất chiếu.</p>
            <p>• Nếu có vé đã đặt, hệ thống sẽ kiểm tra và yêu cầu xử lý hoàn tiền trước</p>
            <p>• Suất chiếu không thể khôi phục sau khi hủy</p>
        `,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Đồng ý hủy',
        cancelButtonText: 'Không hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            updateShowtimeStatus(showtimeId, 'cancelled');
        }
    });
}

// Hàm kích hoạt lại suất chiếu
function reactivateShowtime(showtimeId) {
    Swal.fire({
        title: 'Kích hoạt lại suất chiếu',
        text: 'Suất chiếu sẽ được chuyển về trạng thái "scheduled" và có thể bán vé trở lại.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Kích hoạt',
        cancelButtonText: 'Hủy bỏ'
    }).then((result) => {
        if (result.isConfirmed) {
            updateShowtimeStatus(showtimeId, 'scheduled');
        }
    });
}

// Hàm cập nhật trạng thái suất chiếu
function updateShowtimeStatus(showtimeId, status) {
    // Hiển thị loading
    Swal.fire({
        title: 'Đang xử lý...',
        text: 'Vui lòng chờ trong giây lát',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/admin/showtimes/${showtimeId}/update-status-manual`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success) {
            // Hiển thị thông báo thành công với thông tin chi tiết
            let iconType = 'success';
            let titleText = 'Cập nhật thành công!';
            
            if (status === 'cancelled') {
                iconType = 'warning';
                titleText = 'Đã hủy suất chiếu';
            } else if (status === 'postponed') {
                iconType = 'info';
                titleText = 'Đã hoãn suất chiếu';
            } else if (status === 'scheduled') {
                iconType = 'success';
                titleText = 'Đã kích hoạt lại';
            }
            
            Swal.fire({
                icon: iconType,
                title: titleText,
                text: data.message,
                confirmButtonText: 'Đã hiểu',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                // Reload trang để cập nhật trạng thái
                location.reload();
            });
        } else {
            // Hiển thị lỗi chi tiết
            Swal.fire({
                icon: 'error',
                title: 'Không thể cập nhật',
                text: data.message,
                confirmButtonText: 'Đã hiểu',
                confirmButtonColor: '#d33'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi kết nối',
            text: 'Có lỗi xảy ra khi kết nối với máy chủ. Vui lòng thử lại.',
            confirmButtonText: 'Đã hiểu',
            confirmButtonColor: '#d33'
        });
    });
}
</script>

<!-- Modal thông báo lỗi -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-semibold" id="errorModalLabel">
                    <i class="bx bx-error-circle me-2"></i> Thông báo lỗi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <i class="bx bx-error-circle text-danger fs-24"></i>
                    <p class="mb-0" id="errorMessage"></p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Đóng
                </button>
            </div>
        </div>
    </div>
</div>
@endsection