@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            @if(session('movie_created'))
                <small class="d-block mt-1 text-success-emphasis">
                    <i class="bi bi-info-circle me-1"></i>
                    Phim mới đã được thêm vào đầu danh sách bên dưới.
                </small>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-camera-reels-fill me-2" style="font-size: 1.2em;"></i>
                                🎬 Danh Sách Phim
                            </h4>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <!-- Form tìm kiếm -->
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.movies.index') }}">
                                    <div class="position-relative">
                                        <input type="search" name="query" class="form-control form-control-sm ps-4 pe-3" 
                                               style="min-width: 250px;" placeholder="Tìm kiếm tên phim, đạo diễn..." 
                                               autocomplete="off" value="{{ request('query') }}">
                                        <iconify-icon icon="solar:magnifer-linear"
                                            class="position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"
                                            style="font-size: 14px;"></iconify-icon>
                                    </div>
                                    
                                    <select name="status" class="form-select form-select-sm" style="width:140px;">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="showing" {{ request('status') == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
                                        <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                                    </select>
                                    
                                    <!-- Dropdown lọc nâng cao -->
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm bg-primary text-white dropdown-toggle" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-funnel me-1"></i>Bộ lọc
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 280px;">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="form-label text-muted small mb-1">Quốc gia</label>
                                                    <select name="country_id" class="form-select form-select-sm">
                                                        <option value="">Tất cả quốc gia</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label text-muted small mb-1">Thể loại</label>
                                                    <select name="genre_id" class="form-select form-select-sm">
                                                        <option value="">Tất cả thể loại</option>
                                                        @foreach ($genres as $genre)
                                                            <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                                                                {{ $genre->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label text-muted small mb-1">Độ tuổi</label>
                                                    <select name="age_limit_id" class="form-select form-select-sm">
                                                        <option value="">Tất cả độ tuổi</option>
                                                        @foreach ($ageLimits as $ageLimit)
                                                            <option value="{{ $ageLimit->id }}" {{ request('age_limit_id') == $ageLimit->id ? 'selected' : '' }}>
                                                                {{ $ageLimit->name ?? $ageLimit->label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                                            <i class="bi bi-check-lg me-1"></i>Áp dụng
                                                        </button>
                                                        <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bx bx-search"></i>
                                    </button>
                                </form>

                                <!-- Nút thêm phim -->
                                <a href="{{ route('admin.movies.create') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm phim
                                </a>
                                
                                <!-- Comment tạm thời -->
                                {{-- 
                                <a href="{{ route('admin.directors.create') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-person-plus me-1"></i> Thêm đạo diễn
                                </a>
                                <a href="{{ route('admin.actors.create') }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-people-fill me-1"></i> Thêm diễn viên
                                </a>
                                --}}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form xóa hàng loạt -->
                    <form id="delete-selected-form" action="{{ route('admin.movies.bulkDelete') }}" method="POST" style="display: none;" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-movie-ids">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các phim đã chọn?')">
                            <i class="bi bi-trash me-1"></i>Xóa đã chọn
                        </button>
                    </form>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="checkAllMovies">
                                        </div>
                                    </th>
                                    <th style="width: 70px;">Poster</th>
                                    <th style="width: 250px;">Tên phim</th>
                                    <th style="width: 120px;">Đạo diễn</th>
                                    <th style="width: 140px;">Thể loại</th>
                                    <th style="width: 80px;">Thời lượng</th>
                                    <th style="width: 120px;">Ngày chiếu</th>
                                    <th style="width: 100px;">Trạng thái</th>
                                    <th style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movies as $index => $movie)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                @php
                                                    $canBeDeleted = $movie->canBeEdited();
                                                    $currentStatus = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                    if ($movie->end_date && $movie->end_date < now()) {
                                                        $currentStatus = 'ended';
                                                    }
                                                    
                                                    $disableReason = '';
                                                    if (!$canBeDeleted) {
                                                        if ($currentStatus === 'ended') {
                                                            $disableReason = 'Phim đã kết thúc, không thể xóa';
                                                        } elseif ($currentStatus === 'showing') {
                                                            $disableReason = 'Phim đang chiếu, không thể xóa';
                                                        } elseif ($movie->hasBookedTickets()) {
                                                            $disableReason = 'Phim đã có vé được đặt, không thể xóa';
                                                        }
                                                    }
                                                @endphp
                                                @if(!$canBeDeleted)
                                                    <input type="checkbox" class="form-check-input" disabled title="{{ $disableReason }}">
                                                @else
                                                    <input type="checkbox" class="form-check-input movie-checkbox" value="{{ $movie->id }}">
                                                @endif
                                            </div>
                                        </td>
                                        <td style="width: 70px; padding: 8px; vertical-align: middle;">
                                            @php
                                                $posterUrl = null;
                                                if ($movie->image_path) {
                                                    $posterUrl = Storage::url($movie->image_path);
                                                } elseif ($movie->poster_url) {
                                                    $posterUrl = $movie->poster_url;
                                                } else {
                                                    $posterUrl = asset('client_assets/assets/images/movie-placeholder.png');
                                                }
                                            @endphp
                                            <div class="poster-container" style="position: relative; display: inline-block; width: 55px; height: 80px;">
                                                <img src="{{ $posterUrl }}" 
                                                     alt="{{ $movie->name }}" 
                                                     class="movie-poster-small"
                                                     style="width: 55px !important; height: 80px !important; max-width: 55px !important; max-height: 80px !important; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.3s ease; border: 1px solid #e5e7eb; cursor: pointer; display: block;"
                                                     onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.25)';"
                                                     onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.15)';"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                     loading="lazy">
                                                <div class="poster-fallback" 
                                                     style="display: none; width: 55px; height: 80px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 6px; border: 1px solid #e2e8f0; align-items: center; justify-content: center; color: #94a3b8; font-size: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                    <i class="bi bi-film"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $movie->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $movie->language ?? 'N/A' }} • {{ $movie->country?->name ?? 'N/A' }}
                                                    @if($movie->ageLimit)
                                                        • <span class="badge bg-warning-subtle text-warning">{{ $movie->ageLimit->name ?? $movie->ageLimit->label }}</span>
                                                    @endif
                                                </small>
                                                @if(!$movie->canBeEdited())
                                                    <div class="mt-1">
                                                        @if($movie->hasBookedTickets())
                                                            <span class="badge bg-warning-subtle text-warning" style="font-size: 0.7rem;">
                                                                <i class="bi bi-ticket-fill me-1"></i>Có vé đặt
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td> @forelse($movie->directors as $director)
                                                {{ $director->name }}{{ !$loop->last ? ', ' : '' }}
                                            @empty
                                                N/A
                                            @endforelse</td>
                                        <td>
                                            @if($movie->genres->isNotEmpty())
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($movie->genres->take(2) as $genre)
                                                        <span class="badge bg-info-subtle text-info" style="font-size: 0.75rem;">{{ $genre->name }}</span>
                                                    @endforeach
                                                    @if($movie->genres->count() > 2)
                                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.75rem;" title="{{ $movie->genres->pluck('name')->join(', ') }}">+{{ $movie->genres->count() - 2 }} khác</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $movie->duration_minutes }}</span>
                                            <small class="text-muted d-block">phút</small>
                                            @if($movie->duration_minutes >= 180)
                                                <small class="badge bg-info-subtle text-info mt-1" style="font-size: 0.6rem;">Phim dài</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $movie->release_date->format('d/m/Y') }}</div>
                                                @if($movie->end_date)
                                                    <small class="text-muted">đến {{ $movie->end_date->format('d/m/Y') }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                if ($movie->end_date && $movie->end_date < now()) {
                                                    $statusValue = 'ended';
                                                }
                                                
                                                $statusColors = [
                                                    'showing' => 'bg-success',
                                                    'upcoming' => 'bg-warning',
                                                    'ended' => 'bg-danger',
                                                ];
                                                $statusLabel = $statusValue == 'showing' ? 'Đang chiếu' : ($statusValue == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc');
                                                
                                                $autoEnded = ($movie->end_date && $movie->end_date < now() && $statusValue === 'ended' && (is_object($movie->status) ? $movie->status->value : $movie->status) !== 'ended');
                                            @endphp
                                            <span class="badge {{ $statusColors[$statusValue] ?? 'bg-secondary' }}">
                                                {{ $statusLabel }}
                                                @if($autoEnded)
                                                    <i class="bi bi-clock-history ms-1" title="Tự động cập nhật do đã quá ngày kết thúc"></i>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.movies.show', $movie->id) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                @php
                                                    $canBeEdited = $movie->canBeEdited();
                                                    $disableReason = '';
                                                    if (!$canBeEdited) {
                                                        if ($currentStatus === 'ended') {
                                                            $disableReason = 'Phim đã kết thúc, không thể chỉnh sửa';
                                                        } elseif ($currentStatus === 'showing') {
                                                            $disableReason = 'Phim đang chiếu, không thể chỉnh sửa';
                                                        } elseif ($movie->hasBookedTickets()) {
                                                            $disableReason = 'Phim đã có vé được đặt, không thể chỉnh sửa';
                                                        }
                                                    }
                                                @endphp
                                                @if(!$canBeEdited)
                                                    <button class="btn btn-soft-secondary btn-sm" disabled title="{{ $disableReason }}">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                @else
                                                    <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa phim">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($movies->isEmpty())
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            <i class="bi bi-film display-4 text-muted mb-3"></i>
                                            <div>Không tìm thấy phim nào.</div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $movies->appends(['query' => request('query'), 'status' => request('status'), 'country_id' => request('country_id'), 'age_limit_id' => request('age_limit_id'), 'genre_id' => request('genre_id'), 'release_date' => request('release_date'), 'end_date' => request('end_date')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* CSS cho ảnh poster - Force override */
.poster-container {
    position: relative;
    display: inline-block;
}

img.movie-poster-small {
    width: 55px !important;
    height: 80px !important;
    object-fit: cover !important;
    border-radius: 8px !important;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    border: 2px solid #fff !important;
    position: relative !important;
    overflow: hidden !important;
    display: block !important;
}

img.movie-poster-small:hover {
    transform: translateY(-3px) scale(1.05) !important;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3) !important;
    border-color: #4f46e5 !important;
    z-index: 10 !important;
}

.poster-fallback {
    width: 55px !important;
    height: 80px !important;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%) !important;
    border-radius: 8px !important;
    border: 2px solid #e5e7eb !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: #9ca3af !important;
    font-size: 1.5rem !important;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1) !important;
    transition: all 0.3s ease !important;
}

.poster-fallback:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15) !important;
    border-color: #d1d5db !important;
}

/* Responsive */
@media (max-width: 768px) {
    img.movie-poster-small,
    .poster-fallback {
        width: 45px !important;
        height: 65px !important;
    }
}

@media (max-width: 576px) {
    img.movie-poster-small,
    .poster-fallback {
        width: 40px !important;
        height: 58px !important;
    }
}
</style>
@endsection

@section('scripts')
<style>

/* Enhanced table styling */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 600;
    color: #495057;
    border-top: none;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #f1f3f4;
    padding: 0.875rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Card enhancements */
.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem;
}

/* Form controls */
.form-control-sm, .form-select-sm {
    border-radius: 8px;
    border: 1px solid #d1d9e0;
    transition: all 0.2s ease;
}

.form-control-sm:focus, .form-select-sm:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

/* Dropdown menu */
.dropdown-menu {
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 12px;
    padding: 1rem;
}

/* Button enhancements */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.825rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 500;
}

/* Status badges */
.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

/* Alert styling */
.alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    margin-bottom: 1.5rem;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

.alert-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .movie-poster-small,
    .poster-fallback {
        width: 45px;
        height: 65px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    
    .movie-poster-small:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }
    
    .poster-fallback {
        font-size: 1.2rem;
    }
    
    .table td, .table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .movie-poster-small,
    .poster-fallback {
        width: 40px;
        height: 58px;
        border-radius: 4px;
    }
    
    .movie-poster-small:hover {
        transform: translateY(-1px) scale(1.02);
    }
    
    .poster-fallback {
        font-size: 1rem;
    }
}

/* Loading state */
.table tbody tr.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Enhanced movie name styling */
.table td h6 {
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
    color: #1f2937;
    line-height: 1.3;
}

.table td small {
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.4;
}

/* Enhanced rating display */
.text-warning {
    color: #f59e0b !important;
}

/* Empty state styling */
.table tbody tr td i.bi-film {
    opacity: 0.3;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Kiểm tra và hiển thị thông báo cho phim tự động cập nhật trạng thái
    const autoEndedMovies = document.querySelectorAll('.badge.bg-danger i.bi-clock-history');
    if (autoEndedMovies.length > 0) {
        // Tạo thông báo nhỏ cho người dùng
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-info alert-dismissible fade show mt-2';
        alertDiv.innerHTML = `
            <i class="bi bi-info-circle-fill me-2"></i>
            Có ${autoEndedMovies.length} phim đã được tự động cập nhật trạng thái thành "Kết thúc" do đã quá ngày kết thúc.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Thêm thông báo vào đầu container
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Tự động ẩn thông báo sau 5 giây
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    function updateDeleteButton() {
        const checked = document.querySelectorAll('.movie-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }   

    document.getElementById('checkAllMovies')?.addEventListener('change', function() {
        document.querySelectorAll('.movie-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    document.querySelectorAll('.movie-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    document.getElementById('delete-selected-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.movie-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-movie-ids').value = checked.join(',');
    });
});
</script>
@endsection