@extends('layouts.admin.admin')

@section('title', 'Cài đặt lọc nội dung bình luận')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cài đặt lọc nội dung bình luận</h1>
        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Sensitive Words Management -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter"></i> Danh sách từ khóa nhạy cảm
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Các từ khóa này sẽ được kiểm tra trong bình luận. Nếu phát hiện, bình luận sẽ chuyển sang trạng thái "chờ duyệt".
                    </p>
                    
                    @if(count($sensitiveWords) > 0)
                        <div class="row">
                            @foreach($sensitiveWords as $index => $word)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <div class="d-flex align-items-center bg-light p-2 rounded">
                                        <span class="flex-grow-1">{{ $word }}</span>
                                        <form method="POST" action="{{ route('admin.comments.remove-sensitive-word') }}" 
                                              style="display: inline;" class="ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="word" value="{{ $word }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Bạn có chắc muốn xóa từ khóa này?')"
                                                    title="Xóa từ khóa">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có từ khóa nhạy cảm nào được thêm.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add New Sensitive Word -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus"></i> Thêm từ khóa mới
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.comments.add-sensitive-word') }}">
                        @csrf
                        <div class="form-group">
                            <label for="word">Từ khóa nhạy cảm:</label>
                            <input type="text" name="word" id="word" class="form-control @error('word') is-invalid @enderror" 
                                   placeholder="Nhập từ khóa..." value="{{ old('word') }}">
                            @error('word')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Thêm từ khóa
                        </button>
                    </form>
                    
                    <hr>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Lưu ý:</h6>
                        <ul class="mb-0">
                            <li>Từ khóa không phân biệt chữ hoa/thường</li>
                            <li>Hệ thống sẽ tự động kiểm tra từ khóa trong nội dung bình luận</li>
                            <li>Bình luận chứa từ khóa nhạy cảm sẽ được đưa vào trạng thái "chờ duyệt"</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Thống kê
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <h4 class="text-primary">{{ count($sensitiveWords) }}</h4>
                            <small class="text-muted">Từ khóa nhạy cảm</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Filter Rules -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs"></i> Quy tắc lọc nội dung
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success"><i class="fas fa-check-circle"></i> Tự động duyệt khi:</h6>
                            <ul>
                                <li>Bình luận không chứa từ khóa nhạy cảm</li>
                                <li>Nội dung có độ dài hợp lý (không quá ngắn/dài)</li>
                                <li>Người dùng có lịch sử bình luận tốt</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning"><i class="fas fa-clock"></i> Chờ duyệt khi:</h6>
                            <ul>
                                <li>Bình luận chứa từ khóa nhạy cảm</li>
                                <li>Nội dung có dấu hiệu spam</li>
                                <li>Người dùng mới hoặc có lịch sử vi phạm</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .alert-info ul {
        font-size: 0.9rem;
    }
    
    .btn-outline-danger {
        border: none;
        background: transparent;
        color: #dc3545;
    }
    
    .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
    }
    
    .bg-light {
        transition: background-color 0.2s;
    }
    
    .bg-light:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endsection
