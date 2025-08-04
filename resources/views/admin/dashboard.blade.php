{{-- filepath: c:\laragon\www\DATN\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.admin.admin')

@section('content')
    {{-- Bộ lọc ngày/tháng/năm --}}
    <form method="GET" class="mb-3 d-flex align-items-center gap-2">
        <div class="btn-group" role="group">
            <button type="submit" name="type" value="day"
                class="btn btn-outline-primary {{ request('type', 'day') == 'day' ? 'active' : '' }}">Ngày</button>
            <button type="submit" name="type" value="month"
                class="btn btn-outline-primary {{ request('type') == 'month' ? 'active' : '' }}">Tháng</button>
            <button type="submit" name="type" value="year"
                class="btn btn-outline-primary {{ request('type') == 'year' ? 'active' : '' }}">Năm</button>
        </div>
        @if (request('type', 'day') == 'day')
            <input type="date" name="date" class="form-control w-auto ms-2"
                value="{{ request('date', now()->toDateString()) }}">
        @endif
        @if (request('type') == 'month')
            <select name="month" class="form-select w-auto ms-2">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                        Tháng {{ $m }}
                    </option>
                @endfor
            </select>
            <select name="year" class="form-select w-auto ms-2">
                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                        Năm {{ $y }}
                    </option>
                @endfor
            </select>
        @endif
        @if (request('type') == 'year')
            <select name="year" class="form-select w-auto ms-2">
                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                        Năm {{ $y }}
                    </option>
                @endfor
            </select>
        @endif
    </form>
    <p class="mb-3">
        <strong>
            @if (request('type', 'day') == 'day')
                Thống kê hôm nay ({{ request('date', now()->format('d/m/Y')) }})
            @elseif(request('type') == 'month')
                Thống kê tháng {{ request('month', now()->format('m')) }}/{{ request('year', now()->format('Y')) }}
            @else
                Thống kê năm {{ request('year', now()->format('Y')) }}
            @endif
        </strong>
    </p>



    <div class="container-fluid">
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Biểu đồ doanh thu</h5>
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
                                <tr>
                                    <td><strong>Đánh giá trung bình</strong></td>
                                    <td>{{ number_format($averageRating, 1) }}/10</td>
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


            {{-- Khu vực biểu đồ --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Hiệu suất đặt vé</h4>
                            <div id="booking-performance-chart" class="apex-charts" style="min-height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Phim Đang Chiếu Hot Nhất --}}
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">🎬 Phim Đang Chiếu Hot Nhất</h4>
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
                                                <img src="{{ Storage::url($movie->poster_url) }}" alt="poster"
                                                    class="img-fluid avatar-sm" style="object-fit: cover;">
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
                                                <span
                                                    class="fw-semibold text-success">{{ $movie->total_tickets_sold }}</span>
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
                                    {{-- Nếu hotMovies là collection không phân trang, bỏ dòng này --}}
                                    {{-- {{ $hotMovies->withQueryString()->links('pagination::bootstrap-4') }} --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm border-0 rounded mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 text-center">🎬 Thống kê Phim theo Doanh thu & Vé bán</h5>
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

                    // Biểu đồ doanh thu theo tháng
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
