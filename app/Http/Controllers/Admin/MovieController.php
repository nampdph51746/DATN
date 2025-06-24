<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Country;
use App\Models\AgeLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MovieController extends Controller
{
    /**
     * Hiển thị danh sách phim với tìm kiếm và lọc
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');
        $countryId = $request->input('country_id');
        $ageLimitId = $request->input('age_limit_id');
        $genreId = $request->input('genre_id');
        $releaseDate = $request->input('release_date');
        $endDate = $request->input('end_date');

        $movies = Movie::query()
            ->with(['country', 'ageLimit'])
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('name', 'like', "%{$query}%")
                    ->orWhere('director', 'like', "%{$query}%")
                    ->orWhere('actors', 'like', "%{$query}%")
                    ->orWhere('language', 'like', "%{$query}%");
            })
            ->when($status !== 'all', function ($queryBuilder) use ($status) {
                return $queryBuilder->where('status', $status);
            })
            ->when($countryId, function ($queryBuilder) use ($countryId) {
                return $queryBuilder->where('country_id', $countryId);
            })
            ->when($ageLimitId, function ($queryBuilder) use ($ageLimitId) {
                return $queryBuilder->where('age_limit_id', $ageLimitId);
            })
            ->when($releaseDate, function ($queryBuilder) use ($releaseDate) {
                return $queryBuilder->where('release_date', '>=', $releaseDate);
            })
            ->when($endDate, function ($queryBuilder) use ($endDate) {
                return $queryBuilder->where('end_date', '<=', $endDate);
            })
            ->orderBy('release_date', 'desc')
            ->paginate(10);

        $countries = Country::all();
        $ageLimits = AgeLimit::all();
        $genres = Genre::all();

        return view('admin.movies.index', compact('movies', 'countries', 'ageLimits', 'genres'));
    }

    /**
     * Hiển thị form thêm phim mới
     */
    public function create()
    {
        $countries = Country::all();
        $ageLimits = AgeLimit::all();

        return view('admin.movies.create', compact('countries', 'ageLimits'));
    }

    /**
     * Xử lý thêm phim mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'actors' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'description' => 'nullable|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trailer_url' => 'nullable|url|max:255',
            'language' => 'nullable|string|max:50',
            'country_id' => 'nullable|exists:countries,id',
            'age_limit_id' => 'nullable|exists:age_limits,id',
            'status' => ['required', \Illuminate\Validation\Rule::enum(MovieStatus::class)],
            'average_rating' => 'nullable|numeric|min:0|max:10',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
        ], [
            'name.required' => 'Tên phim là bắt buộc.',
            'name.max' => 'Tên phim không được vượt quá 255 ký tự.',
            'director.max' => 'Tên đạo diễn không được vượt quá 255 ký tự.',
            'duration_minutes.required' => 'Thời lượng phim là bắt buộc.',
            'duration_minutes.integer' => 'Thời lượng phim phải là số nguyên.',
            'duration_minutes.min' => 'Thời lượng phim phải lớn hơn 0 phút.',
            'release_date.required' => 'Ngày phát hành là bắt buộc.',
            'release_date.date' => 'Ngày phát hành không hợp lệ.',
            'release_date.after_or_equal' => 'Ngày phát hành phải từ hôm nay trở đi.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày phát hành.',
            'poster.url' => 'URL poster không hợp lệ.',
            'poster.max' => 'URL poster không được vượt quá 255 ký tự.',
            'trailer_url.url' => 'URL trailer không hợp lệ.',
            'trailer_url.max' => 'URL trailer không được vượt quá 255 ký tự.',
            'language.max' => 'Ngôn ngữ không được vượt quá 50 ký tự.',
            'country_id.exists' => 'Quốc gia không tồn tại.',
            'age_limit_id.exists' => 'Giới hạn độ tuổi không tồn tại.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status' => 'Trạng thái không hợp lệ.',
            'average_rating.numeric' => 'Điểm đánh giá phải là số.',
            'average_rating.min' => 'Điểm đánh giá không được nhỏ hơn 0.',
            'average_rating.max' => 'Điểm đánh giá không được vượt quá 10.',
            'genre_ids.required' => 'Vui lòng chọn ít nhất một thể loại.',
            'genre_ids.array' => 'Thể loại không hợp lệ.',
            'genre_ids.min' => 'Vui lòng chọn ít nhất một thể loại.',
            'genre_ids.*.exists' => 'Thể loại được chọn không tồn tại.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Movie::create([
                    'name' => $request->name,
                    'director' => $request->director,
                    'actors' => $request->actors,
                    'duration_minutes' => $request->duration_minutes,
                    'release_date' => $request->release_date,
                    'end_date' => $request->end_date,
                    'description' => $request->description,
                    'poster_url' => $posterPath,
                    'trailer_url' => $request->trailer_url,
                    'language' => $request->language,
                    'country_id' => $request->country_id,
                    'age_limit_id' => $request->age_limit_id,
                    'status' => MovieStatus::from($request->status),
                    'average_rating' => $request->average_rating ?? 0,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            });

            return redirect()->route('admin.movies.index')
                ->with('success', 'Thêm phim thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm phim: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi thêm phim: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị chi tiết phim
     */
    public function show(Request $request, $id)
    {
        $movie = Movie::with(['country', 'ageLimit', 'genres'])->findOrFail($id);

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

        $rooms = Room::all(); // Thêm dòng này

        return view('admin.movies.show', compact('movie', 'showtimes', 'rooms'));
    }

    /**
     * Hiển thị form chỉnh sửa phim
     */
    public function edit($id)
    {
        $movie = Movie::with('genres')->findOrFail($id);
        $countries = Country::all();
        $ageLimits = AgeLimit::all();

        return view('admin.movies.edit', compact('movie', 'countries', 'ageLimits'));
    }

    /**
     * Xử lý cập nhật phim
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'actors' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'description' => 'nullable|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trailer_url' => 'nullable|url|max:255',
            'language' => 'nullable|string|max:50',
            'country_id' => 'nullable|exists:countries,id',
            'age_limit_id' => 'nullable|exists:age_limits,id',
            'status' => ['required', \Illuminate\Validation\Rule::enum(MovieStatus::class)],
            'average_rating' => 'nullable|numeric|min:0|max:10',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
        ], [
            'name.required' => 'Tên phim là bắt buộc.',
            'name.max' => 'Tên phim không được vượt quá 255 ký tự.',
            'director.max' => 'Tên đạo diễn không được vượt quá 255 ký tự.',
            'duration_minutes.required' => 'Thời lượng phim là bắt buộc.',
            'duration_minutes.integer' => 'Thời lượng phim phải là số nguyên.',
            'duration_minutes.min' => 'Thời lượng phim phải lớn hơn 0 phút.',
            'release_date.required' => 'Ngày phát hành là bắt buộc.',
            'release_date.date' => 'Ngày phát hành không hợp lệ.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày phát hành.',
            'poster.url' => 'URL poster không hợp lệ.',
            'poster.max' => 'URL poster không được vượt quá 255 ký tự.',
            'trailer_url.url' => 'URL trailer không hợp lệ.',
            'trailer_url.max' => 'URL trailer không được vượt quá 255 ký tự.',
            'language.max' => 'Ngôn ngữ không được vượt quá 50 ký tự.',
            'country_id.exists' => 'Quốc gia không tồn tại.',
            'age_limit_id.exists' => 'Giới hạn độ tuổi không tồn tại.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status' => 'Trạng thái không hợp lệ.',
            'average_rating.numeric' => 'Điểm đánh giá phải là số.',
            'average_rating.min' => 'Điểm đánh giá không được nhỏ hơn 0.',
            'average_rating.max' => 'Điểm đánh giá không được vượt quá 10.',
            'genre_ids.required' => 'Vui lòng chọn ít nhất một thể loại.',
            'genre_ids.array' => 'Thể loại không hợp lệ.',
            'genre_ids.min' => 'Vui lòng chọn ít nhất một thể loại.',
            'genre_ids.*.exists' => 'Thể loại được chọn không tồn tại.',
        ]);

        try {
            // Kiểm tra nếu phim có suất chiếu, không cho phép thay đổi duration_minutes
            if ($movie->showtimes()->exists() && $movie->duration_minutes != $request->duration_minutes) {
                return redirect()->back()
                    ->with('error', 'Không thể thay đổi thời lượng phim vì đã có suất chiếu.')
                    ->withInput();
            }

            DB::transaction(function () use ($movie, $request) {
                $data = [
                    'name' => $request->name,
                    'director' => $request->director,
                    'actors' => $request->actors,
                    'duration_minutes' => $request->duration_minutes,
                    'release_date' => $request->release_date,
                    'end_date' => $request->end_date,
                    'description' => $request->description,
                    'trailer_url' => $request->trailer_url,
                    'language' => $request->language,
                    'country_id' => $request->country_id,
                    'age_limit_id' => $request->age_limit_id,
                    'status' => MovieStatus::from($request->status),
                    'average_rating' => $request->average_rating ?? 0,
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            });

            return redirect()->route('admin.movies.index')
                ->with('success', 'Cập nhật phim thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật phim: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật phim: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xóa phim
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        try {
            // Kiểm tra nếu phim có suất chiếu
            if ($movie->showtimes()->exists()) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa phim vì đã có suất chiếu liên quan.');
            }

            DB::transaction(function () use ($movie) {
                // Xóa quan hệ thể loại
                $movie->genres()->detach();
                $movie->delete();
            });

            return redirect()->route('admin.movies.index')
                ->with('success', 'Xóa phim thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa phim: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa phim: ' . $e->getMessage());
        }
    }
}