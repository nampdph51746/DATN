<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('showtime_id', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('showtime_id')) {
            $query->where('showtime_id', $request->showtime_id);
        }
        if ($request->filled('booking_id')) {
            $query->where('booking_id', $request->booking_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(20);

        // Danh sách trạng thái có sẵn (có thể lấy từ model hoặc database)
        $statuses = ['booked' => 'Đã đặt', 'canceled' => 'Đã hủy', 'completed' => 'Hoàn thành'];

        return view('admin.tickets.index', compact('tickets', 'statuses'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['booking', 'showtime', 'seat'])->findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }
}
