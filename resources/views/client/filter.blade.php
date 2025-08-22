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
    .movie-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin: 30px 0;
    }
    .movie-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .movie-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .page-title {
        text-align: center;
        margin: 40px 0;
        color: #fff;
        font-size: 2.5rem;
        font-weight: bold;
    }
</style>

@section('content')
    <!-- Breadcrumbs -->
    <div class="w3l-breadcrumbs">
        <nav id="breadcrumbs" class="breadcrumbs">
            <div class="container page-wrapper">
                <a href="{{ route('client.home') }}">Home</a> » <span class="breadcrumb_last" aria-current="page">{{ $title ?? 'Movies' }}</span>
            </div>
        </nav>
    </div>

    <!-- Movies Section -->
    <section class="w3l-grids">
        <div class="grids-main py-5">
            <div class="container py-lg-4">
                <div class="headerhny-title">
                    <div class="w3l-title-grids">
                        <div class="headerhny-left">
                            <h3 class="hny-title">{{ $title ?? 'Danh sách phim' }}</h3>
                        </div>
                    </div>
                </div>

                @if($movies->count() > 0)
                    <div class="movie-grid-container">
                        @foreach ($movies as $movie)
                            <div class="movie-card">
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
                                <div class="text-center mt-3 mb-3">
                                    @if($movie->status->value === 'showing')
                                        <a href="{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}" class="ticket-btn">
                                            <i class="fa fa-ticket" aria-hidden="true"></i> Đặt vé
                                        </a>
                                    @elseif($movie->status->value === 'upcoming')
                                        <span class="badge badge-info">Sắp chiếu</span>
                                    @else
                                        <span class="badge badge-secondary">Đã kết thúc</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper text-center mt-5">
                        {{ $movies->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <h4 style="color: #fff;">Không tìm thấy phim nào phù hợp với tiêu chí tìm kiếm.</h4>
                        <a href="{{ route('client.home') }}" class="btn btn-primary mt-3">Quay lại trang chủ</a>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @include('client.footer.footer')
@endsection
