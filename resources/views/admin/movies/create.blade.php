@extends('layouts.admin.admin')

@section('content')

    <div class="container-xxl">
        <div class="row">
            <!-- Bên trái: Xem trước Poster -->
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="bg-light text-center rounded">
                            <img src="{{ asset('assets/images/default-poster.png') }}" alt="Poster Phim" class="avatar-xxl" id="poster-preview">
                        </div>
                        <div class="mt-3">
                            <h4>{{ old('name', 'Phim Mới') }}</h4>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1 mt-2">Trạng thái:</p>
                                    <h5 class="mb-0">{{ old('status', 'Bản nháp') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <button type="submit" form="movie-form" class="btn btn-primary w-100">Lưu Phim</button>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bên phải: Form nhập thông tin phim -->
            <div class="col-xl-9 col-lg-8">
                <!-- Thông tin chung -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin Phim</h4>
                    </div>
                    <div class="card-body">
                        <form id="movie-form" action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Tải lên Poster -->
                            <div class="mb-3">
                                <label for="poster" class="form-label">Tải lên Poster Phim</label>
                                <input name="poster" type="file" id="poster" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewPoster(event)">
                                @error('poster')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <img id="poster-preview" src="{{ asset('assets/images/default-poster.png') }}" alt="Poster Phim" style="max-width: 200px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên Phim <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên phim" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="director" class="form-label">Đạo diễn</label>
                                        <input type="text" id="director" name="director" class="form-control" placeholder="Nhập tên đạo diễn" value="{{ old('director') }}">
                                        @error('director')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="actors" class="form-label">Diễn viên</label>
                                        <input type="text" id="actors" name="actors" class="form-control" placeholder="Nhập diễn viên (phân tách bằng dấu phẩy)" value="{{ old('actors') }}">
                                        @error('actors')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="duration_minutes" class="form-label">Thời lượng (phút)</label>
                                        <input type="number" id="duration_minutes" name="duration_minutes" class="form-control" placeholder="Nhập thời lượng" value="{{ old('duration_minutes') }}" min="0">
                                        @error('duration_minutes')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="release_date" class="form-label">Ngày phát hành</label>
                                        <input type="date" id="release_date" name="release_date" class="form-control" value="{{ old('release_date') }}">
                                        @error('release_date')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="language" class="form-label">Ngôn ngữ</label>
                                        <input type="text" id="language" name="language" class="form-control" placeholder="Nhập ngôn ngữ" value="{{ old('language') }}">
                                        @error('language')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="country_id" class="form-label">Quốc gia</label>
                                        <select id="country_id" name="country_id" class="form-control" data-choices>
                                            <option value="">Chọn quốc gia</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="age_limit_id" class="form-label">Giới hạn độ tuổi</label>
                                        <select id="age_limit_id" name="age_limit_id" class="form-control" data-choices>
                                            <option value="">Chọn giới hạn độ tuổi</option>
                                            @foreach ($ageLimits as $ageLimit)
                                                <option value="{{ $ageLimit->id }}" {{ old('age_limit_id') == $ageLimit->id ? 'selected' : '' }}>{{ $ageLimit->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('age_limit_id')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="form-control" data-choices required>
                                            <option value="">Chọn trạng thái</option>
                                            <option value="showing" {{ old('status') == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                                            <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
                                            <option value="ended" {{ old('status') == 'ended' ? 'selected' : '' }}>Kết thúc</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="trailer_url" class="form-label">URL Trailer</label>
                                        <input type="url" id="trailer_url" name="trailer_url" class="form-control" placeholder="Nhập URL trailer" value="{{ old('trailer_url') }}">
                                        @error('trailer_url')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="average_rating" class="form-label">Điểm đánh giá (0-10)</label>
                                        <input type="number" id="average_rating" name="average_rating" class="form-control" placeholder="Nhập điểm đánh giá" value="{{ old('average_rating') }}" step="0.1" min="0" max="10">
                                        @error('average_rating')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="genres" class="form-label">Thể loại phim <span class="text-danger">*</span></label>
                                        <select id="genres" name="genres[]" class="form-control" multiple required>
                                            @foreach ($genres as $genre)
                                                <option value="{{ $genre->id }}" {{ (collect(old('genres'))->contains($genre->id)) ? 'selected' : '' }}>
                                                    {{ $genre->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('genres')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Mô tả</label>
                                        <textarea id="description" name="description" class="form-control bg-light-subtle" rows="7" placeholder="Nhập mô tả phim">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tùy chọn Meta -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Tùy chọn Meta</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="meta_title" class="form-label">Tiêu đề Meta</label>
                                                <input type="text" id="meta_title" name="meta_title" class="form-control" placeholder="Nhập tiêu đề meta" value="{{ old('meta_title') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="meta_keywords" class="form-label">Từ khóa Meta</label>
                                                <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" placeholder="Nhập từ khóa (phân tách bằng dấu phẩy)" value="{{ old('meta_keywords') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="meta_description" class="form-label">Mô tả Meta</label>
                                                <textarea id="meta_description" name="meta_description" class="form-control bg-light-subtle" rows="4" placeholder="Nhập mô tả meta">{{ old('meta_description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mt-3">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary w-100">Lưu Phim</button>
                                </div>
                                <div class="col-lg-6">
                                    <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

             
              
            </div>
        </div>
    </div>

@endsection

<script>
    function previewPoster(event) {
        const [file] = event.target.files;
        if (file) {
            document.querySelectorAll('#poster-preview').forEach(img => {
                img.src = URL.createObjectURL(file);
            });
        }
    }
</script>