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
                                    <input type="text" class="form-control" value="{{ $seat->room->name }}" readonly disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Loại ghế</label>
                                    <input type="text" class="form-control" value="{{ $seat->seatType->name }}" readonly disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Hàng ghế</label>
                                    <input type="text" class="form-control" value="{{ $seat->row_char }}" readonly disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Số ghế</label>
                                    <input type="text" class="form-control" value="{{ $seat->seat_number }}" readonly disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-control" required>
                                        @foreach (\App\Enums\SeatStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $seat->status->value === $status->value ? 'selected' : '' }}>
                                                {{ ucfirst($status->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                <a href="{{ route('admin.rooms.show', $seat->room_id) }}" class="btn btn-secondary ms-2">Quay lại</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection