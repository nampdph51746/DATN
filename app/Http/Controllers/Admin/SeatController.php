<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Room;
use App\Models\SeatType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatController extends Controller
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

    public function create()
    {
        $rooms = Room::all();
        $seatTypes = SeatType::all();
        return view('admin.seats.create', compact('rooms', 'seatTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'seat_type_id' => 'required|exists:seat_types,id',
            'rows' => 'required|integer|min:1',
            'seats_per_row' => 'required|integer|min:1',
        ]);

        $roomId = $request->room_id;
        $seatTypeId = $request->seat_type_id;
        $rows = $request->rows;
        $seatsPerRow = $request->seats_per_row;

        $existingSeats = Seat::where('room_id', $roomId)->count();
        if ($existingSeats > 0) {
            return redirect()->back()->with('error', 'Ghế đã tồn tại cho phòng này!');
        }

        $createdSeats = 0;
        for ($i = 0; $i < $rows; $i++) {
            $rowChar = chr(65 + $i);
            for ($j = 1; $j <= $seatsPerRow; $j++) {
                $seatNumber = str_pad($j, 2, '0', STR_PAD_LEFT);
                Seat::create([
                    'room_id' => $roomId,
                    'seat_type_id' => $seatTypeId,
                    'row_char' => $rowChar,
                    'seat_number' => $seatNumber,
                    'status' => 'available',
                ]);
                $createdSeats++;
            }
        }

        return redirect()->route('admin.seats.index')->with('success', "$createdSeats ghế đã được thêm thành công!");
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
            'status' => 'required|in:available,booked,sold',
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