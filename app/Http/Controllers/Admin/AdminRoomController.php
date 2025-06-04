<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Cinema;
use App\Models\RoomType;

class AdminRoomController extends Controller
{
    // Danh sách phòng chiếu
    public function index(Request $request)
    {
        $query = Room::with(['cinema', 'roomType']);

        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $rooms = $query->paginate(10)->withQueryString(); // giữ tham số lọc khi chuyển trang
        $roomTypes = RoomType::all();

        return view('admin.rooms.index', compact('rooms', 'roomTypes'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        $cinemas = Cinema::all();
        $roomTypes = RoomType::all();
        return view('admin.rooms.create', compact('cinemas', 'roomTypes'));
    }

    // Lưu phòng chiếu mới
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required|integer',
            'room_type_id' => 'nullable|integer',
            'name' => 'required|string|max:100',
            'capacity' => 'required|integer',
            'status' => 'nullable|string|max:20',
        ]);

        Room::create($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Tạo phòng chiếu thành công');
    }

    // Xem chi tiết phòng chiếu
    public function show($id)
    {
        $room = Room::with(['cinema', 'roomType', 'seats'])->findOrFail($id);

        return view('admin.rooms.show', compact('room'));
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $room = Room::with(['cinema', 'roomType'])->findOrFail($id);
        $cinemas = Cinema::all();
        $roomTypes = RoomType::all();
        return view('admin.rooms.edit', compact('room', 'cinemas', 'roomTypes'));
    }

    // Cập nhật phòng chiếu
    public function update(Request $request, $id)
    {
        $request->validate([
            'cinema_id' => 'required|integer',
            'room_type_id' => 'nullable|integer',
            'name' => 'required|string|max:100',
            'capacity' => 'required|integer',
            'status' => 'nullable|string|max:20',
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Cập nhật phòng chiếu thành công');
    }
}
