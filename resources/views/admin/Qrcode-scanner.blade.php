@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header text-white py-4 px-4 bg-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="card-title mb-1 fw-bold">
                                <i class="bi bi-qr-code-scan me-2" style="font-size: 1.3em;"></i>
                                🎫 Quét QR Code Vé Xem Phim
                            </h3>
                            <p class="mb-0 opacity-90">Quét mã QR hoặc nhập mã vé để kiểm tra và kích hoạt vé</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-film display-4 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header border-0 py-3 px-4" style="background: transparent;">
                    <h5 class="card-title mb-0 fw-semibold text-dark">
                        <i class="bi bi-search me-2"></i>Nhập thông tin vé
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Form input -->
                    <div class="mb-4">
                        <label for="codeInput" class="form-label fw-semibold fs-6 text-dark mb-3">
                            <i class="bi bi-keyboard me-2"></i>Nhập mã vé (Booking Code hoặc Ticket Code) hoặc quét QR
                        </label>
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-lg-5 col-md-12">
                                <div class="position-relative">
                                    <input type="text" id="codeInput" 
                                           placeholder="VD: BK1754063915 hoặc TICKET123456" 
                                           class="form-control form-control-lg rounded-3 shadow-sm border-2"
                                           style="border-color: #d1d9e0;"
                                           autofocus autocomplete="off">
                                    <i class="bi bi-ticket-perforated position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <button onclick="scanCode()" class="btn btn-lg w-100 rounded-3 fw-medium shadow-sm" 
                                        style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none; color: white;">
                                    <i class="bi bi-search me-2"></i>Quét Vé
                                </button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <button onclick="openQrModal()" class="btn btn-lg w-100 rounded-3 fw-medium shadow-sm"
                                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: white;">
                                    <i class="bi bi-qr-code-scan me-2"></i>QR Code
                                </button>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <input type="file" id="qrImageUpload" accept="image/*" class="d-none" onchange="scanQrFromImage()">
                                <button onclick="document.getElementById('qrImageUpload').click()" 
                                        class="btn btn-lg w-100 rounded-3 fw-medium shadow-sm"
                                        style="background: linear-gradient(135deg, #6f42c1 0%, #5e35b1 100%); border: none; color: white;">
                                    <i class="bi bi-camera me-2"></i>Tải Ảnh QR
                                </button>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-2">
                            <button onclick="checkStatus()" class="btn btn-outline-primary rounded-3 fw-medium">
                                <i class="bi bi-info-circle me-2"></i>Kiểm tra trạng thái vé
                            </button>
                            <button onclick="clearAll()" class="btn btn-outline-secondary rounded-3 fw-medium">
                                <i class="bi bi-eraser me-2"></i>Xóa kết quả
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Section - Separated -->
    <div id="result" class="d-none">
        <div class="row justify-content-center mt-4">
            <div class="col-xl-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header text-white py-3 bg-primary">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                                <div>
                                    <h5 class="mb-0 fw-bold">🎫 Kết Quả Quét Vé</h5>
                                    <small class="opacity-90">Thông tin chi tiết về vé và đơn hàng</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light btn-sm rounded-3" onclick="printTickets()" id="printButton" style="display: none;">
                                    <i class="bi bi-printer me-1"></i>In Vé
                                </button>
                                <button type="button" class="btn btn-success rounded-3" onclick="clearAll()">
                                    <i class="bi bi-x-lg me-1"></i>Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="resultContent">
                            <!-- Content will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error notification -->
    <div id="error" class="d-none">
        <div class="row justify-content-center mt-4">
            <div class="col-xl-10">
                <div class="alert border-0 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b; border-left: 4px solid #dc2626;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1 fw-bold">Có lỗi xảy ra</h6>
                            <div id="errorContent" class="mb-0"></div>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm ms-auto" onclick="clearAll()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- QR Modal -->
    <div id="qrModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 text-white py-3 px-4" style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-camera-fill"></i>
                        <h4 class="modal-title fw-bold mb-0">Quét QR Code</h4>
                    </div>
                    <button onclick="closeQrModal()" class="btn-close btn-close-white" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="qr-reader" class="rounded-3 mx-auto d-flex align-items-center justify-content-center"
                        style="width: 100%; max-width: 400px; height: 400px; background: #ffffff; border: 3px solid #4f46e5;">
                        <div class="text-center text-muted">
                            <i class="bi bi-camera-fill fs-1 mb-2 d-block"></i>
                            <span>Đang khởi động camera...</span>
                        </div>
                    </div>
                    <p class="mt-3 text-center text-muted small">
                        Đưa mã QR vào khung camera để quét tự động.<br>
                        <span class="badge text-dark" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                            <i class="bi bi-shield-check me-1"></i>Bảo mật & nhanh chóng
                        </span>
                    </p>
                    <div class="text-center mt-3">
                        <button onclick="closeQrModal()" class="btn btn-outline-secondary rounded-3">
                            <i class="bi bi-x-lg me-2"></i>Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="position-fixed top-0 start-50 translate-middle-x z-3 px-4 py-3 rounded-4 text-white fw-medium shadow-lg d-none" style="margin-top: 2rem;"></div>
</div>

<!-- Font Awesome & Animate.css for icons and animation -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
    /* .bg-gradient-primary {
        background: linear-gradient(90deg, #007bff, #0056b3);
    } */
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
    /* #result .card-header {
        border-bottom: 1px solid rgba(255,255,255,0.2);
        background: linear-gradient(90deg, #007bff, #0056b3) !important;
    } */
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
        
        // Phân biệt các loại mã
        let isBookingCode = /^BK\d{6,}$/.test(code) || /^\d{8,}$/.test(code);
        let isFoodId = /^\d{1,5}$/.test(code) && !isBookingCode; // Số nguyên 1-5 ký tự
        
        // Debug log
        console.log('Code:', code);
        console.log('Code length:', code.length);
        console.log('Is numeric:', /^\d+$/.test(code));
        console.log('8+ digits test:', /^\d{8,}$/.test(code));
        console.log('1-5 digits test:', /^\d{1,5}$/.test(code));
        console.log('isBookingCode:', isBookingCode);
        console.log('isFoodId:', isFoodId);
        
        // Đảm bảo logic đúng: nếu là số 1-5 ký tự thì chắc chắn là food
        if (/^\d{1,5}$/.test(code) && !/^\d{8,}$/.test(code)) {
            isFoodId = true;
            isBookingCode = false;
        }
        
        let url, data;
        if (isBookingCode) {
            url = '/admin/api/qr/scan';
            data = { booking_code: code };
        } else if (isFoodId) {
            url = '/admin/api/qr/scan-food';
            data = { booking_item_id: code };
        } else {
            url = '/admin/api/qr/scan-ticket';
            data = { ticket_code: code };
        }
        
        console.log('Final URL:', url);
        console.log('Final Data:', data);

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    if (isBookingCode) {
                        showSuccess(response);
                    } else if (isFoodId) {
                        showFoodResult(response);
                    } else {
                        showTicketResultByTicketCode(response);
                    }
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
        const tickets = response.data.updated_tickets || [];
        const bookingItems = response.data.booking_items || [];
        
        let html = `
            <!-- Success Banner -->
            <div class="bg-success text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-2 me-3"></i>
                        <div>
                            <h4 class="mb-1 fw-bold">${response.message}</h4>
                            <p class="mb-0 opacity-90">Vé đã được xác nhận và kích hoạt thành công</p>
                        </div>
                    </div>
                    <div class="badge bg-light text-success fs-6 px-3 py-2">
                        <i class="bi bi-calendar-check me-1"></i>
                        ${new Date().toLocaleString('vi-VN')}
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="p-4 border-bottom" style="background: #ffffff;">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="text-dark fw-bold mb-3">
                            <i class="bi bi-receipt-cutoff me-2 text-primary"></i>📋 Thông Tin Đơn Hàng
                        </h5>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-hash text-muted me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Mã booking</small>
                                        <span class="fw-semibold text-primary">${booking.booking_code}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-fill text-muted me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Khách hàng</small>
                                        <span class="fw-semibold">${booking.customer_name || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone-fill text-muted me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Số điện thoại</small>
                                        <span class="fw-semibold">${booking.customer_phone || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-cash-coin text-muted me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Tổng tiền</small>
                                        <span class="fw-bold text-success fs-5">${formatCurrency(booking.total_amount || 0)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bg-white rounded-3 p-3 shadow-sm border">
                            <i class="bi bi-film display-4 text-primary mb-2"></i>
                            <h6 class="text-dark mb-0">Vé Xem Phim</h6>
                            <small class="text-muted">Đã xác nhận</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Movie Information -->
            <div class="p-4 border-bottom" style="background: #ffffff;">
                <h5 class="text-dark fw-bold mb-3">
                    <i class="bi bi-camera-reels me-2 text-warning"></i>🎬 Thông Tin Phim
                </h5>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-film text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Phim</small>
                                <span class="fw-semibold">${booking.movie?.name || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-building text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Rạp</small>
                                <span class="fw-semibold">${booking.cinema?.name || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-event text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ngày chiếu</small>
                                <span class="fw-semibold">${booking.showtime?.start_date ? new Date(booking.showtime.start_date).toLocaleDateString('vi-VN') : 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Giờ chiếu</small>
                                <span class="fw-semibold">${booking.showtime?.start_time || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets Section -->
            <div class="p-4 border-bottom">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="text-dark fw-bold mb-0">
                        <i class="bi bi-ticket-perforated me-2 text-success"></i>🎫 Danh Sách Vé (${tickets.length})
                    </h5>
                    <span class="badge bg-success-subtle text-success px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i>Đã xác nhận
                    </span>
                </div>
                <div class="row g-3">
        `;
        
        tickets.forEach(ticket => {
            const seatName = ticket.seat ? (ticket.seat.row_char + ticket.seat.seat_number) : 'N/A';
            const seatType = ticket.seat?.seat_type || ticket.getSeatType || 'Thường';
            html += `
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left: 4px solid #10b981 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="card-title text-success fw-bold mb-1">
                                        <i class="bi bi-geo-alt-fill me-1"></i>Ghế ${seatName}
                                    </h6>
                                    <span class="badge bg-success text-white small">
                                        <i class="bi bi-star-fill me-1"></i>${seatType}
                                    </span>
                                </div>
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </div>
                            <div class="small text-muted">
                                <div class="d-flex justify-content-between">
                                    <span>ID vé:</span>
                                    <span class="fw-medium">${ticket.id}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Quét lúc:</span>
                                    <span class="fw-medium">${new Date(ticket.used_at).toLocaleString('vi-VN')}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += `</div></div>`;
        
        // Add food items if any
        if (bookingItems && bookingItems.length > 0) {
            html += `
                <!-- Food Items Section -->
                <div class="p-4">
                    <h5 class="text-dark fw-bold mb-3">
                        <i class="bi bi-cup-straw me-2 text-warning"></i>🍿 Đồ Ăn & Thức Uống (${bookingItems.length})
                    </h5>
                    <div class="row g-3">
            `;
            
            bookingItems.forEach(item => {
                html += `
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); border-left: 4px solid #f59e0b !important;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="card-title text-warning fw-bold mb-1">
                                            <i class="bi bi-box me-1"></i>${item.product_variant?.product?.name || 'N/A'}
                                        </h6>
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge bg-warning text-dark small">
                                                <i class="bi bi-cup me-1"></i>Size ${item.product_variant?.size || 'N/A'}
                                            </span>
                                            <span class="badge ${getProductStatusClass(item.product_status)} small">
                                                ${getProductStatusText(item.product_status)}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="badge bg-info text-white">x${item.quantity}</span>
                                </div>
                                <div class="small text-muted">
                                    <div class="d-flex justify-content-between">
                                        <span>Đơn giá:</span>
                                        <span class="fw-medium">${formatCurrency(item.price || 0)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Thành tiền:</span>
                                        <span class="fw-bold text-warning">${formatCurrency((item.price || 0) * (item.quantity || 1))}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += `</div></div>`;
        }
        
        $('#result').removeClass('d-none').find('#resultContent').html(html);
        $('#printButton').show();
        
        // Store booking data for printing
        window.currentBookingData = response.data;
    }

    function showTicketStatus(data) {
        let html = `
            <!-- Status Banner -->
            <div class="text-white p-4 bg-primary">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill fs-2 me-3"></i>
                    <div>
                        <h4 class="mb-1 fw-bold">Thông Tin Trạng Thái Vé</h4>
                        <p class="mb-0 opacity-90">Chi tiết về đơn hàng và tình trạng các vé</p>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="p-4 border-bottom" style="background: #ffffff;">
                <h5 class="text-dark fw-bold mb-3">
                    <i class="bi bi-receipt-cutoff me-2 text-primary"></i>📋 Thông Tin Đơn Hàng
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-hash text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Mã booking</small>
                                <span class="fw-semibold text-primary">${data.booking_code}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-circle-fill text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Trạng thái đơn</small>
                                <span class="badge ${getStatusClass(data.booking_status)} px-3 py-2">${getStatusText(data.booking_status)}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-fill text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Khách hàng</small>
                                <span class="fw-semibold">${data.customer_name}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone-fill text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Số điện thoại</small>
                                <span class="fw-semibold">${data.customer_phone}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cash-coin text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Tổng tiền</small>
                                <span class="fw-bold text-success fs-5">${formatCurrency(data.total_amount)}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-event text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ngày đặt</small>
                                <span class="fw-semibold">${new Date(data.booking_date).toLocaleString('vi-VN')}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Movie Information -->
            <div class="p-4 border-bottom" style="background: #ffffff;">
                <h5 class="text-dark fw-bold mb-3">
                    <i class="bi bi-camera-reels me-2 text-warning"></i>🎬 Thông Tin Phim
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-film text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Phim</small>
                                <span class="fw-semibold">${data.showtime.movie_name}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-building text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Rạp</small>
                                <span class="fw-semibold">${data.showtime.cinema_name}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-event text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ngày chiếu</small>
                                <span class="fw-semibold">${data.showtime.show_date}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock text-muted me-2"></i>
                            <div>
                                <small class="text-muted d-block">Giờ chiếu</small>
                                <span class="fw-semibold">${data.showtime.start_time}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets Section -->
            <div class="p-4">
                <h5 class="text-dark fw-bold mb-3">
                    <i class="bi bi-ticket-perforated me-2 text-info"></i>🎫 Danh Sách Vé (${data.tickets.length})
                </h5>
                <div class="row g-3">
        `;
        
        data.tickets.forEach(ticket => {
            const isUsed = ticket.ticket_status === 'used';
            const cardStyle = isUsed 
                ? 'background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #dc2626 !important;'
                : 'background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-left: 4px solid #3b82f6 !important;';
            const iconClass = isUsed ? 'bi-x-circle-fill text-danger' : 'bi-clock-fill text-primary';
            
            html += `
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="${cardStyle}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="card-title fw-bold mb-1 ${isUsed ? 'text-danger' : 'text-primary'}">
                                        <i class="bi bi-geo-alt-fill me-1"></i>Ghế ${ticket.seat_name}
                                    </h6>
                                    <span class="badge ${isUsed ? 'bg-danger' : 'bg-primary'} text-white small">
                                        ${isUsed ? 'Đã sử dụng' : 'Chưa sử dụng'}
                                    </span>
                                </div>
                                <i class="bi ${iconClass} fs-4"></i>
                            </div>
                            <div class="small text-muted">
                                <div class="d-flex justify-content-between">
                                    <span>ID vé:</span>
                                    <span class="fw-medium">${ticket.id}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Giá vé:</span>
                                    <span class="fw-medium">${formatCurrency(ticket.price)}</span>
                                </div>
                                ${ticket.used_at ? `
                                <div class="d-flex justify-content-between">
                                    <span>Quét lúc:</span>
                                    <span class="fw-medium">${new Date(ticket.used_at).toLocaleString('vi-VN')}</span>
                                </div>
                                ` : ''}
                            </div>
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
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success btn-lg" onclick="printTickets('${data?.booking_code || ''}')">
                    <i class="fas fa-print"></i> In Vé
                </button>
            </div>
        `;
        $('#result').removeClass('d-none').find('#resultContent').html(html);
    }

    function showFoodResult(response) {
        const data = response.data;
        let html = `
            <div class="alert alert-success rounded-3 shadow-sm mb-4 text-center animate__animated animate__fadeInDown">
                <h3 class="h5 fw-bold mb-0"><i class="fa-solid fa-circle-check"></i> ${response.message}</h3>
            </div>
            <div class="row g-3">
                <div class="col-md-8 mx-auto">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-warning text-dark text-center fw-medium d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-utensils"></i>
                            <h5 class="mb-0">Thông Tin Đồ Ăn/Uống</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6"><strong>Tên sản phẩm:</strong></div>
                                <div class="col-6">${data?.product_name || ''}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Số lượng:</strong></div>
                                <div class="col-6">${data?.quantity || ''}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Giá:</strong></div>
                                <div class="col-6">${formatCurrency(data?.price || 0)}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Trạng thái:</strong></div>
                                <div class="col-6">
                                    <span class="badge ${getProductStatusClass(data?.product_status)} px-2 py-1">
                                        ${getProductStatusText(data?.product_status)}
                                    </span>
                                </div>
                            </div>
                            ${data?.used_at ? `
                            <div class="row mb-2">
                                <div class="col-6"><strong>Thời gian sử dụng:</strong></div>
                                <div class="col-6">${new Date(data.used_at).toLocaleString('vi-VN')}</div>
                            </div>
                            ` : ''}
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

    // Product status helpers for food items
    function getProductStatusClass(status) {
        return {
            'valid': 'bg-success',
            'checked': 'bg-warning',
            'used': 'bg-secondary',
            'cancelled': 'bg-danger'
        }[status] || 'bg-secondary';
    }

    function getProductStatusText(status) {
        return {
            'valid': 'Chưa sử dụng',
            'checked': 'Đã kiểm tra', 
            'used': 'Đã sử dụng',
            'cancelled': 'Hết hạn'
        }[status] || status;
    }

    $('#codeInput').keypress(function(e) {
        if (e.which == 13) scanCode();
    });

    // Clean up QR scanner when modal is closed by any means
    $('#qrModal').on('hidden.bs.modal', function () {
        if (window.html5QrCode) {
            window.html5QrCode.stop().catch(() => {}).finally(() => {
                window.html5QrCode = null;
            });
        }
    });

    function showToast(message, color = 'bg-success') {
        const toast = $('#toast');
        toast.removeClass().addClass(`position-fixed top-0 start-50 translate-middle-x z-50 px-4 py-3 rounded text-white fw-medium shadow-lg ${color} animate__animated animate__fadeInDown`)
            .text(message).fadeIn(200);
        setTimeout(() => toast.fadeOut(400), 1800);
    }

    function openQrModal() {
        $('#qrModal').modal('show');
        
        // Check camera permissions first
        checkCameraPermissions().then(() => {
            // Reset and initialize QR scanner
            if (window.html5QrCode) {
                window.html5QrCode.stop().catch(() => {}).then(() => {
                    initializeQrScanner();
                });
            } else {
                initializeQrScanner();
            }
        }).catch((error) => {
            console.error('Camera permission denied:', error);
            showToast('Vui lòng cấp quyền truy cập camera để sử dụng tính năng quét QR', 'bg-warning');
        });
    }

    async function checkCameraPermissions() {
        try {
            // Request camera permissions
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            // Stop the stream immediately, we just need to check permissions
            stream.getTracks().forEach(track => track.stop());
            return Promise.resolve();
        } catch (error) {
            return Promise.reject(error);
        }
    }

    function initializeQrScanner() {
        try {
            window.html5QrCode = new Html5Qrcode("qr-reader");
            
            // Configuration for better QR detection
            const config = {
                fps: 15,
                qrbox: { width: 300, height: 300 },
                aspectRatio: 1.0,
                disableFlip: false
            };

            // Try to use back camera first
            const constraints = {
                facingMode: "environment" // Back camera
            };

            window.html5QrCode.start(
                constraints,
                config,
                (qrCodeMessage) => {
                    showToast('Đã quét QR thành công!');
                    $('#codeInput').val(qrCodeMessage);
                    setTimeout(() => {
                        closeQrModal();
                        scanCode();
                    }, 900);
                },
                (errorMessage) => {
                    // Optional: Handle scan errors silently
                    console.log('QR scan error:', errorMessage);
                }
            ).catch((err) => {
                console.error('Failed to start QR scanner:', err);
                
                // Fallback: Try with different constraints
                const fallbackConstraints = { facingMode: "user" }; // Front camera
                
                window.html5QrCode.start(
                    fallbackConstraints,
                    config,
                    (qrCodeMessage) => {
                        showToast('Đã quét QR thành công!');
                        $('#codeInput').val(qrCodeMessage);
                        setTimeout(() => {
                            closeQrModal();
                            scanCode();
                        }, 900);
                    },
                    (errorMessage) => {
                        console.log('QR scan error:', errorMessage);
                    }
                ).catch((fallbackErr) => {
                    console.error('Failed to start QR scanner with fallback:', fallbackErr);
                    showToast('Không thể khởi động camera. Vui lòng kiểm tra quyền truy cập camera.', 'bg-danger');
                });
            });
            
        } catch (error) {
            console.error('Error initializing QR scanner:', error);
            showToast('Lỗi khởi tạo scanner QR', 'bg-danger');
        }
    }

    function closeQrModal() {
        $('#qrModal').modal('hide');
        if (window.html5QrCode) {
            window.html5QrCode.stop().catch((err) => {
                console.log('Error stopping QR scanner:', err);
            }).finally(() => {
                // Clear the scanner instance
                window.html5QrCode = null;
            });
        }
    }

    // Print tickets function
    function printTickets(bookingCode = null) {
        let code = bookingCode;
        
        // If no booking code provided, try to get from input or stored data
        if (!code) {
            code = $('#codeInput').val().trim();
        }
        
        // If still no code, try to get from stored booking data
        if (!code && window.currentBookingData) {
            code = window.currentBookingData.booking_code;
        }
        
        if (!code) {
            showError('Không tìm thấy mã vé hoặc booking code để in');
            return;
        }
        
        // Show loading toast
        showToast('Đang tải trang in vé...', 'bg-info');
        
        // Open print page in new window
        const printUrl = `/admin/print-tickets/${encodeURIComponent(code)}`;
        
        try {
            const printWindow = window.open(printUrl, '_blank');
            
            // Check if popup was blocked
            if (!printWindow) {
                showToast('Popup bị chặn! Vui lòng cho phép popup và thử lại.', 'bg-warning');
                return;
            }
            
            // Optional: Check if window opened successfully after a delay
            setTimeout(() => {
                if (printWindow.closed) {
                    // Window was closed immediately, might be an error
                    console.log('Print window was closed immediately');
                } else {
                    showToast('Trang in vé đã được mở thành công', 'bg-success');
                }
            }, 1000);
            
        } catch (error) {
            console.error('Error opening print window:', error);
            showToast('Lỗi khi mở trang in vé: ' + error.message, 'bg-danger');
        }
    }

    // Store booking data for print
    window.currentBookingData = null;
</script>
@endsection

@section('styles')
<style>
/* Enhanced styling to match movies index page */
.container-fluid {
    background: transparent;
    min-height: 100vh;
    padding: 2rem 1rem;
}

/* Card enhancements */
.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Form controls */
.form-control-lg {
    border-radius: 12px;
    border: 2px solid #d1d9e0;
    transition: all 0.2s ease;
    font-size: 1rem;
    padding: 0.75rem 1rem;
}

.form-control-lg:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

/* Button enhancements */
.btn {
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

/* Alert styling */
.alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

/* Modal enhancements */
.modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

/* QR Reader styling */
#qr-reader {
    transition: all 0.3s ease;
}

#qr-reader:hover {
    transform: scale(1.02);
}

/* Toast styling */
#toast {
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 8px;
    font-weight: 500;
}

/* Responsive design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem 0.5rem;
    }
    
    .card-header h3 {
        font-size: 1.25rem;
    }
    
    .btn-lg {
        font-size: 0.9rem;
        padding: 0.6rem 1rem;
    }
    
    .form-control-lg {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .card-header {
        padding: 1rem !important;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    .btn-lg {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
    
    .form-control-lg {
        font-size: 0.85rem;
    }
}

/* Loading animation */
@keyframes shimmer {
    0% {
        background-position: -468px 0;
    }
    100% {
        background-position: 468px 0;
    }
}

.loading {
    animation-duration: 1.5s;
    animation-fill-mode: forwards;
    animation-iteration-count: infinite;
    animation-name: shimmer;
    animation-timing-function: linear;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 1000px 100%;
}

/* Success animation */
@keyframes successPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
}

.success-animation {
    animation: successPulse 2s infinite;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #4f46e5, #10b981);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #4338ca, #059669);
}
</style>
@endsection