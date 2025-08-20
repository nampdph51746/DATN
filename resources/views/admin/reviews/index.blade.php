@extends('layouts.admin.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Quản lý đánh giá</h1>
            <small class="text-muted">Cập nhật lần cuối: {{ now()->format('H:i:s d/m/Y') }}</small>
        </div>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.reviews.content-filter') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-filter"></i> Lọc nội dung
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-info btn-sm">
                <i class="fas fa-chart-bar"></i> Xem thống kê
            </a>
            <a href="{{ route('admin.reviews.recalculate-all') }}" class="btn btn-warning btn-sm" 
               onclick="return confirm('Bạn có chắc muốn tính lại điểm trung bình cho tất cả phim?')">
                <i class="fas fa-calculator"></i> Tính lại điểm TB
            </a>
            <a href="{{ route('admin.reviews.reset-stats') }}" class="btn btn-danger btn-sm" 
               onclick="return confirm('Bạn có chắc muốn reset tất cả thống kê? Thao tác này sẽ cập nhật lại toàn bộ dữ liệu.')">
                <i class="fas fa-sync-alt"></i> Reset Stats
            </a>
            <a href="{{ route('admin.reviews.index', ['refresh' => 1]) }}" class="btn btn-success btn-sm">
                <i class="fas fa-refresh"></i> Làm mới
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng đánh giá</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đã duyệt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ duyệt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Từ chối</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="movie_id">Phim</label>
                        <select name="movie_id" id="movie_id" class="form-control">
                            <option value="">Tất cả phim</option>
                            @foreach($movies as $movie)
                                <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                                    {{ $movie->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="col-md-2">
                        <label for="rating">Đánh giá</label>
                        <select name="rating" id="rating" class="form-control">
                            <option value="">Tất cả</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} sao
                                </option>
                            @endfor
                        </select>
                    </div> -->
                    <div class="col-md-2">
                        <label for="auto_type">Loại xử lý</label>
                        <select name="auto_type" id="auto_type" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="auto" {{ request('auto_type') == 'auto' ? 'selected' : '' }}>Tự động</option>
                            <option value="manual" {{ request('auto_type') == 'manual' ? 'selected' : '' }}>Thủ công</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="search">Tìm kiếm</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Tên người dùng hoặc bình luận..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đánh giá</h6>
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
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th width="120">Người dùng</th>
                                <th width="150">Phim</th>
                                <!-- <th width="120">Đánh giá</th> -->
                                <th width="200">Bình luận</th>
                                <th width="100">Trạng thái</th>
                                <th width="120">Ngày tạo</th>
                                <th width="100">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_reviews[]" value="{{ $review->id }}" class="review-checkbox">
                                    </td>
                                    <td>{{ $review->user->name }}</td>
                                    <td>
                                        <a href="{{ route('movies.show', $review->movie->id) }}" target="_blank">
                                            {{ Str::limit($review->movie->name, 30) }}
                                        </a>
                                    </td>
                                    <!-- <td class="review-rating-cell">
                                        <div class="text-center">
                                            <div class="mb-1">
                                                <span class="badge bg-warning text-dark">{{ $review->rating_star }}/5</span>
                                            </div>
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
                                    </td> -->
                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                    <td>
                                        @switch($review->status)
                                            @case('approved')
                                                <span class="badge bg-success text-white">Đã duyệt</span>
                                                @if($review->admin_note && str_contains($review->admin_note, 'ÉP DUYỆT'))
                                                    <br><small class="badge bg-warning text-dark mt-1" 
                                                              title="Admin đã ép duyệt bình luận này">ÉP DUYỆT</small>
                                                @endif
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                                @if($review->admin_note && str_contains($review->admin_note, 'Tự động chờ duyệt'))
                                                    <br><small class="badge bg-info text-white mt-1" 
                                                              title="Hệ thống tự động đưa vào chờ duyệt vì: {{ strip_tags($review->admin_note) }}">TỰ ĐỘNG</small>
                                                @endif
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger text-white">Từ chối</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.reviews.show', $review->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="deleteReview({{ $review->id }})">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $reviews->appends(request()->query())->links() }}
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

@section('styles')
<style>
    .stars i {
        font-size: 14px; /* Tăng kích thước để icon dễ thấy hơn */
        margin-right: 2px; /* Điều chỉnh khoảng cách giữa các sao */
        color: #ffc107; /* Màu vàng tiêu chuẩn cho sao */
        transition: color 0.3s ease;
    }

    .stars i.text-muted {
        color: #6c757d; /* Màu xám cho sao chưa đánh giá */
    }

    [data-theme="dark"] .stars i {
        color: #ffc107; /* Đảm bảo màu sắc trong dark mode */
    }

    [data-theme="dark"] .stars i.text-muted {
        color: #b0b0b0; /* Màu xám trong dark mode */
    }

    .table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .table td:nth-child(5) {
        white-space: normal;
        word-wrap: break-word;
        max-width: 200px;
    }

    .badge {
        font-size: 11px;
        padding: 4px 8px;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .review-rating-cell {
        min-width: 120px;
        display: table-cell !important;
        visibility: visible !important;
        width: auto !important;
        text-align: center;
    }

    .review-rating-cell .badge {
        font-size: 0.9rem;
        padding: 0.3rem 0.5rem;
    }

    .review-rating-cell .stars-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2px;
        margin-top: 2px;
    }

    .review-rating-cell .stars-display i {
        font-size: 14px;
        color: #ffc107;
    }

    .review-rating-cell .stars-display i.text-muted {
        color: #6c757d;
    }

    .review-rating-cell .stars-display i.text-warning {
        color: #ffc107;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 12px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .btn-sm {
            font-size: 10px;
            padding: 2px 6px;
        }
        
        .review-rating-cell {
            min-width: 100px;
        }
        
        .review-rating-cell .stars-display i {
            font-size: 12px;
        }
    }

    /* Đảm bảo table hiển thị đầy đủ */
    .table {
        table-layout: auto;
        width: 100%;
    }

    .table th, .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    .table-responsive {
        min-height: 200px;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#selectAll').change(function() {
        $('.review-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Update select all when individual checkboxes change
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
    
    const ids = checkedBoxes.map(function() {
        return $(this).val();
    }).get();
    
    const statusText = {
        'approved': 'duyệt',
        'pending': 'chờ duyệt', 
        'rejected': 'từ chối'
    };
    
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
    
    const ids = checkedBoxes.map(function() {
        return $(this).val();
    }).get();
    
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