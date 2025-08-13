@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="detail-header rounded-5 p-5 mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-gift me-3"></i>
                            Chi tiết khuyến mãi
                        </h1>
                        <p class="lead text-white-50 mb-0">Mã khuyến mãi: {{ $promotion->code }}</p>
                    </div>
                    <div class="header-actions d-flex gap-3">
                        <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotion Info Card -->
    <div class="row">
        <div class="col-xl-8">
            <div class="summary-card mb-4">
                <div class="summary-header">
                    <div class="summary-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="summary-title-section">
                        <h5 class="summary-title">Thông tin khuyến mãi</h5>
                        <div class="booking-badges">
                            <span class="status-badge 
                                @if($promotion->status == 'active') status-paid
                                @elseif($promotion->status == 'pending') status-pending
                                @else status-unpaid @endif">
                                <i class="bi bi-bookmark"></i>
                                {{ ucfirst($promotion->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="summary-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-hash text-blue"></i>
                                    <span>ID khuyến mãi</span>
                                </div>
                                <div class="info-value">{{ $promotion->id }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-gift text-success"></i>
                                    <span>Mã khuyến mãi</span>
                                </div>
                                <div class="info-value">{{ $promotion->code }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-cash-coin text-warning"></i>
                                    <span>Giá trị giảm</span>
                                </div>
                                <div class="info-value text-primary fw-bold">
                                    {{ number_format($promotion->discount_value, 2, ',', '.') }}
                                    {{ $promotion->discount_type == 'percentage' ? '%' : 'đ' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-percent text-info"></i>
                                    <span>Loại giảm giá</span>
                                </div>
                                <div class="info-value">
                                    {{ $promotion->discount_type == 'percentage' ? 'Phần trăm' : 'Giá cố định' }}
                                </div>
                            </div>
                        </div>
                        @if($promotion->max_discount_amount)
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-arrow-up-right-circle text-warning"></i>
                                    <span>Giảm tối đa</span>
                                </div>
                                <div class="info-value">
                                    {{ number_format($promotion->max_discount_amount, 2, ',', '.') }} đ
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-calendar-event text-secondary"></i>
                                    <span>Thời gian áp dụng</span>
                                </div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-chat-left-text text-muted"></i>
                                    <span>Mô tả</span>
                                </div>
                                <div class="info-value">{!! nl2br(e($promotion->description)) !!}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-calendar-event text-secondary"></i>
                                    <span>Ngày tạo</span>
                                </div>
                                <div class="info-value">
                                    {{ $promotion->created_at ? $promotion->created_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-calendar-check text-success"></i>
                                    <span>Ngày cập nhật</span>
                                </div>
                                <div class="info-value">
                                    {{ $promotion->updated_at ? $promotion->updated_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar (có thể thêm các thông tin liên quan hoặc nút thao tác ở đây nếu cần) -->
    </div>
</div>

<style>
:root {
    --blue-primary: #2563eb;
    --blue-secondary: #3b82f6;
    --blue-light: #60a5fa;
    --blue-dark: #1d4ed8;
}
.text-blue { color: var(--blue-primary) !important; }
.detail-header {
    background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
    position: relative;
    overflow: hidden;
}
.detail-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}
.summary-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.1);
    overflow: hidden;
}
.summary-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.summary-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, var(--blue-primary), var(--blue-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}
.summary-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}
.booking-badges {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid;
}
.status-paid {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border-color: rgba(34, 197, 94, 0.2);
}
.status-unpaid {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.2);
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
.summary-body { padding: 2rem; }
.info-item { margin-bottom: 1.5rem; }
.info-item:last-child { margin-bottom: 0; }
.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.info-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
    margin-left: 1.5rem;
}
@media (max-width: 768px) {
    .detail-header { padding: 2rem !important; }
    .header-content h1 { font-size: 1.75rem; }
    .header-actions { flex-direction: column; width: 100%; gap: 0.75rem; }
    .summary-header { padding: 1rem 1.5rem; flex-direction: column; text-align: center; gap: 0.75rem; }
    .summary-body { padding: 1.5rem; }
    .booking-badges { justify-content: center; }
}
@endsection