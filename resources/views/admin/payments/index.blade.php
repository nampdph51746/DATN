@extends('layouts.admin.admin')

@section('content')
    <div class="container-fluid px-4 py-5 bg-light">
        <!-- Dashboard Cards with Modern Design -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Tổng tiền giao dịch</h4>
                            <p class="fw-bold fs-3 mb-0 text-primary">{{ number_format($totalAmount, 0, ',', '.') }} đ</p>
                        </div>
                        <div class="avatar-xl bg-primary bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:wallet-money-broken" class="fs-48 text-primary"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Giao dịch thành công</h4>
                            <p class="fw-bold fs-3 mb-0 text-success">{{ number_format($countCompleted) }}</p>
                        </div>
                        <div class="avatar-xl bg-success bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:check-circle-broken" class="fs-48 text-success"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Chờ thanh toán</h4>
                            <p class="fw-bold fs-3 mb-0 text-warning">{{ number_format($countPending) }}</p>
                        </div>
                        <div class="avatar-xl bg-warning bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:clock-circle-broken" class="fs-48 text-warning"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #e6f0fa); transition: transform 0.3s;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">Giao dịch thất bại</h4>
                            <p class="fw-bold fs-3 mb-0 text-danger">{{ number_format($countFailed) }}</p>
                        </div>
                        <div class="avatar-xl bg-danger bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:close-circle-broken" class="fs-48 text-danger"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats and Recent Activity -->
        <div class="row g-4 mb-5">
            <div class="col-xl-6">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-gradient bg-primary text-white d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 fw-bold">Thống kê nhanh</h4>
                        <iconify-icon icon="solar:chart-square-broken" class="fs-28"></iconify-icon>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <h5 class="text-muted mb-2">Tổng giao dịch</h5>
                                <p class="fw-bold fs-4 mb-0 text-dark">{{ number_format($countCompleted + $countPending + $countFailed) }}</p>
                            </div>
                            <div class="col-6">
                                <h5 class="text-muted mb-2">Tỷ lệ thành công</h5>
                                <p class="fw-bold fs-4 mb-0 text-dark">{{ ($countCompleted + $countPending + $countFailed) > 0 ? number_format(($countCompleted / ($countCompleted + $countPending + $countFailed)) * 100, 1) : 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-gradient bg-success text-white d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 fw-bold">Hoạt động gần đây</h4>
                        <iconify-icon icon="solar:history-broken" class="fs-28"></iconify-icon>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span>Giao dịch mới #1234</span>
                                <span class="badge bg-primary rounded-pill px-3 py-2">Mới</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span>Hoàn tiền #5678</span>
                                <span class="badge bg-warning rounded-pill px-3 py-2 text-dark">Chờ</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span>Thanh toán thành công #91011</span>
                                <span class="badge bg-success rounded-pill px-3 py-2">Hoàn tất</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment List Section with Modern Table -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-dark text-white d-flex align-items-center justify-content-between">
                <h2 class="mb-0 fs-4 fw-bold">Danh sách thanh toán</h2>
                <iconify-icon icon="solar:list-broken" class="fs-32"></iconify-icon>
            </div>
            <div class="card-body">
                <!-- Filter Form with Enhanced Styling -->
                <form method="GET" class="mb-4 d-flex flex-wrap gap-3 align-items-end">
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-3">
                                <iconify-icon icon="solar:search-broken" class="text-muted fs-22"></iconify-icon>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 rounded-end-3" placeholder="Tìm kiếm ID, mã đặt vé hoặc mã người dùng" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <select name="status" class="form-select rounded-3">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary rounded-3 d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:filter-broken" class="fs-20"></iconify-icon>
                            Lọc
                        </button>
                    </div>
                </form>

                <!-- Payments Table with Modern Styling -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered border-light align-middle rounded-3">
                        <thead class="table-dark">
                            <tr>
                                <th class="py-3">ID</th>
                                <th class="py-3">Mã đặt vé</th>
                                <th class="py-3">Người dùng</th>
                                <th class="py-3">Số tiền</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="py-3">Ngày thanh toán</th>
                                <th class="py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr class="transition-all duration-300 hover:shadow-md hover:bg-gray-50">
                                    <td class="fw-medium py-3">#{{ $payment->id }}</td>
                                    <td class="py-3">{{ $payment->booking->booking_code ?? 'Không có' }}</td>
                                    <td class="py-3">{{ $payment->booking->user->name ?? 'Người dùng #' . $payment->booking->user_id }}</td>
                                    <td class="text-nowrap py-3">{{ number_format($payment->amount, 0, ',', '.') }} đ</td>
                                    <td class="py-3">
                                        @switch($payment->status->value)
                                            @case('completed')
                                                <span class="badge bg-success-subtle text-success fw-medium px-3 py-2 rounded-pill">Đã hoàn thành</span>
                                            @break
                                            @case('pending')
                                                <span class="badge bg-warning-subtle text-warning fw-medium px-3 py-2 rounded-pill">Chờ xử lý</span>
                                            @break
                                            @case('failed')
                                                <span class="badge bg-danger-subtle text-danger fw-medium px-3 py-2 rounded-pill">Thanh toán thất bại</span>
                                            @break
                                            @default
                                                <span class="badge bg-secondary-subtle text-secondary fw-medium px-3 py-2 rounded-pill">Không xác định</span>
                                        @endswitch
                                    </td>
                                    <td class="text-nowrap py-3">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="py-3">
                                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1" title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="fs-18"></iconify-icon>
                                            Xem
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">Không tìm thấy bản ghi phù hợp.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination with Modern Styling -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $payments->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Enhanced Styling -->
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
        .bg-gradient {
            background: linear-gradient(90deg, rgba(0, 123, 255, 1) 0%, rgba(0, 86, 179, 1) 100%);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
        .form-control, .form-select, .btn {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, rgba(0, 86, 179, 1) 0%, rgba(0, 123, 255, 1) 100%);
        }
    </style>
@endsection