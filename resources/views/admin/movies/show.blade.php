@extends('layouts.admin.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl py-4">
    @include('admin.partials.notifications')

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="position-relative d-inline-block mb-4">
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
                                <i class="bx bx-edit fs-18"></i> Chỉnh sửa
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.movies.index') }}" 
                               class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100 rounded-3 py-2 fw-medium">
                                <i class="bx bx-arrow-back fs-18"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <!-- Header Section -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="badge bg-info bg-gradient text-white fs-6 py-2 px-3 rounded-pill fw-semibold">
                            <i class="bx bx-info-circle me-1"></i> Chi tiết phim
                        </div>
                        <h3 class="text-dark fw-bold mb-0">{{ $movie->name }}</h3>
                    </div>

                    <!-- Movie Details Grid -->
                    <div class="row g-4">
                        <!-- Basic Info -->
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-user-circle text-primary fs-20"></i>
                                    <span class="fw-semibold text-dark">Đạo diễn</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">{{ $movie->director?->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-time text-success fs-20"></i>
                                    <span class="fw-semibold text-dark">Thời lượng</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">{{ $movie->duration_minutes }} phút</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-calendar text-warning fs-20"></i>
                                    <span class="fw-semibold text-dark">Ngày phát hành</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">{{ $movie->release_date->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-calendar-x text-danger fs-20"></i>
                                    <span class="fw-semibold text-dark">Ngày kết thúc</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">{{ $movie->end_date?->format('d/m/Y') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-globe text-info fs-20"></i>
                                    <span class="fw-semibold text-dark">Quốc gia</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">{{ $movie->country?->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-message-alt-detail text-secondary fs-20"></i>
                                    <span class="fw-semibold text-dark">Ngôn ngữ</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">{{ $movie->language ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-shield text-primary fs-20"></i>
                                    <span class="fw-semibold text-dark">Giới hạn tuổi</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">{{ $movie->ageLimit?->name ?? $movie->ageLimit?->label ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-star text-warning fs-20"></i>
                                    <span class="fw-semibold text-dark">Điểm đánh giá</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">{{ $movie->average_rating ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Extended Info -->
                        <div class="col-12">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-group text-success fs-20"></i>
                                    <span class="fw-semibold text-dark">Diễn viên</span>
                                </div>
                                <p class="text-muted mb-0 ps-4">
                                    @if($movie->actors->isNotEmpty())
                                        {{ $movie->actors->pluck('name')->join(', ') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-category text-info fs-20"></i>
                                    <span class="fw-semibold text-dark">Thể loại</span>
                                </div>
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
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-play-circle text-danger fs-20"></i>
                                    <span class="fw-semibold text-dark">Trailer</span>
                                </div>
                                <div class="ps-4">
                                    @if ($movie->trailer_url)
                                        <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill">
                                            <i class="bx bx-play me-1"></i> Xem trailer
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 bg-light-subtle border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bx bx-image text-primary fs-20"></i>
                                    <span class="fw-semibold text-dark">Poster</span>
                                </div>
                                <div class="ps-4">
                                    @if ($movie->image_path)
                                        <a href="{{ Storage::url($movie->image_path) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="bx bx-image me-1"></i> Xem ảnh gốc
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="mt-5">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="badge bg-success bg-gradient text-white fs-6 py-2 px-3 rounded-pill fw-semibold">
                                <i class="bx bx-detail me-1"></i> Mô tả
                            </div>
                        </div>
                        <div class="p-4 bg-light-subtle rounded-3 border border-light">
                            <p class="text-muted mb-0 lh-lg">{{ $movie->description ?? 'Không có mô tả.' }}</p>
                        </div>
                    </div>

                    <!-- Timeline Info -->
                    <div class="mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 p-3 bg-light-subtle rounded-3 border border-light">
                                    <i class="bx bx-time-five text-success fs-20"></i>
                                    <div>
                                        <small class="text-muted d-block">Thời gian tạo</small>
                                        <span class="fw-medium">{{ $movie->created_at ? $movie->created_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 p-3 bg-light-subtle rounded-3 border border-light">
                                    <i class="bx bx-edit-alt text-warning fs-20"></i>
                                    <div>
                                        <small class="text-muted d-block">Cập nhật lần cuối</small>
                                        <span class="fw-medium">{{ $movie->updated_at ? $movie->updated_at->tz('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách suất chiếu -->
                    <div class="mt-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="badge bg-warning bg-gradient text-white fs-6 py-2 px-3 rounded-pill fw-semibold">
                                <i class="bx bx-movie me-1"></i> Danh sách suất chiếu
                            </div>
                        </div>

                        <!-- Form lọc suất chiếu -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <form class="position-relative" method="GET" action="{{ route('admin.movies.show', $movie->id) }}">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bx bx-search text-muted"></i>
                                        </span>
                                        <input type="search" 
                                               name="showtime_query" 
                                               class="form-control border-start-0 ps-0" 
                                               placeholder="Tìm kiếm suất chiếu (phòng, thời gian)..." 
                                               value="{{ request('showtime_query') }}">
                                        <input type="date" 
                                               name="start_date" 
                                               class="form-control ms-2" 
                                               value="{{ request('start_date') }}" 
                                               title="Chọn ngày bắt đầu">
                                    </div>
                                    @error('start_date')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                    @enderror
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary dropdown-toggle w-100 rounded-3" 
                                            type="button" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                        <i class="bx bx-filter-alt me-2"></i> Lọc suất chiếu
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end w-100 p-3 shadow border-0 rounded-3">
                                        <!-- Lọc theo trạng thái -->
                                        <h6 class="dropdown-header fw-semibold text-primary">
                                            <i class="bx bx-check-circle me-1"></i> Trạng thái
                                        </h6>
                                        <a href="{{ route('admin.movies.show', array_merge([$movie->id], array_filter(['showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" 
                                           class="dropdown-item rounded-2 {{ !request('showtime_status') || request('showtime_status') == 'all' ? 'active' : '' }}">
                                            Tất cả
                                        </a>
                                        @foreach (['scheduled', 'ongoing', 'completed', 'cancelled', 'postponed'] as $status)
                                            <a href="{{ route('admin.movies.show', array_merge([$movie->id], array_filter(['showtime_status' => $status, 'showtime_query' => request('showtime_query'), 'room_id' => request('room_id'), 'start_date' => request('start_date')]))) }}" 
                                               class="dropdown-item rounded-2 {{ request('showtime_status') == $status ? 'active' : '' }}">
                                                {{ ucfirst($status) }}
                                            </a>
                                        @endforeach

                                        <!-- Lọc theo phòng chiếu -->
                                        <div class="dropdown-divider my-2"></div>
                                        <h6 class="dropdown-header fw-semibold text-success">
                                            <i class="bx bx-movie me-1"></i> Phòng chiếu
                                        </h6>
                                        <a href="{{ route('admin.movies.show', array_merge([$movie->id], array_filter(['showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" 
                                           class="dropdown-item rounded-2 {{ !request('room_id') ? 'active' : '' }}">
                                            Tất cả
                                        </a>
                                        @foreach ($rooms as $room)
                                            <a href="{{ route('admin.movies.show', array_merge([$movie->id], array_filter(['room_id' => $room->id, 'showtime_query' => request('showtime_query'), 'showtime_status' => request('showtime_status'), 'start_date' => request('start_date')]))) }}" 
                                               class="dropdown-item rounded-2 {{ request('room_id') == $room->id ? 'active' : '' }}">
                                                {{ $room->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form tạo suất chiếu tự động -->
                        <div class="card border-0 bg-gradient-light shadow-sm mb-4">
                            <div class="card-header bg-primary bg-gradient text-white border-0 rounded-top-3">
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
                                                            <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" 
                                                               class="btn btn-sm btn-outline-primary rounded-pill" 
                                                               title="Chỉnh sửa">
                                                                <i class="bx bx-edit fs-16"></i>
                                                            </a>
                                                            
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

<!-- Thêm CSS và JS cho Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- CSS tùy chỉnh -->
<style>
/* Hiệu ứng hover cho poster */
.card-body img:hover {
    transform: scale(1.02);
}

/* Info cards styling */
.info-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef !important;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    border-color: #0d6efd !important;
}

/* Select2 custom styling */
.select2-container--default .select2-selection--multiple {
    min-height: 44px;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    background: #f8f9fa;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.select2-container--default .select2-selection--multiple:focus-within {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    background: #fff;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    color: #fff;
    border: none;
    border-radius: 0.5rem;
    padding: 4px 12px;
    margin: 2px 4px;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    margin-right: 8px;
    font-weight: bold;
    transition: color 0.2s ease;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffc107;
}

.select2-dropdown {
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.select2-container--default .select2-results__option {
    padding: 12px 20px;
    transition: all 0.2s ease;
}

.select2-container--default .select2-results__option--highlighted {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    color: #fff;
}

/* Table styling improvements */
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

/* Button improvements */
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    border: none;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    border-color: #0d6efd;
}

/* Badge improvements */
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

/* Card improvements */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* Dropdown improvements */
.dropdown-menu {
    border: none;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 0.5rem;
    margin: 2px 0;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    transform: translateX(4px);
}

.dropdown-item.active {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
}

/* Modal improvements */
.modal-content {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
}

.modal-header {
    border-bottom: 2px solid #f1f3f4;
    border-radius: 1rem 1rem 0 0;
}

.modal-footer {
    border-top: 2px solid #f1f3f4;
}

/* Input group improvements */
.input-group-text {
    border: 2px solid #e9ecef;
    background: #f8f9fa;
}

.form-control {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
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
                const errorModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('errorModal'));
                document.getElementById('errorMessage').textContent = 'Vui lòng chọn ít nhất một phòng chiếu.';
                errorModal.show();
                // Ngăn không cho modal xác nhận hiện ra
                e.stopPropagation();
                e.preventDefault();
                return false;
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

        // Xử lý xác nhận tạo suất chiếu
        document.getElementById('confirmCreateShowtime').addEventListener('click', function() {
            document.getElementById('createShowtimeForm').submit();
        });
    </script>

// Xử lý xác nhận tạo suất chiếu
document.getElementById('confirmCreateShowtime').addEventListener('click', function() {
    document.getElementById('createShowtimeForm').submit();
});

// Hàm hoãn suất chiếu
function postponeShowtime(showtimeId) {
    if (confirm('Bạn có chắc chắn muốn hoãn suất chiếu này?')) {
        updateShowtimeStatus(showtimeId, 'postponed');
    }
}

// Hàm hủy suất chiếu
function cancelShowtime(showtimeId) {
    if (confirm('Bạn có chắc chắn muốn hủy suất chiếu này? Hành động này không thể hoàn tác.')) {
        updateShowtimeStatus(showtimeId, 'cancelled');
    }
}

// Hàm kích hoạt lại suất chiếu
function reactivateShowtime(showtimeId) {
    if (confirm('Bạn có chắc chắn muốn kích hoạt lại suất chiếu này?')) {
        updateShowtimeStatus(showtimeId, 'scheduled');
    }
}

// Hàm cập nhật trạng thái suất chiếu
function updateShowtimeStatus(showtimeId, status) {
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
        if (data.success) {
            // Reload trang để cập nhật trạng thái
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái suất chiếu.');
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
@endsection
