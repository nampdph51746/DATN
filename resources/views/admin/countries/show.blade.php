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
                                    <i class="bi bi-globe-asia fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chi tiết quốc gia</h2>
                                <p class="mb-0 opacity-90 fs-5">Thông tin chi tiết của quốc gia: {{ $country->name }}</p>
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
                    <i class="bi bi-globe-asia" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Country Info Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-globe text-info"></i>
                        Thông tin quốc gia
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h4 class="fw-bold text-dark mb-2">{{ $country->name }}</h4>
                        <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2 fs-6">
                            Mã code: {{ $country->code }}
                        </span>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-primary me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="bi bi-calendar-event"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Ngày tạo</small>
                                    <div class="fw-semibold">{{ $country->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-success me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="bi bi-calendar-check"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Cập nhật gần nhất</small>
                                    <div class="fw-semibold">
                                        {{ $country->updated_at->format('d/m/Y H:i') }}
                                        <small class="text-muted">({{ $country->updated_at->diffForHumans() }})</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="avatar-sm rounded-circle bg-warning me-3">
                                    <span class="avatar-title rounded-circle text-white">
                                        <i class="bi bi-hash"></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">ID hệ thống</small>
                                    <div class="fw-semibold">#{{ $country->id }}</div>
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
                                    <small class="text-muted">Số phim thuộc quốc gia này</small>
                                    <div class="fw-semibold">{{ $country->movies()->count() }} phim</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.countries.edit', $country->id) }}" class="btn btn-warning btn-lg rounded-pill">
                            <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa
                        </a>
                        @if($country->movies()->count() == 0)
                            <form action="{{ route('admin.countries.destroy', $country->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa quốc gia này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-lg rounded-pill w-100">
                                    <i class="bi bi-trash3 me-2"></i>Xóa quốc gia
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="bi bi-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movies List Section -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-success-subtle border-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-film text-success"></i>
                            Danh sách phim thuộc quốc gia này
                        </h5>
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            {{ $country->movies()->count() }} phim
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($country->movies()->count() > 0)
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
                                    @foreach($country->movies as $movie)
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
                            <h6 class="text-muted">Chưa có phim nào thuộc quốc gia này</h6>
                            <p class="text-muted small mb-0">Quốc gia này chưa có bộ phim nào trong hệ thống</p>
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
                                <h6 class="mb-1 fw-bold">Tạo quốc gia</h6>
                                <p class="mb-0 text-muted">{{ $country->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @if($country->updated_at != $country->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Cập nhật lần cuối</h6>
                                <p class="mb-0 text-muted">{{ $country->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @foreach($country->movies->sortByDesc('created_at')->take(3) as $movie)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">Thêm phim</h6>
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
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
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
.card {
    transition: all 0.3s ease;
    border: none;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.sticky-top {
    top: 1rem;
}
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
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}
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