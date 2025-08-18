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

    [data-theme="dark"] .img-fluid {
        box-shadow: 0 4px 12px var(--shadow-dark);
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
        display: inline-block;
        background-color: var(--primary-light);
        color: white;
        font-size: 13px;
        padding: 4px 10px;
        border-radius: 20px;
        margin: 5px 5px 0 0;
        transition: background 0.3s;
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

    [data-theme="dark"] .btn-primary {
        box-shadow: 0 4px 12px var(--shadow-dark);
    }

    [data-theme="dark"] .btn-primary:hover {
        box-shadow: 0 6px 18px var(--shadow-dark);
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

    /* Rating Section */
    .rating-input {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 8px;
    }

    .rating-input .star {
        font-size: 26px;
        color: #e0e0e0;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 6px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .rating-input .star:hover {
        color: #ffd700 !important;
        transform: scale(1.15);
        background-color: rgba(255, 215, 0, 0.15);
    }

    .rating-input .star.active {
        color: #ffd700 !important;
        text-shadow: 0 0 8px rgba(255, 215, 0, 0.5);
    }

    .rating-input .star i {
        font-size: inherit;
        color: inherit;
        transition: color 0.3s ease, transform 0.2s ease;
    }

    [data-theme="dark"] .rating-input .star {
        color: #b0b0b0;
    }

    [data-theme="dark"] .rating-input .star:hover {
        color: #ffd700 !important;
        background-color: rgba(255, 215, 0, 0.2);
    }

    [data-theme="dark"] .rating-input .star.active {
        color: #ffd700 !important;
        text-shadow: 0 0 8px rgba(255, 215, 0, 0.7);
    }

    .rating-input .star.active i {
        animation: star-pulse 0.3s ease-in-out;
    }

    @keyframes star-pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    /* Review Section */
    .avatar-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }

    [data-theme="dark"] .avatar-circle {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    }

    .stars i {
        font-size: 14px !important;
        margin-right: 3px;
        transition: color 0.3s ease;
        display: inline-block !important;
        font-family: "Font Awesome 5 Free" !important;
        font-style: normal !important;
        font-variant: normal !important;
        text-rendering: auto !important;
        line-height: 1 !important;
    }

    .stars i.text-warning {
        color: #ffd700 !important;
    }

    .stars i.text-muted {
        color: #6c757d !important;
    }

    .stars i.fas {
        font-weight: 900 !important;
    }

    .stars i.far {
        font-weight: 400 !important;
    }

    [data-theme="dark"] .stars i.text-warning {
        color: #ffd700 !important;
    }

    [data-theme="dark"] .stars i.text-muted {
        color: #b0b0b0 !important;
    }

    .review-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .review-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px var(--shadow-light);
    }

    [data-theme="dark"] .review-item {
        background-color: #2a2a2a;
        border-color: #404040;
    }

    [data-theme="dark"] .review-item:hover {
        box-shadow: 0 6px 20px var(--shadow-dark);
    }

    [data-theme="dark"] .review-item .card-body {
        color: var(--text-dark);
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
                                    Consequuntur hic odio voluptatem tenetur consequatur.</span></p>
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
                    <div class="slider-info banner-view banner-top1 bg bg2">
                        <div class="banner-info">
                            <h3>Latest Online Movies</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<span class="over-para">
                                    Consequuntur hic odio voluptatem tenetur consequatur.</span></p>
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
                    </li>
                </div>
                <div class="item">
                    <li>
                        <div class="slider-info banner-view banner-top2 bg bg2">
                            <div class="banner-info">
                                <h3>Latest Movie Trailers</h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<span class="over-para">
                                        Consequuntur hic odio voluptatem tenetur consequatur.</span></p>
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
                                        Consequuntur hic odio voluptatem tenetur consequatur.</span></p>
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
                <span>{{ $movie->director->name ?? 'N/A' }}</span>
            </div>
                            </div>
                            <div class="movie-info-item">
                                <div>
                <strong>🎭 Diễn viên:</strong>
                <span>
                    @if ($movie->actors->isNotEmpty())
                        {{ $movie->actors->pluck('name')->implode(', ') }}
                    @else
                        N/A
                    @endif
                </span>
            </div>
                            </div>
                            <div class="movie-info-item">
                                <div>
                <strong>⏳ Thời lượng:</strong>
                <span>{{ $movie->duration ?? 'N/A' }} phút</span>
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
                                    <span>{{ $movie->ageLimit?->label ?? 'N/A' }}</span>
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
                    <div class="video-container mx-auto rounded shadow" style="max-width: 960px;">
                        <iframe
                            src="{{ $movie->trailer_url }}"
                            title="Trailer"
                            frameborder="0"
                            allowfullscreen></iframe>
                    </div>
                </div>
            @endif

            {{-- Đánh giá và bình luận --}}
            <div class="mt-5">
                <div class="row">
                    <div class="col-12">
                        <h5 class="fw-bold mb-4" style="font-size: 20px;">⭐ Đánh giá & Bình luận</h5>
                        
                        {{-- Thống kê đánh giá --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                    <h3 class="mb-1">{{ number_format($movie->average_rating ?? 0, 1) }}/5</h3>
                                    <div class="stars mb-2">
                                        @php
                                            $rating = $movie->average_rating ?? 0;
                                            $fullStars = floor($rating);
                                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $fullStars)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="mb-0">{{ $movie->reviews()->where('status', 'approved')->count() }} đánh giá</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                {{-- Form đánh giá --}}
                                @auth
                                    @if($canReview)
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h6 class="card-title">Đánh giá phim này</h6>
                                                <form id="reviewForm">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label">Số sao đánh giá</label>
                                                        <div class="rating-input">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <span class="star" data-rating="{{ $i }}" aria-label="Rate {{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                                                    <i class="far fa-star"></i>
                                                                </span>
                                                            @endfor
                                                        </div>
                                                        <input type="hidden" id="rating_star" name="rating_star" value="">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="comment" class="form-label">Bình luận (tùy chọn)</label>
                                                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Chia sẻ cảm nghĩ của bạn về bộ phim..."></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>{{ $reviewMessage }}
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập</a> để đánh giá phim này.
                                    </div>
                                @endauth
                            </div>
                        </div>

                        {{-- Danh sách đánh giá --}}
                        <div class="reviews-section">
                            <h6 class="mb-3">Bình luận từ khán giả</h6>
                            <div id="reviewsList">
                                @forelse($reviews as $review)
                                    <div class="review-item card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3">
                                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                        <div class="stars">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->rating_star)
                                                                    <i class="fas fa-star text-warning"></i>
                                                                @else
                                                                    <i class="far fa-star text-muted"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                            @if($review->comment)
                                                <p class="mb-0">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Chưa có đánh giá nào cho phim này.</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            @if($movie->reviews()->where('status', 'approved')->count() > 5)
                                <div class="text-center mt-3">
                                    <button class="btn btn-outline-primary" id="loadMoreReviews">Xem thêm đánh giá</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('client.footer.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý rating stars
            const stars = document.querySelectorAll('.rating-input .star');
            const ratingInput = document.getElementById('rating_star');
            
            if (stars.length > 0) {
                stars.forEach((star, index) => {
                    star.addEventListener('click', function() {
                        const rating = this.getAttribute('data-rating');
                        ratingInput.value = rating;
                        
                        // Update visual state
                        stars.forEach((s, i) => {
                            if (i < rating) {
                                s.classList.add('active');
                                s.querySelector('i').className = 'fas fa-star';
                            } else {
                                s.classList.remove('active');
                                s.querySelector('i').className = 'far fa-star';
                            }
                        });
                    });
                    
                    // Hover effect
                    star.addEventListener('mouseenter', function() {
                        const rating = this.getAttribute('data-rating');
                        stars.forEach((s, i) => {
                            if (i < rating) {
                                s.style.color = '#ffd700';
                            } else {
                                s.style.color = '#e0e0e0';
                            }
                        });
                    });
                });
                
                // Reset hover effect
                const ratingContainer = document.querySelector('.rating-input');
                if (ratingContainer) {
                    ratingContainer.addEventListener('mouseleave', function() {
                        const currentRating = ratingInput.value;
                        stars.forEach((s, i) => {
                            if (i < currentRating) {
                                s.style.color = '#ffd700';
                            } else {
                                s.style.color = '#e0e0e0';
                            }
                        });
                    });
                }
            }

            // Xử lý form submit
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const rating = ratingInput.value;
                    const comment = document.getElementById('comment').value;
                    
                    if (!rating) {
                        alert('Vui lòng chọn số sao đánh giá!');
                        return;
                    }
                    
                    // Disable submit button
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Đang gửi...';
                    
                    // Send AJAX request
                    fetch(`/movies/{{ $movie->id }}/reviews`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            rating_star: rating,
                            comment: comment
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide form and show success message
                            const alertClass = data.status === 'approved' ? 'alert-success' : 'alert-warning';
                            const icon = data.status === 'approved' ? 'fa-check-circle' : 'fa-clock';
                            
                            reviewForm.parentElement.innerHTML = `
                                <div class="alert ${alertClass}">
                                    <i class="fas ${icon} me-2"></i>${data.message}
                                </div>
                            `;
                            
                            // Only add new review to the list if it's approved
                            if (data.status === 'approved') {
                                const reviewsList = document.getElementById('reviewsList');
                                const newReview = createReviewElement(data.review);
                                
                                if (reviewsList.querySelector('.text-center')) {
                                    // Replace "no reviews" message
                                    reviewsList.innerHTML = newReview;
                                } else {
                                    // Prepend to existing reviews
                                    reviewsList.insertAdjacentHTML('afterbegin', newReview);
                                }
                                
                                // Reload page to update average rating
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                // For pending reviews, just show message without reloading
                                setTimeout(() => {
                                    // Optionally reload to reset form
                                    location.reload();
                                }, 3000);
                            }
                        } else {
                            alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    });
                });
            }
            
            function createReviewElement(review) {
                const stars = Array.from({length: 5}, (_, i) => {
                    return i < review.rating_star 
                        ? '<i class="fas fa-star text-warning"></i>' 
                        : '<i class="far fa-star text-muted"></i>';
                }).join('');
                
                return `
                    <div class="review-item card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        ${review.user_name.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">${review.user_name}</h6>
                                        <div class="stars">
                                            ${stars}
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">${review.created_at}</small>
                            </div>
                            ${review.comment ? `<p class="mb-0">${review.comment}</p>` : ''}
                        </div>
                    </div>
                `;
            }
        });
    </script>
@endsection