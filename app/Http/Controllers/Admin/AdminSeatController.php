<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;
use App\Enums\SeatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RoomSeatConfiguration;
use Illuminate\Support\Facades\Validator;

class AdminSeatController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $seatTypeId = $request->input('seat_type_id');

        $seatTypes = SeatType::all();

        $seats = Seat::with('room', 'seatType')
            ->when($query, function ($queryBuilder, $query) {
                return $queryBuilder->where('row_char', 'like', "%{$query}%")
                    ->orWhere('seat_number', 'like', "%{$query}%");
            })
            ->when($seatTypeId, function ($queryBuilder, $seatTypeId) {
                return $queryBuilder->where('seat_type_id', $seatTypeId);
            })
            ->paginate(10);

        return view('admin.seats.index', compact('seats', 'seatTypes'));
    }

    public function create(Room $room)
    {
        $seatTypes = SeatType::all();
        $seatPercentages = config('seat_types.percentages');
        
        // Lấy tỷ lệ tùy chỉnh từ room_seat_configurations
        $roomSeatConfigs = RoomSeatConfiguration::where('room_id', $room->id)
            ->with('seatType')
            ->get()
            ->pluck('percentage', 'seat_type_id')
            ->toArray();

        // Nếu chưa có cấu hình tùy chỉnh, sử dụng tỷ lệ mặc định
        if ($roomSeatConfigs) {
            $seatPercentages = $roomSeatConfigs;
        } else {
            $seatPercentages = [];
            foreach ($seatTypes as $seatType) {
                $seatPercentages[$seatType->id] = config('seat_types.percentages')[$seatType->name] ?? 0;
            }
        }

        $seats = Seat::where('room_id', $room->id)->with('seatType')->get();
        $maxRows = 26;
        $maxSeatsPerRow = 50;
        $rows = range('A', chr(64 + $maxRows));

        return view('admin.seats.create', compact('room', 'seatTypes', 'seatPercentages', 'seats', 'maxRows', 'maxSeatsPerRow', 'rows'));
    }

    public function store(Request $request)
    {
        \Log::info('Request data: ', $request->all());

        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'seat_type_id' => 'required|exists:seat_types,id',
            'min_seats_per_row' => 'required|integer|min:1|max:50',
            'seat_type_percentages' => 'required|array',
            'seat_type_percentages.*' => 'required|numeric|min:0|max:100',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'room_id.required' => 'Phòng chiếu là bắt buộc.',
            'room_id.exists' => 'Phòng chiếu không tồn tại.',
            'seat_type_id.required' => 'Loại ghế là bắt buộc.',
            'seat_type_id.exists' => 'Loại ghế không tồn tại.',
            'min_seats_per_row.required' => 'Số ghế tối thiểu mỗi hàng là bắt buộc.',
            'min_seats_per_row.integer' => 'Số ghế tối thiểu mỗi hàng phải là số nguyên.',
            'min_seats_per_row.min' => 'Số ghế tối thiểu mỗi hàng phải lớn hơn hoặc bằng 1.',
            'min_seats_per_row.max' => 'Số ghế tối thiểu mỗi hàng không được vượt quá 50.',
            'seat_type_percentages.required' => 'Tỷ lệ loại ghế là bắt buộc.',
            'seat_type_percentages.*.required' => 'Tỷ lệ mỗi loại ghế không được để trống.',
            'seat_type_percentages.*.numeric' => 'Tỷ lệ phải là số.',
            'seat_type_percentages.*.min' => 'Tỷ lệ phải lớn hơn hoặc bằng 0.',
            'seat_type_percentages.*.max' => 'Tỷ lệ phải nhỏ hơn hoặc bằng 100.',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed: ', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $room = Room::findOrFail($request->room_id);
        if ($room->status !== 'active') {
            $validator->errors()->add('room_id', 'Phòng chiếu không ở trạng thái hoạt động.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingSeatsCount = Seat::where('room_id', $request->room_id)->count();
        if ($existingSeatsCount >= $room->capacity) {
            $validator->errors()->add('room_id', 'Phòng đã đầy ghế.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra tổng tỷ lệ phần trăm
        $totalPercentage = array_sum($request->seat_type_percentages);
        if (abs($totalPercentage - 100) > 0.01) {
            $validator->errors()->add('seat_type_percentages', 'Tổng tỷ lệ loại ghế phải bằng 100%.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Lưu tỷ lệ tùy chỉnh
        DB::beginTransaction();
        try {
            RoomSeatConfiguration::where('room_id', $request->room_id)->delete();
            foreach ($request->seat_type_percentages as $seatTypeId => $percentage) {
                if ($percentage > 0) {
                    RoomSeatConfiguration::create([
                        'room_id' => $request->room_id,
                        'seat_type_id' => $seatTypeId,
                        'percentage' => $percentage,
                    ]);
                }
            }

            // Tính số ghế cần thêm cho mỗi loại
            $seatTypes = SeatType::all()->keyBy('id');
            $requiredSeats = [];
            $totalSeats = $room->capacity;
            foreach ($request->seat_type_percentages as $seatTypeId => $percentage) {
                $requiredSeats[$seatTypeId] = (int) round(($percentage / 100) * $totalSeats);
            }

            // Đếm số ghế hiện có của mỗi loại
            $existingSeatsByType = Seat::where('room_id', $request->room_id)
                ->select('seat_type_id', DB::raw('count(*) as count'))
                ->groupBy('seat_type_id')
                ->pluck('count', 'seat_type_id')
                ->toArray();

            // Tính số ghế còn lại cần thêm cho loại ghế được chọn
            $currentTypeSeats = $existingSeatsByType[$request->seat_type_id] ?? 0;
            $requiredTypeSeats = $requiredSeats[$request->seat_type_id] ?? 0;
            $remainingCapacity = $room->capacity - $existingSeatsCount;
            $totalSeatsToAdd = min($requiredTypeSeats - $currentTypeSeats, $remainingCapacity);

            // Định nghĩa maxRows và maxSeatsPerRow
            $maxRows = 26;
            $maxSeatsPerRow = 50;

            // Tự động tính số ghế mỗi hàng dựa trên min_seats_per_row
            $minSeatsPerRow = $request->min_seats_per_row;
            $optimalSeatsPerRow = $minSeatsPerRow;
            for ($i = $minSeatsPerRow; $i <= $maxSeatsPerRow; $i++) {
                if ($totalSeatsToAdd % $i === 0 && $totalSeatsToAdd / $i <= $maxRows) {
                    $optimalSeatsPerRow = $i;
                    break;
                }
            }
            $rows = ceil($totalSeatsToAdd / $optimalSeatsPerRow);

            // Kiểm tra số ghế thực tế sẽ được thêm
            $totalSeatsProposed = $rows * $optimalSeatsPerRow;
            if ($totalSeatsProposed > $totalSeatsToAdd) {
                $excessSeats = $totalSeatsProposed - $totalSeatsToAdd;
                $validator->errors()->add('min_seats_per_row', 'Số ghế tối thiểu mỗi hàng dẫn đến ' . $excessSeats . ' ghế dư. Vui lòng chọn giá trị khác để khớp với ' . $totalSeatsToAdd . ' ghế cần thêm.');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($rows * $optimalSeatsPerRow > $remainingCapacity) {
                $validator->errors()->add('min_seats_per_row', 'Số ghế muốn thêm vượt quá sức chứa còn lại (' . $remainingCapacity . ' ghế).');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Kiểm tra ghế trùng lặp
            $existingSeats = Seat::where('room_id', $request->room_id)
                ->get()
                ->groupBy('row_char')
                ->map->pluck('seat_number')
                ->toArray();

            // Tạo ghế mới
            $createdSeats = 0;
            $maxRowChar = Seat::where('room_id', $request->room_id)->max('row_char');
            $startRowIndex = $maxRowChar ? ord(strtoupper($maxRowChar)) - 64 : 0;

            for ($i = 0; $i < $rows; $i++) {
                $rowChar = chr(65 + $startRowIndex + $i);
                if (strlen($rowChar) > 5) {
                    throw new \Exception('Số hàng vượt quá giới hạn ký tự cho phép.');
                }
                for ($j = 1; $j <= $optimalSeatsPerRow; $j++) {
                    $seatNumber = str_pad($j, 2, '0', STR_PAD_LEFT);
                    if (isset($existingSeats[$rowChar]) && in_array($seatNumber, $existingSeats[$rowChar])) {
                        \Log::warning("Seat $rowChar$seatNumber already exists, skipping.");
                        continue;
                    }
                    Seat::create([
                        'room_id' => $request->room_id,
                        'seat_type_id' => $request->seat_type_id,
                        'row_char' => $rowChar,
                        'seat_number' => $seatNumber,
                        'status' => SeatStatus::Available->value ?? 'available',
                    ]);
                    \Log::info("Created seat: $rowChar$seatNumber");
                    $createdSeats++;
                    if ($existingSeatsCount + $createdSeats > $room->capacity) {
                        throw new \Exception('Số ghế vượt quá sức chứa của phòng (' . $room->capacity . ' ghế).');
                    }
                }
            }

            // Xác định loại ghế tiếp theo
            $seatTypesOrder = config('seat_types.order');
            $currentTypeSeats += $createdSeats;
            $remainingTypeSeats = $requiredTypeSeats - $currentTypeSeats;
            $nextSeatTypeId = null;
            if ($remainingTypeSeats <= 0) {
                $nextTypeIndex = array_search($seatTypes[$request->seat_type_id]->name, $seatTypesOrder) + 1;
                if ($nextTypeIndex < count($seatTypesOrder)) {
                    $nextSeatType = SeatType::where('name', $seatTypesOrder[$nextTypeIndex])->first();
                    if ($nextSeatType) {
                        $nextSeatTypeId = $nextSeatType->id;
                    }
                }
            } else {
                $nextSeatTypeId = $request->seat_type_id;
            }

            DB::commit();
            return redirect()->route('admin.rooms.show', $request->room_id)
                ->with('success', "$createdSeats ghế loại '{$seatTypes[$request->seat_type_id]->name}' đã được thêm thành công!")
                ->with('next_seat_type_id', $nextSeatTypeId);

        } catch (\Exception $e) {
            \Log::error('Exception during seat creation: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi thêm ghế: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $seat = Seat::with('room', 'seatType')->findOrFail($id);
        $rooms = Room::all();
        $seatTypes = SeatType::all();
        session(['return_room_id' => $seat->room_id]);
        return view('admin.seats.edit', compact('seat', 'rooms', 'seatTypes'));
    }

    public function update(Request $request, $id)
    {
        $seat = Seat::findOrFail($id);

        $request->validate([
            'seat_type_id' => 'required|exists:seat_types,id',
            'status' => 'required|in:available,reserved,booked',
        ]);

        $seat->update([
            'seat_type_id' => $request->seat_type_id,
            'status' => $request->status,
        ]);

        // Lấy room_id từ session và redirect về trang chi tiết phòng cụ thể
        $roomId = session('return_room_id');
        return redirect()->route('admin.rooms.show', ['room' => $roomId])->with('success', 'Ghế đã được cập nhật thành công!');
    }

    public function show($id)
    {
        $seat = Seat::with('room', 'seatType')->findOrFail($id);
        return view('admin.seats.show', compact('seat'));
    }

    public function editBulk(Request $request)
    {
        $seatIds = $request->input('seat_ids', []);
        
        if (empty($seatIds)) {
            return redirect()->route('admin.seats.index')->with('error', 'Vui lòng chọn ít nhất một ghế để chỉnh sửa.');
        }

        $seats = Seat::with('room', 'seatType')->whereIn('id', $seatIds)->get();
        $seatTypes = SeatType::all();

        return view('admin.seats.edit-bulk', compact('seats', 'seatTypes'));
    }

    public function updateBulk(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'required|integer|exists:seats,id',
            'seat_type_id' => 'nullable|integer|exists:seat_types,id',
            'status' => 'nullable|string|in:available,booked,sold,broken',
        ]);

        // Lấy seat_ids từ request
        $seatIds = $request->input('seat_ids', []);
        $seatTypeId = $request->input('seat_type_id');
        $status = $request->input('status');

        // Kiểm tra nếu không có ghế nào được chọn
        if (empty($seatIds)) {
            return redirect()->route('admin.seats.index')->with('error', 'Không có ghế nào được chọn để cập nhật.');
        }

        // Cập nhật hàng loạt ghế trong transaction
        DB::transaction(function () use ($seatIds, $seatTypeId, $status) {
            foreach ($seatIds as $seatId) {
                $seat = Seat::find($seatId);
                if ($seat) {
                    // Chỉ cập nhật nếu có giá trị mới
                    if ($seatTypeId) {
                        $seat->seat_type_id = $seatTypeId;
                    }
                    if ($status) {
                        $seat->status = $status; // Nếu Seat model sử dụng enum, giá trị này sẽ tự động cast
                    }
                    $seat->save();
                }
            }
        });

        return redirect()->route('admin.seats.index')->with('success', 'Cập nhật hàng loạt ghế thành công!');
    }
}