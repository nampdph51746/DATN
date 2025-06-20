@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1">
                        <iconify-icon icon="solar:tag-broken" class="align-middle fs-20 me-2"></iconify-icon>
                        Danh sách thuộc tính
                    </h4>

                    <form action="{{ route('admin.attributes.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light-subtle">
                                <iconify-icon icon="solar:magnifer-broken" class="fs-16"></iconify-icon>
                            </span>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm tên thuộc tính...">
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <iconify-icon icon="solar:filter-broken" class="align-middle fs-16 me-1"></iconify-icon>
                            Tìm
                        </button>
                    </form>

                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-sm btn-primary">
                        <iconify-icon icon="solar:add-circle-broken" class="align-middle fs-16 me-1"></iconify-icon>
                        Thêm Thuộc Tính
                    </a>

                   
                </div>

                <div>
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
                                        Tên thuộc tính
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:calendar-add-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Thời gian tạo
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:calendar-mark-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Thời gian cập nhật
                                    </th>
                                    <th>
                                        <iconify-icon icon="solar:settings-broken" class="align-middle fs-16 me-1"></iconify-icon>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attributes as $key => $attribute)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>{{ $attribute->created_at ? $attribute->created_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                                    <td>{{ $attribute->updated_at ? $attribute->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.attributes.show', $attribute->id) }}" class="btn btn-light btn-sm">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                            </a>
                                           
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Không có thuộc tính nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end mt-3">
                        {!! $attributes->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection