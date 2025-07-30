<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GenreController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view genre')->only('index');
        $this->middleware('can:create genre')->only(['create', 'store']);
        $this->middleware('can:edit genre')->only(['edit', 'update']);
        $this->middleware('can:delete genre')->only('destroy');
    }
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

        $genre = Genre::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        // Notification for create
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'genre',
            'entity_id' => $genre->id,
            'title' => 'Tạo thể loại mới',
            'message' => 'Thể loại "' . $genre->name . '" đã được tạo.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'create', 'data' => $genre->toArray()]),
        ]);
        return redirect()->route('admin.genres.index')->with('success', 'Thêm thể loại thành công!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);

        // Xóa các bản ghi liên kết phim-thể loại trước
        DB::table('movie_genres')->whereIn('genre_id', $ids)->delete();

        // Lấy dữ liệu cũ để thông báo
        $oldGenres = Genre::whereIn('id', $ids)->get()->toArray();

        // Sau đó xóa thể loại
        Genre::whereIn('id', $ids)->delete();

        // Notification for bulk delete
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'genre',
            'entity_id' => null,
            'title' => 'Xóa nhiều thể loại',
            'message' => 'Đã xóa các thể loại: ' . implode(', ', array_column($oldGenres, 'name')),
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'bulkDelete', 'old' => $oldGenres]),
        ]);
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
        $oldData = $genre->toArray();
        $genre->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        // Notification for update
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'genre',
            'entity_id' => $genre->id,
            'title' => 'Cập nhật thể loại',
            'message' => 'Thể loại "' . $genre->name . '" đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'update', 'old' => $oldData, 'new' => $genre->toArray()]),
        ]);
        return redirect()->route('admin.genres.index')->with('success', 'Cập nhật thể loại thành công!');
    }

    public function destroy(string $id)
    {
        $genre = Genre::findOrFail($id);
        $oldData = $genre->toArray();
        $genre->delete();
        // Notification for delete
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'genre',
            'entity_id' => $genre->id,
            'title' => 'Xóa thể loại',
            'message' => 'Thể loại "' . $oldData['name'] . '" đã bị xóa.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'delete', 'old' => $oldData]),
        ]);
        return redirect()->route('admin.genres.index')->with('success', 'Xóa thể loại thành công!');
    }
}