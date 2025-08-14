@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="edit-header rounded-5 p-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-pencil-square me-3"></i>
                            Cập nhật đơn đặt vé
                        </h1>
                        <p class="lead text-white-50 mb-0">Mã đơn: {{ $booking->booking_code }}</p>
                    </div>
                    <div class="header-icon d-none d-lg-block">
                        <div class="floating-icon">
                            <i class="bi bi-ticket-detailed"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar với thông tin hiện tại -->
        <div class="col-xl-4">
            <!-- Current Status Card -->
            <div class="status-card mb-4">
                <div class="status-header">
                    <div class="status-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="status-title">Thông tin hiện tại</h5>
                        <p class="status-subtitle">Trạng thái đơn đặt vé</p>
                    </div>
                </div>
                
                <div class="status-body">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-ticket-perforated text-blue"></i>
                            <span>Mã đặt vé</span>
                        </div>
                        <div class="info-value">{{ $booking->booking_code }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-person text-success"></i>
                            <span>Khách hàng</span>
                        </div>
                        <div class="info-value">{{ $booking->user->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-currency-dollar text-primary"></i>
                            <span>Tổng tiền</span>
                        </div>
                        <div class="info-value">{{ number_format($booking->final_amount, 0, ',', '.') }} đ</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-calendar-event text-secondary"></i>
                            <span>Ngày đặt</span>
                        </div>
                        <div class="info-value">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-bookmark text-warning"></i>
                            <span>Trạng thái hiện tại</span>
                        </div>
                        <div class="info-value">
                            @php
                                $currentStatus = is_object($booking->status) ? $booking->status->value : (string) $booking->status;
                            @endphp
                            <span class="status-badge status-{{ $currentStatus }}">
                                <i class="bi bi-dot"></i>
                                {{ ucfirst($currentStatus) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions-card">
                <div class="quick-actions-header">
                    <h6 class="quick-actions-title">
                        <i class="bi bi-lightning-charge me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="quick-actions-body">
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="quick-action-btn">
                        <i class="bi bi-eye"></i>
                        <span>Xem chi tiết</span>
                    </a>
                    <a href="{{ route('admin.bookings.print', $booking->id) }}" target="_blank" class="quick-action-btn">
                        <i class="bi bi-printer"></i>
                        <span>In vé</span>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="quick-action-btn">
                        <i class="bi bi-list"></i>
                        <span>Danh sách đơn</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="col-xl-8">
            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" id="updateStatusForm">
                @csrf
                @method('PUT')
                
                <div class="form-section mb-4">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-blue">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <div>
                                <h5 class="section-title">Cập nhật trạng thái</h5>
                                <p class="section-subtitle">Thay đổi trạng thái đơn đặt vé</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="floating-input">
                                        <select name="status" 
                                                id="status" 
                                                class="floating-input-field @error('status') is-invalid @enderror"
                                                required>
                                            <option value="">-- Chọn trạng thái mới --</option>
                                            <option value="pending" {{ old('status', $currentStatus) === 'pending' ? 'selected' : '' }}>
                                                Chờ xác nhận
                                            </option>
                                            <option value="confirmed_not_printed" {{ old('status', $currentStatus) === 'confirmed_not_printed' ? 'selected' : '' }}>
                                                Thành công - Chưa in vé
                                            </option>
                                            <option value="confirmed_printed" {{ old('status', $currentStatus) === 'confirmed_printed' ? 'selected' : '' }}>
                                                Thành công - Đã in vé
                                            </option>
                                            <option value="cancelled" {{ old('status', $currentStatus) === 'cancelled' ? 'selected' : '' }}>
                                                Đã hủy
                                            </option>
                                        </select>
                                        <label for="status" class="floating-input-label">
                                            <i class="bi bi-bookmark me-2"></i>Trạng thái mới
                                        </label>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="floating-input">
                                        <textarea name="admin_notes" 
                                                  id="admin_notes" 
                                                  rows="4"
                                                  class="floating-input-field @error('admin_notes') is-invalid @enderror" 
                                                  placeholder=" ">{{ old('admin_notes') }}</textarea>
                                        <label for="admin_notes" class="floating-input-label">
                                            <i class="bi bi-chat-left-text me-2"></i>Ghi chú quản trị (tùy chọn)
                                        </label>
                                        @error('admin_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Guidelines -->
                <div class="guidelines-section mb-4">
                    <div class="guidelines-card">
                        <div class="guidelines-header">
                            <h6 class="guidelines-title">
                                <i class="bi bi-lightbulb me-2"></i>
                                Hướng dẫn trạng thái
                            </h6>
                        </div>
                        <div class="guidelines-body">
                            <div class="guidelines-grid">
                                <div class="guideline-item">
                                    <div class="guideline-icon pending">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div class="guideline-content">
                                        <div class="guideline-status">Chờ xác nhận</div>
                                        <div class="guideline-description">Đơn hàng mới tạo, chờ xử lý</div>
                                    </div>
                                </div>
                                
                                <div class="guideline-item">
                                    <div class="guideline-icon confirmed">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="guideline-content">
                                        <div class="guideline-status">Thành công - Chưa in vé</div>
                                        <div class="guideline-description">Đơn hàng đã thanh toán, có thể in vé</div>
                                    </div>
                                </div>
                                
                                <div class="guideline-item">
                                    <div class="guideline-icon completed">
                                        <i class="bi bi-printer"></i>
                                    </div>
                                    <div class="guideline-content">
                                        <div class="guideline-status">Thành công - Đã in vé</div>
                                        <div class="guideline-description">Vé đã được in, sẵn sàng sử dụng</div>
                                    </div>
                                </div>
                                
                                <div class="guideline-item">
                                    <div class="guideline-icon cancelled">
                                        <i class="bi bi-x-circle"></i>
                                    </div>
                                    <div class="guideline-content">
                                        <div class="guideline-status">Đã hủy</div>
                                        <div class="guideline-description">Đơn hàng bị hủy bỏ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-section">
                    <div class="action-card">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-blue btn-lg rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Cập nhật trạng thái
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
:root {
    --blue-primary: #2563eb;
    --blue-secondary: #3b82f6;
    --blue-light: #60a5fa;
    --blue-dark: #1d4ed8;
}

/* Header */
.edit-header {
    background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
    position: relative;
    overflow: hidden;
}

.edit-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}

.floating-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Status Card */
.status-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.status-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.status-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.status-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

.status-body {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
}

.info-value {
    font-size: 0.95rem;
    color: #1f2937;
    font-weight: 600;
    margin-left: 1.5rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    border: 1px solid;
}

.status-pending {
    background: rgba(251, 191, 36, 0.1);
    color: #fbbf24;
    border-color: rgba(251, 191, 36, 0.2);
}

.status-confirmed, .status-completed {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border-color: rgba(34, 197, 94, 0.2);
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.2);
}

/* Quick Actions */
.quick-actions-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.quick-actions-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

.quick-actions-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0;
}

.quick-actions-body {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    font-weight: 500;
}

.quick-action-btn:hover {
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
    color: white;
    transform: translateX(4px);
}

/* Form Sections */
.form-section {
    margin-bottom: 2rem;
}

.section-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.section-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(37, 99, 235, 0.15);
}

.section-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.section-icon.bg-blue {
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
}

.section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.section-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

.section-body {
    padding: 2rem;
}

/* Floating Inputs */
.floating-input {
    position: relative;
    margin-bottom: 1rem;
}

.floating-input-field {
    width: 100%;
    padding: 1.25rem 1rem 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 1rem;
    font-size: 1rem;
    background: white;
    transition: all 0.3s ease;
    resize: vertical;
}

.floating-input-field:focus {
    outline: none;
    border-color: var(--blue-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.floating-input-field:focus + .floating-input-label,
.floating-input-field:not(:placeholder-shown) + .floating-input-label,
.floating-input-field:valid + .floating-input-label {
    transform: translateY(-0.75rem) scale(0.85);
    color: var(--blue-primary);
}

.floating-input-label {
    position: absolute;
    left: 1rem;
    top: 1.25rem;
    color: #6b7280;
    font-size: 1rem;
    pointer-events: none;
    transition: all 0.3s ease;
    transform-origin: left top;
    background: white;
    padding: 0 0.25rem;
}

/* Guidelines Section */
.guidelines-section {
    margin-bottom: 2rem;
}

.guidelines-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}

.guidelines-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-bottom: 1px solid #f59e0b;
}

.guidelines-title {
    font-size: 1rem;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 0;
}

.guidelines-body {
    padding: 1.5rem;
}

.guidelines-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
}

.guideline-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.guideline-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    border-color: var(--blue-light);
}

.guideline-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.guideline-icon.pending {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
}

.guideline-icon.confirmed {
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.guideline-icon.completed {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.guideline-icon.cancelled {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.guideline-content {
    flex: 1;
}

.guideline-status {
    font-size: 0.875rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.guideline-description {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.4;
}

/* Action Card */
.action-card {
    background: white;
    border-radius: 1.5rem;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
}

/* Buttons */
.btn-blue {
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-blue:hover {
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-primary));
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-header {
        padding: 2rem !important;
    }
    
    .header-content h1 {
        font-size: 1.75rem;
    }
    
    .status-body, .quick-actions-body {
        padding: 1rem;
    }
    
    .section-header {
        padding: 1rem 1.5rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .section-body {
        padding: 1.5rem;
    }
    
    .guidelines-grid {
        grid-template-columns: 1fr;
    }
    
    .action-card {
        padding: 1.5rem;
    }
    
    .action-card .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<script>
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    const status = document.getElementById('status').value;
    
    if (!status) {
        e.preventDefault();
        alert('❌ Vui lòng chọn trạng thái mới!');
        document.getElementById('status').focus();
        return false;
    }
    
    // Confirm status change
    const confirmMessage = `Bạn có chắc chắn muốn thay đổi trạng thái đơn đặt vé thành "${status}"?`;
    if (!confirm(confirmMessage)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection