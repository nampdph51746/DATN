@extends('layouts.admin.admin')

@section('content')
    <!-- Bắt đầu Container Fluid -->
    <div class="container-xxl">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        <div class="card overflow-hiddenCoupons">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center p-3">
                    <h5 class="card-title mb-0">Vai trò đã xoá</h5>
                    <form action="{{ route('roles.deleted') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="search" name="keyword" class="form-control" placeholder="Tìm kiếm theo tên vai trò..."
                                autocomplete="off" value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-outline-primary">
                                <iconify-icon icon="solar:magnifer-linear" class="align-middle"></iconify-icon>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>Tên vai trò</th>
                                    <th>ID vai trò</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày xoá</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->created_at }}</td>
                                        <td>{{ $role->deleted_at }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('roles.deleted-detail', $role->id) }}"
                                                    class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>

                                                @if($role->trashed())
                                                    <form action="{{ route('roles.restore', $role->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-soft-success btn-sm"
                                                            onclick="return confirm('Bạn có chắc muốn phục hồi vai trò này không?')">
                                                            <iconify-icon icon="mdi:restore"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('roles.forceDelete', $role->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Bạn chắc chắn muốn xoá vĩnh viễn vai trò này chứ?')">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- kết thúc bảng responsive -->
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
            <!-- Kết thúc Container Fluid -->

@endsection
