<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use App\Models\Point;
use App\Enums\PointReasonType;

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

    public function toggle($id)
    {
        $history = PointHistory::findOrFail($id);
        $user = $history->user;

        $point = Point::firstOrCreate(
            ['user_id' => $user->id],
            ['points_expiry_date' => now()->addYear()]
        );

        // Xóa tác động điểm cũ
        $point->total_points -= $history->points_change;
        $point->total_points = max(0, $point->total_points); // không cho âm
        $point->save();

        // Đảo chiều
        $reversedPoints = -$history->points_change;
        $isEarned = $history->reason_type === PointReasonType::Earned->value;

        // Cập nhật điểm mới
        $point->total_points += $reversedPoints;
        $point->total_points = max(0, $point->total_points);
        $point->save();

        // Cập nhật lịch sử
        $history->points_change = $reversedPoints;
        $history->reason_type = $isEarned ? PointReasonType::Spent->value : PointReasonType::Earned->value;
        $history->description = ($isEarned ? 'Trừ' : 'Cộng') . ' điểm do chuyển đổi trạng thái đơn hàng #' . $history->booking_id;
        $history->save();

        return redirect()->back()->with('success', 'Đã chuyển trạng thái cộng/trừ điểm và cập nhật trạng thái đơn hàng thành công.');
    }
}
