@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
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

        .table > :not(caption) > * > * {
            padding: 1.2rem 1.5rem;
            border-bottom-width: 1px;
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background: rgba(0, 172, 193, 0.05);
            transform: translateY(-2px);
        }

        .genre-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .edit-btn, .delete-btn {
            border-radius: 8px;
            min-width: 36px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .edit-btn {
            background: var(--primary-teal);
            border: 1px solid var(--primary-teal);
            color: white;
        }

        .edit-btn:hover {
            background: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: #1a202c;
            transform: scale(1.1);
        }

        .delete-btn {
            background: #DC3545;
            border: 1px solid #DC3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
            border-color: #bd2130;
            color: white;
            transform: scale(1.1);
        }

        .btn-primary {
            background: var(--primary-orange);
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--primary-teal);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }

        .alert-success {
            background: rgba(0, 172, 193, 0.1);
            color: var(--primary-teal);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }

        .pagination {
            --bs-pagination-border-radius: 8px;
        }

        .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 3px;
            transition: var(--transition);
            color: var(--primary-teal);
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

        .empty-state {
            padding: 3rem 2rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: var(--primary-teal);
            margin-bottom: 1rem;
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

            .genre-icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .edit-btn, .delete-btn {
                min-width: 32px;
                height: 28px;
            }

            .btn-primary {
                padding: 0.5rem 1rem;
            }
        }
    </style>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold" style="color: var(--primary-orange);">
                <i class="fas fa-film me-2"></i> Quản lý thể loại phim
            </h2>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle me-1"></i> Quản lý danh sách các thể loại phim
            </p>
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
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Card -->
    <div class="card border-0">
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
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-film me-2" style="color: var(--primary-orange);"></i> Tên thể loại
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-align-left me-2" style="color: var(--primary-teal);"></i> Mô tả
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark text-center" style="width: 150px;">
                                <i class="fas fa-cogs me-2" style="color: var(--accent-yellow);"></i> Thao tác
                            </th>
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
                                            <i class="fas fa-film"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold" style="color: var(--primary-teal);">{{ $genre->name }}</h6>
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
                                           class="btn btn-sm edit-btn" 
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
                                                    class="btn btn-sm delete-btn" 
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
                                        <i class="fas fa-film"></i>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Update delete button visibility
    function updateDeleteGenreButton() {
        const checked = document.querySelectorAll('.genre-checkbox:checked');
        const form = document.getElementById('delete-selected-genre-form');
        form.style.display = checked.length > 0 ? 'inline-block' : 'none';
    }

    // Check all genres
    document.getElementById('checkAllGenres')?.addEventListener('change', function() {
        document.querySelectorAll('.genre-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteGenreButton();
    });

    // Individual checkbox change
    document.querySelectorAll('.genre-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteGenreButton);
    });

    // Handle bulk delete form submission
    document.getElementById('delete-selected-genre-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.genre-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-genre-ids').value = checked.join(',');
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -30px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(row);
    });
});
</script>
@endsection