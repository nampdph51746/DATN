@extends('layouts.admin.admin')

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm border-0 rounded bg-light-subtle">
            <div class="card-body px-4 py-4 position-relative">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h4 class="fw-semibold text-orange">
                        Chi tiết thanh toán #{{ $payment->id ?? '-' }}
                    </h4>
                    @php
                        $status = $payment->status?->value ?? 'unknown';
                        $statusText = match ($status) {
                            'pending' => 'Chờ xử lý',
                            'completed' => 'Hoàn tất',
                            'failed' => 'Thất bại',
                            'cancelled' => 'Đã hủy',
                            default => 'Không rõ',
                        };
                        $statusColor = match ($status) {
                            'pending' => 'bg-warning',
                            'completed' => 'bg-success',
                            'failed', 'cancelled' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    @endphp

                    <span class="badge {{ $statusColor }} text-white py-2 px-3 fs-6 rounded-pill">
                        {{ $statusText }}
                    </span>

                </div>

                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <div>
                        <h6 class="text-muted mb-2">Booking</h6>
                        <p class="mb-1"><strong>ID:</strong> {{ $payment->booking?->id ?? '-' }}</p>
                        <p class="mb-1"><strong>Code:</strong> {{ $payment->booking?->booking_code ?? '-' }}</p>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Người dùng</h6>
                        <p class="mb-1"><strong>Tên:</strong> {{ $payment->booking?->user?->name ?? 'Không rõ' }}</p>
                        <p class="mb-1"><strong>User ID:</strong> {{ $payment->booking?->user?->id ?? '-' }}</p>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Thông tin thanh toán</h6>
                        <p class="mb-1"><strong>Số tiền:</strong>
                            {{ $payment->amount ? number_format($payment->amount, 0, ',', '.') . ' đ' : '-' }}</p>
                        <p class="mb-1"><strong>Thời gian thanh toán:</strong>
                            {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-' }}
                        </p>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Phương thức</h6>
                        <p class="mb-1"><strong>Tên:</strong> {{ $payment->paymentMethod?->name ?? '-' }}</p>
                        <p class="mb-1"><strong>Giao dịch:</strong> {{ $payment->transaction_id_gateway ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>
@endsection
