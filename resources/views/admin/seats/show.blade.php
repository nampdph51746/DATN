@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-lg rounded-4 border-0">
                <div class="card-body text-center">
                    <img src="/assets/images/seat-icon.png" alt="Seat Icon" class="img-fluid bg-light rounded mb-3" style="width:80px;">
                    <h4 class="fw-bold mb-2">
                        <iconify-icon icon="solar:chair-broken" class="fs-24 text-primary me-2"></iconify-icon>
                        Ghế {{ $seat->row_char . $seat->seat_number }}
                    </h4>
                    <p class="text-muted mb-0">Phòng: <span class="fw-medium">{{ $seat->room->name }}</span></p>
                    <p class="text-muted mb-0">Loại ghế: 
                        <span class="badge px-3 py-2 rounded-pill" style="background: {{ $seat->seatType->color_code }}; color: #fff;">
                            <iconify-icon icon="solar:armchair-broken" class="fs-16 me-1"></iconify-icon>
                            {{ $seat->seatType->name }}
                        </span>
                    </p>
                    <p class="text-muted mb-0">Hàng ghế: 
                        <span class="fw-medium">
                            <iconify-icon icon="solar:rows-broken" class="fs-16 me-1"></iconify-icon>
                            {{ $seat->row_char }}
                        </span>
                    </p>
                    <p class="text-muted mb-0">Số ghế: 
                        <span class="fw-medium">
                            <iconify-icon icon="solar:hashtag-broken" class="fs-16 me-1"></iconify-icon>
                            {{ $seat->seat_number }}
                        </span>
                    </p>
                    <p class="text-muted mb-0 mt-2">Trạng thái: 
                        @php
                            $statusColors = [
                                'available' => ['bg' => '#28a745', 'icon' => 'solar:check-circle-broken'],
                                'reserved' => ['bg' => '#6c757d', 'icon' => 'solar:clock-broken'],
                                'booked' => ['bg' => '#ffc107', 'icon' => 'solar:calendar-broken'],
                                'sold' => ['bg' => '#dc3545', 'icon' => 'solar:close-circle-broken'],
                            ];
                            $statusValue = is_object($seat->status) ? $seat->status->value : $seat->status;
                            $status = $statusColors[$statusValue] ?? ['bg' => '#6c757d', 'icon' => 'solar:info-circle-broken'];
                        @endphp
                        <span class="badge px-3 py-2 rounded-pill d-inline-flex align-items-center" style="background: {{ $status['bg'] }}; color: #fff;">
                            <iconify-icon icon="{{ $status['icon'] }}" class="fs-16 me-1"></iconify-icon>
                            {{ ucfirst($statusValue) }}
                        </span>
                    </p>
                    <p class="text-muted mb-0 mt-2">Tạo lúc: 
                        <span class="fw-medium">
                            <iconify-icon icon="solar:calendar-broken" class="fs-16 me-1"></iconify-icon>
                            {{ $seat->created_at->format('d/m/Y H:i') }}
                        </span>
                    </p>
                </div>
                <div class="card-footer border-top bg-light">
                    <a href="{{ route('admin.seats.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
                        <iconify-icon icon="solar:arrow-left-broken" class="fs-18"></iconify-icon> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-4 border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <iconify-icon icon="solar:info-circle-broken" class="fs-20 text-info me-2"></iconify-icon>
                        Thông tin chi tiết
                    </h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center">
                            <iconify-icon icon="solar:cinema-broken" class="fs-18 text-primary me-2"></iconify-icon>
                            <span class="fw-medium">Phòng chiếu:</span>
                            <span class="ms-auto text-muted">{{ $seat->room->name }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <iconify-icon icon="solar:armchair-broken" class="fs-18 text-success me-2"></iconify-icon>
                            <span class="fw-medium">Loại ghế:</span>
                            <span class="ms-auto text-muted">{{ $seat->seatType->name }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <iconify-icon icon="solar:rows-broken" class="fs-18 text-warning me-2"></iconify-icon>
                            <span class="fw-medium">Hàng ghế:</span>
                            <span class="ms-auto text-muted">{{ $seat->row_char }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <iconify-icon icon="solar:hashtag-broken" class="fs-18 text-info me-2"></iconify-icon>
                            <span class="fw-medium">Số ghế:</span>
                            <span class="ms-auto text-muted">{{ $seat->seat_number }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <iconify-icon icon="{{ $status['icon'] }}" class="fs-18 me-2" style="color: {{ $status['bg'] }}"></iconify-icon>
                            <span class="fw-medium">Trạng thái:</span>
                            <span class="ms-auto badge px-3 py-2 rounded-pill" style="background: {{ $status['bg'] }}; color: #fff;">
                                {{ ucfirst($statusValue) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <iconify-icon icon="solar:calendar-broken" class="fs-18 text-secondary me-2"></iconify-icon>
                            <span class="fw-medium">Thời gian tạo:</span>
                            <span class="ms-auto text-muted">{{ $seat->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <h6 class="fw-bold text-dark mb-2">
                            <iconify-icon icon="solar:note-broken" class="fs-18 text-warning me-2"></iconify-icon>
                            Ghi chú
                        </h6>
                        <p class="text-muted">
                            Ghế <span class="fw-medium">{{ $seat->row_char . $seat->seat_number }}</span> thuộc phòng <span class="fw-medium">{{ $seat->room->name }}</span> với loại ghế <span class="fw-medium">{{ $seat->seatType->name }}</span>. Trạng thái hiện tại là <span class="fw-medium">{{ ucfirst($statusValue) }}</span>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection