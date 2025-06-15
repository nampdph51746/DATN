@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">
    <div class="row">
    {{-- Tất cả --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Tất cả</h4>
                        <p class="text-muted fw-medium fs-22 mb-0">{{ $paymentStats['all'] }}</p>
                    </div>
                    <div>
                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                            <iconify-icon icon="mdi:clipboard-list-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chờ xử lý --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Chờ xử lý</h4>
                        <p class="text-muted fw-medium fs-22 mb-0">{{ $paymentStats['pending'] }}</p>
                    </div>
                    <div>
                        <div class="avatar-md bg-warning bg-opacity-10 rounded">
                            <iconify-icon icon="mdi:clock-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hoàn tất --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Hoàn tất</h4>
                        <p class="text-muted fw-medium fs-22 mb-0">{{ $paymentStats['completed'] }}</p>
                    </div>
                    <div>
                        <div class="avatar-md bg-success bg-opacity-10 rounded">
                            <iconify-icon icon="mdi:check-circle-outline" class="fs-32 text-success avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Thất bại --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Thất bại</h4>
                        <p class="text-muted fw-medium fs-22 mb-0">{{ $paymentStats['failed'] }}</p>
                    </div>
                    <div>
                        <div class="avatar-md bg-danger bg-opacity-10 rounded">
                            <iconify-icon icon="mdi:close-circle-outline" class="fs-32 text-danger avatar-title"></iconify-icon>
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
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1">Danh sách Thanh Toán</h4>

                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control w-auto" placeholder="ID, Mã Booking hoặc User ID"
                            value="{{ request('search') }}">

                        <select name="status" class="form-select w-auto">
                            <option value="">-- Trạng thái --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                    </form>
                </div>

                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>STT</th>
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
                                    <td></td>
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
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end mt-3">
                        {{ $payments->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection