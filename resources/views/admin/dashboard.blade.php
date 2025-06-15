@extends('layouts.admin.admin')

@section('content')
    <div class="container-fluid">
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Biểu đồ doanh thu theo tháng</h5>
            </div>
            <div class="card-body">
                <div id="revenue-chart"></div>
            </div>
        </div>

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
                                <tr>
                                    <td>Tổng doanh thu</td>
                                    <td><strong>${{ number_format($totalRevenue, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Doanh thu tuần</td>
                                    <td><strong>${{ number_format($weeklyRevenue, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Doanh thu tháng</td>
                                    <td><strong>${{ number_format($monthlyRevenue, 2) }}</strong></td>
                                </tr>
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
                                <tr>
                                    <td>Tổng số tiền đã thanh toán</td>
                                    <td><strong>${{ number_format($totalAmountPaid, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Thanh toán trong tuần</td>
                                    <td><strong>${{ number_format($weeklyAmount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Thanh toán trong tháng</td>
                                    <td><strong>${{ number_format($monthlyAmount, 2) }}</strong></td>
                                </tr>
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

            {{-- Thống kê Người dùng --}}
            <div class="col-xl-4">
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <h4 class="card-title flex-grow-1"><i class="bi bi-people-fill me-2 text-primary"></i>Thống kê Người
                            dùng</h4>
                        <span class="text-muted small">Tổng quan tài khoản</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-nowrap table-centered m-0">
                            <tbody>
                                <tr>
                                    <td>Tổng số người dùng</td>
                                    <td><strong>{{ number_format($totalUsers) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Đã xác minh email</td>
                                    <td><strong>{{ $verifiedEmails }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Chưa xác minh email</td>
                                    <td><strong>{{ $unverifiedEmails }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Có ảnh đại diện</td>
                                    <td><strong>{{ $withAvatar }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Đã đăng nhập</td>
                                    <td><strong>{{ $withLogin }}</strong></td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-soft-success">Đang hoạt động</span></td>
                                    <td>{{ $statusCounts['active'] }}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-soft-warning">Không hoạt động</span></td>
                                    <td>{{ $statusCounts['inactive'] }}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-soft-danger">Đã khóa</span></td>
                                    <td>{{ $statusCounts['suspended'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Thống kê Phim --}}
            <div class="col-12">
                <div class="card card-height-100 mt-4">
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
                                    <td><strong>Đang chiếu</strong></td>
                                    <td>{{ $nowShowing }}</td>
                                    <td><strong>Sắp chiếu</strong></td>
                                    <td>{{ $upcoming }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Đã kết thúc</strong></td>
                                    <td>{{ $ended }}</td>
                                    <td><strong>Thời lượng TB</strong></td>
                                    <td>{{ number_format($averageDuration, 1) }} phút</td>
                                    <td><strong>Đánh giá TB</strong></td>
                                    <td>{{ number_format($averageRating, 1) }}/10</td>
                                </tr>
                            </tbody>
                        </table>

                        <h6 class="mb-2">Phân loại theo trạng thái</h6>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Đang chiếu:</strong> {{ $moviesByStatus['showing'] ?? 0 }}</li>
                            <li><strong>Sắp chiếu:</strong> {{ $moviesByStatus['upcoming'] ?? 0 }}</li>
                            <li><strong>Đã kết thúc:</strong> {{ $moviesByStatus['ended'] ?? 0 }}</li>
                        </ul>
                    </div>
                </div>
            </div>


            {{-- Khu vực biểu đồ --}}
            <div class="col-xxl-7">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Hiệu suất đặt vé</h4>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-light">Tất cả</button>
                                <button type="button" class="btn btn-sm btn-outline-light">1 Tháng</button>
                                <button type="button" class="btn btn-sm btn-outline-light">6 Tháng</button>
                                <button type="button" class="btn btn-sm btn-outline-light active">1 Năm</button>
                            </div>
                        </div>

                        <div dir="ltr">
                            <div id="booking-performance-chart" class="apex-charts" style="min-height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Người dùng hoạt động</h5>
                        <div id="active-users-chart" class="apex-charts mb-2 mt-n2" style="height: 240px;"></div>
                        <div class="row text-center">
                            <div class="col-6">
                                <p class="text-muted mb-2">Hoạt động</p>
                                <h3 class="text-dark mb-3">{{ $activeUsers }}
                                    ({{ round(($activeUsers / ($totalUsers ?: 1)) * 100, 1) }}%)</h3>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-2">Tổng số</p>
                                <h3 class="text-dark mb-3">{{ $totalUsers }}</h3>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('index') }}" class="btn btn-light shadow-none w-100">Chi tiết</a>
                        </div>

                    </div>
                </div>
            </div>


        </div> <!-- /.row -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title">
                                🎬 Phim Đang Chiếu Hot Nhất
                            </h4>

                        </div>
                    </div>

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
                                            <img src={{Storage::url($movie->poster_url)}} alt="poster" class="img-fluid avatar-sm"
                                                style="object-fit: cover;">
                                        </td>
                                        <td><a href="#!">{{ $movie->name }}</a></td>
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

                    <div class="card-footer border-top">
                        <div class="row g-3">
                            <div class="col-sm">
                                <div class="text-muted">
                                    Đang hiển thị <span class="fw-semibold">{{ $hotMovies->count() }}</span> phim
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $hotMovies->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- /.container-fluid -->
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Biểu đồ booking
                var bookingOptions = {
                    chart: {
                        type: 'area',
                        height: 350
                    },
                    series: [{
                        name: 'Đơn đặt vé',
                        data: @json($bookingData)
                    }],
                    xaxis: {
                        categories: @json($months)
                    }
                };
                var bookingChart = new ApexCharts(document.querySelector("#booking-performance-chart"), bookingOptions);
                bookingChart.render();

                // Biểu đồ người dùng hoạt động (radial bar style)
                var activeUserRate = {{ round(($activeUsers / ($totalUsers ?: 1)) * 100, 1) }};
                var userRadialOptions = {
                    chart: {
                        type: "radialBar",
                        height: 240
                    },
                    series: [activeUserRate],
                    labels: ["Hoạt động"],
                    colors: ['#28a745'],
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: "60%"
                            },
                            dataLabels: {
                                name: {
                                    show: false
                                },
                                value: {
                                    fontSize: "22px",
                                    formatter: function(val) {
                                        return val + "%";
                                    }
                                }
                            },
                            track: {
                                background: '#e5e7eb',
                            }
                        }
                    }
                };
                var activeUserChart = new ApexCharts(document.querySelector("#active-users-chart"), userRadialOptions);
                activeUserChart.render();

                // Biểu đồ doanh thu theo tháng (không tính đơn huỷ)
                var revenueOptions = {
                    chart: {
                        type: 'line',
                        height: 350
                    },
                    series: [{
                        name: 'Doanh thu (VNĐ)',
                        data: @json($revenueData)
                    }],
                    xaxis: {
                        categories: @json($monthsLabel)
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
                var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
                revenueChart.render();
            });
        </script>
    @endpush
@endsection
