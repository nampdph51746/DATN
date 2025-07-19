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
                                <form action="{{ route('roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="role-name" class="form-label">Tên vai trò</label>
                                        <input type="text" id="role-name" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $role->name) }}">

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="role-description" class="form-label">Mô tả vai trò</label>
                                        <textarea id="role-description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5" style="resize: none;">{{ old('description', $role->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Chỉnh sửa Vai trò</button>
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
