@extends('layouts.admin.admin')

@section('content')

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-xl-10 col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm rạp chiếu phim</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.cinemas.store') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Tên rạp & Thành phố --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Tên rạp</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" placeholder="Nhập tên rạp">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="city_id" class="form-label">Thành phố</label>
                                    <select name="city_id" id="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                        <option value="">-- Chọn thành phố --</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Địa chỉ --}}
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <textarea name="address" id="address" rows="2"
                                          class="form-control @error('address') is-invalid @enderror"
                                          placeholder="Nhập địa chỉ">{{ old('address') }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Email & Hotline --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email liên hệ</label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" placeholder="VD: contact@cinema.vn">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="hotline" class="form-label">SĐT liên hệ</label>
                                    <input type="text" name="hotline" id="hotline"
                                           class="form-control @error('hotline') is-invalid @enderror"
                                           value="{{ old('hotline') }}" placeholder="VD: 19001000">
                                    @error('hotline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- URL bản đồ --}}
                            <div class="mb-3">
                                <label for="map_url" class="form-label">URL bản đồ</label>
                                <input type="url" name="map_url" id="map_url"
                                       class="form-control @error('map_url') is-invalid @enderror"
                                       value="{{ old('map_url') }}" placeholder="Google Maps URL">
                                @error('map_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- URL ảnh rạp --}}
                            <div class="mb-3">
                                <label for="image" class="form-label">Ảnh rạp</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Giờ mở cửa & Trạng thái --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="opening_hours" class="form-label">Giờ mở cửa</label>
                                    <input type="text" name="opening_hours" id="opening_hours"
                                           class="form-control @error('opening_hours') is-invalid @enderror"
                                           value="{{ old('opening_hours') }}" placeholder="VD: 08:00 - 23:00">
                                    @error('opening_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Mô tả --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea name="description" id="description" rows="3"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Mô tả rạp chiếu">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Nút hành động --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.cinemas.index') }}" class="btn btn-outline-secondary">Huỷ</a>
                                <button type="submit" class="btn btn-primary">Lưu</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection