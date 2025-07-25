@extends('layouts.admin.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Danh sách loại phòng chiếu</h4>

                        <form class="app-search d-none d-md-block ms-2" method="GET" action="{{ route('admin.room-types.index') }}">
                            <div class="position-relative">
                                <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-2" placeholder="Tìm kiếm loại phòng..." autocomplete="off" value="{{ request('query') }}">
                                <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                            </div>
                        </form>

                        <a href="{{ route('admin.room-types.create') }}" class="btn btn-sm btn-primary">
                            Thêm loại phòng
                        </a>

                        <!-- <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                Thao tác
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#!" class="dropdown-item">Download</a>
                                <a href="#!" class="dropdown-item">Export</a>
                                <a href="#!" class="dropdown-item">Import</a>
                            </div>
                        </div> -->
                    </div>

                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên loại phòng</th>
                                        <th>Mô tả</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($roomTypes as $roomType)
                                        <tr>
                                            <td>{{ $roomType->id }}</td>
                                            <td>{{ $roomType->name }}</td>
                                            <td>{{ $roomType->description ?? 'Không có mô tả' }}</td>
                                            <td>
                                                <span class="badge {{ $roomType->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($roomType->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $roomType->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.room-types.show', $roomType->id) }}" class="btn btn-light btn-sm">
                                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.room-types.edit', $roomType->id) }}" class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <!-- <form action="{{ route('admin.room-types.deactivate', $roomType->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn vô hiệu hóa loại phòng này?')">
                                                            <iconify-icon icon="solar:trash-bin-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form> -->
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Không có loại phòng nào được tìm thấy.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer border-top">
                            <div class="d-flex justify-content-end">
                                {{ $roomTypes->appends(['query' => request('query')])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection