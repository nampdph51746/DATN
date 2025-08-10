@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">Thêm Giới Hạn Độ Tuổi</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.age_limits.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên giới hạn <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên giới hạn" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="min_age" class="form-label">Độ tuổi tối thiểu</label>
                            <input type="number" id="min_age" name="min_age" class="form-control" placeholder="Nhập độ tuổi tối thiểu" value="{{ old('min_age') }}">
                            @error('min_age')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả (tuỳ chọn)</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('admin.age_limits.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
