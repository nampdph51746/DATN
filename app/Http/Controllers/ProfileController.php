<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Room;
use App\Models\Banner;
use App\Models\Movie;
use App\Models\Product;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Models\Review;
use App\Enums\MovieStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Promotion;
use App\Models\Booking;
use App\Enums\PointReasonType;
use App\Enums\PromotionDiscountType;
use App\Models\Point;
use App\Models\CustomerRank;
use App\Services\ShowtimeAvailabilityService;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; // ⚠️ cần import Controller gốc
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $request->user()->fill($request->only(['name', 'phone_number', 'date_of_birth', 'address']));

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Cập nhật thông tin thành công');
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);

            if (!Hash::check($request->old_password, auth()->user()->password)) {
                return back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng.']);
            }

            $user = auth()->user();
            $user->password = bcrypt($request->new_password);
            $user->save();
            Auth::logout();

            return back()->with('success', 'Đổi mật khẩu thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi đổi mật khẩu: ' . $e->getMessage()]);
        }
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function general()
    {
        $user = Auth::user();

        // Lấy thông tin rank qua quan hệ
        $rank = \DB::table('customer_ranks')
            ->where('id', $user->customer_rank_id)
            ->value('name');
        $totalSpent = Booking::where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->sum('final_amount');
        $totalPoints = Point::where('user_id', $user->id)
    ->value('total_points') ?? 0;

        return view('client.profilegeneral', [
            'user' => $user,
            'rank' => $rank,
            'totalSpent' => $totalSpent,
            'totalPoints' => $totalPoints,
        ]);
    }

    public function index()
    {
        $userId = Auth::id();

        $orders = Order::with([
                'movie:id,title,poster_url',
                'seats:order_id,seat_number'
            ])
            ->where('user_id', $userId)
            ->orderByDesc('show_date')
            ->orderByDesc('show_time')
            ->get()
            ->map(function ($order) {
                return (object)[
                    'id' => $order->id,
                    'booking_code' => $order->booking_code,
                    'status' => $order->status,
                    'status_text' => $this->getStatusText($order->status),
                    'movie' => $order->movie,
                    'show_date' => $order->show_date,
                    'show_time' => $order->show_time,
                    'cinema_name' => $order->cinema_name,
                    'room_name' => $order->room_name,
                    'seats' => $order->seats->pluck('seat_number')->toArray(),
                    'total_price' => $order->total_price
                ];
            });

        return view('client.profile.ticket-history', compact('orders'));
    }

    private function getStatusText($status)
    {
        switch ($status) {
            case 'paid':
                return 'Đã thanh toán';
            case 'pending':
                return 'Chờ thanh toán';
            case 'canceled':
                return 'Đã hủy';
            default:
                return 'Không xác định';
        }
    }

   public function history(Request $request)
{
    $user = Auth::user();

        // Nếu không có người dùng đăng nhập, chuyển hướng hoặc trả về thông báo
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem lịch sử đặt vé.');
        }

        // Lấy danh sách đặt vé của người dùng, bao gồm các mối quan hệ cần thiết
        $bookings = Booking::with(['tickets.showtime.movie', 'tickets.seat.room.cinema'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Hiển thị 10 bản ghi mỗi trang

        // Trả về view với dữ liệu
        return view('client.historybooking', compact('bookings'));
}

public function detail($id)
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem chi tiết.');
    }

    $booking = Booking::with(['tickets.showtime.movie', 'tickets.seat.room.cinema', 'items'])
        ->where('user_id', $user->id)
        ->findOrFail($id);

    return view('client.booking-detail', compact('booking'));
}

public function membership()
{
    $user = Auth::user();

    // Lấy danh sách hạng theo số điểm tối thiểu tăng dần
    $ranks = \App\Models\CustomerRank::orderBy('min_points_required', 'asc')->get();

    // Lấy số điểm hiện tại (nếu không có thì mặc định là 0)
    $currentPoints = \App\Models\Point::where('user_id', $user->id)->value('total_points') ?? 0;

    // Lấy hạng hiện tại từ quan hệ
    $currentRank = $user->customerRank ?? null;

    // Xác định mốc hạng tiếp theo
    $nextRank = $ranks->firstWhere('min_points_required', '>', $currentPoints);

    // Tính phần trăm tiến trình
    if ($nextRank) {
        $currentRankPoints = $currentRank ? $currentRank->min_points_required : 0;
        $pointsNeededForNext = $nextRank->min_points_required - $currentRankPoints;
        $progress = ($currentPoints - $currentRankPoints) / $pointsNeededForNext * 100;
    } else {
        $progress = 100; // Đã đạt hạng cao nhất
    }

    return view('client.membercard', compact(
        'user',
        'ranks',
        'currentPoints',
        'currentRank',
        'nextRank',
        'progress'
    ));
}


}
