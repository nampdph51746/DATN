@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <iconify-icon icon="solar:check-circle-linear" class="me-2"></iconify-icon>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center gap-3 py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:tag-linear" class="text-white fs-18"></iconify-icon>
                            </div>
                        </div>
                        <div>
                            <h4 class="card-title mb-0 text-dark fw-semibold">Quản lý thể loại phim</h4>
                            <p class="text-muted mb-0 small">Danh sách tất cả thể loại phim trong hệ thống</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <form id="delete-selected-genre-form" action="{{ route('admin.genres.bulkDelete') }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="ids" id="selected-genre-ids">
                            <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1" onclick="return confirm('Bạn có chắc muốn xóa các thể loại đã chọn?')">
                                <iconify-icon icon="solar:trash-bin-trash-linear" style="font-size: 16px;"></iconify-icon>
                                <span class="d-none d-sm-inline">Xóa đã chọn</span>
                            </button>
                        </form>

                        <form class="app-search d-none d-md-block" method="GET" action="{{ route('admin.genres.index') }}">
                            <div class="position-relative">
                                <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-pill border-0 bg-light" placeholder="Tìm kiếm thể loại..." autocomplete="off" value="{{ request('query') }}" style="width: 280px;">
                                <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                            </div>
                        </form>

                        <a href="{{ route('admin.genres.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                            <iconify-icon icon="solar:add-circle-linear" style="font-size: 16px;"></iconify-icon>
                            <span class="d-none d-sm-inline">Thêm thể loại</span>
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4" style="width: 60px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkAllGenres">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-semibold">Tên thể loại</th>
                                    <th class="border-0 fw-semibold">Mô tả</th>
                                    <th class="border-0 fw-semibold" style="width: 150px;">Thời gian tạo</th>
                                    <th class="border-0 fw-semibold" style="width: 150px;">Thời gian cập nhật</th>
                                    <th class="border-0 fw-semibold text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($genres as $genre)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input genre-checkbox" value="{{ $genre->id }}" id="genreCheck{{ $genre->id }}">
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:tag-linear" style="font-size: 16px;"></iconify-icon>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold text-dark">{{ $genre->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-muted">{{ $genre->description ?? 'Chưa có mô tả' }}</span>
                                        </td>
                                        <td class="py-3">
                                            @if($genre->created_at)
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium text-dark">{{ $genre->created_at->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ $genre->created_at->format('H:i') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @if($genre->updated_at)
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium text-dark">{{ $genre->updated_at->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ $genre->updated_at->format('H:i') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.genres.edit', $genre->id) }}" 
                                                   class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                                   style="width: 32px; height: 32px;"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-linear" style="font-size: 16px;"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                                            style="width: 32px; height: 32px;"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            title="Xóa"
                                                            onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                        <iconify-icon icon="solar:trash-bin-trash-linear" style="font-size: 16px;"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="mb-3">
                                                    <iconify-icon icon="solar:tag-broken" class="text-muted" style="font-size: 64px;"></iconify-icon>
                                                </div>
                                                <h5 class="text-muted mb-2">Chưa có thể loại nào</h5>
                                                <p class="text-muted mb-3">Hiện tại chưa có thể loại phim nào trong hệ thống</p>
                                                <a href="{{ route('admin.genres.create') }}" class="btn btn-primary">
                                                    <iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
                                                    Thêm thể loại đầu tiên
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($genres->hasPages())
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center text-muted">
                        <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                        <small>
                            Hiển thị {{ $genres->firstItem() ?? 0 }} đến {{ $genres->lastItem() ?? 0 }} 
                            trong tổng số {{ $genres->total() }} thể loại
                        </small>
                    </div>
                    <div>
                        {{ $genres->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.dropdown-item {
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

.dropdown-item.active {
    background-color: var(--bs-primary) !important;
    color: white !important;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

.form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .d-none.d-md-block {
        display: none !important;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.6875rem;
    }
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

    // Hiện/ẩn nút xóa đã chọn
    function updateDeleteGenreButton() {
        const checked = document.querySelectorAll('.genre-checkbox:checked');
        const form = document.getElementById('delete-selected-genre-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }

    // Chọn tất cả
    document.getElementById('checkAllGenres')?.addEventListener('change', function() {
        document.querySelectorAll('.genre-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteGenreButton();
    });

    // Check từng dòng
    document.querySelectorAll('.genre-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteGenreButton);
    });

    // Khi submit form xóa, lấy id các thể loại đã chọn
    document.getElementById('delete-selected-genre-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.genre-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-genre-ids').value = checked.join(',');
    });

    // Auto-submit search form with debounce
    const searchInput = document.querySelector('input[name="query"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
});
</script>
@endsection