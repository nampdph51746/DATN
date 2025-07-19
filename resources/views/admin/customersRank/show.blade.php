@extends('layouts.admin.admin')

@section('content')
    <!-- Bắt đầu Container Fluid -->
    <div class="container-xxl">

        <div class="row justify-content-center">
            <div class="col-lg-6"> <!-- tăng chiều rộng để mô tả không bị quá chật -->

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">Chi Tiết Hạng Khách Hàng</h4>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">ID:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark" style="width: 30%;">Tên:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark" style="width: 30%;">Điểm Tối Thiểu Cần Có:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->min_points_required }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark" style="width: 30%;">Phần Trăm Giảm Giá:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->discount_percentage }}%</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark align-top">Mô Tả:</td>
                                        <td class="text-dark fw-medium px-0" style="word-break: break-word; max-height: 150px; overflow-y: auto;">
                                            {{ $customerRank->description ?: '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Ngày Tạo:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Ngày Cập Nhật:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <iconify-icon icon="mdi:arrow-left" style="vertical-align: middle;"></iconify-icon> Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc Container Fluid -->
@endsection
