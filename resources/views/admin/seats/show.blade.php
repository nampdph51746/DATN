@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="assets/images/seat-icon.png" alt="Seat Icon" class="img-fluid bg-light rounded">
                    <div class="mt-3">
                        <h4>Chi tiết ghế {{ $seat->row_char . $seat->seat_number }}</h4>
                        <p class="text-muted">Thông tin chi tiết về ghế trong phòng {{ $seat->room->name }}.</p>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <!-- <div class="col-lg-6">
                            <a href="{{ route('admin.seats.edit', $seat->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="bx bx-edit fs-18"></i> Sửa
                            </a>
                        </div> -->
                        <div class="col-lg-6">
                            <a href="{{ route('admin.seats.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
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
                        <span class="fs-24 text-dark fw-medium">Ghế {{ $seat->row_char . $seat->seat_number }}</span>
                    </p>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Phòng chiếu: <span class="text-muted">{{ $seat->room->name }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Loại ghế: <span class="text-muted">{{ $seat->seatType->name }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Hàng ghế: <span class="text-muted">{{ $seat->row_char }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Số ghế: <span class="text-muted">{{ $seat->seat_number }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Trạng thái: 
                                <span class="badge" style="background-color: {{ $seat->status == 'available' ? '#28a745' : ($seat->status == 'booked' ? '#ffc107' : ($seat->status == 'sold' ? '#dc3545' : '#6c757d')) }}; color: #fff;">
                                    {{ $seat->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian tạo: <span class="text-muted">{{ $seat->created_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>
                    <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                    <p class="text-muted">Ghế {{ $seat->row_char . $seat->seat_number }} thuộc phòng {{ $seat->room->name }} với loại ghế {{ $seat->seatType->name }}. Trạng thái hiện tại là {{ $seat->status }}.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection