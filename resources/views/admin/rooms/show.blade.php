@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="container-xxl py-4">
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-12">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Thông tin phòng chiếu</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tên phòng:</strong> {{ $room->name }}</p>
                                        <p><strong>Rạp chiếu:</strong>
                                            {{ $room->cinema ? $room->cinema->name : 'Chưa có thông tin rạp' }}</p>
                                        <p><strong>Loại phòng:</strong>
                                            {{ $room->roomType ? $room->roomType->name : 'Chưa có thông tin loại phòng' }}
                                        </p>
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
                                    </div>
                                </div>
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Phân bổ loại ghế</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Loại ghế</th>
                                                        <th>Tỷ lệ (%)</th>
                                                        <th>Đã có</th>
                                                        <th>Cần có</th>
                                                        <th>Còn lại</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($room->allowedSeatTypes() as $seatType)
                                                        <tr>
                                                            <td class="text-center">{{ $seatType->name }}</td>
                                                            <td class="text-center">
                                                                {{ $seatPercentages[$seatType->id] ?? 0 }}</td>
                                                            <td class="text-center">
                                                                {{ $existingSeatsByType[$seatType->id] ?? 0 }}</td>
                                                            <td class="text-center">{{ $requiredSeats[$seatType->id] ?? 0 }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ max(0, ($requiredSeats[$seatType->id] ?? 0) - ($existingSeatsByType[$seatType->id] ?? 0)) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Nút quay lại đã được chuyển xuống dưới, xóa nút cũ này -->
                            </div>
                            <div class="col-lg-5 col-md-12 d-flex flex-column align-items-center">
                                @if ($seats->count() > 0)
                                    <div class="seat-map mb-3"
                                        style="display: grid; gap: 5px; grid-template-columns: repeat({{ $maxSeatsPerRow + 1 }}, 40px); max-width: {{ ($maxSeatsPerRow + 1) * 45 }}px;">
                                        <div class="seat"></div>
                                        @for ($i = 1; $i <= $maxSeatsPerRow; $i++)
                                            <div class="seat seat-number-label">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                        @endfor
                                        @foreach ($rows as $row)
                                            <div class="seat seat-row-label">{{ $row }}</div>
                                            @for ($i = 1; $i <= $maxSeatsPerRow; $i++)
                                                @php
                                                    $seatNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                    $seat = $seats
                                                        ->where('row_char', $row)
                                                        ->where('seat_number', $seatNumber)
                                                        ->first();
                                                @endphp
                                                @if ($seat)
                                                    <div class="seat"
                                                        style="background-color: {{ $seat->seatType->color_code ?? '#28a745' }}; color: {{ isset($seat->seatType->color_code) && strtolower($seat->seatType->color_code) == '#ffd700' ? 'black' : 'white' }};"
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
                                    <p class="mt-2">Số ghế hiện có: {{ $seats->count() }} / {{ $room->capacity }} (Còn
                                        lại: {{ $room->capacity - $seats->count() }})</p>
                                @else
                                    <p class="text-success">Phòng chưa có ghế nào. Số ghế có thể thêm:
                                        {{ $room->capacity }}</p>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Thêm ghế cho phòng: {{ $room->name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="seatForm" action="{{ route('admin.seats.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                                            <div class="mb-3">
                                                <label class="form-label">Tỷ lệ loại ghế (%)</label>
                                                @foreach ($allowedSeatTypes as $seatType)
                                                    <div class="d-flex align-items-center mb-2 gap-2">
                                                        <label for="seat_type_percentages_{{ $seatType->id }}"
                                                            class="form-label mb-0"
                                                            style="min-width:110px;">{{ $seatType->name }}</label>
                                                        <input type="number"
                                                            name="seat_type_percentages[{{ $seatType->id }}]"
                                                            id="seat_type_percentages_{{ $seatType->id }}"
                                                            class="form-control" min="0" max="100"
                                                            value="{{ $seatPercentages[$seatType->id] ?? 0 }}"
                                                            step="0.01" required
                                                            style="width: 160px; text-align: center;">
                                                        <span>%</span>
                                                    </div>
                                                @endforeach
                                                @error('seat_type_percentages')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <p class="text-muted mt-2" id="percentageWarning"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="seat_type_id" class="form-label">Loại ghế</label>
                                                <select name="seat_type_id" id="seat_type_id" class="form-control" required
                                                    disabled>
                                                    @php
                                                        $currentTypeId =
                                                            $next_seat_type_id ?? ($seatTypesOrder[0] ?? null);
                                                    @endphp
                                                    @foreach ($room->allowedSeatTypes() as $seatType)
                                                        <option value="{{ $seatType->id }}"
                                                            @if ($seatType->id == $currentTypeId) selected @endif>
                                                            {{ $seatType->name }} (Còn lại:
                                                            {{ max(0, ($requiredSeats[$seatType->id] ?? 0) - ($existingSeatsByType[$seatType->id] ?? 0)) }}
                                                            ghế)
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="seat_type_id" value="{{ $currentTypeId }}">
                                                @error('seat_type_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="seats_per_row" class="form-label">Số ghế mỗi hàng</label>
                                                <input type="number" name="seats_per_row" id="seats_per_row"
                                                    class="form-control" min="1"
                                                    value="{{ $nextSuggestedSeatsPerRow ?? 1 }}" required>
                                                <p class="text-info mt-2" id="seatsPerRowSuggestion">
                                                    @if (isset($nextSuggestedSeatsPerRow) && $nextSuggestedSeatsPerRow)
                                                        <span>Đề xuất số ghế mỗi hàng cho loại tiếp theo:
                                                            <strong>{{ $nextSuggestedSeatsPerRow }}</strong></span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="min_seats_per_row" class="form-label">Số ghế tối thiểu mỗi
                                                    hàng</label>
                                                <input type="number" name="min_seats_per_row" id="min_seats_per_row"
                                                    class="form-control" min="1" value="1" required>
                                                <p class="text-info mt-2" id="minSeatsPerRowSuggestion"></p>
                                            </div>
                                            <div class="mb-3">
                                                <p><strong>Sức chứa phòng:</strong> {{ $room->capacity }}</p>
                                                <p><strong>Ghế hiện có:</strong> {{ $seats->count() }}</p>
                                                <p><strong>Ghế còn lại:</strong> {{ $room->capacity - $seats->count() }}
                                                </p>
                                                <p id="remainingSeatsWarning" class="text-warning"></p>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100"
                                                @if ($seats->count() >= $room->capacity) disabled @endif>Thêm ghế</button>
                                        </form>
                                        <hr>
                                        <form action="{{ route('admin.seats.import') }}" method="POST"
                                            enctype="multipart/form-data" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                                            <div class="mb-3">
                                                <label for="excel_file" class="form-label">Import ghế từ file Excel
                                                    (.xlsx, .xls) hoặc CSV (.csv)</label>
                                                <input type="file" name="excel_file" id="excel_file"
                                                    class="form-control" accept=".xlsx,.xls,.csv" required>
                                            </div>
                                            <button type="submit" class="btn btn-success w-100" id="importSeatsBtn"
                                                @if ($seats->count() >= $room->capacity) disabled @endif>Import ghế từ
                                                file</button>
                                            @if (session('import_success'))
                                                <div class="alert alert-success mt-2">{{ session('import_success') }}
                                                </div>
                                            @endif
                                            @if (session('import_error'))
                                                <div class="alert alert-danger mt-2">{{ session('import_error') }}</div>
                                            @endif
                                            @if ($errors->has('excel_file'))
                                                <div class="alert alert-danger mt-2">{{ $errors->first('excel_file') }}
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                                <!-- Xóa nút lớn hiện tại, thêm nút nhỏ bên dưới phân bổ ghế -->
                            </div>
                        </div>
                        <a href="{{ route('admin.rooms.index') }}" class="btn mb-2"
                            style="background: #f86c2c; color: #fff; font-weight: 500; border-radius: 12px; font-size: 0.95rem; height: 36px; min-width: 120px; display: inline-flex; align-items: center; justify-content: center;">Quay
                            lại danh sách phòng</a>

                        <!-- Modal xác nhận thêm ghế -->
                        <div class="modal fade" id="addSeatsConfirmModal" tabindex="-1"
                            aria-labelledby="addSeatsConfirmLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addSeatsConfirmLabel">Xác nhận thêm ghế</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="addSeatsPreview">
                                        <!-- Preview ghế sẽ được render bằng JS -->
                                        @if (isset($previewSeats) && count($previewSeats) > 0)
                                            <div class="mb-2">Sơ đồ ghế chuẩn bị thêm:</div>
                                            <div style="max-height:200px;overflow:auto;border:1px solid #eee;padding:8px;">
                                                @php
                                                    $grouped = collect($previewSeats)->groupBy('row_char');
                                                @endphp
                                                @foreach ($grouped as $rowChar => $seatsInRow)
                                                    <div><strong>Hàng {{ $rowChar }}:</strong>
                                                        @foreach ($seatsInRow as $seat)
                                                            <span
                                                                class="badge bg-primary mx-1">{{ $seat['row_char'] }}{{ $seat['seat_number'] }}</span>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="button" class="btn btn-primary" id="confirmAddSeatsBtn">Xác nhận
                                            thêm</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal xác nhận import ghế -->
                        <div class="modal fade" id="importSeatsConfirmModal" tabindex="-1"
                            aria-labelledby="importSeatsConfirmLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importSeatsConfirmLabel">Xác nhận import ghế</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn muốn import ghế từ file? Nếu phòng đã đầy ghế, thao tác sẽ bị từ
                                        chối.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="button" class="btn btn-success" id="confirmImportSeatsBtn">Xác
                                            nhận import</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal thông báo thành công -->
                        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="successModalLabel">Thành công</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="successModalBody">
                                        Thao tác đã thực hiện thành công!
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary"
                                            data-bs-dismiss="modal">Đóng</button>
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
                            background-color: #28a745;
                            /* Regular (Thường) */
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
                            document.addEventListener('DOMContentLoaded', function() {
                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
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
                                        percentageWarning.innerHTML =
                                            '<span class="text-danger">Tổng tỷ lệ phải bằng 100%. Hiện tại: ' + totalPercentage.toFixed(
                                                2) + '%</span>';
                                    } else {
                                        percentageWarning.innerHTML = '<span class="text-success">Tổng tỷ lệ hợp lệ: ' + totalPercentage
                                            .toFixed(2) + '%</span>';
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
                                        remainingSeatsWarning.innerHTML = '<span class="text-warning">Cảnh báo: Sẽ có ' + excessSeats +
                                            ' ghế dư do không chia hết số hàng.</span>';
                                    } else {
                                        remainingSeatsWarning.innerHTML = '';
                                    }

                                    // Chỉ hiển thị gợi ý, KHÔNG tự động thay đổi số ghế mỗi hàng
                                    if (seatsToAdd > 0) {
                                        let optimalSeatsPerRow = seatsPerRow; // Giữ nguyên giá trị người dùng nhập
                                        let found = false;
                                        for (let i = minSeatsPerRow; i <= maxSeatsPerRow; i++) {
                                            if (seatsToAdd % i === 0 && seatsToAdd / i <= maxRows) {
                                                optimalSeatsPerRow = i;
                                                found = true;
                                                break;
                                            }
                                        }
                                        if (found && optimalSeatsPerRow !== seatsPerRow) {
                                            seatsPerRowSuggestion.innerHTML = 'Đề xuất số ghế mỗi hàng: ' + optimalSeatsPerRow +
                                                ' (chia hết cho ' + seatsToAdd + ' ghế, tạo ' + Math.ceil(seatsToAdd /
                                                    optimalSeatsPerRow) + ' hàng)';
                                        } else {
                                            seatsPerRowSuggestion.innerHTML = 'Bạn đã chọn số ghế mỗi hàng: ' + seatsPerRow +
                                                ' (sẽ tạo ' + rows + ' hàng, có thể dư ghế nếu không chia hết)';
                                        }
                                    } else {
                                        seatsPerRowSuggestion.innerHTML = 'Không cần thêm ghế cho loại này.';
                                    }

                                    // Cảnh báo nếu < số ghế tối thiểu mỗi hàng
                                    if (seatsPerRow < minSeatsPerRow) {
                                        minSeatsPerRowSuggestion.innerHTML =
                                            '<span class="text-danger">Số ghế mỗi hàng phải ≥ số tối thiểu (' + minSeatsPerRow +
                                            ').</span>';
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
                                        seat.addEventListener('click', function() {
                                            window.location.href = '{{ route('admin.seats.edit', ':id') }}'.replace(
                                                ':id', this.dataset.seatId);
                                        });
                                    }
                                });

                                // Modal xác nhận import ghế
                                const importSeatsBtn = document.getElementById('importSeatsBtn');
                                if (importSeatsBtn) {
                                    importSeatsBtn.addEventListener('click', function(e) {
                                        if (importSeatsBtn.disabled) {
                                            e.preventDefault();
                                            alert('Phòng đã đầy ghế, không thể import thêm!');
                                            return false;
                                        }
                                        e.preventDefault();
                                        var modal = new bootstrap.Modal(document.getElementById('importSeatsConfirmModal'));
                                        modal.show();
                                        document.getElementById('confirmImportSeatsBtn').onclick = function() {
                                            modal.hide();
                                            importSeatsBtn.form.submit();
                                        };
                                        return false;
                                    });
                                }

                                // Modal xác nhận thêm ghế
                                seatForm.addEventListener('submit', function(e) {
                                    let totalPercentage = 0;
                                    percentageInputs.forEach(input => {
                                        totalPercentage += parseFloat(input.value) || 0;
                                    });
                                    if (Math.abs(totalPercentage - 100) > 0.01) {
                                        e.preventDefault();
                                        alert('Tổng tỷ lệ loại ghế phải bằng 100%.');
                                        return;
                                    }
                                    // Lấy lại dữ liệu mới nhất từ input khi mở modal xác nhận
                                    let seatTypeId = seatTypeSelect.value;
                                    let percentageInput = document.getElementById('seat_type_percentages_' + seatTypeId);
                                    let percentage = parseFloat(percentageInput.value) || 0;
                                    let requiredSeats = Math.round((percentage / 100) * roomCapacity);
                                    let currentTypeSeats = existingSeatsByType[seatTypeId] || 0;
                                    let seatsToAdd = Math.min(requiredSeats - currentTypeSeats, remainingCapacity);
                                    let seatsPerRow = parseInt(seatsPerRowInput.value) || 1;
                                    let minSeatsPerRow = parseInt(minSeatsPerRowInput.value) || 1;
                                    let rows = Math.ceil(seatsToAdd / seatsPerRow);
                                    // Chỉ chặn nếu số ghế mỗi hàng < tối thiểu hoặc seatsToAdd <= 0
                                    if (seatsPerRow < minSeatsPerRow) {
                                        e.preventDefault();
                                        alert('Số ghế mỗi hàng phải ≥ số tối thiểu (' + minSeatsPerRow + ').');
                                        return;
                                    }
                                    if (seatsToAdd <= 0) {
                                        e.preventDefault();
                                        alert('Không còn ghế cần thêm cho loại này.');
                                        return;
                                    }
                                    // Cho phép thêm với mọi số ghế/hàng hợp lệ, chỉ cảnh báo dư ghế
                                    e.preventDefault();
                                    let previewHtml = '<p>Số ghế sẽ thêm: <strong>' + seatsToAdd + '</strong></p>';
                                    previewHtml += '<p>Số ghế mỗi hàng: <strong>' + seatsPerRow + '</strong></p>';
                                    previewHtml += '<p>Số hàng: <strong>' + rows + '</strong></p>';
                                    let excessSeats = (rows * seatsPerRow) - seatsToAdd;
                                    if (excessSeats > 0) {
                                        previewHtml += '<p class="text-warning">Cảnh báo: Sẽ có ' + excessSeats +
                                            ' ghế dư do không chia hết số hàng.</p>';
                                    }
                                    previewHtml +=
                                        '<div style="max-height:200px;overflow:auto;border:1px solid #eee;padding:8px;margin-top:8px;">';
                                    for (let r = 1; r <= rows; r++) {
                                        previewHtml += '<div>Hàng ' + r + ': ';
                                        for (let s = 1; s <= seatsPerRow; s++) {
                                            let seatNum = (r - 1) * seatsPerRow + s;
                                            if (seatNum > seatsToAdd) break;
                                            previewHtml += '<span class="badge bg-primary mx-1">' + seatNum + '</span>';
                                        }
                                        previewHtml += '</div>';
                                    }
                                    previewHtml += '</div>';
                                    document.getElementById('addSeatsPreview').innerHTML = previewHtml;
                                    var modal = new bootstrap.Modal(document.getElementById('addSeatsConfirmModal'));
                                    modal.show();
                                    document.getElementById('confirmAddSeatsBtn').onclick = function() {
                                        modal.hide();
                                        seatForm.submit();
                                    };
                                });

                                // Hiển thị modal thành công nếu có session
                                @if (session('import_success'))
                                    setTimeout(function() {
                                        document.getElementById('successModalBody').innerHTML =
                                            '{{ session('import_success') }}';
                                        var modal = new bootstrap.Modal(document.getElementById('successModal'));
                                        modal.show();
                                    }, 300);
                                @endif
                                @if (session('add_success'))
                                    setTimeout(function() {
                                        document.getElementById('successModalBody').innerHTML = '{{ session('add_success') }}';
                                        var modal = new bootstrap.Modal(document.getElementById('successModal'));
                                        modal.show();
                                    }, 300);
                                @endif
                                // Khởi động tính toán ban đầu
                                updateCalculations();

                                // Khi modal thành công đóng lại, luôn gọi lại updateCalculations để cập nhật đề xuất cho loại ghế tiếp theo
                                var successModalEl = document.getElementById('successModal');
                                if (successModalEl) {
                                    successModalEl.addEventListener('hidden.bs.modal', function() {
                                        setTimeout(function() {
                                            try {
                                                updateCalculations();
                                            } catch (e) {
                                                console.error('Lỗi updateCalculations:', e);
                                            }
                                        }, 10);
                                    });
                                }
                            });
                        </script>
                    @endpush

                @endsection
