@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('assets/images/movie-placeholder.png') }}" alt="Movie Placeholder" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Thêm phim mới</h4>
                        <p class="text-muted">Nhập thông tin chi tiết để thêm phim vào hệ thống.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="movieForm" class="btn btn-primary w-100">Thêm phim</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin phim</h4>
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
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form id="movieForm" action="{{ route('admin.movies.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên phim</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="director" class="form-label">Đạo diễn</label>
                                    <input type="text" name="director" id="director" class="form-control" value="{{ old('director') }}">
                                    @error('director')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="actors" class="form-label">Diễn viên</label>
                                    <input type="text" name="actors" id="actors" class="form-control" value="{{ old('actors') }}">
                                    @error('actors')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="duration_minutes" class="form-label">Thời lượng (phút)</label>
                                    <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="{{ old('duration_minutes') }}" min="1" required>
                                    @error('duration_minutes')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="release_date" class="form-label">Ngày phát hành</label>
                                    <input type="date" name="release_date" id="release_date" class="form-control" value="{{ old('release_date') }}" required>
                                    @error('release_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="language" class="form-label">Ngôn ngữ</label>
                                    <input type="text" name="language" id="language" class="form-control" value="{{ old('language') }}">
                                    @error('language')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Quốc gia</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Chọn quốc gia</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="age_limit_id" class="form-label">Giới hạn độ tuổi</label>
                                    <select name="age_limit_id" id="age_limit_id" class="form-control">
                                        <option value="">Chọn giới hạn độ tuổi</option>
                                        @foreach ($ageLimits as $ageLimit)
                                            <option value="{{ $ageLimit->id }}" {{ old('age_limit_id') == $ageLimit->id ? 'selected' : '' }}>{{ $ageLimit->name ?? $ageLimit->label }}</option>
                                        @endforeach
                                    </select>
                                    @error('age_limit_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="ended" {{ old('status') == 'ended' ? 'selected' : '' }}>Ended</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="poster_url" class="form-label">URL Poster</label>
                                    <input type="url" name="poster_url" id="poster_url" class="form-control" value="{{ old('poster_url') }}">
                                    @error('poster_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="trailer_url" class="form-label">URL Trailer</label>
                                    <input type="url" name="trailer_url" id="trailer_url" class="form-control" value="{{ old('trailer_url') }}">
                                    @error('trailer_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="genre_ids" class="form-label">Thể loại</label>
                                <select name="genre_ids[]" id="genre_ids" class="form-control select2" multiple required>
                                    @foreach ($genres as $genre)
                                        <option value="{{ $genre->id }}" {{ in_array($genre->id, old('genre_ids', [])) ? 'selected' : '' }}>{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                                @error('genre_ids')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="average_rating" class="form-label">Điểm đánh giá (0-10)</label>
                                    <input type="number" name="average_rating" id="average_rating" class="form-control" value="{{ old('average_rating') }}" step="0.1" min="0" max="10">
                                    @error('average_rating')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" id="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                                    @error('description')
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
