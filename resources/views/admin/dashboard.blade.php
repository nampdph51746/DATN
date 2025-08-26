@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid dashboard-container">
    <!-- Filter Section -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="form-label fw-medium">Thống kê theo:</label>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="day" {{ request('type', 'day') == 'day' ? 'selected' : '' }}>Ngày</option>
                        <option value="month" {{ request('type') == 'month' ? 'selected' : '' }}>Tháng</option>
                        <option value="year" {{ request('type') == 'year' ? 'selected' : '' }}>Năm</option>
                    </select>
                </div>
                @if(request('type', 'day') == 'day')
                    <div class="col-auto">
                        <label class="form-label fw-medium">Chọn ngày:</label>
                        <input type="date" name="date" class="form-control" 
                            value="{{ request('date', now()->format('Y-m-d')) }}"
                            onchange="this.form.submit()">
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Revenue Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Doanh thu</h6>
                            <h3 class="mb-0">{{ number_format($growthStats['current_revenue']) }}đ</h3>
                            <small>
                                @if($growthStats['revenue_growth'] > 0)
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                                {{ number_format(abs($growthStats['revenue_growth']), 1) }}%
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-success bg-gradient text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Người dùng</h6>
                            <h3 class="mb-0">{{ number_format($totalUsers) }}</h3>
                            <small>{{ $activeUsers }} hoạt động 7 ngày qua</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movies Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-info bg-gradient text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Phim</h6>
                            <h3 class="mb-0">{{ $movieStats['total'] }}</h3>
                            <small>{{ $movieStats['showing'] }} đang chiếu</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-film fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-warning bg-gradient text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Đánh giá</h6>
                            <h3 class="mb-0">{{ $reviewStats['total'] }}</h3>
                            <small>{{ $reviewStats['pending'] }} chờ duyệt</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-star fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Biểu đồ doanh thu & đặt vé</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Hot Movies -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Top phim nổi bật</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                    @foreach($movieStats['hotMovies'] as $movie)
                        <div class="list-group-item border-0 d-flex align-items-center px-0">
                            <img src="{{ Storage::url($movie->poster_url) }}" 
                                class="rounded" width="48" height="48" 
                                style="object-fit: cover;">
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $movie->name }}</h6>
                                <small class="text-muted">
                                    {{ number_format($movie->tickets_count) }} vé đã bán
                                </small>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.dashboard-container {
    padding: 1.5rem;
}
.stat-card {
    transition: transform 0.2s;
}
.stat-card:hover {
    transform: translateY(-5px);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthsLabel) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueData) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Lượt đặt vé',
                data: {!! json_encode($bookingData) !!},
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = new Intl.NumberFormat('vi-VN').format(context.parsed.y);
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection