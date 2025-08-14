@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <!-- Sidebar thông tin -->
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chair fa-3x text-primary mb-3"></i>
                    <div class="mt-3">
                        <h4>Chỉnh sửa hàng loạt</h4>
                        <p class="text-muted">Phòng: {{ $room->name }}</p>
                        <div class="row text-center">
                            <div class="col-12">
                                <p class="mb-1"><strong>Tổng ghế được chọn:</strong></p>
                                <h5 class="text-primary">{{ $finalSeats->count() }} ghế</h5>
                                @if($finalSeats->count() > count(request('seat_ids', [])))
                                    <small class="text-info">
                                        <i class="fas fa-info-circle"></i>
                                        Đã tự động thêm ghế đôi liên quan
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-6">
<<<<<<< Updated upstream
                            <button type="submit" form="bulkSeatForm" class="btn btn-primary w-100" {{ $seats->isEmpty() ? 'disabled' : '' }}>Lưu</button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.seats.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
=======
                            <button type="submit" form="bulkSeatForm" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times"></i> Hủy
                            </a>
>>>>>>> Stashed changes
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form chỉnh sửa ghế -->
        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-edit"></i> Cập nhật thông tin hàng loạt
                    </h4>
                </div>
                <div class="card-body">
                 

<<<<<<< Updated upstream
                    @if ($seats->isEmpty())
                        <div class="alert alert-warning">
                            Không có ghế nào được chọn để chỉnh sửa.
                        </div>
                    @else
                        <form id="bulkSeatForm" action="{{ route('admin.seats.bulkUpdate') }}" method="POST">
=======
                    @if ($finalSeats->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Không có ghế nào được chọn. Vui lòng quay lại và chọn ít nhất một ghế.
                        </div>
                    @else
                        <form id="bulkSeatForm" action="{{ route('admin.seats.update-bulk') }}" method="POST">
>>>>>>> Stashed changes
                            @csrf
                            @method('PUT')

                            <!-- Tùy chọn cập nhật -->
                            <div class="mb-4">
                                <h5 class="text-dark fw-semibold">
                                    <i class="fas fa-cogs"></i> Tùy chọn áp dụng
                                </h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
<<<<<<< Updated upstream
                                            <label class="form-label">Loại ghế mới</label>
                                            <select name="seat_type_id" class="form-control">
                                                <option value="">-- Không thay đổi --</option>
                                                @foreach ($seatTypes as $seatType)
                                                    <option value="{{ $seatType->id }}">{{ $seatType->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('seat_type_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Trạng thái mới</label>
                                            <select name="status" class="form-control">
                                                <option value="">-- Không thay đổi --</option>
                                                <option value="available">Available</option>
                                                <option value="booked">Booked</option>
                                                <option value="sold">Sold</option>
                                                <option value="broken">Broken</option>
=======
                                            <label class="form-label">
                                                <i class="fas fa-toggle-on"></i> Trạng thái mới
                                            </label>
                                            <select name="status" class="form-control" required>
                                                <option value="">-- Chọn trạng thái --</option>
                                                @foreach($seatStatuses as $status)
                                                    <option value="{{ $status->value }}">
                                                        {{ ucfirst($status->value) }}
                                                        @if($status->value === 'available')
                                                            (Có thể đặt)
                                                        @elseif($status->value === 'booked')
                                                            (Đã đặt)
                                                        @elseif($status->value === 'maintenance')
                                                            (Bảo trì)
                                                        @endif
                                                    </option>
                                                @endforeach
>>>>>>> Stashed changes
                                            </select>
                                            @error('status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

<<<<<<< Updated upstream
                            <h5 class="text-dark fw-medium">Danh sách ghế được cập nhật</h5>
                            <div class="row">
                                @foreach ($seats as $index => $seat)
                                    <input type="hidden" name="seat_ids[]" value="{{ $seat->id }}">
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3">
                                            <strong>{{ $seat->row_char }}{{ $seat->seat_number }}</strong><br>
                                            Phòng: {{ $seat->room->name ?? 'N/A' }}<br>
                                            Hiện tại: 
                                            <span class="badge bg-info">{{ $seat->seatType->name ?? 'Không rõ' }}</span>, 
                                            <span class="badge bg-secondary">{{ ucfirst($seat->status->value) }}</span>
                                        </div>
                                    </div>
                                @endforeach
=======
                            <!-- Danh sách ghế -->
                            <div class="mb-4">
                                <h5 class="text-dark fw-semibold">
                                    <i class="fas fa-list"></i> Danh sách ghế được cập nhật
                                </h5>
                                
                                @php
                                    $coupleSeats = $finalSeats->filter(function($seat) {
                                        return $seat->seatType && str_contains(strtolower($seat->seatType->name), 'couple');
                                    });
                                    $regularSeats = $finalSeats->diff($coupleSeats);
                                @endphp

                                @if($coupleSeats->count() > 0)
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-heart"></i>
                                        <strong>Lưu ý:</strong> Có {{ $coupleSeats->count() }} ghế đôi trong danh sách. 
                                        Khi cập nhật ghế đôi, cả cặp ghế sẽ được cập nhật cùng nhau.
                                    </div>
                                @endif

                                <div class="seat-preview border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    @foreach ($finalSeats->groupBy('row_char')->sortKeys() as $rowChar => $seatsInRow)
                                        <div class="mb-2">
                                            <strong class="text-primary">Hàng {{ $rowChar }}:</strong>
                                            <div class="d-inline-block ms-2">
                                                @foreach ($seatsInRow->sortBy('seat_number') as $seat)
                                                    <input type="hidden" name="seat_ids[]" value="{{ $seat->id }}">
                                                    
                                                    @php
                                                        $isCouple = $seat->seatType && str_contains(strtolower($seat->seatType->name), 'couple');
                                                        $badgeClass = $isCouple ? 'bg-danger' : 'bg-primary';
                                                        $icon = $isCouple ? 'fas fa-heart' : 'fas fa-chair';
                                                    @endphp
                                                    
                                                    <span class="badge {{ $badgeClass }} mx-1 mb-1" title="{{ $seat->seatType->name ?? 'Unknown' }}">
                                                        <i class="{{ $icon }}"></i>
                                                        {{ $seat->row_char }}{{ $seat->seat_number }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="fas fa-chair text-primary"></i> Ghế thường: {{ $regularSeats->count() }}
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="fas fa-heart text-danger"></i> Ghế đôi: {{ $coupleSeats->count() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Xác nhận -->
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Cảnh báo:</strong> Thao tác này sẽ cập nhật tất cả {{ $finalSeats->count() }} ghế đã chọn. 
                                Vui lòng kiểm tra kỹ trước khi thực hiện.
>>>>>>> Stashed changes
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bulkSeatForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const seatCount = {{ $finalSeats->count() }};
            const coupleCount = {{ $coupleSeats->count() ?? 0 }};
            
            let message = `Bạn có chắc chắn muốn cập nhật ${seatCount} ghế?`;
            if (coupleCount > 0) {
                message += `\n\nLưu ý: Có ${coupleCount} ghế đôi sẽ được cập nhật theo cặp.`;
            }
            
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endsection