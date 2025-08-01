@extends('layouts.client.client')

@section('content')
<style>
    :root {
        --bg-light: #ffffff;
        --bg-dark: #1a1a1a;
        --text-light: #333333;
        --text-dark: #ffffff;
        --muted-light: #6c757d;
        --muted-dark: #b0b0b0;
        --primary-light: #dc3545;
        --primary-dark: #ff6b6b;
        --shadow-light: rgba(0, 0, 0, 0.1);
        --shadow-dark: rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] {
        background-color: var(--bg-dark);
        color: var(--text-dark);
    }

    [data-theme="dark"] .text-muted {
        color: var(--muted-dark) !important;
    }

    [data-theme="dark"] .btn-primary {
        background: linear-gradient(to right, var(--primary-dark), #ff8c8c);
        border-color: var(--primary-dark);
    }

    [data-theme="dark"] .btn-primary:hover {
        background: linear-gradient(to right, #cc0000, #ff4d4d);
    }

    [data-theme="dark"] .shadow-sm {
        box-shadow: 0 2px 8px var(--shadow-dark);
    }

    [data-theme="dark"] .rounded {
        border-radius: 8px;
    }

    .w3l-grids {
        padding: 40px 0;
        background: var(--bg-light);
    }

    [data-theme="dark"] .w3l-grids {
        background: var(--bg-dark);
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 12px var(--shadow-light);
        transition: transform 0.3s ease;
    }

    .img-fluid:hover {
        transform: scale(1.02);
    }

    .movie-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .movie-info-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 15px;
        margin-bottom: 12px;
    }

    .movie-info-item i {
        min-width: 20px;
        color: var(--primary-light);
    }

    [data-theme="dark"] .movie-info-item i {
        color: var(--primary-dark);
    }

    .movie-info-item strong {
        font-weight: 600;
        color: var(--text-light);
    }

    [data-theme="dark"] .movie-info-item strong {
        color: var(--text-dark);
    }

    .movie-info-item span {
        color: var(--muted-light);
    }

    [data-theme="dark"] .movie-info-item span {
        color: var(--muted-dark);
    }

    .genre-badge {
	background-color: #17a2b8; /* Đổi sang xanh biển (Bootstrap info) */
	color: white;
	font-size: 13px;
	padding: 4px 10px;
	border-radius: 20px;
	margin: 5px 5px 0 0;
	box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

    [data-theme="dark"] .genre-badge {
        background-color: var(--primary-dark);
    }

    .genre-badge:hover {
        opacity: 0.85;
    }

    .btn-primary {
        background: linear-gradient(to right, var(--primary-light), #e05c6e);
        border: none;
        color: #fff;
        font-weight: 500;
        padding: 12px 24px;
        font-size: 16px;
        border-radius: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px var(--shadow-light);
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #a71d2a, #c82333);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px var(--shadow-light);
    }

    .video-container {
        position: relative;
        width: 100%;
        padding-top: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 12px var(--shadow-light);
    }

    [data-theme="dark"] .video-container {
        box-shadow: 0 4px 12px var(--shadow-dark);
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .text-center h5 {
        font-size: 20px;
        margin-bottom: 15px;
        color: var(--text-light);
    }

    [data-theme="dark"] .text-center h5 {
        color: var(--text-dark);
    }

    .video-responsive {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* Tỷ lệ 16:9 */
    height: 0;
    overflow: hidden;
    border-radius: 8px;
}

.video-responsive iframe {
    position: absolute !important;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
    border: none;
    display: block;
}


</style>
	<section class="w3l-grids">
		<div class="container py-5">
			<div class="row mb-4">
				{{-- Poster --}}
				<div class="col-md-4 mb-4 mb-md-0">
					<img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('client_assets/assets/images/default-movie.jpg')) }}"
						class="img-fluid rounded shadow-sm border"
						alt="{{ $movie->name }}">
				</div>

				{{-- Nội dung --}}
				<div class="col-md-8">
					<h1 class="fw-bold mb-2" style="font-size: 32px;">{{ $movie->name }}</h1>
					<p class="text-muted" style="font-size: 16px; line-height: 1.6;">
						{{ $movie->description ?? 'Không có mô tả.' }}
					</p>

					<div class="movie-info">
						<div>
							<div class="movie-info-item">
								<div>
									<strong>🎬 Đạo diễn:</strong>
									<span>{{ $movie->director ?? 'Đang cập nhật' }}</span>
								</div>
							</div>
							<div class="movie-info-item">
								<div>
									<strong>🎭 Diễn viên:</strong>
									<span>{{ $movie->actors ?? 'Đang cập nhật' }}</span>
								</div>
							</div>
							<div class="movie-info-item">
								<div>
									<strong>⏳ Thời lượng:</strong>
									<span>{{ $movie->duration_minutes }} phút</span>
								</div>
							</div>
						</div>

						<div>
							<div class="movie-info-item">
								<div>
									<strong>📅 Phát hành:</strong>
									<span>{{ $movie->release_date->format('d/m/Y') }}</span>
								</div>
							</div>
							<div class="movie-info-item">
								<div>
									<strong>🌍 Quốc gia:</strong>
									<span>{{ $movie->country?->name ?? 'N/A' }}</span>
								</div>
							</div>
							<div class="movie-info-item">
								<div>
									<strong>🔞 Giới hạn tuổi:</strong>
									<span>{{ $movie->ageLimit?->name ?? ($movie->ageLimit?->label ?? 'N/A') }}</span>
								</div>
							</div>
						</div>
					</div>

					{{-- Thể loại --}}
					<div class="genres-container mt-3">
						<strong class="d-block mb-1">🎞️ Thể loại:</strong>
						@foreach($movie->genres->take(3) as $genre)
							<span class="genre-badge">{{ $genre->name }}</span>
						@endforeach
					</div>

					{{-- Nút đặt vé --}}
					@auth
						<a href="{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}"
							class="btn btn-primary px-4 py-2 mt-4"
							style="font-size: 16px; font-weight: 500;">
							🎟️ Đặt vé ngay
						</a>
					@else
						<a href="#" 
							class="btn btn-primary px-4 py-2 mt-4"
							style="font-size: 16px; font-weight: 500;"
							onclick="showLoginPrompt(event, '{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}')">
							🎟️ Đặt vé ngay
						</a>
					@endauth
				</div>
			</div>

			{{-- Trailer --}}
			@if ($movie->trailer_url)
    <div class="text-center mt-5">
        <h5 class="fw-bold mb-3" style="font-size: 20px;">🎬 Trailer</h5>
        <div class="video-wrapper mx-auto rounded shadow" style="max-width: 960px;">
            <div class="video-responsive">
                {!! $movie->trailer_url !!}
            </div>
        </div>
    </div>
@endif

		</div>
	</section>
    @include('client.footer.footer')
@endsection