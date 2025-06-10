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

        $room = Room::findOrFail($request->room_id);
        if ($room->status !== 'active') {
            $validator->errors()->add('room_id', 'Phòng chiếu không ở trạng thái hoạt động.');
        }

        $totalSeatsToAdd = $request->rows * $request->seats_per_row;
        $existingSeatsCount = Seat::where('room_id', $request->room_id)->count();
        if ($existingSeatsCount + $totalSeatsToAdd > $room->capacity) {
            $validator->errors()->add('rows', 'Số ghế muốn thêm vượt quá sức chứa của phòng (' . $room->capacity . ' ghế).');
        }

        // Kiểm tra ghế trùng lặp
        $existingSeats = Seat::where('room_id', $request->room_id)
            ->get()
            ->groupBy('row_char')
            ->map->pluck('seat_number')
            ->toArray();
        for ($i = 0; $i < $request->rows; $i++) {
            $rowChar = chr(65 + $i); // A, B, C, ...
            if (strlen($rowChar) > 5) {
                $validator->errors()->add('rows', 'Số hàng vượt quá giới hạn ký tự cho phép.');
                break;
            }
            for ($j = 1; $j <= $request->seats_per_row; $j++) {
                $seatNumber = str_pad($j, 2, '0', STR_PAD_LEFT); // 01, 02, ...
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
            $createdSeats = 0;
            // Tìm hàng cao nhất hiện có
            $maxRowChar = Seat::where('room_id', $request->room_id)
                ->max('row_char');
            $startRowIndex = $maxRowChar ? ord(strtoupper($maxRowChar)) - 64 : 0; // Chuyển từ A=1, B=2, ...

            for ($i = 0; $i < $request->rows; $i++) {
                $rowChar = chr(65 + $startRowIndex + $i); // Bắt đầu từ hàng tiếp theo
                if (strlen($rowChar) > 5) {
                    throw new \Exception('Số hàng vượt quá giới hạn ký tự cho phép.');
                }
                for ($j = 1; $j <= $request->seats_per_row; $j++) {
                    $seatNumber = str_pad($j, 2, '0', STR_PAD_LEFT);
                    if (isset($existingSeats[$rowChar]) && in_array($seatNumber, $existingSeats[$rowChar])) {
                        continue; // Bỏ qua ghế trùng lặp
                    }
                    Seat::create([
                        'room_id' => $request->room_id,
                        'seat_type_id' => $request->seat_type_id,
                        'row_char' => $rowChar,
                        'seat_number' => $seatNumber,
                        'status' => SeatStatus::Available->value,
                    ]);
                    $createdSeats++;
                    if ($existingSeatsCount + $createdSeats > $room->capacity) {
                        throw new \Exception('Số ghế vượt quá sức chứa của phòng (' . $room->capacity . ' ghế).');
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.rooms.show', $request->room_id)
                ->with('success', "$createdSeats ghế đã được thêm thành công!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi thêm ghế: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $seat = Seat::with('room', 'seatType')->findOrFail($id);
        $rooms = Room::all();
        $seatTypes = SeatType::all();
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

        return redirect()->route('admin.seats.index')->with('success', 'Ghế đã được cập nhật thành công!');
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