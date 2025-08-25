{{-- filepath: c:\laragon\www\DATN\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.admin.admin')

@push('styles')
<style>
.accordion-button:not(.collapsed) {
    color: #0c63e4;
    background-color: #e7f1ff;
    box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
}

.accordion-button {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    color: #212529;
    text-align: left;
    background-color: #fff;
    border: 0;
    border-radius: 0;
    overflow-anchor: none;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,border-radius .15s ease;
    cursor: pointer;
}

.accordion-button:hover {
    z-index: 2;
    background-color: #f8f9fa;
}

.accordion-button:focus {
    z-index: 3;
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
}

.accordion-button::after {
    flex-shrink: 0;
    width: 1.25rem;
    height: 1.25rem;
    margin-left: auto;
    content: "";
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-size: 1.25rem;
    transition: transform .2s ease-in-out;
}

.accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230c63e4'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    transform: rotate(-180deg);
}

.accordion-collapse {
    border: 0;
    overflow: hidden;
    transition: all 0.3s ease-in-out;
}

.accordion-collapse:not(.show) {
    display: none !important;
    max-height: 0px !important;
    opacity: 0 !important;
}

.accordion-collapse.show {
    display: block !important;
    height: auto !important;
    overflow: visible !important;
    opacity: 1 !important;
    visibility: visible !important;
    max-height: none !important;
    transform: none !important;
}

.accordion-item {
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.125);
    margin-bottom: 0;
}

.accordion-item:first-of-type {
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.accordion-item:first-of-type .accordion-button {
    border-top-left-radius: calc(0.375rem - 1px);
    border-top-right-radius: calc(0.375rem - 1px);
}

.accordion-item:last-of-type {
    border-bottom-right-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.accordion-item:not(:first-of-type) {
    border-top: 0;
}

.accordion-body {
    padding: 1rem 1.25rem;
    background-color: #fff;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Force visibility for all content */
.accordion-collapse.show .card,
.accordion-collapse.show .table,
.accordion-collapse.show .row,
.accordion-collapse.show .col-xl-3,
.accordion-collapse.show .col-xl-4,
.accordion-collapse.show .col-lg-6,
.accordion-collapse.show .col-12 {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.accordion-collapse.show .d-flex {
    display: flex !important;
}

/* Chart containers */
.accordion-collapse.show [id*="chart"] {
    min-height: 350px !important;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}
</style>
<!-- Bootstrap CSS CDN để đảm bảo hoạt động -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    {{-- Header với bộ lọc --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1">📊 Dashboard Quản Trị</h4>
                            <p class="text-muted mb-0">
                                @if (request('type', 'day') == 'day')
                                    Thống kê hôm nay ({{ request('date', now()->format('d/m/Y')) }})
                                @elseif(request('type') == 'month')
                                    Thống kê tháng {{ request('month', now()->format('m')) }}/{{ request('year', now()->format('Y')) }}
                                @else
                                    Thống kê năm {{ request('year', now()->format('Y')) }}
                                @endif
                            </p>
                        </div>
                        
                        {{-- Bộ lọc ngày/tháng/năm --}}
                        <form method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="btn-group" role="group">
                                <button type="submit" name="type" value="day"
                                    class="btn btn-outline-primary {{ request('type', 'day') == 'day' ? 'active' : '' }}">Ngày</button>
                                <button type="submit" name="type" value="month"
                                    class="btn btn-outline-primary {{ request('type') == 'month' ? 'active' : '' }}">Tháng</button>
                                <button type="submit" name="type" value="year"
                                    class="btn btn-outline-primary {{ request('type') == 'year' ? 'active' : '' }}">Năm</button>
                            </div>
                            
                            @if (request('type', 'day') == 'day')
                                <input type="date" name="date" class="form-control w-auto"
                                    value="{{ request('date', now()->toDateString()) }}">
                            @endif
                            
                            @if (request('type') == 'month')
                                <select name="month" class="form-select w-auto">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                            Tháng {{ $m }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="year" class="form-select w-auto">
                                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                            Năm {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            @endif
                            
                            @if (request('type') == 'year')
                                <select name="year" class="form-select w-auto">
                                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                            Năm {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        {{-- Dashboard Accordion --}}
        <div class="accordion" id="dashboardAccordion">
            
            {{-- 1. Thống kê Tổng quan --}}
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="headingOverview">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOverview" aria-expanded="true" aria-controls="collapseOverview">
                        <i class="fas fa-chart-bar me-2"></i>
                        <strong>📊 Thống kê Tổng quan</strong>
                        <span class="badge bg-primary ms-2">3 thống kê</span>
                    </button>
                </h2>
                <div id="collapseOverview" class="accordion-collapse collapse show" aria-labelledby="headingOverview" data-bs-parent="#dashboardAccordion" data-open="true">
                    <div class="accordion-body">
                        <div class="row g-4">
                            {{-- Thống kê Đặt vé --}}
                            <div class="col-xl-4">
                                <div class="card card-height-100">
                                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="card-title flex-grow-1">📦 Thống kê Đặt vé</h4>
                                        <span class="text-muted small">Phân loại trạng thái</span>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover table-nowrap table-centered m-0">
                                            <tbody>
                                                <tr>
                                                    <td>Tổng số đơn</td>
                                                    <td><strong>{{ number_format($totalBookings) }}</strong></td>
                                                </tr>
                                                @if (request('type', 'day') == 'day')
                                                    <tr>
                                                        <td>Doanh thu ngày</td>
                                                        <td><strong>{{ number_format($totalRevenue ?? 0, 0) }} VNĐ</strong></td>
                                                    </tr>
                                                @endif
                                                @if (request('type') == 'month')
                                                    <tr>
                                                        <td>Doanh thu tháng</td>
                                                        <td><strong>{{ number_format($monthlyRevenue, 0) }} VNĐ</strong></td>
                                                    </tr>
                                                @endif
                                                @if (request('type') == 'year')
                                                    <tr>
                                                        <td>Doanh thu năm</td>
                                                        <td><strong>{{ number_format($yearlyRevenue, 0) }} VNĐ</strong></td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td><span class="badge badge-soft-warning">Chờ xử lý</span></td>
                                                    <td>{{ $bookingsByStatus['pending'] ?? 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-soft-success">Đã xác nhận</span></td>
                                                    <td>{{ $bookingsByStatus['confirmed'] ?? 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-soft-danger">Đã hủy</span></td>
                                                    <td>{{ $bookingsByStatus['cancelled'] ?? 0 }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Thống kê Thanh toán --}}
                            <div class="col-xl-4">
                                <div class="card card-height-100">
                                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="card-title flex-grow-1">💳 Thống kê Thanh toán</h4>
                                        <span class="text-muted small">Phân loại trạng thái</span>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover table-nowrap table-centered m-0">
                                            <tbody>
                                                <tr>
                                                    <td>Tổng số thanh toán</td>
                                                    <td><strong>{{ number_format($totalPayments) }}</strong></td>
                                                </tr>
                                                @if (request('type', 'day') == 'day')
                                                    <tr>
                                                        <td>Thanh toán trong ngày</td>
                                                        <td><strong>{{ number_format($totalAmountPaid, 0) }} VNĐ</strong></td>
                                                    </tr>
                                                @endif
                                                @if (request('type') == 'month')
                                                    <tr>
                                                        <td>Thanh toán trong tháng</td>
                                                        <td><strong>{{ number_format($monthlyAmount, 0) }} VNĐ</strong></td>
                                                    </tr>
                                                @endif
                                                @if (request('type') == 'year')
                                                    <tr>
                                                        <td>Thanh toán trong năm</td>
                                                        <td><strong>{{ number_format($yearlyAmount, 0) }} VNĐ</strong></td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td><span class="badge badge-soft-warning">Chờ xử lý</span></td>
                                                    <td>{{ $paymentsByStatus['pending'] ?? 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-soft-success">Thành công</span></td>
                                                    <td>{{ $paymentsByStatus['completed'] ?? 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-soft-danger">Thất bại</span></td>
                                                    <td>{{ $paymentsByStatus['failed'] ?? 0 }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Thống kê Phim --}}
                            <div class="col-xl-4">
                                <div class="card card-height-100 shadow-sm">
                                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="card-title flex-grow-1">🎬 Thống kê Phim</h4>
                                        <span class="text-muted small">Tổng quan</span>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover table-centered mb-3">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Tổng số phim</strong></td>
                                                    <td>{{ number_format($totalMovies) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Đang chiếu</strong></td>
                                                    <td>{{ $nowShowing }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Sắp chiếu</strong></td>
                                                    <td>{{ $upcoming }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Đã kết thúc</strong></td>
                                                    <td>{{ $ended }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Thời lượng trung bình</strong></td>
                                                    <td>{{ number_format($averageDuration, 1) }} phút</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <h6 class="mb-2">📊 Phân loại theo trạng thái</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li><span class="badge bg-success me-2">Đang chiếu:</span>
                                                {{ $moviesByStatus['showing'] ?? 0 }}</li>
                                            <li><span class="badge bg-warning text-dark me-2">Sắp chiếu:</span>
                                                {{ $moviesByStatus['upcoming'] ?? 0 }}</li>
                                            <li><span class="badge bg-secondary me-2">Đã kết thúc:</span>
                                                {{ $moviesByStatus['ended'] ?? 0 }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Biểu đồ & Phân tích --}}
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="headingCharts">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCharts" aria-expanded="false" aria-controls="collapseCharts">
                        <i class="fas fa-chart-line me-2"></i>
                        <strong>📈 Biểu đồ & Phân tích</strong>
                        <span class="badge bg-success ms-2">3 biểu đồ</span>
                    </button>
                </h2>
                <div id="collapseCharts" class="accordion-collapse collapse" aria-labelledby="headingCharts" data-bs-parent="#dashboardAccordion" data-open="false">
                    <div class="accordion-body">
                        {{-- Biểu đồ doanh thu --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">📈 Biểu đồ doanh thu</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="revenue-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hiệu suất đặt vé --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">📊 Hiệu suất đặt vé</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="booking-performance-chart" class="apex-charts" style="min-height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Biểu đồ Comments --}}
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">📊 Thống kê bình luận theo tháng</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="review-monthly-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Thống kê Bình luận --}}
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="headingReviews">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-expanded="false" aria-controls="collapseReviews">
                        <i class="fas fa-comment me-2"></i>
                        <strong>💬 Thống kê Bình luận</strong>
                        <span class="badge bg-warning ms-2">2 thống kê</span>
                    </button>
                </h2>
                <div id="collapseReviews" class="accordion-collapse collapse" aria-labelledby="headingReviews" data-bs-parent="#dashboardAccordion" data-open="false">
                    <div class="accordion-body">
                        <div class="row g-4">
                            <div class="col-xl-6">
                                <div class="card card-height-100">
                                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="card-title flex-grow-1">💬 Thống kê Bình luận</h4>
                                        <span class="text-muted small">Tổng quan</span>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover table-nowrap table-centered m-0">
                                            <tbody>
                                                <tr>
                                                    <td>Tổng số bình luận</td>
                                                    <td><strong>{{ number_format($totalReviews) }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-soft-success">Đã duyệt</span></td>
                                                    <td>{{ $approvedReviews }}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-soft-warning">Chờ duyệt</span></td>
                                                    <td>{{ $pendingReviews }}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-soft-danger">Từ chối</span></td>
                                                    <td>{{ $rejectedReviews }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Top Movies by Comments --}}
                            <div class="col-xl-6">
                                <div class="card card-height-100">
                                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="card-title flex-grow-1">🏆 Top Phim Nhiều Bình luận Nhất</h4>
                                        <span class="text-muted small">Nhiều comment</span>
                                    </div>
                                    <div class="card-body">
                                        @foreach($topReviewedMovies as $index => $movie)
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="badge bg-info me-2">#{{ $index + 1 }}</span>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold">{{ Str::limit($movie->name, 20) }}</div>
                                                    <small class="text-muted">{{ $movie->comments_count }} bình luận</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Dữ liệu Chi tiết --}}
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="headingDetails">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetails" aria-expanded="false" aria-controls="collapseDetails">
                        <i class="fas fa-table me-2"></i>
                        <strong>🎬 Dữ liệu Chi tiết</strong>
                        <span class="badge bg-info ms-2">2 bảng</span>
                    </button>
                </h2>
                <div id="collapseDetails" class="accordion-collapse collapse" aria-labelledby="headingDetails" data-bs-parent="#dashboardAccordion" data-open="false">
                    <div class="accordion-body">
                        {{-- Phim Đang Chiếu Hot Nhất --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">🎬 Phim Đang Chiếu Hot Nhất</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-3">Poster</th>
                                                        <th>Tên phim</th>
                                                        <th>Đạo diễn</th>
                                                        <th>Thời lượng</th>
                                                        <th>Ngày phát hành</th>
                                                        <th>Ngôn ngữ</th>
                                                        <th>Vé đã bán</th>
                                                        <th>Trạng thái</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($hotMovies as $movie)
                                                        <tr>
                                                            <td class="ps-3">
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
                                                                <img src="{{ $posterUrl }}" alt="poster"
                                                                    class="img-fluid avatar-sm" style="object-fit: cover;">
                                                            </td>
                                                            <td><a href="#!" class="text-decoration-none">{{ $movie->name }}</a></td>
                                                            <td>{{ $movie->director ? $movie->director->name : 'N/A' }}</td>
                                                            <td>{{ $movie->duration_minutes }} phút</td>
                                                            <td>{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</td>
                                                            <td>{{ $movie->language }}</td>
                                                            <td>
                                                                <span class="fw-semibold text-success">{{ $movie->total_tickets_sold }}</span>
                                                            </td>
                                                            <td>
                                                                <i class="bx bxs-circle text-success me-1"></i>Đang chiếu
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer border-top bg-light">
                                        <div class="text-muted text-center">
                                            Đang hiển thị <span class="fw-semibold">{{ $hotMovies->count() }}</span> phim hot nhất
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Thống kê Phim theo Doanh thu & Vé bán --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">💰 Thống kê Phim theo Doanh thu & Vé bán</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover align-middle mb-0">
                                                <thead class="table-light text-center">
                                                    <tr>
                                                        <th>Tên phim</th>
                                                        <th>Thể loại</th>
                                                        <th>Số vé bán</th>
                                                        <th>Doanh thu (VNĐ)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($movieStats as $movie)
                                                        <tr>
                                                            <td class="fw-semibold">{{ $movie->movie }}</td>
                                                            <td>{{ $movie->genres }}</td>
                                                            <td class="text-end">{{ number_format($movie->total_tickets) }}</td>
                                                            <td class="text-end text-success">
                                                                {{ number_format($movie->total_revenue, 0) }} VNĐ</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-3">Không có dữ liệu</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <div class="d-flex justify-content-center">
                                            {{ $movieStats->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- End Accordion --}}
    </div> <!-- /.container-fluid -->

        @push('scripts')
            <!-- Bootstrap JS CDN -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    console.log('Dashboard loaded');
                    
                    // Simple accordion functionality without Bootstrap dependency
                    const accordionButtons = document.querySelectorAll('.accordion-button');
                    console.log('Found accordion buttons:', accordionButtons.length);
                    
                    accordionButtons.forEach((button, index) => {
                        console.log('Setting up button', index);
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log('Button clicked:', this.getAttribute('data-bs-target'));
                            
                            const targetId = this.getAttribute('data-bs-target');
                            const target = document.querySelector(targetId);
                            
                            if (target) {
                                // Sử dụng data attribute để track trạng thái đáng tin cậy
                                const isCurrentlyOpen = target.getAttribute('data-open') === 'true';
                                console.log('Currently open:', isCurrentlyOpen);
                                
                                if (isCurrentlyOpen) {
                                    // Đóng
                                    target.setAttribute('data-open', 'false');
                                    target.classList.remove('show');
                                    this.classList.add('collapsed');
                                    this.setAttribute('aria-expanded', 'false');
                                    
                                    // Hide với animation smooth
                                    target.style.maxHeight = '0px';
                                    target.style.opacity = '0';
                                    target.style.overflow = 'hidden';
                                    target.style.height = '0px';
                                    
                                    // Sau animation thì ẩn hoàn toàn
                                    setTimeout(() => {
                                        target.style.display = 'none';
                                    }, 300);
                                    
                                    console.log('Closed accordion');
                                } else {
                                    // Mở
                                    target.setAttribute('data-open', 'true');
                                    target.classList.add('show');
                                    this.classList.remove('collapsed');
                                    this.setAttribute('aria-expanded', 'true');
                                    
                                    // Show với animation smooth
                                    target.style.display = 'block';
                                    target.style.maxHeight = 'none';
                                    target.style.opacity = '1';
                                    target.style.transform = 'none';
                                    target.style.height = 'auto';
                                    target.style.overflow = 'visible';
                                    
                                    console.log('Opened accordion');
                                    
                                    // Render charts sau khi mở
                                    setTimeout(() => {
                                        if (targetId === '#collapseCharts') {
                                            console.log('Rendering charts');
                                            renderCharts();
                                        }
                                    }, 100);
                                }
                            } else {
                                console.log('Target not found:', targetId);
                            }
                        });
                    });

                    // Charts rendering function
                    function renderCharts() {
                        console.log('renderCharts called');
                        
                        // Revenue chart
                        if (document.querySelector("#revenue-chart") && !window.revenueChart) {
                            console.log('Rendering revenue chart');
                            var revenueOptions = {
                                chart: {
                                    type: 'line',
                                    height: 350
                                },
                                series: [{
                                    name: 'Doanh thu (VNĐ)',
                                    data: @json($revenueData ?? [])
                                }],
                                xaxis: {
                                    categories: @json($monthsLabel ?? [])
                                },
                                stroke: {
                                    curve: 'smooth'
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(val) {
                                            return val.toLocaleString('vi-VN') + ' VNĐ';
                                        }
                                    }
                                },
                                colors: ['#00bcd4']
                            };
                            window.revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
                            window.revenueChart.render();
                        }

                        // Booking performance chart
                        if (document.querySelector("#booking-performance-chart") && !window.bookingChart) {
                            console.log('Rendering booking chart');
                            var bookingOptions = {
                                chart: {
                                    type: 'area',
                                    height: 350
                                },
                                series: [{
                                    name: 'Đơn đặt vé',
                                    data: @json($bookingData ?? [])
                                }],
                                xaxis: {
                                    categories: @json($months ?? [])
                                }
                            };
                            window.bookingChart = new ApexCharts(document.querySelector("#booking-performance-chart"), bookingOptions);
                            window.bookingChart.render();
                        }

                        // Review monthly chart
                        if (document.querySelector("#review-monthly-chart") && !window.reviewMonthlyChart) {
                            console.log('Rendering review monthly chart');
                            var reviewMonthlyData = @json($reviewMonthlyStats ?? []);
                            if (reviewMonthlyData.length > 0) {
                                var reviewMonthlyOptions = {
                                    chart: {
                                        type: 'line',
                                        height: 350
                                    },
                                    series: [{
                                        name: 'Số lượng bình luận',
                                        data: reviewMonthlyData.map(item => item.total)
                                    }],
                                    xaxis: {
                                        categories: reviewMonthlyData.map(item => `${item.month}/${item.year}`)
                                    },
                                    stroke: {
                                        curve: 'smooth'
                                    },
                                    colors: ['#00bcd4'],
                                    yaxis: [{
                                        title: {
                                            text: 'Số lượng bình luận'
                                        }
                                    }]
                                };
                                window.reviewMonthlyChart = new ApexCharts(document.querySelector("#review-monthly-chart"), reviewMonthlyOptions);
                                window.reviewMonthlyChart.render();
                            }
                        }

                    }

                    // Initial render for visible charts
                    renderCharts();
                });
            </script>
        @endpush
    @endsection