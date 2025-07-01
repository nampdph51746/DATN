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
            display: inline-block;
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
    </style>
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
                                    <iframe src="https://player.vimeo.com/video/358205676" allow="autoplay; fullscreen"
                                        allowfullscreen=""></iframe>
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
                                    <iframe src="https://player.vimeo.com/video/395376850" allow="autoplay; fullscreen"
                                        allowfullscreen=""></iframe>
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
                                    <iframe src="https://player.vimeo.com/video/389969665" allow="autoplay; fullscreen"
                                        allowfullscreen=""></iframe>
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
                                    <iframe src="https://player.vimeo.com/video/323491174" allow="autoplay; fullscreen"
                                        allowfullscreen=""></iframe>
                                </div>
                            </div>
                        </div>
                    </li>
                </div>
            </div>
        </div>
    </section>

    <!--grids-sec1-->
    <section class="w3l-grids">
        <div class="grids-main py-5">
            <div class="container py-lg-3">
                <div class="headerhny-title">
                    <div class="w3l-title-grids">
                        <div class="headerhny-left">
                            <h3 class="hny-title">Popular Movies</h3>
                        </div>
                        <div class="headerhny-right text-lg-right">
                            <h4><a class="show-title" href="{{ route('movies.show', ['id' => $showingMovies->first()->id ?? 1]) }}">Show all</a></h4>
                        </div>
                    </div>
                </div>
                <div class="w3l-populohny-grids" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    @if(isset($query) && $showingMovies->isEmpty())
                        <div class="col-12">
                            <p>Không tìm thấy phim nào phù hợp với từ khóa "<strong>{{ $query }}</strong>".</p>
                        </div>
                    @endif
                    @foreach($showingMovies->unique('id') as $movie)
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
                                        <h4>
                                            <span class="post"><span class="fa fa-clock-o"></span> {{ $movie->duration_minutes }} min</span>
                                            <span class="post fa fa-heart text-right"></span>
                                        </h4>
                                    </div>
                                    <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                </a>
                            </div>
                            <div class="genres-container">
                                @foreach($movie->genres->take(3) as $genre)
                                    <span class="genre-badge">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}" class="ticket-btn">
                                    <i class="fa fa-ticket" aria-hidden="true"></i> Đặt vé
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="w3l-grids">
        <div class="grids-main py-5">
            <div class="container py-lg-3">
                <div class="headerhny-title">
                    <div class="w3l-title-grids">
                        <div class="headerhny-left">
                            <h3 class="hny-title">Upcoming Movies</h3>
                        </div>
                        <div class="headerhny-right text-lg-right">
                            <h4><a class="show-title" href="{{ route('movies.show', ['id' => $upcomingMovies->first()->id ?? 1]) }}">Show all</a></h4>
                        </div>
                    </div>
                </div>
                <div class="w3l-populohny-grids" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    @foreach($upcomingMovies->unique('id') as $movie)
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
                                        <h4>
                                            <span class="post"><span class="fa fa-clock-o"></span> {{ $movie->duration_minutes }} min</span>
                                            <span class="post fa fa-heart text-right"></span>
                                        </h4>
                                    </div>
                                    <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                </a>
                            </div>
                            <div class="genres-container">
                                @foreach($movie->genres->take(3) as $genre)
                                    <span class="genre-badge">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}" class="ticket-btn">
                                    <i class="fa fa-ticket" aria-hidden="true"></i> Đặt vé
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!--grids-sec2-->
    <!--mid-slider -->
    <section class="w3l-mid-slider position-relative">
        <div class="companies20-content">
            <div class="owl-mid owl-carousel owl-theme">
                <div class="item">
                    <li>
                        <div class="slider-info mid-view bg bg2">
                            <div class="container">
                                <div class="mid-info">
                                    <span class="sub-text">Comedy</span>
                                    <h3>Jumanji: The Next Level</h3>
                                    <p>2019 ‧ Comedy/Action ‧ 2h 3m</p>
                                    <a class="watch" href="movies.html"><span class="fa fa-play"
                                            aria-hidden="true"></span>
                                        Watch Trailer</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </div>
                <div class="item">
                    <li>
                        <div class="slider-info mid-view mid-top1 bg bg2">
                            <div class="container">
                                <div class="mid-info">
                                    <span class="sub-text">Adventure</span>
                                    <h3>Dolittle</h3>
                                    <p>2020 ‧ Family/Adventure ‧ 1h 41m</p>
                                    <a class="watch" href="movies.html"><span class="fa fa-play"
                                            aria-hidden="true"></span>
                                        Watch Trailer</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </div>
                <div class="item">
                    <li>
                        <div class="slider-info mid-view mid-top2 bg bg2">
                            <div class="container">
                                <div class="mid-info">
                                    <span class="sub-text">Action</span>
                                    <h3>Bad Boys for Life</h3>
                                    <p>2020 ‧ Comedy/Action ‧ 2h 4m</p>
                                    <a class="watch" href="movies.html"><span class="fa fa-play"
                                            aria-hidden="true"></span>
                                        Watch Trailer</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </div>
            </div>
        </div>
    </section>
    @include('client.footer.footer')
@endsection