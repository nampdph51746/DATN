<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointHistory;
use Illuminate\Http\Request;

class PointHistoryController extends Controller
{
    public function index(Request $request)
{
    $query = PointHistory::with('user', 'booking');

    // Lọc theo user_id
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // Các filter khác nếu có...
    if ($request->filled('booking_id')) {
        $query->where('booking_id', $request->booking_id);
    }
    if ($request->filled('reason_type')) {
        $query->where('reason_type', $request->reason_type);
    }

    $histories = $query->orderByDesc('created_at')->paginate(20);
    return view('admin.point_history.index', compact('histories'));
}

    public function show($id)
    {
        $history = PointHistory::with('user', 'booking')->findOrFail($id);
        return view('admin.point_history.show', compact('history'));
    }
}