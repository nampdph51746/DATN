@extends('layouts.admin.admin')

@section('content')
    <div class="container">

        <div class="container-xxl">

            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Chờ xác nhận</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $bookings->where('status', 'pending')->count() }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-warning bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:clock-circle-broken" class="fs-32 text-warning avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Đã xác nhận</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-success bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:clipboard-check-broken" class="fs-32 text-success avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Đã hủy</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $bookings->where('status', 'cancelled')->count() }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-danger bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:cart-cross-broken" class="fs-32 text-danger avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng tiền nằm ngang dưới 3 phần trạng thái -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-center align-items-center gap-4">
                                <span class="fw-bold fs-18">Tổng tiền:</span>
                                <span class="fs-20 text-primary fw-bold">
                                    {{ number_format($bookings->where('status', 'confirmed')->sum('final_amount'), 0, ',', '.') }} đ
                                </span>
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
                            {{-- Tìm kiếm và lọc --}}
                            <form method="GET" class="mb-4 d-flex gap-2">
                                <input type="text" name="search" class="form-control w-auto"
                                    placeholder="ID, Mã đặt vé hoặc ID người dùng" value="{{ request('search') }}">

                                <select name="status" class="form-select w-auto">
                                    <option value="">-- Trạng thái --</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>

                                <button type="submit" class="btn btn-primary">Lọc</button>
                            </form>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 table-hover table-centered">
                                    <thead class="bg-light-subtle">
                                        <tr>
                                            <th>ID</th>
                                            <th>Mã đặt vé</th>
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
                                                        {{ $booking->user->name ?? 'Người dùng #' . $booking->user_id }}
                                                    </a>
                                                </td>
                                                <td>{{ number_format($booking->final_amount, 0, ',', '.') }} đ</td>
                                                <td>
                                                    @switch($booking->status?->value)
                                                        @case('pending')
                                                            <span class="badge bg-warning-subtle text-warning px-2 py-1 fs-13">Chờ xác nhận</span>
                                                        @break
                                                        @case('confirmed')
                                                            <span class="badge bg-success-subtle text-success px-2 py-1 fs-13">Đã xác nhận</span>
                                                        @break
                                                        @case('cancelled')
                                                            <span class="badge bg-danger-subtle text-danger px-2 py-1 fs-13">Đã hủy</span>
                                                        @break
                                                        @default
                                                            <span class="badge bg-light text-dark px-2 py-1 fs-13">Không xác định</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                                            class="btn btn-light btn-sm">
                                                            <iconify-icon icon="solar:eye-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        @php
                                                            $firstTicket = $booking->tickets->first();
                                                            $showtimeStart = $firstTicket ? $firstTicket->showtime->start_time : null;
                                                        @endphp
                                                        <a href="{{ route('admin.bookings.print', $booking->booking_code) }}" target="_blank"
                                                            class="btn btn-primary btn-sm print-ticket-btn"
                                                            data-showtime-start="{{ $showtimeStart ? $showtimeStart->toISOString() : '' }}"
                                                            data-booking-code="{{ $booking->booking_code }}">
                                                            <iconify-icon icon="solar:printer-minimalistic-broken"
                                                                class="align-middle fs-18"></iconify-icon> In vé
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookings->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const printButtons = document.querySelectorAll('.print-ticket-btn');
    
    // Function để xử lý click event cho nút bị vô hiệu hóa
    function handleDisabledButtonClick(e, message) {
        e.preventDefault();
        e.stopPropagation();
        Swal.fire({
            icon: 'warning',
            title: 'Không thể in vé',
            text: message,
            confirmButtonText: 'Đã hiểu',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }
    
    printButtons.forEach(button => {
        const showtimeStartStr = button.getAttribute('data-showtime-start');
        const bookingCode = button.getAttribute('data-booking-code');
        
        if (!showtimeStartStr) {
            // Nếu không có thời gian suất chiếu, vô hiệu hóa nút
            button.disabled = true;
            button.classList.remove('btn-primary');
            button.classList.add('btn-secondary');
            button.title = 'Không tìm thấy thông tin suất chiếu';
            button.removeAttribute('href');
            button.onclick = function(e) {
                return handleDisabledButtonClick(e, 'Không tìm thấy thông tin suất chiếu cho đơn đặt vé này.');
            };
            return;
        }
        
        const showtimeStart = new Date(showtimeStartStr);
        const currentTime = new Date();
        
        // Kiểm tra nếu suất chiếu đã bắt đầu
        if (currentTime >= showtimeStart) {
            button.disabled = true;
            button.classList.remove('btn-primary');
            button.classList.add('btn-secondary');
            button.title = 'Không thể in vé sau khi suất chiếu đã bắt đầu. Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN');
            button.removeAttribute('href');
            button.onclick = function(e) {
                return handleDisabledButtonClick(e, 'Không thể in vé sau khi suất chiếu đã bắt đầu.\nSuất chiếu: ' + showtimeStart.toLocaleString('vi-VN'));
            };
            return;
        }
        
        // Kiểm tra nếu còn ít hơn 1 tiếng trước suất chiếu
        const oneHourBeforeShowtime = new Date(showtimeStart.getTime() - (60 * 60 * 1000));
        if (currentTime <= oneHourBeforeShowtime) {
            button.disabled = true;
            button.classList.remove('btn-primary');
            button.classList.add('btn-warning');
            button.title = 'Vé chỉ có thể in trước suất chiếu ít nhất 1 tiếng. Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN');
            button.removeAttribute('href');
            button.onclick = function(e) {
                return handleDisabledButtonClick(e, 'Vé chỉ có thể in trước suất chiếu ít nhất 1 tiếng.\nSuất chiếu: ' + showtimeStart.toLocaleString('vi-VN'));
            };
            return;
        }
        
        // Nếu trong khoảng thời gian cho phép in vé (từ 1 tiếng trước suất chiếu đến khi suất chiếu bắt đầu)
        button.title = 'In vé (Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN') + ')';
    });
    
    // Cập nhật trạng thái nút mỗi phút
    setInterval(() => {
        printButtons.forEach(button => {
            const showtimeStartStr = button.getAttribute('data-showtime-start');
            const originalHref = button.getAttribute('href') || button.dataset.originalHref;
            
            if (!showtimeStartStr) return;
            
            const showtimeStart = new Date(showtimeStartStr);
            const currentTime = new Date();
            
            // Lưu href gốc nếu chưa có
            if (!button.dataset.originalHref && button.getAttribute('href')) {
                button.dataset.originalHref = button.getAttribute('href');
            }
            
            // Kiểm tra nếu suất chiếu đã bắt đầu
            if (currentTime >= showtimeStart) {
                button.disabled = true;
                button.classList.remove('btn-primary', 'btn-warning');
                button.classList.add('btn-secondary');
                button.title = 'Không thể in vé sau khi suất chiếu đã bắt đầu. Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN');
                button.removeAttribute('href');
                button.onclick = function(e) {
                    return handleDisabledButtonClick(e, 'Không thể in vé sau khi suất chiếu đã bắt đầu.\nSuất chiếu: ' + showtimeStart.toLocaleString('vi-VN'));
                };
                return;
            }
            
            // Kiểm tra nếu còn ít hơn 1 tiếng trước suất chiếu
            const oneHourBeforeShowtime = new Date(showtimeStart.getTime() - (60 * 60 * 1000));
            if (currentTime <= oneHourBeforeShowtime) {
                button.disabled = true;
                button.classList.remove('btn-primary', 'btn-secondary');
                button.classList.add('btn-warning');
                button.title = 'Vé chỉ có thể in trước suất chiếu ít nhất 1 tiếng. Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN');
                button.removeAttribute('href');
                button.onclick = function(e) {
                    return handleDisabledButtonClick(e, 'Vé chỉ có thể in trước suất chiếu ít nhất 1 tiếng.\nSuất chiếu: ' + showtimeStart.toLocaleString('vi-VN'));
                };
                return;
            }
            
            // Nếu trong khoảng thời gian cho phép in vé
            if (button.disabled) {
                button.disabled = false;
                button.classList.remove('btn-secondary', 'btn-warning');
                button.classList.add('btn-primary');
                button.title = 'In vé (Suất chiếu: ' + showtimeStart.toLocaleString('vi-VN') + ')';
                // Khôi phục href gốc
                if (button.dataset.originalHref) {
                    button.setAttribute('href', button.dataset.originalHref);
                }
                button.onclick = null; // Xóa event handler cũ
            }
        });
    }, 60000); // Cập nhật mỗi phút
});
</script>
@endpush
