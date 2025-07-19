@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <!-- Sidebar thông tin -->
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ asset('assets/images/seat-icon.png') }}" alt="Seat Icon" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Chỉnh sửa hàng loạt</h4>
                        <p class="text-muted">Số ghế đã chọn: {{ $seats->count() }}</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="submit" form="bulkSeatForm" class="btn btn-primary w-100" {{ $seats->isEmpty() ? 'disabled' : '' }}>Lưu</button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.seats.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form chỉnh sửa ghế -->
        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cập nhật thông tin nhiều ghế</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($seats->isEmpty())
                        <div class="alert alert-warning">
                            Không có ghế nào được chọn để chỉnh sửa.
                        </div>
                    @else
                        <form id="bulkSeatForm" action="{{ route('admin.seats.bulkUpdate') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <h5 class="text-dark fw-semibold">Tùy chọn áp dụng cho tất cả ghế</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
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
                                            </select>
                                            @error('status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

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
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection