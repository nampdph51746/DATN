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
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1rem 1.5rem;
        }

        .stat-card {
            padding: 1rem;
            border-radius: var(--border-radius);
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
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-ticket-alt fa-2x me-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Tổng đơn đặt vé</h6>
                            <h3 class="mb-0">{{ number_format($totalBookings) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-gradient-success text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave fa-2x me-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Doanh thu</h6>
                            <h3 class="mb-0">{{ number_format($totalRevenue) }}đ</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-gradient-info text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-film fa-2x me-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Phim đang chiếu</h6>
                            <h3 class="mb-0">{{ number_format($nowShowing) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-gradient-warning text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x me-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Lượt đặt vé hôm nay</h6>
                            <h3 class="mb-0">{{ $bookingData[date('G')] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Biểu đồ thống kê --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Biểu đồ doanh thu theo {{ request('type', 'ngày') }}</h4>
                    </div>
                    <div class="card-body">
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Lượt đặt vé theo giờ</h4>
                    </div>
                    <div class="card-body">
                        <div id="bookingChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Top sản phẩm bán chạy</h4>
                    </div>
                    <div class="card-body">
                        <div id="productChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Phân bố doanh thu</h4>
                    </div>
                    <div class="card-body">
                        <div id="revenueDistributionChart"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Phim hot nhất --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Danh sách phim hot</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Poster</th>
                                        <th>Tên phim</th>
                                        <th class="text-center">Thời lượng</th>
                                        <th class="text-center">Ngày chiếu</th>
                                        <th class="text-end">Doanh thu</th>
                                        <th class="text-center">Đã bán</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hotestMovies as $movie)
                                        <tr>
                                            <td class="ps-3">
                                                <img src="{{ Storage::url($movie->poster_url) }}" 
                                                     alt="{{ $movie->name }}" 
                                                     class="img-fluid rounded shadow-sm"
                                                     style="width: 60px; height: 80px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-1">{{ $movie->name }}</h6>
                                                    <small class="text-muted">{{ $movie->genres }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $movie->duration_minutes }} phút</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</td>
                                            <td class="text-end text-success fw-semibold">
                                                {{ number_format($movie->total_revenue, 0, ',', '.') }}đ
                                            </td>
                                            <td class="text-center fw-semibold">
                                                {{ number_format($movie->total_tickets, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#movieDetail{{ $movie->id }}">
                                                    <i class="fas fa-info-circle me-1"></i>Chi tiết
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-3 text-muted">
                                                Không có dữ liệu phim
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modals --}}
        @foreach($hotestMovies as $movie)
        <div class="modal fade" id="movieDetail{{ $movie->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chi tiết phim: {{ $movie->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ Storage::url($movie->poster_url) }}" 
                                     alt="{{ $movie->name }}"
                                     class="img-fluid rounded shadow-sm mb-3">
                                
                                <!-- Biểu đồ thống kê -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3">Thống kê vé theo giờ</h6>
                                        <div id="ticketChart{{ $movie->id }}"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge p-2 bg-success bg-opacity-10 text-success">
                                                            <i class="fas fa-chart-line fa-fw"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Tổng doanh thu</h6>
                                                        <h4 class="mb-0">{{ number_format($movie->total_revenue, 0, ',', '.') }}đ</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge p-2 bg-primary bg-opacity-10 text-primary">
                                                            <i class="fas fa-ticket-alt fa-fw"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Tổng vé đã bán</h6>
                                                        <h4 class="mb-0">{{ number_format($movie->total_tickets, 0, ',', '.') }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge p-2 bg-warning bg-opacity-10 text-warning">
                                                            <i class="fas fa-clock fa-fw"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Tổng suất chiếu</h6>
                                                        <h4 class="mb-0">{{ number_format($movie->total_showtimes ?? 0) }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge p-2 bg-info bg-opacity-10 text-info">
                                                            <i class="fas fa-info-circle fa-fw"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Trạng thái</h6>
                                                        <h4 class="mb-0">
                                                            @if($movie->status === 'showing')
                                                                <span class="badge bg-success">Đang chiếu</span>
                                                            @elseif($movie->status === 'upcoming')
                                                                <span class="badge bg-primary">Sắp chiếu</span>
                                                            @else
                                                                <span class="badge bg-secondary">Ngừng chiếu</span>
                                                            @endif
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Biểu đồ doanh thu
            var revenueOptions = {
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Doanh thu',
                    data: @json($revenueData)
                }],
                xaxis: {
                    categories: @json($revenueDates),
                    labels: {
                        style: { fontSize: '12px' }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                        }
                    }
                },
                colors: ['#2196F3'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 }
            };
            new ApexCharts(document.querySelector("#revenueChart"), revenueOptions).render();

            // Biểu đồ lượt đặt vé theo giờ
            var bookingOptions = {
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '60%'
                    }
                },
                series: [{
                    name: 'Lượt đặt',
                    data: @json(array_values($bookingData))
                }],
                xaxis: {
                    categories: @json(array_keys($bookingData)),
                    labels: {
                        formatter: function(value) {
                            return value + 'h';
                        }
                    }
                },
                colors: ['#9C27B0'],
                dataLabels: { enabled: false }
            };
            new ApexCharts(document.querySelector("#bookingChart"), bookingOptions).render();

            // Biểu đồ top sản phẩm
            var productOptions = {
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                        barHeight: '50%'
                    }
                },
                series: [{
                    name: 'Số lượng bán',
                    data: @json(array_column($topProducts, 'quantity'))
                }],
                xaxis: {
                    categories: @json(array_column($topProducts, 'name')),
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                colors: ['#FF5722'],
                dataLabels: {
                    enabled: true,
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value);
                    }
                }
            };
            new ApexCharts(document.querySelector("#productChart"), productOptions).render();

            // Biểu đồ phân bố doanh thu
            var distributionOptions = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                series: @json(array_column($revenueDistribution, 'value')),
                labels: @json(array_column($revenueDistribution, 'name')),
                colors: ['#2196F3', '#FF5722', '#9C27B0', '#4CAF50'],
                dataLabels: {
                    enabled: true,
                    formatter: function(value, { seriesIndex, w }) {
                        return w.config.labels[seriesIndex] + ': ' + value.toFixed(1) + '%';
                    }
                },
                legend: {
                    position: 'bottom'
                }
            };
            new ApexCharts(document.querySelector("#revenueDistributionChart"), distributionOptions).render();

            // Biểu đồ chi tiết phim
            @foreach($hotestMovies as $movie)
            var ticketOptions{{ $movie->id }} = {
                chart: {
                    type: 'bar',
                    height: 200,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded',
                        borderRadius: 4
                    },
                },
                dataLabels: { enabled: false },
                stroke: { width: 2 },
                series: [{
                    name: 'Số vé',
                    data: @json($movie->hourly_tickets)
                }],
                xaxis: {
                    categories: ['8-10h', '10-12h', '12-14h', '14-16h', '16-18h', '18-20h', '20-22h', '22-24h'],
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    tickAmount: 4,
                    min: 0
                },
                colors: ['#2196F3'],
                grid: { 
                    show: true,
                    borderColor: '#f1f1f1',
                    padding: { left: 10, right: 10 }
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        chart: { height: 150 },
                        xaxis: {
                            labels: {
                                style: { fontSize: '10px' }
                            }
                        }
                    }
                }]
            };
            new ApexCharts(document.querySelector("#ticketChart{{ $movie->id }}"), ticketOptions{{ $movie->id }}).render();
            @endforeach
        });
    </script>
    @endpush
@endsection
