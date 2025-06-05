<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Room;
use App\Models\SeatType;
use Illuminate\Http\Request;

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
}