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
                        $requiredSeats = [];
                        foreach ($seatPercentages as $seatTypeId => $percentage) {
                            $seatType = $seatTypes->where('id', $seatTypeId)->first();
                            $requiredSeats[$seatTypeId] = (int) round(($percentage / 100) * $room->capacity);
                        }
                        $existingSeatsByType = $seats->groupBy('seat_type_id')->map->count()->toArray();
                    @endphp
                    <ul>
                        @foreach ($seatTypes as $seatType)
                            <li><strong>{{ $seatType->name }} ({{ $seatPercentages[$seatType->id] ?? 0 }}%):</strong> 
                                {{ $existingSeatsByType[$seatType->id] ?? 0 }} / {{ $requiredSeats[$seatType->id] ?? 0 }} ghế
                                (Còn lại: {{ max(0, ($requiredSeats[$seatType->id] ?? 0) - ($existingSeatsByType[$seatType->id] ?? 0)) }} ghế)
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

                                        <!-- Tỷ lệ loại ghế -->
                                        <div class="mb-3">
                                            <label class="form-label">Tỷ lệ loại ghế (%)</label>
                                            @foreach ($seatTypes as $seatType)
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <label for="seat_type_percentages_{{ $seatType->id }}" class="form-label">{{ $seatType->name }}</label>
                                                    <input type="number" name="seat_type_percentages[{{ $seatType->id }}]" id="seat_type_percentages_{{ $seatType->id }}" 
                                                           class="form-control w-20" min="0" max="100" 
                                                           value="{{ $seatPercentages[$seatType->id] ?? 0 }}" step="0.01" required>
                                                    <span>%</span>
                                                </div>
                                            @endforeach
                                            @error('seat_type_percentages')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <p class="text-muted mt-2" id="percentageWarning"></p>
                                        </div>

                                        <!-- Loại ghế -->
                                        <div class="mb-3">
                                            <label for="seat_type_id" class="form-label">Loại ghế</label>
                                            <select name="seat_type_id" id="seat_type_id" class="form-control" required>
                                                <option value="">Chọn loại ghế</option>
                                                @php
                                                    $seatTypesOrder = config('seat_types.order');
                                                    $nextSeatTypeId = session('next_seat_type_id');
                                                    if (!$nextSeatTypeId) {
                                                        foreach ($seatTypesOrder as $type) {
                                                            $seatType = $seatTypes->where('name', $type)->first();
                                                            $currentTypeSeats = $existingSeatsByType[$seatType->id] ?? 0;
                                                            $requiredTypeSeats = $requiredSeats[$seatType->id] ?? 0;
                                                            if ($currentTypeSeats < $requiredTypeSeats) {
                                                                $nextSeatTypeId = $seatType->id;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @foreach ($seatTypes as $seatType)
                                                    <option value="{{ $seatType->id }}" {{ $seatType->id == $nextSeatTypeId ? 'selected' : '' }}>
                                                        {{ $seatType->name }} (Còn lại: {{ max(0, ($requiredSeats[$seatType->id] ?? 0) - ($existingSeatsByType[$seatType->id] ?? 0)) }} ghế)
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('seat_type_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Số ghế mỗi hàng -->
                                        <div class="mb-3">
                                            <label for="seats_per_row" class="form-label">Số ghế mỗi hàng</label>
                                            <input type="number" name="seats_per_row" id="seats_per_row" class="form-control" min="1" value="1" required>
                                            @error('seats_per_row')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <p class="text-muted mt-2" id="seatsPerRowSuggestion"></p>
                                        </div>

                                        <!-- Số ghế tối thiểu mỗi hàng -->
                                        <div class="mb-3">
                                            <label for="min_seats_per_row" class="form-label">Số ghế tối thiểu mỗi hàng</label>
                                            <input type="number" name="min_seats_per_row" id="min_seats_per_row" class="form-control" min="1" value="1" required>
                                            @error('min_seats_per_row')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <p class="text-muted mt-2" id="minSeatsPerRowSuggestion"></p>
                                        </div>

                                        <div class="mb-3">
                                            <p><strong>Sức chứa phòng:</strong> {{ $room->capacity }}</p>
                                            <p><strong>Ghế hiện có:</strong> {{ $seats->count() }}</p>
                                            <p><strong>Ghế còn lại:</strong> {{ $room->capacity - $seats->count() }}</p>
                                            <p id="remainingSeatsWarning" class="text-warning"></p>
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
        max-width: {{ ($maxSeatsPerRow + 1) * 45 }}px;
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
        cursor: pointer;
        transition: transform 0.2s;
    }
    .seat:hover {
        transform: scale(1.1);
    }
    .seat-row-label {
        background-color: #6c757d;
        color: white;
    }
    .seat-number-label {
        background-color: #adb5bd;
        color: black;
    }
    .seat-empty {
        background-color: #e9ecef;
    }
    .seat-vip {
        background-color: #ffd700;
        color: black;
    }
    .seat-sweetbox {
        background-color: #ff69b4;
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

        const seatForm = document.getElementById('seatForm');
        const percentageInputs = document.querySelectorAll('input[name^="seat_type_percentages"]');
        const seatsPerRowInput = document.getElementById('seats_per_row');
        const minSeatsPerRowInput = document.getElementById('min_seats_per_row');
        const percentageWarning = document.getElementById('percentageWarning');
        const seatsPerRowSuggestion = document.getElementById('seatsPerRowSuggestion');
        const minSeatsPerRowSuggestion = document.getElementById('minSeatsPerRowSuggestion');
        const remainingSeatsWarning = document.getElementById('remainingSeatsWarning');
        const seatTypeSelect = document.getElementById('seat_type_id');
        const roomCapacity = {{ $room->capacity }};
        const existingSeats = {{ $seats->count() }};
        const remainingCapacity = roomCapacity - existingSeats;
        const existingSeatsByType = {{ json_encode($existingSeatsByType) }};
        const maxSeatsPerRow = {{ $maxSeatsPerRow }};
        const maxRows = {{ $maxRows }};

        function updateCalculations() {
            let totalPercentage = 0;
            percentageInputs.forEach(input => {
                totalPercentage += parseFloat(input.value) || 0;
            });

            // Cảnh báo tổng % ghế
            if (Math.abs(totalPercentage - 100) > 0.01) {
                percentageWarning.innerHTML = '<span class="text-danger">Tổng tỷ lệ phải bằng 100%. Hiện tại: ' + totalPercentage.toFixed(2) + '%</span>';
            } else {
                percentageWarning.innerHTML = '<span class="text-success">Tổng tỷ lệ hợp lệ: ' + totalPercentage.toFixed(2) + '%</span>';
            }

            // Lấy loại ghế được chọn
            const seatTypeId = seatTypeSelect.value;
            const percentageInput = document.getElementById('seat_type_percentages_' + seatTypeId);
            if (!percentageInput) {
                seatsPerRowSuggestion.innerHTML = '';
                remainingSeatsWarning.innerHTML = '';
                minSeatsPerRowSuggestion.innerHTML = '';
                return;
            }

            const percentage = parseFloat(percentageInput.value) || 0;
            const requiredSeats = Math.round((percentage / 100) * roomCapacity);
            const currentTypeSeats = existingSeatsByType[seatTypeId] || 0;
            const seatsToAdd = Math.min(requiredSeats - currentTypeSeats, remainingCapacity);

            const seatsPerRow = parseInt(seatsPerRowInput.value) || 1;
            const minSeatsPerRow = parseInt(minSeatsPerRowInput.value) || 1;
            const rows = Math.ceil(seatsToAdd / seatsPerRow);
            const totalSeatsProposed = rows * seatsPerRow;
            const excessSeats = totalSeatsProposed - seatsToAdd;

            // Cảnh báo dư ghế
            if (excessSeats > 0) {
                remainingSeatsWarning.innerHTML = '<span class="text-warning">Cảnh báo: Sẽ có ' + excessSeats + ' ghế dư do không chia hết số hàng.</span>';
            } else {
                remainingSeatsWarning.innerHTML = '';
            }

            // Gợi ý số ghế mỗi hàng tối ưu
            if (seatsToAdd > 0) {
                let optimalSeatsPerRow = seatsPerRow;
                for (let i = minSeatsPerRow; i <= maxSeatsPerRow; i++) {
                    if (seatsToAdd % i === 0 && seatsToAdd / i <= maxRows) {
                        optimalSeatsPerRow = i;
                        break;
                    }
                }
                const suggestedRows = Math.ceil(seatsToAdd / optimalSeatsPerRow);
                seatsPerRowSuggestion.innerHTML = 'Đề xuất số ghế mỗi hàng: ' + optimalSeatsPerRow + ' (chia hết cho ' + seatsToAdd + ' ghế, tạo ' + suggestedRows + ' hàng)';
            } else {
                seatsPerRowSuggestion.innerHTML = 'Không cần thêm ghế cho loại này.';
            }

            // Cảnh báo nếu < số ghế tối thiểu mỗi hàng
            if (seatsPerRow < minSeatsPerRow) {
                minSeatsPerRowSuggestion.innerHTML = '<span class="text-danger">Số ghế mỗi hàng phải ≥ số tối thiểu (' + minSeatsPerRow + ').</span>';
            } else {
                minSeatsPerRowSuggestion.innerHTML = '';
            }
        }

        // Gắn sự kiện
        percentageInputs.forEach(input => input.addEventListener('input', updateCalculations));
        seatsPerRowInput.addEventListener('input', updateCalculations);
        minSeatsPerRowInput.addEventListener('input', updateCalculations);
        seatTypeSelect.addEventListener('change', () => setTimeout(updateCalculations, 10));

        // Click ghế để chỉnh sửa
        document.querySelectorAll('.seat').forEach(seat => {
            if (seat.dataset.seatId) {
                seat.addEventListener('click', function () {
                    window.location.href = '{{ route("admin.seats.edit", ":id") }}'.replace(':id', this.dataset.seatId);
                });
            }
        });

        // Kiểm tra tổng % khi gửi form
        seatForm.addEventListener('submit', function(e) {
            let totalPercentage = 0;
            percentageInputs.forEach(input => {
                totalPercentage += parseFloat(input.value) || 0;
            });
            if (Math.abs(totalPercentage - 100) > 0.01) {
                e.preventDefault();
                alert('Tổng tỷ lệ loại ghế phải bằng 100%.');
            }
        });

        // Khởi động tính toán ban đầu
        updateCalculations();
    });
</script>
@endpush

@endsection