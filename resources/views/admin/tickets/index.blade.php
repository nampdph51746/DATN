@extends('layouts.admin.admin')

@php
    use App\Enums\TicketStatus;
@endphp

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-2 text-muted">Vé chưa sử dụng</h5>
                            <h3 class="mb-0">{{ $tickets->where('status', 'valid')->count() }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-success-subtle rounded-circle">
                            <iconify-icon icon="solar:ticket-broken" class="fs-4 text-success"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-2 text-muted">Vé đã sử dụng</h5>
                            <h3 class="mb-0">{{ $tickets->where('status', 'used')->count() }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-primary-subtle rounded-circle">
                            <iconify-icon icon="solar:clipboard-check-broken" class="fs-4 text-primary"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-2 text-muted">Vé đã hủy</h5>
                            <h3 class="mb-0">{{ $tickets->where('status', 'cancelled')->count() }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-danger-subtle rounded-circle">
                            <iconify-icon icon="solar:cart-cross-broken" class="fs-4 text-danger"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket List Table -->
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                <h5 class="card-title mb-0">Danh sách vé</h5>
                <div class="d-flex align-items-center gap-2">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.tickets.index') }}" class="d-flex align-items-center gap-2">
                        <input type="text" name="search" placeholder="Tìm kiếm ID vé hoặc ID suất chiếu" value="{{ request('search') }}" class="form-control" style="width: 250px;">
                        <button type="submit" class="btn btn-primary"><iconify-icon icon="solar:magnifer-broken" class="fs-18"></iconify-icon></button>
                    </form>
                    <!-- Filter Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <iconify-icon icon="solar:filter-broken" class="fs-18"></iconify-icon> Lọc
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 300px;">
                            <form method="GET" action="{{ route('admin.tickets.index') }}">
                                <div class="mb-3">
                                    <label for="id" class="form-label">ID Vé</label>
                                    <input type="text" name="id" id="id" placeholder="ID Vé" value="{{ request('id') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="showtime_id" class="form-label">ID Suất chiếu</label>
                                    <input type="text" name="showtime_id" id="showtime_id" placeholder="ID Suất chiếu" value="{{ request('showtime_id') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="booking_id" class="form-label">ID Đơn hàng</label>
                                    <input type="text" name="booking_id" id="booking_id" placeholder="ID Đơn hàng" value="{{ request('booking_id') }}" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Áp dụng bộ lọc</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20px;">
                                    <div class="form-check mb-0">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1"></label>
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>ID Suất chiếu</th>
                                <th>Đơn hàng</th>
                                <th>Ghế</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    <div class="form-check mb-0">
                                        <input type="checkbox" class="form-check-input" id="ticketCheck{{ $ticket->id }}">
                                        <label class="form-check-label" for="ticketCheck{{ $ticket->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->showtime_id }}</td>
                                <td>{{ $ticket->booking_id }}</td>
                                <td>{{ $ticket->seat_id ?? '-' }}</td>
                                <td>
                                    @php
                                        $status = $ticket->status instanceof TicketStatus ? $ticket->status->value : $ticket->status;
                                    @endphp
                                    @switch($status)
                                        @case('valid')
                                            <span class="badge bg-success-subtle text-success">Chưa sử dụng</span>
                                            @break
                                        @case('used')
                                            <span class="badge bg-primary-subtle text-primary">Đã sử dụng</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger-subtle text-danger">Đã hủy</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-subtle text-secondary">Không xác định</span>
                                    @endswitch
                                </td>
                                <td>{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-secondary" title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="fs-16"></iconify-icon>
                                        </a>
                                        <!-- Print Ticket Button -->
                                        <a href="{{ route('admin.tickets.print', $ticket->ticket_code) }}" 
                                           class="btn btn-sm btn-success print-ticket-btn" 
                                           title="In vé" 
                                           target="_blank"
                                           data-showtime-start="{{ $ticket->showtime ? $ticket->showtime->start_time->toISOString() : '' }}"
                                           data-ticket-code="{{ $ticket->ticket_code }}">
                                            <iconify-icon icon="solar:printer-minimalistic-broken" class="fs-16"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Không có vé nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top">
                <nav aria-label="Page navigation example">
                    {{ $tickets->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tickets.js') }}"></script>
@endpush