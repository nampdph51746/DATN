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
                                <form action="{{ route('roles.update', $role->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="role-name" class="form-label">Tên vai trò</label>
                                        <input type="text" id="role-name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $role->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            @foreach ($groupedPermissions as $group => $permissions)
                                                <div class="col-md-3 mb-3">
                                                    <h6 class="fw-bold text-primary text-uppercase">
                                                        {{ str_replace(['-', '_'], ' ', $group) }}</h6>
                                                    @foreach ($permissions as $permission)
                                                        @php
                                                            $parts = explode('.', $permission->name);
                                                            $action = $parts[1] ?? $permission->name;
                                                        @endphp
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                                name="permissions[]" value="{{ $permission->name }}"
                                                                id="perm_{{ $permission->id }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                {{ $action }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>


                                    <button type="submit" class="btn btn-primary">Chỉnh sửa Vai trò</button>
                                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Quay lại</a>

                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Kết thúc Container Fluid -->

@endsection