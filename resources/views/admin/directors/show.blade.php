@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $director->image_path ? Storage::url($director->image_path) : asset('assets/images/director-placeholder.png') }}" 
                         alt="{{ $director->name }}" 
                         class="img-fluid rounded mb-3" 
                         style="max-width: 200px; max-height: 250px; object-fit: cover;">
                    <h4 class="mb-1">{{ $director->name }}</h4>
                    <p class="text-muted mb-2">
                        @if($director->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Ngưng hoạt động</span>
                        @endif
                    </p>
                    <div class="row text-start">
                        <div class="col-6">
                            <h6 class="mb-1">Ngày sinh:</h6>
                            <p class="text-muted">{{ $director->birth_date ? $director->birth_date->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-1">Quốc tịch:</h6>
                            <p class="text-muted">{{ $director->nationality ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-1">Số phim đã làm:</h6>
                            <p class="text-muted">{{ $director->movies->count() }} phim</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('admin.directors.edit', $director->id) }}" class="btn btn-primary w-100">
                                <i class="bx bx-edit fs-18"></i> Chỉnh sửa
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.directors.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bx bx-arrow-back fs-18"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header"  style="background: #ff6633; border-bottom: 1px solid #e55a2b;">
                    <h4 class="card-title" style="color: #333333; font-weight: 600;">Thông tin chi tiết</h4>
                </div>
                <div class="card-body">
                    @if($director->biography)
                        <div class="mb-4">
                            <h6>Tiểu sử:</h6>
                            <p class="text-muted">{{ $director->biography }}</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6>Danh sách phim đã đạo diễn ({{ $director->movies->count() }}):</h6>
                        @if($director->movies->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tên phim</th>
                                            <th>Ngày phát hành</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($director->movies as $movie)
                                            <tr>
                                                <td>{{ $movie->name }}</td>
                                                <td>{{ $movie->release_date->format('d/m/Y') }}</td>
                                                <td>
                                                    @php
                                                        $statusValue = is_object($movie->status) ? $movie->status->value : $movie->status;
                                                        $statusLabel = $statusValue == 'showing' ? 'Đang chiếu' : ($statusValue == 'upcoming' ? 'Sắp chiếu' : 'Kết thúc');
                                                        $statusClass = $statusValue == 'showing' ? 'bg-success' : ($statusValue == 'upcoming' ? 'bg-warning' : 'bg-danger');
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.movies.show', $movie->id) }}" class="btn btn-sm btn-outline-primary">
                                                        Xem chi tiết
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Chưa có phim nào.</p>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <h6>Ngày tạo:</h6>
                            <p class="text-muted">{{ $director->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-6">
                            <h6>Cập nhật lần cuối:</h6>
                            <p class="text-muted">{{ $director->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection