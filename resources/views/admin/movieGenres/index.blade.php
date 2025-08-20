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
                                <i class="bi bi-collection-fill me-2 text-primary"></i>
                                🎭 Danh Sách Thể Loại Phim
                            </h4>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <!-- Form tìm kiếm -->
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.genres.index') }}">
                                    <div class="position-relative">
                                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                                        <input type="text" name="search" class="form-control form-control-sm ps-4" 
                                               placeholder="Tìm kiếm thể loại..." 
                                               value="{{ request('search') }}" 
                                               style="width: 200px;">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-sm bg-primary text-white">
                                        <i class="bi bi-search">Tìm/lọc</i>
                                    </button>
                                </form>

                                <!-- Nút thêm thể loại -->
                                @can('create genre')
                                <a href="{{ route('admin.genres.create') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm thể loại
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form xóa hàng loạt -->
                    <form id="delete-selected-form" action="{{ route('admin.genres.bulkDelete') }}" method="POST" style="display: none;" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-genre-ids">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các thể loại đã chọn?')">
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
                                            <input type="checkbox" class="form-check-input" id="checkAllGenres">
                                        </div>
                                    </th>
                                    <th style="width: 250px;">Tên thể loại</th>
                                    <th>Mô tả</th>
                                    <th style="width: 100px;">Số phim</th>
                                    <th style="width: 120px;">Ngày tạo</th>
                                    <th class="text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($genres as $index => $genre)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input genre-checkbox" value="{{ $genre->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-semibold text-primary">
                                                    <i class="bi bi-collection me-2"></i>{{ $genre->name }}
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-tag-fill me-1"></i>Thể loại phim
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="description-text" style="max-width: 300px;">
                                                @if($genre->description)
                                                    <span class="text-muted">{{ Str::limit($genre->description, 100) }}</span>
                                                    @if(strlen($genre->description) > 100)
                                                        <small class="text-info" data-bs-toggle="tooltip" title="{{ $genre->description }}">
                                                            <i class="bi bi-info-circle ms-1"></i>
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-muted fst-italic">Chưa có mô tả</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <span class="badge bg-info-subtle text-info fs-6 px-3 py-2">
                                                    <i class="bi bi-film me-1"></i>
                                                    {{ $genre->movies_count ?? 0 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $genre->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $genre->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @can('view genre')
                                                <a href="{{ route('admin.genres.show', $genre) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                @endcan
                                                @can('edit genre')
                                                <a href="{{ route('admin.genres.edit', $genre) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa thể loại">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                @endcan
                                                @can('delete genre')
                                                <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa thể loại này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa thể loại">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($genres->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-collection display-4 text-muted mb-3"></i>
                                            <div>Không tìm thấy thể loại nào.</div>
                                            @can('create genre')
                                            <div class="mt-2">
                                                <a href="{{ route('admin.genres.create') }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-plus-circle me-1"></i>Thêm thể loại đầu tiên
                                                </a>
                                            </div>
                                            @endcan
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $genres->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
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

/* Description text styling */
.description-text {
    line-height: 1.4;
}
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
    /* Additional mobile styles if needed */
}

/* Loading state */
.table tbody tr.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Enhanced genre name styling */
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

/* Description text styling */
.description-text {
    line-height: 1.4;
}

/* Responsive improvements */
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
        const checked = document.querySelectorAll('.genre-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }   

    document.getElementById('checkAllGenres')?.addEventListener('change', function() {
        document.querySelectorAll('.genre-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    document.querySelectorAll('.genre-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    document.getElementById('delete-selected-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.genre-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-genre-ids').value = checked.join(',');
    });
});
</script>
@endsection