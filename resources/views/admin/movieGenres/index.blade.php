@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-primary">Quản lý thể loại phim</h2>
            <p class="text-muted mb-0">Quản lý danh sách các thể loại phim trong hệ thống</p>
        </div>
        <div class="d-flex gap-2">
            <form id="delete-selected-genre-form" action="{{ route('admin.genres.bulkDelete') }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="ids" id="selected-genre-ids">
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Bạn có chắc muốn xóa các thể loại đã chọn?')">
                    <i class="fas fa-trash me-1"></i> Xóa đã chọn
                </button>
            </form>
            @can('create genre')
            <a href="{{ route('admin.genres.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> Thêm thể loại
            </a>
            @endcan
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-4 py-3" style="width: 50px;">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="checkAllGenres">
                                </div>
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">Tên thể loại</th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">Mô tả</th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark text-center" style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($genres as $genre)
                            <tr class="border-bottom border-light">
                                <td class="px-4 py-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input genre-checkbox" value="{{ $genre->id }}" id="genreCheck{{ $genre->id }}">
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="genre-icon me-3">
                                            <i class="fas fa-film text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $genre->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-muted">{{ Str::limit($genre->description, 80) ?: 'Chưa có mô tả' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        @can('edit genre')
                                        <a href="{{ route('admin.genres.edit', $genre->id) }}" 
                                           class="btn btn-sm rounded-3 edit-btn" 
                                           title="Chỉnh sửa"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete genre')
                                        <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm rounded-3 delete-btn" 
                                                    title="Xóa"
                                                    data-bs-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc muốn xóa thể loại này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-film text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3 text-muted">Chưa có thể loại phim nào</h5>
                                        <p class="text-muted mb-3">Hãy thêm thể loại phim đầu tiên</p>
                                        @can('create genre')
                                        <a href="{{ route('admin.genres.create') }}" class="btn btn-primary rounded-pill">
                                            <i class="fas fa-plus me-1"></i> Thêm thể loại
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($genres->hasPages())
        <div class="card-footer bg-transparent border-0 pt-0">
            <div class="d-flex justify-content-center">
                {{ $genres->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Modern UI Styles */
.card {
    transition: all 0.3s ease;
}

.table > :not(caption) > * > * {
    padding: 1rem 1.5rem;
    border-bottom-width: 1px;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(108, 117, 125, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.genre-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    color: white;
}

/* Action Buttons */
.edit-btn {
    background-color: #17a2b8 !important;
    border: 1px solid #17a2b8 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.edit-btn:hover {
    background-color: #138496 !important;
    border-color: #117a8b !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(23, 162, 184, 0.3);
}

.delete-btn {
    background-color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    color: white !important;
    transition: all 0.3s ease;
    min-width: 36px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.delete-btn:hover {
    background-color: #c82333 !important;
    border-color: #bd2130 !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(220, 53, 69, 0.3);
}

.edit-btn i,
.delete-btn i {
    font-size: 14px;
}

/* Alert Styles */
.alert {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Pagination */
.pagination {
    --bs-pagination-border-radius: 0.5rem;
}

.page-link {
    border: none;
    margin: 0 2px;
    border-radius: 8px !important;
    transition: all 0.3s ease;
}

.page-link:hover {
    background-color: #667eea;
    color: white;
    transform: translateY(-1px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

/* Empty State */
.empty-state {
    padding: 3rem 2rem;
}

/* Form Check */
.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

/* Responsive */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .genre-icon {
        width: 35px;
        height: 35px;
    }
    
    .edit-btn,
    .delete-btn {
        min-width: 32px;
        height: 28px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    function updateDeleteGenreButton() {
        const checked = document.querySelectorAll('.genre-checkbox:checked');
        const form = document.getElementById('delete-selected-genre-form');
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
        updateDeleteGenreButton();
    });

    document.querySelectorAll('.genre-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteGenreButton);
    });

    document.getElementById('delete-selected-genre-form').addEventListener('submit', function(e) {
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