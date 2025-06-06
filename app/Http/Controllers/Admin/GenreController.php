<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::paginate(10);
        return view('admin.movieGenres.index', compact('genres'));
    }

    public function create()
    {
        return view('admin.movieGenres.create');

    }

    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:100|unique:genres,name',
        'description' => 'nullable|string',
        ]);

        Genre::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Thêm thể loại thành công!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);

        // Xóa các bản ghi liên kết phim-thể loại trước
        DB::table('movie_genres')->whereIn('genre_id', $ids)->delete();

        // Sau đó xóa thể loại
        Genre::whereIn('id', $ids)->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Đã xóa các thể loại đã chọn!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $genre = Genre::findOrFail($id);
        return view('admin.movieGenres.edit', compact('genre'));

    }

    public function update(Request $request, string $id)
    {
        $request->validate([
        'name' => 'required|string|max:100|unique:genres,name,' . $id,
        'description' => 'nullable|string',
    ]);

        $genre = Genre::findOrFail($id);
        $genre->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Cập nhật thể loại thành công!');
    }

    public function destroy(string $id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Xóa thể loại thành công!');
    }
}
