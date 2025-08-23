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
    $data = $request->all();  // Không lấy capacity
    if (!isset($data['capacity'])) {
    $data['capacity'] = 0;
}
    Room::create($data);

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

        // Get layout suggestions
        $layoutOptimizer = new \App\Services\SeatLayoutOptimizer();
        $layoutSuggestions = $layoutOptimizer->getLayoutSuggestions($room);
        
        // Debug constraints
        \Log::info('DEBUG CONSTRAINTS:', [
            'room_id' => $room->id,
            'room_type_id' => $room->room_type_id,
            'room_type_name' => $room->roomType->name ?? 'Unknown',
            'constraints_count' => \App\Models\RoomSeatTypeConstraint::where('room_type_id', $room->room_type_id)->count(),
            'allowed_seat_types_count' => $allowedSeatTypes->count(),
            'allowed_seat_types_names' => $allowedSeatTypes->pluck('name')->toArray()
        ]);

        // Kiểm tra xem phòng có suất chiếu đang hoạt động không
        $hasActiveShowtimes = \App\Models\Showtime::where('room_id', $room->id)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->exists();

        return view('admin.rooms.show', compact(
            'room', 'allowedSeatTypes', 'seats', 'rows', 'maxSeatsPerRow', 'maxRows',
            'seatPercentages', 'existingSeatsByType', 'requiredSeats', 'next_seat_type_id',
            'seatTypesOrder', 'showtimes', 'showtime_id', 'layoutSuggestions', 'hasActiveShowtimes'
        ));
    }

    // Hiển thị form chỉnh sửa

    public function editStatus($id)
{
    $room = Room::findOrFail($id);
return view('admin.rooms.edit_status', compact('room'));
}

public function updateStatus(Request $request, $id)
{
    $room = Room::findOrFail($id);
    $request->validate([
        'status' => 'required|string|max:20',
    ]);
    $room->status = $request->status;
    $room->save();

    return redirect()->route('admin.rooms.index')->with('success', 'Cập nhật trạng thái phòng thành công');
}
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

    // Cập nhật tỷ lệ loại ghế cho phòng
    public function updateSeatPercentages(Request $request, $id)
    {
        \Log::info('Update seat percentages called', [
            'room_id' => $id,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'percentages' => 'required|array',
            'percentages.*' => 'required|numeric|min:0|max:100',
            'ignore_constraints' => 'boolean'
        ]);

        $room = Room::findOrFail($id);
        $percentages = $request->input('percentages', []);
        $ignoreConstraints = $request->boolean('ignore_constraints', false);
        
        // Kiểm tra ghế đôi có bị lẻ không
        $coupleSeatsValidation = $this->validateCoupleSeatsPercentages($room, $percentages);
        if (!$coupleSeatsValidation['valid']) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $coupleSeatsValidation['message']
                ], 422);
            }
            return redirect()->back()->with('error', $coupleSeatsValidation['message']);
        }
        
        \Log::info('Processing seat percentages', [
            'room' => $room->toArray(),
            'percentages' => $percentages,
            'ignore_constraints' => $ignoreConstraints
        ]);
        
        // Kiểm tra tổng tỷ lệ
        $totalPercentage = array_sum($percentages);
        if (!$ignoreConstraints && abs($totalPercentage - 100) > 0.01) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tổng tỷ lệ phải bằng 100%. Hiện tại: ' . number_format($totalPercentage, 2) . '%'
                ], 422);
            }
            return redirect()->back()->with('error', 'Tổng tỷ lệ phải bằng 100%. Hiện tại: ' . number_format($totalPercentage, 2) . '%');
        }

        // Nếu không bỏ qua constraints, kiểm tra validation
        if (!$ignoreConstraints) {
            try {
                $validationService = app(\App\Services\RoomSeatValidationService::class);
                $errors = $validationService->validateSeatPercentages($room, $percentages);
                
                if (!empty($errors)) {
                    \Log::warning('Seat percentage validation errors:', $errors);
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tỷ lệ không hợp lệ: ' . implode(' ', $errors)
                        ], 422);
                    }
                    return redirect()->back()->with('error', 'Tỷ lệ không hợp lệ: ' . implode(' ', $errors));
                }
            } catch (\Exception $e) {
                \Log::error('Validation service error: ' . $e->getMessage());
                // Nếu validation service có lỗi, vẫn cho phép cập nhật
            }
        }

        try {
            // Cập nhật hoặc tạo mới cấu hình cho từng loại ghế
            foreach ($percentages as $seatTypeId => $percentage) {
                \App\Models\RoomSeatConfiguration::updateOrCreate(
                    [
                        'room_id' => $room->id,
                        'seat_type_id' => $seatTypeId
                    ],
                    [
                        'percentage' => $percentage
                    ]
                );
            }

            $message = $ignoreConstraints 
                ? 'Cập nhật tỷ lệ loại ghế thành công (sử dụng tỷ lệ tùy chỉnh)!'
                : 'Cập nhật tỷ lệ loại ghế thành công!';

            \Log::info('Seat percentages updated successfully', [
                'room_id' => $room->id,
                'message' => $message
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Error updating seat percentages: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật tỷ lệ ghế: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật tỷ lệ ghế: ' . $e->getMessage());
        }
    }

    /**
     * Điều chỉnh tỷ lệ cho ghế đôi để đảm bảo số ghế chẵn
     */
    private function adjustPercentagesForCoupleSeats(Room $room, array $percentages, bool $ignoreConstraints): array
    {
        if (!$ignoreConstraints) {
            return $percentages; // Chỉ điều chỉnh khi bỏ qua ràng buộc
        }

        $capacity = $room->capacity;
        $allowedSeatTypes = $room->allowedSeatTypes();
        $adjustedPercentages = $percentages;
        
        foreach ($allowedSeatTypes as $seatType) {
            if (!isset($percentages[$seatType->id])) continue;
            
            $percentage = $percentages[$seatType->id];
            $seatCount = round(($percentage / 100) * $capacity);
            
            // Kiểm tra nếu là ghế đôi
            if ($this->isCoupleSeaType($seatType->name)) {
                if ($seatCount % 2 !== 0) {
                    // Điều chỉnh số ghế thành chẵn
                    $adjustedSeatCount = $seatCount - 1;
                    $adjustedPercentage = ($adjustedSeatCount / $capacity) * 100;
                    $adjustedPercentages[$seatType->id] = round($adjustedPercentage, 2);
                    
                    \Log::info("Adjusted couple seat percentage", [
                        'seat_type' => $seatType->name,
                        'original_count' => $seatCount,
                        'adjusted_count' => $adjustedSeatCount,
                        'original_percentage' => $percentage,
                        'adjusted_percentage' => $adjustedPercentages[$seatType->id]
                    ]);
                }
            }
        }
        
        return $adjustedPercentages;
    }

    /**
     * Validate couple seats percentages to ensure even numbers
     */
    private function validateCoupleSeatsPercentages(Room $room, array $percentages): array
    {
        $allowedSeatTypes = $room->allowedSeatTypes();
        
        foreach ($allowedSeatTypes as $seatType) {
            if (!isset($percentages[$seatType->id])) continue;
            
            $percentage = $percentages[$seatType->id];
            $seatCount = round(($percentage / 100) * $room->capacity);
            
            // Kiểm tra nếu là ghế đôi
            if ($this->isCoupleSeaType($seatType->name)) {
                if ($seatCount % 2 !== 0) {
                    return [
                        'valid' => false,
                        'message' => "Loại ghế đôi '{$seatType->name}' có {$seatCount} ghế (số lẻ). Ghế đôi phải có số lượng chẵn. Vui lòng điều chỉnh tỷ lệ."
                    ];
                }
            }
        }
        
        return ['valid' => true, 'message' => ''];
    }

    /**
     * Kiểm tra xem loại ghế có phải là ghế đôi không
     */
    private function isCoupleSeaType(string $seatTypeName): bool
    {
        $typeName = strtolower($seatTypeName);
        return str_contains($typeName, 'couple') || 
               str_contains($typeName, 'sweetbox') || 
               str_contains($typeName, 'bed') ||
               str_contains($typeName, 'sofa');
    }

    /**
     * Cập nhật sức chứa phòng
     */
    public function updateCapacity(Request $request, $room)
    {
        $room = Room::findOrFail($room);
        
        \Log::info('updateCapacity called', [
            'room_id' => $room->id,
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);

        $request->validate([
            'capacity' => 'required|integer|min:1|max:1000'
        ]);

        $newCapacity = $request->capacity;
        $currentSeats = $room->seats()->count();

        \Log::info('Capacity update details', [
            'room_id' => $room->id,
            'current_capacity' => $room->capacity,
            'new_capacity' => $newCapacity,
            'current_seats' => $currentSeats
        ]);

        // Kiểm tra nếu sức chứa mới nhỏ hơn số ghế hiện tại
        if ($newCapacity < $currentSeats) {
            \Log::warning('Capacity update failed - too many seats', [
                'new_capacity' => $newCapacity,
                'current_seats' => $currentSeats
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "Không thể giảm sức chứa xuống {$newCapacity} vì phòng hiện có {$currentSeats} ghế. Vui lòng xóa ghế trước."
            ], 400);
        }

        $room->update(['capacity' => $newCapacity]);

        \Log::info('Capacity updated successfully', [
            'room_id' => $room->id,
            'old_capacity' => $room->getOriginal('capacity'),
            'new_capacity' => $room->capacity,
            'room_fresh' => $room->fresh()->capacity
        ]);

        return response()->json([
            'success' => true,
            'message' => "Đã cập nhật sức chứa phòng thành {$newCapacity}",
            'new_capacity' => $newCapacity,
            'available_seats' => $newCapacity - $currentSeats
        ]);
    }
}