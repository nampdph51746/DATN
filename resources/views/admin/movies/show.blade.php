@extends('layouts.admin.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl py-4">
    @include('admin.partials.notifications')

                                    <!-- Genres block in modal removed to fix Blade syntax error -->
                            <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('assets/images/movie-placeholder.png')) }}" 
                                 alt="Movie Poster" 
                                 class="img-fluid rounded-3 shadow-sm" 
                                 style="max-height: 400px; object-fit: cover; transition: transform 0.3s ease;">
                            <div class="position-absolute top-0 end-0 m-2">
                                @php
                                    $statusColors = [
                                        'showing' => '#28a745',
                                        'upcoming' => '#ffc107', 
                                        'ended' => '#dc3545',
                                    ];
                                @endphp
                                <span class="badge rounded-pill px-3 py-2 fs-6 fw-semibold" 
                                      style="background-color: {{ $statusColors[$movie->status->value ?? $movie->status] ?? '#6c757d' }}; color: #fff;">
                                    {{ ucfirst($movie->status->value ?? $movie->status) }}
                                </span>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">{{ $movie->name }}</h4>
                        <p class="text-muted mb-0 fs-6">Thông tin chi tiết về phim {{ $movie->name }}</p>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('admin.movies.edit', $movie->id) }}" 
                               class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100 rounded-3 py-2 fw-medium">
                                <i class="bx bx-edit fs-18"></i> Chỉnh sửaenter justify-content-center gap-2 w-100 rounded-3 py-2 fw-medium">
                            </a><i class="bx bx-edit fs-18"></i> Chỉnh sửa
                        </div>a>
                        <div class="col-6">
                            <a href="{{ route('admin.movies.index') }}" 
                               class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100 rounded-3 py-2 fw-medium">
                                <i class="bx bx-arrow-back fs-18"></i> Quay lạiems-center justify-content-center gap-2 w-100 rounded-3 py-2 fw-medium">
                            </a><i class="bx bx-arrow-back fs-18"></i> Quay lại
                        </div>a>
                    </div>div>
                </div>div>
            </div>div>
        </div>div>
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">er-0 h-100">
                    <!-- Header Section -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="badge bg-info bg-gradient text-white fs-6 py-2 px-3 rounded-pill fw-semibold">
                            <i class="bx bx-info-circle me-1"></i> Chi tiết phim-2 px-3 rounded-pill fw-semibold">
                        </div> class="bx bx-info-circle me-1"></i> Chi tiết phim
                        <h3 class="text-dark fw-bold mb-0">{{ $movie->name }}</h3>
                    </div>3 class="text-dark fw-bold mb-0">{{ $movie->name }}</h3>
                    </div>
                    <!-- Movie Details Grid -->
                    <div class="row g-4">id -->
                        <!-- Basic Info -->
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-user-circle text-primary fs-20"></i>
                                    <span class="fw-semibold text-dark">Đạo diễn</span>>
                                </div>pan class="fw-semibold text-dark">Đạo diễn</span>
                                <p class="text-muted mb-0 ps-4">{{ $movie->director?->name ?? 'N/A' }}</p>
                            </div> class="text-muted mb-0 ps-4">{{ $movie->director?->name ?? 'N/A' }}</p>
                        </div>div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-time text-success fs-20"></i>>
                                    <span class="fw-semibold text-dark">Thời lượng</span>
                                </div>pan class="fw-semibold text-dark">Thời lượng</span>
                                <p class="text-muted mb-0 ps-4">{{ $movie->duration_minutes }} phút</p>
                            </div> class="text-muted mb-0 ps-4">{{ $movie->duration_minutes }} phút</p>
                        </div>div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-calendar text-warning fs-20"></i>
                                    <span class="fw-semibold text-dark">Ngày phát hành</span>
                                </div>pan class="fw-semibold text-dark">Ngày phát hành</span>
                                <p class="text-muted mb-0 ps-4">{{ $movie->release_date }}</p>
                            </div> class="text-muted mb-0 ps-4">{{ $movie->release_date }}</p>
                        </div>div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-calendar-x text-danger fs-20"></i>
                                    <span class="fw-semibold text-dark">Ngày kết thúc</span>
                                </div>pan class="fw-semibold text-dark">Ngày kết thúc</span>
                                <p class="text-muted mb-0 ps-4">{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</p>
                            </div> class="text-muted mb-0 ps-4">{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</p>
                        </div>div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-globe text-info fs-20"></i>2">
                                    <span class="fw-semibold text-dark">Quốc gia</span>
                                </div>pan class="fw-semibold text-dark">Quốc gia</span>
                                <p class="text-muted mb-0 ps-4">{{ $movie->country?->name ?? 'N/A' }}</p>
                            </div> class="text-muted mb-0 ps-4">{{ $movie->country?->name ?? 'N/A' }}</p>
                        </div>div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-message-alt-detail text-secondary fs-20"></i>
                                    <span class="fw-semibold text-dark">Ngôn ngữ</span>s-20"></i>
                                </div>pan class="fw-semibold text-dark">Ngôn ngữ</span>
                                <p class="text-muted mb-0 ps-4">{{ $movie->language ?? 'N/A' }}</p>
                            </div> class="text-muted mb-0 ps-4">{{ $movie->language ?? 'N/A' }}</p>
                        </div>div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-shield text-primary fs-20"></i>
                                    <span class="fw-semibold text-dark">Giới hạn tuổi</span>
                                </div>pan class="fw-semibold text-dark">Giới hạn tuổi</span>
                                <p class="text-muted mb-0 ps-4">{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</p>
                            </div> class="text-muted mb-0 ps-4">{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</p>
                        </div>div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-star text-warning fs-20"></i>>
                                    <span class="fw-semibold text-dark">Điểm đánh giá</span>
                                </div>pan class="fw-semibold text-dark">Điểm đánh giá</span>
                                <p class="text-muted mb-0 ps-4">{{ $movie->average_rating ?? 'N/A' }}</p>
                            </div> class="text-muted mb-0 ps-4">{{ $movie->average_rating ?? 'N/A' }}</p>
                        </div>div>
                        </div>
                        <!-- Extended Info -->
                        <div class="col-12">->
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-group text-success fs-20"></i>
                                    <span class="fw-semibold text-dark">Diễn viên</span>
                                </div>pan class="fw-semibold text-dark">Diễn viên</span>
                                <p class="text-muted mb-0 ps-4">
                                    @if($movie->actors->isNotEmpty())
                                        {{ $movie->actors->pluck('name')->join(', ') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>p>
                        </div>div>
                        </div>
                        <div class="col-12">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-category text-info fs-20"></i>
                                    <span class="fw-semibold text-dark">Thể loại</span>
                                </div>pan class="fw-semibold text-dark">Thể loại</span>
                                <div class="ps-4">
                                    @if ($movie->genres->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($movie->genres as $genre)
                                                <span class="badge bg-info bg-gradient text-white rounded-pill px-3 py-2">{{ $genre->name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">N/A</p>
                                    @endif
                                </div>ndif
                            </div>div>
                        </div>div>
                        </div>
                        <!-- Quick Actions -->
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-play-circle text-danger fs-20"></i>
                                    <span class="fw-semibold text-dark">Trailer</span>>
                                </div>pan class="fw-semibold text-dark">Trailer</span>
                                <div class="ps-4">
                                    @if ($movie->trailer_url)
                                        <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill">
                                            <i class="bx bx-play me-1"></i> Xem trailerank" class="btn btn-outline-danger btn-sm rounded-pill">
                                        </a><i class="bx bx-play me-1"></i> Xem trailer
                                    @else/a>
                                        <span class="text-muted">N/A</span>
                                    @endifpan class="text-muted">N/A</span>
                                </div>ndif
                            </div>div>
                        </div>div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">rder border-light">
                                    <i class="bx bx-image text-primary fs-20"></i>
                                    <span class="fw-semibold text-dark">Poster</span>
                                </div>pan class="fw-semibold text-dark">Poster</span>
                                <div class="ps-4">
                                    @if ($movie->image_path)
                                        <a href="{{ Storage::url($movie->image_path) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="bx bx-image me-1"></i> Xem ảnh gốc target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                        </a><i class="bx bx-image me-1"></i> Xem ảnh gốc
                                    @else/a>
                                        <span class="text-muted">N/A</span>
                                    @endifpan class="text-muted">N/A</span>
                                </div>ndif
                            </div>div>
                        </div>div>
                    </div>div>
                    </div>
                    <!-- Description Section -->
                    <div class="mt-5">ection -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="badge bg-success bg-gradient text-white fs-6 py-2 px-3 rounded-pill fw-semibold">
                                <i class="bx bx-detail me-1"></i> Mô tảxt-white fs-6 py-2 px-3 rounded-pill fw-semibold">
                            </div> class="bx bx-detail me-1"></i> Mô tả
                        </div>div>
                        <div class="p-4 bg-light-subtle rounded-3 border border-light">
                            <p class="text-muted mb-0 lh-lg">{{ $movie->description ?? 'Không có mô tả.' }}</p>
                        </div> class="text-muted mb-0 lh-lg">{{ $movie->description ?? 'Không có mô tả.' }}</p>
                    </div>div>
                    </div>
                    <!-- Timeline Info -->
                    <div class="mt-4"> -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 p-3 bg-light-subtle rounded-3 border border-light">
                                    <i class="bx bx-time-five text-success fs-20"></i>ht-subtle rounded-3 border border-light">
                                    <div>ass="bx bx-time-five text-success fs-20"></i>
                                        <small class="text-muted d-block">Thời gian tạo</small>
                                        <span class="fw-medium">{{ $movie->created_at ? $movie->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>pan class="fw-medium">{{ $movie->created_at ? $movie->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</span>
                                </div>div>
                            </div>div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 p-3 bg-light-subtle rounded-3 border border-light">
                                    <i class="bx bx-edit-alt text-warning fs-20"></i>ght-subtle rounded-3 border border-light">
                                    <div>ass="bx bx-edit-alt text-warning fs-20"></i>
                                        <small class="text-muted d-block">Cập nhật lần cuối</small>
                                        <span class="fw-medium">{{ $movie->updated_at ? $movie->updated_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>pan class="fw-medium">{{ $movie->updated_at ? $movie->updated_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</span>
                                </div>div>
                            </div>div>
                        </div>div>
                    </div>div>
                    </div>
                    <!-- Danh sách suất chiếu -->
                    <div class="mt-5">t chiếu -->
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="badge bg-warning bg-gradient text-white fs-6 py-2 px-3 rounded-pill fw-semibold">
                                <i class="bx bx-movie me-1"></i> Danh sách suất chiếupy-2 px-3 rounded-pill fw-semibold">
                            </div> class="bx bx-movie me-1"></i> Danh sách suất chiếu
                        </div>div>
                        </div>
                        <!-- Form lọc suất chiếu -->
                        <div class="row g-3 mb-4">->
                            <div class="col-md-8">
                                <form class="position-relative" method="GET" action="{{ route('admin.movies.show', ['id' => $movie->id]) }}">
                                    <div class="input-group">e" method="GET" action="{{ route('admin.movies.show', ['id' => $movie->id]) }}">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bx bx-search text-muted"></i>er-end-0">
                                        </span>class="bx bx-search text-muted"></i>
                                        <input type="search" 
                                               name="showtime_query" 
                                               class="form-control border-start-0 ps-0" 
                                               placeholder="Tìm kiếm suất chiếu (phòng, thời gian)..." 
                                               value="{{ request('showtime_query') }}"> thời gian)..." 
                                        <input type="date" quest('showtime_query') }}">
                                               name="start_date" 
                                               class="form-control ms-2" 
                                               value="{{ request('start_date') }}" 
                                               title="Chọn ngày bắt đầu">ate') }}" 
                                    </div>     title="Chọn ngày bắt đầu">
                                    @error('start_date')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                    @enderrorl class="text-danger mt-1">{{ $message }}</small>
                                </form>derror
                            </div>form>
                            <div class="col-md-4">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary dropdown-toggle w-100 rounded-3" 
                                            type="button" -outline-primary dropdown-toggle w-100 rounded-3" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">wn" 
                                        <i class="bx bx-filter-alt me-2"></i> Lọc suất chiếu
                                    </button>ass="bx bx-filter-alt me-2"></i> Lọc suất chiếu
                                    <div class="dropdown-menu dropdown-menu-end w-100 p-3 shadow border-0 rounded-3">
                                        <!-- Lọc theo trạng thái -->wn-menu-end w-100 p-3 shadow border-0 rounded-3">
                                        <h6 class="dropdown-header fw-semibold text-primary">
                                            <i class="bx bx-check-circle me-1"></i> Trạng thái
                                        </h6>i class="bx bx-check-circle me-1"></i> Trạng thái
                                        <a href="{{ route('admin.movies.show', array_merge([$movie->id], array_filter(['showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" 
                                           class="dropdown-item rounded-2 {{ !request('showtime_status') || request('showtime_status') == 'all' ? 'active' : '' }}">, 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" 
                                            Tất cảdropdown-item rounded-2 {{ !request('showtime_status') || request('showtime_status') == 'all' ? 'active' : '' }}">
                                        </a>Tất cả
                                        @foreach (['scheduled', 'ongoing', 'completed', 'cancelled', 'postponed'] as $status)
                                            <a href="{{ route('admin.movies.show', array_merge([$movie->id], array_filter(['showtime_status' => $status, 'showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" 
                                               class="dropdown-item rounded-2 {{ request('showtime_status') == $status ? 'active' : '' }}">
                                                {{ ucfirst($status) }}
                                            </a>
                                        @endforeach
                                        <!-- Lọc theo phòng chiếu -->
                                        <div class="dropdown-divider my-2"></div>
                                        <h6 class="dropdown-header fw-semibold text-success">
                                            <i class="bx bx-movie me-1"></i> Phòng chiếuess">
                                        </h6>i class="bx bx-movie me-1"></i> Phòng chiếu
                                        <a href="{{ route('admin.movies.show', array_merge([$movie->id], array_filter(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" 
                                           class="dropdown-item rounded-2 {{ !request('room_id') ? 'active' : '' }}">(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" 
                                            Tất cảdropdown-item rounded-2 {{ !request('room_id') ? 'active' : '' }}">
                                        </a>Tất cả
                                        @foreach ($rooms as $room)
                                            <a href="{{ route('admin.movies.show', array_merge([$movie->id], array_filter(['room_id' => $room->id, 'showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" 
                                               class="dropdown-item rounded-2 {{ request('room_id') == $room->id ? 'active' : '' }}">=> $room->id, 'showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" 
                                                {{ $room->name }}em rounded-2 {{ request('room_id') == $room->id ? 'active' : '' }}">
                                            </a>{{ $room->name }}
                                        @endforeach
                                    </div>ndforeach
                                </div>div>
                            </div>div>
                        </div>div>
                        </div>
                        <!-- Form tạo suất chiếu tự động -->
                        <div class="card border-0 bg-gradient-light shadow-sm mb-4">
                            <div class="card-header bg-primary bg-gradient text-white border-0 rounded-top-3">
                                <h6 class="mb-0 fw-semibold">y bg-gradient text-white border-0 rounded-top-3">
                                    <i class="bx bx-plus-circle me-2"></i> Tạo suất chiếu tự động
                                </h6>i class="bx bx-plus-circle me-2"></i> Tạo suất chiếu tự động
                            </div>h6>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.showtimes.storeAuto') }}" method="POST" id="createShowtimeForm">
                                    @csrfion="{{ route('admin.showtimes.storeAuto') }}" method="POST" id="createShowtimeForm">
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    <div class="row g-4 align-items-end">
                                        <div class="col-md-4">items-end">
                                            <label for="roomSelect" class="form-label fw-semibold text-primary">
                                                <i class="bx bx-movie me-1"></i> Phòng chiếuibold text-primary">
                                            </label>lass="bx bx-movie me-1"></i> Phòng chiếu
                                            <select name="room_ids[]" class="form-control rounded-3" multiple required id="roomSelect">
                                                <option value="" disabled selected hidden>Chọn phòng chiếu...</option> id="roomSelect">
                                                @foreach ($rooms as $room)selected hidden>Chọn phòng chiếu...</option>
                                                    <option value="{{ $room->id }}">🎬 {{ $room->name }}</option>
                                                @endforeach value="{{ $room->id }}">🎬 {{ $room->name }}</option>
                                            </select>oreach
                                            @error('room_ids')
                                                <small class="text-danger mt-1">{{ $message }}</small>
                                            @enderrorl class="text-danger mt-1">{{ $message }}</small>
                                        </div>nderror
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date" class="form-label fw-semibold text-success">
                                                <i class="bx bx-calendar me-1"></i> Ngày chiếuxt-success">
                                            </label>lass="bx bx-calendar me-1"></i> Ngày chiếu
                                            <input type="date" 
                                                   name="date" 
                                                   id="date" " 
                                                   class="form-control rounded-3" 
                                                   value="{{ now()->format('Y-m-d') }}" 
                                                   min="{{ now()->format('Y-m-d') }}" " 
                                                   required>ow()->format('Y-m-d') }}" 
                                            @error('date')d>
                                                <small class="text-danger mt-1">{{ $message }}</small>
                                            @enderrorl class="text-danger mt-1">{{ $message }}</small>
                                        </div>nderror
                                        </div>
                                        <div class="col-md-3">
                                            <label for="max_showtimes" class="form-label fw-semibold text-warning">
                                                <i class="bx bx-time me-1"></i> Số suấtl fw-semibold text-warning">
                                            </label>lass="bx bx-time me-1"></i> Số suất
                                            <select name="max_showtimes" id="max_showtimes" class="form-control rounded-3">
                                                <option value="auto">🎯 Tự động (tối đa)</option>="form-control rounded-3">
                                                <option value="1">1️⃣ 1 suất</option>đa)</option>
                                                <option value="2">2️⃣ 2 suất</option>
                                                <option value="3">3️⃣ 3 suất</option>
                                                <option value="4">4️⃣ 4 suất</option>
                                                <option value="5">5️⃣ 5 suất</option>
                                            </select>on value="5">5️⃣ 5 suất</option>
                                        </div>select>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex gap-2">
                                                <button type="button" 
                                                        class="btn btn-primary btn-lg flex-fill rounded-3 shadow-sm" 
                                                        data-bs-toggle="modal" btn-lg flex-fill rounded-3 shadow-sm" 
                                                        data-bs-target="#confirmShowtimeModal">
                                                    <i class="bx bx-plus fs-18"></i>timeModal">
                                                </button>ass="bx bx-plus fs-18"></i>
                                                <button type="button" 
                                                        class="btn btn-warning rounded-3" 
                                                        onclick="debugSlots()" rounded-3" 
                                                        title="Debug slots">)" 
                                                    <i class="bx bx-bug fs-16"></i>
                                                </button>ass="bx bx-bug fs-16"></i>
                                            </div>button>
                                        </div>div>
                                    </div>div>
                                </form>iv>
                            </div>form>
                        </div>div>
                            @error('room_ids')
                                <small class="text-danger small">{{ $message }}</small>
                            @enderrorl class="text-danger small">{{ $message }}</small>
                            <!-- <div class="col-md-4">
                                <label for="date" class="form-label fw-medium text-dark mb-1"><i class="bx bx-calendar fs-18 me-1"></i> Ngày chiếu</label>
                                <input type="date" name="date" id="date" class="form-control form-control-sm rounded-2" value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}" required>
                                @error('date')ate" name="date" id="date" class="form-control form-control-sm rounded-2" value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}" required>
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror class="text-danger small">{{ $message }}</span>
                            </div>nderror
                            <div class="col-md-2">
                                <label for="max_showtimes" class="form-label fw-medium text-dark mb-1"><i class="bx bx-time fs-18 me-1"></i> Số suất</label>
                                <select name="max_showtimes" id="max_showtimes" class="form-control form-control-sm rounded-2">18 me-1"></i> Số suất</label>
                                    <option value="auto">Tự động (tối đa)</option>ass="form-control form-control-sm rounded-2">
                                    <option value="1">1 suất</option> đa)</option>
                                    <option value="2">2 suất</option>
                                    <option value="3">3 suất</option>
                                    <option value="4">4 suất</option>
                                    <option value="5">5 suất</option>
                                </select>on value="5">5 suất</option>
                            </div>select>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-2 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmShowtimeModal" style="transition: box-shadow .2s;">
                                    <i class="bx bx-plus fs-18"></i> <span class="fw-medium">Tạo suất chiếu</span>r justify-content-center gap-2 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmShowtimeModal" style="transition: box-shadow .2s;">
                                </button>ass="bx bx-plus fs-18"></i> <span class="fw-medium">Tạo suất chiếu</span>
                            </div> -->on>
                        </div>div> -->
                    </form>iv>
                    </form>
                    <!-- Modal xác nhận tạo suất chiếu -->
                    <div class="modal fade" id="confirmShowtimeModal" tabindex="-1" aria-labelledby="confirmShowtimeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">timeModal" tabindex="-1" aria-labelledby="confirmShowtimeModalLabel" aria-hidden="true">
                            <div class="modal-content">lg">
                                <div class="modal-header bg-primary bg-gradient text-white">
                                    <h5 class="modal-title fw-semibold" id="confirmShowtimeModalLabel">
                                        <i class="bx bx-check-circle me-2"></i> Xác nhận tạo suất chiếu
                                    </h5>i class="bx bx-check-circle me-2"></i> Xác nhận tạo suất chiếu
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>utton type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="modal-body p-4">
                                    <div class="alert alert-info border-0 bg-light-info">
                                        <div class="d-flex align-items-center gap-2">fo">
                                            <i class="bx bx-info-circle text-info fs-20"></i>
                                            <p class="mb-0">Bạn có muốn tạo các suất chiếu sau cho phim "<strong class="text-primary" id="modalMovieName"></strong>" vào ngày <strong class="text-success" id="modalDate"></strong>?</p>
                                        </div> class="mb-0">Bạn có muốn tạo các suất chiếu sau cho phim "<strong class="text-primary" id="modalMovieName"></strong>" vào ngày <strong class="text-success" id="modalDate"></strong>?</p>
                                    </div>div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body p-3">ght">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="bx bx-movie me-1"></i> Phòng chiếu
                                                    </h6>i class="bx bx-movie me-1"></i> Phòng chiếu
                                                    <p class="mb-0 fw-medium" id="modalRooms"></p>
                                                </div> class="mb-0 fw-medium" id="modalRooms"></p>
                                            </div>div>
                                        </div>div>
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body p-3">ght">
                                                    <h6 class="text-warning mb-2">
                                                        <i class="bx bx-cog me-1"></i> Chế độ tạo
                                                    </h6>i class="bx bx-cog me-1"></i> Chế độ tạo
                                                    <p class="mb-0 fw-medium" id="modalMode"></p>
                                                </div> class="mb-0 fw-medium" id="modalMode"></p>
                                            </div>div>
                                        </div>div>
                                    </div>div>
                                    <div class="ps-4">
                                        @if ($movie->genres->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($movie->genres as $genre)
                                                    <span class="badge bg-info bg-gradient text-white rounded-pill px-3 py-2">{{ $genre->name }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">N/A</p>
                                        @endif
                                    </div>
                                    <div class="mt-4 text-center">
                                        <div class="badge bg-success bg-gradient text-white fs-6 py-2 px-4 rounded-pill">
                                            <i class="bx bx-check me-1"></i> Tổng cộng: <strong id="modalTotal"></strong> suất chiếu mỗi phòng
                                        </div> class="bx bx-check me-1"></i> Tổng cộng: <strong id="modalTotal"></strong> suất chiếu mỗi phòng
                                    </div>div>
                                    </div>
                                    <div class="alert alert-warning border-0 bg-light-warning mt-4">
                                        <div class="d-flex gap-2">g border-0 bg-light-warning mt-4">
                                            <i class="bx bx-info-circle text-warning fs-20 mt-1"></i>
                                            <div>ass="bx bx-info-circle text-warning fs-20 mt-1"></i>
                                                <h6 class="text-warning mb-1">Lưu ý quan trọng</h6>
                                                <small class="text-muted">Hệ thống sẽ kiểm tra xung đột với các suất chiếu hiện có và chỉ tạo những suất không bị trùng lịch.</small>
                                            </div>mall class="text-muted">Hệ thống sẽ kiểm tra xung đột với các suất chiếu hiện có và chỉ tạo những suất không bị trùng lịch.</small>
                                        </div>div>
                                    </div>div>
                                </div>div>
                                <div class="modal-footer border-0 p-4">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                        <i class="bx bx-x me-1"></i> Hủy bỏtline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                    </button>ass="bx bx-x me-1"></i> Hủy bỏ
                                    <button type="button" class="btn btn-primary rounded-pill px-4" id="confirmCreateShowtime">
                                        <i class="bx bx-check me-1"></i> Xác nhận tạoded-pill px-4" id="confirmCreateShowtime">
                                    </button>ass="bx bx-check me-1"></i> Xác nhận tạo
                                </div>button>
                            </div>div>
                        </div>div>
                    </div>div>
                    <!-- Bảng suất chiếu -->
                    <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">w-sm">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">ver align-middle mb-0">
                                            <tr>lass="bg-light">
                                                <th class="border-0 fw-semibold text-dark py-3 ps-4">ID</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Phòng chiếu</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Thời gian bắt đầu</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Thời gian kết thúc</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Giá vé (VNĐ)</th>c</th>
                                                <th class="border-0 fw-semibold text-dark py-3">Trạng thái</th>h>
                                                <th class="border-0 fw-semibold text-dark py-3">Thời gian tạo</th>
                                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Hành động</th>
                                            </tr>th class="border-0 fw-semibold text-dark py-3 pe-4">Hành động</th>
                                        </thead>>
                                        <tbody>>
                                            @forelse ($showtimes as $showtime)
                                                <tr class="showtime-row border-bottom" 
                                                    data-date="{{ $showtime->start_time->format('Y-m-d') }}"
                                                    data-start-time="{{ $showtime->start_time->toISOString() }}"
                                                    data-end-time="{{ $showtime->end_time->toISOString() }}">}}"
                                                    <td class="ps-4 fw-medium text-primary">#{{ $showtime->id }}</td>
                                                    <td>class="ps-4 fw-medium text-primary">#{{ $showtime->id }}</td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bx bx-movie text-primary fs-18"></i>
                                                            <span class="fw-medium">{{ $showtime->room?->name ?? 'N/A' }}</span>
                                                        </div>pan class="fw-medium">{{ $showtime->room?->name ?? 'N/A' }}</span>
                                                    </td>/div>
                                                    <td>>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bx bx-time text-success fs-16"></i>
                                                            <span>{{ $showtime->start_time->format('d/m/Y H:i') }}</span>
                                                        </div>pan>{{ $showtime->start_time->format('d/m/Y H:i') }}</span>
                                                    </td>/div>
                                                    <td>>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bx bx-time-five text-warning fs-16"></i>
                                                            <span>{{ $showtime->end_time->format('d/m/Y H:i') }}</span>
                                                        </div>pan>{{ $showtime->end_time->format('d/m/Y H:i') }}</span>
                                                    </td>/div>
                                                    <td>>
                                                        <span class="fw-semibold text-success">{{ number_format($showtime->base_price, 0, ',', '.') }}</span>
                                                    </td>span class="fw-semibold text-success">{{ number_format($showtime->base_price, 0, ',', '.') }}</span>
                                                    <td>>
                                                        @php
                                                            $statusColors = [
                                                                'scheduled' => 'bg-primary',
                                                                'ongoing' => 'bg-success',
                                                                'completed' => 'bg-secondary',
                                                                'cancelled' => 'bg-danger',
                                                                'postponed' => 'bg-warning',
                                                            ];
                                                            $statusValue = is_object($showtime->status) ? $showtime->status->value : $showtime->status;
                                                        @endphpatusValue = is_object($showtime->status) ? $showtime->status->value : $showtime->status;
                                                        <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }} rounded-pill px-3 py-2">
                                                            {{ ucfirst($statusValue) }}lors[$statusValue] ?? 'bg-secondary' }} rounded-pill px-3 py-2">
                                                        </span>ucfirst($statusValue) }}
                                                    </td>/span>
                                                    <td>>
                                                        <small class="text-muted">{{ $showtime->created_at ? $showtime->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</small>
                                                    </td>small class="text-muted">{{ $showtime->created_at ? $showtime->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</small>
                                                    <td class="pe-4">
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" 
                                                               class="btn btn-sm btn-outline-primary rounded-pill" ) }}" 
                                                               title="Chỉnh sửa">btn-outline-primary rounded-pill" 
                                                                <i class="bx bx-edit fs-16"></i>
                                                            </a><i class="bx bx-edit fs-16"></i>
                                                            </a>
                                                            @if($statusValue === 'scheduled')
                                                                <button type="button" duled')
                                                                        class="btn btn-sm btn-outline-warning rounded-pill" 
                                                                        onclick="postponeShowtime({{ $showtime->id }})"ill" 
                                                                        title="Hoãn suất chiếu">e({{ $showtime->id }})"
                                                                    <i class="bx bx-time-five fs-16"></i>
                                                                </button>ass="bx bx-time-five fs-16"></i>
                                                                </button>
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger rounded-pill" 
                                                                        onclick="cancelShowtime({{ $showtime->id }})"pill" 
                                                                        title="Hủy suất chiếu">({{ $showtime->id }})"
                                                                    <i class="bx bx-x fs-16"></i>
                                                                </button>ass="bx bx-x fs-16"></i>
                                                            @endifbutton>
                                                            @endif
                                                            @if($statusValue === 'postponed')
                                                                <button type="button" poned')
                                                                        class="btn btn-sm btn-outline-success rounded-pill" 
                                                                        onclick="reactivateShowtime({{ $showtime->id }})"l" 
                                                                        title="Kích hoạt lại">wtime({{ $showtime->id }})"
                                                                    <i class="bx bx-check fs-16"></i>
                                                                </button>ass="bx bx-check fs-16"></i>
                                                            @endifbutton>
                                                        </div>ndif
                                                    </td>/div>
                                                </tr>/td>
                                            @emptytr>
                                                <tr>
                                                    <td colspan="8" class="text-center py-5">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="bx bx-movie text-muted fs-48 mb-3"></i>
                                                            <h6 class="text-muted">Không có suất chiếu nào</h6>
                                                            <p class="text-muted small mb-0">Hãy tạo suất chiếu mới cho phim này</p>
                                                        </div> class="text-muted small mb-0">Hãy tạo suất chiếu mới cho phim này</p>
                                                    </td>/div>
                                                </tr>/td>
                                            @endforelse
                                        </tbody>forelse
                                    </table>ody>
                                </div>table>
                            </div>div>
                        </div>div>
                        </div>
                        <!-- Pagination -->
                        @if($showtimes->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $showtimes->appends(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'room_id' => request('room_id'), 'start_date' => request('start_date')])->links('pagination::bootstrap-5') }}
                            </div> $showtimes->appends(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'room_id' => request('room_id'), 'start_date' => request('start_date')])->links('pagination::bootstrap-5') }}
                        @endifdiv>
                </div>  @endif
            </div>div>
        </div>div>
    </div>div>
</div>
@endsection