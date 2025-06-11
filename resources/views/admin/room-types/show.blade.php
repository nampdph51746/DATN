@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('assets/images/room-type-icon.png') }}" alt="Room Type Icon" class="img-fluid bg-light rounded">
                    <div class="mt-3">
                        <h4>Chi tiết loại phòng {{ $roomType->name }}</h4>
                        <p class="text-muted">Thông tin chi tiết về loại phòng chiếu {{ $roomType->name }}.</p>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <a href="{{ route('admin.room-types.edit', $roomType->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                <i class="bx bx-edit fs-18"></i> Sửa
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.room-types.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
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
                        <span class="fs-24 text-dark fw-medium">Loại phòng {{ $roomType->name }}</span>
                    </p>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Tên loại phòng: <span class="text-muted">{{ $roomType->name }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Số lượng phòng sử dụng: <span class="text-muted">{{ $roomCount }}</span></p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Mô tả: <span class="text-muted">{{ $roomType->description ?? 'Không có mô tả' }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Trạng thái: 
                                <span class="badge {{ $roomType->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($roomType->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian tạo: <span class="text-muted">{{ $roomType->created_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">Thời gian cập nhật: <span class="text-muted">{{ $roomType->updated_at->format('d/m/Y H:i') }}</span></p>
                        </div>
                    </div>
                    <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                    <p class="text-muted">Loại phòng {{ $roomType->name }} hiện đang được sử dụng bởi {{ $roomCount }} phòng chiếu. Trạng thái hiện tại là {{ $roomType->status }}.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection