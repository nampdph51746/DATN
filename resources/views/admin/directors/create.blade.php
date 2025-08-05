@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    @include('admin.partials.notifications')
    
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img id="imagePreview" src="{{ asset('assets/images/director-placeholder.png') }}" alt="Director Image" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Thêm đạo diễn mới</h4>
                        <p class="text-muted">Nhập thông tin để thêm một đạo diễn mới vào hệ thống.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="directorCreateForm" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-save fs-18"></i> Lưu
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.directors.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-x fs-18"></i> Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thêm đạo diễn mới</h4>
                </div>
                <div class="card-body">
                    <form id="directorCreateForm" action="{{ route('admin.directors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên đạo diễn <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="255">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">Ngày sinh</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Quốc tịch</label>
                                    <input type="text" name="nationality" id="nationality" class="form-control @error('nationality') is-invalid @enderror" value="{{ old('nationality') }}" placeholder="Ví dụ: Việt Nam, Mỹ, Nhật Bản...">
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Ảnh đạo diễn</label>
                                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="biography" class="form-label">Tiểu sử</label>
                                    <textarea name="biography" id="biography" class="form-control @error('biography') is-invalid @enderror" rows="5" placeholder="Nhập tiểu sử của đạo diễn...">{{ old('biography') }}</textarea>
                                    @error('biography')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">Hoạt động</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Thêm đạo diễn</button>
                            <a href="{{ route('admin.directors.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Xem trước ảnh
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "{{ asset('assets/images/director-placeholder.png') }}";
        if (file) {
            alert('Vui lòng chọn file ảnh hợp lệ (jpeg, png, jpg, gif).');
        }
    }
});
</script>
@endsection
