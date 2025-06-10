@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ $showtime->movie->poster ?? 'assets/images/default-movie-poster.png' }}" alt="Movie Poster" class="img-fluid bg-light rounded">
                    <div class="mt-3">
                        <h4>Suất chiếu #{{ $showtime->id }}</h4>
                        <p class="text-muted">Thông tin chi tiết về suất chiếu của phim {{ $showtime->movie->name ?? 'Không xác định' }}.</p>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="bx bx-edit fs-18"></i> Sửa
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
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
                    <h4 class="badge bg-info text-light fs-14 py-1 px-2">Chi tiết suất chiếu</h4>
                    <p class="mb-1">
                        <span class="fs-24 text-dark fw-medium">{{ $showtime->movie->name ?? 'Không xác định' }}</span>
                    </p>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Phòng chiếu: <span class="text-muted">{{ $showtime->room->name ?? 'Không xác định' }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Giá vé cơ bản: <span class="text-muted">{{ number_format($showtime->base_price, 2) }} VNĐ</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian bắt đầu: <span class="text-muted">{{ \Carbon\Carbon::parse($showtime->start_time)->format('d/m/Y H:i') }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian kết thúc: <span class="text-muted">{{ \Carbon\Carbon::parse($showtime->end_time)->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Trạng thái: 
                                <span class="badge" style="background-color: {{ $showtime->status->value == 'scheduled' ? '#28a745' : ($showtime->status->value == 'ongoing' ? '#007bff' : ($showtime->status->value == 'completed' ? '#6c757d' : '#dc3545')) }}; color: #fff;">
                                    {{ ucfirst($showtime->status->value) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Số vé đã đặt: <span class="text-muted">{{ $ticketCount }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian tạo: <span class="text-muted">{{ $showtime->created_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian cập nhật: <span class="text-muted">{{ $showtime->updated_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>
                    <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                    <p class="text-muted">Suất chiếu của phim {{ $showtime->movie->name ?? 'Không xác định' }} tại phòng {{ $showtime->room->name ?? 'Không xác định' }}. Trạng thái hiện tại là {{ ucfirst($showtime->status->value) }} với {{ $ticketCount }} vé đã được đặt.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

