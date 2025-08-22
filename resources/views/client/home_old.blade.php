@extends('layouts.client.client')

@section('content')
<style>
    .button-center {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 60px;
    }

    .watch-button {
        background-color: #fff;
        color: #dc3545;
        padding: 10px 20px;
        border: 2px solid #dc3545;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .watch-button:hover {
        background-color: #dc3545;
        color: #fff;
    }

    .movie-img {
        width: 100% !important;
        height: 320px !important;
        object-fit: cover !important;
        border-radius: 8px;
        display: block;
    }

    .w3l-populohny-grids .box16 {
        position: relative;
        overflow: hidden;
    }

    .w3l-populohny-grids .box16 figure {
        margin: 0;
    }

    .owl-three .box16 {
        position: relative;
        overflow: hidden;
    }

    .owl-three .box16 figure {
        margin: 0;
    }

    .owl-three .owl-item .movie-img {
        height: 320px !important;
    }

    .owl-three .owl-stage-outer {
        padding: 10px 0;
    }

    .ticket-btn {
        display: flex;
        position: relative;
        right: 15px;
        background: linear-gradient(to right, #dc3545, #e05c6e);
        color: #fff;
        padding: 10px 24px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .ticket-btn i {
        margin-right: 8px;
    }

    .ticket-btn:hover {
        background: linear-gradient(to right, #a71d2a, #c82333);
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 6px 18px rgba(220, 53, 69, 0.45);
    }

    .genres-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
    }

    .genre-badge {
        background-color: #f8d777;
        color: #212529;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 50px;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .genre-badge:hover {
        background-color: #f1c40f;
        color: #fff;
    }

    .movie-img-container {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
    }

    .movie-img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .movie-img-container img.movie-img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s ease;
    }

    .movie-img-container:hover img.movie-img {
        transform: scale(1.05);
        filter: brightness(0.7);
    }

    .movie-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.75);
        color: #fff;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 15px;
    }

    .movie-img-container:hover .movie-overlay {
        opacity: 1;
    }

    .movie-info h3 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .movie-info p {
        margin-bottom: 10px;
    }

    .genre-badge {
        display: inline-block;
        background: #ffc107;
        color: #000;
        padding: 3px 8px;
        margin: 0 4px 4px 0;
        border-radius: 4px;
        font-size: 13px;
    }

    /* Style cho slide cuối */
    .more-slide-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        height: 320px;
    }

    .more-slide-img {
        width: 100% !important;
        height: 320px !important;
        object-fit: cover !important;
        border-radius: 8px;
        display: block;
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    .more-slide-container:hover .more-slide-img {
        transform: scale(1.05);
        filter: brightness(0.7);
    }

    .more-slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        text-align: center;
        padding: 20px;
        pointer-events: none;
        z-index: 5;
    }

    .more-slide-container:hover .more-slide-overlay {
        opacity: 1;
        pointer-events: auto;
    }

    .more-slide-info {
        z-index: 10;
        padding: 60px 0 0 0;
        text-align: center;
    }

    .more-slide-info h3 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .more-slide-info p {
        font-size: 14px;
        margin-bottom: 15px;
    }

    .more-btn {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #dc3545, #e05c6e);
        color: #fff;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        border: 2px solid transparent;
        z-index: 15;
        pointer-events: auto;
    }

    .more-btn i {
        margin-left: 8px;
        font-size: 14px;
    }

    .more-btn:hover {
        background: linear-gradient(135deg, #a71d2a, #c82333);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.5);
        border-color: #fff;
        color: #fff;
    }

    .more-slide-container .box-content,
    .more-slide-container .video-icon {
        display: none;
    }

    /* Nút điều hướng Swiper */
    .swiper-button-next,
    .swiper-button-prev {
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(220, 53, 69, 0.85);
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        z-index: 10;
        margin-top: -20px;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background-color: #a71d2a;
        transform: translateY(-50%) scale(1.05);
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-weight: bold;
        font-size: 16px;
        color: #fff;
    }

    /* ========== Box16 Styles ========== */
    .box16 {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    .box16 figure {
        margin: 0;
        position: relative;
    }

    .box16 img.movie-img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.4s ease;
    }

    .box16:hover img.movie-img {
        transform: scale(1.05);
    }

    /* Nội dung trong box */
    .box16 .box-content {
        position: absolute;
        bottom: 20px;
        left: 20px;
        z-index: 2;
    }

    /* Tên phim */
    .box16 .box-content .title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: transform 0.3s ease, color 0.3s ease, opacity 0.3s ease;
        opacity: 0;
        transform: translateY(15px);
    }

    /* Icon play */
    .box16 .video-icon {
        font-size: 2rem;
        color: #ffcc00;
        position: absolute;
        bottom: 20px;
        right: 20px;
        transition: opacity 0.3s ease, transform 0.3s ease;
        opacity: 0;
        transform: translateY(15px);
    }

    /* Thời gian & nút đặt vé */
    .box-content h4,
    .box-content .ticket-container {
        opacity: 0;
        transform: translateY(15px);
        transition: all 0.3s ease;
    }

    /* Hover: hiện tên phim + icon play + thời gian + nút */
    .box16:hover .box-content .title,
    .box16:hover .video-icon,
    .box16:hover h4,
    .box16:hover .ticket-container {
        opacity: 1;
        transform: translateY(0);
    }

    /* Tên phim khi hover nổi bật hơn */
    .box16:hover .box-content .title {
        color: #ffcc00;
        transform: translateY(0) scale(1.05);
    }

    /* ========== More Slide Styles ========== */
    .more-slide-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    .more-slide-container figure {
        margin: 0;
    }

    .more-slide-container img.more-slide-img {
        width: 100%;
        height: auto;
        display: block;
    }

    .more-slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        /* Căn giữa dọc */
        text-align: center;
        /* Căn giữa ngang */
        padding: 20px;
    }

    .more-slide-info h3,
    .more-slide-info p {
        color: #fff;
        margin-bottom: 10px;
    }

    .more-btn {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 14px;
        background: #ffcc00;
        color: #000;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .more-btn:hover {
        background: #e6b800;
    }

    .box16 .ticket-container,
    .box16 h4 {
        opacity: 0 !important;
        transform: translateY(15px);
        transition: all 0.3s ease;
        pointer-events: none;
        /* Ngăn click khi ẩn */
    }

    /* Khi hover mới hiện */
    .box16:hover .ticket-container,
    .box16:hover h4 {
        opacity: 1 !important;
        transform: translateY(0);
        pointer-events: auto;
    }

    /* Nút đặt vé */
    .ticket-container {
        display: flex;
        justify-content: center;
        /* Căn ngang */
        align-items: center;
        /* Căn dọc */
        margin-top: 10px;
        opacity: 0;
        transform: translateY(15px);
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .box16:hover .ticket-container {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .w3l-main-slider .banner-view::before {
        background: none;
    }
</style>

<section class="w3l-main-slider position-relative" id="home">
    <div class="companies20-content">
        <div class="owl-one owl-carousel owl-theme">
            @foreach ($banners as $banner)
            <div class="item">
                <li>
                    <div class="slider-info banner-view bg bg2" style="background-image: url('{{ $banner->image_url }}'); background-size: cover; background-position: center;">
                        <div class="banner-info">
                            <!-- <h3>{{ $banner->title }}</h3> -->
                            <!-- Nếu bạn có mô tả thêm, có thể thêm ở đây -->
                            <!-- <a href="{{ $banner->link_url }}" class="popup-with-zoom-anim play-view1" target="_blank" rel="noopener noreferrer">
                                <span class="video-play-icon">
                                    <span class="fa fa-play"></span>
                                </span>
                                <h6>Xem chi tiết</h6>
                            </a> -->
                        </div>
                    </div>
                </li>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!--grids-sec1-->
<section class="w3l-grids">
    <div class="grids-main py-5">
        <div class="container py-lg-3">
            <div class="headerhny-title mb-4">
                <h3 class="hny-title">Phim Đang Chiếu</h3>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($showingMovies->unique('id')->take(6) as $movie)
                    <div class="swiper-slide">
                        <div class="item vhny-grid">
                            <div class="box16">
                                <a href="{{ route('movies.show', ['id' => $movie->id]) }}">
                                    <figure>
                                        <img class="movie-img"
                                            src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('client_assets/assets/images/default-movie.jpg')) }}"
                                            alt="{{ $movie->name }}">
                                    </figure>
                                    <div class="box-content">
                                        <h3 class="title">{{ $movie->name }}</h3>
                                        <h4 class="p-2">
                                            <span class="post"><span class="fa fa-clock-o"></span> {{ $movie->duration_minutes }} phút</span>
                                        </h4>
                                        <div class="ticket-container">
                                            @auth
                                            <a href="{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}" class="ticket-btn">
                                                <img src="{{ asset('client_assets/assets/icons/ticket.svg') }}" alt="Đặt vé" style="width:18px; height:18px; margin-right:6px;">
                                                Đặt vé
                                            </a>
                                            @else
                                            <a href="#" class="ticket-btn" onclick="showLoginPrompt(event, '{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}')">
                                                <img src="{{ asset('client_assets/assets/icons/ticket.svg') }}" alt="Đặt vé" style="width:18px; height:18px; margin-right:6px;">
                                                Đặt vé
                                            </a>
                                            @endauth
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($showingMovies->unique('id')->count() > 5)
                    <div class="swiper-slide">
                        <div class="item vhny-grid">
                            <div class="box16 more-slide-container">
                                <figure>
                                    <img class="more-slide-img"
                                        src="https://cdn.pixabay.com/photo/2017/07/13/23/11/cinema-2502213_1280.jpg"
                                        alt="Xem thêm phim">
                                </figure>
                                <div class="more-slide-overlay">
                                    <div class="more-slide-info">
                                        <h3>Khám phá thêm phim hay</h3>
                                        <p>Xem toàn bộ danh sách phim đang chiếu!</p>
                                        <a href="{{ route('movies.filter', ['status' => 'showing']) }}" class="more-btn">
                                            <i class="fa fa-arrow-right" aria-hidden="true"></i> Xem thêm
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Nút điều hướng -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>
</section>

<section class="w3l-grids">
    <div class="grids-main py-5">
        <div class="container py-lg-3">
            <div class="headerhny-title mb-4">
                <h3 class="hny-title">Phim Sắp Chiếu</h3>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($upcomingMovies->unique('id')->take(6) as $movie)
                    <div class="swiper-slide">
                        <div class="item vhny-grid">
                            <div class="box16">
                                <a href="{{ route('movies.show', ['id' => $movie->id]) }}">
                                    <figure>
                                        <img class="movie-img"
                                            src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('client_assets/assets/images/default-movie.jpg')) }}"
                                            alt="{{ $movie->name }}">
                                    </figure>
                                    <div class="box-content">
                                        <h3 class="title">{{ $movie->name }}</h3>
                                        <h4 class="p-2">
                                            <span class="post"><span class="fa fa-clock-o"></span> {{ $movie->duration_minutes }} phút</span>
                                        </h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Slide Xem thêm -->
                    @if($upcomingMovies->unique('id')->count() > 5)
                    <div class="swiper-slide">
                        <div class="item vhny-grid">
                            <div class="box16 more-slide-container">
                                <figure>
                                    <img class="more-slide-img"
                                        src="https://cdn.pixabay.com/photo/2017/07/13/23/11/cinema-2502213_1280.jpg"
                                        alt="Xem thêm phim">
                                </figure>
                                <div class="more-slide-overlay">
                                    <div class="more-slide-info">
                                        <h3>Khám phá thêm phim sắp chiếu</h3>
                                        <p>Xem toàn bộ danh sách phim sắp ra mắt!</p>
                                        <a href="" class="more-btn">
                                            <i class="fa fa-arrow-right" aria-hidden="true"></i> Xem thêm
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Nút điều hướng -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>
</section>


<!--grids-sec2-->
<section class="w3l-mid-slider position-relative">
    <div class="companies20-content">
        <div class="owl-mid owl-carousel owl-theme">

            <!-- Banner 1 - Combo bắp nước -->
            <div class="item">
                <li>
                    <div class="slider-info mid-view bg"
                        style="background-image: url('https://images.pexels.com/photos/33129/popcorn-movie-party-entertainment.jpg?auto=compress&cs=tinysrgb&w=1600'); background-size: cover; background-position: center;">
                        <div class="container">
                            <div class="mid-info">
                                <span class="sub-text">Combo bắp nước</span>
                                <h3>Giảm ngay 20% cho thành viên</h3>
                                <p>Áp dụng từ 15/08 - 30/08 tại tất cả các rạp CineVN</p>
                                <a class="watch" href="#"><span class="fa fa-ticket-alt"></span> Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </li>
            </div>

            <!-- Banner 2 - Khuyến mãi thành viên -->
            <div class="item">
                <li>
                    <div class="slider-info mid-view bg"
                        style="background-image: url('https://images.pexels.com/photos/109669/pexels-photo-109669.jpeg?auto=compress&cs=tinysrgb&w=1600'); 
            background-size: cover; 
            background-position: center;">
                        <div class="container">
                            <div class="mid-info">
                                <span class="sub-text">Thẻ thành viên</span>
                                <h3>Nhận ngay 50 điểm khi đăng ký mới</h3>
                                <p>Đổi điểm để nhận voucher, quà tặng hấp dẫn</p>
                                <a class="watch" href="#"><span class="fa fa-gift"></span> Tham gia ngay</a>
                            </div>
                        </div>
                    </div>
                </li>
            </div>

            <!-- Banner 3 - Sự kiện khai trương -->
            <div class="item">
                <li>
                    <div class="slider-info mid-view bg"
                        style="background-image: url('https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center;">
                        <div class="container">
                            <div class="mid-info">
                                <span class="sub-text">Sự kiện</span>
                                <h3>Khai trương CineVN Hà Nội</h3>
                                <p>Giảm giá 50% tất cả vé trong tuần đầu</p>
                                <a class="watch" href="#"><span class="fa fa-star"></span> Tìm hiểu thêm</a>
                            </div>
                        </div>
                    </div>
                </li>
            </div>

            <!-- Banner 4 - Đặt vé online -->
            <div class="item">
                <li>
                    <div class="slider-info mid-view bg"
                        style="background-image: url('https://images.pexels.com/photos/7991373/pexels-photo-7991373.jpeg?auto=compress&cs=tinysrgb&w=1600'); 
            background-size: cover; 
            background-position: center;">
                        <div class="container">
                            <div class="mid-info">
                                <span class="sub-text">Đặt vé online</span>
                                <h3>Chọn ghế yêu thích của bạn</h3>
                                <p>Nhanh chóng, tiện lợi, thanh toán an toàn</p>
                                <a class="watch" href="#"><span class="fa fa-play"></span> Đặt vé ngay</a>
                            </div>
                        </div>
                    </div>
                </li>
            </div>
        </div>
    </div>
</section>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 20,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1.2,
                },
                576: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 4,
                }
            }
        });
    });
</script>

@include('client.footer.footer')
@endsection