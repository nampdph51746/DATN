@extends('layouts.admin.admin')

@section('content')
    <style>
        :root {
            --primary-blue: #2196F3;
            --primary-purple: #9C27B0;
            --accent-orange: #FF5722;
            --neutral-bg: #F8FAFC;
            --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            --border-radius: 10px;
            --transition: all 0.2s ease;
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            background: white;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stat-card .icon {
            position: absolute;
            right: 1rem;
            top: 1rem;
            opacity: 0.2;
            font-size: 2.5rem;
        }

        .stat-card .stat-title {
            font-size: 0.875rem;
            color: #64748B;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1rem 1.5rem;
        }

        .time-filter {
            background: white;
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn-filter {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .btn-filter.active {
            background: var(--primary-blue);
            color: white;
        }
    </style>

    <div class="container-fluid">
        {{-- Bộ lọc thời gian --}}
        <div class="time-filter shadow-sm">
            <form method="GET" class="d-flex align-items-center gap-3">
                <div class="btn-group">
                    <button type="submit" name="type" value="day"
                        class="btn btn-filter {{ request('type', 'day') == 'day' ? 'active' : '' }}">
                        <i class="fas fa-calendar-day me-1"></i> Ngày
                    </button>
                    <button type="submit" name="type" value="month"
                        class="btn btn-filter {{ request('type') == 'month' ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt me-1"></i> Tháng
                    </button>
                    <button type="submit" name="type" value="year"
                        class="btn btn-filter {{ request('type') == 'year' ? 'active' : '' }}">
                        <i class="fas fa-calendar me-1"></i> Năm
                    </button>
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

        {{-- Thống kê tổng quan --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-gradient-primary text-white">
                    <i class="fas fa-ticket-alt icon"></i>
                    <h6 class="stat-title">Tổng đơn đặt vé</h6>
                    <h3 class="stat-value">{{ number_format($totalBookings) }}</h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-gradient-success text-white">
                    <i class="fas fa-money-bill-wave icon"></i>
                    <h6 class="stat-title">Doanh thu</h6>
                    <h3 class="stat-value">{{ number_format($totalRevenue) }}đ</h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-gradient-info text-white">
                    <i class="fas fa-film icon"></i>
                    <h6 class="stat-title">Phim đang chiếu</h6>
                    <h3 class="stat-value">{{ number_format($nowShowing) }}</h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-gradient-warning text-white">
                    <i class="fas fa-users icon"></i>
                    <h6 class="stat-title">Lượt đặt vé hôm nay</h6>
                    <h3 class="stat-value">{{ $bookingData[date('G')] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Biểu đồ và Phim hot --}}
        <div class="row mb-4">
            {{-- Biểu đồ doanh thu --}}
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: white" class="card-title mb-0">� Biểu đồ doanh thu</h5>
                    </div>
                    <div class="card-body">
                        <div id="revenue-chart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0" style="color: white">🔥 Phim Hot Nhất</h5>
                    </div>
                    <div class="card-body">
                        @if($hotestMovie)
                        <div class="text-center mb-3">
                            @if($hotestMovie->poster_url)
                                <img src="{{ Storage::url($hotestMovie->poster_url) }}" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;" alt="{{ $hotestMovie->name }}">
                            @endif
                        </div>
                        <h5 class="text-primary mb-3">{{ $hotestMovie->name }}</h5>
                        <p class="text-muted small mb-2">
                            <strong>Đạo diễn:</strong> {{ $hotestMovie->director }}
                        </p>
                        <p class="text-muted small mb-2">
                            <strong>Thời lượng:</strong> {{ $hotestMovie->duration_minutes }} phút
                        </p>
                        <p class="text-muted small mb-3">
                            <strong>Khởi chiếu:</strong> {{ $hotestMovie->release_date ? date('d/m/Y', strtotime($hotestMovie->release_date)) : 'N/A' }}
                        </p>
                        <hr>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-2 rounded bg-light text-center">
                                    <h6 class="text-success mb-0">{{ number_format($hotestMovie->total_revenue) }}đ</h6>
                                    <small class="text-muted">Doanh thu</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded bg-light text-center">
                                    <h6 class="text-primary mb-0">{{ number_format($hotestMovie->total_tickets) }}</h6>
                                    <small class="text-muted">Vé đã bán</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded bg-light text-center">
                                    <h6 class="text-info mb-0">{{ number_format($hotestMovie->total_showtimes) }}</h6>
                                    <small class="text-muted">Suất chiếu</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded bg-light text-center">
                                    <h6 class="text-warning mb-0">{{ number_format($hotestMovie->total_bookings) }}</h6>
                                    <small class="text-muted">Lượt đặt</small>
                                </div>
                            </div>
                        </div>
                        @else
                        <p class="text-muted">Không có dữ liệu phim.</p>
                        @endif
                    </div>
                </div>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 style="color: white" class="card-title mb-0">
                            🎬 Top Phim Đang Chiếu Hot Nhất
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" style="width: 80px;">Poster</th>
                                        <th>Tên phim</th>
                                        <th>Đạo diễn</th>
                                        <th>Ngày khởi chiếu</th>
                                        <th>Doanh thu</th>
                                        <th>Vé đã bán</th>
                                        <th>Đánh giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hotMovies as $movie)
                                        <tr>
                                            <td class="ps-3">
                                                <img src="{{ Storage::url($movie->poster_url) }}" 
                                                     alt="{{ $movie->name }}" 
                                                     class="img-fluid rounded"
                                                     style="width: 60px; height: 80px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <h6 class="mb-1">{{ $movie->name }}</h6>
                                                <small class="text-muted">{{ $movie->duration_minutes }} phút</small>
                                            </td>
                                            <td>--</td>
                                            <td>{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</td>
                                            <td class="text-success fw-semibold">
                                                {{ number_format($movie->total_revenue) }}đ
                                            </td>
                                            <td class="fw-semibold">
                                                {{ number_format($movie->total_tickets) }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="fs-5 text-warning">⭐</span>
                                                    <span>{{ number_format($movie->average_rating ?? 0, 1) }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thống kê phim theo doanh thu --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 style="color: white" class="card-title mb-0">
                            📊 Thống kê Phim theo Doanh thu & Vé bán
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên phim</th>
                                        <th>Thể loại</th>
                                        <th class="text-end">Số vé bán</th>
                                        <th class="text-end">Doanh thu (VNĐ)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($movieStats as $movie)
                                        <tr>
                                            <td class="fw-semibold">{{ $movie->movie }}</td>
                                            <td>{{ $movie->genres }}</td>
                                            <td class="text-end">{{ number_format($movie->total_tickets) }}</td>
                                            <td class="text-end text-success fw-semibold">
                                                {{ number_format($movie->total_revenue, 0) }}đ
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">
                                                Không có dữ liệu phim
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <small class="text-muted">
                                    @if($movieStats instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        Hiển thị {{ $movieStats->count() }} trong tổng số {{ $movieStats->total() }} phim
                                    @else
                                        Hiển thị {{ count($movieStats) }} phim
                                    @endif
                                </small>
                            </div>
                            <div class="col">
                                @if($movieStats instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    <div class="d-flex justify-content-end">
                                        {{ $movieStats->links() }}
                                    </div>
                                @endif
                            </div>
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


            });
        </script>
    @endpush
@endsection