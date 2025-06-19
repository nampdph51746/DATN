@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <!-- Cột trái: Poster và mô tả -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                   <img
                        src="{{ $movie->poster_url ? asset('storage/'.$movie->poster_url) : asset('assets/images/default-poster.png') }}"
                        alt="Poster phim"
                        style="max-width: 120px; max-height: 180px;">
                    <h4 class="mb-2">{{ $movie->name }}</h4>
                    <span class="badge bg-info mb-2">{{ ucfirst(is_object($movie->status) ? $movie->status->value : $movie->status) }}</span>
                    <div class="mb-2">
                        @if($movie->genres && $movie->genres->count())
                            @foreach($movie->genres as $genre)
                                <span class="badge bg-secondary">{{ $genre->name }}</span>
                            @endforeach
                        @endif
                    </div>
                    <p class="text-muted">{{ $movie->description }}</p>
                </div>
                <div class="card-footer border-top bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-primary w-100">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cột phải: Thông tin chi tiết -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="card-title mb-0">Thông tin chi tiết</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Cột trái -->
                        <div class="col-md-6 border-end">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3"><span class="fw-semibold text-dark">Đạo diễn:</span> {{ $movie->director ?? 'N/A' }}</li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Diễn viên:</span> {{ $movie->actors ?? 'N/A' }}</li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Thời lượng:</span> {{ $movie->duration_minutes ?? 'N/A' }} phút</li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Ngày phát hành:</span>
                                    {{ $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') : 'N/A' }}
                                </li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Ngày kết thúc:</span>
                                    {{ $movie->end_date ? \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') : 'N/A' }}
                                </li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Ngôn ngữ:</span> {{ $movie->language ?? 'N/A' }}</li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Quốc gia:</span> {{ $movie->country->name ?? 'N/A' }}</li>
                            </ul>
                        </div>
                        <!-- Cột phải -->
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3"><span class="fw-semibold text-dark">Giới hạn độ tuổi:</span> {{ $movie->ageLimit->name ?? 'N/A' }}</li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Điểm đánh giá:</span>
                                    <span class="badge bg-warning text-dark fs-6">{{ $movie->average_rating ?? 'N/A' }}</span>
                                </li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Trailer:</span>
                                    @if($movie->trailer_url)
                                        <a href="{{ $movie->trailer_url }}" target="_blank" class="link-primary">Xem trailer</a>
                                    @else
                                        <span class="text-muted">Chưa có</span>
                                    @endif
                                </li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Trạng thái:</span>
                                    <span class="badge bg-info">{{ ucfirst(is_object($movie->status) ? $movie->status->value : $movie->status) }}</span>
                                </li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Thể loại:</span>
                                    @if($movie->genres && $movie->genres->count())
                                        @foreach($movie->genres as $genre)
                                            <span class="badge bg-secondary">{{ $genre->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Chưa có</span>
                                    @endif
                                </li>
                                <li class="mb-3"><span class="fw-semibold text-dark">Phòng chiếu khả dụng:</span>
                                    @if(isset($rooms) && count($rooms))
                                        <ul class="mb-0 ps-3">
                                            @foreach ($rooms as $room)
                                                <li>{{ $room->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Chưa có phòng chiếu.</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div>
                        <h5 class="fw-semibold mb-2">Mô tả chi tiết</h5>
                        <p class="text-muted mb-0">{{ $movie->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection