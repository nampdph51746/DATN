@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-info rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="bi bi-eye fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chi tiết diễn viên</h2>
                                <p class="mb-0 opacity-90 fs-5">Thông tin chi tiết của diễn viên: {{ $actor->name }}</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-info rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="bi bi-info-circle me-2"></i>
                                Chi tiết
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-person-circle" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Actor Image & Basic Info -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-info"></i>
                        Thông tin diễn viên
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img src="{{ $actor->image_path ? Storage::url($actor->image_path) : asset('assets/images/actor-placeholder.png') }}" 
                             alt="{{ $actor->name }}" 
                             class="img-fluid rounded-4 shadow-lg" 
                             style="width: 100%; max-width: 280px; height: 320px; object-fit: cover; border: 4px solid #fff;">
                        
                        <!-- Status Badge -->
                        <div class="position-absolute top-3 end-3">
                            @if($actor->is_active)
                                <span class="badge bg-success rounded-pill px-3 py-2 shadow">
                                    <i class="bi bi-check-circle me-1"></i>Hoạt động
                                </span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow">
                                    <i class="bi bi-x-circle me-1"></i>Ngưng hoạt động
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <h4 class="fw-bold text-dark mb-3">{{ $actor->name }}</h4>
                    
                    <div class="row g-3 text-start">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-primary me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="bi bi-globe"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Quốc tịch</small>
                                    <div class="fw-semibold">{{ $actor->nationality ?: 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-success me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="bi bi-calendar-event"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày sinh</small>
                                    <div class="fw-semibold">
                                        @if($actor->birth_date)
                                            {{ $actor->birth_date->format('d/m/Y') }}
                                            <small class="text-muted">({{ $actor->birth_date->age }} tuổi)</small>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-info me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="bi bi-film"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Số phim tham gia</small>
                                    <div class="fw-semibold">{{ $actor->movies->count() }} phim</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-warning me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="bi bi-calendar-plus"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày tạo</small>
                                    <div class="fw-semibold">{{ $actor->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.actors.edit', $actor->id) }}" class="btn btn-warning btn-lg rounded-pill">
                            <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa
                        </a>
                        @if($actor->movies->count() == 0)
                            <form action="{{ route('admin.actors.destroy', $actor->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa diễn viên này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-lg rounded-pill w-100">
                                    <i class="bi bi-trash3 me-2"></i>Xóa diễn viên
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.actors.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="bi bi-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-xl-8 col-lg-7">
            <!-- Biography Section -->
            @if($actor->biography)
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-primary-subtle border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-journal-text text-primary"></i>
                        Tiểu sử
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="biography-content">
                        <p class="mb-0 lh-lg">{{ $actor->biography }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Movies Section -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-success-subtle border-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-film text-success"></i>
                            Danh sách phim tham gia
                        </h5>
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            {{ $actor->movies->count() }} phim
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($actor->movies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-bold">Poster</th>
                                        <th class="border-0 fw-bold">Tên phim</th>
                                        <th class="border-0 fw-bold">Thể loại</th>
                                        <th class="border-0 fw-bold">Năm phát hành</th>
                                        <th class="border-0 fw-bold">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actor->movies as $movie)
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <img src="{{ $movie->poster_path ? Storage::url($movie->poster_path) : asset('assets/images/movie-placeholder.png') }}" 
                                                 alt="{{ $movie->title }}" 
                                                 class="movie-poster-small rounded-3 shadow-sm">
                                        </td>
                                        <td>
                                            <h6 class="mb-1 fw-bold">{{ $movie->title }}</h6>
                                            <small class="text-muted">{{ Str::limit($movie->description, 50) }}</small>
                                        </td>
                                        <td>
                                            @if($movie->genres->count() > 0)
                                                @foreach($movie->genres->take(2) as $genre)
                                                    <span class="badge bg-primary-subtle text-primary rounded-pill me-1">
                                                        {{ $genre->name }}
                                                    </span>
                                                @endforeach
                                                @if($movie->genres->count() > 2)
                                                    <span class="badge bg-secondary rounded-pill">+{{ $movie->genres->count() - 2 }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $movie->release_date ? $movie->release_date->format('Y') : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if($movie->is_active)
                                                <span class="badge bg-success rounded-pill">
                                                    <i class="bi bi-check-circle me-1"></i>Hoạt động
                                                </span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">
                                                    <i class="bi bi-x-circle me-1"></i>Ngưng hoạt động
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-film text-muted fs-1 mb-3"></i>
                            <h6 class="text-muted">Chưa tham gia phim nào</h6>
                            <p class="text-muted small mb-0">Diễn viên này chưa tham gia bộ phim nào trong hệ thống</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Activity Timeline -->
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-warning-subtle border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-warning"></i>
                        Lịch sử hoạt động
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Tạo diễn viên</h6>
                                <p class="mb-0 text-muted">{{ $actor->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($actor->updated_at != $actor->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Cập nhật lần cuối</h6>
                                <p class="mb-0 text-muted">{{ $actor->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @foreach($actor->movies->sortByDesc('created_at')->take(3) as $movie)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Tham gia phim</h6>
                                <p class="mb-0">{{ $movie->title }}</p>
                                <p class="mb-0 text-muted small">{{ $movie->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
/* Gradient Background */
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

/* Movie Poster */
.movie-poster-small {
    width: 45px;
    height: 65px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.movie-poster-small:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Avatar Sizes */
.avatar-xl {
    width: 5rem;
    height: 5rem;
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #e9ecef, #dee2e6);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -2.25rem;
    top: 0.25rem;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border-left: 4px solid #dee2e6;
}

/* Button Enhancements */
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
}

/* Card Enhancements */
.card {
    transition: all 0.3s ease;
    border: none;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Sticky Position */
.sticky-top {
    top: 1rem;
}

/* Color Subtle Backgrounds */
.bg-primary-subtle {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

/* Biography Content */
.biography-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #495057;
}

/* Table Enhancements */
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

/* Badge */
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
    
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        left: 0.5rem;
    }
    
    .timeline-marker {
        left: -1.75rem;
        width: 1rem;
        height: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
</script>
@endsection