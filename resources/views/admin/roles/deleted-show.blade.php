@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-gradient-info text-white d-flex align-items-center justify-content-between rounded-top-4 py-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur d-flex align-items-center justify-content-center">
                            <span class="avatar-title rounded-circle text-info">
                                <i class="fas fa-user-shield fs-2"></i>
                            </span>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Chi tiết vai trò đã xóa</h3>
                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2 shadow">
                                <i class="fas fa-trash-alt me-1"></i> Đã xóa lúc: {{ $role->deleted_at ? \Carbon\Carbon::parse($role->deleted_at)->format('d/m/Y H:i') : '-' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('roles.deleted') }}" class="btn btn-outline-light rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                    </div>
                </div>
                <div class="card-body py-4">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-semibold text-dark" style="width: 30%;">
                                    <i class="fas fa-user-tag me-2 text-primary"></i>Tên vai trò:
                                </td>
                                <td class="fw-bold text-dark">{{ $role->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-dark">
                                    <i class="fas fa-hashtag me-2 text-warning"></i>ID:
                                </td>
                                <td class="fw-bold text-warning">{{ $role->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-dark align-top">
                                    <i class="fas fa-align-left me-2 text-info"></i>Mô tả:
                                </td>
                                <td class="text-dark" style="word-break: break-word; max-height: 150px; overflow-y: auto;">
                                    {{ $role->description ?: '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-dark">
                                    <i class="fas fa-calendar-plus me-2 text-success"></i>Ngày tạo:
                                </td>
                                <td class="text-dark">{{ $role->created_at ? \Carbon\Carbon::parse($role->created_at)->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-dark">
                                    <i class="fas fa-calendar-check me-2 text-secondary"></i>Ngày cập nhật:
                                </td>
                                <td class="text-dark">{{ $role->updated_at ? \Carbon\Carbon::parse($role->updated_at)->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-dark">
                                    <i class="fas fa-trash-alt me-2 text-danger"></i>Ngày xóa:
                                </td>
                                <td class="text-dark">{{ $role->deleted_at ? \Carbon\Carbon::parse($role->deleted_at)->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('roles.deleted') }}" class="btn btn-secondary btn-lg rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
.avatar-xl {
    width: 4rem;
    height: 4rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 2rem;
}
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}
.bg-info-subtle {
    background-color: rgba(6, 182, 212, 0.1) !important;
    color: #17a2b8 !important;
}
.card {
    border: none;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    border-radius: 1.5rem;
    transition: all 0.3s ease;
}
.card:hover {
    box-shadow: 0 12px 30px rgba(0,0,0,0.13);
    transform: translateY(-2px);
}
.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    font-size: 1.05rem;
}
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}
.btn {
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-1px);
}
@media (max-width: 768px) {
    .container-xxl {
        padding: 1rem !important;
    }
    .card-body {
        padding: 1.5rem !important;
    }
    .avatar-xl {
        width: 3rem;
        height: 3rem;
        font-size: 1.2rem;
    }
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}
</style>
@endsection
