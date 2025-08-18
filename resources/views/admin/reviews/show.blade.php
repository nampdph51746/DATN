@extends('layouts.admin.admin')

@section('title', 'Chi tiết bình luận')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết bình luận</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Review Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin bình luận</h6>
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

                    @if($review->status !== 'approved')
                        <button type="button" class="btn btn-warning btn-block mb-2" onclick="showForceApproveModal()">
                            <i class="fas fa-exclamation-triangle"></i> Ép duyệt (Force Approve)
                        </button>
                    @endif

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
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Tổng bình luận:</small><br>
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

<!-- Force Approve Modal -->
<div class="modal fade" id="forceApproveModal" tabindex="-1" role="dialog" aria-labelledby="forceApproveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="forceApproveModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Ép duyệt bình luận
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.reviews.force-approve', $review->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Cảnh báo:</strong> Bạn đang chuẩn bị ép duyệt một bình luận có thể chứa nội dung không phù hợp. 
                        Vui lòng cung cấp lý do rõ ràng cho hành động này.
                    </div>
                    
                    <div class="form-group">
                        <label for="force_reason">Lý do ép duyệt <span class="text-danger">*</span></label>
                        <textarea name="force_reason" id="force_reason" class="form-control" rows="4" 
                                  placeholder="Ví dụ: Sau khi xem xét kỹ, nội dung này không vi phạm quy định nghiêm trọng..."
                                  required></textarea>
                        <small class="form-text text-muted">Tối thiểu 10 ký tự</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-check"></i> Xác nhận ép duyệt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    if (confirm('Bạn có chắc muốn xóa bình luận này? Hành động này không thể hoàn tác!')) {
        $('#deleteForm').submit();
    }
}

function showForceApproveModal() {
    $('#forceApproveModal').modal('show');
}
</script>
@endsection