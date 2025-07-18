@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('assets/images/seat-icon.png') }}" alt="Seat Icon" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Thêm ghế cho phòng: {{ $room->name }}</h4>
                        <p class="text-muted">Chọn loại ghế, tỷ lệ, và cấu hình số ghế mỗi hàng để tạo ghế tự động.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="seatForm" class="btn btn-primary w-100">Thêm ghế</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-outline-secondary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin ghế</h4>
                </div>
                <div class="card-body">
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
                    <form id="seatForm" action="{{ route('admin.seats.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phòng chiếu</label>
                                    <p class="form-control-static">{{ $room->name }} (Sức chứa: {{ $room->capacity }})</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="seat_type_id" class="form-label">Loại ghế</label>
                                    <select name="seat_type_id" id="seat_type_id" class="form-control" required>
                                        <option value="">Chọn loại ghế</option>
                                        @foreach ($seatTypes as $seatType)
                                            <option value="{{ $seatType->id }}">{{ $seatType->name }} ({{ $seatType->price_modifier }})</option>
                                        @endforeach
                                    </select>
                                    @error('seat_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tỷ lệ loại ghế (%)</label>
                            @foreach ($seatTypes as $seatType)
                                <div class="flex items-center space-x-2 mb-2">
                                    <label for="seat_type_percentages_{{ $seatType->id }}" class="form-label">{{ $seatType->name }}</label>
                                    <input type="number" name="seat_type_percentages[{{ $seatType->id }}]" id="seat_type_percentages_{{ $seatType->id }}" 
                                           class="form-control w-20" min="0" max="100" 
                                           value="{{ $seatPercentages[$seatType->id] ?? 0 }}" required>
                                    <span>%</span>
                                </div>
                            @endforeach
                            @error('seat_type_percentages')
                                <span class="text-danger">{{ $message }}</span>
                            @endfor
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="seats_per_row" class="form-label">Số ghế mỗi hàng</label>
                                    <input type="number" name="seats_per_row" id="seats_per_row" class="form-control" min="1" value="1" required>
                                    @error('seats_per_row')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endfor
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="min_seats_per_row" class="form-label">Số ghế tối thiểu mỗi hàng</label>
                                    <input type="number" name="min_seats_per_row" id="min_seats_per_row" class="form-control" min="1" value="1" required>
                                    @error('min_seats_per_row')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Sơ đồ ghế hiện có</label>
                                    <div id="existing-seats" class="border p-3 rounded">
                                        @if ($seats->isEmpty())
                                            <p>Phòng chưa có ghế nào. Số ghế có thể thêm: {{ $room->capacity }}</p>
                                        @else
                                            <div class="seat-map" style="display: grid; gap: 5px; grid-template-columns: repeat({{ $maxSeatsPerRow + 1 }}, 40px); max-width: 600px;">
                                                <div class="seat"></div>
                                                @for ($i = 1; $i <= $maxSeatsPerRow; $i++)
                                                    <div class="seat seat-number-label">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                                                @endfor
                                                @foreach ($rows as $row)
                                                    <div class="seat seat-row-label">{{ $row }}</div>
                                                    @for ($i = 1; $i <= $maxSeatsPerRow; $i++)
                                                        @php
                                                            $seatNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                            $seat = $seats->firstWhere(['row_char' => $row, 'seat_number' => $seatNumber]);
                                                        @endphp
                                                        @if ($seat)
                                                            <div class="seat 
                                                                {{ $seat->seatType->name === 'VIP' ? 'seat-vip' : '' }}
                                                                {{ $seat->seatType->name === 'Sweetbox' ? 'seat-sweetbox' : '' }}
                                                                {{ $seat->status === 'booked' ? 'seat-booked' : '' }}
                                                                {{ $seat->status === 'maintenance' ? 'seat-maintenance' : '' }}"
                                                                 title="{{ $seat->row_char }}{{ $seat->seat_number }} ({{ $seat->seatType->name }}, {{ $seat->status }})">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Debug Data -->
                        <div class="mb-3">
                            <label class="form-label">Debug Data</label>
                            <pre>{{ print_r($seatTypes, true) }}</pre>
                            <pre>{{ print_r($seatPercentages, true) }}</pre>
                            <pre>{{ print_r($seats, true) }}</pre>
                        </div>
                    </form>
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
        background-color: #28a745;
        border-radius: 4px;
        font-size: 12px;
        text-align: center;
        color: white;
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
    .seat-maintenance {
        background-color: #6c757d;
        color: white;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Kiểm tra tổng tỷ lệ phần trăm
        document.getElementById('seatForm').addEventListener('submit', function(e) {
            const percentages = document.querySelectorAll('input[name^="seat_type_percentages"]');
            let totalPercentage = 0;
            percentages.forEach(input => {
                totalPercentage += parseFloat(input.value) || 0;
            });
            if (Math.abs(totalPercentage - 100) > 0.01) {
                e.preventDefault();
                alert('Tổng tỷ lệ loại ghế phải bằng 100%.');
            }

            // Kiểm tra số ghế và số hàng
            const minSeatsPerRow = parseInt(document.getElementById('min_seats_per_row').value) || 1;
            const seatsPerRow = parseInt(document.getElementById('seats_per_row').value) || 1;
            const totalSeatsToAdd = {{ $room->capacity - $seats->count() }};
            if (seatsPerRow < minSeatsPerRow) {
                e.preventDefault();
                alert('Số ghế mỗi hàng phải lớn hơn hoặc bằng số ghế tối thiểu.');
            }
        });
    });
</script>
@endpush

@endsection