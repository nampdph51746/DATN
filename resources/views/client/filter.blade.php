@extends('layouts.client.client')

@section('content')
<style>
    .w3l-grids {
        padding-top: 60px;
    }

    .movie-poster {
        width: 100%;
        height: 350px;
        /* Chiều cao cố định */
        object-fit: cover;
        /* Cắt ảnh để vừa khung mà không bị méo */
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .movie-card {
        border-radius: 8px;
        overflow: hidden;
        /* Đảm bảo bo góc ảnh và card đồng bộ */
        background: #fff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .movie-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .section-title {
        position: relative;
        display: block;
        font-size: 2rem;
        font-weight: bold;
        padding-bottom: 8px;
    }

    .section-title::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 3px;
        background-color: #dc3545;
        border-radius: 2px;
    }

    .movie-title {
        font-size: 1.3rem;
        /* To hơn */
        font-weight: 800;
        /* Siêu đậm */
        background: #337ab7;
        /* Gradient đỏ -> vàng */
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
        position: relative;
        display: inline-block;
        transition: all 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .movie-title::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -3px;
        width: 0;
        height: 3px;
        background: #337ab7;
        transition: width 0.3s ease;
        border-radius: 2px;
    }

    .movie-title:hover::after {
        width: 100%;
    }

    .movie-title:hover {
        transform: scale(1.05);
    }

    .filter-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 0 auto 30px auto;
    }

    /* Dòng form điều khiển */
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: flex-end;
        justify-content: center;
    }

    /* Mỗi input block */
    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 140px;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #343a40;
        font-size: 0.9rem;
    }

    /* Input chung */
    .filter-input {
        padding: 6px 10px;
        border: 1.5px solid #ced4da;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: border-color 0.3s ease;
    }

    .filter-input:focus {
        outline: none;
        border-color: #dc3545;
        box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
    }

    /* Nút Lọc */
    .btn-filter {
        background-color: #dc3545;
        border: none;
        color: white;
        padding: 8px 20px;
        font-weight: 700;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        min-width: 120px;
    }

    .btn-filter:hover {
        background-color: #b02a37;
    }

    .movie-genre {
    white-space: nowrap;       /* Chỉ hiển thị 1 dòng */
    overflow: hidden;          /* Ẩn phần vượt quá */
    text-overflow: ellipsis;   /* Hiện dấu ... */
    display: block;            /* Đảm bảo hoạt động trong inline context */
    max-width: 100%;           /* Giới hạn trong phần card */
}

    /* Responsive nhỏ */
    @media (max-width: 576px) {
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group,
        .btn-filter {
            width: 100%;
        }
    }

    .no-movies-message {
        display: flex;
        justify-content: center;
        /* căn ngang */
        align-items: center;
        /* căn dọc */
        min-height: 400px;
        /* chiều cao tối thiểu */
        font-size: 2rem;
        font-weight: 700;
        color: #dc3545;
        text-align: center;
        padding: 0 20px;
        width: 100%;
    }

    .badge-top {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(45deg, #ff512f, #dd2476);
    color: #fff;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.9rem;
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    z-index: 10;
}

</style>
<section class="w3l-grids">
    <div class="container py-4">
        <h1 class="mb-4 section-title">{{ $title }}</h1>
        @if ($isShowing)
        <div class="filter-container">
            @if ($errors->any())
            <div class="alert alert-danger mb-4 max-w-900 mx-auto">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h2 style="color:#dc3545; font-weight: 700; text-align:center; margin-bottom:1rem;">
                Bộ lọc thời gian chiếu phim
            </h2>
            <div class="filter-row">
                <div class="filter-group">
                    <label for="filter-date" class="filter-label">Ngày:</label>
                    <input type="date" id="filter-date" class="filter-input" value="{{ request('date') }}">
                </div>
                <div class="filter-group">
                    <label for="from-time" class="filter-label">Từ:</label>
                    <input type="time" id="from-time" name="from_time" class="filter-input" value="{{ request('from_time') }}">
                </div>
                <div class="filter-group">
                    <label for="to-time" class="filter-label">Đến:</label>
                    <input type="time" id="to-time" name="to_time" class="filter-input" value="{{ request('to_time') }}">
                </div>
                <div class="filter-group" style="align-self: center;">
                    <button class="btn-filter" onclick="applyFilters()">Lọc phim</button>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            @forelse($movies as $movie)
            <div class="col-md-3 col-sm-6 mb-4">
                <a href="{{ route('movies.show', ['id' => $movie->id]) }}" class="text-decoration-none text-dark">
                    <div class="movie-card shadow-sm h-100">
                        <div class="position-relative">
                            <img                                             src="{{ $movie->image_path ? Storage::url($movie->image_path) : ($movie->poster_url ?? asset('client_assets/assets/images/default-movie.jpg')) }}"

                                class="img-fluid rounded-top movie-poster"
                                alt="{{ $movie->name }}">

                                {{-- ✅ Thêm badge TOP cho 3 phim đầu --}}
                    @if($isShowing && $loop->iteration <= 3)
                        <span class="badge-top">TOP {{ $loop->iteration }}</span>
                    @endif
                        </div>

                        <div class="p-3">
                            <h6 class="movie-title">{{ $movie->name }}</h6>
                            <p class="mb-1 movie-genre">
                                <strong>Thể loại:</strong>
                                {{ $movie->genres->take(3)->pluck('name')->join(', ') ?: 'N/A' }}
                            </p>
                            <p class="mb-2"><strong>Thời lượng:</strong> {{ $movie->duration_minutes }} phút</p>

                            @auth
                            @if ($movie->status->value === 'showing')
                            <a href="{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}" class="btn btn-primary w-100">
                                🎟 ĐẶT VÉ
                            </a>
                            @endif
                            @else
                            @if ($movie->status->value === 'showing')
                            <a href="#" class="btn btn-primary w-100" onclick="showLoginPrompt(event, '{{ route('client.movies.ticketBooking', ['id' => $movie->id]) }}')">
                                🎟 ĐẶT VÉ
                            </a>
                            @endif
                            @endauth
                        </div>
                    </div>
                </a>
            </div>

            @empty
            <div class="no-movies-message">
                Không tìm thấy phim phù hợp.
            </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $movies->appends(request()->query())->links() }}
        </div>
    </div>
</section>
<script>
    function applyFilters() {
        const date = document.getElementById('filter-date').value;
        const fromTime = document.getElementById('from-time').value;
        const toTime = document.getElementById('to-time').value;

        const url = new URL(window.location.href);
        const params = url.searchParams;

        if (date) {
            params.set('date', date);
        } else {
            params.delete('date');
        }

        if (fromTime) {
            params.set('from_time', fromTime);
        } else {
            params.delete('from_time');
        }

        if (toTime) {
            params.set('to_time', toTime);
        } else {
            params.delete('to_time');
        }

        url.search = params.toString();

        window.location.href = url.toString();
    }

    const dateInput = document.getElementById('filter-date');
    const fromTimeInput = document.getElementById('from-time');
    const toTimeInput = document.getElementById('to-time');

    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);

    dateInput.addEventListener('change', () => {
        const selectedDate = dateInput.value;
        const now = new Date();
        const currentDate = now.toISOString().split('T')[0];
        const currentTime = now.toTimeString().slice(0, 5);

        if (selectedDate === currentDate) {
            fromTimeInput.min = currentTime;
            toTimeInput.min = currentTime;

            if (fromTimeInput.value && fromTimeInput.value < currentTime) {
                fromTimeInput.value = currentTime;
            }
            if (toTimeInput.value && toTimeInput.value < currentTime) {
                toTimeInput.value = currentTime;
            }
        } else {
            fromTimeInput.removeAttribute('min');
            toTimeInput.removeAttribute('min');
        }
    });

    window.addEventListener('load', () => {
        dateInput.dispatchEvent(new Event('change'));
    });
</script>
@include('client.footer.footer')
@endsection