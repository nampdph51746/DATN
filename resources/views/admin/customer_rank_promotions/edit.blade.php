@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Sửa khuyến mãi theo hạng khách hàng #{{ $item->customer_rank_id }}_{{ $item->promotion_id }}</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('customer_rank_promotions.index') }}" class="btn btn-sm btn-outline-light">
                            <iconify-icon icon="solar:arrow-left-broken" class="align-middle fs-18"></iconify-icon> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('customer_rank_promotions.update', [$item->customer_rank_id, $item->promotion_id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="customer_rank_id" class="form-label">Hạng khách hàng</label>
                                    <select name="customer_rank_id" id="customer_rank_id" class="form-control @error('customer_rank_id') is-invalid @enderror" disabled>
                                        <option value="{{ $item->customer_rank_id }}" selected>{{ $item->customerRank ? $item->customerRank->name ?? $item->customer_rank_id : $item->customer_rank_id }}</option>
                                    </select>
                                    @error('customer_rank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="promotion_id" class="form-label">Khuyến mãi</label>
                                    <select name="promotion_id" id="promotion_id" class="form-control @error('promotion_id') is-invalid @enderror" disabled>
                                        <option value="{{ $item->promotion_id }}" selected>{{ $item->promotion ? $item->promotion->name ?? $item->promotion->code ?? $item->promotion_id : $item->promotion_id }}</option>
                                    </select>
                                    @error('promotion_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
<<<<<<< HEAD
                                    <label for="discount_code" class="form-label">Mã giảm giá</label>
                                    <input type="text" name="discount_code" id="discount_code" value="{{ old('discount_code', $item->discount_code) }}" class="form-control @error('discount_code') is-invalid @enderror">
                                    @error('discount_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
=======
>>>>>>> origin/main
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $item->description) }}</textarea>
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