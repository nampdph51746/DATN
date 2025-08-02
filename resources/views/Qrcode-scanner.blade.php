<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quét QR Code - Hệ Thống Rạp Chiếu Phim</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-green-100 py-8 px-2">
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl p-8 relative">
            <h1 class="text-4xl font-extrabold text-center mb-8 text-blue-700 tracking-tight">Quét QR Code Vé Xem Phim</h1>
            <!-- Toast thông báo -->
            <div id="toast" class="hidden fixed top-6 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded-lg font-semibold shadow-lg text-white bg-green-600 animate-fade-in"></div>
            <!-- Form nhập mã -->
            <div class="flex flex-col gap-4 mb-6">
                <label for="bookingCode" class="text-lg font-medium text-gray-700">Nhập mã booking hoặc quét QR</label>
                <div class="flex gap-2">
                    <input type="text" id="bookingCode" placeholder="VD: BK1754063915" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-lg">
                    <button onclick="scanBarcode()" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition">Quét Vé</button>
                </div>
                <div class="flex gap-2">
                    <button onclick="openQrModal()" class="flex-1 px-6 py-3 bg-green-500 text-white rounded-lg font-semibold shadow hover:bg-green-600 transition">Quét QR Code</button>
                    <button onclick="downloadQrImage()" class="flex-1 px-6 py-3 bg-purple-500 text-white rounded-lg font-semibold shadow hover:bg-purple-600 transition">Tải Ảnh QR</button>
                </div>
                <button onclick="checkStatus()" class="w-full px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold shadow hover:bg-gray-600 transition">Kiểm Tra Trạng Thái</button>
            </div>
            <!-- Kết quả -->
            <div id="result" class="hidden bg-gray-50 rounded-xl shadow-inner p-6 mt-4">
                <h2 class="text-2xl font-bold mb-4 text-blue-700">Kết Quả</h2>
                <div id="resultContent"></div>
            </div>
            <!-- Thông báo lỗi -->
            <div id="error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mt-4">
                <div id="errorContent"></div>
            </div>
        </div>
    </div>

    <!-- Modal quét QR -->
    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 relative w-full max-w-md border-2 border-blue-200">
            <button onclick="closeQrModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-3xl font-bold">&times;</button>
            <h2 class="text-2xl font-bold mb-4 text-blue-700 text-center">Quét QR Code</h2>
            <div id="qr-reader" class="rounded-lg overflow-hidden border border-blue-200 mx-auto" style="width: 320px"></div>
            <p class="mt-4 text-center text-gray-500 text-sm">Đưa mã QR vào khung camera để quét tự động</p>
        </div>
    </div>

    <script>
        // CSRF token cho Ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function scanBarcode() {
            const bookingCode = $('#bookingCode').val().trim();
            
            if (!bookingCode) {
                showError('Vui lòng nhập mã booking');
                return;
            }

            // Reset UI
            hideMessages();
            
            // Show loading
            $('#result').removeClass('hidden').html('<div class="flex items-center justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div><span class="ml-2">Đang xử lý...</span></div>');

            $.ajax({
                url: '/api/qr/scan',
                method: 'POST',
                data: {
                    booking_code: bookingCode
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess(response);
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

            // Reset UI
            hideMessages();
            
            // Show loading
            $('#result').removeClass('hidden').html('<div class="flex items-center justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div><span class="ml-2">Đang kiểm tra...</span></div>');

            $.ajax({
                url: '/api/qr/check-status',
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

        function showSuccess(response) {
            const booking = response.data.booking;
            const tickets = response.data.updated_tickets;
            
            let html = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <h3 class="font-semibold">✅ ${response.message}</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-lg">Thông Tin Đơn Hàng</h3>
                        <p><strong>Mã booking:</strong> ${booking.booking_code}</p>
                        <p><strong>Khách hàng:</strong> ${booking.customer_name || 'N/A'}</p>
                        <p><strong>Số điện thoại:</strong> ${booking.customer_phone || 'N/A'}</p>
                        <p><strong>Tổng tiền:</strong> ${formatCurrency(booking.total_amount || 0)}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Vé Đã Quét (${tickets.length})</h3>
                        <div class="grid gap-2">
            `;
            
            tickets.forEach(ticket => {
                const seatName = ticket.seat ? (ticket.seat.row_char + ticket.seat.seat_number) : 'N/A';
                html += `
                    <div class="bg-gray-50 p-3 rounded">
                        <p><strong>ID:</strong> ${ticket.id}</p>
                        <p><strong>Ghế:</strong> ${seatName}</p>
                        <p><strong>Trạng thái:</strong> <span class="text-green-600 font-semibold">Đã sử dụng</span></p>
                        <p><strong>Thời gian quét:</strong> ${new Date(ticket.used_at).toLocaleString('vi-VN')}</p>
                    </div>
                `;
            });
            
            html += `</div></div></div>`;
            
            $('#result').removeClass('hidden').html(`<h2 class="text-xl font-semibold mb-4">Kết Quả Quét Vé</h2>${html}`);
        }

        function showTicketStatus(data) {
            let html = `
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-lg">Thông Tin Đơn Hàng</h3>
                        <p><strong>Mã booking:</strong> ${data.booking_code}</p>
                        <p><strong>Trạng thái đơn:</strong> <span class="px-2 py-1 rounded text-sm ${getStatusClass(data.booking_status)}">${getStatusText(data.booking_status)}</span></p>
                        <p><strong>Khách hàng:</strong> ${data.customer_name}</p>
                        <p><strong>Số điện thoại:</strong> ${data.customer_phone}</p>
                        <p><strong>Tổng tiền:</strong> ${formatCurrency(data.total_amount)}</p>
                        <p><strong>Ngày đặt:</strong> ${new Date(data.booking_date).toLocaleString('vi-VN')}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Thông Tin Phim</h3>
                        <p><strong>Phim:</strong> ${data.showtime.movie_name}</p>
                        <p><strong>Rạp:</strong> ${data.showtime.cinema_name}</p>
                        <p><strong>Ngày chiếu:</strong> ${data.showtime.show_date}</p>
                        <p><strong>Giờ chiếu:</strong> ${data.showtime.start_time}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Danh Sách Vé (${data.tickets.length})</h3>
                        <div class="grid gap-2">
            `;
            
            data.tickets.forEach(ticket => {
                const isUsed = ticket.ticket_status === 'used';
                html += `
                    <div class="bg-gray-50 p-3 rounded border-l-4 ${isUsed ? 'border-red-500' : 'border-green-500'}">
                        <p><strong>ID:</strong> ${ticket.id}</p>
                        <p><strong>Ghế:</strong> ${ticket.seat_name}</p>
                        <p><strong>Giá:</strong> ${formatCurrency(ticket.price)}</p>
                        <p><strong>Trạng thái:</strong> 
                            <span class="px-2 py-1 rounded text-sm ${isUsed ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                                ${isUsed ? 'Đã sử dụng' : 'Chưa sử dụng'}
                            </span>
                        </p>
                        ${ticket.used_at ? `<p><strong>Thời gian sử dụng:</strong> ${new Date(ticket.used_at).toLocaleString('vi-VN')}</p>` : ''}
                    </div>
                `;
            });
            
            html += `</div></div></div>`;
            
            $('#result').removeClass('hidden').html(`<h2 class="text-xl font-semibold mb-4">Trạng Thái Vé</h2>${html}`);
        }

        function showError(message) {
            $('#error').removeClass('hidden');
            $('#errorContent').text(message);
            $('#result').addClass('hidden');
        }

        function hideMessages() {
            $('#error').addClass('hidden');
            $('#result').addClass('hidden');
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        }

        function getStatusClass(status) {
            switch(status) {
                case 'confirmed': return 'bg-green-100 text-green-800';
                case 'pending': return 'bg-yellow-100 text-yellow-800';
                case 'cancelled': return 'bg-red-100 text-red-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function getStatusText(status) {
            switch(status) {
                case 'confirmed': return 'Đã xác nhận';
                case 'pending': return 'Chờ xử lý';
                case 'cancelled': return 'Đã hủy';
                default: return status;
            }
        }

        // Enter key support
        $('#bookingCode').keypress(function(e) {
            if (e.which == 13) {
                scanBarcode();
            }
        });

        // QR Modal functions
        function showToast(message, color = 'bg-green-600') {
            const toast = $('#toast');
            toast.removeClass().addClass(`fixed top-6 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded-lg font-semibold shadow-lg text-white ${color}`)
                .text(message).fadeIn(200);
            setTimeout(() => toast.fadeOut(400), 1800);
        }

        function openQrModal() {
            $('#qrModal').removeClass('hidden');
            if (!window.html5QrCode) {
                window.html5QrCode = new Html5Qrcode("qr-reader");
            }
            window.html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                qrCodeMessage => {
                    showToast('Đã quét QR thành công!');
                    $('#bookingCode').val(qrCodeMessage);
                    setTimeout(() => {
                        closeQrModal();
                        scanBarcode();
                    }, 900);
                },
                errorMessage => {
                    // ignore scan errors
                }
            );
        }

        function closeQrModal() {
            $('#qrModal').addClass('hidden');
            if (window.html5QrCode) {
                window.html5QrCode.stop().catch(() => {});
            }
        }

        // Tải ảnh QR code cho bookingCode hiện tại
        function downloadQrImage() {
            const bookingCode = $('#bookingCode').val().trim();
            if (!bookingCode) {
                showError('Vui lòng nhập mã booking để tạo QR');
                return;
            }
            // Sử dụng API miễn phí để tạo QR code
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(bookingCode)}`;
            const link = document.createElement('a');
            link.href = qrUrl;
            link.download = `qr_${bookingCode}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
