@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                {{-- Thông báo  --}}
                @include('admin.partials.alert')
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
                                    <td>{{ $ticket->status }}</td>
                                    <td>{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- View Button -->
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <!-- Edit Button -->
                                            {{-- <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-soft-primary btn-sm" title="Sửa">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a> --}}
                                            <!-- Delete Button -->
                                            {{-- <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa vé này?')">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </button> --}}
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