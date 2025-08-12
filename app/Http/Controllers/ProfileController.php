<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Booking;
use App\Models\Point;

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
        $statusFilter = $request->query('status'); // pending, confirmed, cancelled, refunded, expired

        // Lấy lịch sử booking của user
        $bookingsQuery = Booking::where('user_id', $user->id)
            ->with([
                'tickets.showtime.movie',
                'tickets.seat.room.cinema',
                'items.productVariant.product'
            ]);

        // Nếu có filter trạng thái thì áp dụng
        if ($statusFilter && in_array($statusFilter, ['pending', 'confirmed', 'cancelled', 'refunded', 'expired'])) {
            $bookingsQuery->where('status', $statusFilter);
        }

        // Lấy danh sách lịch sử phim (mới nhất trước)
        $bookings = $bookingsQuery
            ->orderByDesc('created_at')
            ->get();

        return view('client.historybooking', compact('bookings', 'statusFilter'));
    }
}
