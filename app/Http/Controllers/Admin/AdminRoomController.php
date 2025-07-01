<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Seat;
use App\Models\Cinema;
use App\Models\RoomType;
use App\Models\SeatType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoomSeatConfiguration;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;

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
    public function store(StoreRoomRequest $request)
    {
        Room::create($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Tạo phòng chiếu thành công');
    }

    // Xem chi tiết phòng chiếu
public function show(Room $room)
{
    $seatTypes = SeatType::all();
    $seats = Seat::with('seatType')
        ->where('room_id', $room->id)
        ->orderBy('row_char')
        ->orderBy('seat_number')
        ->get();

    // Lấy danh sách các hàng ghế (row_char) duy nhất
    $rows = $seats->pluck('row_char')->unique()->sort()->values();
    
    // Tính số ghế tối đa mỗi hàng
    $maxSeatsPerRow = $seats->isEmpty() ? 50 : $seats->groupBy('row_char')->map->count()->max();
    
    // Số hàng tối đa
    $maxRows = 26;

    // Lấy tỷ lệ tùy chỉnh từ room_seat_configurations
    $seatPercentages = RoomSeatConfiguration::where('room_id', $room->id)
        ->with('seatType')
        ->get()
        ->pluck('percentage', 'seat_type_id')
        ->toArray();
    
    // Nếu chưa có cấu hình tùy chỉnh, sử dụng tỷ lệ mặc định
    if (empty($seatPercentages)) {
        $seatPercentages = [];
        foreach ($seatTypes as $seatType) {
            $seatPercentages[$seatType->id] = config('seat_types.percentages')[$seatType->name] ?? 0;
        }
    }

    // Tính số ghế yêu cầu và ghế hiện có cho mỗi loại
    $requiredSeats = [];
    foreach ($seatPercentages as $seatTypeId => $percentage) {
        $requiredSeats[$seatTypeId] = (int) round(($percentage / 100) * $room->capacity);
    }
    $existingSeatsByType = $seats->groupBy('seat_type_id')->map->count()->toArray();

    // Ghi log dữ liệu
    \Log::info('Seat Percentages: ', $seatPercentages);
    \Log::info('Existing Seats by Type: ', $existingSeatsByType);
    \Log::info('Required Seats: ', $requiredSeats);

    return view('admin.rooms.show', compact('room', 'seatTypes', 'seats', 'rows', 'maxSeatsPerRow', 'maxRows', 'seatPercentages', 'existingSeatsByType', 'requiredSeats'));
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
    public function update(UpdateRoomRequest $request, $id)
    {
        $room = Room::findOrFail($id);
        $room->update($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Cập nhật phòng chiếu thành công');
    }
}