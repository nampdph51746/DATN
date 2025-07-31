@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Thêm khuyến mãi theo hạng khách hàng</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('customer_rank_promotions.index') }}" class="btn btn-sm btn-outline-light">
                            <iconify-icon icon="solar:arrow-left-broken" class="align-middle fs-18"></iconify-icon> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer_rank_promotions.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="customer_rank_id" class="form-label">Hạng khách hàng</label>
                                    <select name="customer_rank_id" id="customer_rank_id" class="form-control @error('customer_rank_id') is-invalid @enderror">
                                        <option value="">Chọn hạng khách hàng</option>
                                        @foreach(\App\Models\CustomerRank::all() as $rank)
                                            <option value="{{ $rank->id }}" {{ old('customer_rank_id') == $rank->id ? 'selected' : '' }}>{{ $rank->name ?? $rank->id }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_rank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="promotion_id" class="form-label">Khuyến mãi</label>
                                    <select name="promotion_id" id="promotion_id" class="form-control @error('promotion_id') is-invalid @enderror">
                                        <option value="">Chọn khuyến mãi</option>
                                        @foreach(\App\Models\Promotion::all() as $promotion)
                                            <option value="{{ $promotion->id }}" {{ old('promotion_id') == $promotion->id ? 'selected' : '' }}>{{ $promotion->name ?? $promotion->code ?? $promotion->id }}</option>
                                        @endforeach
                                    </select>
                                    @error('promotion_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">Lưu</button>
                                    <a href="{{ route('customer_rank_promotions.index') }}" class="btn btn-outline-secondary btn-sm">Hủy</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection