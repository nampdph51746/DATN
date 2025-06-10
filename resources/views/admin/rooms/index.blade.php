@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        @include('admin.partials.notifications')

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Danh Sách Tất Cả Phòng Chiếu</h4>
                        {{-- Form tìm kiếm theo tên phòng --}}
                        <form method="GET" action="{{ route('admin.rooms.index') }}" class="d-flex align-items-center ms-2">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Tìm tên phòng..." value="{{ request('search') }}">
                                <span class="input-group-text bg-white border-start-0">
                                    <iconify-icon icon="mdi:magnify" class="fs-18 text-muted"></iconify-icon>
                                </span>
                            </div>
                        </form>
                        <a href="{{ route('admin.rooms.create') }}" class="btn btn-sm btn-primary">
                            Thêm Phòng Chiếu
                        </a>

                        {{-- Bộ lọc loại phòng --}}
                        <form method="GET" action="{{ route('admin.rooms.index') }}"
                            class="d-flex align-items-center ms-2">
                            <select name="room_type_id" class="form-select form-select-sm me-2"
                                onchange="this.form.submit()">
                                <option value="">-- Tất cả loại phòng --</option>
                                @foreach ($roomTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
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
                                        <th>Tên Phòng</th>
                                        <th>Tên Rạp Chiếu</th>
                                        <th>Tên Loại Phòng</th>
                                        <th>Sức Chứa</th>
                                        <th>Trạng Thái</th>
                                        <th>ID</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rooms as $room)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="customCheck{{ $room->id }}">
                                                    <label class="form-check-label"
                                                        for="customCheck{{ $room->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">

                                                    <p class="text-dark fw-medium fs-15 mb-0">{{ $room->name }}</p>
                                                </div>
                                            </td>
                                            <td>{{ $room->cinema->name }}</td>
                                            <td>{{ $room->roomType->name }}</td>
                                            <td>{{ $room->capacity }}</td>
                                            <td>
                                                @if ($room->status === 'active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @elseif ($room->status === 'maintenance')
                                                    <span class="badge bg-warning text-dark">Bảo trì</span>
                                                @endif
                                            </td>
                                            <td>{{ $room->id }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.rooms.show', $room->id) }}"
                                                        class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                            class="align-middle fs-18"></iconify-icon></a>
                                                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                                        class="btn btn-soft-primary btn-sm"><iconify-icon
                                                            icon="solar:pen-2-broken"
                                                            class="align-middle fs-18"></iconify-icon></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        {{ $rooms->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection