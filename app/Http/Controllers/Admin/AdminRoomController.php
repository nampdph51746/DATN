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
    public function show(Request $request, $id)
    {
        // Load room với relationships
        $room = Room::with(['cinema', 'roomType'])->findOrFail($id);
        \Log::info('Room data:', [
            'room_id' => $room->id,
            'cinema_id' => $room->cinema_id,
            'room_type_id' => $room->room_type_id,
            'cinema' => $room->cinema ? $room->cinema->toArray() : null,
            'roomType' => $room->roomType ? $room->roomType->toArray() : null
        ]);

        // Lấy showtime_id từ request (nếu có) hoặc lấy suất chiếu gần nhất
        $showtime_id = $request->input('showtime_id');
        if (!$showtime_id) {
            $showtime = \App\Models\Showtime::where('room_id', $room->id)
                ->where('start_time', '>=', now())
                ->orderBy('start_time', 'asc')
                ->first();
            $showtime_id = $showtime ? $showtime->id : null;
        }
        \Log::info('Selected showtime_id:', ['showtime_id' => $showtime_id]);

        // Lấy danh sách ghế với trạng thái từ showtime_seat_states
        $seats = Seat::with('seatType')
            ->where('room_id', $room->id)
            ->orderBy('row_char')
            ->orderBy('seat_number')
            ->get()
            ->map(function ($seat) use ($showtime_id) {
                if ($showtime_id) {
                    $seatState = $seat->showtimeSeatStates()->where('showtime_id', $showtime_id)->first();
                    $seat->display_status = $seatState ? $seatState->status->value : $seat->status->value;
                } else {
                    $seat->display_status = $seat->status->value;
                }
                if ($seat->seatType) {
                    $seat->seatType->name = htmlspecialchars($seat->seatType->name, ENT_QUOTES, 'UTF-8');
                }
                return $seat;
            });
        \Log::info('Seats:', $seats->map(function ($seat) {
            return [
                'id' => $seat->id,
                'room_id' => $seat->room_id,
                'seat_type_id' => $seat->seat_type_id,
                'row_char' => $seat->row_char,
                'seat_number' => $seat->seat_number,
                'display_status' => $seat->display_status,
                'seat_type_name' => $seat->seatType ? $seat->seatType->name : null
            ];
        })->toArray());

        // Lấy danh sách suất chiếu và escape movie name
        $showtimes = \App\Models\Showtime::where('room_id', $room->id)
            ->with('movie')
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($showtime) {
                if ($showtime->movie) {
                    $showtime->movie->name = htmlspecialchars($showtime->movie->name, ENT_QUOTES, 'UTF-8');
                }
                return $showtime;
            });
        \Log::info('Showtimes:', $showtimes->map(function ($showtime) {
            return [
                'id' => $showtime->id,
                'movie_id' => $showtime->movie_id,
                'movie_name' => $showtime->movie ? $showtime->movie->name : null,
                'start_time' => $showtime->start_time->toDateTimeString()
            ];
        })->toArray());

        // Lấy danh sách loại ghế được phép
        $allowedSeatTypes = $room->allowedSeatTypes()->map(function ($seatType) {
            if ($seatType) {
                $seatType->name = htmlspecialchars($seatType->name, ENT_QUOTES, 'UTF-8');
            }
            return $seatType;
        });
        \Log::info('Allowed Seat Types:', $allowedSeatTypes->map(function ($seatType) {
            return [
                'id' => $seatType->id,
                'name' => $seatType->name
            ];
        })->toArray());

        $rows = $seats->pluck('row_char')->unique()->sort()->values();
        $maxSeatsPerRow = $seats->isEmpty() ? 50 : $seats->groupBy('row_char')->map(function($group) { return $group->count(); })->max();
        $maxRows = 26;
        \Log::info('Rows and Layout:', [
            'rows' => $rows->toArray(),
            'maxSeatsPerRow' => $maxSeatsPerRow,
            'maxRows' => $maxRows
        ]);

        // Lấy tỷ lệ tùy chỉnh từ room_seat_configurations
        $seatPercentages = RoomSeatConfiguration::where('room_id', $room->id)
            ->with('seatType')
            ->get()
            ->map(function ($config) {
                if ($config->seatType) {
                    $config->seatType->name = htmlspecialchars($config->seatType->name, ENT_QUOTES, 'UTF-8');
                }
                return $config;
            })
            ->pluck('percentage', 'seat_type_id')
            ->toArray();
        if (empty($seatPercentages)) {
            $seatPercentages = [];
            foreach ($allowedSeatTypes as $seatType) {
                if (isset($seatType->id) && isset($seatType->name)) {
                    $seatPercentages[$seatType->id] = config('seat_types.percentages')[$seatType->name] ?? 0;
                }
            }
        }

        // Tính số ghế yêu cầu và ghế hiện có cho mỗi loại
        $requiredSeats = [];
        foreach ($seatPercentages as $seatTypeId => $percentage) {
            $requiredSeats[$seatTypeId] = (int) round(($percentage / 100) * $room->capacity);
        }
        $existingSeatsByType = $seats->groupBy('seat_type_id')->map(function($group) { return $group->count(); })->toArray();

        // Tính thứ tự loại ghế và loại ghế tiếp theo cần thêm
        $seatTypesOrder = array_keys($seatPercentages);
        $currentTypeIndex = 0;
        $next_seat_type_id = null;
        if (session()->has('next_seat_type_id')) {
            $currentTypeIndex = array_search(session('next_seat_type_id'), $seatTypesOrder);
            if ($currentTypeIndex === false) {
                $currentTypeIndex = 0;
            }
        }
        for ($i = $currentTypeIndex; $i < count($seatTypesOrder); $i++) {
            $typeId = $seatTypesOrder[$i];
            $remaining = ($requiredSeats[$typeId] ?? 0) - ($existingSeatsByType[$typeId] ?? 0);
            if ($remaining > 0) {
                $next_seat_type_id = $typeId;
                break;
            }
        }

        \Log::info('Seat Percentages:', $seatPercentages);
        \Log::info('Existing Seats by Type:', $existingSeatsByType);
        \Log::info('Required Seats:', $requiredSeats);
        \Log::info('Next seat type id:', [$next_seat_type_id]);

        return view('admin.rooms.show', compact(
            'room', 'allowedSeatTypes', 'seats', 'rows', 'maxSeatsPerRow', 'maxRows',
            'seatPercentages', 'existingSeatsByType', 'requiredSeats', 'next_seat_type_id',
            'seatTypesOrder', 'showtimes', 'showtime_id'
        ));
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