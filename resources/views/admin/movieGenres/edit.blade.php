
@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sửa Thể Loại Phim</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.genres.update', $genre->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên thể loại <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $genre->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả (tuỳ chọn)</label>
                            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $genre->description) }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
