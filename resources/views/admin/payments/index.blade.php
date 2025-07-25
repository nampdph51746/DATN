@extends('layouts.admin.admin')

@section('content')
    <div class="container">
        <!-- Dashboard Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2">Tổng tiền giao dịch</h4>
                            <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($totalAmount, 0, ',', '.') }} đ</p>
                        </div>
                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                            <iconify-icon icon="solar:wallet-money-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2">Giao dịch thành công</h4>
                            <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($countCompleted) }}</p>
                        </div>
                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                            <iconify-icon icon="solar:check-circle-broken"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2">Chờ thanh toán</h4>
                            <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($countPending) }}</p>
                        </div>
                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                            <iconify-icon icon="solar:clock-circle-broken"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2">Giao dịch thất bại</h4>
                            <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($countFailed) }}</p>
                        </div>
                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                            <iconify-icon icon="solar:close-circle-broken"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment List Section -->
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Danh sách thanh toán</h2>

                <!-- Filter Form -->
                <form method="GET" class="mb-4 d-flex flex-wrap gap-2">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control"
                            placeholder="ID, mã đặt vé hoặc mã người dùng" value="{{ request('search') }}">
                    </div>
                    <div>
                        <select name="status" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành
                            </option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền
                            </option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </div>
                </form>

                <!-- Payments Table -->
                <div class="table-responsive">
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
                                    <td>{{ $payment->booking->booking_code ?? 'Không có' }}</td>
                                    <td>{{ $payment->booking->user->name ?? 'Người dùng #' . $payment->booking->user_id }}
                                    </td>
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
                                        <a href="{{ route('admin.payments.show', $payment->id) }}"
                                            class="btn btn-light btn-sm" title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
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

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $payments->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    @endsection