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

    <!-- Statistics Cards -->
    <!-- <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold text-white-75 mb-2">Tổng phương thức</h6>
                            <h3 class="fw-bold mb-0">{{ $paymentMethods->total() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="bx bx-credit-card fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold text-white-75 mb-2">Đang hoạt động</h6>
                            <h3 class="fw-bold mb-0">{{ $paymentMethods->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="bx bx-check-circle fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold text-white-75 mb-2">Ngưng hoạt động</h6>
                            <h3 class="fw-bold mb-0">{{ $paymentMethods->where('is_active', false)->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="bx bx-x-circle fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold text-white-75 mb-2">Có logo</h6>
                            <h3 class="fw-bold mb-0">{{ $paymentMethods->where('logo_url', '!=', null)->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="bx bx-image fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <h4 class="card-title mb-0">
                                <i class="bx bx-credit-card me-2 text-primary"></i>
                                Danh Sách Phương Thức Thanh Toán
                            </h4>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <!-- Form tìm kiếm -->
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.payment_methods.index') }}">
                                    <div class="position-relative">
                                        <i class="bx bx-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                                        <input type="text" name="search" class="form-control form-control-sm ps-4" 
                                               placeholder="Tìm kiếm phương thức..." 
                                               value="{{ request('search') }}" 
                                               style="width: 200px;">
                                    </div>
                                    
                                    <select name="is_active" class="form-select form-select-sm" style="width: 150px;">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Ngưng hoạt động</option>
                                    </select>
                                    
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
                                    <th style="width: 80px;">ID</th>
                                    <th style="width: 300px;">Thông tin phương thức</th>
                                    <!-- <th style="width: 120px;">Mã</th> -->
                                    <!-- <th style="width: 100px;">Logo</th> -->
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px;">Ngày tạo</th>
                                    <th class="text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentMethods as $method)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-primary">#{{ $method->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="flex-shrink-0 method-icon-container">
                                                    @if($method->logo_url)
                                                        <img src="{{ $method->logo_url }}" 
                                                             alt="{{ $method->name }}" 
                                                             class="method-logo"
                                                             style="width: 40px !important; height: 40px !important; object-fit: contain; border-radius: 8px; border: 1px solid #e9ecef;"
                                                             loading="lazy"
                                                             onerror="this.src='{{ asset('assets/images/payment-placeholder.png') }}'; this.onerror=null;">
                                                    @else
                                                        <div class="method-placeholder d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #f3f4f6, #e5e7eb); border-radius: 8px;">
                                                            <i class="bx bx-credit-card text-muted fs-18"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold text-primary">
                                                        <i class="bx bx-wallet me-2"></i>{{ $method->name }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="bx bx-layer me-1"></i>Phương thức thanh toán
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- <td>
                                            <div class="text-center">
                                                <span class="badge bg-info-subtle text-info fs-6 px-3 py-2">
                                                    <i class="bx bx-code me-1"></i>
                                                    {{ $method->code }}
                                                </span>
                                            </div>
                                        </td> -->
                                        <!-- <td>
                                            <div class="text-center">
                                                @if($method->logo_url)
                                                    <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                                        <i class="bx bx-check me-1"></i>Có logo
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning fs-6 px-3 py-2">
                                                        <i class="bx bx-x me-1"></i>Chưa có
                                                    </span>
                                                @endif
                                            </div>
                                        </td> -->
                                        <td>
                                            <div class="text-center">
                                                @if($method->is_active)
                                                    <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                                        <i class="bx bx-check-circle me-1"></i>Hoạt động
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2">
                                                        <i class="bx bx-x-circle me-1"></i>Ngưng hoạt động
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $method->created_at ? $method->created_at->format('d/m/Y') : 'N/A' }}</div>
                                                <small class="text-muted">{{ $method->created_at ? $method->created_at->format('H:i') : '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.payment_methods.show', $method) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <i class="bx bx-show fs-18"></i>
                                                </a>
                                                <a href="{{ route('admin.payment_methods.edit', $method) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                                    <i class="bx bx-edit fs-18"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($paymentMethods->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bx bx-credit-card display-4 text-muted mb-3"></i>
                                            <div>Không tìm thấy phương thức thanh toán nào.</div>
                                            <div class="mt-2">
                                                <span class="text-muted">Hiện tại chưa có phương thức thanh toán nào trong hệ thống</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $paymentMethods->appends(['search' => request('search'), 'is_active' => request('is_active')])->links('pagination::bootstrap-5') }}
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

/* Method logo styling */
.method-icon-container {
    position: relative;
    display: inline-block;
}

.table .method-logo {
    width: 40px !important;
    height: 40px !important;
    min-width: 40px !important;
    min-height: 40px !important;
    max-width: 40px !important;
    max-height: 40px !important;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
}

.table .method-logo:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    border-color: #3b82f6;
}

.table .method-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.table .method-placeholder:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg, #e5e7eb, #d1d5db);
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

/* Statistics cards styling */
.card .card-body {
    transition: all 0.3s ease;
}

.card:hover .card-body {
    transform: translateY(-2px);
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 500;
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

/* Enhanced method name styling */
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
    
    .method-logo, .method-placeholder {
        width: 32px !important;
        height: 32px !important;
    }
}

/* Loading state */
.table tbody tr.loading {
    opacity: 0.6;
    pointer-events: none;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
