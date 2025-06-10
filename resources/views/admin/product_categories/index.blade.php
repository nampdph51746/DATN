@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        @include('admin.partials.notifications')

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:box-broken" class="align-middle fs-18 me-2"></iconify-icon>
                            Danh mục sản phẩm
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('product-categories.create') }}" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-broken" class="align-middle fs-18 me-1"></iconify-icon>
                                Thêm danh mục
                            </a>
                            <a href="{{ route('product-categories.trash') }}" class="btn btn-sm btn-outline-danger" title="Thùng rác">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th><iconify-icon icon="solar:hashtag-broken" class="align-middle fs-16 me-1"></iconify-icon>ID</th>
                                        <th><iconify-icon icon="solar:text-field-broken" class="align-middle fs-16 me-1"></iconify-icon>Tên danh mục</th>
                                        <th><iconify-icon icon="solar:document-broken" class="align-middle fs-16 me-1"></iconify-icon>Mô tả</th>
                                        <th><iconify-icon icon="solar:calendar-broken" class="align-middle fs-16 me-1"></iconify-icon>Ngày tạo</th>
                                        <th><iconify-icon icon="solar:settings-broken" class="align-middle fs-16 me-1"></iconify-icon>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                            <td>
                                                <iconify-icon icon="solar:tag-broken" class="align-middle fs-16 me-1 text-primary"></iconify-icon>
                                                {{ $category->name }}
                                            </td>
                                            <td>{{ Str::limit($category->description, 50) }}</td>
                                            <td>
                                                <iconify-icon icon="solar:clock-circle-broken" class="align-middle fs-16 me-1 text-muted"></iconify-icon>
                                                {{ $category->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('product-categories.edit', $category->id) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <form action="{{ route('product-categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-end">
                            {{ $categories->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection