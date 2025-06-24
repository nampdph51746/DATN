<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;
use App\Enums\SeatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        return view('admin.seats.create', compact('room', 'seatTypes'));
    }

public function store(Request $request)
    {
        \Log::info('Request data: ', $request->all());

        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'seat_type_id' => 'required|exists:seat_types,id',
            'rows' => 'required|integer|min:1',
            'seats_per_row' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'room_id.required' => 'Phòng chiếu là bắt buộc.',
            'room_id.exists' => 'Phòng chiếu không tồn tại.',
            'seat_type_id.required' => 'Loại ghế là bắt buộc.',
            'seat_type_id.exists' => 'Loại ghế không tồn tại.',
            'rows.required' => 'Số hàng là bắt buộc.',
            'rows.integer' => 'Số hàng phải là số nguyên.',
            'rows.min' => 'Số hàng phải lớn hơn hoặc bằng 1.',
            'seats_per_row.required' => 'Số ghế mỗi hàng là bắt buộc.',
            'seats_per_row.integer' => 'Số ghế mỗi hàng phải là số nguyên.',
            'seats_per_row.min' => 'Số ghế mỗi hàng phải lớn hơn hoặc bằng 1.',
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

        // Kiểm tra phòng đầy
        $existingSeatsCount = Seat::where('room_id', $request->room_id)->count();
        if ($existingSeatsCount >= $room->capacity) {
            $validator->errors()->add('room_id', 'Phòng đã đầy ghế.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Lấy cấu hình tỷ lệ phần trăm
        $seatPercentages = config('seat_types.percentages');
        $seatTypesOrder = config('seat_types.order');
        $seatType = SeatType::findOrFail($request->seat_type_id);

        // Tính số ghế cần thêm cho mỗi loại
        $totalSeats = $room->capacity;
        $requiredSeats = [];
        foreach ($seatPercentages as $type => $percentage) {
            $requiredSeats[$type] = (int) round(($percentage / 100) * $totalSeats);
        }

        // Đếm số ghế hiện có của mỗi loại
        $existingSeatsByType = Seat::where('room_id', $request->room_id)
            ->join('seat_types', 'seats.seat_type_id', '=', 'seat_types.id')
            ->select('seat_types.name', DB::raw('count(*) as count'))
            ->groupBy('seat_types.name')
            ->pluck('count', 'seat_types.name')
            ->toArray();

        // Xác định loại ghế hợp lệ tiếp theo
        $nextTypeIndex = 0;
        foreach ($seatTypesOrder as $index => $type) {
            $currentTypeSeats = $existingSeatsByType[$type] ?? 0;
            $requiredTypeSeats = $requiredSeats[$type] ?? 0;
            if ($currentTypeSeats < $requiredTypeSeats) {
                $nextTypeIndex = $index;
                break;
            }
        }

        // Kiểm tra xem loại ghế gửi lên có hợp lệ không
        if ($seatType->name !== $seatTypesOrder[$nextTypeIndex]) {
            $validator->errors()->add('seat_type_id', "Vui lòng thêm hết ghế {$seatTypesOrder[$nextTypeIndex]} trước khi thêm ghế {$seatType->name}.");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra số ghế muốn thêm
        $totalSeatsToAdd = $request->rows * $request->seats_per_row;
        $currentTypeSeats = $existingSeatsByType[$seatType->name] ?? 0;
        $requiredTypeSeats = $requiredSeats[$seatType->name] ?? 0;

        if ($currentTypeSeats + $totalSeatsToAdd > $requiredTypeSeats) {
            $validator->errors()->add('rows', "Số ghế {$seatType->name} muốn thêm vượt quá số ghế yêu cầu ({$requiredTypeSeats} ghế, đã có {$currentTypeSeats} ghế).");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $remainingCapacity = $room->capacity - $existingSeatsCount;
        if ($totalSeatsToAdd > $remainingCapacity) {
            $validator->errors()->add('rows', 'Số ghế muốn thêm vượt quá sức chứa còn lại của phòng (' . $remainingCapacity . ' ghế).');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra ghế trùng lặp
        $existingSeats = Seat::where('room_id', $request->room_id)
            ->get()
            ->groupBy('row_char')
            ->map->pluck('seat_number')
            ->toArray();
        \Log::info('Existing seats: ', $existingSeats);

        for ($i = 0; $i < $request->rows; $i++) {
            $rowChar = chr(65 + $i);
            if (strlen($rowChar) > 5) {
                $validator->errors()->add('rows', 'Số hàng vượt quá giới hạn ký tự cho phép.');
                break;
            }
            for ($j = 1; $j <= $request->seats_per_row; $j++) {
                $seatNumber = str_pad($j, 2, '0', STR_PAD_LEFT);
                if (isset($existingSeats[$rowChar]) && in_array($seatNumber, $existingSeats[$rowChar])) {
                    $validator->errors()->add('seats', "Ghế $rowChar$seatNumber đã tồn tại trong phòng này.");
                }
            }
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            \Log::info('Starting seat creation for room_id: ' . $request->room_id);
            $createdSeats = 0;
            $maxRowChar = Seat::where('room_id', $request->room_id)->max('row_char');
            $startRowIndex = $maxRowChar ? ord(strtoupper($maxRowChar)) - 64 : 0;

            for ($i = 0; $i < $request->rows; $i++) {
                $rowChar = chr(65 + $startRowIndex + $i);
                if (strlen($rowChar) > 5) {
                    throw new \Exception('Số hàng vượt quá giới hạn ký tự cho phép.');
                }
                for ($j = 1; $j <= $request->seats_per_row; $j++) {
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

            \Log::info('Created ' . $createdSeats . ' seats successfully.');
            \Log::info('Before DB commit');
            DB::commit();
            \Log::info('After DB commit');

            // Tính số ghế còn lại cần thêm cho loại hiện tại
            $currentTypeSeats += $createdSeats;
            $remainingTypeSeats = $requiredTypeSeats - $currentTypeSeats;

            // Xác định loại ghế tiếp theo
            $nextSeatTypeId = null;
            if ($remainingTypeSeats <= 0) {
                $nextTypeIndex = array_search($seatType->name, $seatTypesOrder) + 1;
                if ($nextTypeIndex < count($seatTypesOrder)) {
                    $nextSeatType = SeatType::where('name', $seatTypesOrder[$nextTypeIndex])->first();
                    if ($nextSeatType) {
                        $nextSeatTypeId = $nextSeatType->id;
                    }
                }
            } else {
                $nextSeatTypeId = $seatType->id; // Tiếp tục với loại hiện tại
            }

            return redirect()->route('admin.rooms.show', $request->room_id)
                ->with('success', "$createdSeats ghế loại '$seatType->name' đã được thêm thành công!")
                ->with('next_seat_type_id', $nextSeatTypeId);

        } catch (\Exception $e) {
            \Log::error('Exception during seat creation: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi thêm ghế: ' . $e->getMessage());
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