@extends('layouts.admin.admin')

@section('content')
    <!-- Bắt đầu Container Fluid -->
    <div class="container-xxl">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Hạng Khách Hàng</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form action="{{ route('customers-rank.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="customer_rank-name" class="form-label">Tên Hạng Khách Hàng</label>
                                        <input type="text" id="customer_rank-name" name="name" class="form-control @error('name') is-invalid @enderror"
                                            
                                            value="{{ old('name') }}">

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-min_points_required" class="form-label">Điểm Tối Thiểu Cần Có</label>
                                        <input type="number" id="customer_rank-min_points_required" name="min_points_required" class="form-control @error('min_points_required') is-invalid @enderror"
                                            
                                            value="{{ old('min_points_required') }}">
                                        @error('min_points_required')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-discount_percentage" class="form-label">Phần Trăm Giảm Giá</label>
                                        <input type="number" id="customer_rank-discount_percentage" name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror"
                                            value="{{ old('discount_percentage') }}" step="0.01" min="0" max="100"
                                            placeholder="vd: 12.5">
                                        @error('discount_percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-description" class="form-label">Mô Tả Hạng Khách Hàng</label>
                                        <textarea id="customer_rank-description" name="description" class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Nhập mô tả hạng khách hàng"
                                            rows="5" style="resize: none;">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Tạo Hạng Khách Hàng</button>
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
