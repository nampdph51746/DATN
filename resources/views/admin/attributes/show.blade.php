@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <iconify-icon icon="solar:tag-broken" class="fs-64 text-primary"></iconify-icon>
                        <div class="mt-3">
                            <h4>Thuộc tính #{{ $attribute->id }}</h4>
                            <p class="text-muted">Thông tin chi tiết về thuộc tính <strong>{{ $attribute->name }}</strong>.</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon> Sửa
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
                                <iconify-icon icon="solar:arrow-left-broken" class="fs-18"></iconify-icon> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="badge bg-info text-light fs-14 py-1 px-2">Chi tiết thuộc tính</h4>
                    <p class="mb-1">
                        <span class="fs-24 text-dark fw-medium">{{ $attribute->name }}</span>
                    </p>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:hashtag-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                ID: <span class="text-muted">{{ $attribute->id }}</span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:text-field-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Tên thuộc tính: <span class="text-muted">{{ $attribute->name }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="row align-items-center g-2 mt-3">
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:calendar-add-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Thời gian tạo: <span class="text-muted">{{ $attribute->created_at ? $attribute->created_at->format('d/m/Y H:i') : 'Không xác định' }}</span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:calendar-mark-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Thời gian cập nhật: <span class="text-muted">{{ $attribute->updated_at ? $attribute->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</span>
                            </p>
                        </div>
                    </div>

                    <h4 class="text-dark fw-medium mt-4">
                        <iconify-icon icon="solar:list-broken" class="align-middle fs-18 me-1"></iconify-icon>
                        Danh sách giá trị thuộc tính
                    </h4>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>
                                        <iconify-icon icon="solar:hashtag-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        #
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:text-field-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Giá trị
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:calendar-add-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Thời gian tạo
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:calendar-mark-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Thời gian cập nhật
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attribute->attributeValues as $key => $attributeValue)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $attributeValue->value }}</td>
                                    <td>{{ $attributeValue->created_at ? $attributeValue->created_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                                    <td>{{ $attributeValue->updated_at ? $attributeValue->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Không có giá trị thuộc tính nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                    <p class="text-muted">Thuộc tính <strong>{{ $attribute->name }}</strong> được sử dụng để định nghĩa các đặc tính của sản phẩm trong hệ thống. Hiện có <strong>{{ $attribute->attributeValues->count() }}</strong> giá trị thuộc tính liên quan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection