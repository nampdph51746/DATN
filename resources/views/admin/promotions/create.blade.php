@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-success rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Thêm khuyến mãi mới</h2>
                                <p class="mb-0 opacity-90 fs-5">Tạo thông tin khuyến mãi mới trong hệ thống</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-success rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="fa-solid fa-plus-circle me-2"></i>
                                Tạo mới
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Preview Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-eye text-success"></i>
                        Xem trước khuyến mãi
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <div class="avatar-xl rounded-circle bg-success mb-3 d-inline-flex align-items-center justify-content-center">
                            <span class="avatar-title rounded-circle text-white">
                                <i class="fa-solid fa-gift fs-1"></i>
                            </span>
                        </div>
                        <h4 class="fw-bold text-dark mb-2" id="previewName">Tên khuyến mãi</h4>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2" id="previewCode">Mã: ---</span>
                        <div class="mt-3">
                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2" id="previewType">Loại: ---</span>
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2" id="previewValue">Giá trị: ---</span>
                        </div>
                        <div class="mt-3 text-muted small" id="previewDate">Thời gian: ---</div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="submit" form="promotionCreateForm" class="btn btn-success btn-lg rounded-pill">
                            <i class="fa-solid fa-save me-2"></i>Lưu khuyến mãi
                        </button>
                        <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="col-xl-8 col-lg-7">
            <form id="promotionCreateForm" action="{{ route('admin.promotions.store') }}" method="post">
                @csrf
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-success-subtle border-0 py-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-gift text-success"></i>
                            Thông tin khuyến mãi
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="name" id="promotion-name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Tên khuyến mãi" required>
                                    <label for="promotion-name"><i class="fa-solid fa-gift me-2"></i>Tên khuyến mãi <span class="text-danger">*</span></label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="code" id="promotion-code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Mã khuyến mãi" required>
                                    <label for="promotion-code"><i class="fa-solid fa-barcode me-2"></i>Mã khuyến mãi <span class="text-danger">*</span></label>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('status') is-invalid @enderror" id="promotion-status" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <label for="promotion-status"><i class="fa-solid fa-toggle-on me-2"></i>Trạng thái</label>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="rank_id" id="rank_id" class="form-select @error('rank_id') is-invalid @enderror">
                                        <option value="">-- Chọn hạng --</option>
                                        @foreach ($ranks as $id => $name)
                                            <option value="{{ $id }}" {{ old('rank_id', $promotion->rank_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="rank_id"><i class="fa-solid fa-user-tag me-2"></i>Hạng khách hàng</label>
                                    @error('rank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('discount_type') is-invalid @enderror" id="promotion-discount-type" name="discount_type" required>
                                        @foreach($discountTypes as $type)
                                            <option value="{{ $type->value }}" {{ old('discount_type') == $type->value ? 'selected' : '' }}>{{ $type->value }}</option>
                                        @endforeach
                                    </select>
                                    <label for="promotion-discount-type"><i class="fa-solid fa-percent me-2"></i>Loại giảm giá</label>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="discount_value" id="promotion-discount-value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value') }}" placeholder="Giá trị giảm giá" required>
                                    <label for="promotion-discount-value"><i class="fa-solid fa-money-bill-wave me-2"></i>Giá trị giảm giá <span class="text-danger">*</span></label>
                                    @error('discount_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="max_discount_amount" id="promotion-max-discount-amount" class="form-control @error('max_discount_amount') is-invalid @enderror" value="{{ old('max_discount_amount') }}" placeholder="Giá trị giảm tối đa">
                                    <label for="promotion-max-discount-amount"><i class="fa-solid fa-arrow-up-wide-short me-2"></i>Giá trị giảm tối đa</label>
                                    @error('max_discount_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="min_booking_value" id="promotion-min-booking-value" class="form-control @error('min_booking_value') is-invalid @enderror" value="{{ old('min_booking_value') }}" placeholder="Giá trị đơn hàng tối thiểu">
                                    <label for="promotion-min-booking-value"><i class="fa-solid fa-cart-shopping me-2"></i>Giá trị đơn hàng tối thiểu</label>
                                    @error('min_booking_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="datetime-local" name="start_date" id="promotion-start-date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                    <label for="promotion-start-date"><i class="fa-solid fa-calendar-day me-2"></i>Ngày bắt đầu <span class="text-danger">*</span></label>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="datetime-local" name="end_date" id="promotion-end-date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                                    <label for="promotion-end-date"><i class="fa-solid fa-calendar-xmark me-2"></i>Ngày kết thúc <span class="text-danger">*</span></label>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" name="quantity" id="promotion-quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" placeholder="Số lượng mã" required>
                                    <label for="promotion-quantity"><i class="fa-solid fa-hashtag me-2"></i>Số lượng mã <span class="text-danger">*</span></label>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control bg-light-subtle @error('description') is-invalid @enderror" id="promotion-description" name="description" style="height: 120px;" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                                    <label for="promotion-description"><i class="fa-solid fa-align-left me-2"></i>Mô tả</label>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- CSS Styling -->
<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
}
.bg-success-subtle {
    background-color: rgba(40,167,69,0.08) !important;
}
.bg-info-subtle {
    background-color: rgba(23,162,184,0.08) !important;
}
.bg-warning-subtle {
    background-color: rgba(255,193,7,0.08) !important;
}
.avatar-xl {
    width: 5rem;
    height: 5rem;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
}
.card {
    transition: all 0.3s ease;
    border: none;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.sticky-top {
    top: 1rem;
}
.form-section {
    position: relative;
    animation: fadeInUp 0.5s ease-out;
}
.form-floating > .form-control,
.form-floating > .form-select {
    height: calc(3.5rem + 2px);
    font-size: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}
.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40,167,69,0.1);
    transform: translateY(-1px);
}
.form-floating > label {
    font-weight: 600;
    color: #6c757d;
    padding: 1rem 1rem;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.form-section:nth-child(2) { animation-delay: 0.1s; }
.form-section:nth-child(3) { animation-delay: 0.2s; }
.form-section:nth-child(4) { animation-delay: 0.3s; }
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    .card-body {
        padding: 1.5rem !important;
    }
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live preview for promotion info
    function updatePreview() {
        document.getElementById('previewName').textContent = document.getElementById('promotion-name').value || 'Tên khuyến mãi';
        document.getElementById('previewCode').textContent = 'Mã: ' + (document.getElementById('promotion-code').value || '---');
        document.getElementById('previewType').textContent = 'Loại: ' + (document.getElementById('promotion-discount-type').value || '---');
        document.getElementById('previewValue').textContent = 'Giá trị: ' + (document.getElementById('promotion-discount-value').value || '---');
        let start = document.getElementById('promotion-start-date').value;
        let end = document.getElementById('promotion-end-date').value;
        document.getElementById('previewDate').textContent = (start && end) ? `Thời gian: ${start} - ${end}` : 'Thời gian: ---';
    }
    ['promotion-name','promotion-code','promotion-discount-type','promotion-discount-value','promotion-start-date','promotion-end-date'].forEach(function(id){
        document.getElementById(id)?.addEventListener('input', updatePreview);
    });
    updatePreview();
});
</script>
@endsection