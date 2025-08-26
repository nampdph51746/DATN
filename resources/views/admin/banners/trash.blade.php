@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1">Danh sách banner đã xóa</h4>

                    {{-- Form tìm kiếm --}}
                    <form action="{{ route('admin.banners.trash') }}" method="GET" class="d-flex align-items-center gap-1">
                        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tìm tiêu đề banner...">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                    </form>

                    <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-outline-dark">
                        ⬅️ Quay lại danh sách
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover table-centered">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>#</th>
                                <th>Tiêu đề</th>
                                <th>Ảnh</th>
                                <th>Link</th>
                                <th>Thứ tự</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banners as $index => $banner)
                                <tr>
                                    <td>{{ $banners->firstItem() + $index }}</td>
                                    <td>{{ $banner->title }}</td>
                                    <td>
                                        @if($banner->image_url)
                                            <img src="{{ $banner->image_url }}" alt="Ảnh banner" width="80">
                                        @endif
                                    </td>
                                    <td>{{ $banner->link_url }}</td>
                                    <td>{{ $banner->display_order }}</td>
                                    <td>{{ $banner->start_date?->format('d/m/Y') }}</td>
                                    <td>{{ $banner->end_date?->format('d/m/Y') }}</td>
                                    <td>
                                        @if($banner->is_active)
                                            <span class="badge bg-success">Hiển thị</span>
                                        @else
                                            <span class="badge bg-secondary">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            {{-- Khôi phục --}}
                                            <form action="{{ route('admin.banners.restore', $banner->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-soft-success btn-sm" onclick="return confirm('Bạn có chắc chắn muốn khôi phục banner này?')">
                                                    <iconify-icon icon="solar:refresh-circle-broken" class="fs-18"></iconify-icon>
                                                </button>
                                            </form>

                                            {{-- Xóa vĩnh viễn --}}
                                            <form action="{{ route('admin.banners.forceDelete', $banner->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn banner này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Không có banner nào đã bị xóa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end mt-3">
                        {!! $banners->links('pagination::bootstrap-4') !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection