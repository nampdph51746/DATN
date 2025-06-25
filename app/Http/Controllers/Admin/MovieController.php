<?php

namespace App\Http\Controllers\Admin;

use App\Models\Movie;
use App\Models\Country;
use App\Models\AgeLimit;
use App\Models\Genre;
use App\Models\Room;
use App\Enums\MovieStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MovieController extends Controller
{
    // Danh sách phim
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');
        $countryId = $request->input('country_id');
        $ageLimitId = $request->input('age_limit_id');
        $releaseDate = $request->input('release_date');
        $endDate = $request->input('end_date');

        $movies = Movie::query()
            ->with(['country', 'ageLimit'])
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('director', 'like', "%{$query}%")
                  ->orWhere('actors', 'like', "%{$query}%")
                  ->orWhere('language', 'like', "%{$query}%");
            })
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
            ->when($countryId, fn($q) => $q->where('country_id', $countryId))
            ->when($ageLimitId, fn($q) => $q->where('age_limit_id', $ageLimitId))
            ->when($releaseDate, fn($q) => $q->where('release_date', '>=', $releaseDate))
            ->when($endDate, fn($q) => $q->where('end_date', '<=', $endDate))
            ->orderBy('release_date', 'desc')
            ->paginate(10);

        $countries = Country::all();
        $ageLimits = AgeLimit::all();
        $genres = Genre::all();

        return view('admin.movies.index', compact('movies', 'countries', 'ageLimits', 'genres'));
    }

    // Form thêm phim
    public function create()
    {
        $countries = Country::all();
        $ageLimits = AgeLimit::all();
        $genres = Genre::all();
        return view('admin.movies.create', compact('countries', 'ageLimits', 'genres'));
    }

    // Lưu phim mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'actors' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'description' => 'nullable|string',
            'poster' => 'nullable|image|max:2048',
            'trailer_url' => 'nullable|url|max:255',
            'language' => 'nullable|string|max:50',
            'country_id' => 'nullable|exists:countries,id',
            'age_limit_id' => 'nullable|exists:age_limits,id',
            'status' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\MovieStatus::class)],
            'average_rating' => 'nullable|numeric|min:0|max:10',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $posterPath = null;
                if ($request->hasFile('poster')) {
                    $posterPath = $request->file('poster')->store('posters', 'public');
                }
                $movie = Movie::create([
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
                    'status' => \App\Enums\MovieStatus::from($request->status),
                    'average_rating' => $request->average_rating ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $movie->genres()->sync($request->genre_ids);
            });

            // Đảm bảo redirect về index
            return redirect()->route('admin.movies.index')->with('success', 'Thêm phim thành công!');
        } catch (\Exception $e) {
            \Log::error('Lỗi khi thêm phim: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm phim: ' . $e->getMessage())->withInput();
        }
    }

    // Xem chi tiết phim
    public function show(Request $request, $id)
    {
        $movie = Movie::with(['country', 'ageLimit', 'genres'])->findOrFail($id);
        $rooms = Room::all();

        // Lọc suất chiếu (nếu có)
        $showtimeQuery = $request->input('showtime_query');
        $showtimeStatus = $request->input('showtime_status', 'all');
        $roomId = $request->input('room_id');
        $startDate = $request->input('start_date');

        $showtimes = $movie->showtimes()
            ->with(['room'])
            ->when($showtimeQuery, function ($q) use ($showtimeQuery) {
                $q->whereHas('room', fn($qr) => $qr->where('name', 'like', "%{$showtimeQuery}%"))
                  ->orWhere('start_time', 'like', "%{$showtimeQuery}%")
                  ->orWhere('end_time', 'like', "%{$showtimeQuery}%");
            })
            ->when($showtimeStatus !== 'all', fn($q) => $q->where('status', $showtimeStatus))
            ->when($roomId, fn($q) => $q->where('room_id', $roomId))
            ->when($startDate, fn($q) => $q->whereDate('start_time', '>=', $startDate))
            ->orderBy('start_time', 'desc')
            ->paginate(5, ['*'], 'showtime_page');

        return view('admin.movies.show', compact('movie', 'showtimes', 'rooms'));
    }

    // Form sửa phim
    public function edit($id)
    {
        $movie = Movie::with('genres')->findOrFail($id);
        $countries = Country::all();
        $ageLimits = AgeLimit::all();
        $genres = Genre::all();
        return view('admin.movies.edit', compact('movie', 'countries', 'ageLimits', 'genres'));
    }

    // Cập nhật phim
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
            'poster' => 'nullable|image|max:2048',
            'trailer_url' => 'nullable|url|max:255',
            'language' => 'nullable|string|max:50',
            'country_id' => 'nullable|exists:countries,id',
            'age_limit_id' => 'nullable|exists:age_limits,id',
            'status' => ['required', \Illuminate\Validation\Rule::enum(MovieStatus::class)],
            'average_rating' => 'nullable|numeric|min:0|max:10',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
        ]);

        try {
            // Không cho phép đổi thời lượng nếu đã có suất chiếu
            if ($movie->showtimes()->exists() && $movie->duration_minutes != $request->duration_minutes) {
                return redirect()->back()->with('error', 'Không thể thay đổi thời lượng phim vì đã có suất chiếu.')->withInput();
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
                ];
                // Xử lý upload poster mới nếu có
                if ($request->hasFile('poster')) {
                    $data['poster_url'] = $request->file('poster')->store('posters', 'public');
                }
                $movie->update($data);
                if ($request->has('genre_ids')) {
                    $movie->genres()->sync($request->genre_ids);
                }
            });

            return redirect()->route('admin.movies.index')->with('success', 'Cập nhật phim thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật phim: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật phim: ' . $e->getMessage())->withInput();
        }
    }

    // Xóa phim
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        try {
            if ($movie->showtimes()->exists()) {
                return redirect()->back()->with('error', 'Không thể xóa phim vì đã có suất chiếu liên quan.');
            }
            DB::transaction(function () use ($movie) {
                $movie->genres()->detach();
                $movie->delete();
            });
            return redirect()->route('admin.movies.index')->with('success', 'Xóa phim thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa phim: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa phim: ' . $e->getMessage());
        }
    }

    // Xóa nhiều phim
    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        try {
            DB::transaction(function () use ($ids) {
                $movies = Movie::whereIn('id', $ids)->get();
                foreach ($movies as $movie) {
                    if ($movie->showtimes()->exists()) continue;
                    $movie->genres()->detach();
                    $movie->delete();
                }
            });
            return redirect()->route('admin.movies.index')->with('success', 'Đã xóa các phim đã chọn!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa phim: ' . $e->getMessage());
        }
    }
}