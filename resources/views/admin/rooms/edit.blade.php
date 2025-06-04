@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3">
                        <h4>Chỉnh Sửa Phòng Chiếu</h4>
                        <div class="row">
                            <div class="col-lg-6 col-6">
                                <p class="mb-1 mt-2">Rạp Chiếu:</p>
                                <h5 class="mb-0">{{ $cinemas->count() }} rạp</h5>
                            </div>
                            <div class="col-lg-6 col-6">
                                <p class="mb-1 mt-2">Loại Phòng:</p>
                                <h5 class="mb-0">{{ $roomTypes->count() }} loại</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="room-edit-form" class="btn btn-outline-secondary w-100">Cập Nhật</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-primary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông Tin Phòng Chiếu</h4>
                </div>
                <div class="card-body">
                    <form id="room-edit-form" action="{{ route('admin.rooms.update', $room->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="cinema_id" class="form-label">Rạp Chiếu</label>
                                    <select class="form-control" id="cinema_id" name="cinema_id" required>
                                        <option value="">Chọn Rạp Chiếu</option>
                                        @foreach ($cinemas as $cinema)
                                            <option value="{{ $cinema->id }}" {{ $room->cinema_id == $cinema->id ? 'selected' : '' }}>{{ $cinema->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('cinema_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="room_type_id" class="form-label">Loại Phòng</label>
                                    <select class="form-control" id="room_type_id" name="room_type_id">
                                        <option value="">Chọn Loại Phòng</option>
                                        @foreach ($roomTypes as $roomType)
                                            <option value="{{ $roomType->id }}" {{ $room->room_type_id == $roomType->id ? 'selected' : '' }}>{{ $roomType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('room_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên Phòng</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên phòng" value="{{ old('name', $room->name) }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Sức Chứa</label>
                                    <input type="number" id="capacity" name="capacity" class="form-control" placeholder="Nhập sức chứa" value="{{ old('capacity', $room->capacity) }}" required>
                                    @error('capacity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng Thái</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active" @selected($room->status == 'active')>Hoạt động</option>
                                        <option value="maintenance" @selected($room->status == 'maintenance')>Bảo trì</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-0">
                                    <label for="description" class="form-label">Mô Tả</label>
                                    <textarea class="form-control bg-light-subtle" id="description" name="description" rows="7" placeholder="Nhập mô tả phòng">{{ old('description', $room->description) }}</textarea>
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