<?php

namespace App\Http\Controllers\Client;

use App\Models\Room;
use App\Models\Movie;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Enums\MovieStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $query = request('query');

        // Query cho phim đang chiếu
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

        // Query riêng cho phim sắp chiếu
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

        // Debug
        \Log::info('Showing Movies Count: ' . $showingMovies->count());
        \Log::info('Upcoming Movies Count: ' . $upcomingMovies->count());
        \Log::info('Upcoming Movies Data: ' . $upcomingMovies->toJson());

        return view('client.home', compact('showingMovies', 'upcomingMovies', 'query'));
        }

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
        $movie = Movie::findOrFail($id);
        $showtimes = $this->getShowtimes($id);
        
        \Log::info('Showtimes for movie ID ' . $id . ': ' . $showtimes->toJson());
        if ($showtimes->isEmpty()) {
            \Log::warning('No showtimes found for movie ID ' . $id);
        }

        $dates = $this->getDates($showtimes);
        $showtimesData = $this->getShowtimesData($showtimes);
        $seatTypes = SeatType::all();
        $showtimeId = request()->input('showtime_id'); // Chỉ lấy từ query string, không gán mặc định

        \Log::info('Dates: ' . json_encode($dates));
        \Log::info('Showtimes Data: ' . json_encode($showtimesData));
        \Log::info('Selected Showtime ID: ' . ($showtimeId ?? 'none'));

        return view('client.ticket_booking', compact('movie', 'dates', 'showtimesData', 'showtimeId', 'seatTypes'));
    }

    private function getShowtimes($movieId)
    {
        $showtimes = Showtime::where('movie_id', $movieId)
            ->where('status', 'scheduled')
            ->where('start_time', '>=', Carbon::now())
            ->with('room')
            ->get();
        
        \Log::info('Filtered Showtimes for movie ID ' . $movieId . ': ' . $showtimes->toJson());
        
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
            $dayName = $isToday ? 'Today' : ($isTomorrow ? 'Tomorrow' : $carbonDate->format('l'));
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

        // Phim đang chiếu: Sắp xếp theo số lượng vé bán ra (giả định có bảng tickets)
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
                  ->whereHas('tickets'); // Đếm vé bán ra trong 30 ngày
            }])
            ->orderBy('showtimes_count', 'desc')
            ->take(8)
            ->get();

        // Recent Movies: Sắp xếp theo created_at mới nhất
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

        // Popular Movies: Dựa trên số vé bán ra trong 30 ngày
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

        // Trend Movies: Dựa trên số vé bán ra trong 7 ngày
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

        // Debug
        \Log::info('Showing Movies: ' . $showingMovies->toJson());
        \Log::info('Recent Movies: ' . $recentMovies->toJson());
        \Log::info('Popular Movies: ' . $popularMovies->toJson());
        \Log::info('Trend Movies: ' . $trendMovies->toJson());

        return view('client.movies', compact('showingMovies', 'recentMovies', 'popularMovies', 'trendMovies', 'query'));
    }
}