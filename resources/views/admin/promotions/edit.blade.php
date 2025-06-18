@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Chỉnh sửa khuyến mãi</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('promotions.update', $promotion->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-name" class="form-label">Tên khuyến mãi</label>
                                    <input type="text" id="promotion-name" name="name" class="form-control" value="{{ old('name', $promotion->name) }}">
                                    @error('name')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-code" class="form-label">Mã khuyến mãi</label>
                                    <input type="text" id="promotion-code" name="code" class="form-control" value="{{ old('code', $promotion->code) }}">
                                    @error('code')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-status" class="form-label">Trạng thái</label>
                                    <select class="form-control" id="promotion-status" name="status">
                                        <option value="active" {{ old('status', $promotion->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status', $promotion->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="inactive" {{ old('status', $promotion->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-discount-type" class="form-label">Loại giảm giá</label>
                                    <select class="form-control" id="promotion-discount-type" name="discount_type">
                                        @foreach($discountTypes as $type)
                                            <option value="{{ $type->value }}" {{ old('discount_type', $promotion->discount_type) == $type->value ? 'selected' : '' }}>{{ $type->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('discount_type')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-discount-value" class="form-label">Giá trị giảm giá</label>
                                    <input type="number" step="0.01" id="promotion-discount-value" name="discount_value" class="form-control" value="{{ old('discount_value', $promotion->discount_value) }}">
                                    @error('discount_value')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-max-discount-amount" class="form-label">Giá trị giảm tối đa</label>
                                    <input type="number" step="0.01" id="promotion-max-discount-amount" name="max_discount_amount" class="form-control" value="{{ old('max_discount_amount', $promotion->max_discount_amount) }}">
                                    @error('max_discount_amount')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-min-booking-value" class="form-label">Giá trị đơn hàng tối thiểu</label>
                                    <input type="number" step="0.01" id="promotion-min-booking-value" name="min_booking_value" class="form-control" value="{{ old('min_booking_value', $promotion->min_booking_value) }}">
                                    @error('min_booking_value')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-start-date" class="form-label">Ngày bắt đầu</label>
                                    <input type="datetime-local" id="promotion-start-date" name="start_date" class="form-control" value="{{ old('start_date', $promotion->start_date->format('Y-m-d\TH:i')) }}">
                                    @error('start_date')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-end-date" class="form-label">Ngày kết thúc</label>
                                    <input type="datetime-local" id="promotion-end-date" name="end_date" class="form-control" value="{{ old('end_date', $promotion->end_date->format('Y-m-d\TH:i')) }}">
                                    @error('end_date')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-quantity" class="form-label">Số lượng mã</label>
                                    <input type="number" id="promotion-quantity" name="quantity" class="form-control" value="{{ old('quantity', $promotion->quantity) }}">
                                    @error('quantity')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-usage-limit" class="form-label">Giới hạn sử dụng mỗi người dùng</label>
                                    <input type="number" id="promotion-usage-limit" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user', $promotion->usage_limit_per_user) }}">
                                    @error('usage_limit_per_user')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="promotion-applies-to" class="form-label">Áp dụng cho</label>
                                    <select class="form-control" id="promotion-applies-to" name="applies_to">
                                        <option value="">Chọn đối tượng áp dụng</option>
                                        <option value="movies" {{ old('applies_to', $promotion->applies_to) == 'movies' ? 'selected' : '' }}>Movies</option>
                                        <option value="tickets" {{ old('applies_to', $promotion->applies_to) == 'tickets' ? 'selected' : '' }}>Tickets</option>
                                    </select>
                                    @error('applies_to')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-0">
                                    <label for="promotion-description" class="form-label">Mô tả</label>
                                    <textarea class="form-control bg-light-subtle" id="promotion-description" name="description" rows="7">{{ old('description', $promotion->description) }}</textarea>
                                    @error('description')
                                        <span class="text-danger fs-13">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end g-2 mt-3">
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-outline-secondary w-100">Cập nhật</button>
                            </div>
                            <div class="col-lg-2">
                                <a href="{{ route('promotions.index') }}" class="btn btn-primary w-100">Hủy</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection