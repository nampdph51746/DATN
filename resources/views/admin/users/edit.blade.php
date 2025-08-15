@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-info rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chỉnh sửa khách hàng</h2>
                                <p class="mb-0 opacity-90 fs-5">Cập nhật thông tin khách hàng: {{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-info rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="fas fa-user-edit me-2"></i>
                                Chỉnh sửa
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Avatar Preview Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 sticky-top">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-image text-info"></i>
                        Xem trước ảnh đại diện
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img id="avatarPreview"
                             src="{{ $user->avatar_url ? Storage::url($user->avatar_url) : asset('assets/images/user-placeholder.png') }}"
                             alt="User Preview"
                             class="img-fluid rounded-4 shadow-lg"
                             style="width: 100%; max-width: 220px; height: 220px; object-fit: cover; border: 4px solid #fff;">
                        <!-- Upload overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 rounded-4 d-flex align-items-center justify-content-center opacity-0 transition-opacity"
                             id="uploadOverlay"
                             style="transition: opacity 0.3s ease;">
                            <div class="text-white text-center">
                                <i class="fas fa-cloud-upload-alt fs-1 mb-2"></i>
                                <p class="mb-0 fw-semibold">Thay đổi ảnh</p>
                            </div>
                        </div>
                    </div>
                    <div class="upload-area border-2 border-dashed border-info rounded-4 p-4 bg-info-subtle cursor-pointer"
                         onclick="document.getElementById('avatar').click()">
                        <div class="upload-content text-center">
                            <i class="fas fa-cloud-upload-alt text-info fs-1 mb-3"></i>
                            <h6 class="fw-bold text-info mb-2">Thay đổi ảnh đại diện</h6>
                            <p class="text-muted small mb-0">
                                Kéo thả hoặc click để chọn ảnh mới<br>
                                <em>JPG, PNG, GIF tối đa 2MB</em>
                            </p>
                        </div>
                    </div>
                    @if($user->avatar_url)
                        <div class="mt-3">
                            <div class="form-check">
                                <input type="checkbox" name="remove_avatar" id="remove_avatar" class="form-check-input" value="1">
                                <label for="remove_avatar" class="form-check-label text-danger">
                                    <i class="fas fa-trash-alt me-1"></i>Xóa ảnh hiện tại
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-grid gap-2">
                        <button type="submit" form="userEditForm" class="btn btn-info btn-lg rounded-pill">
                            <i class="fas fa-save me-2"></i>Cập nhật khách hàng
                        </button>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-warning btn-lg rounded-pill">
                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="col-xl-8 col-lg-7">
            <form id="userEditForm" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Thông tin cơ bản -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-primary-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-user text-primary"></i>
                                Thông tin cơ bản
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Tên khách hàng"
                                           required>
                                    <label for="name">
                                        <i class="fas fa-user me-2"></i>Tên khách hàng <span class="text-danger">*</span>
                                    </label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="Email"
                                           required>
                                    <label for="email">
                                        <i class="fas fa-envelope me-2"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text"
                                           name="phone_number"
                                           id="phone_number"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           value="{{ old('phone_number', $user->phone_number) }}"
                                           placeholder="Số điện thoại">
                                    <label for="phone_number">
                                        <i class="fas fa-phone me-2"></i>Số điện thoại
                                    </label>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="file"
                                       name="avatar"
                                       id="avatar"
                                       class="form-control @error('avatar') is-invalid @enderror d-none"
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                @error('avatar')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-floating">
                                    <div class="form-control d-flex align-items-center" style="height: calc(3.5rem + 2px);">
                                        <span class="text-muted" id="avatarFileName">
                                            {{ $user->avatar_url ? 'Ảnh hiện tại: ' . basename($user->avatar_url) : 'Chưa chọn file ảnh' }}
                                        </span>
                                    </div>
                                    <label>
                                        <i class="fas fa-image me-2"></i>Ảnh đại diện
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin bổ sung -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-info-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-info-circle text-info"></i>
                                Thông tin bổ sung
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date"
                                           name="date_of_birth"
                                           id="date_of_birth"
                                           class="form-control @error('date_of_birth') is-invalid @enderror"
                                           value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                                           placeholder="Ngày sinh">
                                    <label for="date_of_birth">
                                        <i class="fas fa-birthday-cake me-2"></i>Ngày sinh
                                    </label>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text"
                                           name="address"
                                           id="address"
                                           class="form-control @error('address') is-invalid @enderror"
                                           value="{{ old('address', $user->address) }}"
                                           placeholder="Địa chỉ">
                                    <label for="address">
                                        <i class="fas fa-map-marker-alt me-2"></i>Địa chỉ
                                    </label>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cài đặt -->
                <div class="card border-0 shadow-lg rounded-4 form-section">
                    <div class="card-header bg-success-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-cogs text-success"></i>
                                Cài đặt
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="customer_rank_id" id="customer_rank_id"
                                        class="form-select @error('customer_rank_id') is-invalid @enderror">
                                        <option value="">Chọn cấp bậc</option>
                                        @foreach ($customerRanks as $rank)
                                            <option value="{{ $rank->id }}" {{ old('customer_rank_id', $user->customer_rank_id) == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="customer_rank_id">
                                        <i class="fas fa-medal me-2"></i>Cấp bậc
                                    </label>
                                    @error('customer_rank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                                        <option value="">Chọn vai trò</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role', $selectedRole) == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="role">
                                        <i class="fas fa-user-tag me-2"></i>Vai trò
                                    </label>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="status" id="status"
                                        class="form-select @error('status') is-invalid @enderror">
                                        <option value="">Chọn trạng thái</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->value }}" {{ old('status', $user->status->value ?? '') == $status->value ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="status">
                                        <i class="fas fa-flag me-2"></i>Trạng thái
                                    </label>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-3 bg-light mb-3 rounded mt-3">
                    <div class="row justify-content-end g-2">
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-save me-2"></i>Lưu
                            </button>
                        </div>
                        <div class="col-lg-2">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
.form-section {
    position: relative;
    animation: fadeInUp 0.5s ease-out;
}
.section-header {
    position: relative;
}
.section-header::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, #17a2b8, #138496);
    border-radius: 2px;
}
.form-floating > .form-control,
.form-floating > .form-select {
    height: calc(3.5rem + 2px);
    font-size: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}
.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: #17a2b8;
    box-shadow: 0 0 0 3px rgba(23,162,184,0.1);
    transform: translateY(-1px);
}
.form-floating > label {
    font-weight: 600;
    color: #6c757d;
    padding: 1rem 1rem;
}
.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}
.upload-area:hover {
    background-color: rgba(23,162,184,0.15) !important;
    border-color: #17a2b8 !important;
    transform: translateY(-2px);
}
.upload-content {
    pointer-events: none;
}
.avatar-xl {
    width: 5rem;
    height: 5rem;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
}
.card {
    transition: all 0.3s ease;
    border: none;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.sticky-top {
    top: 1rem;
}
.bg-primary-subtle {
    background-color: rgba(23,162,184,0.1) !important;
}
.bg-info-subtle {
    background-color: rgba(23,162,184,0.08) !important;
}
.bg-success-subtle {
    background-color: rgba(40,167,69,0.08) !important;
}
.form-check-input:checked {
    background-color: #17a2b8;
    border-color: #17a2b8;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.form-section:nth-child(2) { animation-delay: 0.1s; }
.form-section:nth-child(3) { animation-delay: 0.2s; }
.form-section:nth-child(4) { animation-delay: 0.3s; }
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    .card-body {
        padding: 1.5rem !important;
    }
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview functionality
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarFileName = document.getElementById('avatarFileName');
    const uploadOverlay = document.getElementById('uploadOverlay');
    const removeAvatarCheckbox = document.getElementById('remove_avatar');

    // Show overlay on hover
    avatarPreview.addEventListener('mouseenter', function() {
        uploadOverlay.style.opacity = '1';
    });
    avatarPreview.addEventListener('mouseleave', function() {
        uploadOverlay.style.opacity = '0';
    });
    // Click on image to trigger file input
    avatarPreview.addEventListener('click', function() {
        avatarInput.click();
    });
    // Handle file selection
    avatarInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                avatarFileName.textContent = file.name;
                if (removeAvatarCheckbox) {
                    removeAvatarCheckbox.checked = false;
                }
            };
            reader.readAsDataURL(file);
        } else {
            avatarPreview.src = "{{ $user->avatar_url ? Storage::url($user->avatar_url) : asset('assets/images/user-placeholder.png') }}";
            avatarFileName.textContent = "{{ $user->avatar_url ? 'Ảnh hiện tại: ' . basename($user->avatar_url) : 'Chưa chọn file ảnh' }}";
            if (file) {
                alert('Vui lòng chọn file ảnh hợp lệ (jpeg, png, jpg, gif).');
                avatarInput.value = '';
            }
        }
    });
    // Handle remove avatar checkbox
    if (removeAvatarCheckbox) {
        removeAvatarCheckbox.addEventListener('change', function() {
            if (this.checked) {
                avatarPreview.src = "{{ asset('assets/images/user-placeholder.png') }}";
                avatarFileName.textContent = 'Ảnh sẽ bị xóa';
                avatarInput.value = '';
            } else {
                avatarPreview.src = "{{ $user->avatar_url ? Storage::url($user->avatar_url) : asset('assets/images/user-placeholder.png') }}";
                avatarFileName.textContent = "{{ $user->avatar_url ? 'Ảnh hiện tại: ' . basename($user->avatar_url) : 'Chưa chọn file ảnh' }}";
            }
        });
    }
    // Drag and drop functionality
    const uploadArea = document.querySelector('.upload-area');
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    uploadArea.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            avatarInput.files = files;
            avatarInput.dispatchEvent(new Event('change'));
        }
    });
});
</script>
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection