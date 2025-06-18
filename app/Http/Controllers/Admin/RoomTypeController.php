<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $roomTypes = RoomType::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:room_types,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên loại phòng là bắt buộc.',
            'name.unique' => 'Tên loại phòng đã tồn tại.',
            'name.max' => 'Tên loại phòng không được vượt quá 100 ký tự.',
        ]);

        try {
            RoomType::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.room-types.index')->with('success', 'Thêm loại phòng thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm loại phòng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm loại phòng. Vui lòng thử lại.');
        }
    }

    public function show($id)
    {
        $roomType = RoomType::findOrFail($id);
        $roomCount = $roomType->rooms()->count(); 
        return view('admin.room-types.show', compact('roomType', 'roomCount'));
    }

    public function edit($id)
    {
        $roomType = RoomType::findOrFail($id);
        return view('admin.room-types.edit', compact('roomType'));
    }

    public function update(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:room_types,name,' . $roomType->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Tên loại phòng là bắt buộc.',
            'name.unique' => 'Tên loại phòng đã tồn tại.',
            'name.max' => 'Tên loại phòng không được vượt quá 100 ký tự.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        try {
            if ($roomType->rooms()->exists()) {
                if ($request->name !== $roomType->name) {
                    return redirect()->back()->with('error', 'Không thể thay đổi tên loại phòng vì đang được sử dụng trong các phòng hiện tại.');
                }
                if ($request->status !== $roomType->status) {
                    return redirect()->back()->with('error', 'Không thể thay đổi trạng thái loại phòng vì đang được sử dụng trong các phòng hiện tại.');
                }
            }

            $roomType->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.room-types.index')->with('success', 'Cập nhật loại phòng thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật loại phòng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật loại phòng. Vui lòng thử lại.');
        }
    }

    // public function deactivate($id)
    // {
    //     $roomType = RoomType::findOrFail($id);

    //     try {
    //         if ($roomType->rooms()->exists()) {
    //             return redirect()->back()->with('error', 'Không thể vô hiệu hóa loại phòng vì đang được sử dụng trong các phòng hiện tại.');
    //         }

    //         $roomType->update(['status' => 'inactive']);

    //         return redirect()->route('admin.room-types.index')->with('success', 'Loại phòng đã được vô hiệu hóa thành công!');
    //     } catch (\Exception $e) {
    //         Log::error('Lỗi khi vô hiệu hóa loại phòng: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Có lỗi xảy ra khi vô hiệu hóa loại phòng. Vui lòng thử lại.');
    //     }
    // }
}