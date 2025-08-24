@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <iconify-icon icon="solar:info-circle-broken" class="align-middle fs-18 me-2"></iconify-icon>
                        Chi tiết danh mục sản phẩm
                    </h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $category->id }}</dd>

                        <dt class="col-sm-4">Tên danh mục</dt>
                        <dd class="col-sm-8">{{ $category->name }}</dd>

                        <dt class="col-sm-4">Mô tả</dt>
                        <dd class="col-sm-8">{{ $category->description }}</dd>

                        <dt class="col-sm-4">Ngày tạo</dt>
                        <dd class="col-sm-8">{{ $category->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Ngày cập nhật</dt>
                        <dd class="col-sm-8">{{ $category->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
