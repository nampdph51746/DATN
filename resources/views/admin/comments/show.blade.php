@extends('layouts.admin.admin')

@section('title', 'Chi tiết bình luận')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết bình luận</h1>
        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Comment Details -->
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
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $comment->user->name }}</strong><br>
                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Phim:</div>
                        <div class="col-sm-9">
                            <a href="{{ route('movies.show', $comment->movie->id) }}" target="_blank" class="text-decoration-none">
                                <strong>{{ $comment->movie->name }}</strong>
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Trạng thái:</div>
                        <div class="col-sm-9">
                            @switch($comment->status)
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
                        <div class="col-sm-9">{{ $comment->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>

                    @if($comment->comment)
                        <div class="row mb-3">
                            <div class="col-sm-3 font-weight-bold">Nội dung:</div>
                            <div class="col-sm-9">
                                <div class="bg-light p-3 rounded">
                                    {{ $comment->comment }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($comment->admin_note)
                        <div class="row mb-3">
                            <div class="col-sm-3 font-weight-bold">Ghi chú admin:</div>
                            <div class="col-sm-9">
                                <div class="bg-info text-white p-3 rounded">
                                    {{ $comment->admin_note }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($comment->reviewed_by)
                        <div class="row mb-3">
                            <div class="col-sm-3 font-weight-bold">Được duyệt bởi:</div>
                            <div class="col-sm-9">
                                {{ $comment->reviewer->name ?? 'N/A' }}
                                @if($comment->reviewed_at)
                                    <small class="text-muted">({{ $comment->reviewed_at->format('d/m/Y H:i:s') }})</small>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                </div>
                <div class="card-body">
                    <!-- Status Update Form -->
                    <form method="POST" action="{{ route('admin.comments.update-status', $comment->id) }}" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <label for="status">Cập nhật trạng thái:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="approved" {{ $comment->status === 'approved' ? 'selected' : '' }}>Duyệt</option>
                                    <option value="pending" {{ $comment->status === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="rejected" {{ $comment->status === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">
                                    <i class="fas fa-save"></i> Cập nhật
                                </button>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="admin_note">Ghi chú admin (tùy chọn):</label>
                            <textarea name="admin_note" id="admin_note" class="form-control" rows="3" 
                                      placeholder="Nhập ghi chú nếu cần...">{{ old('admin_note', $comment->admin_note) }}</textarea>
                        </div>
                    </form>

                    <!-- Additional Actions -->
                    <div class="btn-group" role="group">
                        <form method="POST" action="{{ route('admin.comments.recheck', $comment->id) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm" 
                                    onclick="return confirm('Bạn có muốn kiểm tra lại nội dung bình luận này?')">
                                <i class="fas fa-search"></i> Kiểm tra lại
                            </button>
                        </form>

                        @if($comment->status !== 'approved')
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#forceApproveModal">
                                <i class="fas fa-exclamation-triangle"></i> Ép duyệt
                            </button>
                        @endif

                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movie Info Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin phim</h6>
                </div>
                <div class="card-body">
                    @if($comment->movie->image_path)
                        <img src="{{ Storage::url($comment->movie->image_path) }}" 
                             class="img-fluid rounded mb-3" alt="{{ $comment->movie->name }}">
                    @endif
                    
                    <h6>{{ $comment->movie->name }}</h6>
                    <p class="text-muted small">{{ Str::limit($comment->movie->description, 100) }}</p>
                    
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">Tổng bình luận:</small><br>
                            <strong>{{ $comment->movie->comments()->where('status', 'approved')->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Force Approve Modal -->
<div class="modal fade" id="forceApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ép duyệt bình luận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.comments.force-approve', $comment->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Cảnh báo:</strong> Bạn đang ép duyệt một bình luận có thể có vấn đề. 
                        Vui lòng nhập lý do chi tiết.
                    </div>
                    <div class="form-group">
                        <label for="force_reason">Lý do ép duyệt: <span class="text-danger">*</span></label>
                        <textarea name="force_reason" id="force_reason" class="form-control" rows="4" 
                                  placeholder="Nhập lý do chi tiết tại sao bạn muốn ép duyệt bình luận này..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-exclamation-triangle"></i> Ép duyệt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn xóa bình luận này không? Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }

    .stars i {
        font-size: 18px;
        margin-right: 3px;
    }

    .badge {
        font-size: 12px;
    }

    .btn-group .btn {
        margin-right: 5px;
    }
</style>
@endsection
