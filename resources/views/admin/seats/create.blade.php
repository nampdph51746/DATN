@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('assets/images/seat-icon.png') }}" alt="Seat Icon" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Thêm ghế hàng loạt</h4>
                        <p class="text-muted">Chọn phòng, loại ghế, và cấu hình số hàng/số ghế mỗi hàng để tạo ghế tự động.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="seatForm" class="btn btn-primary w-100">Thêm ghế</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.seats.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
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
                    <form id="seatForm" action="{{ route('admin.seats.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="room_id" class="form-label">Phòng chiếu</label>
                                    <select name="room_id" id="room_id" class="form-control" required>
                                        <option value="">Chọn phòng chiếu</option>
                                        @foreach ($rooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
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
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="rows" class="form-label">Số hàng</label>
                                    <input type="number" name="rows" id="rows" class="form-control" min="1" value="1" required>
                                    @error('rows')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="seats_per_row" class="form-label">Số ghế mỗi hàng</label>
                                    <input type="number" name="seats_per_row" id="seats_per_row" class="form-control" min="1" value="1" required>
                                    @error('seats_per_row')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection