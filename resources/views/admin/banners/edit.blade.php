@extends('layouts.admin.admin')

@section('content')

<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cập nhật Banner</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                {{-- Tiêu đề --}}
                                <div class="col-md-6">
                                    <label for="title" class="form-label">Tiêu đề</label>
                                    <input type="text" name="title" id="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        placeholder="Nhập tiêu đề banner"
                                        value="{{ old('title', $banner->title) }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Link điều hướng --}}
                                <div class="col-md-6">
                                    <label for="link_url" class="form-label">Đường dẫn</label>
                                    <input type="text" name="link_url" id="link_url"
                                        class="form-control @error('link_url') is-invalid @enderror"
                                        placeholder="Nhập link banner"
                                        value="{{ old('link_url', $banner->link_url) }}">
                                    @error('link_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Hình ảnh --}}
                                <div class="col-md-6">
                                    <label for="image" class="form-label">Hình ảnh (để trống nếu không thay đổi)</label>
                                    <input type="file" name="image" id="image"
                                        class="form-control @error('image') is-invalid @enderror">
                                    @if($banner->image_url)
                                        <img src="{{ $banner->image_url }}" alt="banner" class="img-thumbnail mt-2" style="max-height:100px;">
                                    @endif
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Vị trí hiển thị --}}
                                <div class="col-md-6">
                                    <label for="display_order" class="form-label">Vị trí hiển thị</label>
                                    <input type="number" name="display_order" id="display_order"
                                        class="form-control @error('display_order') is-invalid @enderror"
                                        placeholder="Nhập số thứ tự"
                                        value="{{ old('display_order', $banner->display_order) }}">
                                    @error('display_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Ngày bắt đầu --}}
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="form-control @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date', $banner->start_date->format('Y-m-d')) }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Ngày kết thúc --}}
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="end_date" id="end_date"
                                        class="form-control @error('end_date') is-invalid @enderror"
                                        value="{{ old('end_date', $banner->end_date->format('Y-m-d')) }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Trạng thái --}}
                                <div class="col-md-6">
                                    <label for="is_active" class="form-label">Trạng thái</label>
                                    <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                        <option value="1" {{ old('is_active', $banner->is_active) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                        <option value="0" {{ old('is_active', $banner->is_active) == 0 ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection