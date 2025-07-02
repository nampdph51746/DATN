<?php

namespace App\Http\Controllers\Client;

use App\Models\Room;
use App\Models\Movie;
use App\Models\Product;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Models\Seat; // Giả sử có bảng Seat
use App\Enums\MovieStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $query = request('query');

        // Truy vấn phim đang chiếu
        $showingMovies = Movie::query()
            ->with('genres')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('genres', function ($g) use ($query) {
                      $g->where('name', 'like', "%{$query}%");
                  });
            })
            ->where('status', MovieStatus::Showing)
            ->orderBy('release_date', 'desc')
            ->take(8)
            ->get();

        // Truy vấn phim sắp chiếu
        $upcomingMovies = Movie::query()
            ->with('genres')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('genres', function ($g) use ($query) {
                      $g->where('name', 'like', "%{$query}%");
                  });
            })
            ->where('status', MovieStatus::Upcoming)
            ->orderBy('release_date', 'asc')
            ->take(6)
            ->get();

        return view('client.home', compact('showingMovies', 'upcomingMovies', 'query'));
    }

    public function show(Request $request, $id)
    {
        // Lấy thông tin phim (kèm quốc gia, giới hạn độ tuổi)
        $movie = Movie::with(['country', 'ageLimit'])->findOrFail($id);

        // Lấy danh sách phòng
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
        // Lấy thông tin phim
        $movie = Movie::findOrFail($id);
        $showtimes = $this->getShowtimes($id);
        if ($showtimes->isEmpty()) {
            Log::warning("Không tìm thấy suất chiếu cho phim ID {$id}");
            return redirect()->back()->with('error', 'Không có suất chiếu nào cho phim này.');
        }

        // Chuẩn bị danh sách ngày cho carousel
        $dates = $this->getDates($showtimes);

        // Lấy dữ liệu suất chiếu
        $showtimesData = $this->getShowtimesData($showtimes);

        // Lấy showtime_id từ query string
        $showtimeId = request()->input('showtime_id');
        $showtime = null;
        if ($showtimeId) {
            $showtime = Showtime::with(['room', 'seats' => function ($query) {
                $query->where('status', 'available')->orWhere('status', 'reserved');
            }])->find($showtimeId);
            if (!$showtime) {
                Log::warning("Suất chiếu ID {$showtimeId} không hợp lệ cho phim ID {$id}");
                return redirect()->back()->with('error', 'Suất chiếu không hợp lệ.');
            }
        }

        // Lấy danh sách loại ghế
        $seatTypes = SeatType::all();

        // Lấy tất cả sản phẩm đang hoạt động cùng với các biến thể
        $products = Product::where('is_active', true)
            ->with(['productVariants' => function ($query) {
                $query->where('is_active', true)
                    ->with(['productVariantOptions.attributeValue.attribute']);
            }])
            ->get();
        if ($products->isEmpty()) {
            Log::warning("Không có sản phẩm nào đang hoạt động cho phim ID {$id}");
        }

        return view('client.ticket_booking', compact('movie', 'dates', 'showtimesData', 'showtimeId', 'seatTypes', 'products', 'showtime'));
    }

    private function getShowtimes($movieId)
    {
        $showtimes = Showtime::where('movie_id', $movieId)
            ->where('status', 'scheduled')
            ->where('start_time', '>=', Carbon::now())
            ->with('room')
            ->get();
        
        return $showtimes;
    }

    private function getDates($showtimes)
    {
        $showtimesByDate = $showtimes->groupBy(function ($showtime) {
            return Carbon::parse($showtime->start_time)->format('Y-m-d');
        });

        $dates = [];
        foreach ($showtimesByDate as $date => $showtimes) {
            $carbonDate = Carbon::parse($date);
            $isToday = $carbonDate->isToday();
            $isTomorrow = $carbonDate->isTomorrow();
            $dayName = $isToday ? 'Hôm nay' : ($isTomorrow ? 'Ngày mai' : $carbonDate->format('l'));
            $dates[] = [
                'date' => $carbonDate->format('d'),
                'day' => $dayName,
                'full_date' => $date,
            ];
        }

        return collect($dates)->sortBy('full_date')->values()->toArray();
    }

    private function getShowtimesData($showtimes)
    {
        $showtimesByDate = $showtimes->groupBy(function ($showtime) {
            return Carbon::parse($showtime->start_time)->format('Y-m-d');
        });

        $showtimesData = [];
        foreach ($showtimesByDate as $date => $showtimes) {
            $showtimesData[$date] = $showtimes->groupBy('room_id')->map(function ($roomShowtimes) {
                return [
                    'room_name' => $roomShowtimes->first()->room->name,
                    'times' => $roomShowtimes->map(function ($showtime) {
                        return [
                            'id' => $showtime->id,
                            'time' => Carbon::parse($showtime->start_time)->format('h:i A'),
                            'base_price' => $showtime->base_price,
                        ];
                    })->toArray(),
                ];
            })->values()->toArray();
        }

        return $showtimesData;
    }

    public function movies(Request $request)
    {
        $query = $request->input('query');

        // Phim đang chiếu: Sắp xếp theo số lượng vé bán ra
        $showingMovies = Movie::query()
            ->with(['genres'])
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('genres', function ($g) use ($query) {
                      $g->where('name', 'like', "%{$query}%");
                  });
            })
            ->where('status', MovieStatus::Showing)
            ->withCount(['showtimes' => function ($q) {
                $q->where('start_time', '>=', Carbon::now()->subDays(30))
                  ->whereHas('tickets');
            }])
            ->orderBy('showtimes_count', 'desc')
            ->take(8)
            ->get();

        // Phim mới: Sắp xếp theo created_at mới nhất
        $recentMovies = Movie::query()
            ->with(['genres'])
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('genres', function ($g) use ($query) {
                      $g->where('name', 'like', "%{$query}%");
                  });
            })
            ->whereIn('status', [MovieStatus::Showing, MovieStatus::Upcoming])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Phim phổ biến: Dựa trên số vé bán ra trong 30 ngày
        $popularMovies = Movie::query()
            ->with(['genres'])
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('genres', function ($g) use ($query) {
                      $g->where('name', 'like', "%{$query}%");
                  });
            })
            ->where('status', MovieStatus::Showing)
            ->withCount(['showtimes' => function ($q) {
                $q->where('start_time', '>=', Carbon::now()->subDays(30))
                  ->whereHas('tickets');
            }])
            ->orderBy('showtimes_count', 'desc')
            ->take(8)
            ->get();

        // Phim xu hướng: Dựa trên số vé bán ra trong 7 ngày
        $trendMovies = Movie::query()
            ->with(['genres'])
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('genres', function ($g) use ($query) {
                      $g->where('name', 'like', "%{$query}%");
                  });
            })
            ->whereIn('status', [MovieStatus::Showing, MovieStatus::Upcoming])
            ->withCount(['showtimes' => function ($q) {
                $q->where('start_time', '>=', Carbon::now()->subDays(7))
                  ->whereHas('tickets');
            }])
            ->orderBy('showtimes_count', 'desc')
            ->take(8)
            ->get();

        return view('client.movies', compact('showingMovies', 'recentMovies', 'popularMovies', 'trendMovies', 'query'));
    }
}