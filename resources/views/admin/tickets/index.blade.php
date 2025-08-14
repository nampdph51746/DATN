@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4">
    <style>
        :root {
            --primary-orange: #FF6F00;
            --primary-teal: #00ACC1;
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
            box-shadow: 0 12px 30px rgba(0, 172, 193, 0.15);
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.5rem;
        }
        .avatar-lg {
            width: 4rem;
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-teal));
            border-radius: 50%;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .badge {
            font-size: 0.95rem;
            border-radius: 50px;
            padding: 0.5em 1em;
        }
        .badge-valid {
            background: var(--primary-teal);
            color: #fff;
        }
        .badge-used {
            background: var(--primary-orange);
            color: #fff;
        }
        .badge-cancelled {
            background: #dc3545;
            color: #fff;
        }
        .badge-other {
            background: #6c757d;
            color: #fff;
        }
        .btn {
            font-weight: 600;
            transition: var(--transition);
            border-radius: 50px;
        }
        .btn-primary {
            background: var(--primary-orange);
            border: none;
        }
        .btn-primary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 172, 193, 0.3);
        }
        .btn-outline-secondary {
            border-color: var(--primary-teal);
            color: var(--primary-teal);
        }
        .btn-outline-secondary:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
        }
        .view-detail-btn, .print-btn {
            border-radius: 8px;
            min-width: 36px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .view-detail-btn {
            background: var(--primary-teal);
            border: 1px solid var(--primary-teal);
            color: white;
        }
        .view-detail-btn:hover {
            background: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: #1a202c;
            transform: scale(1.1);
        }
        .print-btn {
            background: var(--primary-orange);
            border: 1px solid var(--primary-orange);
            color: white;
        }
        .print-btn:hover {
            background: var(--primary-teal);
            border-color: var(--primary-teal);
            color: white;
            transform: scale(1.1);
        }
        .table > :not(caption) > * > * {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        .table tbody tr {
            transition: var(--transition);
        }
        .table tbody tr:hover {
            background: rgba(0, 172, 193, 0.05);
            transform: translateX(2px);
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 111, 0, 0.2);
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
            color: var(--primary-teal);
            transition: var(--transition);
        }
        .page-link:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }
        .page-item.active .page-link {
            background: var(--primary-teal);
            border: none;
            color: white;
        }
        .form-check-input:checked {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }
        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }
            .avatar-lg {
                width: 3rem;
                height: 3rem;
                font-size: 1.4rem;
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
                    <h4 class="fw-bold mb-1" style="color: var(--primary-orange);">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Quản lý vé
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Quản lý danh sách vé trong hệ thống
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-ticket-alt fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Tổng vé</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-teal);">{{ $tickets->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-teal); width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Vé chưa sử dụng</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--primary-orange);">{{ $tickets->where('status', 'valid')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--primary-orange); width: {{ $tickets->total() > 0 ? ($tickets->where('status', 'valid')->count() / $tickets->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-calendar-check fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Vé đã sử dụng</h6>
                            <h3 class="mb-0 fw-bold" style="color: var(--accent-yellow);">{{ $tickets->where('status', 'used')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: var(--accent-yellow); width: {{ $tickets->total() > 0 ? ($tickets->where('status', 'used')->count() / $tickets->total()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <i class="fas fa-times-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted fw-semibold">Vé đã hủy</h6>
                            <h3 class="mb-0 fw-bold" style="color: #dc3545;">{{ $tickets->where('status', 'cancelled')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" style="background: #dc3545; width: {{ $tickets->total() > 0 ? ($tickets->where('status', 'cancelled')->count() / $tickets->total()) * 100 : 0 }}%"></div>
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
                                Danh sách vé
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <!-- Search Section -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.tickets.index') }}">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text rounded-start-pill">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="search" name="search" 
                                               class="form-control ps-0" 
                                               placeholder="Tìm kiếm vé..." 
                                               value="{{ request('search') }}">
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
                                            <input type="checkbox" class="form-check-input" id="checkAllTickets">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-hashtag me-1" style="color: var(--primary-orange);"></i> ID
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-film me-1" style="color: var(--primary-teal);"></i> Suất chiếu
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-shopping-cart me-1" style="color: var(--accent-yellow);"></i> Đơn hàng
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-chair me-1" style="color: var(--primary-orange);"></i> Ghế
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-traffic-light me-1" style="color: var(--primary-teal);"></i> Trạng thái
                                    </th>
                                    <th class="border-0 fw-bold text-dark">
                                        <i class="fas fa-calendar-plus me-1" style="color: var(--accent-yellow);"></i> Ngày đặt
                                    </th>
                                    <th class="border-0 fw-bold text-dark text-center pe-4">
                                        <i class="fas fa-cogs me-1" style="color: var(--primary-orange);"></i> Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    @php
                                        $status = $ticket->status instanceof \App\Enums\TicketStatus ? $ticket->status->value : $ticket->status;
                                        $statusClass = $status === 'valid' ? 'badge-valid' : ($status === 'used' ? 'badge-used' : ($status === 'cancelled' ? 'badge-cancelled' : 'badge-other'));
                                    @endphp
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input ticket-checkbox" value="{{ $ticket->id }}">
                                            </div>
                                        </td>
                                        <td class="fw-medium">#{{ $ticket->id }}</td>
                                        <td>
                                            <i class="fas fa-film text-info me-1"></i>
                                            {{ $ticket->showtime_id }}
                                        </td>
                                        <td>
                                            <i class="fas fa-shopping-cart text-warning me-1"></i>
                                            {{ $ticket->booking_id }}
                                        </td>
                                        <td>
                                            <i class="fas fa-chair text-primary me-1"></i>
                                            {{ $ticket->seat_id ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $statusClass }}">
                                                <i class="fas fa-traffic-light me-1"></i>
                                                @switch($status)
                                                    @case('valid')
                                                        Chưa sử dụng
                                                        @break
                                                    @case('used')
                                                        Đã sử dụng
                                                        @break
                                                    @case('cancelled')
                                                        Đã hủy
                                                        @break
                                                    @default
                                                        Không xác định
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-plus text-success me-1"></i>
                                            {{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                                                   class="btn btn-sm view-detail-btn" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-ticket-alt text-muted fs-1 mb-3"></i>
                                                <h6 class="text-muted">Không có vé nào</h6>
                                                <p class="text-muted small mb-0">Hãy thêm vé mới để bắt đầu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($tickets->hasPages())
                    <div class="card-footer bg-white border-top py-4 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Hiển thị {{ $tickets->firstItem() }}-{{ $tickets->lastItem() }} trong tổng {{ $tickets->total() }} vé
                            </div>
                            <div>
                                {{ $tickets->appends(request()->query())->links('pagination::bootstrap-5') }}
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

    // Check all tickets
    document.getElementById('checkAllTickets')?.addEventListener('change', function() {
        document.querySelectorAll('.ticket-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
});
</script>
@endsection