@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center gap-2">
                    <h4 class="card-title flex-grow-1 mb-0">Danh Sách Đạo Diễn</h4>
                    
                    <form class="d-flex align-items-center gap-2 ms-2" method="GET" action="{{ route('admin.directors.index') }}" style="min-width:320px;">
                        <div class="position-relative flex-grow-1">
                            <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-2"
                                placeholder="Tìm kiếm tên đạo diễn..." autocomplete="off" value="{{ request('query') }}">
                            <iconify-icon icon="solar:magnifer-linear"
                                class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"
                                style="font-size: 16px;"></iconify-icon>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.directors.create') }}" class="btn btn-sm btn-primary">
                        Thêm đạo diễn
                    </a>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 55px;">Ảnh</th>
                                    <th>Tên đạo diễn</th>
                                    <th>Ngày sinh</th>
                                    <th>Quốc tịch</th>
                                    <th>Số phim đã làm</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian tạo</th>
                                    <th class="text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($directors as $director)
                                    <tr>
                                        <td>
                                            <img src="{{ $director->image_path ? Storage::url($director->image_path) : asset('assets/images/director-placeholder.png') }}" 
                                                 alt="{{ $director->name }}" 
                                                 class="director-avatar"
                                                 loading="lazy"
                                                 onerror="this.src='{{ asset('assets/images/director-placeholder.png') }}'; this.onerror=null;">
                                        </td>
                                        <td>{{ $director->name }}</td>
                                        <td>{{ $director->birth_date ? $director->birth_date->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $director->nationality ?? 'N/A' }}</td>
                                        <td>{{ $director->movies->count() }}</td>
                                        <td>
                                            @if($director->is_active)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-danger">Ngưng hoạt động</span>
                                            @endif
                                        </td>
                                        <td>{{ $director->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.directors.show', $director->id) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.directors.edit', $director->id) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                @if($director->movies->count() == 0)
                                                    <form action="{{ route('admin.directors.destroy', $director->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đạo diễn này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-soft-secondary btn-sm" disabled title="Không thể xóa vì đang có phim">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($directors->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Không có đạo diễn nào.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $directors->appends(['query' => request('query')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.director-avatar {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.table-responsive {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    border-top: none;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d1edff 0%, #a8e6cf 100%);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}
</style>
@endsection
