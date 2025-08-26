@extends('layouts.admin.admin')

@section('content')

<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-9 col-lg-8 ">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Banner</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                {{-- Tiêu đề --}}
                                <div class="col-md-6">
                                    <label for="title" class="form-label">Tiêu đề</label>
                                    <input type="text" name="title" id="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        placeholder="Nhập tiêu đề banner"
                                        value="{{ old('title') }}">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Link điều hướng --}}
                                <div class="col-md-6">
                                    <label for="link" class="form-label">Đường dẫn</label>
                                    <input type="text" name="link" id="link"
                                        class="form-control @error('link') is-invalid @enderror"
                                        placeholder="Nhập link banner"
                                        value="{{ old('link') }}">
                                    @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Hình ảnh --}}
                                <div class="col-md-6">
                                    <label for="image" class="form-label">Hình ảnh</label>
                                    <input type="file" name="image" id="image"
                                        class="form-control @error('image') is-invalid @enderror">
                                    @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Vị trí hiển thị --}}
                                <div class="col-md-6">
                                    <label for="position" class="form-label">Vị trí hiển thị</label>
                                    <input type="number" name="position" id="position"
                                        class="form-control @error('position') is-invalid @enderror"
                                        placeholder="Nhập số thứ tự"
                                        value="{{ old('position') }}">
                                    @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Ngày bắt đầu --}}
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="form-control @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date', date('Y-m-d')) }}">
                                    @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Ngày kết thúc --}}
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="end_date" id="end_date"
                                        class="form-control @error('end_date') is-invalid @enderror"
                                        value="{{ old('end_date', date('Y-m-d', strtotime('+1 month'))) }}">
                                    @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Trạng thái --}}
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Hiển thị</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection