@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl py-5">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-gradient-primary text-whit        /* Responsive enhancements */
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
            #result {
                margin-top: 1.5rem;
            }
        }
        
        /* Fix positioning and spacing */
        .card-body {
            position: relative;
        }
        
        #error {
            position: relative;
            z-index: 1;
            margin-top: 2rem;
        }
        
        /* Prevent content overflow */
        .container-xxl {
            overflow-x: hidden;
        }content-between align-items-center py-3 px-4">
                        <h4 class="card-title mb-0 fw-bold">Quét QR Code Vé Xem Phim</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Form input -->
                        <div class="mb-4">
                            <label for="codeInput" class="form-label fw-semibold fs-5 text-dark">Nhập mã vé (Booking Code hoặc Ticket Code) hoặc quét QR</label>
                            <div class="row g-3 align-items-end mb-4">
                                <div class="col-lg-5 col-md-12">
                                    <input type="text" id="codeInput" placeholder="VD: BK1754063915 hoặc TICKET123456" class="form-control form-control-lg rounded-3 shadow-sm">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <button onclick="scanCode()" class="btn btn-primary w-100 rounded-3 fw-medium">Quét Vé</button>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <button onclick="openQrModal()" class="btn btn-success w-100 rounded-3 fw-medium">Quét QR</button>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-12">
                                    <input type="file" id="qrImageUpload" accept="image/*" class="d-none" onchange="scanQrFromImage()">
                                    <button onclick="document.getElementById('qrImageUpload').click()" class="btn btn-purple w-100 rounded-3 fw-medium">📷 Tải Ảnh QR</button>
                                </div>
                            </div>
                        </div>

                        <!-- Result section -->
                        <div id="result" class="d-none mt-4">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-primary text-white py-3">
                                    <h5 class="mb-0 fw-bold text-center">🎫 Kết Quả Quét Vé</h5>
                                </div>
                                <div class="card-body">
                                    <div id="resultContent">
                                        <!-- Content will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>                        <!-- Error notification -->
                        <div id="error" class="d-none alert alert-danger mt-4 rounded-3 shadow-sm">
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
                        <h4 class="modal-title fw-bold mb-0">Quét QR Code</h4>
                        <button onclick="closeQrModal()" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="qr-reader" class="border-2 border-primary rounded-3 mx-auto" style="width: 100%; max-width: 400px; height: 400px;"></div>
                        <p class="mt-3 text-center text-muted small">Đưa mã QR vào khung camera để quét tự động</p>
                        <div class="text-center mt-3">
                            <button onclick="closeQrModal()" class="btn btn-secondary">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div id="toast" class="position-fixed top-0 start-50 translate-middle-x z-50 px-4 py-3 rounded text-white fw-medium shadow-lg d-none"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
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
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }
        
        /* Enhanced ticket card styling */
        .ticket-card {
            background: linear-gradient(135deg, #f8fff9, #e8f7ef) !important;
            border: 2px solid #28a745 !important;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.2) !important;
        }
        
        /* Ensure main card doesn't overlap */
        .container-xxl > .row > .col-xl-10 > .card {
            position: relative;
            z-index: 10;
            margin-bottom: 2rem;
        }
        
        /* Success banner positioning */
        .text-center .d-inline-flex {
            animation: slideInFromTop 0.6s ease-out;
            position: relative;
            z-index: 5;
        }
        
        .ticket-holes {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            height: 2px;
            background: repeating-linear-gradient(
                to right,
                transparent 0,
                transparent 10px,
                #28a745 10px,
                #28a745 12px
            );
        }
        
        .ticket-holes .hole {
            position: absolute;
            top: -6px;
            width: 12px;
            height: 12px;
            background: #fff;
            border: 2px solid #28a745;
            border-radius: 50%;
        }
        
        .ticket-holes .hole-left {
            left: -7px;
        }
        
        .ticket-holes .hole-right {
            right: -7px;
        }
        
        /* Success banner animation */
        @keyframes slideInFromTop {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .text-center .d-inline-flex {
            animation: slideInFromTop 0.6s ease-out;
        }
        
        /* Card entrance animation */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .ticket-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* FontAwesome icons styling */
        .fas {
            transition: all 0.3s ease;
        }
        
        .card-header .fas {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        
        /* Enhanced gradient backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        
        .bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        
        /* Info boxes styling */
        .bg-light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .bg-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .bg-success-subtle {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%) !important;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        
        /* Badge enhancements */
        .badge {
            font-weight: 600;
            padding: 8px 16px;
            letter-spacing: 0.5px;
        }
        
        /* Responsive enhancements */
        /* Result section container */
        #result {
            width: 100%;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }
        
        /* Result card styling */
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
        
        /* Ensure cards don't shrink */
        #result .card-body {
            padding: 0;
            width: 100%;
        }
        
        /* Loading spinner styling */
        .loading-content {
            padding: 2rem;
            text-align: center;
            width: 100%;
        }
        
        /* Result cards spacing */
        #resultContent .card {
            margin-bottom: 1rem;
            width: 100%;
        }
        
        #resultContent .row {
            margin: 0;
            width: 100%;
        }
        
        /* Force full width */
        .col-xl-10 {
            width: 100%;
            max-width: 1200px;
        }
        .alert {
            border-radius: 0.5rem;
            padding: 1.25rem;
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
        }
        
        /* Responsive fixes */
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
        
        /* Ensure buttons have proper styling */
        .btn-purple:focus,
        .btn-purple:active {
            background-color: #5e35b1 !important;
            border-color: #5e35b1 !important;
            color: white !important;
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
        }
    </style>

    <script>
        // CSRF token for Ajax
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
            const bookingCode = $('#bookingCode').val().trim();
            if (!bookingCode) {
                showError('Vui lòng nhập mã booking');
                return;
            }
            hideMessages();
            $('#result').removeClass('d-none').find('#resultContent').html(
                '<div class="loading-content"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted">Đang kiểm tra...</div></div>'
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
            
            // Debug log
            console.log('Showing success result:', response);
            
            let html = `
                <!-- Success Banner -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center bg-success text-white px-4 py-3 rounded-pill shadow-lg">
                        <i class="fas fa-check-circle fs-4 me-3"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">${response.message}</h5>
                            <small class="opacity-75">Quét thành công ${tickets.length} vé</small>
                        </div>
                    </div>
                </div>

                <!-- Booking Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="card-header bg-gradient-primary text-white py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-receipt fs-4 me-3"></i>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Thông Tin Đơn Hàng</h5>
                                        <small class="opacity-75">Chi tiết đơn đặt vé</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column text-center p-3 bg-light rounded-3">
                                            <i class="fas fa-barcode text-primary fs-3 mb-2"></i>
                                            <small class="text-muted mb-1">Mã Booking</small>
                                            <strong class="text-primary fs-5">${booking.booking_code}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column text-center p-3 bg-light rounded-3">
                                            <i class="fas fa-user text-info fs-3 mb-2"></i>
                                            <small class="text-muted mb-1">Khách Hàng</small>
                                            <strong class="text-dark">${booking.customer_name || 'N/A'}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column text-center p-3 bg-light rounded-3">
                                            <i class="fas fa-phone text-warning fs-3 mb-2"></i>
                                            <small class="text-muted mb-1">Số Điện Thoại</small>
                                            <strong class="text-dark">${booking.customer_phone || 'N/A'}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column text-center p-3 bg-success-subtle rounded-3">
                                            <i class="fas fa-money-bill text-success fs-3 mb-2"></i>
                                            <small class="text-muted mb-1">Tổng Tiền</small>
                                            <strong class="text-success fs-5">${formatCurrency(booking.total_amount || 0)}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets Grid -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="card-header bg-success text-white py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-ticket-alt fs-4 me-3"></i>
                                        <div>
                                            <h5 class="mb-1 fw-bold">Danh Sách Vé Đã Quét</h5>
                                            <small class="opacity-75">Tất cả vé đã được kích hoạt thành công</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-success fs-6 px-3 py-2 rounded-pill">
                                        ${tickets.length} vé
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
            `;
            
            tickets.forEach((ticket, index) => {
                const seatName = ticket.seat ? (ticket.seat.row_char + ticket.seat.seat_number) : 'N/A';
                const timeFormatted = new Date(ticket.used_at).toLocaleString('vi-VN');
                
                html += `
                                    <div class="col-md-6 col-xl-4">
                                        <div class="ticket-card border border-success rounded-4 p-4 h-100 bg-success-subtle position-relative overflow-hidden">
                                            <!-- Decorative Elements -->
                                            <div class="position-absolute top-0 end-0 opacity-10">
                                                <i class="fas fa-ticket-alt" style="font-size: 4rem; transform: rotate(15deg);"></i>
                                            </div>
                                            
                                            <!-- Ticket Content -->
                                            <div class="position-relative">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <strong style="font-size: 14px;">${seatName}</strong>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-1 fw-bold text-success">Ghế ${seatName}</h6>
                                                            <small class="text-muted">Vé số #${ticket.id}</small>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-success rounded-pill">
                                                        <i class="fas fa-check me-1"></i>Đã Sử Dụng
                                                    </span>
                                                </div>
                                                
                                                <div class="border-top border-success-subtle pt-3 mt-3">
                                                    <div class="d-flex align-items-center text-muted">
                                                        <i class="fas fa-clock me-2"></i>
                                                        <small><strong>Quét lúc:</strong> ${timeFormatted}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Ticket Holes -->
                                            <div class="ticket-holes">
                                                <div class="hole hole-left"></div>
                                                <div class="hole hole-right"></div>
                                            </div>
                                        </div>
                                    </div>
                `;
            });
            
            html += `
                                </div>
                            </div>
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
                        <h3 class="h5 fw-bold text-dark mb-3">Thông Tin Đơn Hàng</h3>
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
                        <h3 class="h5 fw-bold text-dark mb-3">Thông Tin Phim</h3>
                        <div class="row g-3">
                            <div class="col-md-6"><strong>Phim:</strong> ${data.showtime.movie_name}</div>
                            <div class="col-md-6"><strong>Rạp:</strong> ${data.showtime.cinema_name}</div>
                            <div class="col-md-6"><strong>Ngày chiếu:</strong> ${data.showtime.show_date}</div>
                            <div class="col-md-6"><strong>Giờ chiếu:</strong> ${data.showtime.start_time}</div>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="h5 fw-bold text-dark mb-3">Danh Sách Vé (${data.tickets.length})</h3>
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
                <!-- Success Banner -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center bg-success text-white px-4 py-3 rounded-pill shadow-lg">
                        <i class="fas fa-check-circle fs-4 me-3"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">${response.message}</h5>
                            <small class="opacity-75">Vé đã được kích hoạt thành công</small>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Ticket Information -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                            <div class="card-header bg-gradient-primary text-white py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-ticket-alt fs-4 me-3"></i>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Thông Tin Vé</h5>
                                        <small class="opacity-75">Chi tiết vé đã quét</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="ticket-info-grid">
                                    <div class="info-item d-flex justify-content-between align-items-center p-3 mb-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-barcode text-primary fs-5 me-3"></i>
                                            <span class="text-muted">Mã vé:</span>
                                        </div>
                                        <strong class="text-primary">${data?.ticket_code || ''}</strong>
                                    </div>
                                    
                                    <div class="info-item d-flex justify-content-between align-items-center p-3 mb-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-couch text-warning fs-5 me-3"></i>
                                            <span class="text-muted">Ghế:</span>
                                        </div>
                                        <strong class="text-dark fs-5">${data?.seat_name || ''}</strong>
                                    </div>
                                    
                                    <div class="info-item d-flex justify-content-between align-items-center p-3 mb-3 bg-success-subtle rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success fs-5 me-3"></i>
                                            <span class="text-muted">Trạng thái:</span>
                                        </div>
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check me-1"></i>Đã sử dụng
                                        </span>
                                    </div>
                                    
                                    <div class="info-item d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock text-info fs-5 me-3"></i>
                                            <span class="text-muted">Thời gian quét:</span>
                                        </div>
                                        <strong class="text-info">${data?.used_at ? new Date(data.used_at).toLocaleString('vi-VN') : ''}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Information -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                            <div class="card-header bg-success text-white py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-receipt fs-4 me-3"></i>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Thông Tin Đơn Hàng</h5>
                                        <small class="opacity-75">Chi tiết booking</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="booking-info-grid">
                                    <div class="info-item d-flex justify-content-between align-items-center p-3 mb-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hashtag text-primary fs-5 me-3"></i>
                                            <span class="text-muted">Mã booking:</span>
                                        </div>
                                        <strong class="text-primary">${data?.booking_code || ''}</strong>
                                    </div>
                                    
                                    <div class="info-item d-flex justify-content-between align-items-center p-3 mb-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user text-info fs-5 me-3"></i>
                                            <span class="text-muted">Khách hàng:</span>
                                        </div>
                                        <strong class="text-dark">${data?.customer_name || ''}</strong>
                                    </div>
                                    
                                    <div class="info-item d-flex justify-content-between align-items-center p-3 mb-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-phone text-warning fs-5 me-3"></i>
                                            <span class="text-muted">Số điện thoại:</span>
                                        </div>
                                        <strong class="text-dark">${data?.customer_phone || ''}</strong>
                                    </div>
                                    
                                    <div class="info-item d-flex justify-content-between align-items-center p-3 bg-success-subtle rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-money-bill text-success fs-5 me-3"></i>
                                            <span class="text-muted">Tổng tiền:</span>
                                        </div>
                                        <strong class="text-success fs-5">${formatCurrency(data?.total_amount || 0)}</strong>
                                    </div>
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
            toast.removeClass().addClass(`position-fixed top-0 start-50 translate-middle-x z-50 px-4 py-3 rounded text-white fw-medium shadow-lg ${color}`)
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