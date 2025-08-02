@extends('layouts.admin.admin')

@php
    use App\Enums\TicketStatus;
@endphp
@section('content')
    <div class="container">
        <div class="container-xxl">
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Vé chưa sử dụng</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $tickets->where('status', 'valid')->count() }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-success bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:ticket-broken" class="fs-32 text-success avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Vé đã sử dụng</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $tickets->where('status', 'used')->count() }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:clipboard-check-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2">Vé đã hủy</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $tickets->where('status', 'cancelled')->count() }}</p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-danger bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:cart-cross-broken" class="fs-32 text-danger avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Danh sách vé</h4>
                    <div class="d-flex gap-2 align-items-center">
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('tickets.index') }}" class="d-flex align-items-center gap-2">
                            <input type="text" name="search" placeholder="Tìm kiếm ID vé hoặc ID suất chiếu" value="{{ request('search') }}" class="form-control form-control-sm" style="width: 200px;">
                            <button type="submit" class="btn btn-sm btn-primary">Tìm</button>
                        </form>
                        {{-- <!-- Add New Button -->
                        <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary">Thêm mới</a> --}}
                        <!-- Filter Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                Lọc
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <form method="GET" action="{{ route('tickets.index') }}" class="p-2">
                                    <div class="mb-2">
                                        <label for="id" class="form-label">ID Vé</label>
                                        <input type="text" name="id" id="id" placeholder="ID Vé" value="{{ request('id') }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-2">
                                        <label for="showtime_id" class="form-label">ID Suất chiếu</label>
                                        <input type="text" name="showtime_id" id="showtime_id" placeholder="ID Suất chiếu" value="{{ request('showtime_id') }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-2">
                                        <label for="booking_id" class="form-label">ID Đơn hàng</label>
                                        <input type="text" name="booking_id" id="booking_id" placeholder="ID Đơn hàng" value="{{ request('booking_id') }}" class="form-control form-control-sm">
                                    </div>
                                    {{-- <div class="mb-2">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select name="status" id="status" class="form-control form-control-sm">
                                            <option value="">Tất cả trạng thái</option>
                                            @foreach($statuses as $value => $label)
                                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Lọc</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check">
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
                                        <div class="form-check">
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
                                                <span class="badge bg-success">Chưa sử dụng</span>
                                                @break
                                            @case('used')
                                                <span class="badge bg-primary">Đã sử dụng</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">Không xác định</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- View Button -->
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <!-- Print Ticket Button -->
                                            <a href="{{ route('admin.tickets.print', $ticket->ticket_code) }}" class="btn btn-success btn-sm" title="In vé" target="_blank">
                                                <iconify-icon icon="solar:printer-minimalistic-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <!-- Edit Button -->
                                            {{-- <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-soft-primary btn-sm" title="Sửa">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a> --}}
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có vé nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <nav aria-label="Page navigation example">
                        {{ $tickets->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection