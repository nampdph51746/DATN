{{-- filepath: resources/views/admin/movies/ageLimit/index.blade.php --}}
@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Alert Messages với animation -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle bg-success-subtle">
                        <span class="avatar-title rounded-circle bg-success text-white">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Thành công!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-shield-check text-warning me-2"></i>
                        Quản lý giới hạn độ tuổi
                    </h4>
                    <p class="text-muted mb-0">Quản lý các giới hạn độ tuổi cho phim trong hệ thống</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @can('create age limit')
                        <a href="{{ route('admin.age_limits.create') }}" class="btn btn-warning btn-lg rounded-pill shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i> Thêm giới hạn mới
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-white">
                                    <i class="bi bi-shield-check fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng giới hạn</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $ageLimits->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-white">
                                    <i class="bi bi-0-circle fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Không giới hạn</h6>
                            <h3 class="mb-0 fw-bold text-info">{{ $ageLimits->where('min_age', 0)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-info" style="width: {{ $ageLimits->total() > 0 ? ($ageLimits->where('min_age', 0)->count() / $ageLimits->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success text-white">
                                    <i class="bi bi-person-check fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Trẻ em (< 13)</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $ageLimits->where('min_age', '>', 0)->where('min_age', '<', 13)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 65%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-danger-subtle">
                                <span class="avatar-title rounded-circle bg-danger text-white">
                                    <i class="bi bi-person-x fs-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Người lớn (18+)</h6>
                            <h3 class="mb-0 fw-bold text-danger">{{ $ageLimits->where('min_age', '>=', 18)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar bg-danger" style="width: {{ $ageLimits->total() > 0 ? ($ageLimits->where('min_age', '>=', 18)->count() / $ageLimits->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 py-4 rounded-top-4">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="bi bi-table me-2 text-warning"></i>
                                Danh sách giới hạn độ tuổi
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                @can('delete age limit')
                                    <form id="delete-selected-age-limit-form" method="POST" action="{{ route('admin.age_limits.bulkDelete') }}" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="ids" id="selected-age-limit-ids">
                                        <button type="submit" class="btn btn-danger btn-lg rounded-pill">
                                            <i class="bi bi-trash3 me-2"></i>Xóa đã chọn
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-bold text-dark" style="width: 50px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="checkAllAgeLimits">
                                            <label class="form-check-label" for="checkAllAgeLimits"></label>
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-shield-check me-1"></i> Tên giới hạn
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-file-text me-1"></i> Mô tả
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="bi bi-calendar-event me-1"></i> Độ tuổi tối thiểu
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="bi bi-gear me-1"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ageLimits as $ageLimit)
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input age-limit-checkbox" 
                                                       value="{{ $ageLimit->id }}" id="ageLimitCheck{{ $ageLimit->id }}">
                                                <label class="form-check-label" for="ageLimitCheck{{ $ageLimit->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-sm rounded-circle bg-warning-subtle">
                                                        <span class="avatar-title rounded-circle bg-warning text-white">
                                                            @if($ageLimit->min_age == 0)
                                                                <i class="bi bi-unlock"></i>
                                                            @elseif($ageLimit->min_age >= 18)
                                                                <i class="bi bi-person-x"></i>
                                                            @else
                                                                <i class="bi bi-person-check"></i>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $ageLimit->name }}</h6>
                                                    <small class="text-muted">ID: #{{ $ageLimit->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="description-content">
                                                @if($ageLimit->description)
                                                    <p class="mb-0 text-muted">{{ Str::limit($ageLimit->description, 60) }}</p>
                                                @else
                                                    <span class="text-muted fst-italic">Không có mô tả</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($ageLimit->min_age == 0)
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                        <i class="bi bi-unlock me-1"></i>Không giới hạn
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                                        <i class="bi bi-calendar-event me-1"></i>{{ $ageLimit->min_age }}+ tuổi
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                @can('edit age limit')
                                                    <a href="{{ route('admin.age_limits.edit', $ageLimit->id) }}"
                                                       class="btn btn-sm rounded-3 edit-btn" 
                                                       title="Chỉnh sửa"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-shield-check text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có giới hạn độ tuổi nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm giới hạn độ tuổi mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($ageLimits->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $ageLimits->firstItem() }}-{{ $ageLimits->lastItem() }} trong tổng {{ $ageLimits->total() }} giới hạn
                            </div>
                            <div>
                                {{ $ageLimits->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Hiện/ẩn nút xóa đã chọn
    function updateDeleteAgeLimitButton() {
        const checked = document.querySelectorAll('.age-limit-checkbox:checked');
        const form = document.getElementById('delete-selected-age-limit-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }

    // Chọn tất cả
    document.getElementById('checkAllAgeLimits')?.addEventListener('change', function() {
        document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteAgeLimitButton();
    });

    // Check từng dòng
    document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteAgeLimitButton);
    });

    document.getElementById('delete-selected-age-limit-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const checked = Array.from(document.querySelectorAll('.age-limit-checkbox:checked')).map(cb => cb.value);

        if (checked.length === 0) {
            alert('Bạn chưa chọn mục nào!');
            return;
        }

        if (confirm('Bạn có chắc muốn xóa các giới hạn đã chọn?')) {
            document.getElementById('selected-age-limit-ids').value = checked.join(',');
            this.submit();
        }
    });
});
</script>

<style>
/* Modern UI Styles */
.card {
    transition: all 0.3s ease;
    border: none !important;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
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

.rounded-pill {
    border-radius: 50rem !important;
}

.rounded-4 {
    border-radius: 0.75rem !important;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-lg {
    width: 3.5rem;
    height: 3.5rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* THÊM ACTION BUTTONS CSS: */
/* Action Buttons */
.edit-btn {
    background-color: #212529 !important;
    border: 1px solid #212529 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.edit-btn:hover {
    background-color: #343a40 !important;
    border-color: #343a40 !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(33, 37, 41, 0.3);
}

.edit-btn i {
    font-size: 14px;
    color: white !important;
}

/* XÓA DROPDOWN STYLES */

.alert {
    border-radius: 0.75rem;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.progress {
    background-color: rgba(0,0,0,0.05);
}

.bg-warning-subtle {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
}

.description-content {
    max-width: 200px;
}

/* Tooltip styling */
.tooltip {
    font-size: 12px;
}

@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.85rem;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
    
    .description-content {
        max-width: 120px;
    }
    
    .edit-btn {
        min-width: 32px;
        height: 28px;
    }
}
</style>
@endsection