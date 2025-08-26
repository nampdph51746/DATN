@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    @include('admin.partials.notifications')
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('assets/images/room-type-icon.png') }}" alt="Room Type Icon" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Cập nhật loại phòng chiếu {{ $roomType->name }}</h4>
                        <p class="text-muted">Chỉnh sửa thông tin loại phòng chiếu trong hệ thống.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="roomTypeEditForm" class="btn btn-primary w-100">Lưu</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.room-types.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin loại phòng</h4>
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
                    <form id="roomTypeEditForm" action="{{ route('admin.room-types.update', $roomType->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên loại phòng <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $roomType->name) }}" placeholder="Nhập tên loại phòng">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Nhập mô tả loại phòng (tùy chọn)">{{ old('description', $roomType->description) }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="active" {{ old('status', $roomType->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $roomType->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
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