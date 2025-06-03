@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="assets/images/seat-icon.png" alt="Seat Icon" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Cập nhật ghế {{ $seat->row_char . $seat->seat_number }}</h4>
                        <p class="text-muted">Chỉnh sửa thông tin ghế ngồi trong phòng {{ $seat->room->name }}.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="seatEditForm" class="btn btn-primary w-100">Lưu</button>
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="seatEditForm" action="{{ route('admin.seats.update', $seat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phòng chiếu</label>
                                    <p class="form-control-static">{{ $seat->room->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="seat_type_id" class="form-label">Loại ghế</label>
                                    <select name="seat_type_id" id="seat_type_id" class="form-control" required>
                                        @foreach ($seatTypes as $seatType)
                                            <option value="{{ $seatType->id }}" {{ $seat->seat_type_id == $seatType->id ? 'selected' : '' }}>{{ $seatType->name }}</option>
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
                                    <label class="form-label">Hàng ghế</label>
                                    <p class="form-control-static">{{ $seat->row_char }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Số ghế</label>
                                    <p class="form-control-static">{{ $seat->seat_number }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="available" {{ $seat->status == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="booked" {{ $seat->status == 'booked' ? 'selected' : '' }}>Booked</option>
                                        <option value="sold" {{ $seat->status == 'sold' ? 'selected' : '' }}>Sold</option>
                                    </select>
                                    @error('status')
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