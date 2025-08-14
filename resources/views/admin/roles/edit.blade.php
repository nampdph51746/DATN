@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    @include('admin.partials.notifications')

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-warning rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="fas fa-user-shield fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chỉnh sửa vai trò</h2>
                                <p class="mb-0 opacity-90 fs-5">Cập nhật thông tin vai trò: {{ $role->name }}</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-warning rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="fas fa-edit me-2"></i>
                                Chỉnh sửa
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="fas fa-user-shield" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-xl-8 col-lg-7 mx-auto">
            <form id="roleEditForm" action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Thông tin cơ bản -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-primary-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-user-shield text-primary"></i>
                                Thông tin vai trò
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
                                           value="{{ old('name', $role->name) }}"
                                           placeholder="Tên vai trò"
                                           required>
                                    <label for="name">
                                        <i class="fas fa-user-shield me-2"></i>Tên vai trò <span class="text-danger">*</span>
                                    </label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách quyền -->
                <div class="card border-0 shadow-lg rounded-4 mb-4 form-section">
                    <div class="card-header bg-info-subtle border-0 py-4">
                        <div class="section-header">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-key text-info"></i>
                                Quyền hạn
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            @foreach ($groupedPermissions as $group => $permissions)
                                <div class="col-md-3 mb-3">
                                    <h6 class="fw-bold text-primary text-uppercase mb-2">
                                        <i class="fas fa-folder me-1"></i>{{ str_replace(['-', '_'], ' ', $group) }}
                                    </h6>
                                    @foreach ($permissions as $permission)
                                        @php
                                            $parts = explode('.', $permission->name);
                                            $action = $parts[1] ?? $permission->name;
                                        @endphp
                                        <div class="form-check mb-2">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                name="permissions[]" value="{{ $permission->name }}"
                                                id="perm_{{ $permission->id }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                <i class="fas fa-key me-1"></i>{{ $action }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Nút thao tác -->
                <div class="card border-0 shadow-lg rounded-4 form-section">
                    <div class="card-body p-4 text-end">
                        <button type="submit" class="btn btn-warning btn-lg rounded-pill me-2">
                            <i class="fas fa-save me-2"></i>Cập nhật vai trò
                        </button>
                        <a href="{{ route('roles.show', $role->id) }}" class="btn btn-outline-info btn-lg rounded-pill me-2">
                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
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
    background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
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
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.1);
    transform: translateY(-1px);
}
.form-floating > label {
    font-weight: 600;
    color: #6c757d;
    padding: 1rem 1rem;
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
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}
.bg-warning-subtle {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}
.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}
.bg-success-subtle {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}
.form-check-input:checked {
    background-color: var(--bs-success);
    border-color: var(--bs-success);
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
@endsection