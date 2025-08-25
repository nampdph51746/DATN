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
        --genre-bg: #6c757d;
        /* Màu mới cho genre-badge */
        --genre-bg-dark: #b0b0b0;
        /* Màu tối cho genre-badge */
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
        display: flex;
        flex-direction: column;
        /* Sắp xếp theo chiều dọc */
        gap: 15px;
        margin-top: 20px;
    }

    .movie-info-item {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 18px;
        /* Tăng cỡ chữ */
        margin-bottom: 15px;
    }

    .movie-info-item i {
        color: var(--primary-light);
        font-size: 20px;
        /* Tăng cỡ icon */
        transition: color 0.3s ease;
    }

    [data-theme="dark"] .movie-info-item i {
        color: var(--primary-dark);
    }

    .movie-info-item i:hover {
        color: var(--text-light);
    }

    [data-theme="dark"] .movie-info-item i:hover {
        color: var(--text-dark);
    }

    .movie-info-item strong {
        font-weight: 700;
        color: var(--text-light);
        min-width: 130px;
    }

    [data-theme="dark"] .movie-info-item strong {
        color: var(--text-dark);
    }

    .movie-info-item span {
        color: var(--muted-light);
        font-weight: 500;
    }

    [data-theme="dark"] .movie-info-item span {
        color: var(--muted-dark);
    }

    .movie-card {
        display: flex;
        flex-direction: column;
        background: var(--bg-light);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px var(--shadow-light);
        transition: transform 0.3s ease;
    }

    [data-theme="dark"] .movie-card {
        background: var(--bg-dark);
        box-shadow: 0 4px 12px var(--shadow-dark);
    }

    .movie-card:hover {
        transform: translateY(-5px);
    }

    .movie-poster {
        width: 100%;
        height: 350px;
        /* Thu nhỏ ảnh */
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }

    [data-theme="dark"] .movie-poster {
        border-bottom-color: #333;
    }

    .movie-details {
        padding: 20px;
        flex-grow: 1;
    }

    .movie-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-light);
        margin-bottom: 15px;
        background: linear-gradient(90deg, var(--primary-light), var(--text-light));
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: transform 0.3s ease;
    }

    [data-theme="dark"] .movie-title {
        color: var(--text-dark);
        background: linear-gradient(90deg, var(--primary-dark), var(--text-dark));
    }

    .movie-title:hover {
        transform: scale(1.02);
    }

    .movie-description {
        font-size: 16px;
        color: var(--muted-light);
        line-height: 1.8;
        margin-bottom: 20px;
    }

    [data-theme="dark"] .movie-description {
        color: var(--muted-dark);
    }

    .genres-container {
        margin-top: 20px;
    }

    .genre-badge {
        display: inline-block;
        background-color: var(--genre-bg);
        /* Màu mới cho genre */
        color: white;
        font-size: 16px;
        padding: 6px 15px;
        border-radius: 20px;
        margin-right: 12px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px var(--shadow-light);
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    [data-theme="dark"] .genre-badge {
        background-color: var(--genre-bg-dark);
        /* Màu tối cho genre */
        box-shadow: 0 2px 4px var(--shadow-dark);
    }

    .genre-badge:hover {
        opacity: 0.9;
        transform: scale(1.05);
    }

    .btn-primary {
        background: linear-gradient(to right, var(--primary-light), #e05c6e);
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 14px 35px;
        font-size: 16px;
        border-radius: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px var(--shadow-light);
        text-transform: uppercase;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #a71d2a, #c82333);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px var(--shadow-light);
    }

    [data-theme="dark"] .btn-primary {
        box-shadow: 0 4px 15px var(--shadow-dark);
    }

    [data-theme="dark"] .btn-primary:hover {
        box-shadow: 0 6px 20px var(--shadow-dark);
    }

    .btn-primary i {
        color: #fff;
        /* Icon màu trắng */
    }

    [data-theme="dark"] .btn-primary i {
        color: #fff;
        /* Icon màu trắng trong theme tối */
    }

    @media (min-width: 768px) {
        .movie-card {
            flex-direction: row;
        }

        .movie-poster {
            width: 250px;
            /* Thu nhỏ ảnh trên desktop */
            height: auto;
            border-right: 1px solid #eee;
            border-bottom: none;
        }

        [data-theme="dark"] .movie-poster {
            border-right-color: #333;
            border-bottom: none;
        }

        .movie-details {
            padding: 30px;
        }
    }

    .genre-badge {
        display: inline-block;
        background-color: var(--genre-bg);
        color: white;
        font-size: 13px;
        padding: 4px 10px;
        border-radius: 20px;
        margin: 5px 5px 0 0;
        transition: background 0.3s;
    }

    [data-theme="dark"] .genre-badge {
        background-color: var(--genre-bg-dark);
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
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Comment Section */
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

    .comment-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .comment-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px var(--shadow-light);
    }

    [data-theme="dark"] .comment-item {
        background-color: #2a2a2a;
        border-color: #404040;
    }

    [data-theme="dark"] .comment-item:hover {
        box-shadow: 0 6px 20px var(--shadow-dark);
    }

    [data-theme="dark"] .comment-item .card-body {
        color: var(--text-dark);
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
        padding-bottom: 56.25%;
        /* Tỷ lệ 16:9 */
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
        {{-- Thông báo tài khoản bị khóa --}}
        @if(Auth::check() && isset($isUserBanned) && $isUserBanned)
        <div class="alert alert-danger mb-4" role="alert">
            <h5 class="alert-heading">
                <i class="fa fa-ban me-2"></i>Tài khoản bị tạm khóa đặt vé
            </h5>
            <p class="mb-1">
                Tài khoản của bạn đã bị tạm khóa chức năng đặt vé do vi phạm quy định (đặt ghế nhiều lần mà không thanh toán).
            </p>
            <hr>
            <p class="mb-0">
                <strong>Thời gian khóa:</strong> Đến {{ $banInfo->banned_until->format('d/m/Y H:i') }}<br>
                <strong>Số lần vi phạm:</strong> {{ $banInfo->failed_attempts_count }} lần<br>
                <small class="text-muted">Vui lòng liên hệ admin nếu bạn cho rằng đây là nhầm lẫn.</small>
            </p>
        </div>
        @endif

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

                <div class="movie-info">
                    <div class="movie-info-item">
                        <strong>🎬 Đạo diễn:</strong>
                        <span>{{ $movie->director->name ?? 'Đang cập nhật' }}</span>
                    </div>

                    <div class="movie-info-item">
                        <strong>🎭 Diễn viên:</strong>
                        @if($movie->actors->isNotEmpty())
                        <span>
                            {{ $movie->actors->pluck('name')->join(', ') }}
                        </span>
                        @else
                        <span>Đang cập nhật</span>
                        @endif
                    </div>
                    <div class="movie-info-item">
                        <strong>⏳ Thời lượng:</strong>
                        <span>{{ $movie->duration_minutes }} phút</span>
                    </div>

                    <div class="movie-info-item">
                        <strong>📅 Phát hành:</strong>
                        <span>{{ $movie->release_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="movie-info-item">
                        <strong>🌍 Quốc gia:</strong>
                        <span>{{ $movie->country?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="movie-info-item">
                        <strong>🔞 Giới hạn tuổi:</strong>
                        <span>{{ $movie->ageLimit?->description ?? 'N/A' }}</span>
                    </div>
                </div>

                {{-- Thể loại --}}
                <div class="genres-container mt-3">
    <strong class="d-block mb-1">🎞️ Thể loại:</strong>
@foreach($movie->genres as $genre)
    <a href="{{ route('movies.filter', $genre->name) }}" class="genre-badge">
        {{ $genre->name }}
    </a>
@endforeach

</div>

                {{-- Nút đặt vé --}}
                @if($movie->status === 'showing')
                @auth
                    @if(isset($isUserBanned) && $isUserBanned)
                        <a href="#" class="btn btn-primary px-4 py-2 mt-4"
                            style="font-size: 16px; font-weight: 500;"
                            onclick="showBanAlert(event, '{{ $banInfo->banned_until->format('d/m/Y H:i') }}', {{ $banInfo->failed_attempts_count }})">
                            <i class="fa fa-ticket-alt" style="color: #fff;"></i> Đặt vé ngay
                        </a>
                    @else
                        <a href="{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}"
                            class="btn btn-primary px-4 py-2 mt-4"
                            style="font-size: 16px; font-weight: 500;">
                            <i class="fa fa-ticket-alt" style="color: #fff;"></i> Đặt vé ngay
                        </a>
                    @endif
                @else
                <a href="#"
                    class="btn btn-primary px-4 py-2 mt-4"
                    style="font-size: 16px; font-weight: 500;"
                    onclick="showLoginPrompt(event, '{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}')">
                    <i class="fa fa-ticket-alt" style="color: #fff;"></i> Đặt vé ngay
                </a>
                @endauth
                @endif


            </div>
        </div>

<div class="movie-detail mt-4 mb-5 text-center">
    <h3 style="font-size: 24px; font-weight: bold; margin-bottom: 15px; color: #222;">
        📝 Chi tiết
    </h3>
    <p style="font-size: 18px; line-height: 1.8; color: #333;">
        {{ $movie->description }}
    </p>
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

        {{-- Bình luận --}}
        <div class="mt-5">
            <div class="row">
                <div class="col-12">
                    <h5 class="fw-bold mb-4" style="font-size: 20px;">💬 Bình luận</h5>

                    {{-- Thống kê bình luận --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <h3 class="mb-1">{{ $movie->comments()->where('status', 'approved')->count() }}</h3>
                                <p class="mb-0">bình luận</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            {{-- Form bình luận --}}
                            @auth
                            @if($canComment)
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title">Bình luận về phim này</h6>
                                    <form id="commentForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Nội dung bình luận</label>
                                            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Chia sẻ cảm nghĩ của bạn về bộ phim..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>{{ $commentMessage }}
                            </div>
                            @endif
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập</a> để bình luận về phim này.
                            </div>
                            @endauth
                        </div>
                    </div>

                    {{-- Danh sách bình luận --}}
                    <div class="comments-section">
                        <h6 class="mb-3">Bình luận từ khán giả</h6>
                        <div id="commentsList">
                            @forelse($comments as $comment)
                            <div class="comment-item card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    @if($comment->comment)
                                    <p class="mb-0">{{ $comment->comment }}</p>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có bình luận nào cho phim này.</p>
                            </div>
                            @endforelse
                        </div>

                        @if($movie->comments()->where('status', 'approved')->count() > 5)
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary" id="loadMoreComments">Xem thêm bình luận</button>
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
        // Xử lý form submit bình luận
        const commentForm = document.getElementById('commentForm');
        if (commentForm) {
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const comment = document.getElementById('comment').value.trim();

                if (!comment) {
                    alert('Vui lòng nhập nội dung bình luận!');
                    return;
                }

                // Disable submit button
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Đang gửi...';

                // Send AJAX request
                fetch(`/movies/{{ $movie->id }}/comments`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            comment: comment
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide form and show success message
                            const alertClass = data.status === 'approved' ? 'alert-success' : 'alert-warning';
                            const icon = data.status === 'approved' ? 'fa-check-circle' : 'fa-clock';

                            commentForm.parentElement.innerHTML = `
                                <div class="alert ${alertClass}">
                                    <i class="fas ${icon} me-2"></i>${data.message}
                                </div>
                            `;

                            // Only add new comment to the list if it's approved
                            if (data.status === 'approved') {
                                const commentsList = document.getElementById('commentsList');
                                const newComment = createCommentElement(data.comment);

                                if (commentsList.querySelector('.text-center')) {
                                    // Replace "no comments" message
                                    commentsList.innerHTML = newComment;
                                } else {
                                    // Prepend to existing comments
                                    commentsList.insertAdjacentHTML('afterbegin', newComment);
                                }

                                // Reload page to update comment count
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                // For pending comments, just show message without reloading
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

        function createCommentElement(comment) {
            return `
                    <div class="comment-item card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        ${comment.user_name.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">${comment.user_name}</h6>
                                    </div>
                                </div>
                                <small class="text-muted">${comment.created_at}</small>
                            </div>
                            ${comment.comment ? `<p class="mb-0">${comment.comment}</p>` : ''}
                        </div>
                    </div>
                `;
        }

        // Function để show ban alert
        function showBanAlert(event, bannedUntil, failedAttempts) {
            event.preventDefault();
            
            Swal.fire({
                icon: 'error',
                title: 'Tài khoản bị tạm khóa',
                html: `
                    <div style="text-align: left; padding: 10px;">
                        <p><strong>Lý do:</strong> Vi phạm quy định đặt ghế (đặt ghế nhiều lần mà không thanh toán)</p>
                        <p><strong>Số lần vi phạm:</strong> ${failedAttempts} lần</p>
                        <p><strong>Thời gian khóa:</strong> Đến ${bannedUntil}</p>
                        <hr>
                        <p style="color: #666; font-size: 14px;"><i class="fa fa-info-circle"></i> Vui lòng liên hệ admin nếu bạn cho rằng đây là nhầm lẫn.</p>
                    </div>
                `,
                confirmButtonText: 'Đã hiểu',
                confirmButtonColor: '#dc3545',
                width: 500,
                customClass: {
                    popup: 'ban-alert-popup'
                }
            });
        }

        // Function để show login prompt (nếu chưa có)
        function showLoginPrompt(event, redirectUrl) {
            event.preventDefault();
            
            Swal.fire({
                icon: 'warning',
                title: 'Cần đăng nhập',
                text: 'Bạn cần đăng nhập để đặt vé xem phim.',
                showCancelButton: true,
                confirmButtonText: 'Đăng nhập',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#dc3545',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login?redirect=' + encodeURIComponent(redirectUrl);
                }
            });
        }
    });
</script>
@endsection