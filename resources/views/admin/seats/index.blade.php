@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <style>
        :root {
            --primary-blue: #2196f3;
            --primary-dark: #1565c0;
            --accent-green: #43e97b;
            --accent-yellow: #FFCA28;
            --neutral-bg: #F8FAFC;
            --card-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
            --transition: all 0.3s ease;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--neutral-bg);
        }
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(33, 150, 243, 0.15);
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
        }
        .badge {
            font-size: 0.95rem;
            border-radius: 50px;
            padding: 0.5em 1em;
        }
        .badge-available {
            background: var(--accent-green);
            color: #fff;
        }
        .badge-reserved {
            background: var(--accent-yellow);
            color: #1a202c;
        }
        .badge-booked {
            background: #1565c0;
            color: #fff;
        }
        .badge-other {
            background: #dc3545;
            color: #fff;
        }
        .btn {
            font-weight: 600;
            transition: var(--transition);
            border-radius: 50px;
        }
        .btn-primary {
            background: var(--primary-blue);
            border: none;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.3);
        }
        .btn-outline-primary {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }
        .btn-outline-primary:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
        }
        .btn-outline-info {
            border-color: var(--accent-green);
            color: var(--accent-green);
        }
        .btn-outline-info:hover {
            background: var(--accent-green);
            color: white;
            transform: translateY(-2px);
        }
        .table > :not(caption) > * > * {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        .table tbody tr {
            transition: var(--transition);
        }
        .table tbody tr:hover {
            background: rgba(33, 150, 243, 0.05);
            transform: translateX(2px);
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.2);
        }
        .input-group-text {
            background: var(--neutral-bg);
            border-color: #e0e0e0;
            border-radius: 8px;
        }
        .pagination {
            --bs-pagination-border-radius: 8px;
        }
        .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 3px;
            color: var(--primary-blue);
            transition: var(--transition);
        }
        .page-link:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
        }
        .page-item.active .page-link {
            background: var(--primary-blue);
            border: none;
            color: white;
        }
        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            .input-group {
                min-width: 100% !important;
            }
        }
    </style>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary-blue);">
                        <i class="fas fa-chair me-2"></i>
                        Quản lý ghế ngồi
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý toàn bộ ghế ngồi trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.seats.create') }}" class="btn btn-primary btn-lg rounded-pill">
                        <i class="fas fa-plus-circle me-2"></i> Thêm ghế mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-table me-2"></i>
                                Danh sách ghế ngồi
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search and Filter Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.seats.index') }}">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text rounded-start-pill">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="search" name="query" 
                                               class="form-control ps-0" 
                                               placeholder="Tìm kiếm ghế..." 
                                               value="{{ request('query') }}">
                                        <select name="seat_type_id" class="form-select rounded-end-pill">
                                            <option value="">Tất cả loại ghế</option>
                                            @foreach ($seatTypes as $seatType)
                                                <option value="{{ $seatType->id }}" {{ request('seat_type_id') == $seatType->id ? 'selected' : '' }}>
                                                    {{ $seatType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4" style="width: 50px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkAllSeats">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-hashtag me-1" style="color: var(--primary-blue);"></i> ID
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-film me-1" style="color: var(--primary-dark);"></i> Phòng chiếu
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-chair me-1" style="color: var(--accent-green);"></i> Loại ghế
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-layer-group me-1" style="color: var(--accent-yellow);"></i> Hàng ghế
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-hashtag me-1" style="color: var(--primary-blue);"></i> Số ghế
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-traffic-light me-1" style="color: var(--primary-dark);"></i> Trạng thái
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-plus me-1" style="color: var(--accent-yellow);"></i> Tạo lúc
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-blue);"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($seats as $seat)
                                    @php
                                        $statusValue = is_object($seat->status) ? $seat->status->value : $seat->status;
                                        $statusClass = $statusValue === 'available' ? 'badge-available' : ($statusValue === 'reserved' ? 'badge-reserved' : ($statusValue === 'booked' ? 'badge-booked' : 'badge-other'));
                                    @endphp
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input seat-checkbox" value="{{ $seat->id }}">
                                            </div>
                                        </td>
                                        <td class="fw-medium">{{ $seat->id }}</td>
                                        <td>{{ $seat->room->name }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $seat->seatType->color_code }}; color: #fff;">
                                                {{ $seat->seatType->name }}
                                            </span>
                                        </td>
                                        <td>{{ $seat->row_char }}</td>
                                        <td>{{ $seat->seat_number }}</td>
                                        <td>
                                            <span class="badge {{ $statusClass }}">
                                                <i class="fas fa-traffic-light me-1"></i>
                                                {{ ucfirst($statusValue) }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">{{ $seat->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.seats.show', $seat->id) }}"
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.seats.edit', $seat->id) }}"
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Chỉnh sửa"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($seats->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-chair text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có ghế nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm ghế mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($seats->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $seats->firstItem() }}-{{ $seats->lastItem() }} trong tổng {{ $seats->total() }} ghế
                            </div>
                            <div>
                                {{ $seats->appends(['query' => request('query'), 'seat_type_id' => request('seat_type_id')])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Check all seats
    document.getElementById('checkAllSeats')?.addEventListener('change', function() {
        document.querySelectorAll('.seat-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
});
</script>
@endsection