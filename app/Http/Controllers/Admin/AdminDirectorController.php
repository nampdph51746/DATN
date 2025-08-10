<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Director;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        
        $directors = Director::query()
            ->when($query, function ($queryBuilder, $query) {
                return $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('nationality', 'like', '%' . $query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.directors.index', compact('directors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.directors.create');
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
            $path = $request->file('image')->store('directors', 'public');
            $data['image_path'] = $path;
        }

        Director::create($data);

        return redirect()->route('admin.directors.index')
            ->with('success', 'Thêm đạo diễn thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $director = Director::with('movies')->findOrFail($id);
        return view('admin.directors.show', compact('director'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $director = Director::findOrFail($id);
        return view('admin.directors.edit', compact('director'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $director = Director::findOrFail($id);
        
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
            if ($director->image_path) {
                Storage::disk('public')->delete($director->image_path);
            }
            
            $path = $request->file('image')->store('directors', 'public');
            $data['image_path'] = $path;
        }

        $director->update($data);

        return redirect()->route('admin.directors.index')
            ->with('success', 'Cập nhật đạo diễn thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $director = Director::findOrFail($id);
        
        // Kiểm tra nếu đạo diễn có phim
        if ($director->movies()->count() > 0) {
            return redirect()->route('admin.directors.index')
                ->with('error', 'Không thể xóa đạo diễn vì đang có phim!');
        }

        // Xóa ảnh
        if ($director->image_path) {
            Storage::disk('public')->delete($director->image_path);
        }

        $director->delete();

        return redirect()->route('admin.directors.index')
            ->with('success', 'Xóa đạo diễn thành công!');
    }
}