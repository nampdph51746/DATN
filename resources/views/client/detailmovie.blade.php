@extends('layouts.client.client')

@section('content')
<section class="w3l-main-slider position-relative" id="home">
	<div class="companies20-content">
		<div class="owl-one owl-carousel owl-theme">
			<div class="item">
				<li>
					<div class="slider-info banner-view bg bg2">
						<div class="banner-info">
							<h3>Latest Movie Trailers</h3>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<span class="over-para">
									Consequuntur hic odio
									voluptatem tenetur consequatur.</span></p>
							<a href="#small-dialog1" class="popup-with-zoom-anim play-view1">
								<span class="video-play-icon">
									<span class="fa fa-play"></span>
								</span>
								<h6>Watch Trailer</h6>
							</a>
							<div id="small-dialog1" class="zoom-anim-dialog mfp-hide">
								<iframe src="https://player.vimeo.com/video/358205676" allow="autoplay; fullscreen" allowfullscreen=""></iframe>
							</div>
						</div>
					</div>
				</li>
			</div>
			<div class="item">
				<li>
					<div class="slider-info  banner-view banner-top1 bg bg2">
						<div class="banner-info">
							<h3>Latest Online Movies</h3>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<span class="over-para">
									Consequuntur hic odio
									voluptatem tenetur consequatur.</span></p>
							<a href="#small-dialog2" class="popup-with-zoom-anim play-view1">
								<span class="video-play-icon">
									<span class="fa fa-play"></span>
								</span>
								<h6>Watch Trailer</h6>
							</a>
							<div id="small-dialog2" class="zoom-anim-dialog mfp-hide">
								<iframe src="https://player.vimeo.com/video/395376850" allow="autoplay; fullscreen" allowfullscreen=""></iframe>
							</div>
						</div>
					</div>
				</li>
			</div>
			<div class="item">
				<li>
					<div class="slider-info banner-view banner-top2 bg bg2">
						<div class="banner-info">
							<h3>Latest Movie Trailers</h3>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<span class="over-para">
									Consequuntur hic odio
									voluptatem tenetur consequatur.</span></p>
							<a href="#small-dialog3" class="popup-with-zoom-anim play-view1">
								<span class="video-play-icon">
									<span class="fa fa-play"></span>
								</span>
								<h6>Watch Trailer</h6>
							</a>
							<div id="small-dialog3" class="zoom-anim-dialog mfp-hide">
								<iframe src="https://player.vimeo.com/video/389969665" allow="autoplay; fullscreen" allowfullscreen=""></iframe>
							</div>
						</div>
					</div>
				</li>
			</div>
			<div class="item">
				<li>
					<div class="slider-info banner-view banner-top3 bg bg2">
						<div class="banner-info">
							<h3>Latest Online Movies</h3>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<span class="over-para">
									Consequuntur hic odio
									voluptatem tenetur consequatur.</span></p>
							<a href="#small-dialog4" class="popup-with-zoom-anim play-view1">
								<span class="video-play-icon">
									<span class="fa fa-play"></span>
								</span>
								<h6>Watch Trailer</h6>
							</a>
							<div id="small-dialog4" class="zoom-anim-dialog mfp-hide">
								<iframe src="https://player.vimeo.com/video/323491174" allow="autoplay; fullscreen" allowfullscreen=""></iframe>
							</div>
						</div>
					</div>
				</li>
			</div>
		</div>
	</div>
</section>
<section class="w3l-grids">
	<div class="container py-5">

		{{-- THÔNG TIN PHIM --}}
		<div class="row mb-4">
			{{-- Ảnh poster --}}
			<div class="col-md-4 mb-4 mb-md-0">
				<img src="{{ $movie->poster_url ?? asset('assets/images/movie-placeholder.png') }}" alt="{{ $movie->name }}"
					class="img-fluid rounded shadow-sm border">
			</div>

			{{-- Nội dung --}}
			<div class="col-md-8">
				<h1 class="fw-bold text-dark" style="font-size: 26px;">{{ $movie->name }}</h1>
				<p class="text-muted" style="font-size: 22px;">{{ $movie->description ?? 'Không có mô tả.' }}</p>

				<div class="row text-dark mb-4" style="font-size: 24px;">
					<div class="col-sm-6 mb-3">
						<strong>🎬 Đạo diễn:</strong> {{ $movie->director ?? 'Đang cập nhật' }}<br>
						<strong>🎭 Diễn viên:</strong> {{ $movie->actors ?? 'Đang cập nhật' }}<br>
						<strong>⏳ Thời lượng:</strong> {{ $movie->duration_minutes }} phút<br>
						<strong>📢 Ngôn ngữ:</strong> {{ $movie->language ?? 'Đang cập nhật' }}
					</div>
					<div class="col-sm-6 mb-3">
						<strong>📅 Phát hành:</strong> {{ $movie->release_date->format('d/m/Y') }}<br>
						<strong>🎞️ Thể loại:</strong> {{ $movie->genre ?? 'Đang cập nhật' }}<br>
						<strong>🌍 Quốc gia:</strong> {{ $movie->country?->name ?? 'N/A' }}<br>
						<strong>🔞 Giới hạn tuổi:</strong> {{ $movie->ageLimit?->label ?? 'N/A' }}
					</div>
				</div>

				{{-- CHỌN SUẤT CHIẾU --}}
				<a href="{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}"
					class="btn btn-primary px-4 py-2 rounded-4 shadow-sm"
					style="font-size: 18px; font-weight: 500;">
					🎟️ Đặt vé
				</a>

			</div>
		</div>

		{{-- TRAILER --}}
		@if ($movie->trailer_url)
		<div class="text-center mt-5">
    <h5 class="text-dark fw-bold mb-3" style="font-size: 24px;">🎬 Trailer</h5>
    <div class="video-container mx-auto rounded shadow" style="max-width: 960px;">
        <iframe
            src="https://youtube.com/embed/8EzK7o-1La4?si=sFtwa4mAtkYVfS-7"
            title="Trailer"
            frameborder="0"
            allowfullscreen></iframe>
    </div>
</div>

<style>
.video-container {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* Tỷ lệ 16:9 */
    height: 0;
    overflow: hidden;
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>

		@endif
	</div>
</section>
@include('client.footer.footer')
@endsection