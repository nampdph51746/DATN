@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.genres.index') }}">Thể loại phim</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <iconify-icon icon="solar:tag-bold-duotone" class="me-2"></iconify-icon>
                    Thêm thể loại phim mới
                </h4>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form action="{{ route('admin.genres.store') }}" method="POST">
                @csrf
                
                <!-- Thông tin cơ bản -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2 text-primary"></iconify-icon>
                            Thông tin thể loại
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Tên thể loại <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ví dụ: Hành động, Kinh dị, Hài hước..."
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Tên thể loại phim sẽ hiển thị cho khán giả</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Mô tả thể loại</label>
                            <textarea name="description" id="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="4"
                                      placeholder="Mô tả chi tiết về thể loại phim này...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mô tả đặc điểm và nội dung của thể loại phim</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Action Buttons -->
                        <div class="card">
                            <div class="card-footer bg-light">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <iconify-icon icon="solar:diskette-bold-duotone" class="me-1"></iconify-icon>
                                            Lưu thể loại
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary w-100">
                                            <iconify-icon icon="solar:arrow-left-bold-duotone" class="me-1"></iconify-icon>
                                            Quay lại
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <iconify-icon icon="solar:lightbulb-bolt-bold-duotone" class="me-2 text-warning"></iconify-icon>
                                    Các thể loại phổ biến
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0 small text-muted">
                                    <li class="mb-2">
                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-success me-1"></iconify-icon>
                                        Hành động, Phiêu lưu, Kinh dị
                                    </li>
                                    <li class="mb-2">
                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-success me-1"></iconify-icon>
                                        Hài hước, Lãng mạn, Tài liệu
                                    </li>
                                    <li class="mb-0">
                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-success me-1"></iconify-icon>
                                        Khoa học viễn tưởng, Anime
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
.page-title-box {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 10px;
    color: white;
    margin-bottom: 0;
}

.page-title-box .breadcrumb {
    background: transparent;
    margin-bottom: 0;
}

.page-title-box .breadcrumb-item a {
    color: rgba(255,255,255,0.8);
}

.page-title-box .breadcrumb-item.active {
    color: white;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0,0,0,.125);
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.btn-primary {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
}
</style>
@endsection
