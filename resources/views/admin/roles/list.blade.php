@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card overflow-hiddenCoupons shadow-sm border-0">
            <div class="card-header bg-light-subtle p-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">Quản lý Vai trò</h5>

                    <form method="GET" action="{{ route('roles.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm"
                                    value="{{ request('keyword') }}">
                            </div>

                            <div class="col-md-4">
                                <select name="created_order" class="form-select">
                                    <option value="">Thứ tự tạo</option>
                                    <option value="desc" {{ request('created_order') == 'desc' ? 'selected' : '' }}>Mới nhất
                                    </option>
                                    <option value="asc" {{ request('created_order') == 'asc' ? 'selected' : '' }}>Cũ nhất
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-5 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel-fill me-1"></i> Lọc
                                </button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary w-100">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Đặt lại
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover table-centered">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>Tên Vai Trò</th>
                                <th>ID</th>
                                <th>Ngày Tạo</th>
                                <th>Ngày Cập Nhật</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->created_at }}</td>
                                    <td>{{ $role->updated_at }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-light btn-sm"
                                                title="Xem chi tiết">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>

                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-soft-primary btn-sm"
                                                title="Chỉnh sửa">
                                                <iconify-icon icon="solar:pen-2-broken"
                                                    class="align-middle fs-18"></iconify-icon>
                                            </a>

                                            <form action="{{ route('roles.softDelete', $role->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc muốn xóa vai trò này?')"
                                                    title="Xóa mềm">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Không có vai trò nào phù hợp.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row g-0 align-items-center justify-content-between text-center text-sm-start p-3 border-top">
                <div class="col-sm">
                    <div class="text-muted">
                        Hiển thị
                        <span class="fw-semibold">{{ $roles->firstItem() ?? 0 }}</span>
                        đến
                        <span class="fw-semibold">{{ $roles->lastItem() ?? 0 }}</span>
                        trong tổng số
                        <span class="fw-semibold">{{ $roles->total() }}</span> kết quả
                    </div>
                </div>
                <div class="col-sm-auto mt-3 mt-sm-0">
                    {{ $roles->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection