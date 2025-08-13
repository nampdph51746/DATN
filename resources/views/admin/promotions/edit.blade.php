@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-warning rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="bi bi-ticket-perforated fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chỉnh sửa khuyến mãi</h2>
                                <p class="mb-0 opacity-90 fs-5">Cập nhật thông tin khuyến mãi "{{ $promotion->name }}"</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-primary rounded-pill px-4 py-3 fs-5 fw-semibold shadow">
                                <i class="bi bi-clock-history me-2"></i>
                                Đang chỉnh sửa
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-ticket-perforated" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Sidebar với Action Buttons -->
        <div class="col-xl-3 col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge text-warning"></i>
                            Thao tác
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <button type="submit" form="promotionEditForm" 
                                    class="btn btn-primary btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <i class="bi bi-check-circle"></i>
                                Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.promotions.show', $promotion->id) }}" 
                               class="btn btn-outline-info btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-eye"></i>
                                Xem chi tiết
                            </a>
                            <a href="{{ route('admin.promotions.index') }}" 
                               class="btn btn-outline-secondary btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-arrow-left"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="col-xl-9 col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md rounded-circle bg-warning-subtle">
                            <span class="avatar-title rounded-circle bg-warning text-white">
                                <i class="bi bi-ticket-perforated"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark">Thông tin khuyến mãi</h4>
                            <p class="text-muted mb-0">Cập nhật chi tiết chương trình khuyến mãi</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="promotionEditForm" action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Section: Thông tin cơ bản -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-warning-subtle">
                                        <span class="avatar-title rounded-circle bg-warning text-white">
                                            <i class="bi bi-info-circle"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-warning">Thông tin cơ bản</h5>
                                        <p class="text-muted mb-0 small">Tên, mã, trạng thái, hạng khách hàng</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="text" id="promotion-name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $promotion->name) }}" placeholder="Tên khuyến mãi" required>
                                        <label for="promotion-name">
                                            <i class="bi bi-tag me-2 text-warning"></i>Tên khuyến mãi <span class="text-danger">*</span>
                                        </label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="text" id="promotion-code" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $promotion->code) }}" placeholder="Mã khuyến mãi" required>
                                        <label for="promotion-code">
                                            <i class="bi bi-barcode me-2 text-primary"></i>Mã khuyến mãi <span class="text-danger">*</span>
                                        </label>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('status') is-invalid @enderror" id="promotion-status" name="status" required>
                                            <option value="active" {{ old('status', $promotion->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="pending" {{ old('status', $promotion->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="inactive" {{ old('status', $promotion->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <label for="promotion-status">
                                            <i class="bi bi-traffic-light me-2 text-success"></i>Trạng thái <span class="text-danger">*</span>
                                        </label>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <select name="rank_id" id="rank_id" class="form-select @error('rank_id') is-invalid @enderror">
                                            <option value="">-- Chọn hạng --</option>
                                            @foreach ($ranks as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('rank_id', $promotion->rank_id) == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="rank_id">
                                            <i class="bi bi-person-badge me-2 text-info"></i>Hạng khách hàng
                                        </label>
                                        @error('rank_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section: Giá trị giảm giá -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-success-subtle">
                                        <span class="avatar-title rounded-circle bg-success text-white">
                                            <i class="bi bi-percent"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-success">Giá trị giảm giá</h5>
                                        <p class="text-muted mb-0 small">Loại, giá trị, tối đa, tối thiểu</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('discount_type') is-invalid @enderror" id="promotion-discount-type" name="discount_type" required>
                                            @foreach($discountTypes as $type)
                                                <option value="{{ $type->value }}" {{ old('discount_type', $promotion->discount_type) == $type->value ? 'selected' : '' }}>{{ $type->value }}</option>
                                            @endforeach
                                        </select>
                                        <label for="promotion-discount-type">
                                            <i class="bi bi-percent me-2 text-success"></i>Loại giảm giá <span class="text-danger">*</span>
                                        </label>
                                        @error('discount_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" id="promotion-discount-value" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value', $promotion->discount_value) }}" placeholder="Giá trị giảm giá" required>
                                        <label for="promotion-discount-value">
                                            <i class="bi bi-cash-coin me-2 text-warning"></i>Giá trị giảm giá <span class="text-danger">*</span>
                                        </label>
                                        @error('discount_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" id="promotion-max-discount-amount" name="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror" value="{{ old('max_discount_amount', $promotion->max_discount_amount) }}" placeholder="Giá trị giảm tối đa">
                                        <label for="promotion-max-discount-amount">
                                            <i class="bi bi-cash-stack me-2 text-primary"></i>Giá trị giảm tối đa
                                        </label>
                                        @error('max_discount_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" id="promotion-min-booking-value" name="min_booking_value" class="form-control @error('min_booking_value') is-invalid @enderror" value="{{ old('min_booking_value', $promotion->min_booking_value) }}" placeholder="Giá trị đơn hàng tối thiểu">
                                        <label for="promotion-min-booking-value">
                                            <i class="bi bi-cart-check me-2 text-success"></i>Giá trị đơn hàng tối thiểu
                                        </label>
                                        @error('min_booking_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section: Thời gian & Số lượng -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-info-subtle">
                                        <span class="avatar-title rounded-circle bg-info text-white">
                                            <i class="bi bi-calendar-event"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-info">Thời gian & Số lượng</h5>
                                        <p class="text-muted mb-0 small">Ngày bắt đầu, kết thúc, số lượng, giới hạn</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="datetime-local" id="promotion-start-date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $promotion->start_date->format('Y-m-d\TH:i')) }}" required>
                                        <label for="promotion-start-date">
                                            <i class="bi bi-calendar-plus me-2 text-primary"></i>Ngày bắt đầu <span class="text-danger">*</span>
                                        </label>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="datetime-local" id="promotion-end-date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $promotion->end_date->format('Y-m-d\TH:i')) }}" required>
                                        <label for="promotion-end-date">
                                            <i class="bi bi-calendar-x me-2 text-danger"></i>Ngày kết thúc <span class="text-danger">*</span>
                                        </label>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="number" id="promotion-quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $promotion->quantity) }}" placeholder="Số lượng mã">
                                        <label for="promotion-quantity">
                                            <i class="bi bi-123 me-2 text-info"></i>Số lượng mã
                                        </label>
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="number" id="promotion-usage-limit" name="usage_limit_per_user" class="form-control @error('usage_limit_per_user') is-invalid @enderror" value="{{ old('usage_limit_per_user', $promotion->usage_limit_per_user) }}" placeholder="Giới hạn sử dụng mỗi người dùng">
                                        <label for="promotion-usage-limit">
                                            <i class="bi bi-person-check me-2 text-success"></i>Giới hạn sử dụng mỗi người dùng
                                        </label>
                                        @error('usage_limit_per_user')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section: Đối tượng áp dụng & Mô tả -->
                        <div class="form-section mb-4">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-secondary-subtle">
                                        <span class="avatar-title rounded-circle bg-secondary text-white">
                                            <i class="bi bi-people"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-secondary">Đối tượng & Mô tả</h5>
                                        <p class="text-muted mb-0 small">Áp dụng cho, mô tả chi tiết</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('applies_to') is-invalid @enderror" id="promotion-applies-to" name="applies_to">
                                            <option value="">Chọn đối tượng áp dụng</option>
                                            <option value="movies" {{ old('applies_to', $promotion->applies_to) == 'movies' ? 'selected' : '' }}>Movies</option>
                                            <option value="tickets" {{ old('applies_to', $promotion->applies_to) == 'tickets' ? 'selected' : '' }}>Tickets</option>
                                        </select>
                                        <label for="promotion-applies-to">
                                            <i class="bi bi-people me-2 text-secondary"></i>Áp dụng cho
                                        </label>
                                        @error('applies_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <textarea class="form-control bg-light-subtle @error('description') is-invalid @enderror" id="promotion-description" name="description" style="height: 120px;" placeholder="Mô tả">{{ old('description', $promotion->description) }}</textarea>
                                        <label for="promotion-description">
                                            <i class="bi bi-journal-text me-2 text-secondary"></i>Mô tả
                                        </label>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <a href="{{ route('admin.promotions.index') }}" 
                               class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-check-circle me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #FF6F00 0%, #00ACC1 100%);
}
.bg-warning-subtle {
    background-color: rgba(255, 111, 0, 0.1) !important;
}
.bg-success-subtle {
    background-color: rgba(76, 175, 80, 0.1) !important;
}
.bg-info-subtle {
    background-color: rgba(0, 172, 193, 0.1) !important;
}
.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.1) !important;
}
.avatar-xl { width: 5rem; height: 5rem; }
.avatar-md { width: 3rem; height: 3rem; }
.avatar-sm { width: 2.5rem; height: 2.5rem; }
.avatar-title {
    display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;
}
.btn { transition: all 0.3s ease; font-weight: 500; border-width: 2px; }
.btn:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
.btn-lg { padding: 0.75rem 2rem; font-size: 1rem; }
.card { transition: all 0.3s ease; border: none !important; }
.card:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(0,0,0,0.15) !important; }
.sticky-top { position: sticky; z-index: 1020; }
.form-section { position: relative; animation: fadeInUp 0.5s ease-out; }
.section-header { position: relative; }
.section-header::after {
    content: ''; position: absolute; bottom: -10px; left: 0; width: 50px; height: 3px;
    background: linear-gradient(90deg, #FF6F00, #00ACC1); border-radius: 2px;
}
.form-floating > .form-control, .form-floating > .form-select {
    height: calc(3.5rem + 2px); font-size: 1rem; border: 2px solid #e9ecef; border-radius: 0.75rem; transition: all 0.3s ease;
}
.form-floating > .form-control:focus, .form-floating > .form-select:focus {
    border-color: #FF6F00; box-shadow: 0 0 0 3px rgba(255,111,0,0.1); transform: translateY(-1px);
}
.form-floating > label { font-weight: 600; color: #6c757d; padding: 1rem 1rem; }
.bg-light-subtle { background-color: #f8f9fa !important; }
@media (max-width: 768px) {
    .container-fluid { padding: 1rem !important; }
    .avatar-xl { width: 4rem; height: 4rem; }
    .btn-lg { padding: 0.5rem 1.5rem; font-size: 0.9rem; }
    .form-floating > .form-control, .form-floating > .form-select { height: calc(3rem + 2px); }
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.form-section:nth-child(2) { animation-delay: 0.1s; }
.form-section:nth-child(3) { animation-delay: 0.2s; }
.form-section:nth-child(4) { animation-delay: 0.3s; }
</style>
@endsection