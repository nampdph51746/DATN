@extends('layouts.admin.admin')

@section('content')
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
                    <p><strong>Sức chứa:</strong> 
                        <span id="capacity-display">{{ $room->capacity }}</span>
                        @if($seats->count() == 0)
                            <button class="btn btn-sm btn-outline-primary ms-2" onclick="editCapacity()">
                                <i class="fas fa-edit"></i> Sửa
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
                            <input type="number" id="capacity-input" class="form-control" value="{{ $room->capacity }}" 
                                   min="1" max="1000" onchange="validateCapacityChange(this.value)">
                            <button type="submit" class="btn btn-success btn-sm">Lưu</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelEditCapacity()">Hủy</button>
                        </div>
                    </form>
                    
                    <!-- Hiển thị đề xuất sức chứa -->
                    <div id="capacityHelp" class="mt-2" style="display: none;">
                        <div id="capacityHelpText"></div>
                    </div>
                    
                    <!-- Đề xuất sức chứa phổ biến -->
                    <div id="capacity-suggestions" class="mt-2" style="display: none;">
                        <small class="text-muted">💡 Đề xuất sức chứa phổ biến:</small>
                        <div class="mt-1">
                            <button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="setCapacity(50)">50 ghế</button>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="setCapacity(80)">80 ghế</button>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="setCapacity(100)">100 ghế</button>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="setCapacity(120)">120 ghế</button>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="setCapacity(150)">150 ghế</button>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="setCapacity(200)">200 ghế</button>
                        </div>
                    </div>
                    @endif
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
                            @endphp
                            
                            @if ($seat)
                                @php
                                    $displayStatus = $showtime_id ? ($seat->showtimeSeatStates()->where('showtime_id', $showtime_id)->first()?->status?->value ?? $seat->status->value) : $seat->status->value;
                                    $seatTypeName = $seat->seatType->name ?? 'Chưa có loại';
                                    $backgroundColor = $seat->seatType->color_code ?? '#28a745';
                                    $textColor = (isset($seat->seatType->color_code) && strtolower($seat->seatType->color_code) == '#ffd700') ? 'black' : 'white';
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
                                {{-- Empty cell để giữ grid alignment --}}
                                <div class="seat seat-empty" style="background-color: transparent; border: 1px dashed #ccc; cursor: default;">
                                    {{-- Ô trống --}}
                                </div>
                            @endif
                            @endfor
                        @endforeach
                    </div>
                    <p class="mt-2">Số ghế hiện có: {{ $seats->count() }} / {{ $room->capacity }} (Còn lại: {{ $room->capacity - $seats->count() }})</p>
                    
                    @if($hasActiveShowtimes)
                        <div class="alert alert-warning mt-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Không thể xóa ghế:</strong> Phòng này đang có suất chiếu hoạt động.
                        </div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning" id="bulkEditBtn" disabled>
                            <i class="fas fa-edit"></i> Chỉnh sửa hàng loạt
                        </button>
                        <button type="button" class="btn btn-danger" id="bulkDeleteBtn" disabled onclick="confirmBulkDelete()" 
                                @if($hasActiveShowtimes) title="Không thể xóa ghế khi phòng có suất chiếu đang hoạt động" @endif>
                            <i class="fas fa-trash"></i> Xóa hàng loạt
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
                    
                    // Dữ liệu ghế đôi từ server
                    const coupleSeatsData = @json($coupleSeatsIds);
                    
                    console.log('Couple seats data:', coupleSeatsData);
                    
                    try {
                        document.addEventListener('DOMContentLoaded', function () {
                            console.log('DOM fully loaded - Seat script');
                            const seats = document.querySelectorAll('.seat');
                            const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
                            const bulkEditBtn = document.getElementById('bulkEditBtn');
                            console.log('Found seats:', seats.length, 'Found checkboxes:', seatCheckboxes.length);

                            // Thêm CSS cho animation
                            const style = document.createElement('style');
                            style.textContent = `
                                .couple-auto-selected {
                                    animation: pulseCouple 1s ease-in-out;
                                    border: 2px solid #f39c12 !important;
                                }
                                @keyframes pulseCouple {
                                    0% { transform: scale(1); }
                                    50% { transform: scale(1.1); box-shadow: 0 0 15px rgba(243, 156, 18, 0.7); }
                                    100% { transform: scale(1); }
                                }
                            `;
                            document.head.appendChild(style);

                            // Map ghế theo cặp đôi (A1-A2, A3-A4, etc.)
                            const couplePairs = {};
                            coupleSeatsData.forEach(seatId => {
                                const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
                                if (seatElement) {
                                    const rowChar = seatElement.dataset.rowChar;
                                    const seatNumber = parseInt(seatElement.dataset.seatNumber);
                                    
                                    if (seatNumber % 2 === 1) {
                                        // Ghế lẻ, tìm ghế chẵn kế tiếp
                                        const partnerSeatNumber = seatNumber + 1;
                                        const partnerElement = document.querySelector(`[data-row-char="${rowChar}"][data-seat-number="${partnerSeatNumber}"]`);
                                        if (partnerElement && coupleSeatsData.includes(parseInt(partnerElement.dataset.seatId))) {
                                            couplePairs[seatId] = parseInt(partnerElement.dataset.seatId);
                                            couplePairs[partnerElement.dataset.seatId] = seatId;
                                        }
                                    }
                                }
                            });
                            
                            console.log('Couple pairs mapping:', couplePairs);

                            // Cập nhật trạng thái nút "Chỉnh sửa hàng loạt" và "Xóa hàng loạt"
                            function updateBulkEditButton() {
                                const checkedCount = document.querySelectorAll('.seat-checkbox:checked').length;
                                bulkEditBtn.disabled = checkedCount === 0;
                                
                                const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
                                if (bulkDeleteBtn) {
                                    // Disable nếu không có ghế nào được chọn HOẶC phòng có suất chiếu đang hoạt động
                                    const hasActiveShowtimes = {{ $hasActiveShowtimes ? 'true' : 'false' }};
                                    bulkDeleteBtn.disabled = checkedCount === 0 || hasActiveShowtimes;
                                }
                                
                                console.log('Checked seats:', checkedCount, 'Bulk buttons disabled:', bulkEditBtn.disabled);
                            }

                            // Xử lý chọn ghế đôi tự động
                            function handleCoupleSelection(checkbox, isChecked) {
                                const seatId = parseInt(checkbox.value);
                                const partnerId = couplePairs[seatId];
                                
                                if (partnerId) {
                                    const partnerCheckbox = document.querySelector(`.seat-checkbox[value="${partnerId}"]`);
                                    if (partnerCheckbox && partnerCheckbox.checked !== isChecked) {
                                        partnerCheckbox.checked = isChecked;
                                        console.log(`Auto-${isChecked ? 'selected' : 'deselected'} couple partner seat ${partnerId} for seat ${seatId}`);
                                        
                                        // Thêm visual feedback
                                        const partnerSeat = partnerCheckbox.closest('.seat');
                                        if (partnerSeat) {
                                            if (isChecked) {
                                                partnerSeat.classList.add('couple-auto-selected');
                                                setTimeout(() => partnerSeat.classList.remove('couple-auto-selected'), 1000);
                                            }
                                        }
                                    }
                                }
                            }

                            // Gắn sự kiện change cho checkbox
                            seatCheckboxes.forEach(checkbox => {
                                checkbox.addEventListener('change', function () {
                                    console.log('Checkbox changed, ID:', this.value, 'Checked:', this.checked);
                                    
                                    // Xử lý chọn ghế đôi tự động
                                    handleCoupleSelection(this, this.checked);
                                    
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
                                    const checkedSeats = document.querySelectorAll('.seat-checkbox:checked');
                                    if (checkedSeats.length === 0) {
                                        e.preventDefault();
                                        console.log('No seats selected for bulk edit');
                                        alert('Vui lòng chọn ít nhất một ghế để chỉnh sửa.');
                                        return;
                                    }
                                    
                                    // Hiển thị thông báo xác nhận với thông tin ghế đôi
                                    const selectedSeatIds = Array.from(checkedSeats).map(cb => parseInt(cb.value));
                                    const coupleSeatsInSelection = selectedSeatIds.filter(id => coupleSeatsData.includes(id));
                                    
                                    let message = `Bạn đã chọn ${checkedSeats.length} ghế để chỉnh sửa hàng loạt.`;
                                    if (coupleSeatsInSelection.length > 0) {
                                        message += `\n\nLưu ý: Có ${coupleSeatsInSelection.length} ghế đôi trong lựa chọn của bạn.`;
                                        message += `\nHệ thống sẽ tự động cập nhật cả cặp ghế đôi liên quan.`;
                                    }
                                    message += `\n\nTiếp tục?`;
                                    
                                    if (!confirm(message)) {
                                        e.preventDefault();
                                    }
                                });
                            } else {
                                console.warn('bulkEditForm not found in DOM');
                            }

                            // Khởi tạo trạng thái nút
                            updateBulkEditButton();
                        });
                    } catch (error) {
                        console.error('Error in seat script:', error);
                    }
                </script>

                <!-- JavaScript cho xóa hàng loạt -->
                <script>
                    // Hàm xác nhận xóa hàng loạt
                    function confirmBulkDelete() {
                        // Kiểm tra nếu phòng có suất chiếu đang hoạt động
                        const hasActiveShowtimes = {{ $hasActiveShowtimes ? 'true' : 'false' }};
                        if (hasActiveShowtimes) {
                            alert('⚠️ KHÔNG THỂ XÓA GHẾ!\n\nPhòng chiếu này đang có suất chiếu hoạt động (scheduled hoặc ongoing).\nVui lòng hủy hoặc hoàn thành tất cả suất chiếu trước khi xóa ghế.');
                            return;
                        }

                        const checkedSeats = document.querySelectorAll('.seat-checkbox:checked');
                        if (checkedSeats.length === 0) {
                            alert('Vui lòng chọn ít nhất một ghế để xóa.');
                            return;
                        }

                        const selectedSeatIds = Array.from(checkedSeats).map(cb => parseInt(cb.value));
                        const coupleSeatsInSelection = selectedSeatIds.filter(id => coupleSeatsData.includes(id));
                        
                        let seatNames = Array.from(checkedSeats).map(cb => {
                            const seat = cb.closest('.seat');
                            return seat.textContent.trim();
                        });

                        let message = `⚠️ BẠN ĐANG CHUẨN BỊ XÓA ${checkedSeats.length} GHẾ!\n\n`;
                        message += `Ghế sẽ bị xóa: ${seatNames.join(', ')}\n\n`;
                        
                        if (coupleSeatsInSelection.length > 0) {
                            message += `🔗 Lưu ý: Có ${coupleSeatsInSelection.length} ghế đôi trong lựa chọn.\n`;
                            message += `Hệ thống sẽ tự động xóa cả cặp ghế đôi liên quan.\n\n`;
                        }

                        message += `🚨 CẢNH BÁO:\n`;
                        message += `• Chỉ có thể xóa ghế CHƯA TỪNG có ai đặt\n`;
                        message += `• Ghế đã từng được đặt sẽ KHÔNG thể xóa\n`;
                        message += `• Thao tác này KHÔNG THỂ HOÀN TÁC\n\n`;
                        message += `Bạn có chắc chắn muốn tiếp tục?`;

                        if (confirm(message)) {
                            performBulkDelete(selectedSeatIds);
                        }
                    }

                    // Thực hiện xóa hàng loạt
                    function performBulkDelete(seatIds) {
                        const deleteForm = document.getElementById('bulkDeleteForm');
                        const seatIdsContainer = document.getElementById('delete-seat-ids');
                        
                        // Debug: Log form information
                        console.log('=== BULK DELETE DEBUG ===');
                        console.log('Form action:', deleteForm.action);
                        console.log('Form method:', deleteForm.method);
                        console.log('Seat IDs to delete:', seatIds);
                        
                        // Xóa input cũ
                        seatIdsContainer.innerHTML = '';
                        
                        // Thêm input cho mỗi seat ID
                        seatIds.forEach(seatId => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'seat_ids[]';
                            input.value = seatId;
                            seatIdsContainer.appendChild(input);
                        });

                        // Debug: Log form data before submit
                        const formData = new FormData(deleteForm);
                        console.log('Form data before submit:');
                        for (let [key, value] of formData.entries()) {
                            console.log(key, value);
                        }

                        // Hiển thị loading
                        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
                        const originalText = bulkDeleteBtn.innerHTML;
                        bulkDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xóa...';
                        bulkDeleteBtn.disabled = true;

                        // Debug: Final check before submit
                        console.log('About to submit form to:', deleteForm.action);
                        console.log('Form HTML:', deleteForm.outerHTML);

                        // Submit form
                        deleteForm.submit();
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
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-fill mb-3" id="seatTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="manual-tab" data-bs-toggle="pill" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true">
                                <i class="fas fa-plus"></i> Tạo ghế thủ công
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="import-tab" data-bs-toggle="pill" data-bs-target="#import" type="button" role="tab" aria-controls="import" aria-selected="false">
                                <i class="fas fa-file-excel"></i> Import từ Excel
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="single-tab" data-bs-toggle="pill" data-bs-target="#single" type="button" role="tab" aria-controls="single" aria-selected="false">
                                <i class="fas fa-chair"></i> Thêm ghế đơn
                            </button>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" id="seatTabsContent">
                        <!-- Tab tạo ghế thủ công -->
                        <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                            <!-- Bước 1: Thiết lập số hàng -->
                            <div class="mb-3">
                                <label for="total_rows" class="form-label">Số hàng muốn tạo</label>
                                <input type="number" id="total_rows" class="form-control" min="1" max="26" value="5">
                                <small class="text-muted">Tối đa 26 hàng (A-Z)</small>
                            </div>

                            <!-- Bước 2: Thiết lập số ghế mỗi hàng -->
                            <div class="mb-3">
                                <label for="seats_per_row" class="form-label">Số ghế mỗi hàng</label>
                                <input type="number" id="seats_per_row" class="form-control" min="1" max="50" value="10">
                                <small class="text-muted">Tối đa 50 ghế mỗi hàng</small>
                            </div>

                            <!-- Bước 3: Chọn loại ghế cho từng hàng -->
                            <div class="mb-3">
                                <label class="form-label">Phân loại ghế theo hàng</label>
                                <div id="row-seat-types" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    <!-- Sẽ được tạo động bằng JavaScript -->
                                </div>
                            </div>

                            <!-- Bước 4: Preview và tạo -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="createSeats()">
                                    <i class="fas fa-eye"></i> Xem trước
                                </button>
                                <button type="button" class="btn btn-primary" onclick="createSeats()" id="create-seats-btn" disabled>
                                    <i class="fas fa-plus"></i> Tạo ghế
                                </button>
                            </div>

                            <!-- Thông tin tóm tắt -->
                            <div class="mt-3 p-2 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <span>Sức chứa phòng:</span>
                                    <span id="room-capacity">{{ $room->capacity }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Ghế hiện có:</span>
                                    <span>{{ $seats->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
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
                        <div class="tab-pane fade" id="import" role="tabpanel" aria-labelledby="import-tab">
                            <form action="{{ route('admin.seats.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                
                                <div class="mb-3">
                                    <label for="excel_file" class="form-label">
                                        <i class="fas fa-file-excel text-success me-2"></i>
                                        Chọn file Excel hoặc CSV
                                    </label>
                                    <input type="file" name="excel_file" id="excel_file" class="form-control" 
                                           accept=".xlsx,.xls,.csv" required>
                                    <div class="form-text">
                                        <small>Hỗ trợ các định dạng: .xlsx, .xls, .csv</small>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success w-100" id="importSeatsBtn" 
                                        @if($seats->count() >= $room->capacity) disabled @endif>
                                    <i class="fas fa-upload me-2"></i>Import ghế từ file
                                </button>

                                @if(session('import_success'))
                                    <div class="alert alert-success mt-3">
                                        <i class="fas fa-check-circle me-2"></i>{{ session('import_success') }}
                                    </div>
                                @endif
                                @if(session('import_error'))
                                    <div class="alert alert-danger mt-3">
                                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('import_error') }}
                                    </div>
                                @endif
                                @if($errors->has('excel_file'))
                                    <div class="alert alert-danger mt-3">
                                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('excel_file') }}
                                    </div>
                                @endif
                            </form>

                            <!-- Hướng dẫn định dạng file -->
                            <div class="mt-4">
                                <h6 class="fw-bold">
                                    <i class="fas fa-info-circle text-info me-2"></i>Định dạng file Excel/CSV:
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
                                    status có thể là: available, maintenance, reserved, booked
                                </small>
                            </div>
                        </div>

                        <!-- Tab thêm ghế đơn -->
                        <div class="tab-pane fade" id="single" role="tabpanel" aria-labelledby="single-tab">
                            <form id="addSingleSeatForm">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="single_row_char" class="form-label">Hàng <span class="text-danger">*</span></label>
                                            <select name="row_char" id="single_row_char" class="form-control" required>
                                                <option value="">Chọn hàng</option>
                                                @for ($i = 1; $i <= 26; $i++)
                                                    <option value="{{ chr(64 + $i) }}">{{ chr(64 + $i) }}</option>
                                                @endfor
                                            </select>
                                            <div class="form-text">Ví dụ: A, B, C...</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="single_seat_number" class="form-label">Số ghế <span class="text-danger">*</span></label>
                                            <input type="number" name="seat_number" id="single_seat_number" class="form-control" min="1" max="50" required>
                                            <div class="form-text">Ví dụ: 1, 2, 3...</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="single_seat_type" class="form-label">Loại ghế <span class="text-danger">*</span></label>
                                    <select name="seat_type_id" id="single_seat_type" class="form-control" required>
                                        <option value="">Chọn loại ghế</option>
                                        @foreach ($room->allowedSeatTypes() as $seatType)
                                            <option value="{{ $seatType->id }}" data-is-couple="{{ str_contains(strtolower($seatType->name), 'couple') || str_contains(strtolower($seatType->name), 'sweetbox') || str_contains(strtolower($seatType->name), 'bed') || str_contains(strtolower($seatType->name), 'sofa') ? 'true' : 'false' }}">
                                                {{ $seatType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="seatTypeHelp" class="form-text"></div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Lưu ý khi thêm ghế:</h6>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Ghế đơn:</strong> Thêm 1 ghế tại vị trí đã chọn</li>
                                        <li><strong>Ghế đôi:</strong> Tự động tạo cả 2 ghế (số lẻ và chẵn kế tiếp)</li>
                                        <li><strong>Ví dụ:</strong> Chọn H8 với ghế đôi → sẽ tạo H8 và H9</li>
                                        <li><strong>Điều kiện:</strong> Vị trí ghế chưa tồn tại và không vượt sức chứa</li>
                                    </ul>
                                </div>

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i> Thêm ghế
                                </button>
                            </form>

                            <!-- Ghế hiện có -->
                            <div class="mt-4">
                                <h6 class="fw-bold">
                                    <i class="fas fa-chair text-primary me-2"></i>Thống kê ghế hiện có:
                                </h6>
                                <div class="row text-center">
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
                                            <small>Tổng cộng</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Nút quay lại đã được chuyển xuống dưới, xóa nút cũ này -->
        </div>
        
        <div class="col-lg-3 col-md-12">
            <a href="{{ route('admin.rooms.index') }}" class="btn mb-2" style="background: #f86c2c; color: #fff; font-weight: 500; border-radius: 12px; font-size: 0.95rem; height: 36px; min-width: 120px; display: inline-flex; align-items: center; justify-content: center;">Quay lại danh sách phòng</a>
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
            <div id="preview-seat-map" class="border rounded p-3" style="max-height: 400px; overflow: auto;">
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
        <button type="button" class="btn btn-primary" onclick="confirmCreateSeats()">Xác nhận tạo ghế</button>
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

<!-- Modal xác nhận cập nhật tỷ lệ -->
<div class="modal fade" id="updatePercentagesModal" tabindex="-1" aria-labelledby="updatePercentagesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updatePercentagesLabel">Xác nhận cập nhật tỷ lệ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Bạn có chắc chắn muốn cập nhật tỷ lệ loại ghế cho phòng này không?</p>
        <div id="percentagePreview" class="mt-3 p-3 bg-light rounded">
          <!-- Preview tỷ lệ sẽ được render bằng JS -->
        </div>
        <div class="alert alert-warning mt-3">
          <i class="fas fa-exclamation-triangle"></i>
          <strong>Lưu ý:</strong> Việc thay đổi tỷ lệ sẽ ảnh hưởng đến việc tính toán số ghế cần thêm cho từng loại.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" id="confirmUpdatePercentagesBtn">Xác nhận cập nhật</button>
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
    
    /* CSS cho nút xóa hàng loạt */
    #bulkDeleteBtn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    #bulkDeleteBtn:not(:disabled):hover {
        background-color: #c82333;
        border-color: #bd2130;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .btn-danger {
        transition: all 0.2s ease;
    }
</style>

<script>
// Dữ liệu từ server
const roomData = {
    id: {{ $room->id }},
    capacity: {{ $room->capacity }},
    existingSeats: {{ $seats->count() }},
    roomType: {
        id: {{ $room->room_type_id }},
        name: '{{ $room->roomType->name }}',
        hasCoupleSeats: {{ $room->allowedSeatTypes()->filter(function($type) {
            $name = strtolower($type->name);
            return str_contains($name, 'couple') || str_contains($name, 'sweetbox') || 
                   str_contains($name, 'bed') || str_contains($name, 'sofa');
        })->count() > 0 ? 'true' : 'false' }},
        coupleSeatsNames: {!! json_encode($room->allowedSeatTypes()->filter(function($type) {
            $name = strtolower($type->name);
            return str_contains($name, 'couple') || str_contains($name, 'sweetbox') || 
                   str_contains($name, 'bed') || str_contains($name, 'sofa');
        })->pluck('name')->toArray()) !!}
    }
};

const allowedSeatTypes = {!! json_encode($room->allowedSeatTypes()->map(function($type) {
    return [
        'id' => $type->id,
        'name' => $type->name,
        'color_code' => $type->color_code ?? '#007bff',
        'is_couple' => str_contains(strtolower($type->name), 'couple') || str_contains(strtolower($type->name), 'bed') || str_contains(strtolower($type->name), 'sofa')
    ];
})) !!};

let seatConfiguration = []; // Lưu cấu hình ghế theo hàng

document.addEventListener('DOMContentLoaded', function() {
    // Đồng bộ hiển thị sức chứa
    syncCapacityDisplay();
    
    // Khởi tạo interface
    updateRowSeatTypes();
    
    // Event listeners
    document.getElementById('total_rows').addEventListener('input', updateRowSeatTypes);
    document.getElementById('seats_per_row').addEventListener('input', updateRowSeatTypes);
});

// Đồng bộ tất cả các hiển thị sức chứa
function syncCapacityDisplay() {
    const capacity = roomData.capacity;
    const capacityDisplay = document.getElementById('capacity-display');
    const roomCapacityElement = document.getElementById('room-capacity');
    
    if (capacityDisplay) {
        capacityDisplay.textContent = capacity;
    }
    if (roomCapacityElement) {
        roomCapacityElement.textContent = capacity;
    }
}

// Cập nhật interface chọn loại ghế cho từng hàng
function updateRowSeatTypes() {
    const totalRows = parseInt(document.getElementById('total_rows').value) || 0;
    const seatsPerRow = parseInt(document.getElementById('seats_per_row').value) || 0;
    const container = document.getElementById('row-seat-types');
    
    container.innerHTML = '';
    seatConfiguration = [];
    
    if (totalRows === 0 || seatsPerRow === 0) {
        updateSummary();
        return;
    }
    
    for (let i = 0; i < totalRows; i++) {
        const rowChar = String.fromCharCode(65 + i); // A, B, C...
        const rowConfig = {
            row: rowChar,
            seatType: allowedSeatTypes[0]?.id || null,
            seatsCount: seatsPerRow
        };
        seatConfiguration.push(rowConfig);
        
        const rowDiv = document.createElement('div');
        rowDiv.className = 'row-config mb-2 p-2 border rounded';
        rowDiv.innerHTML = `
            <div class="d-flex align-items-center justify-content-between">
                <strong>Hàng ${rowChar}:</strong>
                <select class="form-select form-select-sm" style="width: auto;" onchange="updateRowSeatType(${i}, this.value)">
                    ${allowedSeatTypes.map(type => 
                        `<option value="${type.id}">${type.name}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="small text-muted mt-1" id="row-${i}-info">
                ${seatsPerRow} ghế - ${allowedSeatTypes[0]?.name || 'Chưa chọn'}
            </div>
        `;
        container.appendChild(rowDiv);
    }
    
    updateSummary();
}

// Cập nhật loại ghế cho hàng
function updateRowSeatType(rowIndex, seatTypeId) {
    if (seatConfiguration[rowIndex]) {
        seatConfiguration[rowIndex].seatType = parseInt(seatTypeId);
        const seatType = allowedSeatTypes.find(type => type.id == seatTypeId);
        const seatsPerRow = seatConfiguration[rowIndex].seatsCount;
        
        // Cập nhật thông tin hàng
        const infoElement = document.getElementById(`row-${rowIndex}-info`);
        if (infoElement) {
            infoElement.textContent = `${seatsPerRow} ghế - ${seatType?.name || 'Chưa chọn'}`;
        }
        
        updateSummary();
    }
}

// Cập nhật lại hiển thị cấu hình hàng ghế
function updateRowConfiguration() {
    // Sử dụng container đúng tồn tại trong DOM
    const container = document.getElementById('row-seat-types');
    if (!container) {
        console.error('Container row-seat-types not found!');
        return;
    }
    
    container.innerHTML = '';
    
    seatConfiguration.forEach((rowConfig, index) => {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'row-config mb-2 p-2 border rounded';
        rowDiv.innerHTML = `
            <div class="d-flex align-items-center justify-content-between">
                <strong>Hàng ${rowConfig.row}:</strong>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="updateRowSeatType(${index}, this.value)">
                        ${allowedSeatTypes.map(type => 
                            `<option value="${type.id}" ${type.id == rowConfig.seatType ? 'selected' : ''}>${type.name}</option>`
                        ).join('')}
                    </select>
                    <input type="number" class="form-control form-control-sm" style="width: 80px;" 
                           value="${rowConfig.seatsCount}" min="1" max="20" 
                           onchange="updateRowSeatsCount(${index}, this.value)">
                    <button class="btn btn-outline-danger btn-sm" onclick="removeRowConfiguration(${index})" title="Xóa hàng">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="small text-muted mt-1" id="row-${index}-info">
                ${rowConfig.seatsCount} ghế - ${allowedSeatTypes.find(t => t.id == rowConfig.seatType)?.name || 'Chưa chọn'}
            </div>
        `;
        container.appendChild(rowDiv);
    });
}

// Cập nhật số ghế của hàng
function updateRowSeatsCount(rowIndex, newCount) {
    const count = parseInt(newCount);
    if (count > 0 && count <= 20) {
        seatConfiguration[rowIndex].seatsCount = count;
        
        // Cập nhật thông tin hiển thị
        const seatType = allowedSeatTypes.find(t => t.id == seatConfiguration[rowIndex].seatType);
        document.getElementById(`row-${rowIndex}-info`).textContent = 
            `${count} ghế - ${seatType?.name || 'Chưa chọn'}`;
        
        updateSummary();
        renderPreviewMap();
    }
}

// Cập nhật thông tin tóm tắt
function updateSummary() {
    const totalSeats = seatConfiguration.reduce((sum, row) => sum + row.seatsCount, 0);
    const currentSeats = roomData.existingSeats;
    const afterAdd = currentSeats + totalSeats;
    
    document.getElementById('seats-to-add').textContent = totalSeats;
    document.getElementById('total-after-add').textContent = afterAdd;
    
    // Kiểm tra capacity mismatch và hiển thị đề xuất
    checkCapacityAndSuggest(totalSeats, currentSeats, afterAdd);
    
    // Enable/disable nút tạo ghế
    const createBtn = document.getElementById('create-seats-btn');
    createBtn.disabled = totalSeats === 0 || afterAdd > roomData.capacity;
    
    if (afterAdd > roomData.capacity) {
        createBtn.textContent = `Vượt quá sức chứa (${roomData.capacity})`;
        createBtn.className = 'btn btn-danger';
    } else {
        createBtn.textContent = 'Tạo ghế';
        createBtn.className = 'btn btn-primary';
    }
}

// Kiểm tra capacity và đưa ra đề xuất
function checkCapacityAndSuggest(totalSeats, currentSeats, afterAdd) {
    console.log('checkCapacityAndSuggest called:', {totalSeats, currentSeats, afterAdd, capacity: roomData.capacity});
    
    const capacityWarning = document.getElementById('capacity-warning');
    const suggestions = document.getElementById('suggestions');
    
    if (!capacityWarning || !suggestions) {
        console.error('Warning elements not found:', {capacityWarning, suggestions});
        return;
    }
    
    // Reset hiển thị
    capacityWarning.style.display = 'none';
    suggestions.innerHTML = '';
    
    // Kiểm tra các trường hợp
    if (totalSeats === 0) {
        return; // Không có ghế nào được cấu hình
    }
    
    const remainingCapacity = roomData.capacity - currentSeats;
    console.log('Remaining capacity:', remainingCapacity);
    
    if (totalSeats !== remainingCapacity && currentSeats === 0) {
        // Trường hợp phòng trống, có mismatch với capacity
        console.log('Capacity mismatch detected for empty room');
        capacityWarning.style.display = 'block';
        
        let suggestionsHTML = '<h6>💡 Đề xuất điều chỉnh:</h6>';
        
        if (totalSeats < remainingCapacity) {
            // Thiếu ghế
            const deficit = remainingCapacity - totalSeats;
            suggestionsHTML += `
                <div class="alert alert-info p-2 mb-2">
                    <strong>Thiếu ${deficit} ghế</strong> so với sức chứa phòng (${roomData.capacity})
                </div>
                <div class="suggestion-options">
                    <button class="btn btn-sm btn-outline-primary me-2" onclick="suggestAddSeats(${deficit})">
                        📝 Đề xuất thêm ghế
                    </button>
                    <button class="btn btn-sm btn-outline-warning" onclick="suggestUpdateCapacity(${totalSeats})">
                        🔧 Cập nhật sức chứa thành ${totalSeats}
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="suggestOptimalLayout(${remainingCapacity})">
                        🎯 Tạo layout tối ưu ${remainingCapacity} ghế
                    </button>
                </div>`;
        } else if (totalSeats > remainingCapacity) {
            // Thừa ghế
            const excess = totalSeats - remainingCapacity;
            suggestionsHTML += `
                <div class="alert alert-warning p-2 mb-2">
                    <strong>Thừa ${excess} ghế</strong> so với sức chứa phòng (${roomData.capacity})
                </div>
                <div class="suggestion-options">
                    <button class="btn btn-sm btn-outline-danger me-2" onclick="suggestRemoveSeats(${excess})">
                        ✂️ Đề xuất bớt ghế
                    </button>
                    <button class="btn btn-sm btn-outline-success" onclick="suggestUpdateCapacity(${totalSeats})">
                        📈 Tăng sức chứa thành ${totalSeats}
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="suggestOptimalLayout(${remainingCapacity})">
                        🎯 Tạo layout tối ưu ${remainingCapacity} ghế
                    </button>
                </div>`;
        }
        
        suggestions.innerHTML = suggestionsHTML;
    } else if (totalSeats > remainingCapacity && currentSeats > 0) {
        // Phòng đã có ghế, không đủ chỗ
        capacityWarning.style.display = 'block';
        suggestions.innerHTML = `
            <div class="alert alert-danger p-2 mb-2">
                <strong>Không đủ chỗ!</strong> Chỉ còn ${remainingCapacity} vị trí trống
            </div>
            <div class="suggestion-options">
                <button class="btn btn-sm btn-outline-warning" onclick="suggestAdjustToFit(${remainingCapacity})">
                    🔧 Điều chỉnh về ${remainingCapacity} ghế
                </button>
            </div>`;
    }
}

// Các function đề xuất điều chỉnh
function suggestAddSeats(deficit) {
    if (seatConfiguration.length === 0) {
        alert('Vui lòng thêm ít nhất 1 hàng ghế trước');
        return;
    }
    
    // Tạo bản sao để không ảnh hưởng đến cấu hình gốc
    const originalConfig = JSON.parse(JSON.stringify(seatConfiguration));
    
    // Thuật toán phân bổ thông minh: ưu tiên hàng giữa, phân bổ đều
    const rowsCount = seatConfiguration.length;
    let remaining = deficit;
    let message = `Đề xuất thêm ${deficit} ghế để tạo layout cân đối:\n`;
    
    // Sắp xếp các hàng theo thứ tự ưu tiên: giữa -> ngoài
    const priorityOrder = [];
    const mid = Math.floor(rowsCount / 2);
    
    // Thêm hàng giữa trước
    priorityOrder.push(mid);
    
    // Thêm các hàng xung quanh hàng giữa
    for (let i = 1; i <= mid; i++) {
        if (mid - i >= 0) priorityOrder.push(mid - i);
        if (mid + i < rowsCount) priorityOrder.push(mid + i);
    }
    
    // Phân bổ ghế theo thứ tự ưu tiên
    while (remaining > 0) {
        let distributed = false;
        
        for (let rowIndex of priorityOrder) {
            if (remaining <= 0) break;
            
            // Thêm tối đa 2 ghế mỗi lượt để tránh mất cân đối
            const toAdd = Math.min(remaining, 2);
            seatConfiguration[rowIndex].seatsCount += toAdd;
            remaining -= toAdd;
            distributed = true;
            
            if (remaining <= 0) break;
        }
        
        // Tránh vòng lặp vô hạn
        if (!distributed) break;
    }
    
    // Tạo thông báo chi tiết
    seatConfiguration.forEach((row, index) => {
        const diff = row.seatsCount - originalConfig[index].seatsCount;
        if (diff > 0) {
            message += `• Hàng ${row.row}: +${diff} ghế (${originalConfig[index].seatsCount} → ${row.seatsCount})\n`;
        }
    });
    
    if (remaining > 0) {
        message += `\n⚠️ Không thể phân bổ đủ ${remaining} ghế còn lại`;
    }
    
    message += '\n💡 Tip: Ghế được thêm ưu tiên vào hàng giữa để tạo layout cân đối';
    
    if (confirm(message + '\n\nÁp dụng thay đổi này?')) {
        updateRowConfiguration();
        updateSummary();
        renderPreviewMap();
    } else {
        // Khôi phục cấu hình gốc nếu user hủy
        seatConfiguration.forEach((row, index) => {
            row.seatsCount = originalConfig[index].seatsCount;
        });
    }
}

function suggestRemoveSeats(excess) {
    if (seatConfiguration.length === 0) {
        alert('Không có hàng ghế nào để bớt');
        return;
    }
    
    // Tạo bản sao để không ảnh hưởng đến cấu hình gốc
    const originalConfig = JSON.parse(JSON.stringify(seatConfiguration));
    
    let remaining = excess;
    let message = `Đề xuất bớt ${excess} ghế để tạo layout cân đối:\n`;
    
    // Thuật toán bớt ghế thông minh: bớt từ đầu và cuối để giữ tính đối xứng
    const rowsCount = seatConfiguration.length;
    
    // Tạo danh sách các hàng với thông tin vị trí (đầu/cuối/giữa)
    const rowsWithPosition = seatConfiguration.map((row, index) => ({
        index,
        row,
        isEdge: index === 0 || index === rowsCount - 1,
        seatsCount: row.seatsCount,
        originalSeatsCount: row.seatsCount
    }));
    
    // Sắp xếp ưu tiên: hàng đầu/cuối trước, sau đó theo số ghế nhiều nhất
    rowsWithPosition.sort((a, b) => {
        if (a.isEdge && !b.isEdge) return -1;
        if (!a.isEdge && b.isEdge) return 1;
        return b.seatsCount - a.seatsCount;
    });
    
    // Lưu thông tin chi tiết cách bớt ghế cho mỗi hàng
    const detailChanges = [];
    
    // Bớt ghế theo chiến lược cân đối
    while (remaining > 0) {
        let distributed = false;
        
        for (let rowInfo of rowsWithPosition) {
            if (remaining <= 0) break;
            
            const row = rowInfo.row;
            const maxRemovable = Math.max(0, row.seatsCount - 1); // Giữ ít nhất 1 ghế
            
            if (maxRemovable > 0) {
                // Bớt tối đa 2 ghế mỗi lượt để tránh mất cân đối
                const toRemove = Math.min(remaining, Math.min(maxRemovable, 2));
                
                // Xác định cách bớt ghế: từ hai đầu để cân đối
                let removeMethod = '';
                if (toRemove === 1) {
                    // Bớt 1 ghế: ưu tiên bớt từ cuối nếu hàng có số ghế lẻ, từ đầu nếu chẵn
                    removeMethod = row.seatsCount % 2 === 1 ? 'cuối' : 'đầu';
                } else if (toRemove === 2) {
                    // Bớt 2 ghế: bớt 1 đầu + 1 cuối để giữ tính đối xứng
                    removeMethod = 'đầu + cuối';
                }
                
                // Ghi lại thay đổi chi tiết
                const existingChange = detailChanges.find(c => c.row === row.row);
                if (existingChange) {
                    existingChange.removed += toRemove;
                    existingChange.methods.push(removeMethod);
                } else {
                    detailChanges.push({
                        row: row.row,
                        index: rowInfo.index,
                        originalCount: rowInfo.originalSeatsCount,
                        removed: toRemove,
                        methods: [removeMethod]
                    });
                }
                
                row.seatsCount -= toRemove;
                remaining -= toRemove;
                distributed = true;
            }
            
            if (remaining <= 0) break;
        }
        
        // Tránh vòng lặp vô hạn
        if (!distributed) break;
    }
    
    // Tạo thông báo chi tiết với cách bớt ghế cụ thể
    detailChanges.forEach(change => {
        const position = change.index === 0 ? ' (hàng đầu)' : 
                        change.index === rowsCount - 1 ? ' (hàng cuối)' : 
                        ' (hàng giữa)';
        
        const newCount = change.originalCount - change.removed;
        message += `• Hàng ${change.row}: ${change.originalCount} → ${newCount} ghế${position}\n`;
        
        // Thêm chi tiết cách bớt
        const methodText = change.methods.join(', ').replace('đầu + cuối', 'bớt 1 ghế đầu + 1 ghế cuối');
        message += `  └─ Bớt từ: ${methodText}\n`;
    });
    
    if (remaining > 0) {
        message += `\n⚠️ Không thể bớt đủ ${remaining} ghế (mỗi hàng cần ít nhất 1 ghế)`;
    }
    
    message += '\n💡 Tip: Ghế được bớt từ đầu và cuối hàng để giữ tính đối xứng và thẩm mỹ';
    message += '\n   Ví dụ: Hàng A có 12 ghế (A01-A12), bớt 2 ghế → còn A02-A11';
    
    if (confirm(message + '\n\nÁp dụng thay đổi này?')) {
        updateRowConfiguration();
        updateSummary();
        renderPreviewMap();
    } else {
        // Khôi phục cấu hình gốc nếu user hủy
        seatConfiguration.forEach((row, index) => {
            row.seatsCount = originalConfig[index].seatsCount;
        });
    }
}

function suggestUpdateCapacity(newCapacity) {
    if (confirm(`Cập nhật sức chứa phòng từ ${roomData.capacity} thành ${newCapacity}?`)) {
        updateRoomCapacity(newCapacity);
    }
}

function suggestAdjustToFit(maxSeats) {
    if (seatConfiguration.length === 0) {
        alert('Không có hàng ghế nào để điều chỉnh');
        return;
    }
    
    const totalCurrent = seatConfiguration.reduce((sum, row) => sum + row.seatsCount, 0);
    const toRemove = totalCurrent - maxSeats;
    
    if (toRemove <= 0) {
        alert('Cấu hình hiện tại đã phù hợp');
        return;
    }
    
    // Tạo bản sao để không ảnh hưởng đến cấu hình gốc
    const originalConfig = JSON.parse(JSON.stringify(seatConfiguration));
    
    let message = `Điều chỉnh để vừa ${maxSeats} ghế (bớt ${toRemove} ghế):\n`;
    
    // Sử dụng thuật toán cân đối như suggestRemoveSeats
    const rowsCount = seatConfiguration.length;
    let remaining = toRemove;
    
    // Tạo danh sách các hàng với thông tin vị trí
    const rowsWithPosition = seatConfiguration.map((row, index) => ({
        index,
        row,
        isEdge: index === 0 || index === rowsCount - 1,
        seatsCount: row.seatsCount,
        originalSeatsCount: row.seatsCount
    }));
    
    // Sắp xếp ưu tiên: hàng đầu/cuối trước, sau đó theo số ghế nhiều nhất
    rowsWithPosition.sort((a, b) => {
        if (a.isEdge && !b.isEdge) return -1;
        if (!a.isEdge && b.isEdge) return 1;
        return b.seatsCount - a.seatsCount;
    });
    
    // Lưu thông tin chi tiết cách bớt ghế cho mỗi hàng
    const detailChanges = [];
    
    // Bớt ghế theo chiến lược cân đối
    while (remaining > 0) {
        let distributed = false;
        
        for (let rowInfo of rowsWithPosition) {
            if (remaining <= 0) break;
            
            const row = rowInfo.row;
            const maxRemovable = Math.max(0, row.seatsCount - 1); // Giữ ít nhất 1 ghế
            
            if (maxRemovable > 0) {
                // Bớt tối đa 2 ghế mỗi lượt để tránh mất cân đối
                const toRemoveFromRow = Math.min(remaining, Math.min(maxRemovable, 2));
                
                // Xác định cách bớt ghế: từ hai đầu để cân đối
                let removeMethod = '';
                if (toRemoveFromRow === 1) {
                    // Bớt 1 ghế: ưu tiên bớt từ cuối nếu hàng có số ghế lẻ, từ đầu nếu chẵn
                    removeMethod = row.seatsCount % 2 === 1 ? 'cuối' : 'đầu';
                } else if (toRemoveFromRow === 2) {
                    // Bớt 2 ghế: bớt 1 đầu + 1 cuối để giữ tính đối xứng
                    removeMethod = 'đầu + cuối';
                }
                
                // Ghi lại thay đổi chi tiết
                const existingChange = detailChanges.find(c => c.row === row.row);
                if (existingChange) {
                    existingChange.removed += toRemoveFromRow;
                    existingChange.methods.push(removeMethod);
                } else {
                    detailChanges.push({
                        row: row.row,
                        index: rowInfo.index,
                        originalCount: rowInfo.originalSeatsCount,
                        removed: toRemoveFromRow,
                        methods: [removeMethod]
                    });
                }
                
                row.seatsCount -= toRemoveFromRow;
                remaining -= toRemoveFromRow;
                distributed = true;
            }
            
            if (remaining <= 0) break;
        }
        
        // Tránh vòng lặp vô hạn
        if (!distributed) break;
    }
    
    // Tạo thông báo chi tiết với cách bớt ghế cụ thể
    detailChanges.forEach(change => {
        const position = change.index === 0 ? ' (hàng đầu)' : 
                        change.index === rowsCount - 1 ? ' (hàng cuối)' : 
                        ' (hàng giữa)';
        
        const newCount = change.originalCount - change.removed;
        message += `• Hàng ${change.row}: ${change.originalCount} → ${newCount} ghế${position}\n`;
        
        // Thêm chi tiết cách bớt
        const methodText = change.methods.join(', ').replace('đầu + cuối', 'bớt 1 ghế đầu + 1 ghế cuối');
        message += `  └─ Bớt từ: ${methodText}\n`;
    });
    
    if (remaining > 0) {
        message += `\n⚠️ Không thể bớt đủ ${remaining} ghế còn lại (mỗi hàng cần ít nhất 1 ghế)`;
    }
    
    message += '\n💡 Layout được tối ưu để cân đối và thẩm mỹ';
    message += '\n   Ghế được bớt từ đầu và cuối hàng để giữ tính đối xứng';
    
    if (confirm(message + '\n\nÁp dụng điều chỉnh này?')) {
        updateRowConfiguration();
        updateSummary();
        renderPreviewMap();
    } else {
        // Khôi phục cấu hình gốc nếu user hủy
        seatConfiguration.forEach((row, index) => {
            row.seatsCount = originalConfig[index].seatsCount;
        });
    }
}

// Đề xuất layout tối ưu từ đầu
function suggestOptimalLayout(targetSeats) {
    if (seatConfiguration.length === 0) {
        alert('Vui lòng thiết lập số hàng trước');
        return;
    }
    
    const rowsCount = seatConfiguration.length;
    
    // Tính toán layout tối ưu
    const baseSeatsPerRow = Math.floor(targetSeats / rowsCount);
    const extraSeats = targetSeats % rowsCount;
    
    // Tạo layout cân đối: phân bổ ghế thừa vào các hàng giữa
    const optimalConfig = [];
    const midIndex = Math.floor(rowsCount / 2);
    
    for (let i = 0; i < rowsCount; i++) {
        const row = seatConfiguration[i];
        let seatsForThisRow = baseSeatsPerRow;
        
        // Phân bổ ghế thừa ưu tiên cho hàng giữa
        if (extraSeats > 0) {
            const distanceFromCenter = Math.abs(i - midIndex);
            const priority = rowsCount - distanceFromCenter;
            
            // Các hàng gần giữa sẽ được ưu tiên
            const rowsNeedingExtra = [];
            for (let j = 0; j < rowsCount; j++) {
                const dist = Math.abs(j - midIndex);
                rowsNeedingExtra.push({ index: j, distance: dist });
            }
            
            // Sắp xếp theo khoảng cách từ giữa
            rowsNeedingExtra.sort((a, b) => a.distance - b.distance);
            
            // Kiểm tra xem hàng hiện tại có trong top extraSeats không
            const currentRowRank = rowsNeedingExtra.findIndex(r => r.index === i);
            if (currentRowRank < extraSeats) {
                seatsForThisRow += 1;
            }
        }
        
        optimalConfig.push({
            row: row.row,
            oldSeats: row.seatsCount,
            newSeats: seatsForThisRow,
            seatType: row.seatType
        });
    }
    
    // Tạo thông báo
    let message = `🎯 Layout tối ưu cho ${targetSeats} ghế (${rowsCount} hàng):\n\n`;
    
    optimalConfig.forEach((config, index) => {
        const change = config.newSeats - config.oldSeats;
        const changeStr = change > 0 ? `+${change}` : change < 0 ? `${change}` : '=';
        const position = index === 0 ? ' (đầu)' : 
                        index === rowsCount - 1 ? ' (cuối)' : 
                        index === Math.floor(rowsCount / 2) ? ' (giữa)' : '';
        
        message += `• Hàng ${config.row}: ${config.oldSeats} → ${config.newSeats} (${changeStr})${position}\n`;
    });
    
    message += '\n💡 Layout được tối ưu hóa:\n';
    message += '  - Hàng giữa có nhiều ghế nhất\n';
    message += '  - Giảm dần về phía đầu và cuối\n';
    message += '  - Tạo hình dạng cân đối và thẩm mỹ';
    
    if (confirm(message + '\n\nÁp dụng layout tối ưu này?')) {
        // Áp dụng cấu hình mới
        seatConfiguration.forEach((row, index) => {
            row.seatsCount = optimalConfig[index].newSeats;
        });
        
        updateRowConfiguration();
        updateSummary();
        renderPreviewMap();
    }
}

// Function cập nhật sức chứa phòng qua AJAX
function updateRoomCapacity(newCapacity) {
    fetch(`{{ route('admin.rooms.update-capacity', $room->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            capacity: newCapacity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            roomData.capacity = newCapacity;
            // Cập nhật tất cả các element hiển thị sức chứa
            document.getElementById('room-capacity').textContent = newCapacity;
            const capacityDisplay = document.getElementById('capacity-display');
            if (capacityDisplay) {
                capacityDisplay.textContent = newCapacity;
            }
            updateSummary();
            alert('Đã cập nhật sức chứa phòng thành công!');
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể cập nhật sức chứa'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật sức chứa');
    });
}

// Render sơ đồ ghế preview
function renderPreviewMap() {
    const container = document.getElementById('preview-seat-map');
    if (seatConfiguration.length === 0) {
        container.innerHTML = '<p class="text-muted">Chưa có cấu hình ghế nào</p>';
        return;
    }
    
    // DEBUG: Log dữ liệu để kiểm tra
    console.log('=== DEBUGGING PREVIEW MAP ===');
    console.log('seatConfiguration:', seatConfiguration);
    seatConfiguration.forEach((row, index) => {
        console.log(`Row ${row.row} (index ${index}): ${row.seatsCount} seats`);
    });
    
    // Tìm số ghế tối đa trong tất cả các hàng
    const maxSeatsPerRow = Math.max(...seatConfiguration.map(row => row.seatsCount));
    console.log('Max seats per row:', maxSeatsPerRow);
    
    // Tính toán grid columns: 1 cột cho label hàng + maxSeatsPerRow cột cho ghế
    const totalColumns = maxSeatsPerRow + 1;
    console.log('Total columns will be:', totalColumns);
    
    // Reset container hoàn toàn và sử dụng CSS Grid trực tiếp
    container.innerHTML = '';
    container.style.cssText = 'padding: 10px; background: #f8f9fa; border-radius: 5px;';
    
    // Tạo HTML string với CSS Grid đơn giản và rõ ràng
    let htmlContent = `
        <div style="
            display: grid !important;
            grid-template-columns: repeat(${totalColumns}, 30px) !important;
            gap: 3px !important;
            justify-content: center !important;
            align-items: center !important;
            font-family: monospace !important;
            background: white !important;
            padding: 10px !important;
            border: 2px solid #007bff !important;
            border-radius: 5px !important;
        ">
    `;
    
    // Header row - cell trống cho label
    htmlContent += `
        <div style="
            background: #6c757d !important;
            color: white !important;
            width: 30px !important;
            height: 26px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 3px !important;
            font-size: 10px !important;
            font-weight: bold !important;
            border: 1px solid #333 !important;
        "></div>
    `;
    
    // Header cells cho số ghế (01, 02, 03, ...)
    for (let i = 1; i <= maxSeatsPerRow; i++) {
        htmlContent += `
            <div style="
                background: #adb5bd !important;
                color: black !important;
                width: 30px !important;
                height: 26px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-radius: 3px !important;
                font-size: 10px !important;
                font-weight: bold !important;
                border: 1px solid #333 !important;
            ">${String(i).padStart(2, '0')}</div>
        `;
    }
    
    // Tạo các hàng ghế
    seatConfiguration.forEach((rowConfig, rowIndex) => {
        const seatType = allowedSeatTypes.find(type => type.id == rowConfig.seatType);
        const isCouple = seatType?.is_couple || false;
        
        console.log(`Rendering row ${rowConfig.row}: ${rowConfig.seatsCount} seats`);
        
        // Label hàng (A, B, C, ...)
        htmlContent += `
            <div style="
                background: #6c757d !important;
                color: white !important;
                width: 30px !important;
                height: 26px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-radius: 3px !important;
                font-size: 10px !important;
                font-weight: bold !important;
                border: 1px solid #333 !important;
            ">${rowConfig.row}</div>
        `;
        
        // Tạo ĐỦ maxSeatsPerRow cells cho hàng này
        for (let seatIndex = 1; seatIndex <= maxSeatsPerRow; seatIndex++) {
            if (seatIndex <= rowConfig.seatsCount) {
                // Có ghế thực tế
                let seatContent = `${rowConfig.row}${String(seatIndex).padStart(2, '0')}`;
                let backgroundColor = seatType?.color_code || '#28a745';
                let textColor = 'white';
                
                // Xử lý màu text cho ghế vàng
                if (backgroundColor && backgroundColor.toLowerCase() === '#ffd700') {
                    textColor = 'black';
                }
                
                if (isCouple && seatIndex % 2 === 0) {
                    seatContent = '❤️';
                }
                
                htmlContent += `
                    <div style="
                        background: ${backgroundColor} !important;
                        color: ${textColor} !important;
                        width: 30px !important;
                        height: 26px !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        border-radius: 3px !important;
                        font-size: 8px !important;
                        font-weight: bold !important;
                        border: 1px solid #333 !important;
                    " title="${seatType?.name || 'Ghế'} - ${rowConfig.row}${String(seatIndex).padStart(2, '0')}">${seatContent}</div>
                `;
            } else {
                // Ô trống - QUAN TRỌNG: phải có để grid không bị lệch
                htmlContent += `
                    <div style="
                        background: transparent !important;
                        width: 30px !important;
                        height: 26px !important;
                        border: 1px dashed #ccc !important;
                    "></div>
                `;
                console.log(`  -> Empty cell at position ${seatIndex}`);
            }
        }
    });
    
    htmlContent += '</div>';
    
    container.innerHTML = htmlContent;
    console.log('=== PREVIEW MAP RENDERED ===');
    console.log(`Grid with ${totalColumns} columns (1 label + ${maxSeatsPerRow} seats)`);
}

// Render tóm tắt preview
function renderPreviewSummary() {
    const container = document.getElementById('preview-summary');
    const summary = {};
    let totalSeats = 0;
    let coupleSeats = 0;
    
    seatConfiguration.forEach(rowConfig => {
        const seatType = allowedSeatTypes.find(type => type.id == rowConfig.seatType);
        const typeName = seatType?.name || 'Unknown';
        
        if (!summary[typeName]) {
            summary[typeName] = { count: 0, rows: [] };
        }
        
        summary[typeName].count += rowConfig.seatsCount;
        summary[typeName].rows.push(rowConfig.row);
        totalSeats += rowConfig.seatsCount;
        
        if (seatType?.is_couple) {
            coupleSeats += Math.floor(rowConfig.seatsCount / 2);
        }
    });
    
    let html = '';
    Object.entries(summary).forEach(([typeName, data]) => {
        html += `
            <tr>
                <td>${typeName}</td>
                <td>${data.count} ghế</td>
            </tr>
            <tr>
                <td class="text-muted small">Hàng:</td>
                <td class="text-muted small">${data.rows.join(', ')}</td>
            </tr>
        `;
    });
    
    html += `
        <tr class="table-info">
            <td><strong>Tổng ghế:</strong></td>
            <td><strong>${totalSeats}</strong></td>
        </tr>
    `;
    
    if (coupleSeats > 0) {
        html += `
            <tr class="table-warning">
                <td><strong>Ghế đôi:</strong></td>
                <td><strong>${coupleSeats} cặp</strong></td>
            </tr>
        `;
    }
    
    container.innerHTML = html;
}

// Render ghi chú về ghế đôi
function renderCoupleNotes() {
    const container = document.getElementById('couple-seat-notes');
    const coupleRows = seatConfiguration.filter(row => {
        const seatType = allowedSeatTypes.find(type => type.id == row.seatType);
        return seatType?.is_couple;
    });
    
    if (coupleRows.length === 0) {
        container.innerHTML = '<li>Không có ghế đôi trong cấu hình này.</li>';
        return;
    }
    
    let html = '<li>Ghế đôi sẽ được tạo tự động:</li>';
    coupleRows.forEach(row => {
        const oddSeats = row.seatsCount % 2;
        const couples = Math.floor(row.seatsCount / 2);
        html += `<li>Hàng ${row.row}: ${couples} cặp ghế đôi`;
        if (oddSeats > 0) {
            html += ` + ${oddSeats} ghế đơn`;
        }
        html += '</li>';
    });
    
    container.innerHTML = html;
}

// Xác nhận tạo ghế
// Tạo ghế mới
function createSeats() {
    if (seatConfiguration.length === 0) {
        alert('Vui lòng thiết lập cấu hình ghế trước!');
        return;
    }
    
    // Render preview sơ đồ
    renderPreviewMap();
    renderPreviewSummary();
    renderCoupleNotes();
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('previewSeatsModal'));
    modal.show();
}

function confirmCreateSeats() {
    if (seatConfiguration.length === 0) {
        alert('Không có cấu hình ghế để tạo!');
        return;
    }
    
    // Đóng modal preview
    const previewModal = bootstrap.Modal.getInstance(document.getElementById('previewSeatsModal'));
    previewModal.hide();
    
    // Gửi request tạo ghế
    createSeatsRequest();
}

// Gửi request tạo ghế
function createSeatsRequest() {
    console.log('createSeatsRequest called');
    console.log('seatConfiguration:', seatConfiguration);
    
    if (seatConfiguration.length === 0) {
        alert('Không có cấu hình ghế để tạo!');
        return;
    }
    
    // Gửi request tạo ghế
    const formData = new FormData();
    
    // Lấy CSRF token từ form có sẵn hoặc tạo mới
    let csrfToken = '';
    const existingTokenInput = document.querySelector('input[name="_token"]');
    if (existingTokenInput) {
        csrfToken = existingTokenInput.value;
    } else {
        csrfToken = '{{ csrf_token() }}';
    }
    
    formData.append('_token', csrfToken);
    formData.append('room_id', roomData.id);
    
    // Chuyển đổi seatConfiguration thành format phù hợp với backend
    const seatConfigData = seatConfiguration.map((config, index) => ({
        row: index + 1,  // Gửi số thứ tự hàng: 1, 2, 3, 4, 5
        seats_per_row: config.seatsCount,
        seat_type_id: config.seatType
    }));
    
    formData.append('seat_config', JSON.stringify(seatConfigData));
    
    console.log('Sending data:', {
        room_id: roomData.id,
        seat_config: seatConfigData,
        csrf_token: csrfToken
    });
    
    fetch('{{ route("admin.seats.store-new") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert('Tạo ghế thành công!');
            location.reload();
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể tạo ghế'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi tạo ghế!');
    });
}

// Hàm chỉnh sửa sức chứa
function editCapacity() {
    document.getElementById('capacity-display').style.display = 'none';
    document.getElementById('capacity-form').style.display = 'block';
    document.getElementById('capacity-input').focus();
}

function cancelEditCapacity() {
    document.getElementById('capacity-display').style.display = 'inline';
    document.getElementById('capacity-form').style.display = 'none';
}

// Functions cho capacity editing
function editCapacity() {
    document.getElementById('capacity-form').style.display = 'block';
    document.getElementById('capacity-suggestions').style.display = 'block';
    document.getElementById('capacity-input').focus();
}

function cancelEditCapacity() {
    document.getElementById('capacity-form').style.display = 'none';
    document.getElementById('capacity-suggestions').style.display = 'none';
    document.getElementById('capacityHelp').style.display = 'none';
    document.getElementById('capacity-input').value = roomData.capacity;
}

function setCapacity(capacity) {
    document.getElementById('capacity-input').value = capacity;
    validateCapacityChange(capacity);
}

function validateCapacityChange(capacity) {
    const capacityHelp = document.getElementById('capacityHelp');
    const helpText = document.getElementById('capacityHelpText');
    
    if (roomData.roomType.hasCoupleSeats) {
        const capacityNum = parseInt(capacity);
        const coupleSeatsStr = roomData.roomType.coupleSeatsNames.join(', ');
        
        if (capacityNum % 2 !== 0) {
            helpText.innerHTML = `⚠️ Phòng "${roomData.roomType.name}" có ghế đôi (${coupleSeatsStr}), sức chứa phải là số chẵn. Đề xuất: ${capacityNum + 1} ghế.`;
            capacityHelp.className = 'mt-2 alert alert-warning py-2';
            helpText.className = 'text-warning fw-bold';
        } else {
            helpText.innerHTML = `✓ Sức chứa phù hợp cho phòng có ghế đôi (${coupleSeatsStr}).`;
            capacityHelp.className = 'mt-2 alert alert-success py-2';
            helpText.className = 'text-success fw-bold';
        }
        capacityHelp.style.display = 'block';
    } else {
        capacityHelp.style.display = 'none';
    }
}

function updateCapacity(event) {
    event.preventDefault();
    
    const newCapacity = document.getElementById('capacity-input').value;
    console.log('updateCapacity called with capacity:', newCapacity);
    
    // Validate capacity cho ghế đôi
    if (roomData.roomType.hasCoupleSeats && parseInt(newCapacity) % 2 !== 0) {
        alert(`❌ Không thể cập nhật sức chứa!\n\nPhòng "${roomData.roomType.name}" có chứa ghế đôi (${roomData.roomType.coupleSeatsNames.join(', ')}) nên sức chứa phải là số chẵn.\n\nVui lòng đổi sức chứa thành ${parseInt(newCapacity) + 1} ghế.`);
        document.getElementById('capacity-input').focus();
        document.getElementById('capacity-input').select();
        return;
    }
    
    // Lấy CSRF token từ form có sẵn hoặc tạo mới
    let csrfToken = '';
    const existingTokenInput = document.querySelector('input[name="_token"]');
    if (existingTokenInput) {
        csrfToken = existingTokenInput.value;
    } else {
        csrfToken = '{{ csrf_token() }}';
    }
    
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('capacity', newCapacity);
    
    console.log('Sending request to:', '{{ route("admin.rooms.update-capacity", $room->id) }}');
    console.log('FormData:', Array.from(formData.entries()));
    
    fetch('{{ route("admin.rooms.update-capacity", $room->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Cập nhật tất cả các element hiển thị sức chứa
            document.getElementById('capacity-display').textContent = newCapacity;
            const roomCapacityElement = document.getElementById('room-capacity');
            if (roomCapacityElement) {
                roomCapacityElement.textContent = newCapacity;
            }
            roomData.capacity = parseInt(newCapacity);
            cancelEditCapacity();
            updateSummary(); // Cập nhật lại tóm tắt
            alert('Cập nhật sức chứa thành công!');
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể cập nhật sức chứa'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật sức chứa!');
    });
}

// JavaScript cho thêm ghế đơn
// Xử lý thay đổi loại ghế để hiển thị thông tin
document.addEventListener('DOMContentLoaded', function() {
    const seatTypeSelect = document.getElementById('single_seat_type');
    const seatTypeHelp = document.getElementById('seatTypeHelp');
    
    if (seatTypeSelect && seatTypeHelp) {
        seatTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const isCouple = selectedOption.getAttribute('data-is-couple') === 'true';
            
            if (isCouple) {
                seatTypeHelp.innerHTML = '<span class="text-warning"><i class="fas fa-heart"></i> Loại ghế đôi - Sẽ tạo 2 ghế liên tiếp</span>';
            } else {
                seatTypeHelp.innerHTML = '<span class="text-info"><i class="fas fa-chair"></i> Loại ghế đơn</span>';
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
    }
    
    // Xử lý submit form thêm ghế đơn
    const addSingleSeatForm = document.getElementById('addSingleSeatForm');
    if (addSingleSeatForm) {
        addSingleSeatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Disable button và hiển thị loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
            
            fetch('{{ route("admin.seats.add-single") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset form
                    addSingleSeatForm.reset();
                    document.getElementById('seatTypeHelp').innerHTML = '';
                    
                    // Hiển thị thông báo thành công
                    alert(data.message);
                    
                    // Reload trang để cập nhật sơ đồ ghế
                    window.location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm ghế!');
            })
            .finally(() => {
                // Restore button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});
</script>

<style>
.seat-preview {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    font-size: 8px;
    border: 1px solid #ccc;
}

.couple-seat {
    border: 2px solid #ff6b6b !important;
}

.seat-header, .row-label {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 10px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.row-config {
    background-color: #f8f9fa;
}

.row-config:hover {
    background-color: #e9ecef;
}

/* Capacity warning and suggestions styling */
#capacity-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 15px;
    margin-top: 15px;
}

#suggestions {
    margin-top: 10px;
}

.suggestion-options {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.suggestion-options .btn {
    border-radius: 6px;
    font-size: 0.875rem;
    padding: 6px 12px;
    transition: all 0.2s ease;
}

.suggestion-options .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#suggestions .alert {
    border-radius: 6px;
    border-left: 4px solid;
}

#suggestions .alert-info {
    border-left-color: #0dcaf0;
    background-color: #e7f3ff;
}

#suggestions .alert-warning {
    border-left-color: #ffc107;
    background-color: #fff8e1;
}

#suggestions .alert-danger {
    border-left-color: #dc3545;
    background-color: #ffebee;
}

/* Preview seat map styling */
.seat-preview-grid {
    font-family: 'Courier New', monospace;
    display: grid !important;
    gap: 3px;
    justify-content: start;
    align-items: center;
    width: fit-content;
}

.seat-preview, .seat-header, .row-label {
    width: 30px !important;
    height: 30px !important;
    min-width: 30px;
    min-height: 30px;
    max-width: 30px;
    max-height: 30px;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-weight: bold;
    user-select: none;
    transition: transform 0.1s ease;
    box-sizing: border-box !important;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.seat-preview:hover {
    transform: scale(1.05);
    z-index: 1;
    position: relative;
}

.couple-seat {
    font-size: 12px !important;
    border: 2px solid #ff6b6b !important;
}

/* Đảm bảo grid layout không bị lệch */
.seat-preview-grid > div {
    box-sizing: border-box !important;
    width: 30px !important;
    height: 30px !important;
}

/* Empty cells để duy trì grid structure */
.seat-preview-grid > div:empty {
    min-height: 30px !important;
    min-width: 30px !important;
}
</style>
@endsection