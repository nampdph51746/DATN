@extends('admin.layouts.master')

@section('title', 'Quét QR Code')

@section('styles')
<style>
    .qr-scanner-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .scanner-section {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .result-section {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: none;
    }
    
    .result-success {
        border-left: 4px solid #10b981;
    }
    
    .result-error {
        border-left: 4px solid #ef4444;
    }
    
    .scan-input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        font-size: 16px;
        margin-bottom: 10px;
    }
    
    .scan-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .scan-btn {
        background: #3b82f6;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .scan-btn:hover {
        background: #2563eb;
    }
    
    .scan-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }
    
    .info-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 16px;
        margin: 10px 0;
    }
    
    .info-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }
    
    .info-value {
        color: #6b7280;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-error {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .items-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 10px;
    }
    
    .item-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .item-row:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quét QR Code</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Quét QR Code</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="qr-scanner-container">
        <!-- Scanner Section -->
        <div class="scanner-section">
            <h5><i class="fas fa-qrcode me-2"></i>Quét QR Code</h5>
            <p class="text-muted mb-3">Nhập hoặc dán mã QR để quét vé, đồ ăn hoặc đơn hàng</p>
            
            <div class="row">
                <div class="col-md-8">
                    <input type="text" 
                           id="qrInput" 
                           class="scan-input" 
                           placeholder="Nhập mã QR hoặc dán nội dung QR code..."
                           autocomplete="off">
                </div>
                <div class="col-md-4">
                    <button id="scanBtn" class="scan-btn w-100">
                        <i class="fas fa-search me-2"></i>Quét
                    </button>
                </div>
            </div>
            
            <div class="mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Hỗ trợ quét: Vé đơn lẻ, Đồ ăn/uống, Toàn bộ đơn hàng
                </small>
            </div>
        </div>

        <!-- Result Section -->
        <div id="resultSection" class="result-section">
            <div id="resultContent"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const qrInput = $('#qrInput');
    const scanBtn = $('#scanBtn');
    const resultSection = $('#resultSection');
    const resultContent = $('#resultContent');

    // Auto focus vào input
    qrInput.focus();

    // Quét khi nhấn Enter
    qrInput.on('keypress', function(e) {
        if (e.which === 13) {
            scanQrCode();
        }
    });

    // Quét khi click button
    scanBtn.on('click', function() {
        scanQrCode();
    });

    function scanQrCode() {
        const qrData = qrInput.val().trim();
        
        if (!qrData) {
            showError('Vui lòng nhập mã QR');
            return;
        }

        // Disable button và hiện loading
        scanBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Đang quét...');

        $.ajax({
            url: '{{ route("admin.qr-scan.scan") }}',
            method: 'POST',
            data: {
                qr_data: qrData,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showSuccess(response);
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Lỗi khi quét QR code';
                showError(error);
            },
            complete: function() {
                // Reset button
                scanBtn.prop('disabled', false).html('<i class="fas fa-search me-2"></i>Quét');
            }
        });
    }

    function showSuccess(response) {
        resultSection.removeClass('result-error').addClass('result-success').show();
        
        let html = `
            <h5 class="text-success mb-3">
                <i class="fas fa-check-circle me-2"></i>${response.message}
            </h5>
        `;

        switch(response.type) {
            case 'ticket':
                html += renderTicketResult(response.ticket);
                break;
            case 'food':
                html += renderFoodResult(response.food_items, response.updated_items);
                break;
            case 'booking':
                html += renderBookingResult(response.booking, response.tickets_scanned, response.food_items_scanned);
                break;
        }

        resultContent.html(html);
        
        // Clear input và focus lại
        qrInput.val('').focus();
    }

    function showError(message) {
        resultSection.removeClass('result-success').addClass('result-error').show();
        resultContent.html(`
            <h5 class="text-danger mb-3">
                <i class="fas fa-exclamation-triangle me-2"></i>Lỗi quét QR
            </h5>
            <p class="mb-0">${message}</p>
        `);
        
        // Focus lại input
        qrInput.focus();
    }

    function renderTicketResult(ticket) {
        return `
            <div class="info-card">
                <div class="info-label">Thông tin vé</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Mã vé:</div>
                        <div class="info-value">${ticket.ticket_code}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Ghế:</div>
                        <div class="info-value">${ticket.seat?.name || 'N/A'}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Phim:</div>
                        <div class="info-value">${ticket.showtime?.movie?.title || 'N/A'}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Thời gian sử dụng:</div>
                        <div class="info-value">${new Date(ticket.used_at).toLocaleString('vi-VN')}</div>
                    </div>
                </div>
            </div>
        `;
    }

    function renderFoodResult(foodItems, updatedItems) {
        let html = `
            <div class="info-card">
                <div class="info-label">Đồ ăn/uống đã quét: ${updatedItems?.length || 0} items</div>
                <div class="items-list">
        `;

        if (updatedItems && updatedItems.length > 0) {
            updatedItems.forEach(item => {
                html += `
                    <div class="item-row">
                        <div>
                            <strong>${item.product_variant?.product?.name || 'N/A'}</strong><br>
                            <small class="text-muted">Số lượng: ${item.quantity}</small>
                        </div>
                        <div>
                            <span class="status-badge status-success">Đã sử dụng</span>
                        </div>
                    </div>
                `;
            });
        }

        html += `</div></div>`;
        return html;
    }

    function renderBookingResult(booking, ticketsScanned, foodScanned) {
        return `
            <div class="info-card">
                <div class="info-label">Thông tin đơn hàng</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Mã đơn hàng:</div>
                        <div class="info-value">${booking.booking_code}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Khách hàng:</div>
                        <div class="info-value">${booking.customer_name}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Vé đã quét:</div>
                        <div class="info-value">${ticketsScanned} vé</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Đồ ăn đã quét:</div>
                        <div class="info-value">${foodScanned} items</div>
                    </div>
                </div>
            </div>
        `;
    }
});
</script>
@endsection
