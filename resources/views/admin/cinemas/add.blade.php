@extends('layouts.admin.admin')

@section('content')
<div class="page-content py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 animate-fadeInUp">
                    <div class="card-header bg-gradient-primary text-white rounded-top-4 py-4 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="bi bi-building fs-3"></i>
                            </div>
                            <div>
                                <h4 class="card-title mb-0 fw-bold">Thêm rạp chiếu phim</h4>
                                <small class="text-white-75">Nhập thông tin chi tiết về rạp chiếu mới</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('admin.cinemas.store') }}" enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Tên rạp <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control floating-input @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" placeholder="Nhập tên rạp">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="city_id" class="form-label fw-semibold">Thành phố <span class="text-danger">*</span></label>
                                    <select name="city_id" id="city_id" class="form-select floating-input @error('city_id') is-invalid @enderror">
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

                            <div class="mb-4">
                                <label for="address" class="form-label fw-semibold">Địa chỉ <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" rows="2"
                                          class="form-control floating-input @error('address') is-invalid @enderror"
                                          placeholder="Nhập địa chỉ">{{ old('address') }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email liên hệ</label>
                                    <input type="email" name="email" id="email"
                                           class="form-control floating-input @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" placeholder="VD: contact@cinema.vn">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="hotline" class="form-label fw-semibold">SĐT liên hệ</label>
                                    <input type="text" name="hotline" id="hotline"
                                           class="form-control floating-input @error('hotline') is-invalid @enderror"
                                           value="{{ old('hotline') }}" placeholder="VD: 19001000">
                                    @error('hotline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="map_url" class="form-label fw-semibold">URL bản đồ</label>
                                <input type="url" name="map_url" id="map_url"
                                       class="form-control floating-input @error('map_url') is-invalid @enderror"
                                       value="{{ old('map_url') }}" placeholder="Google Maps URL">
                                @error('map_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label fw-semibold">Ảnh rạp</label>
                                <input type="file" name="image" id="image" class="form-control floating-input @error('image') is-invalid @enderror">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="opening_hours" class="form-label fw-semibold">Giờ mở cửa</label>
                                    <input type="text" name="opening_hours" id="opening_hours"
                                           class="form-control floating-input @error('opening_hours') is-invalid @enderror"
                                           value="{{ old('opening_hours') }}" placeholder="VD: 08:00 - 23:00">
                                    @error('opening_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold">Trạng thái</label>
                                    <select name="status" id="status" class="form-select floating-input @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Mô tả</label>
                                <textarea name="description" id="description" rows="3"
                                          class="form-control floating-input @error('description') is-invalid @enderror"
                                          placeholder="Mô tả rạp chiếu">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.cinemas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="bi bi-arrow-left"></i> Huỷ
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-save me-2"></i> Lưu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
}
.text-white-75 { color: rgba(255,255,255,0.85) !important; }
.icon-box {
    box-shadow: 0 4px 16px rgba(37,99,235,0.08);
}
.card {
    border-radius: 1.5rem !important;
}
.card-header {
    border-radius: 1.5rem 1.5rem 0 0 !important;
    border-bottom: none;
}
.card-body {
    border-radius: 0 0 1.5rem 1.5rem !important;
}
.floating-input {
    background: #f8fafc;
    border: 2px solid #e5e7eb;
    border-radius: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-size: 1rem;
}
.floating-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px #2563eb33;
    background: #fff;
}
.form-label {
    font-weight: 600;
    color: #2563eb;
}
.btn-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: none;
    font-weight: 600;
}
.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    box-shadow: 0 8px 24px rgba(37,99,235,0.18);
}
.btn-outline-secondary {
    border-radius: 1rem;
    font-weight: 600;
}
.invalid-feedback {
    font-size: 0.95rem;
}
.animate-fadeInUp {
    animation: fadeInUp 0.7s;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(24px);}
    to { opacity: 1; transform: translateY(0);}
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection