@extends('layouts.admin.admin')

@section('content')
    <!-- Bắt đầu Container Fluid -->
    <div class="container-xxl">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chỉnh sửa Hạng Khách Hàng</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form action="{{ route('customers-rank.update', $customerRank->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="customer_rank-name" class="form-label">Tên Hạng Khách Hàng</label>
                                        <input type="text" id="customer_rank-name" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $customerRank->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-min_points_required" class="form-label">Điểm Tối Thiểu Cần
                                            Có</label>
                                        <input type="number" id="customer_rank-min_points_required"
                                            name="min_points_required" class="form-control @error('min_points_required') is-invalid @enderror"
                                            value="{{ old('min_points_required', $customerRank->min_points_required) }}">

                                        @error('min_points_required')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-discount_percentage" class="form-label">Phần Trăm Giảm
                                            Giá</label>
                                        <input type="number" id="customer_rank-discount_percentage"
                                            name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror"
                                            value="{{ old('discount_percentage', $customerRank->discount_percentage) }}"
                                            step="0.01" min="0" max="100">

                                        @error('discount_percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-description" class="form-label">Mô Tả Hạng Khách
                                            Hàng</label>
                                        <textarea id="customer_rank-description" name="description" class="form-control @error('description') is-invalid @enderror"
                                            rows="5"
                                            style="resize: none;">{{ old('description', $customerRank->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Cập Nhật Hạng Khách Hàng</button>
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