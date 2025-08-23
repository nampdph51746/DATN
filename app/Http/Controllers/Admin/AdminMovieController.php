<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Country;
use App\Models\AgeLimit;
use App\Models\Review;
use App\Models\Room;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function Termwind\parse;

class AdminMovieController extends Controller
{
    // Danh sách phim
    public function index(Request $request)
    {
        // Tự động cập nhật trạng thái các phim đã kết thúc
        Movie::updateExpiredMovies();

        $query = $request->input('query');
        $status = $request->input('status');
        $countryId = $request->input('country_id');
        $ageLimitId = $request->input('age_limit_id');
        $genreId = $request->input('genre_id');
        $releaseDate = $request->input('release_date');
        $endDate = $request->input('end_date');

        $movies = Movie::query()
            ->with(['country', 'ageLimit', 'genres', 'director', 'actors'])
            ->when($query, function ($queryBuilder, $query) {
                return $queryBuilder->where('name', 'like', "%{$query}%")
                    ->orWhereHas('director', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })
                    ->orWhereHas('actors', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })
                    ->orWhere('language', 'like', "%{$query}%");
            })
            ->when($status && $status !== 'all', function ($queryBuilder) use ($status) {
                return $queryBuilder->where('status', $status);
            })
            ->when($countryId, function ($queryBuilder, $countryId) {
                return $queryBuilder->where('country_id', $countryId);
            })
            ->when($ageLimitId, function ($queryBuilder, $ageLimitId) {
                return $queryBuilder->where('age_limit_id', $ageLimitId);
            })
            ->when($genreId, function ($queryBuilder, $genreId) {
                return $queryBuilder->whereHas('genres', function ($q) use ($genreId) {
                    $q->where('genres.id', $genreId);
                });
            })
            ->when($releaseDate, function ($queryBuilder, $releaseDate) {
                return $queryBuilder->where('release_date', '>=', $releaseDate);
            })
            ->when($endDate, function ($queryBuilder, $endDate) {
                return $queryBuilder->where('end_date', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Lấy danh sách quốc gia
        $countries = Country::all();

        // Lấy danh sách giới hạn độ tuổi (nếu có dùng)
        $ageLimits = AgeLimit::all();

        // Lấy danh sách thể loại
        $genres = Genre::all();

        return view('admin.movies.index', compact('movies', 'countries', 'ageLimits', 'genres'));
    }

    // Form tạo phim
    public function create()
    {
        $countries = Country::all();
        $ageLimits = AgeLimit::all();
        $genres = Genre::all();
        $directors = \App\Models\Director::where('is_active', true)->orderBy('name')->get();
        $actors = \App\Models\Actor::where('is_active', true)->orderBy('name')->get();
        return view('admin.movies.create', compact('countries', 'ageLimits', 'genres', 'directors', 'actors'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);

        // Kiểm tra xem có phim nào không thể xóa không
        $unableToDeleteMovies = Movie::whereIn('id', $ids)->get()->filter(function ($movie) {
            return !$movie->canBeEdited();
        });

        if ($unableToDeleteMovies->count() > 0) {
            $unableToDeleteMovieNames = $unableToDeleteMovies->pluck('name')->join(', ');
            return redirect()->route('admin.movies.index')
                ->with('error', 'Không thể xóa những phim sau (đã kết thúc, đang chiếu hoặc đã có vé được đặt): ' . $unableToDeleteMovieNames);
        }

        DB::beginTransaction();
        try {
            // Lấy tất cả showtimes liên quan đến các phim cần xóa
            $showtimes = \App\Models\Showtime::whereIn('movie_id', $ids)->get();

            // Xóa tất cả seat states và tickets liên quan đến các showtimes này
            foreach ($showtimes as $showtime) {
                $showtime->seatStates()->delete();
                $showtime->tickets()->delete();
            }

            // Xóa các showtimes
            Showtime::whereIn('movie_id', $ids)->delete();

            // Xóa các reviews liên quan đến các phim này
            Review::whereIn('movie_id', $ids)->delete();

            // Xóa các bản ghi liên kết thể loại phim
            DB::table('movie_genres')->whereIn('movie_id', $ids)->delete();

            // Xóa các phim
            Movie::whereIn('id', $ids)->delete();

            DB::commit();
            return redirect()->route('admin.movies.index')->with('success', 'Đã xóa các phim đã chọn!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.movies.index')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // Lưu phim mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:movies,name',
            'director_id' => 'required|array|exists:directors,id',
            'actor_ids' => 'required|array|min:1|max:10',
            'actor_ids.*' => 'exists:actors,id',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'release_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'description' => 'required|string|min:10|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'poster_url' => 'nullable|url|max:500',
            'trailer_url' => 'nullable|url|max:500',
            'language' => 'required|string|max:50',
            'country_id' => 'required|integer|exists:countries,id',
            'age_limit_id' => 'required|integer|exists:age_limits,id',
            'genre_ids' => 'required|array|min:1|max:5',
            'genre_ids.*' => 'exists:genres,id',
            'average_rating' => 'nullable|numeric|min:0|max:10',
        ], [
            // Thông báo lỗi tiếng Việt chuyên nghiệp
            'name.required' => 'Tên phim là trường bắt buộc.',
            'name.unique' => 'Tên phim này đã tồn tại trong hệ thống.',
            'name.max' => 'Tên phim không được vượt quá 255 ký tự.',
            'director_id.required' => 'Vui lòng chọn đạo diễn.',
            'release_date.after_or_equal' => 'Ngày phát hành phải lớn hơn hoặc bằng hôm nay.',
            'director_id.exists' => 'Đạo diễn được chọn không tồn tại.',
            'actor_ids.required' => 'Vui lòng chọn ít nhất một diễn viên.',
            'actor_ids.min' => 'Phim phải có ít nhất một diễn viên.',
            'actor_ids.max' => 'Phim không được có quá 10 diễn viên.',
            'actor_ids.*.exists' => 'Một số diễn viên được chọn không tồn tại.',
            'duration_minutes.required' => 'Thời lượng phim là trường bắt buộc.',
            'duration_minutes.integer' => 'Thời lượng phim phải là số nguyên.',
            'duration_minutes.min' => 'Thời lượng phim phải ít nhất 1 phút.',
            'duration_minutes.max' => 'Thời lượng phim không được vượt quá 600 phút (10 giờ).',
            'release_date.required' => 'Ngày phát hành là trường bắt buộc.',
            'release_date.date' => 'Ngày phát hành phải là định dạng ngày hợp lệ.',
            'end_date.date' => 'Ngày kết thúc phải là định dạng ngày hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày phát hành.',
            'description.required' => 'Mô tả phim là trường bắt buộc.',
            'description.min' => 'Mô tả phim phải có ít nhất 10 ký tự.',
            'description.max' => 'Mô tả phim không được vượt quá 5000 ký tự.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 5MB.',
            'poster_url.url' => 'Đường dẫn poster phải là URL hợp lệ.',
            'poster_url.max' => 'Đường dẫn poster không được vượt quá 500 ký tự.',
            'trailer_url.url' => 'Đường dẫn trailer phải là URL hợp lệ.',
            'trailer_url.max' => 'Đường dẫn trailer không được vượt quá 500 ký tự.',
            'language.required' => 'Ngôn ngữ phim là trường bắt buộc.',
            'language.max' => 'Ngôn ngữ không được vượt quá 50 ký tự.',
            'country_id.required' => 'Vui lòng chọn quốc gia sản xuất.',
            'country_id.exists' => 'Quốc gia được chọn không tồn tại.',
            'age_limit_id.required' => 'Vui lòng chọn giới hạn độ tuổi.',
            'age_limit_id.exists' => 'Giới hạn độ tuổi được chọn không tồn tại.',
            'genre_ids.required' => 'Vui lòng chọn ít nhất một thể loại phim.',
            'genre_ids.min' => 'Phim phải có ít nhất một thể loại.',
            'genre_ids.max' => 'Phim không được có quá 5 thể loại.',
            'genre_ids.*.exists' => 'Một số thể loại được chọn không tồn tại.',
            'average_rating.numeric' => 'Điểm đánh giá phải là số.',
            'average_rating.min' => 'Điểm đánh giá phải từ 0 trở lên.',
            'average_rating.max' => 'Điểm đánh giá không được vượt quá 10.',
        ]);

        $data = $request->except(['image', 'genre_ids', 'actor_ids', 'director_id']);


        // Đảm bảo thư mục tồn tại
        $posterDir = storage_path('app/public/posters');
        if (!File::exists($posterDir)) {
            File::makeDirectory($posterDir, 0755, true);
        }

        // Xử lý upload ảnh poster
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posters', 'public');
            $data['image_path'] = $path;
        }

        $movie = Movie::create($data);

        if ($request->has('director_id')) {
            $movie->directors()->sync($request->input('director_id'));
        }

        // Liên kết với thể loại
        if ($request->has('genre_ids')) {
            $movie->genres()->sync($request->input('genre_ids'));
        }

        // Liên kết với diễn viên
        if ($request->has('actor_ids')) {
            $movie->actors()->sync($request->input('actor_ids'));
        }

        return redirect()->route('admin.movies.index')->with([
            'success' => 'Phim "' . $movie->name . '" đã được thêm thành công!',
            'movie_created' => true,
            'movie_id' => $movie->id
        ]);
    }

    // Form sửa phim
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);

        // Kiểm tra xem phim có thể chỉnh sửa không
        if (!$movie->canBeEdited()) {
            $reason = '';
            $movieStatus = is_object($movie->status) ? $movie->status->value : $movie->status;

            if ($movieStatus === 'ended') {
                $reason = 'Không thể chỉnh sửa phim đã kết thúc!';
            } elseif ($movieStatus === 'showing') {
                $reason = 'Không thể chỉnh sửa phim đang chiếu!';
            } elseif ($movie->hasBookedTickets()) {
                $reason = 'Không thể chỉnh sửa phim đã có vé được đặt!';
            }

            return redirect()->route('admin.movies.index')->with('error', $reason);
        }

        $countries = Country::all();
        $ageLimits = AgeLimit::all();
        $genres = Genre::all();
        $directors = \App\Models\Director::where('is_active', true)->orderBy('name')->get();
        $actors = \App\Models\Actor::where('is_active', true)->orderBy('name')->get();
        return view('admin.movies.edit', compact('movie', 'countries', 'ageLimits', 'genres', 'directors', 'actors'));
    }

    // Cập nhật phim
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        // Kiểm tra xem phim có thể chỉnh sửa không
        if (!$movie->canBeEdited()) {
            $reason = '';
            $movieStatus = is_object($movie->status) ? $movie->status->value : $movie->status;

            if ($movieStatus === 'ended') {
                $reason = 'Không thể cập nhật phim đã kết thúc!';
            } elseif ($movieStatus === 'showing') {
                $reason = 'Không thể cập nhật phim đang chiếu!';
            } elseif ($movie->hasBookedTickets()) {
                $reason = 'Không thể cập nhật phim đã có vé được đặt!';
            }

            return redirect()->route('admin.movies.index')->with('error', $reason);
        }

        $request->validate([
                'name' => 'required|string|max:255|unique:movies,name,' . $id,
                'director_id' => 'required|array|exists:directors,id',
                'actor_ids' => 'required|array|min:1|max:10',
                'actor_ids.*' => 'exists:actors,id',
                'duration_minutes' => 'required|integer|min:1|max:600',
                'release_date' => 'required|date|after_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:release_date',
                'description' => 'required|string|min:10|max:5000',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'poster_url' => 'nullable|url|max:500',
                'trailer_url' => 'nullable|url|max:500',
                'language' => 'required|string|max:50',
                'country_id' => 'required|integer|exists:countries,id',
                'age_limit_id' => 'required|integer|exists:age_limits,id',
                'genre_ids' => 'required|array|min:1|max:5',
                'genre_ids.*' => 'exists:genres,id',
                'average_rating' => 'nullable|numeric|min:0|max:10',
            ], [
                // Thông báo lỗi tiếng Việt chuyên nghiệp
                'name.required' => 'Tên phim là trường bắt buộc.',
                'name.unique' => 'Tên phim này đã tồn tại trong hệ thống.',
                'name.max' => 'Tên phim không được vượt quá 255 ký tự.',
                'director_id.required' => 'Vui lòng chọn đạo diễn.',
                'director_id.exists' => 'Đạo diễn được chọn không tồn tại.',
                'actor_ids.required' => 'Vui lòng chọn ít nhất một diễn viên.',
                'actor_ids.min' => 'Phim phải có ít nhất một diễn viên.',
                'actor_ids.max' => 'Phim không được có quá 10 diễn viên.',
                'actor_ids.*.exists' => 'Một số diễn viên được chọn không tồn tại.',
                'duration_minutes.required' => 'Thời lượng phim là trường bắt buộc.',
                'duration_minutes.integer' => 'Thời lượng phim phải là số nguyên.',
                'duration_minutes.min' => 'Thời lượng phim phải ít nhất 1 phút.',
                'duration_minutes.max' => 'Thời lượng phim không được vượt quá 600 phút (10 giờ).',
                'release_date.required' => 'Ngày phát hành là trường bắt buộc.',
                'release_date.date' => 'Ngày phát hành phải là định dạng ngày hợp lệ.',
                 'release_date.after_or_equal' => 'Ngày phát hành phải lớn hơn hoặc bằng hôm nay.',
                'end_date.date' => 'Ngày kết thúc phải là định dạng ngày hợp lệ.',
                'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày phát hành.',
                'description.required' => 'Mô tả phim là trường bắt buộc.',
                'description.min' => 'Mô tả phim phải có ít nhất 10 ký tự.',
                'description.max' => 'Mô tả phim không được vượt quá 5000 ký tự.',
                'poster.image' => 'File tải lên phải là hình ảnh.',
                'poster.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
                'poster.max' => 'Kích thước hình ảnh không được vượt quá 5MB.',
                'poster_url.url' => 'Đường dẫn poster phải là URL hợp lệ.',
                'poster_url.max' => 'Đường dẫn poster không được vượt quá 500 ký tự.',
                'trailer_url.url' => 'Đường dẫn trailer phải là URL hợp lệ.',
                'trailer_url.max' => 'Đường dẫn trailer không được vượt quá 500 ký tự.',
                'language.required' => 'Ngôn ngữ phim là trường bắt buộc.',
                'language.max' => 'Ngôn ngữ không được vượt quá 50 ký tự.',
                'country_id.required' => 'Vui lòng chọn quốc gia sản xuất.',
                'country_id.exists' => 'Quốc gia được chọn không tồn tại.',
                'age_limit_id.required' => 'Vui lòng chọn giới hạn độ tuổi.',
                'age_limit_id.exists' => 'Giới hạn độ tuổi được chọn không tồn tại.',
                'genre_ids.required' => 'Vui lòng chọn ít nhất một thể loại phim.',
                'genre_ids.min' => 'Phim phải có ít nhất một thể loại.',
                'genre_ids.max' => 'Phim không được có quá 5 thể loại.',
                'genre_ids.*.exists' => 'Một số thể loại được chọn không tồn tại.',
                'average_rating.numeric' => 'Điểm đánh giá phải là số.',
                'average_rating.min' => 'Điểm đánh giá phải từ 0 trở lên.',
                'average_rating.max' => 'Điểm đánh giá không được vượt quá 10.',
            ]);

        $data = $request->except(['poster', 'genre_ids', 'actor_ids', 'director_id']);

        // Đảm bảo thư mục tồn tại
        $posterDir = storage_path('app/public/posters');
        if (!File::exists($posterDir)) {
            File::makeDirectory($posterDir, 0755, true);
        }

        // Xử lý upload ảnh poster mới
        if ($request->hasFile('poster')) {
            if ($movie->image_path && Storage::disk('public')->exists($movie->image_path)) {
                Storage::disk('public')->delete($movie->image_path);
            }

            $posterPath = $request->file('poster')->store('posters', 'public');
            $data['image_path'] = $posterPath;
        }

        $movie->update($data);

        // Liên kết với đạo diễn (nhiều đạo diễn)
        if ($request->has('director_id')) {
            $movie->directors()->sync($request->input('director_id'));
        }

        // Liên kết với thể loại
        if ($request->has('genre_ids')) {
            $movie->genres()->sync($request->input('genre_ids'));
        }

        // Liên kết với diễn viên
        if ($request->has('actor_ids')) {
            $movie->actors()->sync($request->input('actor_ids'));
        }

        return redirect()->route('admin.movies.index')->with('success', 'Cập nhật phim thành công');
    }


    // Xóa phim
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        // Kiểm tra xem phim có thể xóa không
        if (!$movie->canBeEdited()) {
            $movieStatus = is_object($movie->status) ? $movie->status->value : $movie->status;
            $reason = '';

            if ($movieStatus === 'ended') {
                $reason = 'Không thể xóa phim đã kết thúc chiếu!';
            } elseif ($movieStatus === 'showing') {
                $reason = 'Không thể xóa phim đang chiếu!';
            } elseif ($movie->hasBookedTickets()) {
                $reason = 'Không thể xóa phim đã có vé được đặt!';
            }

            return redirect()->route('admin.movies.index')->with('error', $reason);
        }

        // Xóa vé liên quan đến các showtimes của phim
        foreach ($movie->showtimes as $showtime) {
            $showtime->tickets()->delete();
        }

        // Xóa các showtimes liên quan
        $movie->showtimes()->delete();

        // Xóa poster nếu có
        if ($movie->poster_url) {
            $oldPath = str_replace('/storage/', '', $movie->poster_url);
            Storage::disk('public')->delete($oldPath);
        }

        $movie->delete();

        return redirect()->route('admin.movies.index')->with('success', 'Xóa phim thành công!');
    }
    public function show(Request $request, $id)
    {
        $movie = Movie::with(['genres', 'ageLimit', 'country', 'director', 'actors'])->findOrFail($id);

        // Lấy thông tin tìm kiếm/lọc suất chiếu
        $showtimeQuery = $request->input('showtime_query');
        $showtimeStatus = $request->input('showtime_status', 'all');
        $roomId = $request->input('room_id');
        $startDate = $request->input('start_date');

        // Tải danh sách suất chiếu với tìm kiếm/lọc
        $showtimes = $movie->showtimes()
            ->with(['room'])
            ->when($showtimeQuery, function ($queryBuilder, $showtimeQuery) {
                return $queryBuilder->whereHas('room', function ($q) use ($showtimeQuery) {
                    $q->where('name', 'like', "%{$showtimeQuery}%");
                })->orWhere('start_time', 'like', "%{$showtimeQuery}%")
                    ->orWhere('end_time', 'like', "%{$showtimeQuery}%");
            })
            ->when($showtimeStatus !== 'all', function ($queryBuilder, $showtimeStatus) {
                return $queryBuilder->where('status', $showtimeStatus);
            })
            ->when($roomId, function ($queryBuilder, $roomId) {
                return $queryBuilder->where('room_id', $roomId);
            })
            ->when($startDate, function ($queryBuilder, $startDate) {
                return $queryBuilder->whereDate('start_time', '>=', $startDate);
            })
            ->orderBy('start_time', 'desc')
            ->paginate(5, ['*'], 'showtime_page');

        $rooms = Room::all();

        return view('admin.movies.show', compact('movie', 'showtimes', 'rooms'));
    }

    /**
     * Đếm số lượng phim trùng tên
     */
    public function countDuplicateName(Request $request)
    {
        $name = $request->input('name');
        $excludeId = $request->input('exclude_id');
        $count = Movie::where('name', $name)
            ->when($excludeId, function ($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            })
            ->count();
        return response()->json(['count' => $count]);
    }
}