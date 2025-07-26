@extends('layouts.admin.admin')

@section('content')

<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:tag-broken" class="align-middle fs-20 me-2"></iconify-icon>
                            Thêm thuộc tính
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.attributes.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Tên thuộc tính</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light-subtle">
                                        <iconify-icon icon="solar:text-field-broken" class="fs-16"></iconify-icon>
                                    </span>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Xin điền tên thuộc tính"
                                        value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary">
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