@extends('layouts.admin.admin')

@section('content')
    <div class="container-fluid">
        @include('admin.partials.notifications')

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <iconify-icon icon="solar:box-broken" class="fs-64 text-primary"></iconify-icon>
                            <div class="mt-3">
                                @if ($combo->image_url)
                                    <img src="{{ $combo->image_url ? asset('storage/' . $combo->image_url) : asset('assets/images/default.png') }}"
                                        alt="{{ $combo->sku }}" class="img-fluid mt-2" style="max-width: 200px;">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </div>
                            <div class="mt-3">
                                <h4>Combo #{{ $combo->id }}</h4>
                                <p class="text-muted">Thông tin chi tiết về combo <strong>{{ $combo->sku }}</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <a href="{{ route('admin.combos.edit', $combo->id) }}"
                                    class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon> Sửa
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('admin.combos.index') }}"
                                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">
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
                        <h4 class="badge bg-info text-light fs-14 py-1 px-2">Chi tiết combo</h4>
                        <p class="mb-1">
                            <span class="fs-24 text-dark fw-medium">{{ $combo->sku }}</span>
                        </p>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:hashtag-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    ID: <span class="text-muted">{{ $combo->id }}</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:tag-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                    Sản phẩm: <span
                                        class="text-muted">{{ $combo->product ? $combo->product->name : 'Không xác định' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:calendar-add-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    Thời gian tạo: <span
                                        class="text-muted">{{ $combo->created_at ? $combo->created_at->format('d/m/Y H:i') : 'Không xác định' }}</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:calendar-mark-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    Thời gian cập nhật: <span
                                        class="text-muted">{{ $combo->updated_at ? $combo->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:check-circle-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    Trạng thái: <span
                                        class="text-muted">{{ $combo->is_active ? 'Hoạt động' : 'Không hoạt động' }}</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-0 fw-medium text-dark fs-16">
                                    <iconify-icon icon="solar:box-minimalistic-broken"
                                        class="align-middle fs-16 me-1"></iconify-icon>
                                    Loại combo: <span class="text-muted">Combo tiêu chuẩn</span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="mb-0 fw-medium text-dark fs-16">
                                <iconify-icon icon="solar:text-bold-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                Mô tả:
                            </p>
                            <p class="text-muted mt-2">Combo bao gồm các sản phẩm được chọn.</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-dark fw-medium mb-0">
                                <iconify-icon icon="solar:list-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                Danh sách các mục trong combo
                            </h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>
                                            <iconify-icon icon="solar:hashtag-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>
                                            #
                                        </th>
                                        <th>
                                            <iconify-icon icon="solar:text-field-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>
                                            Biến thể
                                        </th>
                                        <th>
                                            <iconify-icon icon="solar:chart-square-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>
                                            Số lượng
                                        </th>
                                        <th>
                                            <iconify-icon icon="solar:calendar-add-broken"
                                                class="align-middle fs-16 me-1"></iconify-icon>
                                            Thời gian thêm
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($combo->comboPackageItems as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->itemProductVariant->sku ?? 'Không có tên' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Không có mục nào trong combo.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <h4 class="text-dark fw-medium mt-4">Ghi chú:</h4>
                        <p class="text-muted">
                            Combo <strong>{{ $combo->sku }}</strong> thuộc sản phẩm
                            <strong>{{ $combo->product ? $combo->product->name : 'Không xác định' }}</strong>.
                            Hiện có
                            <strong>{{ $combo->comboPackageItems->count() }}</strong> mục trong combo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection