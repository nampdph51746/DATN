<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminActorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        
        $actors = Actor::withCount('movies')
            ->when($query, function ($queryBuilder, $query) {
                return $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('nationality', 'like', '%' . $query . '%')
                    ->orWhere('biography', 'like', '%' . $query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Append search parameter to pagination links
        $actors->appends($request->query());

        return view('admin.actors.index', compact('actors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.actors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('actors', 'public');
            $data['image_path'] = $path;
        }

        Actor::create($data);

        return redirect()->route('admin.actors.index')
            ->with('success', 'Thêm diễn viên thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $actor = Actor::with('movies')->findOrFail($id);
        return view('admin.actors.show', compact('actor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $actor = Actor::findOrFail($id);
        return view('admin.actors.edit', compact('actor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $actor = Actor::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($actor->image_path) {
                Storage::disk('public')->delete($actor->image_path);
            }
            
            $path = $request->file('image')->store('actors', 'public');
            $data['image_path'] = $path;
        }

        $actor->update($data);

        return redirect()->route('admin.actors.index')
            ->with('success', 'Cập nhật diễn viên thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $actor = Actor::findOrFail($id);
        
        // Kiểm tra nếu diễn viên có phim
        if ($actor->movies()->count() > 0) {
            return redirect()->route('admin.actors.index')
                ->with('error', 'Không thể xóa diễn viên vì đang có phim!');
        }

        // Xóa ảnh
        if ($actor->image_path) {
            Storage::disk('public')->delete($actor->image_path);
        }

        $actor->delete();

        return redirect()->route('admin.actors.index')
            ->with('success', 'Xóa diễn viên thành công!');
    }

    /**
     * Bulk delete actors.
     */
    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        $actors = Actor::whereIn('id', $ids)->get();
        
        // Kiểm tra xem có diễn viên nào đang có phim không
        $actorsWithMovies = $actors->filter(function ($actor) {
            return $actor->movies()->count() > 0;
        });

        if ($actorsWithMovies->count() > 0) {
            return redirect()->route('admin.actors.index')
                ->with('error', 'Không thể xóa một số diễn viên vì đang có phim!');
        }

        // Xóa ảnh của các diễn viên
        foreach ($actors as $actor) {
            if ($actor->image_path) {
                Storage::disk('public')->delete($actor->image_path);
            }
        }

        Actor::whereIn('id', $ids)->delete();
        
        return redirect()->route('admin.actors.index')
            ->with('success', 'Đã xóa các diễn viên đã chọn!');
    }
}