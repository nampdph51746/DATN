{{-- filepath: resources/views/admin/movies/ageLimit/index.blade.php --}}
@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <style>
        :root {
            --primary-orange: #FF6F00;
            --primary-teal: #00ACC1;
            --accent-yellow: #FFCA28;
            --neutral-bg: #F8FAFC;
            --card-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--neutral-bg);
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .alert-success {
            background: rgba(0, 172, 193, 0.1);
            color: var(--primary-teal);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #DC3545;
        }

        .table > :not(caption) > * > * {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background: rgba(0, 172, 193, 0.05);
            transform: translateX(2px);
        }

        .btn {
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary-orange);
            border: none;
            border-radius: 50px;
        }

        .btn-primary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }

        .btn-success {
            background: var(--primary-teal);
            border: none;
            border-radius: 50px;
        }

        .btn-success:hover {
            background: var(--accent-yellow);
            color: #1a202c;
            transform: translateY(-2px);
        }

        .btn-info {
            background: var(--accent-yellow);
            border: none;
            border-radius: 50px;
            color: #1a202c;
        }

        .btn-info:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }

        .btn-warning {
            background: var(--accent-yellow);
            border: none;
            border-radius: 50px;
            color: #1a202c;
        }

        .btn-warning:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border-color: var(--primary-teal);
            color: var(--primary-teal);
            border-radius: 50px;
        }

        .btn-outline-primary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
        }

        .edit-btn {
            background: var(--primary-orange);
            border: 1px solid var(--primary-orange);
            color: white;
            border-radius: 8px;
            min-width: 36px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .edit-btn:hover {
            background: var(--primary-teal);
            border-color: var(--primary-teal);
            color: white;
            transform: scale(1.1);
        }

        .edit-btn i {
            font-size: 14px;
            color: white !important;
        }

        .avatar-lg {
            width: 4rem;
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            border-radius: 50%;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.2);
        }

        .input-group-text {
            background: var(--neutral-bg);
            border-color: #e0e0e0;
            border-radius: 8px;
        }

        .pagination {
            --bs-pagination-border-radius: 8px;
        }

        .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 3px;
            color: var(--primary-teal);
            transition: var(--transition);
        }

        .page-link:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: var(--primary-teal);
            border: none;
            color: white;
        }

        .form-check-input:checked {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card, .alert, .table tbody tr {
            animation: fadeInUp 0.6s ease-out;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .avatar-lg {
                width: 3rem;
                height: 3rem;
                font-size: 1.4rem;
            }

            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .input-group {
                min-width: 100% !important;
            }
        }
    </style>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle">
                        <i class="fas fa-check-circle fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Thành công!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                    @if(session('age_limit_created'))
                        <small class="d-block mt-1 text-success-emphasis">
                            <i class="fas fa-info-circle me-1"></i>
                            Giới hạn độ tuổi mới đã được thêm vào đầu danh sách bên dưới.
                        </small>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm rounded-circle">
                        <i class="fas fa-exclamation-triangle fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading mb-1 fw-bold">Có lỗi xảy ra!</h6>
                    <p class="mb-0">{{ session('error') }}</p>
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
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-shield-alt me-2"></i>
                        Quản lý giới hạn độ tuổi
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý các giới hạn độ tuổi cho phim trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @can('create age limit')
                        <a href="{{ route('admin.age_limits.create') }}" class="btn btn-primary btn-lg rounded-pill">
                            <i class="fas fa-plus-circle me-2"></i> Thêm giới hạn mới
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-shield-alt fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng giới hạn</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $ageLimits->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-teal); width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-unlock fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Không giới hạn</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-orange);">{{ $ageLimits->where('min_age', 0)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-orange); width: {{ $ageLimits->total() > 0 ? ($ageLimits->where('min_age', 0)->count() / $ageLimits->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-child fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Trẻ em (< 13)</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--accent-yellow);">{{ $ageLimits->where('min_age', '>', 0)->where('min_age', '<', 13)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--accent-yellow); width: {{ $ageLimits->total() > 0 ? ($ageLimits->where('min_age', '>', 0)->where('min_age', '<', 13)->count() / $ageLimits->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-user-check fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Người lớn (18+)</h6>
                            <h3 class="mb-0 fw-bold" style="color: #dc3545;">{{ $ageLimits->where('min_age', '>=', 18)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: #dc3545; width: {{ $ageLimits->total() > 0 ? ($ageLimits->where('min_age', '>=', 18)->count() / $ageLimits->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-table me-2"></i>
                                Danh sách giới hạn độ tuổi
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Bulk Actions -->
                            @can('delete age limit')
                                <form id="delete-selected-form" action="{{ route('admin.age_limits.bulkDelete') }}" method="POST" style="display: none;" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="ids" id="selected-age-limit-ids">
                                    <div class="alert alert-warning border-0 rounded-4 d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle text-warning fs-4 me-3"></i>
                                        <div class="flex-grow-1">
                                            <strong>Xóa nhiều giới hạn đã chọn</strong>
                                            <p class="mb-0 small">Bạn có chắc muốn xóa các giới hạn đã chọn?</p>
                                        </div>
                                        <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('Bạn có chắc muốn xóa các giới hạn đã chọn?')">
                                            <i class="fas fa-trash me-1"></i> Xóa đã chọn
                                        </button>
                                    </div>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4" style="width: 50px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkAllAgeLimits">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-shield-alt me-1" style="color: var(--primary-teal);"></i> Tên giới hạn
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-align-left me-1" style="color: var(--primary-orange);"></i> Mô tả
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-birthday-cake me-1" style="color: var(--accent-yellow);"></i> Độ tuổi tối thiểu
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-plus me-1" style="color: var(--primary-teal);"></i> Thời gian tạo
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-orange);"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ageLimits as $ageLimit)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input age-limit-checkbox" value="{{ $ageLimit->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-sm rounded-circle" 
                                                         style="background: {{ $ageLimit->min_age == 0 ? 'linear-gradient(135deg, #10b981, #059669)' : ($ageLimit->min_age >= 18 ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 'linear-gradient(135deg, #f59e0b, #d97706)') }}; color: white; display: flex; align-items: center; justify-content: center;">
                                                        @if($ageLimit->min_age == 0)
                                                            <i class="fas fa-unlock"></i>
                                                        @elseif($ageLimit->min_age >= 18)
                                                            <i class="fas fa-user-check"></i>
                                                        @else
                                                            <i class="fas fa-child"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold" style="color: var(--primary-teal);">{{ $ageLimit->name }}</h6>
                                                    <small class="text-muted">ID: #{{ $ageLimit->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-align-left text-primary me-2" style="color: var(--primary-orange);"></i>
                                                @if($ageLimit->description)
                                                    <span class="fw-medium">{{ Str::limit($ageLimit->description, 50) }}</span>
                                                @else
                                                    <span class="text-muted fst-italic">Không có mô tả</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-birthday-cake text-warning me-2" style="color: var(--accent-yellow);"></i>
                                                @if($ageLimit->min_age == 0)
                                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                                        <i class="fas fa-unlock me-1"></i>Không giới hạn
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning rounded-pill px-3 py-2">
                                                        <i class="fas fa-birthday-cake me-1"></i>{{ $ageLimit->min_age }}+ tuổi
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-plus text-info me-2" style="color: var(--primary-teal);"></i>
                                                <span class="text-muted">{{ $ageLimit->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                @can('edit age limit')
                                                    <a href="{{ route('admin.age_limits.edit', $ageLimit->id) }}"
                                                       class="btn btn-sm edit-btn" 
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
                                        <td colspan="6" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-shield-alt text-muted fs-1 mb-3"></i>
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
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Update delete button visibility
    function updateDeleteButton() {
        const checked = document.querySelectorAll('.age-limit-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        form.style.display = checked.length > 0 ? 'block' : 'none';
    }   

    // Check all age limits
    document.getElementById('checkAllAgeLimits')?.addEventListener('change', function() {
        document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    // Individual checkbox change
    document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    // Handle bulk delete form submission
    document.getElementById('delete-selected-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.age-limit-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-age-limit-ids').value = checked.join(',');
    });
});
</script>
@endsection