@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-info rounded-4 shadow-lg overflow-hidden position-relative">
                <div class="card-body p-5 position-relative" style="z-index:2;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur d-flex align-items-center justify-content-center" style="min-width:80px; min-height:80px;">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="fas fa-user-tie fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2 d-flex align-items-center gap-2">
                                    <span class="avatar-md rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                    </span>
                                    Chi tiết đạo diễn
                                </h2>
                                <p class="mb-0 opacity-90 fs-5">Thông tin chi tiết về {{ $director->name }}</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            @if($director->is_active)
                                <span class="badge bg-success bg-opacity-90 rounded-pill px-4 py-3 fs-5 fw-semibold shadow">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Đang hoạt động
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-90 rounded-pill px-4 py-3 fs-5 fw-semibold shadow">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Ngưng hoạt động
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 h-100">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <span class="avatar-sm rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                            <span class="avatar-title rounded-circle bg-primary text-white">
                                <i class="fas fa-id-card"></i>
                            </span>
                        </span>
                        Thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img src="{{ $director->image_path ? Storage::url($director->image_path) : asset('assets/images/director-placeholder.png') }}" 
                             alt="{{ $director->name }}" 
                             class="img-fluid rounded-circle shadow-lg" 
                             style="width: 200px; height: 200px; object-fit: cover; border: 6px solid #fff;">
                        
                        <!-- Status indicator -->
                        @if($director->is_active)
                            <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-3 shadow border border-white border-3">
                                <i class="fas fa-check-circle text-white fs-5"></i>
                            </div>
                        @else
                            <div class="position-absolute bottom-0 end-0 bg-danger rounded-circle p-3 shadow border border-white border-3">
                                <i class="fas fa-times-circle text-white fs-5"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="fw-bold mb-2 text-dark">{{ $director->name }}</h4>
                    
                    @if($director->is_active)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 rounded-pill px-3 py-2 mb-3 fw-semibold">
                            <i class="fas fa-check-circle me-1"></i>Đang hoạt động
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 rounded-pill px-3 py-2 mb-3 fw-semibold">
                            <i class="fas fa-times-circle me-1"></i>Ngưng hoạt động
                        </span>
                    @endif
                    
                    <!-- Director Info -->
                    <div class="row text-start g-3 mt-3">
                        @if($director->nationality)
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3 p-3 bg-light-subtle rounded-3">
                                    <div class="avatar-sm rounded-circle bg-primary bg-opacity-10">
                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                            <i class="fas fa-globe-americas"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted small fw-semibold">Quốc tịch</p>
                                        <p class="mb-0 fw-bold">{{ $director->nationality }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($director->birth_date)
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3 p-3 bg-light-subtle rounded-3">
                                    <div class="avatar-sm rounded-circle bg-success bg-opacity-10">
                                        <span class="avatar-title rounded-circle bg-success text-white">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted small fw-semibold">Ngày sinh</p>
                                        <p class="mb-0 fw-bold">{{ $director->birth_date->format('d/m/Y') }}</p>
                                        <p class="mb-0 text-muted small">{{ $director->birth_date->age }} tuổi</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light-subtle rounded-3">
                                <div class="avatar-sm rounded-circle bg-info bg-opacity-10">
                                    <span class="avatar-title rounded-circle bg-info text-white">
                                        <i class="fas fa-film"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted small fw-semibold">Số phim đã làm</p>
                                    <p class="mb-0 fw-bold">{{ $director->movies->count() }} phim</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light-subtle rounded-3">
                                <div class="avatar-sm rounded-circle bg-warning bg-opacity-10">
                                    <span class="avatar-title rounded-circle bg-warning text-white">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted small fw-semibold">Tham gia từ</p>
                                    <p class="mb-0 fw-bold">{{ $director->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.directors.edit', $director->id) }}" 
                           class="btn btn-warning btn-lg rounded-pill">
                            <i class="fas fa-pencil-alt me-2"></i>Chỉnh sửa thông tin
                        </a>
                        <a href="{{ route('admin.directors.index') }}" 
                           class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details & Movies -->
        <div class="col-xl-8 col-lg-7">
            <!-- Biography Section -->
            @if($director->biography)
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-white border-0 py-4">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar-md rounded-circle bg-info-subtle d-flex align-items-center justify-content-center">
                                <span class="avatar-title rounded-circle bg-info text-white">
                                    <i class="fas fa-book"></i>
                                </span>
                            </span>
                            <div>
                                <h4 class="mb-0 fw-bold text-dark">Tiểu sử</h4>
                                <p class="text-muted mb-0">Thông tin về sự nghiệp và cuộc đời</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="bg-light-subtle rounded-3 p-4">
                            <p class="mb-0 lh-lg">{{ $director->biography }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Movies Section -->
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar-md rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                                <span class="avatar-title rounded-circle bg-primary text-white">
                                    <i class="fas fa-film"></i>
                                </span>
                            </span>
                            <div>
                                <h4 class="mb-0 fw-bold text-dark">Danh sách phim</h4>
                                <p class="text-muted mb-0">{{ $director->movies->count() }} phim đã thực hiện</p>
                            </div>
                        </div>
                        @if($director->movies->count() > 0)
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 rounded-pill px-3 py-2 fw-semibold">
                                <i class="fas fa-film me-1"></i>
                                {{ $director->movies->count() }} phim
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($director->movies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-4 fw-bold text-uppercase small border-0">Phim</th>
                                        <th class="py-4 fw-bold text-uppercase small border-0">Thông tin</th>
                                        <th class="py-4 fw-bold text-uppercase small border-0">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($director->movies as $movie)
                                        <tr class="border-bottom">
                                            <td class="ps-4 py-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('assets/images/movie-placeholder.png')) }}" 
                                                         alt="{{ $movie->name }}" 
                                                         class="rounded-3 shadow-sm"
                                                         style="width: 50px; height: 70px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="fw-bold mb-1 text-dark">{{ $movie->name }}</h6>
                                                        <p class="text-muted mb-0 small">{{ $movie->duration_minutes }} phút</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <i class="fas fa-calendar-alt text-primary"></i>
                                                        <span class="small">{{ $movie->release_date->format('d/m/Y') }}</span>
                                                    </div>
                                                    @if($movie->average_rating)
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <span class="small">{{ $movie->average_rating }}/10</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                @php
                                                    $statusClass = match($movie->status->value) {
                                                        'upcoming' => 'bg-info',
                                                        'showing' => 'bg-success', 
                                                        'ended' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                    $statusText = match($movie->status->value) {
                                                        'upcoming' => 'Sắp chiếu',
                                                        'showing' => 'Đang chiếu',
                                                        'ended' => 'Đã kết thúc',
                                                        default => 'Không xác định'
                                                    };
                                                    $statusIcon = match($movie->status->value) {
                                                        'upcoming' => 'fas fa-clock',
                                                        'showing' => 'fas fa-play-circle',
                                                        'ended' => 'fas fa-stop-circle',
                                                        default => 'fas fa-question-circle'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusClass) }} border border-{{ str_replace('bg-', '', $statusClass) }} border-opacity-20 rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2">
                                                    <i class="{{ $statusIcon }}"></i>
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="d-flex flex-column align-items-center gap-3">
                                <div class="avatar-xl rounded-circle bg-light">
                                    <span class="avatar-title rounded-circle text-muted">
                                        <i class="fas fa-film fs-1"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-muted">Chưa có phim nào</h5>
                                    <p class="text-muted mb-0">Đạo diễn này chưa thực hiện phim nào</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* GIỮ NGUYÊN CÁC STYLES KHÁC: */
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

                                                    {{-- Đã loại bỏ average_rating --}}
                                                    {{-- Đã loại bỏ average_rating --}}
                                                    {{-- Đã loại bỏ average_rating --}}
    transition: all 0.3s ease;
    border: none !important;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

/* Tooltip styling - GIỮ NGUYÊN: */
.tooltip {
    font-size: 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Khởi tạo tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">