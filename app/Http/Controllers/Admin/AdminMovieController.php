<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Country;
use App\Models\AgeLimit;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Showtime;
use Illuminate\Support\Facades\DB;

class AdminMovieController extends Controller
{
    // Danh sách phim
    public function index(Request $request)
    {
        $movies = Movie::with(['genres', 'country', 'ageLimit'])->paginate(10);

        // Lấy danh sách quốc gia
        $countries = Country::all();

        // Lấy danh sách giới hạn độ tuổi (nếu có dùng)
        $ageLimits = AgeLimit::all();

        return view('admin.movies.index', compact('movies', 'countries', 'ageLimits'));
    }

    // Form tạo phim
    public function create()
    {
        $countries = Country::all();
        $ageLimits = AgeLimit::all();
        $genres = Genre::all();
        return view('admin.movies.create', compact('countries', 'ageLimits', 'genres'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);

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
            \App\Models\Showtime::whereIn('movie_id', $ids)->delete();

            // Xóa các reviews liên quan đến các phim này
            \App\Models\Review::whereIn('movie_id', $ids)->delete();

            // Xóa các bản ghi liên kết thể loại phim
            DB::table('movie_genres')->whereIn('movie_id', $ids)->delete();

            // Xóa các phim
            \App\Models\Movie::whereIn('id', $ids)->delete();

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
            'name' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'actors' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
            'release_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'description' => 'nullable|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trailer_url' => 'nullable|url',
            'language' => 'nullable|string|max:50',
            'country_id' => 'nullable|integer|exists:countries,id',
            'age_limit_id' => 'nullable|integer|exists:age_limits,id',
            'status' => 'required|in:showing,upcoming,ended',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'average_rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $data = $request->except('poster');

        // Đảm bảo thư mục tồn tại
        $posterDir = storage_path('app/public/posters');
        if (!File::exists($posterDir)) {
            File::makeDirectory($posterDir, 0755, true);
        }

        // Xử lý upload ảnh poster
        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $data['poster_url'] = '/storage/' . $path;
        }

        $movie = Movie::create($data);
        $movie->genres()->sync($request->input('genres', []));

        return redirect()->route('admin.movies.index')->with('success', 'Thêm phim thành công');
    }

    // Form sửa phim
    public function edit($id)
    {
            $movie = Movie::findOrFail($id);
            $countries = Country::all();
            $ageLimits = AgeLimit::all();
            $genres = Genre::all();
            return view('admin.movies.edit', compact('movie', 'countries', 'ageLimits', 'genres'));
    }

    // Cập nhật phim
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'actors' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
            'release_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'description' => 'nullable|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trailer_url' => 'nullable|url',
            'language' => 'nullable|string|max:50',
            'country_id' => 'nullable|integer|exists:countries,id',
            'age_limit_id' => 'nullable|integer|exists:age_limits,id',
            'status' => 'required|in:showing,upcoming,ended',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'average_rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $movie = Movie::findOrFail($id);
        $data = $request->except('poster');

        // Đảm bảo thư mục tồn tại
        $posterDir = storage_path('app/public/posters');
        if (!File::exists($posterDir)) {
            File::makeDirectory($posterDir, 0755, true);
        }

        // Xử lý upload ảnh poster mới
        if ($request->hasFile('poster')) {
            if ($movie->poster_url) {
                $oldPath = str_replace('/storage/', '', $movie->poster_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('poster')->store('posters', 'public');
            $data['poster_url'] = '/storage/' . $path;
        }

        $movie->update($data);
        $movie->genres()->sync($request->input('genres', []));

        return redirect()->route('admin.movies.index')->with('success', 'Cập nhật phim thành công');
    }

    // Xóa phim
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

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
    public function show($id)
    {
        $movie = Movie::with(['genres', 'ageLimit', 'country'])->findOrFail($id);
        return view('admin.movies.show', compact('movie'));
    }
}