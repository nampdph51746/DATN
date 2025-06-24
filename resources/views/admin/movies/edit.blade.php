@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-9 col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Chỉnh sửa phim: {{ $movie->name }}</h4>
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
                    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" id="movie-edit-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Poster phim lên đầu -->
                            <div class="col-lg-12 mb-3">
                                <label for="poster" class="form-label">Poster phim</label>
                                <input type="file" id="poster" name="poster" class="form-control" accept="image/*" onchange="previewPoster(event)">
                                @error('poster') <div class="text-danger mt-1">{{ $message }}</div> @enderror

                                <div class="mt-2">
                                    <img
                                        id="poster-preview"
                                        src="{{ $movie->poster_url ? asset('storage/'.$movie->poster_url) : asset('assets/images/default-poster.png') }}"
                                        alt="Poster phim"
                                        style="max-width: 120px; max-height: 180px;">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="name" class="form-label">Tên phim</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $movie->name) }}">
                                @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="director" class="form-label">Đạo diễn</label>
                                <input type="text" id="director" name="director" class="form-control" value="{{ old('director', $movie->director) }}">
                                @error('director') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="actors" class="form-label">Diễn viên</label>
                                <input type="text" id="actors" name="actors" class="form-control" value="{{ old('actors', $movie->actors) }}">
                                @error('actors') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="duration_minutes" class="form-label">Thời lượng (phút)</label>
                                <input type="number" id="duration_minutes" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $movie->duration_minutes) }}">
                                @error('duration_minutes') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="release_date" class="form-label">Ngày phát hành</label>
                                <input type="date" id="release_date" name="release_date" class="form-control" value="{{ old('release_date', $movie->release_date) }}">
                                @error('release_date') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc chiếu</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $movie->end_date) }}">
                                @error('end_date') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="language" class="form-label">Ngôn ngữ</label>
                                <input type="text" id="language" name="language" class="form-control" value="{{ old('language', $movie->language) }}">
                                @error('language') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="country_id" class="form-label">Quốc gia</label>
                                <select id="country_id" name="country_id" class="form-control">
                                    <option value="">Chọn quốc gia</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country_id', $movie->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="age_limit_id" class="form-label">Giới hạn độ tuổi</label>
                                <select id="age_limit_id" name="age_limit_id" class="form-control">
                                    <option value="">Chọn độ tuổi</option>
                                    @foreach ($ageLimits as $ageLimit)
                                        <option value="{{ $ageLimit->id }}" {{ old('age_limit_id', $movie->age_limit_id) == $ageLimit->id ? 'selected' : '' }}>{{ $ageLimit->name }}</option>
                                    @endforeach
                                </select>
                                @error('age_limit_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="showing" {{ old('status', $movie->status) == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                                    <option value="upcoming" {{ old('status', $movie->status) == 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
                                    <option value="ended" {{ old('status', $movie->status) == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                                </select>
                                @error('status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="average_rating" class="form-label">Điểm đánh giá (0-10)</label>
                                <input type="number" id="average_rating" name="average_rating" class="form-control" value="{{ old('average_rating', $movie->average_rating) }}" step="0.1" min="0" max="10">
                                @error('average_rating') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="trailer_url" class="form-label">Trailer URL</label>
                                <input type="url" id="trailer_url" name="trailer_url" class="form-control" value="{{ old('trailer_url', $movie->trailer_url) }}">
                                @error('trailer_url') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="average_rating" class="form-label">Điểm đánh giá (0-10)</label>
                                    <input type="number" name="average_rating" id="average_rating" class="form-control" value="{{ old('average_rating', $movie->average_rating) }}" step="0.1" min="0" max="10">
                                    @error('average_rating')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea id="description" name="description" class="form-control" rows="5">{{ old('description', $movie->description) }}</textarea>
                                @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewPoster(event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('poster-preview').src = URL.createObjectURL(file);
    }
}
</script>
@endpush
