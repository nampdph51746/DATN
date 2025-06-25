<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ClientController extends Controller
{
    public function show(Request $request, $id)
    {
        // Lấy thông tin phim (kèm country, ageLimit)
        $movie = Movie::with(['country', 'ageLimit'])->findOrFail($id);

        // Lấy danh sách phòng (phục vụ lọc hoặc hiển thị)
        $rooms = Room::all();

        // Tạo danh sách 15 ngày kế tiếp
        $dates = collect(range(0, 14))->map(fn ($i) => now()->addDays($i));

        // Lấy ngày được chọn từ request (hoặc mặc định hôm nay)
        $selectedDate = $request->input('date') ?? now()->format('Y-m-d');

        // Truy vấn suất chiếu theo phim và ngày
        $showtimes = $movie->showtimes()
            ->with('room')
            ->whereDate('start_time', $selectedDate)
            ->orderBy('start_time')
            ->get();

        return view('client.detailmovie', compact(
            'movie',
            'showtimes',
            'rooms',
            'dates',
            'selectedDate'
        ));
    }

public function ticketBooking($id)
{
    $movie = Movie::with('showtimes.room')->findOrFail($id);

    // Tạo danh sách 30 ngày tới
    $days = collect();
    for ($i = 0; $i < 30; $i++) {
        $date = now()->addDays($i);
        $days->push([
            'date' => $date->toDateString(),
            'day' => $date->format('d'),
            'label' => match(true) {
                $i === 0 => 'Hôm nay',
                $i === 1 => 'Ngày mai',
                default => $date->format('d/m')  // Hiển thị ngày/tháng thay cho thứ
            }
        ]);
    }

    // Nhóm suất chiếu theo ngày (chỉ lấy suất sau thời gian hiện tại)
    $groupedShowtimes = $movie->showtimes
        ->filter(function ($s) {
            return \Carbon\Carbon::parse($s->start_time)->isAfter(now());
        })
        ->groupBy(function ($s) {
            return \Carbon\Carbon::parse($s->start_time)->toDateString();
        });

    return view('client.ticket_booking', compact('movie', 'days', 'groupedShowtimes'));
}

}