@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div>
                                        <h4 class="fw-medium text-dark d-flex align-items-center gap-2">
                                            {{ $booking->booking_code }}
                                            @if ($booking->payment && $booking->payment->status == \App\Enums\PaymentStatus::Paid)
                                                <span
                                                    class="badge bg-success-subtle text-success px-2 py-1 fs-13">Paid</span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger px-2 py-1 fs-13">Unpaid</span>
                                            @endif
                                            <span class="border border-warning text-warning fs-13 px-2 py-1 rounded">
                                                {{ $booking->status }}
                                            </span>
                                        </h4>
                                        <p class="mb-0">
                                            Order / Order Details / #{{ $booking->booking_code }} -
                                            {{ $booking->created_at->format('F d , Y \a\t h:i A') }}
                                        </p>
                                    </div>
                                    <div>
                                        <a href="#!" class="btn btn-outline-secondary">Refund</a>
                                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Return</a>
                                        <a href="#!" class="btn btn-primary">Edit Order</a>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h4 class="fw-medium text-dark">Progress</h4>
                                </div>

                                <div class="row row-cols-xxl-5 row-cols-md-2 row-cols-1">
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" style="width: 100%"></div>
                                        </div>
                                        <p class="mb-0 mt-2">Order Confirming</p>
                                    </div>
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" style="width: 100%"></div>
                                        </div>
                                        <p class="mb-0 mt-2">Payment Pending</p>
                                    </div>
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                                                role="progressbar" style="width: 60%"></div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-2">
                                            <p class="mb-0">Processing</p>
                                            <div class="spinner-border spinner-border-sm text-warning" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                                role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <p class="mb-0 mt-2">Shipping</p>
                                    </div>
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                                role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <p class="mb-0 mt-2">Delivered</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h4 class="fw-medium text-dark">Thông tin đơn đặt vé</h4>
                                    <p><strong>Mã đặt vé:</strong> {{ $booking->booking_code }}</p>
                                    <p><strong>Người dùng:</strong> {{ $booking->user->name ?? 'N/A' }}</p>
                                    <p><strong>Tổng tiền trước giảm:</strong>
                                        {{ number_format($booking->total_amount_before_discount, 0, ',', '.') }} đ</p>
                                    <p><strong>Giảm giá:</strong>
                                        {{ number_format($booking->discount_amount, 0, ',', '.') }} đ</p>
                                    <p><strong>Tổng thanh toán:</strong>
                                        {{ number_format($booking->final_amount, 0, ',', '.') }} đ</p>
                                    <p><strong>Trạng thái:</strong> {{ $booking->status }}</p>
                                    <p><strong>Ghi chú:</strong> {{ $booking->notes }}</p>
                                </div>
                            </div>

                            <div
                                class="card-footer d-flex flex-wrap align-items-center justify-content-between bg-light-subtle gap-2">
                                <p class="border rounded mb-0 px-2 py-1 bg-body">
                                    <i class='bx bx-arrow-from-left align-middle fs-16'></i>
                                    Estimated shipping date:
                                    <span class="text-dark fw-medium">{{ now()->addDays(2)->format('M d, Y') }}</span>
                                </p>
                                <div>
                                    <a href="#!" class="btn btn-primary">Make As Ready To Ship</a>
                                </div>
                            </div>
                        </div>

                        {{-- Ticket --}}
                        <div class="card mt-4">
                            <div class="card-header bg-primary text-white">
                                <strong>Danh sách vé</strong>
                            </div>
                            <div class="card-body p-0">
                                @if ($booking->tickets->isEmpty())
                                    <p class="p-3">Không có vé nào trong đơn này.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Mã vé</th>
                                                    <th>Phim</th>
                                                    <th>Suất chiếu</th>
                                                    <th>Ghế</th>
                                                    <th>Giá tại thời điểm mua</th>
                                                    <th>Trạng thái</th>
                                                    <th>Ngày tạo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($booking->tickets as $index => $ticket)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $ticket->ticket_code }}</td>
                                                        <td>{{ optional($ticket->showtime->movie)->title ?? 'N/A' }}</td>
                                                        <td>
                                                            {{ optional($ticket->showtime)->start_time ? $ticket->showtime->start_time->format('d/m/Y H:i') : 'N/A' }}<br>
                                                            @if (optional($ticket->showtime)->room)
                                                                Phòng: {{ $ticket->showtime->room->name }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($ticket->seat)
                                                                {{ $ticket->seat->row_char . $ticket->seat->seat_number }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($ticket->price_at_purchase, 0, ',', '.') }} đ
                                                        </td>
                                                        @php
                                                            $statusColors = [
                                                                'pending'   => 'bg-warning',
                                                                'confirmed' => 'bg-success',
                                                                'cancelled' => 'bg-danger',
                                                            ];

                                                            // Xử lý an toàn: nếu là enum thì lấy ->value, nếu không thì cast về string
                                                            $statusValue = is_object($ticket->status) ? $ticket->status->value : (string) $ticket->status;
                                                        @endphp

                                                        <td>
                                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                                {{ ucfirst($statusValue) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Product --}}
                        <div class="card mt-4">
                            <div class="card-header bg-primary text-white">
                                <strong>Danh sách sản phẩm đã đặt</strong>
                            </div>
                            <div class="card-body p-0">
                                @if ($booking->bookingItems->isEmpty())
                                    <p class="p-3">Không có sản phẩm nào trong đơn này.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Biến thể</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá tại thời điểm mua</th>
                                                    <th>Mô tả</th>
                                                    <th>Ảnh</th>
                                                    <th>Loại sản phẩm</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($booking->bookingItems as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $item->productVariant->product->name ?? 'N/A' }}</td>
                                                        <td>{{ $item->productVariant->name ?? 'N/A' }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->price_at_purchase, 0, ',', '.') }} đ
                                                        </td>
                                                        <td>{{ $item->productVariant->product->description ?? 'Không có mô tả' }}
                                                        </td>
                                                        <td>
                                                            @if ($item->productVariant->product->image_url)
                                                                <img src="{{ asset($item->productVariant->product->image_url) }}"
                                                                    alt="Ảnh sản phẩm" width="50">
                                                            @else
                                                                <span class="text-muted">Không có ảnh</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $type = $item->productVariant->product->product_type;
                                                                $typeStr = is_object($type) ? $type->value : $type;
                                                            @endphp
                                                            <span class="badge bg-info text-dark">
                                                                {{ ucfirst($typeStr ?? 'Không rõ') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Thanh Toán --}}

                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                @php
                    $payment = $booking->payments->first(); // Lấy thanh toán đầu tiên
                @endphp

                @if ($payment)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Payment Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="px-0">
                                                <p class="d-flex mb-0 align-items-center gap-1">
                                                    <iconify-icon icon="solar:bill-list-broken"></iconify-icon>
                                                    Transaction ID:
                                                </p>
                                            </td>
                                            <td class="text-end text-dark fw-medium px-0">
                                                {{ $payment->transaction_id_gateway ?? '---' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-0">
                                                <p class="d-flex mb-0 align-items-center gap-1">
                                                    <iconify-icon icon="solar:shield-check-broken"></iconify-icon>
                                                    Status:
                                                </p>_
                                            </td>
                                        @php
                                            $statusColors = [
                                                'pending'   => 'text-warning',
                                                'completed' => 'text-success',
                                                'failed'    => 'text-danger',
                                            ];

                                            // Ép về chuỗi nếu là Enum object
                                            $statusValue = is_object($payment->status) ? $payment->status->value : (string) $payment->status;

                                            $statusColor = $statusColors[$statusValue] ?? 'text-muted';
                                        @endphp

                                        <td class="text-end fw-medium px-0">
                                            <span class="{{ $statusColor }}">{{ ucfirst($statusValue) }}</span>
                                        </td>

                                        </tr>
                                        <tr>
                                            <td class="px-0">
                                                <p class="d-flex mb-0 align-items-center gap-1">
                                                    <iconify-icon icon="solar:calendar-broken"></iconify-icon> Paid
                                                    At:
                                                </p>
                                            </td>
                                            <td class="text-end text-dark fw-medium px-0">
                                                {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'Chưa thanh toán' }}
                                            </td>
                                        </tr>
                                        @if ($payment->payment_details)
                                            <tr>
                                                <td class="px-0">
                                                    <p class="d-flex mb-0 align-items-center gap-1">
                                                        <iconify-icon icon="solar:document-text-broken"></iconify-icon>
                                                        Details:
                                                    </p>
                                                </td>
                                                <td class="text-end px-0">
                                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                        data-bs-target="#paymentDetailModal{{ $payment->id }}">
                                                        Xem
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Footer hiển thị Total + Payment Method --}}
                        <div class="card-footer d-flex justify-content-between bg-light-subtle flex-wrap">
                            <div class="d-flex flex-column">
                                <p class="fw-medium text-dark mb-1">Total Amount</p>
                                <p class="fw-bold text-dark mb-0">
                                    {{ number_format($payment->amount, 0, ',', '.') }} đ</p>
                            </div>
                            <div class="d-flex flex-column text-end">
                                <p class="fw-medium text-dark mb-1">Payment Method</p>
                                <p class="fw-bold text-primary mb-0">
                                    {{ $payment->paymentMethod->name ?? 'Không rõ' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Modal chi tiết thanh toán --}}
                    @if ($payment->payment_details)
                        <div class="modal fade" id="paymentDetailModal{{ $payment->id }}" tabindex="-1"
                            aria-labelledby="paymentDetailModalLabel{{ $payment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="paymentDetailModalLabel{{ $payment->id }}">
                                            Chi tiết thanh toán</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <pre>{{ json_encode(json_decode($payment->payment_details), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning mt-4">Chưa có thông tin thanh toán nào cho đơn hàng này.</div>
                @endif
                {{-- Promotion --}}
                @if ($booking->promotion)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin khuyến mãi áp dụng</h4>
                        </div>
                        <div class="card-body">
                            <p><strong>Tên khuyến mãi:</strong> {{ $booking->promotion->name }}</p>
                            <p><strong>Mã khuyến mãi:</strong> {{ $booking->promotion->code ?? 'Không có' }}</p>
                            <p><strong>Mô tả:</strong> {!! nl2br(e($booking->promotion->description)) !!}</p>
                            <p><strong>Loại giảm giá:</strong>
                                {{ $booking->promotion->discount_type == 'percentage' ? 'Phần trăm' : 'Giá cố định' }}
                            </p>
                            <p><strong>Giá trị giảm:</strong>
                                {{ number_format($booking->promotion->discount_value, 2, ',', '.') }}
                                {{ $booking->promotion->discount_type == 'percentage' ? '%' : 'đ' }}
                            </p>
                            @if ($booking->promotion->max_discount_amount)
                                <p><strong>Giá trị giảm tối đa:</strong>
                                    {{ number_format($booking->promotion->max_discount_amount, 2, ',', '.') }} đ</p>
                            @endif
                            <p><strong>Thời gian áp dụng:</strong>
                                {{ \Carbon\Carbon::parse($booking->promotion->start_date)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($booking->promotion->end_date)->format('d/m/Y') }}
                            </p>
                            <p><strong>Trạng thái:</strong> {{ ucfirst($booking->promotion->status) }}</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection
