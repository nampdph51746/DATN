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
            box-shadow: 0 12px 30px rgba(0, 172, 193, 0.15);
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .badge {
            font-size: 0.95rem;
            border-radius: 50px;
            padding: 0.5em 1em;
        }
        .badge-success { background: var(--primary-teal); color: #fff; }
        .badge-warning { background: var(--accent-yellow); color: #1a202c; }
        .badge-danger { background: #dc3545; color: #fff; }
        .badge-info { background: var(--primary-teal); color: #fff; }
        .btn-primary {
            background: var(--primary-orange);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background: var(--primary-teal);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }
        .btn-success, .btn-warning, .btn-danger, .btn-info {
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-success { background: var(--primary-teal); border: none; }
        .btn-success:hover { background: var(--accent-yellow); color: #1a202c; }
        .btn-warning { background: var(--accent-yellow); border: none; color: #1a202c; }
        .btn-warning:hover { background: var(--primary-orange); color: white; }
        .btn-danger { background: #dc3545; border: none; }
        .btn-danger:hover { background: var(--primary-orange); color: white; }
        .btn-info { background: var(--primary-teal); border: none; }
        .btn-info:hover { background: var(--accent-yellow); color: #1a202c; }
        .view-detail-btn, .edit-btn {
            border-radius: 8px;
            min-width: 36px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .view-detail-btn {
            background: var(--primary-teal);
            border: 1px solid var(--primary-teal);
            color: white;
        }
        .view-detail-btn:hover {
            background: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: #1a202c;
            transform: scale(1.1);
        }
        .edit-btn {
            background: #212529;
            border: 1px solid #212529;
            color: white;
        }
        .edit-btn:hover {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
            transform: scale(1.1);
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
        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }
        .form-control:focus {
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
        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }
        .stars-display i {
            font-size: 16px;
            margin-right: 2px;
        }
        .stars-display i.text-warning { color: #ffc107; }
        .stars-display i.text-muted { color: #6c757d; }
        @media (max-width: 768px) {
            .table-responsive { font-size: 12px; }
            .btn-lg { padding: 0.5rem 1rem; font-size: 0.9rem; }
            .avatar-lg { width: 3rem; height: 3rem; font-size: 1.4rem; }
            .view-detail-btn, .edit-btn { min-width: 32px; height: 28px; }
        }
    </style>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-comments me-2"></i>
                        Quản lý đánh giá
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý toàn bộ đánh giá phim của người dùng
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.reviews.content-filter') }}" class="btn btn-primary btn-lg rounded-pill">
                        <i class="fas fa-filter me-2"></i> Lọc nội dung
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-success btn-lg rounded-pill">
                        <i class="fas fa-chart-bar me-2"></i> Thống kê
                    </a>
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
                                <i class="fas fa-comments fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng đánh giá</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $stats['total'] }}</h3>
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
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Đã duyệt</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-orange);">{{ $stats['approved'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-orange); width: {{ $stats['total'] > 0 ? ($stats['approved'] / $stats['total']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Chờ duyệt</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--accent-yellow);">{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--accent-yellow); width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-times-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Từ chối</h6>
                            <h3 class="mb-0 fw-bold" style="color: #dc3545;">{{ $stats['rejected'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: #dc3545; width: {{ $stats['total'] > 0 ? ($stats['rejected'] / $stats['total']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow mb-4 rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="m-0 fw-bold d-flex align-items-center gap-2" style="color: #fff;">
                <i class="fas fa-filter"></i> Bộ lọc
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="fw-semibold"><i class="fas fa-flag me-1"></i>Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="movie_id" class="fw-semibold"><i class="fas fa-film me-1"></i>Phim</label>
                        <select name="movie_id" id="movie_id" class="form-control">
                            <option value="">Tất cả phim</option>
                            @foreach($movies as $movie)
                                <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                                    {{ $movie->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="rating" class="fw-semibold"><i class="fas fa-star me-1"></i>Đánh giá</label>
                        <select name="rating" id="rating" class="form-control">
                            <option value="">Tất cả</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} sao
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="auto_type" class="fw-semibold"><i class="fas fa-robot me-1"></i>Loại xử lý</label>
                        <select name="auto_type" id="auto_type" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="auto" {{ request('auto_type') == 'auto' ? 'selected' : '' }}>Tự động</option>
                            <option value="manual" {{ request('auto_type') == 'manual' ? 'selected' : '' }}>Thủ công</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="search" class="fw-semibold"><i class="fas fa-search me-1"></i>Tìm kiếm</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Tên người dùng hoặc bình luận..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card border-0 shadow mb-4 rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold d-flex align-items-center gap-2" style="color: #fff;">
                <i class="fas fa-table"></i> Danh sách đánh giá
            </h6>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-sm" onclick="bulkUpdateStatus('approved')">
                    <i class="fas fa-check"></i> Duyệt
                </button>
                <button type="button" class="btn btn-warning btn-sm" onclick="bulkUpdateStatus('pending')">
                    <i class="fas fa-clock"></i> Chờ duyệt
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="bulkUpdateStatus('rejected')">
                    <i class="fas fa-times"></i> Từ chối
                </button>
                <button type="button" class="btn btn-dark btn-sm" onclick="bulkDelete()">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="30"><input type="checkbox" id="selectAll"></th>
                                <th><i class="fas fa-user me-1 text-primary"></i> Người dùng</th>
                                <th><i class="fas fa-film me-1 text-info"></i> Phim</th>
                                <th><i class="fas fa-star me-1 text-warning"></i> Đánh giá</th>
                                <th><i class="fas fa-comment-dots me-1 text-secondary"></i> Bình luận</th>
                                <th><i class="fas fa-flag me-1 text-warning"></i> Trạng thái</th>
                                <th><i class="fas fa-calendar-plus me-1 text-success"></i> Ngày tạo</th>
                                <th class="text-center pe-4"><i class="fas fa-cogs me-1 text-primary"></i> Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_reviews[]" value="{{ $review->id }}" class="review-checkbox">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm bg-primary text-white">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $review->user->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('movies.show', $review->movie->id) }}" target="_blank" class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm bg-info text-white">
                                                <i class="fas fa-film"></i>
                                            </div>
                                            <span>{{ Str::limit($review->movie->name, 30) }}</span>
                                        </a>
                                    </td>
                                    <td class="review-rating-cell">
                                        <div class="text-center">
                                            <span class="badge bg-warning text-dark mb-1">
                                                <i class="fas fa-star"></i> {{ $review->rating_star }}/5
                                            </span>
                                            <div class="stars-display">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating_star)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm bg-secondary text-white">
                                                <i class="fas fa-comment-dots"></i>
                                            </div>
                                            <span>{{ Str::limit($review->comment, 50) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($review->status)
                                            @case('approved')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle me-1"></i>Đã duyệt
                                                </span>
                                                @break
                                            @case('pending')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock me-1"></i>Chờ duyệt
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Từ chối
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="fas fa-calendar-plus me-1"></i>
                                            {{ $review->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.reviews.show', $review->id) }}" 
                                               class="btn btn-sm view-detail-btn" 
                                               title="Chi tiết"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm edit-btn" 
                                                    onclick="deleteReview({{ $review->id }})"
                                                    title="Xóa"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có đánh giá nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Action Forms -->
<form id="bulkStatusForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="review_ids" id="bulkStatusIds">
    <input type="hidden" name="status" id="bulkStatus">
</form>
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.reviews.bulk-delete') }}" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="review_ids" id="bulkDeleteIds">
</form>
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#selectAll').change(function() {
        $('.review-checkbox').prop('checked', $(this).prop('checked'));
    });
    $('.review-checkbox').change(function() {
        if (!$(this).prop('checked')) {
            $('#selectAll').prop('checked', false);
        } else if ($('.review-checkbox:checked').length === $('.review-checkbox').length) {
            $('#selectAll').prop('checked', true);
        }
    });
});
function bulkUpdateStatus(status) {
    const checkedBoxes = $('.review-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Vui lòng chọn ít nhất một đánh giá!');
        return;
    }
    const ids = checkedBoxes.map(function() { return $(this).val(); }).get();
    const statusText = { 'approved': 'duyệt', 'pending': 'chờ duyệt', 'rejected': 'từ chối' };
    if (confirm(`Bạn có chắc muốn ${statusText[status]} ${ids.length} đánh giá đã chọn?`)) {
        $('#bulkStatusForm').attr('action', '{{ route("admin.reviews.bulk-status") }}');
        $('#bulkStatusIds').val(ids.join(','));
        $('#bulkStatus').val(status);
        $('#bulkStatusForm').submit();
    }
}
function bulkDelete() {
    const checkedBoxes = $('.review-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Vui lòng chọn ít nhất một đánh giá!');
        return;
    }
    const ids = checkedBoxes.map(function() { return $(this).val(); }).get();
    if (confirm(`Bạn có chắc muốn xóa ${ids.length} đánh giá đã chọn? Hành động này không thể hoàn tác!`)) {
        $('#bulkDeleteIds').val(ids.join(','));
        $('#bulkDeleteForm').submit();
    }
}
function deleteReview(id) {
    if (confirm('Bạn có chắc muốn xóa đánh giá này? Hành động này không thể hoàn tác!')) {
        $('#deleteForm').attr('action', `/admin/reviews/${id}`);
        $('#deleteForm').submit();
    }
}
</script>
@endsection