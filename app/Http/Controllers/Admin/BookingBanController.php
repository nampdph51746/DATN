<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserBookingBan;
use App\Models\User;
use App\Services\BookingAttemptService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingBanController extends Controller
{
    protected $bookingAttemptService;

    public function __construct(BookingAttemptService $bookingAttemptService)
    {
        $this->bookingAttemptService = $bookingAttemptService;
    }

    /**
     * Hiển thị danh sách user bị ban
     */
    public function index(Request $request)
    {
        $query = UserBookingBan::with('user');

        // Filter theo trạng thái
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'expired') {
                $query->expired();
            }
        }

        // Tìm kiếm theo tên user
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $bans = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.booking-bans.index', compact('bans'));
    }

    /**
     * Hiển thị chi tiết ban và thống kê attempts của user
     */
    public function show($id)
    {
        $ban = UserBookingBan::with('user')->findOrFail($id);
        $stats = $this->bookingAttemptService->getUserAttemptStats($ban->user_id);

        return view('admin.booking-bans.show', compact('ban', 'stats'));
    }

    /**
     * Hủy ban cho user
     */
    public function unban(Request $request, $id)
    {
        $ban = UserBookingBan::findOrFail($id);
        
        if ($this->bookingAttemptService->unbanUser($ban->user_id)) {
            return redirect()->back()->with('success', 'Đã hủy ban cho user thành công!');
        }
        
        return redirect()->back()->with('error', 'Không thể hủy ban cho user này');
    }

    /**
     * Tạo ban thủ công cho user
     */
    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'days' => 'required|integer|min:1|max:365',
            'reason' => 'required|string|max:500',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Kiểm tra user đã bị ban chưa
        if ($this->bookingAttemptService->isUserBanned($request->user_id)) {
            return redirect()->back()->with('error', 'User này đã bị ban rồi!');
        }

        UserBookingBan::create([
            'user_id' => $request->user_id,
            'failed_attempts' => 0, // Manual ban
            'banned_at' => now(),
            'banned_until' => now()->addDays($request->days),
            'is_active' => true,
            'reason' => $request->reason,
        ]);

        return redirect()->route('admin.booking-bans.index')
            ->with('success', "Đã ban user {$user->name} trong {$request->days} ngày!");
    }

    /**
     * API để search users
     */
    public function searchUsers(Request $request)
    {
        $search = $request->get('q');
        
        $users = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}
