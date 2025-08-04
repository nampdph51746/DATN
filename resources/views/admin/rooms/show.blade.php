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
                    <p><strong>Rạp chiếu:</strong> {{ $room->cinema ? $room->cinema->name : 'Chưa có thông tin rạp' }}</p>
                    <p><strong>Loại phòng:</strong> {{ $room->roomType ? $room->roomType->name : 'Chưa có thông tin loại phòng' }}</p>
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
                                        <td class="text-center">{{ $seatPercentages[$seatType->id] ?? 0 }}</td>
                                        <td class="text-center">{{ $existingSeatsByType[$seatType->id] ?? 0 }}</td>
                                        <td class="text-center">{{ $requiredSeats[$seatType->id] ?? 0 }}</td>
                                        <td class="text-center">{{ max(0, ($requiredSeats[$seatType->id] ?? 0) - ($existingSeatsByType[$seatType->id] ?? 0)) }}</td>
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
            <!-- Thêm dropdown chọn suất chiếu -->
            <div class="mb-3 w-100">
                <form method="GET" action="{{ route('admin.rooms.show', $room->id) }}">
                    <!-- <label for="showtime_id" class="form-label">Chọn suất chiếu</label> -->
                    <select name="showtime_id" id="showtime_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Chọn suất chiếu --</option>
                        @foreach ($showtimes as $showtime)
                            <option value="{{ $showtime->id }}" {{ $showtime_id == $showtime->id ? 'selected' : '' }}>
                                {{ htmlspecialchars($showtime->movie->name, ENT_QUOTES, 'UTF-8') }} - {{ $showtime->start_time->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if ($seats->count() > 0)
                <form id="bulkEditForm" action="{{ route('admin.seats.edit-bulk') }}" method="POST">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <div class="seat-map mb-3" style="display: grid; gap: 5px; grid-template-columns: repeat({{ $maxSeatsPerRow + 1 }}, 40px); max-width: {{ ($maxSeatsPerRow + 1) * 45 }}px;">
                        <div class="seat"></div>
                        @for ($i = 1; $i <= $maxSeatsPerRow; $i++)
                            <div class="seat seat-number-label">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                        @endfor
                        @foreach ($rows as $row)
                            <div class="seat seat-row-label">{{ $row }}</div>
                            @for ($i = 1; $i <= $maxSeatsPerRow; $i++)
                            @php
                                $seatNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
                                $seat = $seats->where('row_char', $row)->where('seat_number', $seatNumber)->first();
                                if (!$seat) continue;
                                $displayStatus = $showtime_id ? ($seat->showtimeSeatStates()->where('showtime_id', $showtime_id)->first()?->status?->value ?? $seat->status->value) : $seat->status->value;
                                $seatTypeName = $seat->seatType->name ?? 'Chưa có loại';
                                $backgroundColor = $seat->seatType->color_code ?? '#28a745';
                                $textColor = (isset($seat->seatType->color_code) && strtolower($seat->seatType->color_code) == '#ffd700') ? 'black' : 'white';
                                $isBooked = $displayStatus === 'booked';
                            @endphp
                            <div class="seat
                                @if ($displayStatus == 'booked') seat-booked
                                @elseif ($displayStatus == 'reserved') seat-reserved
                                @else
                                @endif"
                                @if ($displayStatus == 'available')
                                    style="background-color: {{ $backgroundColor }}; color: {{ $textColor }}"
                                @endif
                                data-seat-id="{{ $seat->id }}"
                                title="{{ $row }}{{ $seatNumber }} ({{ htmlspecialchars($seatTypeName, ENT_QUOTES, 'UTF-8') }}, {{ ucfirst($displayStatus) }})"
                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true"
                                data-bs-title="Ghế: {{ $row }}{{ $seatNumber }}<br>Loại: {{ htmlspecialchars($seatTypeName, ENT_QUOTES, 'UTF-8') }}<br>Trạng thái: {{ ucfirst($displayStatus) }}"
                                style="cursor: {{ $isBooked ? 'not-allowed' : 'pointer' }}; position: relative;"
                                @if ($isBooked) onclick="event.preventDefault(); return false;" @endif>
                                <input type="checkbox" name="seat_ids[]" value="{{ $seat->id }}" class="seat-checkbox" style="position: absolute; top: 5px; left: 5px; z-index: 10;" @if ($isBooked) disabled @endif>
                                {{ $row }}{{ $seatNumber }}
                            </div>
                            @endfor
                        @endforeach
                    </div>
                    <p class="mt-2">Số ghế hiện có: {{ $seats->count() }} / {{ $room->capacity }} (Còn lại: {{ $room->capacity - $seats->count() }})</p>
                    <button type="submit" class="btn btn-warning mt-2" id="bulkEditBtn" disabled>Chỉnh sửa hàng loạt</button>
                </form>
                <script>
                    console.log('Seat script loaded');
                    try {
                        document.addEventListener('DOMContentLoaded', function () {
                            console.log('DOM fully loaded - Seat script');
                            const seats = document.querySelectorAll('.seat');
                            const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
                            const bulkEditBtn = document.getElementById('bulkEditBtn');
                            console.log('Found seats:', seats.length, 'Found checkboxes:', seatCheckboxes.length);

                            // Cập nhật trạng thái nút "Chỉnh sửa hàng loạt"
                            function updateBulkEditButton() {
                                const checkedCount = document.querySelectorAll('.seat-checkbox:checked').length;
                                bulkEditBtn.disabled = checkedCount === 0;
                                console.log('Checked seats:', checkedCount, 'Bulk edit button disabled:', bulkEditBtn.disabled);
                            }

                            // Gắn sự kiện change cho checkbox
                            seatCheckboxes.forEach(checkbox => {
                                checkbox.addEventListener('change', function () {
                                    console.log('Checkbox changed, ID:', this.value, 'Checked:', this.checked);
                                    updateBulkEditButton();
                                });
                            });

                            // Gắn sự kiện click cho ghế (chỉ redirect nếu không click vào checkbox)
                            seats.forEach(seat => {
                                if (seat.dataset.seatId) {
                                    console.log('Attaching click event to seat with ID:', seat.dataset.seatId);
                                    seat.addEventListener('click', function (e) {
                                        const checkbox = this.querySelector('.seat-checkbox');
                                        const isBooked = this.classList.contains('seat-booked');
                                        if (e.target !== checkbox && !checkbox.checked && !isBooked) {
                                            console.log('Seat clicked (not checkbox), ID:', this.dataset.seatId);
                                            console.log('Redirecting to:', '/admin/seats/' + this.dataset.seatId + '/edit');
                                            window.location.href = '/admin/seats/' + this.dataset.seatId + '/edit';
                                        } else if (e.target === checkbox) {
                                            console.log('Checkbox clicked directly, ID:', checkbox.value);
                                        }
                                    });
                                } else {
                                    console.log('No seatId found for seat:', seat.outerHTML);
                                }
                            });

                            // Gắn sự kiện submit cho form
                            const bulkEditForm = document.getElementById('bulkEditForm');
                            if (bulkEditForm) {
                                bulkEditForm.addEventListener('submit', function (e) {
                                    const checkedCount = document.querySelectorAll('.seat-checkbox:checked').length;
                                    if (checkedCount === 0) {
                                        e.preventDefault();
                                        console.log('No seats selected for bulk edit');
                                        alert('Vui lòng chọn ít nhất một ghế để chỉnh sửa.');
                                    }
                                });
                            } else {
                                console.warn('bulkEditForm not found in DOM');
                            }

                            document.addEventListener('click', function (e) {
                                console.log('Document click - Seat script, target:', e.target.outerHTML);
                            });

                            // Khởi tạo trạng thái nút
                            updateBulkEditButton();
                        });
                    } catch (error) {
                        console.error('Error in seat script:', error);
                    }
                </script>
            @else
                <p class="text-success">Phòng chưa có ghế nào. Số ghế có thể thêm: {{ $room->capacity }}</p>
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
                                    <label for="seat_type_percentages_{{ $seatType->id }}" class="form-label mb-0" style="min-width:110px;">{{ $seatType->name }}</label>
                                    <input type="number" name="seat_type_percentages[{{ $seatType->id }}]" id="seat_type_percentages_{{ $seatType->id }}" 
                                           class="form-control" min="0" max="100" 
                                           value="{{ $seatPercentages[$seatType->id] ?? 0 }}" step="0.01" required style="width: 160px; text-align: center;">
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
                            <select name="seat_type_id" id="seat_type_id" class="form-control" required disabled>
                                @php
                                    $currentTypeId = $next_seat_type_id ?? ($seatTypesOrder[0] ?? null);
                                @endphp
                                @foreach ($room->allowedSeatTypes() as $seatType)
                                    <option value="{{ $seatType->id }}" @if($seatType->id == $currentTypeId) selected @endif>
                                        {{ $seatType->name }} (Còn lại: {{ max(0, ($requiredSeats[$seatType->id] ?? 0) - ($existingSeatsByType[$seatType->id] ?? 0)) }} ghế)
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
                            <input type="number" name="seats_per_row" id="seats_per_row" class="form-control" min="1" value="{{ $nextSuggestedSeatsPerRow ?? 1 }}" required>
                            <p class="text-info mt-2" id="seatsPerRowSuggestion">
                                @if(isset($nextSuggestedSeatsPerRow) && $nextSuggestedSeatsPerRow)
                                    <span>Đề xuất số ghế mỗi hàng cho loại tiếp theo: <strong>{{ $nextSuggestedSeatsPerRow }}</strong></span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label for="min_seats_per_row" class="form-label">Số ghế tối thiểu mỗi hàng</label>
                            <input type="number" name="min_seats_per_row" id="min_seats_per_row" class="form-control" min="1" value="1" required>
                            <p class="text-info mt-2" id="minSeatsPerRowSuggestion"></p>
                        </div>
                        <div class="mb-3">
                            <p><strong>Sức chứa phòng:</strong> {{ $room->capacity }}</p>
                            <p><strong>Ghế hiện có:</strong> {{ $seats->count() }}</p>
                            <p><strong>Ghế còn lại:</strong> {{ $room->capacity - $seats->count() }}</p>
                            <p id="remainingSeatsWarning" class="text-warning"></p>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" @if($seats->count() >= $room->capacity) disabled @endif>Thêm ghế</button>
                    </form>
                    <hr>
                    <form action="{{ route('admin.seats.import') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Import ghế từ file Excel (.xlsx, .xls) hoặc CSV (.csv)</label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="importSeatsBtn" @if($seats->count() >= $room->capacity) disabled @endif>Import ghế từ file</button>
                        @if(session('import_success'))
                            <div class="alert alert-success mt-2">{{ session('import_success') }}</div>
                        @endif
                        @if(session('import_error'))
                            <div class="alert alert-danger mt-2">{{ session('import_error') }}</div>
                        @endif
                        @if($errors->has('excel_file'))
                            <div class="alert alert-danger mt-2">{{ $errors->first('excel_file') }}</div>
                        @endif
                    </form>
                </div>
            </div>
            <!-- Xóa nút lớn hiện tại, thêm nút nhỏ bên dưới phân bổ ghế -->
                </div>
            </div>
            <a href="{{ route('admin.rooms.index') }}" class="btn mb-2" style="background: #f86c2c; color: #fff; font-weight: 500; border-radius: 12px; font-size: 0.95rem; height: 36px; min-width: 120px; display: inline-flex; align-items: center; justify-content: center;">Quay lại danh sách phòng</a>

<!-- Modal xác nhận thêm ghế -->
<div class="modal fade" id="addSeatsConfirmModal" tabindex="-1" aria-labelledby="addSeatsConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSeatsConfirmLabel">Xác nhận thêm ghế</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="addSeatsPreview">
        <!-- Preview ghế sẽ được render bằng JS -->
        @if(isset($previewSeats) && count($previewSeats) > 0)
            <div class="mb-2">Sơ đồ ghế chuẩn bị thêm:</div>
            <div style="max-height:200px;overflow:auto;border:1px solid #eee;padding:8px;">
                @php
                    $grouped = collect($previewSeats)->groupBy('row_char');
                @endphp
                @foreach($grouped as $rowChar => $seatsInRow)
                    <div><strong>Hàng {{ $rowChar }}:</strong>
                        @foreach($seatsInRow as $seat)
                            <span class="badge bg-primary mx-1">{{ $seat['row_char'] }}{{ $seat['seat_number'] }}</span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" id="confirmAddSeatsBtn">Xác nhận thêm</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal xác nhận import ghế -->
<div class="modal fade" id="importSeatsConfirmModal" tabindex="-1" aria-labelledby="importSeatsConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importSeatsConfirmLabel">Xác nhận import ghế</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc chắn muốn import ghế từ file? Nếu phòng đã đầy ghế, thao tác sẽ bị từ chối.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-success" id="confirmImportSeatsBtn">Xác nhận import</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal thông báo thành công -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Thành công</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="successModalBody">
        Thao tác đã thực hiện thành công!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
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
        position: relative; /* Đảm bảo là container cho checkbox */
        pointer-events: auto; /* Cho phép xử lý sự kiện */
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
        background-color: #c82333; /* Updated color for booked seats */
        color: white;
    }
    .seat-reserved {
        background-color: #ffc107;
        color: black;
    }
    .seat[data-seat-id] {
        pointer-events: auto !important;
    }
    .seat-checkbox {
        cursor: pointer;
        position: absolute;
        top: 5px;
        left: 5px;
        z-index: 10;
        width: 20px;
        height: 20px;
    }
    .seat-checkbox:checked ~ * {
        /* Thay đổi style của .seat khi checkbox được checked */
        background-color: #007bff; /* Màu xanh khi chọn */
        color: white;
    }
    .seat-checkbox:focus + * {
        /* Highlight nhẹ khi focus vào checkbox */
        outline: 2px solid #007bff;
        outline-offset: 2px;
    }
</style>

@push('scripts')
<script>
    console.log('Form script loaded');
    try {
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM fully loaded - Form script');

            // Kiểm tra các phần tử DOM
            const seatForm = document.getElementById('seatForm');
            if (!seatForm) {
                console.warn('seatForm not found in DOM');
                return;
            }
            const percentageInputs = document.querySelectorAll('input[name^="seat_type_percentages"]');
            if (percentageInputs.length === 0) {
                console.warn('No percentageInputs found in DOM');
                return;
            }
            const seatsPerRowInput = document.getElementById('seats_per_row');
            if (!seatsPerRowInput) {
                console.warn('seatsPerRowInput not found in DOM');
                return;
            }
            const minSeatsPerRowInput = document.getElementById('min_seats_per_row');
            if (!minSeatsPerRowInput) {
                console.warn('minSeatsPerRowInput not found in DOM');
                return;
            }
            const percentageWarning = document.getElementById('percentageWarning');
            if (!percentageWarning) {
                console.warn('percentageWarning not found in DOM');
                return;
            }
            const seatsPerRowSuggestion = document.getElementById('seatsPerRowSuggestion');
            if (!seatsPerRowSuggestion) {
                console.warn('seatsPerRowSuggestion not found in DOM');
                return;
            }
            const minSeatsPerRowSuggestion = document.getElementById('minSeatsPerRowSuggestion');
            if (!minSeatsPerRowSuggestion) {
                console.warn('minSeatsPerRowSuggestion not found in DOM');
                return;
            }
            const remainingSeatsWarning = document.getElementById('remainingSeatsWarning');
            if (!remainingSeatsWarning) {
                console.warn('remainingSeatsWarning not found in DOM');
                return;
            }
            const seatTypeHidden = document.querySelector('input[name="seat_type_id"]');
            if (!seatTypeHidden) {
                console.warn('seatTypeHidden (input[name="seat_type_id"]) not found in DOM');
                return;
            }

            const roomCapacity = {{ json_encode($room->capacity, JSON_HEX_AMP) }};
            const existingSeats = {{ json_encode($seats->count(), JSON_HEX_AMP) }};
            const remainingCapacity = roomCapacity - existingSeats;
            const existingSeatsByType = {{ json_encode($existingSeatsByType, JSON_HEX_AMP) }};
            const maxSeatsPerRow = {{ json_encode($maxSeatsPerRow, JSON_HEX_AMP) }};
            const maxRows = {{ json_encode($maxRows, JSON_HEX_AMP) }};

            function updateCalculations() {
                console.log('Running updateCalculations');
                let totalPercentage = 0;
                percentageInputs.forEach(input => {
                    totalPercentage += parseFloat(input.value) || 0;
                });

                if (Math.abs(totalPercentage - 100) > 0.01) {
                    percentageWarning.innerHTML = '<span class="text-danger">Tổng tỷ lệ phải bằng 100%. Hiện tại: ' + totalPercentage.toFixed(2) + '%</span>';
                } else {
                    percentageWarning.innerHTML = '<span class="text-success">Tổng tỷ lệ hợp lệ: ' + totalPercentage.toFixed(2) + '%</span>';
                }

                const seatTypeId = seatTypeHidden.value;
                console.log('seatTypeId from hidden input:', seatTypeId);
                const percentageInput = document.getElementById('seat_type_percentages_' + seatTypeId);
                if (!percentageInput) {
                    console.warn('percentageInput not found for seatTypeId:', seatTypeId);
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

                if (excessSeats > 0) {
                    remainingSeatsWarning.innerHTML = '<span class="text-warning">Cảnh báo: Sẽ có ' + excessSeats + ' ghế dư do không chia hết số hàng.</span>';
                } else {
                    remainingSeatsWarning.innerHTML = '';
                }

                if (seatsToAdd > 0) {
                    let optimalSeatsPerRow = seatsPerRow;
                    let found = false;
                    for (let i = minSeatsPerRow; i <= maxSeatsPerRow; i++) {
                        if (seatsToAdd % i === 0 && seatsToAdd / i <= maxRows) {
                            optimalSeatsPerRow = i;
                            found = true;
                            break;
                        }
                    }
                    if (found && optimalSeatsPerRow !== seatsPerRow) {
                        seatsPerRowSuggestion.innerHTML = 'Đề xuất số ghế mỗi hàng: ' + optimalSeatsPerRow + ' (chia hết cho ' + seatsToAdd + ' ghế, tạo ' + Math.ceil(seatsToAdd / optimalSeatsPerRow) + ' hàng)';
                    } else {
                        seatsPerRowSuggestion.innerHTML = 'Bạn đã chọn số ghế mỗi hàng: ' + seatsPerRow + ' (sẽ tạo ' + rows + ' hàng, có thể dư ghế nếu không chia hết)';
                    }
                } else {
                    seatsPerRowSuggestion.innerHTML = 'Không cần thêm ghế cho loại này.';
                }

                if (seatsPerRow < minSeatsPerRow) {
                    minSeatsPerRowSuggestion.innerHTML = '<span class="text-danger">Số ghế mỗi hàng phải ≥ số tối thiểu (' + minSeatsPerRow + ').</span>';
                } else {
                    minSeatsPerRowSuggestion.innerHTML = '';
                }
            }

            // Gắn sự kiện cho inputs
            percentageInputs.forEach(input => {
                input.addEventListener('input', () => {
                    console.log('Percentage input changed:', input.id, input.value);
                    updateCalculations();
                });
            });
            seatsPerRowInput.addEventListener('input', () => {
                console.log('seatsPerRowInput changed:', seatsPerRowInput.value);
                updateCalculations();
            });
            minSeatsPerRowInput.addEventListener('input', () => {
                console.log('minSeatsPerRowInput changed:', minSeatsPerRowInput.value);
                updateCalculations();
            });

            const importSeatsBtn = document.getElementById('importSeatsBtn');
            if (importSeatsBtn) {
                importSeatsBtn.addEventListener('click', function(e) {
                    if (importSeatsBtn.disabled) {
                        e.preventDefault();
                        console.log('Import button disabled, showing alert');
                        alert('Phòng đã đầy ghế, không thể import thêm!');
                        return false;
                    }
                    e.preventDefault();
                    console.log('Opening importSeatsConfirmModal');
                    var modal = new bootstrap.Modal(document.getElementById('importSeatsConfirmModal'));
                    modal.show();
                    document.getElementById('confirmImportSeatsBtn').onclick = function() {
                        console.log('Confirm import clicked');
                        modal.hide();
                        importSeatsBtn.form.submit();
                    };
                    return false;
                });
            } else {
                console.warn('importSeatsBtn not found in DOM');
            }

            seatForm.addEventListener('submit', function(e) {
                console.log('seatForm submitted');
                let totalPercentage = 0;
                percentageInputs.forEach(input => {
                    totalPercentage += parseFloat(input.value) || 0;
                });
                if (Math.abs(totalPercentage - 100) > 0.01) {
                    e.preventDefault();
                    console.log('Invalid total percentage:', totalPercentage);
                    alert('Tổng tỷ lệ loại ghế phải bằng 100%.');
                    return;
                }
                let seatTypeId = seatTypeHidden.value;
                let percentageInput = document.getElementById('seat_type_percentages_' + seatTypeId);
                let percentage = parseFloat(percentageInput.value) || 0;
                let requiredSeats = Math.round((percentage / 100) * roomCapacity);
                let currentTypeSeats = existingSeatsByType[seatTypeId] || 0;
                let seatsToAdd = Math.min(requiredSeats - currentTypeSeats, remainingCapacity);
                let seatsPerRow = parseInt(seatsPerRowInput.value) || 1;
                let minSeatsPerRow = parseInt(minSeatsPerRowInput.value) || 1;
                let rows = Math.ceil(seatsToAdd / seatsPerRow);
                if (seatsPerRow < minSeatsPerRow) {
                    e.preventDefault();
                    console.log('seatsPerRow < minSeatsPerRow:', seatsPerRow, minSeatsPerRow);
                    alert('Số ghế mỗi hàng phải ≥ số tối thiểu (' + minSeatsPerRow + ').');
                    return;
                }
                if (seatsToAdd <= 0) {
                    e.preventDefault();
                    console.log('No seats to add:', seatsToAdd);
                    alert('Không còn ghế cần thêm cho loại này.');
                    return;
                }
                e.preventDefault();
                let previewHtml = '<p>Số ghế sẽ thêm: <strong>' + seatsToAdd + '</strong></p>';
                previewHtml += '<p>Số ghế mỗi hàng: <strong>' + seatsPerRow + '</strong></p>';
                previewHtml += '<p>Số hàng: <strong>' + rows + '</strong></p>';
                let excessSeats = (rows * seatsPerRow) - seatsToAdd;
                if (excessSeats > 0) {
                    previewHtml += '<p class="text-warning">Cảnh báo: Sẽ có ' + excessSeats + ' ghế dư do không chia hết số hàng.</p>';
                }
                previewHtml += '<div style="max-height:200px;overflow:auto;border:1px solid #eee;padding:8px;margin-top:8px;">';
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
                console.log('Opening addSeatsConfirmModal');
                var modal = new bootstrap.Modal(document.getElementById('addSeatsConfirmModal'));
                modal.show();
                document.getElementById('confirmAddSeatsBtn').onclick = function() {
                    console.log('Confirm add seats clicked');
                    modal.hide();
                    seatForm.submit();
                };
            });

            @if(session('import_success'))
            setTimeout(function() {
                console.log('Showing import_success modal');
                document.getElementById('successModalBody').innerHTML = '{{ addslashes(session('import_success')) }}';
                var modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();
            }, 300);
            @endif
            @if(session('add_success'))
            setTimeout(function() {
                console.log('Showing add_success modal');
                document.getElementById('successModalBody').innerHTML = '{{ addslashes(session('add_success')) }}';
                var modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();
            }, 300);
            @endif

            var successModalEl = document.getElementById('successModal');
            if (successModalEl) {
                successModalEl.addEventListener('hidden.bs.modal', function () {
                    console.log('successModal hidden, running updateCalculations');
                    setTimeout(function() {
                        try {
                            updateCalculations();
                        } catch (e) {
                            console.error('Lỗi updateCalculations:', e);
                        }
                    }, 10);
                });
            } else {
                console.warn('successModal not found in DOM');
            }

            console.log('Calling initial updateCalculations');
            updateCalculations();
        });
    } catch (error) {
        console.error('Error in form script:', error);
    }
</script>
@endpush

@endsection