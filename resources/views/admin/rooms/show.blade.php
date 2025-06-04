@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="badge bg-success text-light fs-14 py-1 px-2">Phòng Chiếu</h4>
                        <p class="mb-1">
                            <span class="fs-24 text-dark fw-medium">{{ $room->name }}</span>
                        </p>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="mb-0 fw-medium fs-18 text-dark">Rạp: <span
                                    class="text-muted">{{ $room->cinema->name }}</span></p>
                        </div>
                        <div class="d-flex gap-2 align-items-center mt-2">
                            <p class="mb-0 fw-medium fs-18 text-dark">Loại Phòng: <span
                                    class="text-muted">{{ $room->roomType->name }}</span></p>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                    class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100"><i
                                        class="bx bx-edit fs-18"></i> Chỉnh Sửa</a>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('admin.rooms.index') }}"
                                    class="btn btn-light d-flex align-items-center justify-content-center gap-2 w-100"><i
                                        class="bx bx-arrow-back fs-18"></i> Quay Lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-dark fw-medium">Thông Tin Chi Tiết</h4>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <h5 class="text-dark fw-medium">Sức Chứa: <span class="text-muted">{{ $room->capacity }}
                                        ghế</span></h5>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="text-dark fw-medium">Trạng Thái:
                                    <span class="text-muted">
                                        @if ($room->status == 'active')
                                            Hoạt động
                                        @elseif($room->status == 'maintenance')
                                            Bảo trì
                                        @endif
                                    </span>
                                </h5>
                            </div>
                        </div>
                        <h4 class="text-dark fw-medium mt-4">Mô Tả</h4>
                        <p class="text-muted">{{ $room->description ?: 'Không có mô tả.' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-light-subtle">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                        <iconify-icon icon="material-symbols:theaters"
                                            class="fs-35 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="text-dark fw-medium fs-16 mb-1">Rạp Chiếu</p>
                                        <p class="mb-0">{{ $room->cinema->name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                        <iconify-icon icon="solar:armchair-bold-duotone"
                                            class="fs-35 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="text-dark fw-medium fs-16 mb-1">Sức Chứa</p>
                                        <p class="mb-0">{{ $room->capacity }} ghế</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                        <iconify-icon icon="solar:settings-bold-duotone"
                                            class="fs-35 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="text-dark fw-medium fs-16 mb-1">Trạng Thái</p>
                                        <p class="mb-0">
                                            @if ($room->status == 'active')
                                                Hoạt động
                                            @elseif($room->status == 'maintenance')
                                                Bảo trì
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                        <iconify-icon icon="solar:tag-bold-duotone"
                                            class="fs-35 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="text-dark fw-medium fs-16 mb-1">Loại Phòng</p>
                                        <p class="mb-0">{{ $room->roomType->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Thêm bảng danh sách ghế --}}
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Danh Sách Ghế</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Mã Ghế</th>
                                <th>Số Ghế</th>
                                <th>Loại Ghế</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($room->seats as $seat)
                                <tr>
                                    <td>{{ $seat->row_char }}
                                    </td>
                                    <td>{{ $seat->seat_number }}</td>
                                    <td>{{ $seat->seatType->name ?? 'N/A' }}</td>
                                     @php
                                            $statusColors = [
                                                'available' => 'bg-success',
                                                'reserved'  => 'bg-secondary',
                                                'booked'    => 'bg-warning',
                                            ];
                                            $statusValue = is_object($seat->status) ? $seat->status->value : $seat->status;
                                        @endphp

                                        <td>
                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-danger' }}">
                                                {{ ucfirst($statusValue) }}
                                            </span>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có ghế nào trong phòng này.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
