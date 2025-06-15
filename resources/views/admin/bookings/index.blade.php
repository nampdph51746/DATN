@extends('layouts.admin.admin')

@section('content')
<div class="container">

    <div class="container-xxl">

        <div class="row">
            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Tổng đơn</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $bookingStats['total'] }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:clipboard-text-broken"
                                            class="fs-32 text-primary avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Chờ xác nhận</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $bookingStats['pending'] }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-warning bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:clock-circle-broken"
                                            class="fs-32 text-warning avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Đã xác nhận</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $bookingStats['confirmed'] }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-success bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:check-circle-broken"
                                            class="fs-32 text-success avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Đã hủy</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $bookingStats['cancelled'] }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-danger bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:close-circle-broken"
                                            class="fs-32 text-danger avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Danh sách đơn đặt vé</h4>
                        </div>
                        {{-- Tim kiem va loc --}}
                        <form method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control w-auto"
                                placeholder="ID, Mã Booking hoặc User ID" value="{{ request('search') }}">
                                

                            <select name="status" class="form-select w-auto">
                                <option value="">-- Trạng thái --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác
                                    nhận</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã
                                    xác nhận</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã
                                    hủy</option>
                            </select>


                            <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã Đặt Vé</th>
                                        <th>Người dùng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>

                                        <td>{{ $booking->booking_code }}</td>

                                        <td>
                                            <a href="#!" class="link-primary fw-medium">
                                                {{ $booking->user->name ?? 'User #' . $booking->user_id }}
                                            </a>
                                        </td>

                                        <td>{{ number_format($booking->final_amount, 0, ',', '.') }} đ</td>

                                        <td>
                                            @switch($booking->status?->value)
                                            @case('pending')
                                            <span class="badge bg-warning-subtle text-warning px-2 py-1 fs-13">Chờ
                                                xác nhận</span>
                                            @break

                                            @case('confirmed')
                                            <span class="badge bg-success-subtle text-success px-2 py-1 fs-13">Đã
                                                xác nhận</span>
                                            @break

                                            @case('cancelled')
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1 fs-13">Đã
                                                hủy</span>
                                            @break

                                            @default
                                            <span class="badge bg-light text-dark px-2 py-1 fs-13">Không rõ</span>
                                            @endswitch

                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                                    class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.bookings.editStatus', $booking->id) }}"
                                                    class="btn btn-soft-primary btn-sm"
                                                    title="Chỉnh sửa trạng thái">
                                                    <iconify-icon icon="solar:pen-2-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-end">
                            {{ $bookings->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection