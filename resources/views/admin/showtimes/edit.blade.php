@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ $showtime->movie->poster ?? 'assets/images/default-movie-poster.png' }}" alt="Movie Poster" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Cập nhật suất chiếu #{{ $showtime->id }}</h4>
                        <p class="text-muted">Chỉnh sửa thông tin suất chiếu cho phim {{ $showtime->movie->name ?? 'Không xác định' }}.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="showtimeEditForm" class="btn btn-primary w-100">Lưu</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin suất chiếu</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="showtimeEditForm" action="{{ route('admin.showtimes.update', $showtime->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="movie_id" class="form-label">Phim</label>
                                    <select name="movie_id" id="movie_id" class="form-control" required>
                                        @foreach ($movies as $movie)
                                            <option value="{{ $movie->id }}" {{ $showtime->movie_id == $movie->id ? 'selected' : '' }}>{{ $movie->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('movie_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="room_id" class="form-label">Phòng chiếu</label>
                                    <select name="room_id" id="room_id" class="form-control" required>
                                        @foreach ($rooms as $room)
                                            <option value="{{ $room->id }}" {{ $showtime->room_id == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Thời gian bắt đầu</label>
                                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ \Carbon\Carbon::parse($showtime->start_time)->format('Y-m-d\TH:i') }}" required>
                                    @error('start_time')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">Thời gian kết thúc</label>
                                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="{{ \Carbon\Carbon::parse($showtime->end_time)->format('Y-m-d\TH:i') }}" required>
                                    @error('end_time')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="base_price" class="form-label">Giá vé cơ bản (VNĐ)</label>
                                    <input type="number" name="base_price" id="base_price" class="form-control" value="{{ number_format($showtime->base_price, 2) }}" step="0.01" min="0" max="99999.99" required>
                                    @error('base_price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-control" required>
                                        @foreach (App\Enums\ShowtimeStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $showtime->status->value == $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection