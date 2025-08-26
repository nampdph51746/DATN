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
                            <h4 class="card-title mb-0">
                                <i class="bi bi-camera-reels-fill me-2 text-success"></i>
                                🤵‍♂️🤵‍♀️ Danh Sách Đạo Diễn
                            </h4>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <!-- Form tìm kiếm -->
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.directors.index') }}">
                                    <div class="position-relative">
                                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                                        <input type="text" name="query" class="form-control form-control-sm ps-4" 
                                               placeholder="Tìm kiếm đạo diễn..." 
                                               value="{{ request('query') }}" 
                                               style="width: 200px;">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bx bx-search"></i>
                                    </button>
                                </form>

                                <!-- Nút thêm -->
                                <a href="{{ route('admin.directors.create') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm đạo diễn
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form xóa hàng loạt -->
                    <form id="delete-selected-form" action="{{ route('admin.directors.bulkDelete') }}" method="POST" style="display: none;" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-director-ids">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các đạo diễn đã chọn?')">
                            <i class="bi bi-trash me-1"></i>Xóa đã chọn
                        </button>
                    </form>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="checkAllDirectors">
                                        </div>
                                    </th>
                                    <th style="width: 280px;">Thông tin đạo diễn</th>
                                    <th style="width: 120px;">Ngày sinh</th>
                                    <th style="width: 120px;">Quốc tịch</th>
                                    <th style="width: 100px;">Số phim</th>
                                    <th style="width: 100px;">Trạng thái</th>
                                    <th style="width: 120px;">Ngày tạo</th>
                                    <th class="text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($directors as $director)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input director-checkbox" value="{{ $director->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $director->image_path ? Storage::url($director->image_path) : asset('assets/images/director-placeholder.png') }}" 
                                                         alt="{{ $director->name }}" 
                                                         class="director-avatar"
                                                         loading="lazy"
                                                         onerror="this.src='{{ asset('assets/images/director-placeholder.png') }}'; this.onerror=null;">
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold text-success">
                                                        <i class="bi bi-person-video3 me-2"></i>{{ $director->name }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-camera-reels me-1"></i>Đạo diễn phim
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @if($director->birth_date)
                                                    <div class="fw-medium">{{ $director->birth_date->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ $director->birth_date->age }} tuổi</small>
                                                @else
                                                    <span class="text-muted fst-italic">Chưa có thông tin</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                @if($director->nationality)
                                                    <span class="badge bg-secondary-subtle text-secondary fs-6 px-3 py-2">
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        {{ $director->nationality }}
                                                    </span>
                                                @else
                                                    <span class="text-muted fst-italic">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <span class="badge bg-info-subtle text-info fs-6 px-3 py-2">
                                                    <i class="bi bi-film me-1"></i>
                                                    {{ $director->movies_count ?? $director->movies->count() }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                @if($director->is_active)
                                                    <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                                        <i class="bi bi-check-circle me-1"></i>Hoạt động
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2">
                                                        <i class="bi bi-x-circle me-1"></i>Ngưng hoạt động
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $director->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $director->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.directors.show', $director->id) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.directors.edit', $director->id) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa đạo diễn">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                @if(($director->movies_count ?? $director->movies->count()) == 0)
                                                    <form action="{{ route('admin.directors.destroy', $director->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đạo diễn này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa đạo diễn">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-soft-secondary btn-sm" disabled title="Không thể xóa vì đang có phim">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($directors->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-camera-reels display-4 text-muted mb-3"></i>
                                            <div>Không tìm thấy đạo diễn nào.</div>
                                            <div class="mt-2">
                                                <a href="{{ route('admin.directors.create') }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-plus-circle me-1"></i>Thêm đạo diễn đầu tiên
                                                </a>
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
                        {{ $directors->appends(['query' => request('query')])->links('pagination::bootstrap-5') }}
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

/* Director avatar styling */
.director-avatar {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border: 2px solid #ffffff;
    transition: all 0.2s ease;
}

.director-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
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

/* Enhanced director name styling */
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
    
    .director-avatar {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 576px) {
    /* Additional mobile styles if needed */
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

    function updateDeleteButton() {
        const checked = document.querySelectorAll('.director-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        if (form) {
            if (checked.length > 0) {
                form.style.display = 'inline-block';
            } else {
                form.style.display = 'none';
            }
        }
    }

    document.getElementById('checkAllDirectors')?.addEventListener('change', function () {
        document.querySelectorAll('.director-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    document.querySelectorAll('.director-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    const deleteForm = document.getElementById('delete-selected-form');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function (e) {
            const checked = Array.from(document.querySelectorAll('.director-checkbox:checked')).map(cb => cb.value);
            if (checked.length === 0) {
                e.preventDefault();
                return false;
            }
            document.getElementById('selected-director-ids').value = checked.join(',');
        });
    }
});
</script>
@endsection