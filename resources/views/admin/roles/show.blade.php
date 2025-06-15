@extends('layouts.admin.admin')

@section('content')
    <!-- Bắt đầu Container Fluid -->
    <div class="container-xxl">

        <div class="row justify-content-center">
            <div class="col-lg-6"> <!-- tăng chiều rộng để mô tả không bị quá chật -->

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">Chi tiết Vai trò</h4>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark" style="width: 30%;">Tên vai trò:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">ID vai trò:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark align-top">Mô tả:</td>
                                        <td class="text-dark fw-medium px-0" style="word-break: break-word; max-height: 150px; overflow-y: auto;">
                                            {{ $role->description ?: '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Ngày tạo:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Ngày cập nhật:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <iconify-icon icon="mdi:arrow-left" style="vertical-align: middle;"></iconify-icon> Quay lại
                    </a>
                </div>
            </div>

        </div>
    </div>
    <!-- Kết thúc Container Fluid -->
@endsection
