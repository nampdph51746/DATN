@extends('layouts.client.client')
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
@section('content')
    <!-- Breadcrumbs -->
    <div class="w3l-breadcrumbs">
        <nav id="breadcrumbs" class="breadcrumbs">
            <div class="container page-wrapper">
                <a href="{{ route('client.home') }}">Home</a> » <span class="breadcrumb_last"
                    aria-current="page">Movies</span>
            </div>
        </nav>
    </div>

    <!-- Phim đang chiếu -->
    <section class="w3l-grids">
        <div class="grids-main py-5">
            <div class="container py-lg-4">
                <div class="headerhny-title">
                    <div class="w3l-title-grids">
                        <div class="headerhny-left">
                            <h3 class="hny-title">Phim đang chiếu</h3>
                        </div>
                        <div class="headerhny-right text-lg-right">
                            <h4><a class="show-title" href="{{ route('client.movies') }}">Show all</a></h4>
                        </div>
                    </div>
                </div>
                <div class="w3l-populohny-grids">
                    @forelse ($showingMovies as $movie)
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
                    @empty
                        <div class="item vhny-grid">
                            <p>No movies currently showing.</p>
                        </div>
                    @endforelse
                </div>
                <div class="button-center text-center mt-3">
                    <a href="{{ route('client.movies') }}" class="btn view-button">View all <span
                            class="fa fa-angle-double-right ml-2" aria-hidden="true"></span></a>
                </div>
            </div>
    </section>

    <!-- Tabs: Recent, Popular, Trend -->
<section class="w3l-albums py-5" id="projects">
    <div class="container py-lg-4">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <!-- Horizontal Tab -->
                <div id="parentHorizontalTab">
                    <ul class="resp-tabs-list hor_1">
                        <li>Recent Movies</li>
                        <li>Popular Movies</li>
                        <li>Trend Movies</li>
                        <div class="clear"></div>
                    </ul>
                    <div class="resp-tabs-container hor_1">
                        <!-- Recent Movies -->
                        <div class="albums-content">
                            <div class="row">
                                @forelse ($recentMovies as $movie)
                                    <div class="col-lg-4 new-relise-gd mt-lg-0 mt-4">
                                        <div class="slider-info">
                                            <div class="img-circle">
                                                <a href="{{ route('movies.show', ['id' => $movie->id]) }}">
                                                    <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('client_assets/assets/images/movie-placeholder.png')) }}"
                                                         class="img-fluid movie-img" alt="{{ $movie->name }}">
                                                    <div class="overlay-icon">
                                                        <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="message">
                                                <p>{{ $movie->language ?? 'N/A' }}</p>
                                                <a class="author-book-title"
                                                   href="{{ route('movies.show', ['id' => $movie->id]) }}">{{ $movie->name }}</a>
                                                <h4>
                                                    <span class="post"><span class="fa fa-clock-o"></span>
                                                        {{ $movie->duration_minutes }} min</span>
                                                    <span class="post fa fa-heart text-right"></span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-lg-12">
                                        <p>No recent movies available.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Popular Movies -->
                        <div class="albums-content">
                            <div class="row">
                                @forelse ($popularMovies as $movie)
                                    <div class="col-lg-4 new-relise-gd mt-lg-0 mt-4">
                                        <div class="slider-info">
                                            <div class="img-circle">
                                                <a href="{{ route('movies.show', ['id' => $movie->id]) }}">
                                                    <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('client_assets/assets/images/movie-placeholder.png')) }}"
                                                         class="img-fluid movie-img" alt="{{ $movie->name }}">
                                                    <div class="overlay-icon">
                                                        <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="message">
                                                <p>{{ $movie->language ?? 'N/A' }}</p>
                                                <a class="author-book-title"
                                                   href="{{ route('movies.show', ['id' => $movie->id]) }}">{{ $movie->name }}</a>
                                                <h4>
                                                    <span class="post"><span class="fa fa-clock-o"></span>
                                                        {{ $movie->duration_minutes }} min</span>
                                                    <span class="post fa fa-heart text-right"></span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-lg-12">
                                        <p>No popular movies available.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Trend Movies -->
                        <div class="albums-content">
                            <div class="row">
                                @forelse ($trendMovies as $movie)
                                    <div class="col-lg-4 new-relise-gd mt-lg-0 mt-4">
                                        <div class="slider-info">
                                            <div class="img-circle">
                                                <a href="{{ route('movies.show', ['id' => $movie->id]) }}">
                                                    <img src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('client_assets/assets/images/movie-placeholder.png')) }}"
                                                         class="img-fluid movie-img" alt="{{ $movie->name }}">
                                                    <div class="overlay-icon">
                                                        <span class="fa fa-play video-icon" aria-hidden="true"></span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="message">
                                                <p>{{ $movie->language ?? 'N/A' }}</p>
                                                <a class="author-book-title"
                                                   href="{{ route('movies.show', ['id' => $movie->id]) }}">{{ $movie->name }}</a>
                                                <h4>
                                                    <span class="post"><span class="fa fa-clock-o"></span>
                                                        {{ $movie->duration_minutes }} min</span>
                                                    <span class="post fa fa-heart text-right"></span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-lg-12">
                                        <p>No trending movies available.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    @include('client.footer.footer')
@endsection