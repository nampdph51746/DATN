@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ $movie->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-stretch">
                        <!-- Bên trái: nền cam nhạt, ảnh poster -->
                        <div class="col-md-5 d-flex flex-column justify-content-center align-items-center p-0" style="background: #fff3e0; border-radius: 12px 0 0 12px;">
                            <div class="w-100 text-center py-4">
                                <img src="{{ $movie->poster_url ? asset($movie->poster_url) : asset('assets/images/posters/default.png') }}"
                                     alt=""
                                     id="moviePoster"
                                     style="max-width:90%; border-radius:12px; box-shadow:0 2px 8px #0001; border: 3px solid #222; cursor: pointer;"
                                     data-bs-toggle="modal" data-bs-target="#posterModal">
                            </div>
                        </div>
                        <!-- Thanh ngăn cách dọc ở giữa -->
                        <div class="col-md-1 d-flex justify-content-center align-items-center p-0">
                            <div style="width:4px; height:120px; background:#ff9800; border-radius:2px;"></div>
                        </div>
                        <!-- Thông tin phim -->
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Thể loại:</strong>
                                    @foreach($movie->genres as $genre)
                                        <span class="badge bg-info text-dark">{{ $genre->name }}</span>
                                    @endforeach
                                </li>
                                <li class="list-group-item"><strong>Đạo diễn:</strong> {{ $movie->director }}</li>
                                <li class="list-group-item"><strong>Diễn viên:</strong> {{ $movie->actors }}</li>
                                <li class="list-group-item"><strong>Thời lượng:</strong> {{ $movie->duration_minutes }} phút</li>
                                <li class="list-group-item"><strong>Ngày phát hành:</strong> {{ \Carbon\Carbon::parse($movie->release_date)->format('d-m-Y') }}</li>
                                <li class="list-group-item"><strong>Ngôn ngữ:</strong> {{ $movie->language }}</li>
                                <li class="list-group-item"><strong>Quốc gia:</strong> {{ $movie->country->name ?? '' }}</li>
                                <li class="list-group-item"><strong>Độ tuổi:</strong> {{ $movie->ageLimit->name ?? '' }}</li>
                                <li class="list-group-item"><strong>Điểm đánh giá:</strong> {{ $movie->average_rating }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h5 class="fw-bold">Mô tả phim</h5>
                        <div class="bg-light rounded p-3">
                            {{ $movie->description }}
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal phóng to ảnh -->
<div class="modal fade" id="posterModal" tabindex="-1" aria-labelledby="posterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body text-center p-0">
        <img src="{{ $movie->poster_url ? asset($movie->poster_url) : asset('assets/images/posters/default.png') }}"
             alt=""
             style="max-width:100%; max-height:80vh; border-radius:16px; border: 4px solid #222; box-shadow:0 4px 24px #0004;">
      </div>
    </div>
  </div>
</div>
@endsection