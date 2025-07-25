@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid py-4">
    @include('admin.partials.notifications')
    
    <div class="row">
        <!-- Sidebar thông tin -->
        <div class="col-xl-3 col-lg-4 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <img src="{{ asset('assets/images/movie-icon.png') }}" alt="Movie Icon" class="img-fluid rounded mb-3" style="max-width: 150px;">
                    <div>
                        <h4 class="card-title mb-2">Thêm suất chiếu</h4>
                        <p class="text-muted mb-0">Tạo một hoặc nhiều suất chiếu mới cho phim tại phòng chiếu được chọn.</p>
                    </div>
                </div>
                <div class="card-footer bg-light border-top">
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="submit" form="showtimeForm" class="btn btn-primary w-100">Lưu</button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form chính -->
        <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin suất chiếu</h4>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="showtimeForm" action="{{ route('admin.showtimes.store') }}" method="POST">
                        @csrf
                        <div id="showtimes">
                            <div class="showtime-entry mb-4">
                                <h5>Suất chiếu 1</h5>
                                <div class="row g-3">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="mb-3">
                                            <label for="showtimes[0][movie_id]" class="form-label">Phim</label>
                                            <select name="showtimes[0][movie_id]" class="form-control" required>
                                                <option value="">Chọn phim</option>
                                                @foreach ($movies as $movie)
                                                    <option value="{{ $movie->id }}">{{ $movie->name }} ({{ $movie->duration }} phút)</option>
                                                @endforeach
                                            </select>
                                            @error('showtimes.0.movie_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="mb-3">
                                            <label for="showtimes[0][room_id]" class="form-label">Phòng chiếu</label>
                                            <select name="showtimes[0][room_id]" class="form-control" required>
                                                <option value="">Chọn phòng chiếu</option>
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('showtimes.0.room_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="mb-3">
                                            <label for="showtimes[0][start_time]" class="form-label">Thời gian bắt đầu</label>
                                            <input type="datetime-local" name="showtimes[0][start_time]" class="form-control" required>
                                            @error('showtimes.0.start_time')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="mb-3">
                                            <label for="showtimes[0][end_time]" class="form-label">Thời gian kết thúc</label>
                                            <input type="datetime-local" name="showtimes[0][end_time]" class="form-control" required>
                                            @error('showtimes.0.end_time')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="mb-3">
                                            <label for="showtimes[0][base_price]" class="form-label">Giá vé cơ bản (VNĐ)</label>
                                            <input type="number" name="showtimes[0][base_price]" class="form-control" step="0.01" min="0" max="99999.99" required>
                                            @error('showtimes.0.base_price')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="mb-3">
                                            <label for="showtimes[0][status]" class="form-label">Trạng thái</label>
                                            <select name="showtimes[0][status]" class="form-control" required>
                                                <option value="scheduled">Scheduled</option>
                                                <option value="ongoing">Ongoing</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                            @error('showtimes.0.status')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-showtime" class="btn btn-secondary mb-3">Thêm suất chiếu khác</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-control, .form-select {
        max-width: 100%;
        min-width: 200px;
    }
    @media (max-width: 767px) {
        .form-control, .form-select {
            min-width: 100%;
        }
    }
    .showtime-entry {
        border: 1px solid #e9ecef;
        padding: 15px;
        border-radius: 5px;
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('scripts')
<script>
    let index = 1;
    document.getElementById('add-showtime').addEventListener('click', function() {
        const container = document.getElementById('showtimes');
        const entry = document.querySelector('.showtime-entry').cloneNode(true);
        entry.querySelector('h5').textContent = `Suất chiếu ${index + 1}`;
        entry.querySelectorAll('select, input').forEach(input => {
            input.name = input.name.replace(/\[0\]/, `[${index}]`);
            input.value = '';
            const errorSpan = input.nextElementSibling;
            if (errorSpan && errorSpan.classList.contains('text-danger')) {
                errorSpan.remove();
            }
        });
        container.appendChild(entry);
        index++;
    });
</script>
@endsection