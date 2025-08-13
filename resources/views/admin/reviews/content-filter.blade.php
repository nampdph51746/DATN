@extends('layouts.admin.admin')

@section('title', 'Cài đặt lọc nội dung')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cài đặt lọc nội dung</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-sm">
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
                    
                    <div class="row">
                        @foreach($sensitiveWords as $index => $word)
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="d-flex align-items-center bg-light p-2 rounded">
                                    <span class="flex-grow-1">{{ $word }}</span>
                                    <form method="POST" action="{{ route('admin.reviews.remove-sensitive-word') }}" 
                                          class="d-inline ml-2" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa từ khóa này?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="word" value="{{ $word }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(count($sensitiveWords) == 0)
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có từ khóa nhạy cảm nào được cấu hình.</p>
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
                        <i class="fas fa-plus"></i> Thêm từ khóa nhạy cảm
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reviews.add-sensitive-word') }}">
                        @csrf
                        <div class="form-group">
                            <label for="word">Từ khóa</label>
                            <input type="text" 
                                   name="word" 
                                   id="word" 
                                   class="form-control @error('word') is-invalid @enderror" 
                                   placeholder="Nhập từ khóa nhạy cảm..."
                                   required>
                            @error('word')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Thêm từ khóa
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filter Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Thống kê lọc nội dung
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $totalReviews = \App\Models\Review::count();
                        $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
                        // Sửa lỗi: kiểm tra cột admin_note có tồn tại trước khi truy vấn
                        $autoFlaggedReviews = \Illuminate\Support\Facades\Schema::hasColumn('reviews', 'admin_note')
                            ? \App\Models\Review::where('admin_note', 'like', 'Tự động chờ duyệt:%')->count()
                            : 0;
                        $rejectedReviews = \App\Models\Review::where('status', 'rejected')->count();
                    @endphp
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Tổng đánh giá:</span>
                            <strong>{{ number_format($totalReviews) }}</strong>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Chờ duyệt:</span>
                            <span class="badge badge-warning">{{ number_format($pendingReviews) }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Tự động chặn:</span>
                            <span class="badge badge-info">{{ number_format($autoFlaggedReviews) }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Từ chối:</span>
                            <span class="badge badge-danger">{{ number_format($rejectedReviews) }}</span>
                        </div>
                    </div>
                    
                    @if($totalReviews > 0)
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted">
                                Tỷ lệ chặn tự động: 
                                <strong>{{ round(($autoFlaggedReviews / $totalReviews) * 100, 1) }}%</strong>
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Content Filter Rules -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs"></i> Quy tắc lọc nội dung
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-left">
                        <h6 class="font-weight-bold">Tự động chờ duyệt khi:</h6>
                        <ul class="list-unstyled ml-3">
                            <li><i class="fas fa-check text-success"></i> Chứa từ khóa nhạy cảm</li>
                            <li><i class="fas fa-check text-success"></i> Chứa email hoặc số điện thoại</li>
                            <li><i class="fas fa-check text-success"></i> Chứa đường link</li>
                            <li><i class="fas fa-check text-success"></i> Quá ngắn (< 10 ký tự)</li>
                            <li><i class="fas fa-check text-success"></i> Quá dài (> 500 ký tự)</li>
                            <li><i class="fas fa-check text-success"></i> Tài khoản mới (< 7 ngày)</li>
                            <li><i class="fas fa-check text-success"></i> Ít review đã duyệt (< 3)</li>
                            <li><i class="fas fa-check text-success"></i> Có dấu hiệu spam</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endsection