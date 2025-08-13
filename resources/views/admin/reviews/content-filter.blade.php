@extends('layouts.admin.admin')

@section('title', 'Cài đặt lọc nội dung')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 fw-bold text-gradient-primary">
            <i class="fas fa-shield-alt me-2"></i> Cài đặt lọc nội dung
        </h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary rounded-3 d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Sensitive Words Management -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3 rounded-top-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-filter me-2"></i> Danh sách từ khóa nhạy cảm
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Các từ khóa này sẽ được kiểm tra trong bình luận. Nếu phát hiện, bình luận sẽ chuyển sang trạng thái <span class="badge bg-warning text-dark">chờ duyệt</span>.
                    </p>
                    <div class="row g-2">
                        @forelse($sensitiveWords as $index => $word)
                            <div class="col-md-4 col-sm-6">
                                <div class="d-flex align-items-center bg-light p-2 rounded-3 shadow-sm">
                                    <span class="flex-grow-1 fw-medium text-dark">{{ $word }}</span>
                                    <form method="POST" action="{{ route('admin.reviews.remove-sensitive-word') }}" 
                                          class="d-inline ms-2" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa từ khóa này?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="word" value="{{ $word }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Chưa có từ khóa nhạy cảm nào được cấu hình.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Sensitive Word & Statistics -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3 rounded-top-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-plus me-2"></i> Thêm từ khóa nhạy cảm
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reviews.add-sensitive-word') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="word" class="form-label fw-semibold">Từ khóa</label>
                            <input type="text" 
                                   name="word" 
                                   id="word" 
                                   class="form-control form-control-lg rounded-3 @error('word') is-invalid @enderror" 
                                   placeholder="Nhập từ khóa nhạy cảm..."
                                   required>
                            @error('word')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 fw-medium">
                            <i class="fas fa-plus"></i> Thêm từ khóa
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filter Statistics -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-gradient-info text-white py-3 rounded-top-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2"></i> Thống kê lọc nội dung
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $totalReviews = \App\Models\Review::count();
                        $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
                        $autoFlaggedReviews = \Illuminate\Support\Facades\Schema::hasColumn('reviews', 'admin_note')
                            ? \App\Models\Review::where('admin_note', 'like', 'Tự động chờ duyệt:%')->count()
                            : 0;
                        $rejectedReviews = \App\Models\Review::where('status', 'rejected')->count();
                    @endphp
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="fw-medium">Tổng đánh giá:</span>
                        <span class="fw-bold text-primary">{{ number_format($totalReviews) }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="fw-medium">Chờ duyệt:</span>
                        <span class="badge bg-warning text-dark">{{ number_format($pendingReviews) }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="fw-medium">Tự động chặn:</span>
                        <span class="badge bg-info text-dark">{{ number_format($autoFlaggedReviews) }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="fw-medium">Từ chối:</span>
                        <span class="badge bg-danger">{{ number_format($rejectedReviews) }}</span>
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
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-gradient-dark text-white py-3 rounded-top-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-cogs me-2"></i> Quy tắc lọc nội dung
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled ms-2">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Chứa từ khóa nhạy cảm</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Chứa email hoặc số điện thoại</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Chứa đường link</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Quá ngắn (< 10 ký tự)</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Quá dài (> 500 ký tự)</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Tài khoản mới (< 7 ngày)</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Ít review đã duyệt (< 3)</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Có dấu hiệu spam</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #007bff, #0056b3);
    }
    .bg-gradient-info {
        background: linear-gradient(90deg, #17a2b8, #138496);
    }
    .bg-gradient-dark {
        background: linear-gradient(90deg, #343a40, #23272b);
    }
    .rounded-4 { border-radius: 1rem !important; }
    .card-header { border-bottom: none; }
    .btn-close { float: right; }
    .form-control-lg { font-size: 1.1rem; }
    .shadow-lg { box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important; }
    .fw-medium { font-weight: 500; }
    .fw-bold { font-weight: 700; }
    .text-gradient-primary {
        background: linear-gradient(90deg, #007bff, #0056b3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-fill-color: transparent;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush