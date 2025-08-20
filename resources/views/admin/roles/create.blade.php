@extends('layouts.admin.admin')

@section('content')
    <!-- Bắt đầu Container Fluid -->
    <div class="container-xxl">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin Vai trò</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form action="{{ route('roles.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="role-name" class="form-label">Tên Vai trò</label>
                                        <input type="text" id="role-name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Nhập tên vai trò" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check mb-2">
                                            <input type="checkbox" class="form-check-input" id="check-all-permissions">
                                            <label class="form-check-label fw-bold" for="check-all-permissions">Chọn tất cả</label>
                                        </div>
                                        <label class="form-label">Chọn quyền</label>
                                        <div class="row">
                                           <div class="row">
                                            @foreach ($groupedPermissions as $group => $permissions)
                                                <div class="col-md-3 mb-3">
                                                    <h6 class="fw-bold text-primary text-uppercase">{{ str_replace('-', ' ', $group) }}</h6>
                                                    @foreach ($permissions as $permission)
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                                name="permissions[]" value="{{ $permission->name }}"
                                                                id="perm_{{ $permission->id }}">
                                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                {{ ucwords(str_replace(['-', '.'], [' ', ' '], Str::after($permission->name, $group.'.'))) }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tạo Vai trò</button>
                                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Quay lại</a>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.getElementById('check-all-permissions').addEventListener('change', function () {
                const checked = this.checked;
                document.querySelectorAll('.permission-checkbox').forEach(function (checkbox) {
                    checkbox.checked = checked;
                });
            });
        </script>
    @endpush
    <!-- Kết thúc Container Fluid -->

@endsection