@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Chỉnh sửa trạng thái phòng: {{ $room->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.rooms.updateStatus', $room->id) }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng Thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" {{ $room->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                <option value="full" {{ $room->status == 'full' ? 'selected' : '' }}>Đã đầy</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection