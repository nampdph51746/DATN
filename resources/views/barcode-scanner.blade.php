<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quét Mã Barcode - Hệ Thống Rạp Chiếu Phim</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Quét Mã Vé</h1>
            
            <!-- Form nhập mã -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Nhập Mã Booking</h2>
                <div class="flex gap-4">
                    <input type="text" 
                           id="bookingCode" 
                           placeholder="Nhập mã booking (VD: BK1754063915)"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button onclick="scanBarcode()" 
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Quét Vé
                    </button>
                </div>
                <button onclick="checkStatus()" 
                        class="mt-3 px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Kiểm Tra Trạng Thái
                </button>
            </div>

            <!-- Kết quả -->
            <div id="result" class="hidden bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Kết Quả</h2>
                <div id="resultContent"></div>
            </div>

            <!-- Thông báo lỗi -->
            <div id="error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div id="errorContent"></div>
            </div>
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
                url: '/api/barcode/scan',
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
                url: '/api/barcode/check-status',
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
    </script>
</body>
</html>