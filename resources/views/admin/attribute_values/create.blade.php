@extends('layouts.admin.admin')

@section('content')

<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:list-broken" class="align-middle fs-20 me-2"></iconify-icon>
                            Thêm giá trị thuộc tính
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.attribute-values.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="attribute_id" class="form-label">Thuộc tính</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light-subtle">
                                        <iconify-icon icon="solar:tag-broken" class="fs-16"></iconify-icon>
                                    </span>
                                    <select name="attribute_id" id="attribute_id" class="form-select @error('attribute_id') is-invalid @enderror">
                                        <option value="">-- Chọn thuộc tính --</option>
                                        @foreach($attributes as $attribute)
                                        <option value="{{ $attribute->id }}" {{ old('attribute_id') == $attribute->id ? 'selected' : '' }}>
                                            {{ $attribute->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('attribute_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="value" class="form-label">Giá trị thuộc tính</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light-subtle">
                                        <iconify-icon icon="solar:text-field-broken" class="fs-16"></iconify-icon>
                                    </span>
                                    <input type="text" name="value" id="value"
                                        class="form-control @error('value') is-invalid @enderror"
                                        placeholder="Nhập giá trị thuộc tính"
                                        value="{{ old('value') }}">
                                    @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.attribute-values.index') }}" class="btn btn-outline-secondary">
                                    <iconify-icon icon="solar:arrow-left-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                    Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:check-circle-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                    Lưu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection