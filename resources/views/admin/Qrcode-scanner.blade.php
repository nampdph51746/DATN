@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-qrcode fa-2x"></i>
                        <h3 class="card-title mb-0 fw-bold">Quét QR Code Vé Xem Phim</h3>
                    </div>
                </div>
                <div class="card-body p-4 bg-light">
                    <!-- Form input -->
                    <div class="mb-4">
                        <label for="codeInput" class="form-label fw-semibold fs-5 text-dark">
                            <i class="fa-solid fa-ticket"></i> Nhập mã vé (Booking Code hoặc Ticket Code) hoặc quét QR
                        </label>
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-lg-5 col-md-12">
                                <input type="text" id="codeInput" placeholder="VD: BK1754063915 hoặc TICKET123456"
                                    class="form-control form-control-lg rounded-3 shadow-sm border-primary"
                                    autofocus autocomplete="off">
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <button onclick="scanCode()" class="btn btn-primary w-100 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass"></i> Quét Vé
                                </button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <button onclick="openQrModal()" class="btn btn-success w-100 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-camera"></i> Quét QR
                                </button>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <input type="file" id="qrImageUpload" accept="image/*" class="d-none" onchange="scanQrFromImage()">
                                <button onclick="document.getElementById('qrImageUpload').click()" class="btn btn-purple w-100 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-image"></i> Tải Ảnh QR
                                </button>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-2">
                            <button onclick="checkStatus()" class="btn btn-outline-info rounded-3 fw-medium d-flex align-items-center gap-2">
                                <i class="fa-solid fa-circle-info"></i> Kiểm tra trạng thái vé
                            </button>
                            <button onclick="clearAll()" class="btn btn-outline-secondary rounded-3 fw-medium d-flex align-items-center gap-2">
                                <i class="fa-solid fa-eraser"></i> Xóa kết quả
                            </button>
                        </div>
                    </div>

                    <!-- Result section -->
                    <div id="result" class="d-none mt-4">
                        <div class="card border-0 shadow-sm rounded-3 animate__animated animate__fadeIn">
                            <div class="card-header bg-primary text-white py-3 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-check-circle"></i>
                                <h5 class="mb-0 fw-bold text-center flex-grow-1">🎫 Kết Quả Quét Vé</h5>
                            </div>
                            <div class="card-body">
                                <div id="resultContent">
                                    <!-- Content will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Error notification -->
                    <div id="error" class="d-none alert alert-danger mt-4 rounded-3 shadow-sm animate__animated animate__shakeX">
                        <div id="errorContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Modal -->
    <div id="qrModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 bg-gradient-primary text-white py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-camera-retro"></i>
                        <h4 class="modal-title fw-bold mb-0">Quét QR Code</h4>
                    </div>
                    <button onclick="closeQrModal()" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="qr-reader" class="border-2 border-primary rounded-3 mx-auto d-flex align-items-center justify-content-center"
                        style="width: 100%; max-width: 400px; height: 400px; background: #f8f9fa;">
                        <span class="text-muted">Camera sẽ hiển thị tại đây...</span>
                    </div>
                    <p class="mt-3 text-center text-muted small">
                        Đưa mã QR vào khung camera để quét tự động.<br>
                        <span class="badge bg-info text-dark">Bảo mật & nhanh chóng</span>
                    </p>
                    <div class="text-center mt-3">
                        <button onclick="closeQrModal()" class="btn btn-secondary">
                            <i class="fa-solid fa-xmark"></i> Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="position-fixed top-0 start-50 translate-middle-x z-50 px-4 py-3 rounded text-white fw-medium shadow-lg d-none"></div>
</div>

<!-- Font Awesome & Animate.css for icons and animation -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #007bff, #0056b3);
    }
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-purple:hover {
        background-color: #5e35b1;
        border-color: #5e35b1;
        color: white;
        transform: translateY(-2px);
    }
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    #result {
        width: 100%;
        margin-top: 1rem;
    }
    #result .card {
        border: 1px solid #e9ecef;
        width: 100%;
        min-width: 100%;
    }
    #result .card-header {
        border-bottom: 1px solid rgba(255,255,255,0.2);
        background: linear-gradient(90deg, #007bff, #0056b3) !important;
    }
    #resultContent {
        min-height: 100px;
        padding: 1.5rem;
        width: 100%;
    }
    #result .card-body {
        padding: 0;
        width: 100%;
    }
    .loading-content {
        padding: 2rem;
        text-align: center;
        width: 100%;
    }
    #resultContent .card {
        margin-bottom: 1rem;
        width: 100%;
    }
    #resultContent .row {
        margin: 0;
        width: 100%;
    }
    .col-xl-8 {
        width: 100%;
        max-width: 900px;
    }
    .alert {
        border-radius: 0.5rem;
        padding: 1.25rem;
        font-size: 1.1rem;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }
    .modal-content {
        border-radius: 0.75rem !important;
    }
    #qr-reader {
        background: #f8f9fa;
    }
    #toast {
        min-width: 250px;
        transition: opacity 0.3s ease;
        z-index: 9999;
        font-size: 1.1rem;
    }
    @media (max-width: 768px) {
        .col-lg-2, .col-md-4 {
            margin-bottom: 10px;
        }
        .card-body {
            padding: 1.5rem !important;
        }
        .btn {
            font-size: 0.9rem;
            padding: 0.6rem 1rem;
        }
        #qr-reader {
            width: 100% !important;
            height: 300px !important;
        }
    }
    .btn-purple:focus,
    .btn-purple:active {
        background-color: #5e35b1 !important;
        border-color: #5e35b1 !important;
        color: white !important;
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
    }
</style>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function scanCode() {
        const code = $('#codeInput').val().trim();
        if (!code) {
            showError('Vui lòng nhập mã vé (booking code hoặc ticket code)');
            return;
        }
        hideMessages();
        $('#result').removeClass('d-none').find('#resultContent').html(
            '<div class="loading-content"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted">Đang xử lý...</div></div>'
        );
        let isBookingCode = /^BK\d{6,}$/.test(code) || /^\d{8,}$/.test(code);
        let url = isBookingCode ? '/admin/api/qr/scan' : '/admin/api/qr/scan-ticket';
        let data = isBookingCode ? { booking_code: code } : { ticket_code: code };

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    isBookingCode ? showSuccess(response) : showTicketResultByTicketCode(response);
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                showError(xhr.responseJSON?.message || 'Có lỗi xảy ra khi quét vé');
            }
        });
    }

    function checkStatus() {
        const bookingCode = $('#codeInput').val().trim();
        if (!bookingCode) {
            showError('Vui lòng nhập mã booking');
            return;
        }
        hideMessages();
        $('#result').removeClass('d-none').find('#resultContent').html(
            '<div class="loading-content"><div class="spinner-border text-info" role="status"></div><div class="mt-2 text-muted">Đang kiểm tra trạng thái...</div></div>'
        );
        $.ajax({
            url: '/admin/api/qr/check-status',
            method: 'POST',
            data: { booking_code: bookingCode },
            success: function(response) {
                if (response.success) {
                    showTicketStatus(response.data);
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                showError(xhr.responseJSON?.message || 'Có lỗi xảy ra khi kiểm tra trạng thái');
            }
        });
    }

    function scanQrFromImage() {
        const fileInput = document.getElementById('qrImageUpload');
        const file = fileInput.files[0];
        if (!file) {
            showError('Vui lòng chọn một ảnh QR để quét');
            return;
        }
        hideMessages();
        $('#result').removeClass('d-none').find('#resultContent').html(
            '<div class="loading-content"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted">Đang xử lý ảnh QR...</div></div>'
        );
        const html5QrCode = new Html5Qrcode("qr-reader");
        html5QrCode.scanFile(file, true)
            .then(qrCodeMessage => {
                showToast('Đã quét ảnh QR thành công!');
                $('#codeInput').val(qrCodeMessage);
                setTimeout(() => scanCode(), 900);
            })
            .catch(() => showError('Không thể quét mã QR từ ảnh. Vui lòng thử lại.'))
            .finally(() => fileInput.value = '');
    }

    function showSuccess(response) {
        const booking = response.data.booking;
        const tickets = response.data.updated_tickets;
        let html = `
            <div class="alert alert-success rounded-3 shadow-sm mb-4 animate__animated animate__fadeInDown">
                <h4 class="fw-bold mb-2"><i class="fa-solid fa-circle-check"></i> ${response.message}</h4>
            </div>
            <div class="card border-0 shadow-sm mb-4 rounded-3">
                <div class="card-header bg-info text-white d-flex align-items-center gap-2">
                    <i class="fa-solid fa-file-invoice"></i>
                    <h5 class="mb-0 fw-bold">📋 Thông Tin Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Mã booking:</strong> <span class="text-primary">${booking.booking_code}</span></div>
                        <div class="col-md-6"><strong>Khách hàng:</strong> ${booking.customer_name || 'N/A'}</div>
                        <div class="col-md-6"><strong>Số điện thoại:</strong> ${booking.customer_phone || 'N/A'}</div>
                        <div class="col-md-6"><strong>Tổng tiền:</strong> <span class="text-success fw-bold">${formatCurrency(booking.total_amount || 0)}</span></div>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-success text-white d-flex align-items-center gap-2">
                    <i class="fa-solid fa-ticket"></i>
                    <h5 class="mb-0 fw-bold">🎫 Vé Đã Quét (${tickets.length})</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
        `;
        tickets.forEach(ticket => {
            const seatName = ticket.seat ? (ticket.seat.row_char + ticket.seat.seat_number) : 'N/A';
            html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-success shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title text-success"><i class="fa-solid fa-chair"></i> Ghế ${seatName}</h6>
                                    <span class="badge bg-success"><i class="fa-solid fa-check"></i> Đã sử dụng</span>
                                </div>
                                <p class="card-text mb-1"><small class="text-muted">ID: ${ticket.id}</small></p>
                                <p class="card-text mb-0"><small class="text-muted">Quét lúc: ${new Date(ticket.used_at).toLocaleString('vi-VN')}</small></p>
                            </div>
                        </div>
                    </div>
            `;
        });
        html += `
                    </div>
                </div>
            </div>
        `;
        $('#result').removeClass('d-none').find('#resultContent').html(html);
    }

    function showTicketStatus(data) {
        let html = `
            <div class="card border-0 shadow-sm mb-4 rounded-3">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-dark mb-3"><i class="fa-solid fa-file-invoice"></i> Thông Tin Đơn Hàng</h3>
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Mã booking:</strong> ${data.booking_code}</div>
                        <div class="col-md-6"><strong>Trạng thái đơn:</strong> <span class="badge ${getStatusClass(data.booking_status)}">${getStatusText(data.booking_status)}</span></div>
                        <div class="col-md-6"><strong>Khách hàng:</strong> ${data.customer_name}</div>
                        <div class="col-md-6"><strong>Số điện thoại:</strong> ${data.customer_phone}</div>
                        <div class="col-md-6"><strong>Tổng tiền:</strong> ${formatCurrency(data.total_amount)}</div>
                        <div class="col-md-6"><strong>Ngày đặt:</strong> ${new Date(data.booking_date).toLocaleString('vi-VN')}</div>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-4 rounded-3">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-dark mb-3"><i class="fa-solid fa-film"></i> Thông Tin Phim</h3>
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Phim:</strong> ${data.showtime.movie_name}</div>
                        <div class="col-md-6"><strong>Rạp:</strong> ${data.showtime.cinema_name}</div>
                        <div class="col-md-6"><strong>Ngày chiếu:</strong> ${data.showtime.show_date}</div>
                        <div class="col-md-6"><strong>Giờ chiếu:</strong> ${data.showtime.start_time}</div>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="h5 fw-bold text-dark mb-3"><i class="fa-solid fa-ticket"></i> Danh Sách Vé (${data.tickets.length})</h3>
                <div class="row g-3">
        `;
        data.tickets.forEach(ticket => {
            const isUsed = ticket.ticket_status === 'used';
            html += `
                <div class="col-md-6">
                    <div class="card ${isUsed ? 'border-danger' : 'border-success'} shadow-sm rounded-3">
                        <div class="card-body">
                            <p class="mb-2"><strong>ID:</strong> ${ticket.id}</p>
                            <p class="mb-2"><strong>Ghế:</strong> ${ticket.seat_name}</p>
                            <p class="mb-2"><strong>Giá:</strong> ${formatCurrency(ticket.price)}</p>
                            <p class="mb-2"><strong>Trạng thái:</strong> <span class="badge ${isUsed ? 'bg-danger' : 'bg-success'}">${isUsed ? 'Đã sử dụng' : 'Chưa sử dụng'}</span></p>
                            ${ticket.used_at ? `<p class="mb-0"><strong>Thời gian sử dụng:</strong> ${new Date(ticket.used_at).toLocaleString('vi-VN')}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        html += `</div></div>`;
        $('#result').removeClass('d-none').find('#resultContent').html(html);
    }

    function showTicketResultByTicketCode(response) {
        const data = response.data;
        let html = `
            <div class="alert alert-success rounded-3 shadow-sm mb-4 text-center animate__animated animate__fadeInDown">
                <h3 class="h5 fw-bold mb-0"><i class="fa-solid fa-circle-check"></i> ${response.message}</h3>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-primary text-white text-center fw-medium d-flex align-items-center gap-2">
                            <i class="fa-solid fa-ticket"></i>
                            <h5 class="mb-0">Thông Tin Vé</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6"><strong>Mã vé:</strong></div>
                                <div class="col-6">${data?.ticket_code || ''}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Ghế:</strong></div>
                                <div class="col-6">${data?.seat_name || ''}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Trạng thái:</strong></div>
                                <div class="col-6"><span class="badge bg-success"><i class="fa-solid fa-check"></i> Đã sử dụng</span></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Thời gian quét:</strong></div>
                                <div class="col-6">${data?.used_at ? new Date(data.used_at).toLocaleString('vi-VN') : ''}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-info text-white text-center fw-medium d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-invoice"></i>
                            <h5 class="mb-0">Thông Tin Đơn Hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6"><strong>Mã booking:</strong></div>
                                <div class="col-6">${data?.booking_code || ''}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Khách hàng:</strong></div>
                                <div class="col-6">${data?.customer_name || ''}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Số điện thoại:</strong></div>
                                <div class="col-6">${data?.customer_phone || ''}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Tổng tiền:</strong></div>
                                <div class="col-6">${formatCurrency(data?.total_amount || 0)}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#result').removeClass('d-none').find('#resultContent').html(html);
    }

    function showError(message) {
        $('#error').removeClass('d-none').find('#errorContent').text(message);
        $('#result').addClass('d-none');
    }

    function hideMessages() {
        $('#error').addClass('d-none');
        $('#result').addClass('d-none');
    }

    function clearAll() {
        $('#codeInput').val('');
        hideMessages();
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    function getStatusClass(status) {
        return {
            'confirmed': 'bg-success',
            'pending': 'bg-warning',
            'cancelled': 'bg-danger'
        }[status] || 'bg-secondary';
    }

    function getStatusText(status) {
        return {
            'confirmed': 'Đã xác nhận',
            'pending': 'Chờ xử lý',
            'cancelled': 'Đã hủy'
        }[status] || status;
    }

    $('#codeInput').keypress(function(e) {
        if (e.which == 13) scanCode();
    });

    function showToast(message, color = 'bg-success') {
        const toast = $('#toast');
        toast.removeClass().addClass(`position-fixed top-0 start-50 translate-middle-x z-50 px-4 py-3 rounded text-white fw-medium shadow-lg ${color} animate__animated animate__fadeInDown`)
            .text(message).fadeIn(200);
        setTimeout(() => toast.fadeOut(400), 1800);
    }

    function openQrModal() {
        $('#qrModal').modal('show');
        if (!window.html5QrCode) {
            window.html5QrCode = new Html5Qrcode("qr-reader");
        }
        window.html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 },
            qrCodeMessage => {
                showToast('Đã quét QR thành công!');
                $('#codeInput').val(qrCodeMessage);
                setTimeout(() => {
                    closeQrModal();
                    scanCode();
                }, 900);
            },
            () => {}
        );
    }

    function closeQrModal() {
        $('#qrModal').modal('hide');
        if (window.html5QrCode) {
            window.html5QrCode.stop().catch(() => {});
        }
    }
</script>
@endsection