@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center bg-light-subtle">
                        <div>
                            <h4 class="card-title">Quét QR Code Vé Xem Phim</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Form input -->
                        <div class="mb-4">
                            <label for="codeInput" class="form-label h5 text-dark">Nhập mã vé (Booking Code hoặc Ticket Code) hoặc quét QR</label>
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-md-4">
                                    <input type="text" id="codeInput" placeholder="VD: BK1754063915 hoặc TICKET123456" class="form-control form-control-lg">
                                </div>
                                <div class="col-md-2">
                                    <button onclick="scanCode()" class="btn btn-primary w-100">Quét Vé</button>
                                </div>
                                <div class="col-md-2">
                                    <button onclick="openQrModal()" class="btn btn-success w-100">Quét QR Code</button>
                                </div>
                                <div class="col-md-2">
                                    <input type="file" id="qrImageUpload" accept="image/*" class="d-none" onchange="scanQrFromImage()">
                                    <button onclick="document.getElementById('qrImageUpload').click()" class="btn btn-purple w-100">Tải Ảnh QR</button>
                                </div>
                            </div>
                        </div>

                        <!-- Result section -->
                        <div id="result" class="d-none">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 table-hover table-centered">
                                    <thead class="bg-light-subtle">
                                        <tr>
                                            <th colspan="4" class="text-primary">Kết Quả Quét Vé</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultContent"></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Error notification -->
                        <div id="error" class="d-none alert alert-danger mt-4">
                            <div id="errorContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Modal -->
        <div id="qrModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0 bg-light-subtle">
                        <h4 class="modal-title text-primary">Quét QR Code</h4>
                        <button onclick="closeQrModal()" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="qr-reader" class="border rounded mx-auto" style="width: 360px"></div>
                        <p class="mt-3 text-center text-muted small">Đưa mã QR vào khung camera để quét tự động</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        .btn-purple {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
        .btn-purple:hover {
            background-color: #5e35b1;
            border-color: #5e35b1;
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
                '<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"></div><span class="ms-3 text-muted">Đang xử lý...</span></td></tr>'
            );
            // Nhận diện code: nếu bắt đầu bằng BK hoặc có độ dài lớn hơn 8 và toàn số => booking code, còn lại là ticket code
            let isBookingCode = false;
            if (/^BK\d{6,}$/.test(code)) {
                isBookingCode = true;
            } else if (/^\d{8,}$/.test(code)) {
                isBookingCode = true;
            } else if (/^TICKET/i.test(code)) {
                isBookingCode = false;
            }
            let url = '';
            let data = {};
            if (isBookingCode) {
                url = '/admin/api/qr/scan';
                data = { booking_code: code };
            } else {
                url = '/admin/api/qr/scan-ticket';
                data = { ticket_code: code };
            }
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        if (isBookingCode) {
                            showSuccess(response);
                        } else {
                            showTicketResultByTicketCode(response);
                        }
                    } else {
                        showError(response.message);
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'Có lỗi xảy ra khi quét vé';
                    showError(errorMsg);
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
                '<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"></div><span class="ms-3 text-muted">Đang kiểm tra...</span></td></tr>'
            );

            $.ajax({
                url: '/admin/api/qr/check-status',
                method: 'POST',
                data: {
                    booking_code: bookingCode
                },
                success: function(response) {
                    if (response.success) {
                        showTicketStatus(response.data);
                    } else {
                        showError(response.message);
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'Có lỗi xảy ra khi kiểm tra trạng thái';
                    showError(errorMsg);
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
                '<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"></div><span class="ms-3 text-muted">Đang xử lý ảnh QR...</span></td></tr>'
            );
            const html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.scanFile(file, true)
                .then(qrCodeMessage => {
                    showToast('Đã quét ảnh QR thành công!');
                    $('#codeInput').val(qrCodeMessage);
                    setTimeout(() => {
                        scanCode();
                    }, 900);
                })
                .catch(err => {
                    showError('Không thể quét mã QR từ ảnh. Vui lòng thử lại.');
                })
                .finally(() => {
                    fileInput.value = '';
                });
        }

        function showSuccess(response) {
            const booking = response.data.booking;
            const tickets = response.data.updated_tickets;

            let html = `
                <tr>
                    <td colspan="4">
                        <div class="alert alert-success mb-4">
                            <h3 class="h5 font-weight-bold">✅ ${response.message}</h3>
                        </div>
                        <div class="mb-4">
                            <h3 class="h5 font-weight-bold text-dark">Thông Tin Đơn Hàng</h3>
                            <div class="mt-2">
                                <p><strong>Mã booking:</strong> ${booking.booking_code}</p>
                                <p><strong>Khách hàng:</strong> ${booking.customer_name || 'N/A'}</p>
                                <p><strong>Số điện thoại:</strong> ${booking.customer_phone || 'N/A'}</p>
                                <p><strong>Tổng tiền:</strong> ${formatCurrency(booking.total_amount || 0)}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="h5 font-weight-bold text-dark">Vé Đã Quét (${tickets.length})</h3>
                            <div class="mt-2">
            `;

            tickets.forEach(ticket => {
                const seatName = ticket.seat ? (ticket.seat.row_char + ticket.seat.seat_number) : 'N/A';
                html += `
                    <div class="card border-success mb-2">
                        <div class="card-body">
                            <p><strong>ID:</strong> ${ticket.id}</p>
                            <p><strong>Ghế:</strong> ${seatName}</p>
                            <p><strong>Trạng thái:</strong> <span class="text-success font-weight-bold">Đã sử dụng</span></p>
                            <p><strong>Thời gian quét:</strong> ${new Date(ticket.used_at).toLocaleString('vi-VN')}</p>
                        </div>
                    </div>
                `;
            });

            html += `</div></td></tr>`;

            $('#result').removeClass('d-none').find('#resultContent').html(html);
        }

        function showTicketStatus(data) {
            let html = `
                <tr>
                    <td colspan="4">
                        <div class="mb-4">
                            <h3 class="h5 font-weight-bold text-dark">Thông Tin Đơn Hàng</h3>
                            <div class="mt-2">
                                <p><strong>Mã booking:</strong> ${data.booking_code}</p>
                                <p><strong>Trạng thái đơn:</strong> <span class="badge ${getStatusClass(data.booking_status)}">${getStatusText(data.booking_status)}</span></p>
                                <p><strong>Khách hàng:</strong> ${data.customer_name}</p>
                                <p><strong>Số điện thoại:</strong> ${data.customer_phone}</p>
                                <p><strong>Tổng tiền:</strong> ${formatCurrency(data.total_amount)}</p>
                                <p><strong>Ngày đặt:</strong> ${new Date(data.booking_date).toLocaleString('vi-VN')}</p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h3 class="h5 font-weight-bold text-dark">Thông Tin Phim</h3>
                            <div class="mt-2">
                                <p><strong>Phim:</strong> ${data.showtime.movie_name}</p>
                                <p><strong>Rạp:</strong> ${data.showtime.cinema_name}</p>
                                <p><strong>Ngày chiếu:</strong> ${data.showtime.show_date}</p>
                                <p><strong>Giờ chiếu:</strong> ${data.showtime.start_time}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="h5 font-weight-bold text-dark">Danh Sách Vé (${data.tickets.length})</h3>
                            <div class="mt-2">
            `;

            data.tickets.forEach(ticket => {
                const isUsed = ticket.ticket_status === 'used';
                html += `
                    <div class="card ${isUsed ? 'border-danger' : 'border-success'} mb-2">
                        <div class="card-body">
                            <p><strong>ID:</strong> ${ticket.id}</p>
                            <p><strong>Ghế:</strong> ${ticket.seat_name}</p>
                            <p><strong>Giá:</strong> ${formatCurrency(ticket.price)}</p>
                            <p><strong>Trạng thái:</strong> 
                                <span class="badge ${isUsed ? 'bg-danger' : 'bg-success'}">
                                    ${isUsed ? 'Đã sử dụng' : 'Chưa sử dụng'}
                                </span>
                            </p>
                            ${ticket.used_at ? `<p><strong>Thời gian sử dụng:</strong> ${new Date(ticket.used_at).toLocaleString('vi-VN')}</p>` : ''}
                        </div>
                    </div>
                `;
            });

            html += `</div></td></tr>`;

            $('#result').removeClass('d-none').find('#resultContent').html(html);
        }

        function showError(message) {
            $('#error').removeClass('d-none');
            $('#errorContent').text(message);
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
            switch (status) {
                case 'confirmed':
                    return 'bg-success';
                case 'pending':
                    return 'bg-warning';
                case 'cancelled':
                    return 'bg-danger';
                default:
                    return 'bg-secondary';
            }
        }

        function getStatusText(status) {
            switch (status) {
                case 'confirmed':
                    return 'Đã xác nhận';
                case 'pending':
                    return 'Chờ xử lý';
                case 'cancelled':
                    return 'Đã hủy';
                default:
                    return status;
            }
        }

        $('#codeInput').keypress(function(e) {
            if (e.which == 13) {
                scanCode();
            }
        });

        function showToast(message, color = 'bg-success') {
            const toast = $('#toast');
            toast.removeClass().addClass(
                `position-fixed top-0 start-50 translate-middle-x z-50 px-4 py-3 rounded text-white font-weight-bold shadow-lg ${color}`
            ).text(message).fadeIn(200);
            setTimeout(() => toast.fadeOut(400), 1800);
        }

        function openQrModal() {
            $('#qrModal').modal('show');
            if (!window.html5QrCode) {
                window.html5QrCode = new Html5Qrcode("qr-reader");
            }
            window.html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: 250
            },
            qrCodeMessage => {
                showToast('Đã quét QR thành công!');
                $('#codeInput').val(qrCodeMessage);
                setTimeout(() => {
                    closeQrModal();
                    scanCode();
                }, 900);
            },
            errorMessage => {}
            );
        }
        // Hiển thị kết quả khi quét ticket code
        function showTicketResultByTicketCode(response) {
            const data = response.data;
            let html = `<tr><td colspan="4">
                <div class="d-flex flex-column align-items-center">
                    <div class="alert alert-success mb-4 w-75 text-center">
                        <h3 class="h5 font-weight-bold mb-0">✅ ${response.message}</h3>
                    </div>
                    <div class="card mb-4 w-75 shadow-sm">
                        <div class="card-header bg-primary text-white text-center">
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
                                <div class="col-6"><span class="badge bg-success">Đã sử dụng</span></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Thời gian quét:</strong></div>
                                <div class="col-6">${data?.used_at ? new Date(data.used_at).toLocaleString('vi-VN') : ''}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card w-75 shadow-sm">
                        <div class="card-header bg-info text-white text-center">
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
            </td></tr>`;
            $('#result').removeClass('d-none').find('#resultContent').html(html);
        }

        function closeQrModal() {
            $('#qrModal').modal('hide');
            if (window.html5QrCode) {
                window.html5QrCode.stop().catch(() => {});
            }
        }
    </script>
@endsection