@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        @include('admin.partials.notifications')
        
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:add-circle-broken" class="align-middle fs-24 text-primary"></iconify-icon>
                        <h4 class="card-title mb-0">Tạo mới danh mục sản phẩm</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product-categories.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-dark">
                                            <iconify-icon icon="solar:text-field-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                            Tên danh mục
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <iconify-icon icon="solar:tag-broken" class="fs-18 text-muted"></iconify-icon>
                                            </span>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên danh mục" value="{{ old('name') }}">
                                        </div>
                                        @error('name')
                                            <span class="text-danger d-flex align-items-center mt-1">
                                                <iconify-icon icon="solar:info-circle-broken" class="fs-16 me-1"></iconify-icon>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-dark">
                                            <iconify-icon icon="solar:document-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                            Mô tả
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <iconify-icon icon="solar:notes-broken" class="fs-18 text-muted"></iconify-icon>
                                            </span>
                                            <textarea id="description" name="description" class="form-control" placeholder="Nhập mô tả cho danh mục" rows="4">{{ old('description') }}</textarea>
                                        </div>
                                        @error('description')
                                            <span class="text-danger d-flex align-items-center mt-1">
                                                <iconify-icon icon="solar:info-circle-broken" class="fs-16 me-1"></iconify-icon>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-top d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:check-circle-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                    Tạo mới
                                </button>
                                <a href="{{ route('product-categories.index') }}" class="btn btn-outline-secondary">
                                    <iconify-icon icon="solar:close-circle-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                    Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 