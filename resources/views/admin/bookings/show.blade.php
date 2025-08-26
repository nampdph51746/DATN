@extends('layouts.admin.admin')

@section('content')
    <style>
        .combo-highlight {
            background-color: #ffffff !important;
            border-left: 4px solid #007bff;
        }
        .combo-items-detail {
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            margin-top: 5px;
        }
        .product-image {
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
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
                                            @if (
                                                $booking->payments->first() &&
                                                    (is_object($booking->payments->first()->status)
                                                        ? $booking->payments->first()->status->value
                                                        : (string) $booking->payments->first()->status) === 'completed')
                                                <span class="badge bg-success-subtle text-success px-2 py-1 fs-13">Đã thanh
                                                    toán</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 fs-13">Chưa thanh
                                                    toán</span>
                                            @endif
                                            <span class="border border-warning text-warning fs-13 px-2 py-1 rounded">
                                                {{ ucfirst(is_object($booking->status) ? $booking->status->value : (string) $booking->status) }}
                                            </span>
                                        </h4>
                                        <p class="mb-0">
                                            Đơn đặt vé / Chi tiết đơn hàng / #{{ $booking->booking_code }} -
                                            {{ $booking->created_at->format('F d, Y \a\t h:i A') }}
                                        </p>
                                    </div>
                                    <div>
                                        <a href="#!" class="btn btn-outline-secondary">Refund</a>
                                        <a href="{{ route('admin.bookings.index') }}"
                                            class="btn btn-outline-secondary">Return</a>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h4 class="fw-medium text-dark">Tiến trình đặt vé</h4>
                                </div>

                                <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
                                    @php
                                        $statusValue = is_object($booking->status)
                                            ? $booking->status->value
                                            : (string) $booking->status;
                                        $paymentCompleted =
                                            $booking->payments->first() &&
                                            (is_object($booking->payments->first()->status)
                                                ? $booking->payments->first()->status->value
                                                : (string) $booking->payments->first()->status) === 'completed';
                                        $progressWidths = [
                                            'pending' => [25, 0, 0, 0],
                                            'confirmed' => [
                                                100,
                                                $paymentCompleted ? 100 : 50,
                                                100,
                                                $paymentCompleted ? 100 : 0,
                                            ],
                                            'completed' => [100, 100, 100, 100],
                                        ];
                                        $widths = $progressWidths[$statusValue] ?? [25, 0, 0, 0];
                                    @endphp
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" style="width: {{ $widths[0] }}%"></div>
                                        </div>
                                        <p class="mb-0 mt-2">Đặt vé</p>
                                    </div>
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated {{ $widths[1] > 0 ? 'bg-success' : 'bg-warning' }}"
                                                role="progressbar" style="width: {{ $widths[1] }}%"></div>
                                        </div>
                                        <p class="mb-0 mt-2">Thanh toán</p>
                                    </div>
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated {{ $widths[2] > 0 ? 'bg-success' : 'bg-warning' }}"
                                                role="progressbar" style="width: {{ $widths[2] }}%"></div>
                                        </div>
                                        <p class="mb-0 mt-2">Xác nhận</p>
                                    </div>
                                    <div class="col">
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated {{ $widths[3] > 0 ? 'bg-success' : 'bg-primary' }}"
                                                role="progressbar" style="width: {{ $widths[3] }}%"></div>
                                        </div>
                                        <p class="mb-0 mt-2">Hoàn tất</p>
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
                                    <p><strong>Trạng thái:</strong>
                                        {{ ucfirst(is_object($booking->status) ? $booking->status->value : (string) $booking->status) }}
                                    </p>
                                    <p><strong>Ghi chú:</strong> {{ $booking->notes ?? 'Không có' }}</p>
                                </div>
                            </div>

                            <div class="card-footer bg-light-subtle">
                                <!-- Xóa Estimated shipping date và Make As Ready To Ship -->
                            </div>
                        </div>

                        <!-- Danh sách vé -->
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
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($booking->tickets as $index => $ticket)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $ticket->ticket_code }}</td>
                                                        <td>{{ optional($ticket->showtime->movie)->name ?? 'N/A' }}</td>
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
                                                                'pending' => 'bg-warning',
                                                                'confirmed' => 'bg-success',
                                                                'checked' => 'bg-info',
                                                                'used' => 'bg-secondary',
                                                                'cancelled' => 'bg-danger',
                                                                'valid' => 'bg-success',
                                                            ];
                                                            $statusValue = is_object($ticket->status)
                                                                ? $ticket->status->value
                                                                : (string) $ticket->status;
                                                        @endphp
                                                        <td>
                                                            <span
                                                                class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                                {{ ucfirst($statusValue) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '' }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('tickets.qr', ['ticket_id' => $ticket->id]) }}" target="_blank"
                                                                @if (in_array($statusValue, ['used', 'cancelled', 'valid'])) class="btn btn-sm btn-primary ms-2 disabled" style="opacity:0.6;cursor:not-allowed;" @else class="btn btn-sm btn-primary ms-2" @endif>
                                                                In vé
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Danh sách sản phẩm và combo đã đặt -->
                        <div class="card mt-4">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <strong>Danh sách sản phẩm và combo đã đặt</strong>
                                <small class="badge bg-light text-dark">
                                    Tổng: {{ $booking->bookingItems->count() }} item(s)
                                </small>
                            </div>
                            <div class="card-body p-0">
                                @if ($booking->bookingItems->isEmpty())
                                    <p class="p-3">Không có sản phẩm nào trong đơn này.</p>
                                @else
                                    <!-- Thống kê nhanh -->
                                    @php
                                        $comboCount = $booking->bookingItems->whereNotNull('combo_id')->count();
                                        $productCount = $booking->bookingItems->whereNull('combo_id')->count();
                                    @endphp
                                    <div class="p-3 bg-light border-bottom">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <i class="fas fa-box text-primary"></i>
                                                    <span><strong>Sản phẩm:</strong> {{ $productCount }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <span class="text-warning">🍿</span>
                                                    <span><strong>Combo:</strong> {{ $comboCount }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <i class="fas fa-shopping-cart text-success"></i>
                                                    <span><strong>Tổng cộng:</strong> {{ $booking->bookingItems->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên sản phẩm/Combo</th>
                                                    <th>Biến thể/Chi tiết</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá tại thời điểm mua</th>
                                                    <th>Mô tả</th>
                                                    <th>Ảnh</th>
                                                    <th>Loại</th>
                                                    <th>Trạng thái</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($booking->bookingItems as $index => $item)
                                                    <tr class="{{ $item->combo_id ? 'combo-highlight' : '' }}">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            @if($item->combo_id)
                                                                <!-- Đây là combo -->
                                                                <strong class="text-primary">🍿 {{ $item->combo->name }}</strong>
                                                                <div class="combo-items-detail">
                                                                    <strong class="text-muted">Combo bao gồm:</strong>
                                                                    @foreach($item->combo->comboPackageItems as $comboItem)
                                                                        <div class="ms-2">
                                                                            • {{ $comboItem->quantity }}x {{ $comboItem->itemProductVariant->product->name ?? 'N/A' }}
                                                                            @if($comboItem->itemProductVariant->productVariantOptions->isNotEmpty())
                                                                                <small class="text-muted">({{ $comboItem->itemProductVariant->productVariantOptions->pluck('attributeValue.value')->join(' - ') }})</small>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <!-- Đây là sản phẩm thường -->
                                                                {{ $item->productVariant->product->name ?? 'N/A' }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->combo_id)
                                                                <span class="badge bg-warning">Combo</span><br>
                                                                <small>SKU: {{ $item->productVariant->sku ?? 'N/A' }}</small>
                                                            @else
                                                                {{ $item->productVariant->sku ?? 'N/A' }}
                                                                @if($item->productVariant->productVariantOptions->isNotEmpty())
                                                                    <br><small class="text-muted">
                                                                        {{ $item->productVariant->productVariantOptions->pluck('attributeValue.value')->join(' - ') }}
                                                                    </small>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->price_at_purchase, 0, ',', '.') }} đ</td>
                                                        <td>
                                                            @if($item->combo_id)
                                                                {{ $item->combo->description ?? 'Combo đặc biệt' }}
                                                            @else
                                                                {{ $item->productVariant->product->description ?? 'Không có mô tả' }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->combo_id)
                                                                <!-- Hiển thị ảnh combo -->
                                                                @php
                                                                    $comboImage = null;
                                                                    if ($item->combo && $item->combo->combo_url) {
                                                                        $comboImage = 'storage/' . $item->combo->combo_url;
                                                                    } elseif ($item->combo && $item->combo->comboPackageItems->first() && $item->combo->comboPackageItems->first()->itemProductVariant && $item->combo->comboPackageItems->first()->itemProductVariant->product && $item->combo->comboPackageItems->first()->itemProductVariant->product->image_url) {
                                                                        $comboImage = $item->combo->comboPackageItems->first()->itemProductVariant->product->image_url;
                                                                    }
                                                                @endphp
                                                                @if($comboImage)
                                                                    <img src="{{ asset($comboImage) }}" alt="Ảnh combo" width="50" class="product-image">
                                                                @else
                                                                    <div class="text-center p-2 bg-warning text-white rounded" style="width:50px; height:50px; display:flex; align-items:center; justify-content:center;">
                                                                        🍿
                                                                    </div>
                                                                @endif
                                                            @else
                                                                @if ($item->productVariant->product->image_url)
                                                                    <img src="{{ asset($item->productVariant->product->image_url) }}" alt="Ảnh sản phẩm" width="50" class="product-image">
                                                                @else
                                                                    <span class="text-muted">Không có ảnh</span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->combo_id)
                                                                <span class="badge bg-warning text-dark">
                                                                    🍿 COMBO
                                                                </span>
                                                            @else
                                                                @php
                                                                    $type = $item->productVariant->product->product_type ?? null;
                                                                    $typeStr = is_object($type) ? $type->value : $type;
                                                                @endphp
                                                                <span class="badge bg-info text-dark">
                                                                    {{ $typeStr ? ucfirst($typeStr) : 'Không rõ' }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusRaw = $item->product_status ?? 'valid';
                                                                $statusValue = is_object($statusRaw) ? $statusRaw->value : (string) $statusRaw;
                                                                $statusColors = [
                                                                    'valid' => 'bg-success',
                                                                    'checked' => 'bg-info',
                                                                    'used' => 'bg-secondary',
                                                                    'cancelled' => 'bg-danger',
                                                                ];
                                                            @endphp
                                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                                {{ ucfirst($statusValue) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('foods.qr', ['item_id' => $item->id]) }}" target="_blank"
                                                                @if (in_array($statusValue, ['used', 'cancelled', 'valid'])) class="btn btn-sm btn-primary ms-2 disabled" style="opacity:0.6;cursor:not-allowed;" @else class="btn btn-sm btn-primary ms-2" @endif>
                                                                @if($item->combo_id)
                                                                    In QR Combo
                                                                @else  
                                                                    In QR Đồ ăn
                                                                @endif
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @push('scripts')
                                                    <script>
                                                        function printTicketQR(ticketId) {
                                                            fetch(`/api/ticket/print/${ticketId}`, {
                                                                    method: 'POST'
                                                                })
                                                                .then(res => res.json())
                                                                .then(data => {
                                                                    if (data.success && data.qr) {
                                                                        showQrModal(data.qr);
                                                                    } else {
                                                                        alert(data.message || 'Có lỗi xảy ra!');
                                                                    }
                                                                });
                                                        }

                                                        function printFoodQR(itemId) {
                                                            fetch(`/api/food/print/${itemId}`, {
                                                                    method: 'POST'
                                                                })
                                                                .then(res => res.json())
                                                                .then(data => {
                                                                    if (data.success && data.qr) {
                                                                        showQrModal(data.qr);
                                                                    } else {
                                                                        alert(data.message || 'Có lỗi xảy ra!');
                                                                    }
                                                                });
                                                        }

                                                        function showQrModal(qrBase64) {
                                                            const modal = document.createElement('div');
                                                            modal.style.position = 'fixed';
                                                            modal.style.top = '0';
                                                            modal.style.left = '0';
                                                            modal.style.width = '100vw';
                                                            modal.style.height = '100vh';
                                                            modal.style.background = 'rgba(0,0,0,0.5)';
                                                            modal.style.display = 'flex';
                                                            modal.style.alignItems = 'center';
                                                            modal.style.justifyContent = 'center';
                                                            modal.innerHTML =
                                                                `<div style='background:#fff;padding:30px;border-radius:10px;text-align:center;'><img src='data:image/png;base64,${qrBase64}' style='max-width:300px;'><br><button onclick='this.parentNode.parentNode.remove()' style='margin-top:20px;'>Đóng</button></div>`;
                                                            document.body.appendChild(modal);
                                                        }
                                                    </script>
                                                @endpush
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                @php
                    $payment = $booking->payments->first();
                @endphp
                @if ($payment)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Tóm tắt thanh toán</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="px-0">
                                                <p class="d-flex mb-0 align-items-center gap-1">
                                                    <iconify-icon icon="solar:bill-list-broken"></iconify-icon>
                                                    Mã giao dịch:
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
                                                    Trạng thái:
                                                </p>
                                            </td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'text-warning',
                                                    'completed' => 'text-success',
                                                    'failed' => 'text-danger',
                                                ];
                                                $statusValue = is_object($payment->status)
                                                    ? $payment->status->value
                                                    : (string) $payment->status;
                                                $statusColor = $statusColors[$statusValue] ?? 'text-muted';
                                            @endphp
                                            <td class="text-end fw-medium px-0">
                                                <span class="{{ $statusColor }}">{{ ucfirst($statusValue) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-0">
                                                <p class="d-flex mb-0 align-items-center gap-1">
                                                    <iconify-icon icon="solar:calendar-broken"></iconify-icon>
                                                    Thời gian thanh toán:
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
                                                        Chi tiết:
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
                        <div class="card-footer d-flex justify-content-between bg-light-subtle flex-wrap">
                            <div class="d-flex flex-column">
                                <p class="fw-medium text-dark mb-1">Tổng tiền</p>
                                <p class="fw-bold text-dark mb-0">
                                    {{ number_format($payment->amount, 0, ',', '.') }} đ</p>
                            </div>
                            <div class="d-flex flex-column text-end">
                                <p class="fw-medium text-dark mb-1">Phương thức thanh toán</p>
                                <p class="fw-bold text-primary mb-0">
                                    {{ $payment->paymentMethod->name ?? 'Không rõ' }}</p>
                            </div>
                        </div>
                    </div>
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