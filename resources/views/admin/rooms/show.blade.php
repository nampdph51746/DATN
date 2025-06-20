@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Chi tiết phòng chiếu: {{ $room->name }}</h4>
                    <div>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-sm me-2">Quay lại</a>
                        @if ($seats->count() >= $room->capacity)
                            <button class="btn btn-primary btn-sm disabled" title="Phòng đã đầy ghế">Thêm ghế</button>
                        @else
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSeatsModal">Thêm ghế</button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h5>Thông tin phòng</h5>
                    <p><strong>Tên phòng:</strong> {{ $room->name }}</p>
                    <p><strong>Rạp chiếu:</strong> {{ $room->cinema->name }}</p>
                    <p><strong>Loại phòng:</strong> {{ $room->roomType->name }}</p>
                    <p><strong>Sức chứa:</strong> {{ $room->capacity }}</p>
                    <p><strong>Trạng thái:</strong>
                        @switch($room->status)
                            @case('active')
                                <span class="badge bg-success">Hoạt động</span>
                                @break
                            @case('maintenance')
                                <span class="badge bg-warning text-dark">Bảo trì</span>
                                @break
                            @case('inactive')
                                <span class="badge bg-secondary">Không hoạt động</span>
                                @break
                            @default
                                <span class="badge bg-danger">Không xác định</span>
                        @endswitch
                    </p>

                    <h5 class="mt-4">Phân bổ ghế</h5>
                    @php
                        $seatPercentages = config('seat_types.percentages');
                        $requiredSeats = [];
                        foreach ($seatPercentages as $type => $percentage) {
                            $requiredSeats[$type] = (int) round(($percentage / 100) * $room->capacity);
                        }
                        $existingSeatsByType = $seats->groupBy('seatType.name')->map->count()->toArray();
                    @endphp
                    <ul>
                        @foreach ($seatPercentages as $type => $percentage)
                            <li><strong>{{ $type }} ({{ $percentage }}%):</strong> 
                                {{ $existingSeatsByType[$type] ?? 0 }} / {{ $requiredSeats[$type] }} ghế
                                (Còn lại: {{ max(0, $requiredSeats[$type] - ($existingSeatsByType[$type] ?? 0)) }} ghế)
                            </li>
                        @endforeach
                    </ul>

                    <h5 class="mt-4">Sơ đồ ghế</h5>
                    @if ($seats->isEmpty())
                        <p class="text-success">Phòng chưa có ghế nào. Số ghế có thể thêm: {{ $room->capacity }}</p>
                    @else
                        <div class="seat-map" style="display: grid; gap: 5px; grid-template-columns: repeat({{ $maxSeatsPerRow + 1 }}, 40px); max-width: {{ ($maxSeatsPerRow + 1) * 45 }}px;">
                            <!-- Header số ghế -->
                            <div class="seat"></div>
                            @for ($i = 1; $i <= $maxSeatsPerRow; $i++)
                                <div class="seat seat-number-label">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                            @endfor
                            <!-- Ghế theo hàng -->
                            @foreach ($rows as $row)
                                <div class="seat seat-row-label">{{ $row }}</div>
                                @for ($i = 1; $i <= $maxSeatsPerRow; $i++)
                                    @php
                                        $seatNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
                                        $seat = $seats->where('row_char', $row)->where('seat_number', $seatNumber)->first();
                                    @endphp
                                    @if ($seat)
                                        <div class="seat 
                                            {{ $seat->seatType->name === 'VIP' ? 'seat-vip' : '' }}
                                            {{ $seat->seatType->name === 'Sweetbox' ? 'seat-sweetbox' : '' }}
                                            {{ $seat->status->value === 'booked' ? 'seat-booked' : '' }}
                                            {{ $seat->status->value === 'reserved' ? 'seat-reserved' : '' }}"
                                             data-seat-id="{{ $seat->id }}"
                                             title="{{ $seat->row_char }}{{ $seat->seat_number }} ({{ $seat->seatType->name }}, {{ ucfirst($seat->status->value) }})"
                                             data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true"
                                             data-bs-title="Ghế: {{ $seat->row_char }}{{ $seat->seat_number }}<br>Loại: {{ $seat->seatType->name }}<br>Trạng thái: {{ ucfirst($seat->status->value) }}">
                                            {{ $seat->row_char }}{{ $seat->seat_number }}
                                        </div>
                                    @else
                                        <div class="seat seat-empty"></div>
                                    @endif
                                @endfor
                            @endforeach
                        </div>
                        <p class="mt-2">Số ghế hiện có: {{ $seats->count() }} / {{ $room->capacity }} (Còn lại: {{ $room->capacity - $seats->count() }})</p>
                    @endif

                    <!-- Modal Thêm Ghế -->
                    <div class="modal fade" id="addSeatsModal" tabindex="-1" aria-labelledby="addSeatsModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSeatsModalLabel">Thêm ghế cho phòng: {{ $room->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="seatForm" action="{{ route('admin.seats.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                                        <div class="mb-3">
                                            <label for="seat_type_id" class="form-label">Loại ghế</label>
                                            <select name="seat_type_id" id="seat_type_id" class="form-control" required>
                                                <option value="">Chọn loại ghế</option>
                                                @php
                                                    $seatTypesOrder = config('seat_types.order');
                                                    $nextSeatTypeId = session('next_seat_type_id');
                                                    if (!$nextSeatTypeId) {
                                                        foreach ($seatTypesOrder as $type) {
                                                            $currentTypeSeats = $existingSeatsByType[$type] ?? 0;
                                                            $requiredTypeSeats = $requiredSeats[$type] ?? 0;
                                                            if ($currentTypeSeats < $requiredTypeSeats) {
                                                                $nextSeatType = $seatTypes->where('name', $type)->first();
                                                                if ($nextSeatType) {
                                                                    $nextSeatTypeId = $nextSeatType->id;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @foreach ($seatTypes as $seatType)
                                                    <option value="{{ $seatType->id }}" {{ $seatType->id == $nextSeatTypeId ? 'selected' : '' }}>
                                                        {{ $seatType->name }} (Còn lại: {{ max(0, ($requiredSeats[$seatType->name] ?? 0) - ($existingSeatsByType[$seatType->name] ?? 0)) }} ghế)
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('seat_type_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="rows" class="form-label">Số hàng</label>
                                            <input type="number" name="rows" id="rows" class="form-control" min="1" value="1" required>
                                            @error('rows')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="seats_per_row" class="form-label">Số ghế mỗi hàng</label>
                                            <input type="number" name="seats_per_row" id="seats_per_row" class="form-control" min="1" value="1" required>
                                            @error('seats_per_row')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <p><strong>Sức chứa phòng:</strong> {{ $room->capacity }}</p>
                                            <p><strong>Ghế hiện có:</strong> {{ $seats->count() }}</p>
                                            <p><strong>Ghế còn lại:</strong> {{ $room->capacity - $seats->count() }}</p>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Thêm ghế</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .seat-map {
        display: grid;
        gap: 5px;
        max-width: 600px;
    }
    .seat {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #28a745; /* Regular (Thường) */
        border-radius: 4px;
        font-size: 12px;
        text-align: center;
        color: white;
        cursor: pointer; /* Thêm con trỏ tay khi hover */
        transition: transform 0.2s; /* Hiệu ứng hover */
    }
    .seat:hover {
        transform: scale(1.1); /* Phóng to nhẹ khi hover */
    }
    .seat-row-label {
        background-color: #6c757d; /* Màu cho hàng (A, B, C,...) */
        color: white;
    }
    .seat-number-label {
        background-color: #adb5bd; /* Màu cho mã ghế (01, 02,...) */
        color: black;
    }
    .seat-empty {
        background-color: #e9ecef;
    }
    .seat-vip {
        background-color: #ffd700; /* VIP */
        color: black;
    }
    .seat-sweetbox {
        background-color: #ff69b4; /* Sweetbox */
        color: black;
    }
    .seat-booked {
        background-color: #dc3545;
        color: white;
    }
    .seat-reserved {
        background-color: #ffc107;
        color: black;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        document.querySelectorAll('.seat').forEach(seat => {
            if (seat.dataset.seatId) {
                seat.addEventListener('click', function() {
                    window.location.href = '{{ route("admin.seats.edit", ":id") }}'.replace(':id', this.dataset.seatId);
                });
            }
        });
    });
</script>
@endpush

@endsection