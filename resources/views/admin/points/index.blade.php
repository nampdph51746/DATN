@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <h4 class="card-title mb-0">Danh Sách Điểm Thưởng</h4>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <!-- Form tìm kiếm -->
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.points.index') }}">
                                    <div class="position-relative">
                                        <input type="search" name="search" class="form-control form-control-sm ps-4 pe-3" 
                                               style="min-width: 250px;" placeholder="Tìm kiếm ID khách hàng hoặc điểm..." 
                                               autocomplete="off" value="{{ request('search') }}">
                                        <iconify-icon icon="solar:magnifer-linear"
                                            class="position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"
                                            style="font-size: 14px;"></iconify-icon>
                                    </div>
                                    
                                    <!-- Dropdown lọc nâng cao -->
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-funnel me-1"></i>Lọc thêm
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 280px;">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="form-label text-muted small mb-1">ID Khách hàng</label>
                                                    <input type="text" name="customer_id" placeholder="ID Khách hàng" value="{{ request('customer_id') }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label text-muted small mb-1">Điểm</label>
                                                    <input type="text" name="points" placeholder="Điểm" value="{{ request('points') }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                                            <i class="bi bi-funnel me-1"></i>Lọc
                                                        </button>
                                                        <a href="{{ route('admin.points.index') }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bx bx-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="checkAllPoints">
                                        </div>
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:user-bold" class="fs-5 me-1"></iconify-icon>
                                        Khách hàng
                                    </th>
                                    <th>
                                        <iconify-icon icon="mdi:coin" class="fs-5 me-1 text-warning"></iconify-icon>
                                        Điểm xu
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:calendar-bold" class="fs-5 me-1"></iconify-icon>
                                        Ngày hết hạn
                                    </th>
                                    <th class="text-center">
                                        <iconify-icon icon="solar:history-bold" class="fs-5 me-1"></iconify-icon>
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($points as $point)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input point-checkbox" value="{{ $point->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">
                                                    <iconify-icon icon="solar:user-circle-bold" class="fs-5 text-primary me-1"></iconify-icon>
                                                    {{ $point->user->name ?? 'N/A' }}
                                                </h6>
                                                <small class="text-muted">ID: {{ $point->user_id ?? $point->customer_id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-warning">
                                                <iconify-icon icon="mdi:coin" class="fs-6 text-warning me-1"></iconify-icon>
                                                {{ number_format($point->total_points ?? $point->points) }}
                                                <small class="text-muted d-block">điểm</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">
                                                    <iconify-icon icon="solar:calendar-bold" class="fs-6 me-1"></iconify-icon>
                                                    {{ $point->points_expiry_date ? \Carbon\Carbon::parse($point->points_expiry_date)->format('d/m/Y') : 'N/A' }}
                                                </div>
                                                @if($point->points_expiry_date)
                                                    @php
                                                        $expiryDate = \Carbon\Carbon::parse($point->points_expiry_date);
                                                        $daysLeft = now()->diffInDays($expiryDate, false);
                                                    @endphp
                                                    @if($daysLeft < 0)
                                                        <small class="badge bg-danger-subtle text-danger mt-1" style="font-size: 0.6rem;">Đã hết hạn</small>
                                                    @elseif($daysLeft <= 7)
                                                        <small class="badge bg-warning-subtle text-warning mt-1" style="font-size: 0.6rem;">Sắp hết hạn</small>
                                                    @elseif($daysLeft <= 30)
                                                        <small class="badge bg-info-subtle text-info mt-1" style="font-size: 0.6rem;">Còn {{ $daysLeft }} ngày</small>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.points.show', $point->id) }}"
                                                    class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-bold" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.point_history.index', ['user_id' => $point->user_id ?? $point->customer_id]) }}"
                                                    class="btn btn-soft-primary btn-sm" title="Lịch sử điểm">
                                                    <iconify-icon icon="solar:history-bold" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-gift display-4 text-muted mb-3"></i>
                                            <div>Không tìm thấy điểm thưởng nào.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $points->appends(['search' => request('search'), 'customer_id' => request('customer_id'), 'points' => request('points')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Enhanced table styling */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 600;
    color: #495057;
    border-top: none;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #f1f3f4;
    padding: 0.875rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Card enhancements */
.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem;
}

/* Form controls */
.form-control-sm, .form-select-sm {
    border-radius: 8px;
    border: 1px solid #d1d9e0;
    transition: all 0.2s ease;
}

.form-control-sm:focus, .form-select-sm:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

/* Dropdown menu */
.dropdown-menu {
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 12px;
    padding: 1rem;
}

/* Button enhancements */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.825rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

.btn-light:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-soft-primary {
    background-color: rgba(79, 70, 229, 0.1);
    border-color: rgba(79, 70, 229, 0.2);
    color: #4f46e5;
}

.btn-soft-primary:hover {
    background-color: rgba(79, 70, 229, 0.2);
    border-color: rgba(79, 70, 229, 0.3);
    color: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 500;
}

/* Status badges */
.bg-warning-subtle {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
    color: #92400e !important;
}

.bg-danger-subtle {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
    color: #991b1b !important;
}

.bg-info-subtle {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
    color: #1e40af !important;
}

/* Alert styling */
.alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    margin-bottom: 1.5rem;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

/* Points specific styling */
.text-warning {
    color: #f59e0b !important;
}

/* Enhanced user display */
.table td h6 {
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
    color: #1f2937;
    line-height: 1.3;
}

.table td small {
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.4;
}

/* Empty state styling */
.table tbody tr td i.bi-gift {
    opacity: 0.3;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .form-control-sm {
        min-width: 200px !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Checkbox functionality
    document.getElementById('checkAllPoints')?.addEventListener('change', function() {
        document.querySelectorAll('.point-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    document.querySelectorAll('.point-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.point-checkbox:checked').length === document.querySelectorAll('.point-checkbox').length;
            const checkAllBox = document.getElementById('checkAllPoints');
            if (checkAllBox) {
                checkAllBox.checked = allChecked;
            }
        });
    });

    // Auto dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
});
</script>
