@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
    <div class="row g-4">
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header d-flex align-items-center gap-2 p-3" style="background: linear-gradient(135deg, #F97316, #FACC15); color: white;">
                    <iconify-icon icon="solar:buildings-bold" class="fs-20 me-2"></iconify-icon>
                    <h5 class="mb-0">Thông tin phòng chiếu</h5>
                </div>
                <div class="card-body">
                    <p><strong><iconify-icon icon="solar:tag-bold" class="me-2 text-teal"></iconify-icon>Tên phòng:</strong> {{ $room->name }}</p>
                    <p><strong><iconify-icon icon="solar:cinema-bold" class="me-2 text-teal"></iconify-icon>Rạp chiếu:</strong> {{ $room->cinema ? $room->cinema->name : 'Chưa có thông tin rạp' }}</p>
                    <p><strong><iconify-icon icon="solar:box-bold" class="me-2 text-teal"></iconify-icon>Loại phòng:</strong> {{ $room->roomType ? $room->roomType->name : 'Chưa có thông tin loại phòng' }}</p>
                    <p><strong><iconify-icon icon="solar:users-bold" class="me-2 text-teal"></iconify-icon>Sức chứa:</strong> 
                        <span id="capacity-display">{{ $room->capacity }}</span>
                        @if($seats->count() == 0)
                            <button class="btn btn-sm btn-outline-teal ms-2" onclick="editCapacity()" style="border-color: #0D9488; color: #0D9488;">
                                <iconify-icon icon="solar:pen-2-bold" class="me-1"></iconify-icon> Sửa
                            </button>
                        @else
                            <span class="ms-2 text-muted small">(Không thể sửa - đã có {{ $seats->count() }} ghế)</span>
                        @endif
                    </p>
                    
                    @if($seats->count() == 0)
                    <form id="capacity-form" style="display: none;" onsubmit="updateCapacity(event)">
                        @csrf
                        @method('PUT')
                        <div class="input-group">
                            <input type="number" id="capacity-input" class="form-control border-teal" value="{{ $room->capacity }}" 
                                   min="1" max="1000" onchange="validateCapacityChange(this.value)" style="border-color: #0D9488;">
                            <button type="submit" class="btn btn-teal text-white" style="background-color: #0D9488; border: none;">Lưu</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="cancelEditCapacity()">Hủy</button>
                        </div>
                    </form>
                    
                    <!-- Hiển thị đề xuất sức chứa -->
                    <div id="capacityHelp" class="mt-2" style="display: none;">
                        <div id="capacityHelpText" class="alert alert-info py-2 mb-0"></div>
                    </div>
                    
                    <!-- Đề xuất sức chứa phổ biến -->
                    <div id="capacity-suggestions" class="mt-2" style="display: none;">
                        <small class="text-muted">💡 Đề xuất sức chứa phổ biến:</small>
                        <div class="mt-1 d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setCapacity(50)">50</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setCapacity(80)">80</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setCapacity(100)">100</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setCapacity(120)">120</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setCapacity(150)">150</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setCapacity(200)">200</button>
                        </div>
                    </div>
                    @endif
                    <p><strong><iconify-icon icon="solar:check-circle-bold" class="me-2 text-teal"></iconify-icon>Trạng thái:</strong>
                        @switch($room->status)
                            @case('active')
                                <span class="badge bg-teal text-white" style="background-color: #0D9488;">Hoạt động</span>
                                @break
                            @case('maintenance')
                                <span class="badge bg-yellow text-dark" style="background-color: #FACC15;">Bảo trì</span>
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
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header d-flex align-items-center gap-2 p-3" style="background: linear-gradient(135deg, #F97316, #FACC15); color: white;">
                    <iconify-icon icon="solar:chart-bold" class="fs-20 me-2"></iconify-icon>
                    <h6 class="mb-0">Phân bổ loại ghế</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead style="background-color: #0D9488; color: white;">
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
                                        <td>{{ $seatType->name }}</td>
                                        <td>{{ $seatPercentages[$seatType->id] ?? 0 }}</td>
                                        <td>{{ $existingSeatsByType[$seatType->id] ?? 0 }}</td>
                                        <td>{{ $requiredSeats[$seatType->id] ?? 0 }}</td>
                                        <td>{{ max(0, ($requiredSeats[$seatType->id] ?? 0) - ($existingSeatsByType[$seatType->id] ?? 0)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-12 d-flex flex-column align-items-center">
            <!-- Dropdown chọn suất chiếu -->
            <div class="mb-3 w-100">
                <form method="GET" action="{{ route('admin.rooms.show', $room->id) }}">
                    <select name="showtime_id" id="showtime_id" class="form-select border-teal" onchange="this.form.submit()" style="border-color: #0D9488; color: #0D9488;">
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
                <form id="bulkEditForm" action="{{ route('admin.seats.edit-bulk') }}" method="POST" class="w-100">
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
                            @endphp
                            
                            @if ($seat)
                                @php
                                    $displayStatus = $showtime_id ? ($seat->showtimeSeatStates()->where('showtime_id', $showtime_id)->first()?->status?->value ?? $seat->status->value) : $seat->status->value;
                                    $seatTypeName = $seat->seatType->name ?? 'Chưa có loại';
                                    $backgroundColor = $seat->seatType->color_code ?? '#0D9488';
                                    $textColor = (isset($seat->seatType->color_code) && strtolower($seat->seatType->color_code) == '#facc15') ? 'black' : 'white';
                                    $isBooked = $displayStatus === 'booked';
                                    
                                    // Determine CSS classes
                                    $cssClasses = 'seat';
                                    if ($displayStatus == 'booked') {
                                        $cssClasses .= ' seat-booked';
                                    } elseif ($displayStatus == 'reserved') {
                                        $cssClasses .= ' seat-reserved';
                                    }
                                    
                                    // Determine inline styles
                                    $inlineStyles = 'cursor: ' . ($isBooked ? 'not-allowed' : 'pointer') . '; position: relative;';
                                    if ($displayStatus == 'available') {
                                        $inlineStyles .= ' background-color: ' . $backgroundColor . '; color: ' . $textColor . ';';
                                    }
                                @endphp
                                <div class="{{ $cssClasses }}"
                                    style="{{ $inlineStyles }}"
                                    data-seat-id="{{ $seat->id }}"
                                    data-row-char="{{ $row }}"
                                    data-seat-number="{{ $i }}"
                                    title="{{ $row }}{{ $seatNumber }} ({{ htmlspecialchars($seatTypeName, ENT_QUOTES, 'UTF-8') }}, {{ ucfirst($displayStatus) }})"
                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true"
                                    data-bs-title="Ghế: {{ $row }}{{ $seatNumber }}<br>Loại: {{ htmlspecialchars($seatTypeName, ENT_QUOTES, 'UTF-8') }}<br>Trạng thái: {{ ucfirst($displayStatus) }}"
                                    @if ($isBooked) onclick="event.preventDefault(); return false;" @endif>
                                    <input type="checkbox" name="seat_ids[]" value="{{ $seat->id }}" class="seat-checkbox" style="position: absolute; top: 5px; left: 5px; z-index: 10;" @if ($isBooked) disabled @endif>
                                    {{ $row }}{{ $seatNumber }}
                                </div>
                            @else
                                <div class="seat seat-empty"></div>
                            @endif
                            @endfor
                        @endforeach
                    </div>
                    <p class="mt-2">Số ghế hiện có: {{ $seats->count() }} / {{ $room->capacity }} (Còn lại: {{ $room->capacity - $seats->count() }})</p>
                    
                    @if($hasActiveShowtimes)
                        <div class="alert alert-warning mt-2">
                            <iconify-icon icon="solar:warning-bold" class="me-2"></iconify-icon>
                            <strong>Không thể xóa ghế:</strong> Phòng này đang có suất chiếu hoạt động.
                        </div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-yellow text-dark" id="bulkEditBtn" disabled style="background-color: #FACC15;">
                            <iconify-icon icon="solar:pen-2-bold" class="me-1"></iconify-icon> Chỉnh sửa hàng loạt
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="bulkDeleteBtn" disabled onclick="confirmBulkDelete()" 
                                @if($hasActiveShowtimes) title="Không thể xóa ghế khi phòng có suất chiếu đang hoạt động" @endif style="border-color: #F97316; color: #F97316;">
                            <iconify-icon icon="solar:trash-bin-trash-bold" class="me-1"></iconify-icon> Xóa hàng loạt
                            @if($hasActiveShowtimes)
                                <small class="d-block">Phòng có suất chiếu</small>
                            @endif
                        </button>
                    </div>
                </form>

                <!-- Form xóa hàng loạt (ẩn) -->
                <form id="bulkDeleteForm" action="{{ route('admin.seats.deleteBulk') }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <div id="delete-seat-ids"></div>
                </form>

                <!-- Debug: Hiển thị URL route -->
                <script>
                    console.log('Route URL for bulk delete:', '{{ route("admin.seats.deleteBulk") }}');
                    console.log('Current page URL:', window.location.href);
                </script>

                @php
                    // Xử lý dữ liệu ghế đôi trước khi truyền vào JavaScript
                    $coupleSeatsIds = $seats->filter(function($seat) {
                        return $seat->seatType && (str_contains(strtolower($seat->seatType->name), 'couple') || 
                               str_contains(strtolower($seat->seatType->name), 'sweetbox') || 
                               str_contains(strtolower($seat->seatType->name), 'bed') ||
                               str_contains(strtolower($seat->seatType->name), 'sofa'));
                    })->pluck('id')->toArray();
                @endphp

                <script>
                    console.log('Seat script loaded');
                    
                    // ... (giữ nguyên script hiện có, chỉ thêm transitions nếu cần)
                </script>

            @else
                <p class="text-success">Phòng chưa có ghế nào. Số ghế có thể thêm: {{ $room->capacity }}</p>
            @endif
        </div>
        <div class="col-lg-3 col-md-12">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header d-flex align-items-center gap-2 p-3" style="background: linear-gradient(135deg, #F97316, #FACC15); color: white;">
                    <iconify-icon icon="solar:chair-bold" class="fs-20 me-2"></iconify-icon>
                    <h6 class="mb-0">Thêm ghế cho phòng: {{ $room->name }}</h6>
                </div>
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs mb-3" id="seatTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true">
                                <iconify-icon icon="solar:pen-2-bold" class="me-1"></iconify-icon> Thủ công
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="import-tab" data-bs-toggle="tab" data-bs-target="#import" type="button" role="tab" aria-controls="import" aria-selected="false">
                                <iconify-icon icon="solar:document-add-bold" class="me-1"></iconify-icon> Import Excel
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="single-tab" data-bs-toggle="tab" data-bs-target="#single" type="button" role="tab" aria-controls="single" aria-selected="false">
                                <iconify-icon icon="solar:chair-bold" class="me-1"></iconify-icon> Ghế đơn
                            </button>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" id="seatTabsContent">
                        <!-- Tab tạo ghế thủ công -->
                        <div class="tab-pane fade show active" id="manual" role="tabpanel">
                            <!-- Bước 1: Thiết lập số hàng -->
                            <div class="mb-3">
                                <label for="total_rows" class="form-label">Số hàng muốn tạo</label>
                                <input type="number" id="total_rows" class="form-control border-teal" min="1" max="26" value="5" style="border-color: #0D9488;">
                                <small class="text-muted">Tối đa 26 hàng (A-Z)</small>
                            </div>

                            <!-- Bước 2: Thiết lập số ghế mỗi hàng -->
                            <div class="mb-3">
                                <label for="seats_per_row" class="form-label">Số ghế mỗi hàng</label>
                                <input type="number" id="seats_per_row" class="form-control border-teal" min="1" max="50" value="10" style="border-color: #0D9488;">
                                <small class="text-muted">Tối đa 50 ghế mỗi hàng</small>
                            </div>

                            <!-- Bước 3: Chọn loại ghế cho từng hàng -->
                            <div class="mb-3">
                                <label class="form-label">Phân loại ghế theo hàng</label>
                                <div id="row-seat-types" class="border rounded p-3" style="max-height: 300px; overflow-y: auto; border-color: #0D9488;">
                                    <!-- Sẽ được tạo động bằng JavaScript -->
                                </div>
                            </div>

                            <!-- Bước 4: Preview và tạo -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-teal" onclick="createSeats()" style="border-color: #0D9488; color: #0D9488;">
                                    <iconify-icon icon="solar:eye-bold" class="me-1"></iconify-icon> Xem trước
                                </button>
                                <button type="button" class="btn btn-teal text-white" onclick="createSeats()" id="create-seats-btn" disabled style="background-color: #0D9488;">
                                    <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon> Tạo ghế
                                </button>
                            </div>

                            <!-- Thông tin tóm tắt -->
                            <div class="mt-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Sức chứa phòng:</span>
                                    <span id="room-capacity">{{ $room->capacity }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ghế hiện có:</span>
                                    <span>{{ $seats->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Sẽ thêm:</span>
                                    <span id="seats-to-add">0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Tổng sau khi thêm:</span>
                                    <span id="total-after-add">{{ $seats->count() }}</span>
                                </div>
                                
                                <!-- Cảnh báo và đề xuất -->
                                <div id="capacity-warning" class="mt-2" style="display: none;">
                                    <!-- Nội dung cảnh báo -->
                                </div>
                                <div id="suggestions" class="mt-2">
                                    <!-- Các nút đề xuất sẽ được thêm vào đây -->
                                </div>
                            </div>
                        </div>

                        <!-- Tab import từ Excel -->
                        <div class="tab-pane fade" id="import" role="tabpanel">
                            <form action="{{ route('admin.seats.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                
                                <div class="mb-3">
                                    <label for="excel_file" class="form-label">
                                        <iconify-icon icon="solar:document-add-bold" class="text-teal me-2"></iconify-icon>
                                        Chọn file Excel hoặc CSV
                                    </label>
                                    <input type="file" name="excel_file" id="excel_file" class="form-control border-teal" 
                                           accept=".xlsx,.xls,.csv" required style="border-color: #0D9488;">
                                    <small class="text-muted">Hỗ trợ: .xlsx, .xls, .csv</small>
                                </div>

                                <button type="submit" class="btn btn-teal text-white w-100" id="importSeatsBtn" 
                                        @if($seats->count() >= $room->capacity) disabled @endif style="background-color: #0D9488;">
                                    <iconify-icon icon="solar:upload-bold" class="me-2"></iconify-icon>Import ghế
                                </button>

                                @if(session('import_success'))
                                    <div class="alert alert-success mt-3">
                                        <iconify-icon icon="solar:check-circle-bold" class="me-2"></iconify-icon>{{ session('import_success') }}
                                    </div>
                                @endif
                                @if(session('import_error'))
                                    <div class="alert alert-danger mt-3">
                                        <iconify-icon icon="solar:warning-bold" class="me-2"></iconify-icon>{{ session('import_error') }}
                                    </div>
                                @endif
                                @if($errors->has('excel_file'))
                                    <div class="alert alert-danger mt-3">
                                        <iconify-icon icon="solar:warning-bold" class="me-2"></iconify-icon>{{ $errors->first('excel_file') }}
                                    </div>
                                @endif
                            </form>

                            <!-- Hướng dẫn định dạng file -->
                            <div class="mt-4">
                                <h6 class="fw-bold">
                                    <iconify-icon icon="solar:info-circle-bold" class="text-info me-2"></iconify-icon>Định dạng file:
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>room_id</th>
                                                <th>seat_type_id</th>
                                                <th>row_char</th>
                                                <th>seat_number</th>
                                                <th>status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $room->id }}</td>
                                                <td>7</td>
                                                <td>A</td>
                                                <td>1</td>
                                                <td>available</td>
                                            </tr>
                                            <tr>
                                                <td>{{ $room->id }}</td>
                                                <td>7</td>
                                                <td>A</td>
                                                <td>2</td>
                                                <td>available</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">
                                    <strong>Lưu ý:</strong> room_id phải là {{ $room->id }}, seat_type_id tham khảo bảng loại ghế, 
                                    status: available, maintenance, reserved, booked
                                </small>
                            </div>
                        </div>

                        <!-- Tab thêm ghế đơn -->
                        <div class="tab-pane fade" id="single" role="tabpanel">
                            <form id="addSingleSeatForm">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="single_row_char" class="form-label">Hàng</label>
                                            <select name="row_char" id="single_row_char" class="form-select border-teal" style="border-color: #0D9488;" required>
                                                <option value="">Chọn hàng</option>
                                                @for ($i = 1; $i <= 26; $i++)
                                                    <option value="{{ chr(64 + $i) }}">{{ chr(64 + $i) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="single_seat_number" class="form-label">Số ghế</label>
                                            <input type="number" name="seat_number" id="single_seat_number" class="form-control border-teal" min="1" max="50" required style="border-color: #0D9488;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="single_seat_type" class="form-label">Loại ghế</label>
                                    <select name="seat_type_id" id="single_seat_type" class="form-select border-teal" required style="border-color: #0D9488;">
                                        <option value="">Chọn loại ghế</option>
                                        @foreach ($room->allowedSeatTypes() as $seatType)
                                            <option value="{{ $seatType->id }}" data-is-couple="{{ str_contains(strtolower($seatType->name), 'couple') || str_contains(strtolower($seatType->name), 'sweetbox') || str_contains(strtolower($seatType->name), 'bed') || str_contains(strtolower($seatType->name), 'sofa') ? 'true' : 'false' }}">
                                                {{ $seatType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="seatTypeHelp" class="form-text"></div>
                                </div>
                                
                                <div class="alert alert-info py-2">
                                    <h6><iconify-icon icon="solar:info-circle-bold" class="me-2"></iconify-icon>Lưu ý:</h6>
                                    <ul class="mb-0 small">
                                        <li>Ghế đơn: Thêm 1 ghế</li>
                                        <li>Ghế đôi: Tạo 2 ghế liên tiếp</li>
                                        <li>Ví dụ: H8 ghế đôi → H8 & H9</li>
                                    </ul>
                                </div>

                                <button type="submit" class="btn btn-teal text-white w-100" style="background-color: #0D9488;">
                                    <iconify-icon icon="solar:add-circle-bold" class="me-2"></iconify-icon> Thêm ghế
                                </button>
                            </form>

                            <!-- Ghế hiện có -->
                            <div class="mt-4">
                                <h6 class="fw-bold">
                                    <iconify-icon icon="solar:chart-bold" class="text-primary me-2"></iconify-icon>Thống kê ghế:
                                </h6>
                                <div class="row text-center g-2">
                                    <div class="col-4">
                                        <div class="bg-light p-2 rounded">
                                            <div class="fw-bold text-primary">{{ $seats->count() }}</div>
                                            <small>Đã có</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-light p-2 rounded">
                                            <div class="fw-bold text-success">{{ $room->capacity - $seats->count() }}</div>
                                            <small>Còn lại</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-light p-2 rounded">
                                            <div class="fw-bold text-info">{{ $room->capacity }}</div>
                                            <small>Tổng</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 text-center">
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-orange" style="border-color: #F97316; color: #F97316; border-radius: 12px;">
                <iconify-icon icon="solar:arrow-left-bold" class="me-1"></iconify-icon> Quay lại danh sách phòng
            </a>
        </div>
    </div>
</div>

<!-- Modal Preview ghế -->
<div class="modal fade" id="previewSeatsModal" tabindex="-1" aria-labelledby="previewSeatsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previewSeatsModalLabel">Xem trước sơ đồ ghế</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <h6>Sơ đồ ghế chuẩn bị tạo:</h6>
            <div id="preview-seat-map" class="border rounded p-3" style="max-height: 400px; overflow: auto; border-color: #0D9488;">
              <!-- Sơ đồ ghế sẽ được render bằng JavaScript -->
            </div>
          </div>
          <div class="col-md-4">
            <h6>Thông tin tóm tắt:</h6>
            <div class="table-responsive">
              <table class="table table-sm">
                <tbody id="preview-summary">
                  <!-- Tóm tắt sẽ được render bằng JavaScript -->
                </tbody>
              </table>
            </div>
            <div class="mt-3">
              <h6>Lưu ý về ghế đôi:</h6>
              <ul class="list-unstyled small text-muted" id="couple-seat-notes">
                <!-- Ghi chú về ghế đôi sẽ được render bằng JavaScript -->
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-teal text-white" onclick="confirmCreateSeats()" style="background-color: #0D9488;">Xác nhận tạo ghế</button>
      </div>
    </div>
  </div>
</div>

<!-- Các modal khác giữ nguyên nhưng cập nhật buttons với color scheme -->

@push('styles')
    <style>
        /* Styles hiện đại hóa */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .btn-teal {
            background-color: #0D9488;
            border-color: #0D9488;
            transition: all 0.3s ease;
        }
        .btn-teal:hover {
            background-color: #0B8278;
            border-color: #0B8278;
        }
        .btn-outline-teal {
            border-color: #0D9488;
            color: #0D9488;
            transition: all 0.3s ease;
        }
        .btn-outline-teal:hover {
            background-color: #0D9488;
            color: white;
        }
        .btn-yellow {
            background-color: #FACC15;
            border-color: #FACC15;
            color: black;
            transition: all 0.3s ease;
        }
        .btn-yellow:hover {
            background-color: #EAB308;
            border-color: #EAB308;
        }
        .btn-outline-orange {
            border-color: #F97316;
            color: #F97316;
            transition: all 0.3s ease;
        }
        .btn-outline-orange:hover {
            background-color: #F97316;
            color: white;
        }
        .border-teal {
            border-color: #0D9488 !important;
        }
        .text-teal {
            color: #0D9488 !important;
        }
        .seat {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .seat:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .nav-tabs .nav-link {
            border-radius: 8px 8px 0 0;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link.active {
            background-color: #0D9488;
            color: white;
            border-color: #0D9488;
        }
        .tab-content {
            border: 1px solid #0D9488;
            border-top: none;
            padding: 20px;
            border-radius: 0 0 12px 12px;
        }
        /* Giữ nguyên các style khác và tinh chỉnh nếu cần */
    </style>
@endpush

@push('scripts')
    <script>
        // Khởi tạo tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

@endsection