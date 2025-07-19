@extends('layouts.admin.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Payment Refund</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">490</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:chat-round-money-broken"
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
                                <h4 class="card-title mb-2">Order Cancel</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">241</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:cart-cross-broken"
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
                                <h4 class="card-title mb-2">Order Shipped</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">630</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:box-broken"
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
                                <h4 class="card-title mb-2">Order Delivering</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">170</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:tram-broken"
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
                                <h4 class="card-title mb-2">Pending Review</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">210</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:clipboard-remove-broken"
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
                                <h4 class="card-title mb-2">Pending Payment</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">608</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:clock-circle-broken"
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
                                <h4 class="card-title mb-2">Delivered</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">200</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:clipboard-check-broken"
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
                                <h4 class="card-title mb-2">In Progress</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">656</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:inbox-line-broken"
                                        class="fs-32 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2>Danh sách Thanh Toán</h2>

        <form method="GET" class="mb-4 d-flex gap-2">
            <input type="text" name="search" class="form-control w-auto" placeholder="ID, Mã Booking hoặc User ID"
                value="{{ request('search') }}">

            <select name="status" class="form-select w-auto">
                <option value="">-- Trạng thái --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>
            <button type="submit" class="btn btn-primary">Lọc</button>
        </form>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Mã đặt vé</th>
                    <th>Người dùng</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày thanh toán</th>
                    <th>Xem</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td>#{{ $payment->id }}</td>
                        <td>{{ $payment->booking->booking_code ?? 'N/A' }}</td>
                        <td>{{ $payment->booking->user->name ?? 'Người dùng #' . $payment->booking->user_id }}</td>
                        <td>{{ number_format($payment->amount, 0, ',', '.') }} đ</td>
                        <td>
                            @switch($payment->status->value)
                                @case('completed')
                                    <span class="badge bg-success">Đã hoàn thành</span>
                                @break

                                @case('pending')
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                @break

                                @case('failed')
                                    <span class="badge bg-danger">Thanh toán thất bại</span>
                                @break

                                @default
                                    <span class="badge bg-light text-dark">Không xác định</span>
                            @endswitch
                        </td>

                        <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Xem chi tiết -->
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-light btn-sm"
                                    title="Xem chi tiết">
                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                </a>
                                <a href="{{ route('admin.payments.editStatus', $payment->id) }}"
                                    class="btn btn-soft-primary btn-sm" title="Chỉnh sửa trạng thái">
                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có bản ghi phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


            <div class="d-flex justify-content-center mt-4">
                {{ $payments->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endsection
