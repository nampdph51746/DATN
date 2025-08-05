@extends('layouts.admin.admin')

@section('title', 'Chi tiết đánh giá')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đánh giá</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Review Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đánh giá</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Người dùng:</div>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $review->user->name }}</strong><br>
                                    <small class="text-muted">{{ $review->user->email }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Phim:</div>
                        <div class="col-sm-9">
                            <a href="{{ route('movies.show', $review->movie->id) }}" target="_blank" class="text-decoration-none">
                                <strong>{{ $review->movie->name }}</strong>
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Đánh giá:</div>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-2 p-2">{{ $review->rating_star }}/5</span>
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating_star)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Trạng thái:</div>
                        <div class="col-sm-9">
                            @switch($review->status)
                                @case('approved')
                                    <span class="badge bg-success text-white p-2">Đã duyệt</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning text-dark p-2">Chờ duyệt</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger text-white p-2">Từ chối</span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Ngày tạo:</div>
                        <div class="col-sm-9">{{ $review->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>

                    @if($review->comment)
                        <div class="row mb-3">
                            <div class="col-sm-3 font-weight-bold">Bình luận:</div>
                            <div class="col-sm-9">
                                <div class="bg-light p-3 rounded">
                                    {{ $review->comment }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($review->admin_note)
                        <div class="row mb-3">
                            <div class="col-sm-3 font-weight-bold">Ghi chú admin:</div>
                            <div class="col-sm-9">
                                <div class="bg-warning p-3 rounded">
                                    {{ $review->admin_note }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($review->reviewed_by)
                        <div class="row mb-3">
                            <div class="col-sm-3 font-weight-bold">Người duyệt:</div>
                            <div class="col-sm-9">
                                {{ $review->reviewer->name ?? 'N/A' }}
                                @if($review->reviewed_at)
                                    <br><small class="text-muted">
                                        {{ \Carbon\Carbon::parse($review->reviewed_at)->format('d/m/Y H:i:s') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reviews.update-status', $review->id) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select name="status" id="status" class="form-control">
                                <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="admin_note">Ghi chú admin</label>
                            <textarea name="admin_note" id="admin_note" class="form-control" rows="3" 
                                      placeholder="Ghi chú về lý do duyệt/từ chối...">{{ $review->admin_note }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i>Cập nhật
                        </button>
                    </form>

                    <hr>

                    @if($review->comment)
                        <form method="POST" action="{{ route('admin.reviews.recheck', $review->id) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fas fa-search"></i> Kiểm tra lại nội dung
                            </button>
                        </form>
                    @endif

                    <button type="button" class="btn btn-danger btn-block" onclick="deleteReview()">
                        <i class="fas fa-trash"></i> Xóa đánh giá
                    </button>
                </div>
            </div>

            <!-- Movie Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin phim</h6>
                </div>
                <div class="card-body">
                    @if($review->movie->image_path)
                        <img src="{{ Storage::url($review->movie->image_path) }}" 
                             class="img-fluid rounded mb-3" alt="{{ $review->movie->name }}">
                    @endif
                    
                    <h6>{{ $review->movie->name }}</h6>
                    <p class="text-muted small">{{ Str::limit($review->movie->description, 100) }}</p>
                    
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Đánh giá TB:</small><br>
                            <strong>{{ $review->movie->average_rating ?? 0 }}/5</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Tổng đánh giá:</small><br>
                            <strong>{{ $review->movie->reviews()->where('status', 'approved')->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('styles')
<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    margin-right: 10px;
}

.stars i {
    font-size: 16px;
    margin-right: 2px;
}
</style>
@endsection

@section('scripts')
<script>
function deleteReview() {
    if (confirm('Bạn có chắc muốn xóa đánh giá này? Hành động này không thể hoàn tác!')) {
        $('#deleteForm').submit();
    }
}
</script>
@endsection
