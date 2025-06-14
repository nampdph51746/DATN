@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Thùng rác danh mục sản phẩm</h4>
                        <form class="app-search d-none d-md-block ms-2">
                            <div class="position-relative">
                                <input type="search" class="form-control form-control-sm ps-5 pe-3 rounded-2" placeholder="Tìm kiếm danh mục trong thùng rác" autocomplete="off" value="">
                                <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                            </div>
                        </form>
                        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-sm btn-primary">
                            Quay Lại Danh Sách
                        </a>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheckAll">
                                                <label class="form-check-label" for="customCheckAll"></label>
                                            </div>
                                        </th>
                                        <th>Tên danh mục</th>
                                        <th>Mô tả</th>
                                        <th>Ngày xóa</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customCheck{{ $category->id }}">
                                                    <label class="form-check-label" for="customCheck{{ $category->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-dark fw-medium fs-15 mb-0">{{ $category->name }}</p>
                                            </td>
                                            <td>{{ Str::limit($category->description, 50) }}</td>
                                            <td>{{ $category->deleted_at ? $category->deleted_at->format('d/m/Y H:i') : '' }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <form action="{{ route('product-categories.restore', $category->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            onclick="return confirm('Bạn có chắc muốn khôi phục danh mục này?')">
                                                            <iconify-icon icon="solar:arrow-up-linear" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('product-categories.force-delete', $category->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn danh mục này?')">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Không có danh mục nào trong thùng rác.</td>
                                        </tr>
                                    @endforelse
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