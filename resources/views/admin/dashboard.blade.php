@extends('layouts.admin.admin')

@section('content')
    <style>
        :root {
            --primary-orange: #FF6F00;
            --primary-teal: #00ACC1;
            --accent-yellow: #FFCA28;
            --neutral-bg: #F5F7FA;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
            background: var(--neutral-bg);
        }

        .btn-outline-primary {
            border-color: var(--primary-teal);
            color: var(--primary-teal);
            transition: var(--transition);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary.active {
            background-color: var(--primary-teal);
            color: white;
            border-color: var(--primary-teal);
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.2rem rgba(255, 111, 0, 0.25);
        }

        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th, .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .badge-soft-warning {
            background-color: rgba(255, 202, 40, 0.2);
            color: var(--accent-yellow);
        }

        .badge-soft-success {
            background-color: rgba(0, 172, 193, 0.2);
            color: var(--primary-teal);
        }

        .badge-soft-danger {
            background-color: rgba(220, 53, 69, 0.2);
            color: #DC3545;
        }

        h4.card-title {
            color: var(--primary-orange);
            font-weight: 700;
        }

        .text-muted {
            color: #6c757d !important;
        }
    </style>

    {{-- Bộ lọc ngày/tháng/năm --}}
    <form method="GET" class="mb-4 d-flex align-items-center gap-3 bg-white p-3 rounded shadow-sm">
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
    <p class="mb-4 text-primary fw-bold">
        @if (request('type', 'day') == 'day')
            Thống kê hôm nay ({{ request('date', now()->format('d/m/Y')) }})
        @elseif(request('type') == 'month')
            Thống kê tháng {{ request('month', now()->format('m')) }}/{{ request('year', now()->format('Y')) }}
        @else
            Thống kê năm {{ request('year', now()->format('Y')) }}
        @endif
    </p>

    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header">
                <h5 style="color: white" class="card-title mb-0">📦 Biểu đồ doanh thu</h5>
            </div>
            <div class="card-body">
                <div id="revenue-chart" style="min-height: 350px;"></div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Thống kê Đặt vé --}}
            <div class="col-xl-4">
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <h4 style="color: white" class="card-title flex-grow-1">📦 Thống kê Đặt vé</h4>
                        <span class="text-white small">Phân loại trạng thái</span>
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
                        <h4 style="color: white" class="card-title flex-grow-1">💳 Thống kê Thanh toán</h4>
                        <span class="text-white small">Phân loại trạng thái</span>
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
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <h4  style="color: white" class="card-title flex-grow-1">🎬 Thống kê Phim</h4>
                        <span class="text-white small">Tổng quan</span>
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
                                <tr>
                                    <td><strong>Đánh giá trung bình</strong></td>
                                    <td>{{ number_format($averageRating, 1) }}/5</td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="mb-2 text-primary">📊 Phân loại theo trạng thái</h6>
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

        {{-- Thống kê Đánh giá --}}
        <div class="row g-4 mt-2">
            <div class="col-xl-3">
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <h4 style="color: white" class="card-title flex-grow-1">⭐ Thống kê Đánh giá</h4>
                        <span class="text-white small">Tổng quan</span>
                    </div>
6397                    <div class="card-body">
                        <table class="table table-hover table-nowrap table-centered m-0">
                            <tbody>
                                <tr>
                                    <td>Tổng số đánh giá</td>
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

            {{-- Phân bố Rating --}}
            <div class="col-xl-3">
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <h4 style="color: white" class="card-title flex-grow-1">🌟 Phân bố Rating</h4>
                        <span class="text-white small">Theo số sao</span>
                    </div>
                    <div class="card-body">
                        @foreach($ratingDistribution as $rating)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->rating_star)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="badge bg-primary">{{ $rating->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Top Movies by Reviews --}}
            <div class="col-xl-3">
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <h4  style="color: white" class="card-title flex-grow-1">🏆 Top Đánh giá</h4>
                        <span class="text-white small">Nhiều review</span>
                    </div>
                    <div class="card-body">
                        @foreach($topReviewedMovies as $index => $movie)
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-info me-2">#{{ $index + 1 }}</span>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ Str::limit($movie->name, 20) }}</div>
                                    <small class="text-muted">{{ $movie->reviews_count }} đánh giá</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Top Rated Movies --}}
            <div class="col-xl-3">
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <h4 style="color: white" class="card-title flex-grow-1">🎯 Top Rating</h4>
                        <span class="text-white small">Chất lượng cao</span>
                    </div>
                    <div class="card-body">
                        @foreach($topRatedMovies as $index => $movie)
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-warning me-2">#{{ $index + 1 }}</span>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ Str::limit($movie->name, 20) }}</div>
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $movie->average_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                        <small class="text-muted ms-1">{{ $movie->average_rating }}/5</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Biểu đồ Reviews --}}
        <div class="row g-4 mt-2">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: white" class="card-title mb-0">Thống kê đánh giá theo tháng</h5>
                    </div>
                    <div class="card-body">
                        <div id="review-monthly-chart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: white" class="card-title mb-0">Phân bố đánh giá theo sao</h5>
                    </div>
                    <div class="card-body">
                        <div id="rating-distribution-chart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Khu vực biểu đồ --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 style="color: white" class="card-title mb-0">Hiệu suất đặt vé</h4>
                    </div>
                    <div class="card-body">
                        <div id="booking-performance-chart" class="apex-charts" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Phim Đang Chiếu Hot Nhất --}}
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4 style="color: white" class="card-title mb-0">🎬 Phim Đang Chiếu Hot Nhất</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-centered">
                            <table class="table mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-3">Poster</th>
                                        <th>Tên phim</th>
                                        <th>Đạo diễn</th>
                                        <th>Thời lượng</th>
                                        <th>Ngày phát hành</th>
                                        <th>Ngôn ngữ</th>
                                        <th>Đánh giá</th>
                                        <th>Vé đã bán</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hotMovies as $movie)
                                        <tr>
                                            <td class="ps-3">
                                                <img src="{{ Storage::url($movie->poster_url) }}" alt="poster"
                                                    class="img-fluid avatar-sm rounded" style="object-fit: cover;">
                                            </td>
                                            <td><a href="#!" class="text-primary">{{ $movie->name }}</a></td>
                                            <td>{{ $movie->director }}</td>
                                            <td>{{ $movie->duration_minutes }} phút</td>
                                            <td>{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</td>
                                            <td>{{ $movie->language }}</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    {{ $movie->average_rating ?? 'N/A' }}/10
                                                </span>
                                            </td>
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
                        <div class="row g-3">
                            <div class="col-sm">
                                <div class="text-muted">
                                    Đang hiển thị <span class="fw-semibold">{{ $hotMovies->count() }}</span> phim
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{-- Nếu hotMovies là collection không phân trang, bỏ dòng này --}}
                                {{-- {{ $hotMovies->withQueryString()->links('pagination::bootstrap-4') }} --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card rounded mb-4">
                    <div class="card-header">
                        <h5 style="color: white" class="mb-0 text-center">🎬 Thống kê Phim theo Doanh thu & Vé bán</h5>
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
                                            <td colspan="4" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3">
                        <div class="d-flex justify-content-center">
                            {{ $movieStats->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /.container-fluid -->

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Biểu đồ booking
                var bookingOptions = {
                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: { show: false }
                    },
                    series: [{
                        name: 'Đơn đặt vé',
                        data: @json($bookingData)
                    }],
                    xaxis: {
                        categories: @json($months),
                        labels: { style: { colors: '#6c757d' } }
                    },
                    colors: ['#00ACC1'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.3,
                            stops: [0, 90, 100]
                        }
                    },
                    stroke: { curve: 'smooth', width: 3 }
                };
                var bookingChart = new ApexCharts(document.querySelector("#booking-performance-chart"), bookingOptions);
                bookingChart.render();

                // Biểu đồ doanh thu theo tháng
                var revenueOptions = {
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: { show: false }
                    },
                    series: [{
                        name: 'Doanh thu (VNĐ)',
                        data: @json($revenueData)
                    }],
                    xaxis: {
                        categories: @json($monthsLabel),
                        labels: { style: { colors: '#6c757d' } }
                    },
                    stroke: { curve: 'smooth', width: 3 },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toLocaleString('vi-VN') + ' VNĐ';
                            }
                        }
                    },
                    colors: ['#FF6F00'],
                    grid: { borderColor: '#e9ecef' }
                };
                var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
                revenueChart.render();

                // Biểu đồ đánh giá theo tháng
                var reviewMonthlyData = @json($reviewMonthlyStats);
                var reviewMonthlyOptions = {
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: { show: false }
                    },
                    series: [{
                        name: 'Số lượng đánh giá',
                        data: reviewMonthlyData.map(item => item.total)
                    }, {
                        name: 'Rating trung bình',
                        data: reviewMonthlyData.map(item => parseFloat(item.avg_rating).toFixed(1))
                    }],
                    xaxis: {
                        categories: reviewMonthlyData.map(item => `${item.month}/${item.year}`),
                        labels: { style: { colors: '#6c757d' } }
                    },
                    stroke: { curve: 'smooth', width: 3 },
                    colors: ['#00ACC1', '#FFCA28'],
                    yaxis: [{
                        title: { text: 'Số lượng đánh giá', style: { color: '#00ACC1' } }
                    }, {
                        opposite: true,
                        title: { text: 'Rating trung bình', style: { color: '#FFCA28' } },
                        min: 0,
                        max: 5
                    }],
                    grid: { borderColor: '#e9ecef' }
                };
                var reviewMonthlyChart = new ApexCharts(document.querySelector("#review-monthly-chart"), reviewMonthlyOptions);
                reviewMonthlyChart.render();

                // Biểu đồ phân bố rating
                var ratingData = @json($ratingDistribution);
                var ratingOptions = {
                    chart: {
                        type: 'donut',
                        height: 350
                    },
                    series: ratingData.map(item => item.count),
                    labels: ratingData.map(item => `${item.rating_star} sao`),
                    colors: ['#FF6F00', '#FFCA28', '#00ACC1', '#4bc0c0', '#36a2eb'],
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Tổng',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                        }
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: { colors: '#6c757d' }
                    }
                };
                var ratingChart = new ApexCharts(document.querySelector("#rating-distribution-chart"), ratingOptions);
                ratingChart.render();
            });
        </script>
    @endpush
@endsection