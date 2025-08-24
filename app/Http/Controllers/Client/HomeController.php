<?php

namespace App\Http\Controllers\Client;

use App\Models\Room;
use App\Models\Movie;
use App\Models\Product;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Models\Review;
use App\Models\RoomType;
use App\Enums\MovieStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Promotion;
use App\Models\User;
use App\Models\Booking;
use App\Enums\PointReasonType;
use App\Enums\PromotionDiscountType;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\CustomerRank;
use App\Services\ShowtimeAvailabilityService;
use App\Services\BookingAttemptService;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $query = request('query');

        // Cập nhật trạng thái các phim đã kết thúc
        Movie::updateExpiredMovies();

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
            ->where(function($q) {
                // Chỉ lấy phim chưa kết thúc hoặc không có ngày kết thúc
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
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
            ->where(function($q) {
                // Chỉ lấy phim chưa kết thúc hoặc không có ngày kết thúc
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->orderBy('release_date', 'asc')
            ->take(6)
            ->get();

        // Kiểm tra trạng thái ban của user hiện tại
        $isUserBanned = false;
        $banInfo = null;
        if (Auth::check()) {
            $bookingAttemptService = new BookingAttemptService();
            
            // Không auto-cancel nếu đang ở bước thanh toán (session/payment flag)
            if (!session()->has('is_checkout') || !session('is_checkout')) {
                $cancelledCount = $bookingAttemptService->cancelAllActiveAttempts(Auth::id());
                if ($cancelledCount > 0) {
                    Log::info("Auto-cancelled {$cancelledCount} reserved attempts for user " . Auth::id() . " when accessing home page");
                }
            }
            
            $isUserBanned = $bookingAttemptService->isUserBanned(Auth::id());
            if ($isUserBanned) {
                $banInfo = $bookingAttemptService->getUserBanInfo(Auth::id());
            }
        }

        return view('client.home', compact('showingMovies', 'upcomingMovies', 'query', 'isUserBanned', 'banInfo'));
    }

        public function filter(Request $request, $genreName = null)
    {
        // Cập nhật trạng thái các phim đã kết thúc
        Movie::updateExpiredMovies();
        
        $data = $request->all();

        // Xử lý chuyển chuỗi rỗng thành null cho from_time và to_time
        $data['from_time'] = $data['from_time'] ?? null;
        $data['to_time'] = $data['to_time'] ?? null;
        if ($data['from_time'] === '') $data['from_time'] = null;
        if ($data['to_time'] === '') $data['to_time'] = null;

        $validator = Validator::make($data, [
            'status'    => 'nullable|in:showing,upcoming',
            'search'    => 'nullable|string|max:255',
            'date'      => 'nullable|date',
            'from_time' => ['nullable', 'date_format:H:i'],
            'to_time'   => ['nullable', 'date_format:H:i', 'after_or_equal:from_time'],
        ], [
            'status.in' => 'Trạng thái không hợp lệ.',
            'search.string' => 'Tìm kiếm phải là chuỗi.',
            'search.max' => 'Từ khóa tìm kiếm quá dài.',
            'date.date' => 'Ngày không hợp lệ.',
            'from_time.date_format' => 'Giờ bắt đầu không đúng định dạng.',
            'to_time.date_format' => 'Giờ kết thúc không đúng định dạng.',
            'to_time.after_or_equal' => 'Giờ kết thúc phải lớn hơn hoặc bằng giờ bắt đầu.',
        ]);

        // Custom rule để nếu nhập giờ thì phải nhập ngày
        $validator->sometimes('from_time', 'required', function ($input) {
            return !empty($input->from_time) && empty($input->date);
        });

        $validator->sometimes('to_time', 'required', function ($input) {
            return !empty($input->to_time) && empty($input->date);
        });

        $validator->after(function ($validator) use ($data) {
            if ((!empty($data['from_time']) || !empty($data['to_time'])) && empty($data['date'])) {
                $validator->errors()->add('date', 'Vui lòng chọn ngày nếu muốn nhập giờ.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Tiếp tục xử lý query lọc phim...

        $query = Movie::query()->with('genres', 'showtimes');

        $title = 'Danh sách phim';
        $isShowing = false;

        if (!empty($data['status'])) {
            $query->where('status', $data['status']);
            
            // Thêm điều kiện lọc theo ngày kết thúc
            if ($data['status'] === 'showing' || $data['status'] === 'upcoming') {
                $query->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                });
            }
            
            if ($data['status'] === 'showing') {
                $title = 'Phim đang chiếu';
                $isShowing = true;
            } elseif ($data['status'] === 'upcoming') {
                $title = 'Phim sắp chiếu';
            }
        }

        if ($genreName) {
            $query->whereHas('genres', function ($q) use ($genreName) {
                $q->where('name', $genreName);
            });
            $title = 'Phim ' . $genreName;
        }

        if (!empty($data['search'])) {
            $query->where('name', 'like', '%' . $data['search'] . '%');
            $title = 'Kết quả tìm kiếm';
        }

        if ($isShowing && !empty($data['date'])) {
            $date = $data['date'];
            $fromTime = $data['from_time'] ?? null;
            $toTime = $data['to_time'] ?? null;

            $query->whereHas('showtimes', function ($q) use ($date, $fromTime, $toTime) {
                $q->whereDate('start_time', $date);

                if ($fromTime && $toTime) {
                    $q->whereTime('start_time', '>=', $fromTime)
                        ->whereTime('start_time', '<=', $toTime);
                } elseif ($fromTime) {
                    $q->whereTime('start_time', '>=', $fromTime);
                } elseif ($toTime) {
                    $q->whereTime('start_time', '<=', $toTime);
                }
            });
        }

        if ($isShowing) {
            $query->withCount(['showtimes as tickets_sold' => function ($q) {
                $q->join('tickets', 'showtimes.id', '=', 'tickets.showtime_id')
                    ->join('bookings', 'tickets.booking_id', '=', 'bookings.id')
                    ->where('bookings.status', 'confirmed'); // chỉ tính vé đã xác nhận
            }])->orderByDesc('tickets_sold')
                ->orderByDesc('release_date');
        } else {
            $query->orderBy('release_date', 'desc');
        }

        $movies = $query->orderBy('release_date', 'desc')->paginate(12);

        return view('client.filter', compact('movies', 'title', 'isShowing'));
    }

    public function show(Request $request, $id)
    {
        // Lấy thông tin phim (kèm quốc gia, giới hạn độ tuổi)
        $movie = Movie::with(['country', 'ageLimit', 'genres'])->findOrFail($id);

        // Lấy reviews đã được duyệt
        $reviews = Review::with('user')
            ->where('movie_id', $id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Kiểm tra user hiện tại có thể review không
        $canReview = false;
        $reviewMessage = '';
        
        if (Auth::check()) {
            $user = Auth::user();
            
            // Kiểm tra đã xem phim chưa
            $hasWatchedMovie = Booking::where('user_id', $user->id)
                ->whereHas('showtime', function ($query) use ($id) {
                    $query->where('movie_id', $id);
                })
                ->where('status', 'confirmed')
                ->exists();

            // Kiểm tra đã đánh giá chưa
            $hasReviewed = Review::where('user_id', $user->id)
                ->where('movie_id', $id)
                ->exists();

            if (!$hasWatchedMovie) {
                $reviewMessage = 'Bạn cần xem phim này trước khi có thể đánh giá.';
            } elseif ($hasReviewed) {
                $reviewMessage = 'Bạn đã đánh giá phim này rồi.';
            } else {
                $canReview = true;
            }
        } else {
            $reviewMessage = 'Vui lòng đăng nhập để đánh giá.';
        }

        // Lấy danh sách phòng
        $rooms = Room::all();

        // Tạo danh sách 15 ngày kế tiếp
        $dates = collect(range(0, 14))->map(fn($i) => now()->addDays($i));

        // Lấy ngày được chọn từ request (hoặc mặc định hôm nay)
        $selectedDate = $request->input('date') ?? now()->format('Y-m-d');

        // Truy vấn suất chiếu theo phim và ngày (chỉ lấy những suất chưa đầy)
        $allShowtimes = $movie->showtimes()
            ->with(['room.seats', 'showtimeSeatStates'])
            ->whereDate('start_time', $selectedDate)
            ->where('status', 'scheduled')
            ->where('start_time', '>=', Carbon::now())
            ->orderBy('start_time')
            ->get();

        // Lọc ra các suất chiếu chưa đầy
        $availabilityService = new ShowtimeAvailabilityService();
        $showtimes = $availabilityService->filterAvailableShowtimes($allShowtimes);

        // Kiểm tra trạng thái ban của user hiện tại
        $isUserBanned = false;
        $banInfo = null;
        if (Auth::check()) {
            $bookingAttemptService = new BookingAttemptService();
            
            // Không auto-cancel nếu đang ở bước thanh toán (session/payment flag)
            if (!session()->has('is_checkout') || !session('is_checkout')) {
                $cancelledCount = $bookingAttemptService->cancelAllActiveAttempts(Auth::id());
                if ($cancelledCount > 0) {
                    Log::info("Auto-cancelled {$cancelledCount} reserved attempts for user " . Auth::id() . " when accessing movie detail page");
                }
            }
            
            $isUserBanned = $bookingAttemptService->isUserBanned(Auth::id());
            if ($isUserBanned) {
                $banInfo = $bookingAttemptService->getUserBanInfo(Auth::id());
            }
        }

        return view('client.detailmovie', compact(
            'movie',
            'showtimes',
            'rooms',
            'dates',
            'selectedDate',
            'reviews',
            'canReview',
            'reviewMessage',
            'isUserBanned',
            'banInfo'
        ));
    }

    public function ticketBooking($id)
    {
        $movie = Movie::findOrFail($id);
        $showtimes = $this->getShowtimes($id);
        if ($showtimes->isEmpty()) {
            Log::warning("Không tìm thấy suất chiếu cho phim ID {$id}");
            return redirect()->back()->with('error', 'Không có suất chiếu nào cho phim này.');
        }

        $dates = $this->getDates($showtimes);
        $showtimesData = $this->getShowtimesData($showtimes);

        // Get all room types for filtering
        $roomTypes = RoomType::where('status', 'active')->get(['id', 'name', 'description']);

        $showtimeId = request()->input('showtime_id');
        $showtime = null;
        $room = null;
        $cinema = null;
        if ($showtimeId) {
            $showtime = Showtime::with(['room.cinema', 'seats' => function ($query) {
                $query->where('status', 'available')->orWhere('status', 'reserved');
            }])->find($showtimeId);
            if (!$showtime) {
                return redirect()->back()->with('error', 'Suất chiếu không hợp lệ.');
            }
            $room = $showtime->room;
            $cinema = $room->cinema;
        }

        $seatTypes = SeatType::all();
        $products = Product::where('is_active', true)
            ->with(['productVariants' => function ($query) {
                $query->where('is_active', true)
                    ->with(['productVariantOptions.attributeValue.attribute']);
            }])
            ->get();
        $roomIds = $showtimes->pluck('room_id')->unique()->toArray();
        $roomsData = Room::query()
            ->with(['cinema' => function ($query) {
                $query->where('status', 'active');
            }, 'roomType'])
            ->whereIn('id', $roomIds)
            ->where('status', 'active')
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'cinema' => $room->cinema ? [
                        'id' => $room->cinema->id,
                        'name' => $room->cinema->name,
                        'address' => $room->cinema->address,
                        'city_id' => $room->cinema->city_id,
                        'hotline' => $room->cinema->hotline,
                        'email' => $room->cinema->email,
                        'map_url' => $room->cinema->map_url,
                        'image_url' => $room->cinema->image_url,
                        'opening_hours' => $room->cinema->opening_hours,
                        'description' => $room->cinema->description,
                        'status' => $room->cinema->status,
                    ] : null,
                    'room_type' => $room->roomType ? $room->roomType->name : 'N/A',
                    'capacity' => $room->capacity,
                    'status' => $room->status,
                ];
            });
        $cinemas = $this->formatCinemas($roomsData);
        $user = Auth::user();
        $userPoints = $user?->points?->total_points ?? 0;
        $userRank = $user?->customerRank;
        $promotionStatus = 0;
        $discount = 0;
        $pointsUsed = 0;
        $promotionId = null;
        
        // Tính toán điểm có thể sử dụng cho đơn hàng này
        $maxDiscountPercentage = $userRank?->discount_percentage ?? 30; // mặc định 30%
        $maxUsablePoints = 0; // sẽ được tính toán bằng JavaScript dựa trên tổng đơn hàng

        $bookingData = [
    'movie_id' => $movie->id,
    'movie_title' => $movie->title,
    'showtime_id' => $showtime?->id,
    'showtime_start' => $showtime?->start_time,
    'room_name' => $room?->name,
    'cinema_name' => $cinema?->name,
    'user_id' => $user?->id,
    'user_points' => $userPoints,
    'user_rank' => $userRank?->name ?? null,
    'products' => $products,
    'promotion_status' => $promotionStatus,
    'discount' => $discount,
    'points_used' => $pointsUsed,
    'promotion_id' => $promotionId,
    'seat_types' => $seatTypes,
];

              session(['booking_data' => $bookingData]);
              

        return view('client.ticket_booking', compact(
            'movie',
            'dates',
            'showtimesData',
            'roomTypes',
            'showtimeId',
            'seatTypes',
            'products',
            'showtime',
            'roomsData',
            'cinemas',
            'userPoints',
            'userRank',
            'maxDiscountPercentage',
            'promotionStatus',
            'discount',
            'pointsUsed',
            'promotionId',
            'room',
            'cinema'
        ));
    }

    private function getShowtimes($movieId)
    {
        $showtimes = Showtime::where('movie_id', $movieId)
            ->where('status', 'scheduled')
            ->where('start_time', '>=', Carbon::now())
            ->with(['room.seats', 'room.roomType', 'showtimeSeatStates'])
            ->get();

        // Lọc ra các suất chiếu chưa đầy
        $availabilityService = new ShowtimeAvailabilityService();
        return $availabilityService->filterAvailableShowtimes($showtimes);
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
        $availabilityService = new ShowtimeAvailabilityService();
        
        $showtimesByDate = $showtimes->groupBy(function ($showtime) {
            return Carbon::parse($showtime->start_time)->format('Y-m-d');
        });

        $showtimesData = [];
        foreach ($showtimesByDate as $date => $showtimes) {
            $showtimesData[$date] = $showtimes->groupBy('room_id')->map(function ($roomShowtimes) use ($availabilityService) {
                $room = $roomShowtimes->first()->room ?? (object)['name' => 'Unknown Room'];
                return [
                    'room_name' => $room->name,
                    'room_type_id' => $room->roomType->id ?? null,
                    'room_type_name' => $room->roomType->name ?? 'Không xác định',
                    'times' => $roomShowtimes->map(function ($showtime) use ($availabilityService) {
                        $availabilityInfo = $availabilityService->getShowtimeAvailabilityInfo($showtime);
                        
                        return [
                            'id' => $showtime->id,
                            'time' => Carbon::parse($showtime->start_time)->format('h:i A'),
                            'end_time' => Carbon::parse($showtime->end_time)->format('h:i A'),
                            'base_price' => $showtime->base_price,
                            'available_seats' => $availabilityInfo['available_seats'],
                            'occupancy_rate' => $availabilityInfo['occupancy_rate'],
                            'is_nearly_full' => $availabilityInfo['is_nearly_full'],
                            'status_class' => $availabilityInfo['status_class'],
                        ];
                    })->toArray(),
                ];
            })->values()->toArray();
        }

        return $showtimesData;
    }

    public function movies(Request $request)
    {
        $query = $request->input('search');
        $genreId = $request->input('genre');

        // Base query cho tất cả phim
        $baseQuery = Movie::query()->with(['genres']);

        // Áp dụng tìm kiếm theo tên phim
        if ($query) {
            $baseQuery->where('name', 'like', '%' . $query . '%');
        }

        // Áp dụng lọc theo thể loại
        if ($genreId) {
            $baseQuery->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }

        // Phim đang chiếu: Sắp xếp theo số lượng vé bán ra
        $showingMovies = (clone $baseQuery)
            ->where('status', MovieStatus::Showing)
            ->orderBy('release_date', 'desc')
            ->take(12)
            ->get();

        // Phim mới: Sắp xếp theo created_at mới nhất
        $recentMovies = (clone $baseQuery)
            ->where('status', MovieStatus::Showing)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Phim phổ biến: Dựa trên số vé bán ra trong 30 ngày
        $popularMovies = (clone $baseQuery)
            ->where('status', MovieStatus::Showing)
            ->withCount(['showtimes as tickets_sold' => function ($q) {
                $q->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
                  ->where('bookings.created_at', '>=', now()->subDays(30));
            }])
            ->orderBy('tickets_sold', 'desc')
            ->take(8)
            ->get();

        // Phim xu hướng: Dựa trên số vé bán ra trong 7 ngày
        $trendMovies = (clone $baseQuery)
            ->where('status', MovieStatus::Showing)
            ->withCount(['showtimes as tickets_sold_week' => function ($q) {
                $q->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
                  ->where('bookings.created_at', '>=', now()->subDays(7));
            }])
            ->orderBy('tickets_sold_week', 'desc')
            ->take(8)
            ->get();

        // Lấy danh sách thể loại để hiển thị trong dropdown
        $genres = \App\Models\Genre::all();

        return view('client.movies', compact('showingMovies', 'recentMovies', 'popularMovies', 'trendMovies', 'query', 'genreId', 'genres'));
    }

    private function formatCinemas($roomsData)
    {
        return $roomsData->pluck('cinema')->unique('id')->filter()->values()->map(function ($cinema) {
            $cinema = (array) $cinema;
            return [
                'id' => $cinema['id'] ?? null,
                'name' => $cinema['name'] ?? '',
                'address' => $cinema['address'] ?? '',
                'city_id' => $cinema['city_id'] ?? null,
                'hotline' => $cinema['hotline'] ?? '',
                'email' => $cinema['email'] ?? '',
                'map_url' => $cinema['map_url'] ?? '',
                'image_url' => $cinema['image_url'] ?? '',
                'opening_hours' => $cinema['opening_hours'] ?? '',
                'description' => $cinema['description'] ?? '',
                'status' => $cinema['status'] ?? '',
            ];
        });
    }

    public function applyDiscountCode(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50',
                'order_amount' => 'required|numeric|min:0',
            ]);

            $code = $validated['code'];
            $orderAmount = (float)$validated['order_amount'];
            $now = now();
            $user = Auth::user();

            $promotion = Promotion::where('code', $code)->first();

            // 1. Kiểm tra tính hợp lệ cơ bản
            if (!$promotion || $promotion->status !== 'active' || $promotion->quantity <= 0) {
                return response()->json(['error' => 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng.'], 400);
            }

            // 2. Kiểm tra thời gian
            if (($promotion->start_date && $now->isBefore($promotion->start_date)) || ($promotion->end_date && $now->isAfter($promotion->end_date))) {
                return response()->json(['error' => 'Mã giảm giá đã hết hạn hoặc chưa có hiệu lực.'], 400);
            }

            // 3. Kiểm tra giá trị đơn hàng tối thiểu
            if ($promotion->min_booking_value && $orderAmount < $promotion->min_booking_value) {
                return response()->json(['error' => 'Đơn hàng tối thiểu ' . number_format((float)$promotion->min_booking_value) . '₫ để sử dụng mã này.'], 400);
            }

            // 4. Kiểm tra mã đã được sử dụng bởi user hiện tại
            if ($user) {
                $hasUsedPromotion = Booking::where('user_id', $user->id)
                    ->where('promotion_id', $promotion->id)
                    ->where('status', 'confirmed')
                    ->exists();
                
                if ($hasUsedPromotion) {
                    return response()->json(['error' => 'Mã giảm giá này đã được sử dụng trong đơn hàng trước đó.'], 400);
                }
            }

            // 5. Kiểm tra quyền sử dụng mã theo rank
            if ($promotion->rank_id && (!$user || $user->customer_rank_id !== $promotion->rank_id)) {
                $rankName = $promotion->rank ? $promotion->rank->name : 'Hạng đặc biệt';
                return response()->json(['error' => "Mã giảm giá này chỉ dành cho khách hàng hạng {$rankName}."], 400);
            }

            // 6. Tính toán giảm giá
            $discount = 0;
            $discountType = is_string($promotion->discount_type) ? $promotion->discount_type : $promotion->discount_type->value;
            
            if ($discountType === 'percentage') {
                $discount = $orderAmount * ((float)$promotion->discount_value / 100);
                if ($promotion->max_discount_amount && $discount > (float)$promotion->max_discount_amount) {
                    $discount = (float)$promotion->max_discount_amount;
                }
            } else {
                $discount = (float)$promotion->discount_value;
            }

            // Đảm bảo discount không vượt quá order amount
            $discount = min($discount, $orderAmount);

            // 7. Giảm số lượng mã
            $promotion->decrement('quantity');

            Log::info('[DEBUG] Áp dụng mã giảm giá thành công:', [
                'user_id' => $user?->id,
                'promotion_code' => $code,
                'discount' => $discount,
                'order_amount' => $orderAmount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công!',
                'promotion_id' => $promotion->id,
                'promotion_code' => $code,
                'promotion_name' => $promotion->name,
                'discount' => $discount,
                'final_amount' => $orderAmount - $discount,
                'discount_type' => $discountType,
                'discount_value' => (float)$promotion->discount_value,
                'display_text' => $this->getDiscountDisplayText($promotion),
                'promotion_rank' => $promotion->rank?->name ?? 'Chung',
                'max_discount' => $promotion->max_discount_amount ? (float)$promotion->max_discount_amount : null
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()['code'][0] ?? 'Dữ liệu không hợp lệ.'], 422);
        } catch (\Exception $e) {
            Log::error('[DEBUG] Exception in applyDiscountCode:', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);
            return response()->json(['error' => 'Đã có lỗi xảy ra. Vui lòng thử lại.'], 500);
        }
    }

    public function applyPoints(Request $request)
    {
        Log::info('[DEBUG] applyPoints input:', $request->all());

        $request->validate([
            'points' => 'required|integer|min:1',
            'order_amount' => 'required|numeric|min:0',
            'current_discount' => 'required|numeric|min:0', // Mã giảm giá hiện tại
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Bạn cần đăng nhập để đổi điểm.'], 401);
        }

        $pointsToUse = $request->points;
        $orderAmount = $request->order_amount;
        $currentDiscount = $request->current_discount;

        if (!$user->points || $pointsToUse > $user->points->total_points) {
            Log::warning('[DEBUG] Không đủ điểm:', [
                'user_id' => $user->id,
                'pointsToUse' => $pointsToUse,
                'available_points' => $user->points ? $user->points->total_points : 0
            ]);
            return response()->json(['error' => 'Số điểm không đủ để đổi.'], 400);
        }

        // Tính tiền giảm từ điểm: 1 điểm = 1,000₫
        $pointDiscount = $pointsToUse * 1000;

        // Lấy phần trăm giảm giá tối đa từ hạng của user, mặc định 30%
        $userRank = $user->customerRank;
        $maxDiscountPercentage = $userRank ? $userRank->discount_percentage : 30;

        // Kiểm tra tổng giảm giá không vượt quá phần trăm tối đa của đơn hàng
        $maxTotalDiscount = $orderAmount * ($maxDiscountPercentage / 100);
        $totalDiscount = $currentDiscount + $pointDiscount;

        if ($totalDiscount > $maxTotalDiscount) {
            $maxPointsAllowed = floor(($maxTotalDiscount - $currentDiscount) / 1000);
            return response()->json([
                'error' => "Tổng giảm giá không được vượt quá {$maxDiscountPercentage}% đơn hàng. Bạn chỉ có thể dùng tối đa {$maxPointsAllowed} điểm.",
                'max_points_allowed' => $maxPointsAllowed,
                'max_total_discount' => $maxTotalDiscount,
                'current_discount' => $currentDiscount,
                'max_discount_percentage' => $maxDiscountPercentage
            ], 400);
        }

        if ($pointDiscount > $orderAmount) {
            return response()->json(['error' => 'Số tiền giảm từ điểm vượt quá tổng đơn hàng.'], 400);
        }

        // KHÔNG trừ điểm và KHÔNG tạo PointHistory ở đây. Chỉ xác nhận số điểm có thể dùng.
        Log::info('[DEBUG] Xác nhận số điểm có thể dùng, sẽ trừ sau khi thanh toán thành công:', [
            'user_id' => $user->id,
            'pointsToUse' => $pointsToUse,
            'pointDiscount' => $pointDiscount,
            'totalDiscount' => $totalDiscount
        ]);

        return response()->json([
            'success' => true,
            'message' => "Bạn có thể dùng {$pointsToUse} điểm để giảm {$pointDiscount} VNĐ. Điểm sẽ chỉ bị trừ khi thanh toán thành công!",
            'discount' => $pointDiscount,
            'points_used' => $pointsToUse,
            'total_discount' => $totalDiscount,
            'max_total_discount' => $maxTotalDiscount
        ]);
    }

    public function getUserRank()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Bạn cần đăng nhập để xem thông tin xếp hạng.'], 401);
        }

        $rank = $user->customerRank;
        if (!$rank) {
            return response()->json(['error' => 'Bạn chưa có xếp hạng.'], 404);
        }

        return response()->json([
            'success' => true,
            'rank_id' => $rank->id,
            'rank_name' => $rank->name,
            'required_points' => $rank->required_points,
            'discount_rate' => $rank->discount_rate,
        ]);
    }

    public function getUserPoints()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Bạn cần đăng nhập để xem thông tin điểm.'], 401);
        }

        $points = $user->points;
        if (!$points) {
            return response()->json(['error' => 'Bạn chưa có điểm nào.'], 404);
        }

        return response()->json([
            'success' => true,
            'total_points' => $points->total_points,
            'expiry_date' => $points->points_expiry_date,
        ]);
    }

    public function getUserPointHistory()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
        }

        $pointHistory = PointHistory::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $pointHistory
        ]);
    }

    public function getAvailablePromotions()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
            }

            $now = now();
            $userRankId = $user->customer_rank_id;

            Log::info('[DEBUG] getAvailablePromotions - User Info:', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'customer_rank_id' => $userRankId,
                'rank_name' => $user->customerRank?->name ?? 'NULL',
                'now' => $now->format('Y-m-d H:i:s')
            ]);

            // Lấy mã giảm giá cho rank của user
            $userRankPromotions = $this->getUserRankPromotions($userRankId, $now);

            // Lấy mã giảm giá chung
            $generalPromotions = $this->getGeneralPromotions($now);

            // Lấy mã giảm giá của các rank khác
            $otherRankPromotions = $this->getOtherRankPromotions($userRankId, $now);

            // Phân loại và format data
            $categorizedPromotions = [
                'user_rank' => $userRankPromotions->map(fn($p) => $this->formatPromotion($p, 'user_rank'))->toArray(),
                'general' => $generalPromotions->map(fn($p) => $this->formatPromotion($p, 'general'))->toArray(),
                'higher_ranks' => $otherRankPromotions->map(fn($p) => $this->formatPromotion($p, 'higher_rank'))->toArray()
            ];

            // Debug total count
            $totalPromotions = count($categorizedPromotions['user_rank']) + count($categorizedPromotions['general']) + count($categorizedPromotions['higher_ranks']);
            
            Log::info('[DEBUG] Categorized promotions result:', [
                'user_rank_count' => count($categorizedPromotions['user_rank']),
                'general_count' => count($categorizedPromotions['general']),
                'higher_ranks_count' => count($categorizedPromotions['higher_ranks']),
                'total_count' => $totalPromotions,
                'user_rank_data' => $categorizedPromotions['user_rank'],
                'general_data' => $categorizedPromotions['general'],
                'higher_ranks_data' => $categorizedPromotions['higher_ranks']
            ]);

            return response()->json([
                'success' => true,
                'user_rank' => $user->customerRank?->name ?? 'Khách thường',
                'user_rank_id' => $userRankId,
                'data' => $categorizedPromotions
            ]);
        } catch (\Exception $e) {
            Log::error('[DEBUG] Exception in getAvailablePromotions:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi lấy danh sách mã giảm giá. Vui lòng thử lại.',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function getUserRankPromotions($userRankId, $now)
    {
        if (!$userRankId) {
            Log::info('[DEBUG] getUserRankPromotions: No user rank ID');
            return collect();
        }

        $user = Auth::user();
        
        Log::info('[DEBUG] getUserRankPromotions query params:', [
            'userRankId' => $userRankId,
            'now' => $now,
            'now_formatted' => $now->format('Y-m-d H:i:s')
        ]);

        $promotions = Promotion::with('rank')
            ->where('status', 'active')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('quantity', '>', 0)
            ->where('rank_id', $userRankId)
            ->whereNotExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('bookings')
                    ->whereColumn('bookings.promotion_id', 'promotions.id')
                    ->where('bookings.user_id', $user->id)
                    ->where('bookings.status', 'confirmed');
            })
            ->orderBy('discount_value', 'desc')
            ->get();

        // Debug: kiểm tra tất cả promotions cho rank này không quan tâm thời gian
        $allRankPromotions = Promotion::with('rank')
            ->where('status', 'active')
            ->where('rank_id', $userRankId)
            ->get();

        Log::info('[DEBUG] All promotions for this rank (ignoring time):', [
            'userRankId' => $userRankId,
            'count' => $allRankPromotions->count(),
            'promotions' => $allRankPromotions->map(fn($p) => [
                'id' => $p->id,
                'code' => $p->code,
                'name' => $p->name,
                'start_date' => $p->start_date,
                'end_date' => $p->end_date,
                'status' => $p->status,
                'quantity' => $p->quantity,
                'rank_name' => $p->rank?->name
            ])
        ]);

        Log::info('[DEBUG] getUserRankPromotions result:', [
            'userRankId' => $userRankId,
            'count' => $promotions->count(),
            'promotions' => $promotions->map(fn($p) => [
                'id' => $p->id,
                'code' => $p->code,
                'name' => $p->name,
                'rank_name' => $p->rank?->name
            ])
        ]);

        return $promotions;
    }

    private function getGeneralPromotions($now)
    {
        $user = Auth::user();
        
        $promotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('quantity', '>', 0)
            ->whereNull('rank_id')
            ->whereNotExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('bookings')
                    ->whereColumn('bookings.promotion_id', 'promotions.id')
                    ->where('bookings.user_id', $user->id)
                    ->where('bookings.status', 'confirmed');
            })
            ->orderBy('discount_value', 'desc')
            ->get();

        Log::info('[DEBUG] getGeneralPromotions result:', [
            'count' => $promotions->count(),
            'promotions' => $promotions->map(fn($p) => [
                'id' => $p->id,
                'code' => $p->code,
                'name' => $p->name
            ])
        ]);

        return $promotions;
    }

    private function getOtherRankPromotions($userRankId, $now)
    {
        Log::info('[DEBUG] getOtherRankPromotions called with params:', [
            'userRankId' => $userRankId,
            'now' => $now instanceof \DateTime ? $now->format('Y-m-d H:i:s') : $now
        ]);

        if (!$userRankId) {
            Log::info('[DEBUG] getOtherRankPromotions: No user rank ID, returning empty collection.');
            return collect();
        }

        // Lấy thông tin rank của user hiện tại
        $userRank = CustomerRank::find($userRankId);
        if (!$userRank) {
            Log::info('[DEBUG] getOtherRankPromotions: User rank not found, returning empty collection.');
            return collect();
        }

        // Nếu rank của user không có min_points_required, không thể so sánh
        if (is_null($userRank->min_points_required)) {
            Log::info('[DEBUG] getOtherRankPromotions: User rank has null min_points_required.', [
                'userRankName' => $userRank->name
            ]);
            return collect();
        }

        Log::info('[DEBUG] User rank info for getOtherRankPromotions:', [
            'userRankName' => $userRank->name,
            'userMinPoints' => $userRank->min_points_required
        ]);

        // Log all higher ranks first to check if we're considering the right ranks
        $higherRanks = CustomerRank::where('min_points_required', '>', $userRank->min_points_required)
            ->get();

        Log::info('[DEBUG] Higher ranks available in database:', [
            'count' => $higherRanks->count(),
            'ranks' => $higherRanks->map(fn($r) => ['id' => $r->id, 'name' => $r->name, 'min_points' => $r->min_points_required])
        ]);

        // Check ALL promotions that belong to higher ranks regardless of other conditions
        $allHigherRankPromotions = Promotion::with('rank')
            ->whereHas('rank', function ($query) use ($userRank) {
                $query->where('min_points_required', '>', $userRank->min_points_required);
            })
            ->get();

        Log::info('[DEBUG] ALL promotions for higher ranks (ignoring status/dates):', [
            'count' => $allHigherRankPromotions->count(),
            'promotions' => $allHigherRankPromotions->map(fn($p) => [
                'id' => $p->id,
                'code' => $p->code,
                'name' => $p->name,
                'rank_name' => $p->rank?->name,
                'status' => $p->status,
                'start_date' => $p->start_date,
                'end_date' => $p->end_date,
                'quantity' => $p->quantity
            ])
        ]);

        // Chỉ lấy các promotion cho rank cao hơn rank hiện tại của user
        // dựa trên min_points_required
        $user = Auth::user();
        
        $promotions = Promotion::with('rank')
            ->where('status', 'active')
            ->where(function ($query) use ($now) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            })
            ->where('quantity', '>', 0)
            ->whereHas('rank', function ($query) use ($userRank) {
                // Chỉ lấy promotion của các rank có min_points_required lớn hơn rank hiện tại
                $query->where('min_points_required', '>', $userRank->min_points_required);
            })
            ->whereNotExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('bookings')
                    ->whereColumn('bookings.promotion_id', 'promotions.id')
                    ->where('bookings.user_id', $user->id)
                    ->where('bookings.status', 'confirmed');
            })
            ->get();

        Log::info('[DEBUG] getOtherRankPromotions result:', [
            'count' => $promotions->count(),
            'promotions' => $promotions->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'rank_name' => $p->rank?->name])
        ]);

        return $promotions;
    }

    private function formatPromotion($promotion, $category)
    {
        $discountAmount = $this->calculateDiscountAmount($promotion);

        return [
            'id' => $promotion->id,
            'code' => $promotion->code,
            'name' => $promotion->name,
            'description' => $promotion->description,
            'discount_type' => $promotion->discount_type,
            'discount_value' => (float)$promotion->discount_value,
            'discount_amount' => $discountAmount,
            'max_discount_amount' => $promotion->max_discount_amount ? (float)$promotion->max_discount_amount : null,
            'min_booking_value' => $promotion->min_booking_value ? (float)$promotion->min_booking_value : null,
            'rank' => $promotion->rank?->name,
            'rank_id' => $promotion->rank_id,
            'category' => $category,
            'quantity' => $promotion->quantity,
            'display_text' => $this->getDiscountDisplayText($promotion)
        ];
    }

    private function calculateDiscountAmount($promotion)
    {
        // Hàm này chỉ dùng để hiển thị ước tính, không phải tính toán thực tế
        // Tính toán thực tế sẽ được thực hiện trong applyDiscountCode và applyDiscountCodeAutomatically

        if ($promotion->discount_type === 'fixed') {
            return (float)$promotion->discount_value;
        }

        if ($promotion->discount_type === 'percentage') {
            // Trả về giá trị phần trăm để hiển thị, không phải số tiền thực tế
            return (float)$promotion->discount_value;
        }

        return 0;
    }

    private function getDiscountDisplayText($promotion)
    {
        if ($promotion->discount_type instanceof PromotionDiscountType && $promotion->discount_type->value === 'percentage') {
            return (float)$promotion->discount_value . '%';
        }

        if (is_string($promotion->discount_type) && $promotion->discount_type === 'percentage') {
            return (float)$promotion->discount_value . '%';
        }

        return number_format((float)$promotion->discount_value) . '₫';
    }
       public function applyDiscountCodeAutomatically(Request $request)
    {
        try {
            Log::info('[DEBUG] applyDiscountCodeAutomatically input:', $request->all());

            $request->validate([
                'order_amount' => 'required|numeric|min:0',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để sử dụng mã giảm giá'], 401);
            }

            $orderAmount = $request->order_amount;
            $now = now();

            // Kiểm tra đơn hàng tối thiểu 200,000đ
            if ($orderAmount < 200000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng tối thiểu 200,000 ₫ để sử dụng mã giảm giá.',
                    'minimum_required' => 200000,
                    'current_amount' => $orderAmount
                ]);
            }

            Log::info('[DEBUG] User info for auto promotion:', [
                'user_id' => $user->id,
                'user_rank_id' => $user->customer_rank_id,
                'user_rank_name' => $user->customerRank?->name ?? 'Khách thường',
                'order_amount' => $orderAmount
            ]);

            // Tìm mã giảm giá tốt nhất theo thứ tự ưu tiên:
            // 1. Mã giảm giá dành riêng cho rank của user
            // 2. Mã giảm giá chung (không giới hạn rank)
            $promotionQuery = Promotion::where('status', 'active')
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->where('quantity', '>', 0)
                ->where(function ($query) use ($orderAmount) {
                    $query->whereNull('min_booking_value')
                        ->orWhere('min_booking_value', '<=', $orderAmount);
                })
                ->where(function ($query) use ($orderAmount) {
                    // Lọc ra mã Fixed có giá trị lớn hơn order amount
                    $query->where('discount_type', 'percentage')
                        ->orWhere(function ($subQuery) use ($orderAmount) {
                            $subQuery->where('discount_type', 'fixed')
                                ->where('discount_value', '<=', $orderAmount);
                        });
                });

            $bestPromotion = null;

            // BƯỚC 1: Ưu tiên tìm mã giảm giá dành riêng cho rank của user
            if ($user->customer_rank_id) {
                $rankPromotions = (clone $promotionQuery)
                    ->where('rank_id', $user->customer_rank_id)
                    ->whereNotExists(function ($query) use ($user) {
                        $query->select(DB::raw(1))
                            ->from('bookings')
                            ->whereColumn('bookings.promotion_id', 'promotions.id')
                            ->where('bookings.user_id', $user->id)
                            ->where('bookings.status', 'confirmed');
                    })
                    ->with('rank')
                    ->orderByRaw('
                    CASE 
                        WHEN discount_type = "percentage" THEN 
                            LEAST(
                                ? * (discount_value / 100), 
                                COALESCE(max_discount_amount, ?)
                            )
                        ELSE discount_value 
                    END DESC
                ', [$orderAmount, $orderAmount])
                    ->get();

                Log::info('[DEBUG] Found rank-specific promotions:', [
                    'user_rank_id' => $user->customer_rank_id,
                    'user_rank_name' => $user->customerRank?->name,
                    'promotions_count' => $rankPromotions->count(),
                    'promotions' => $rankPromotions->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'code' => $p->code,
                            'name' => $p->name,
                            'discount_type' => $p->discount_type,
                            'discount_value' => $p->discount_value,
                            'rank_name' => $p->rank?->name
                        ];
                    })
                ]);

                $bestPromotion = $rankPromotions->first();
            }

            // BƯỚC 2: Nếu không có mã giảm giá theo rank, tìm mã giảm giá chung
            if (!$bestPromotion) {
                $generalPromotions = $promotionQuery
                    ->whereNull('rank_id')
                    ->whereNotExists(function ($query) use ($user) {
                        $query->select(DB::raw(1))
                            ->from('bookings')
                            ->whereColumn('bookings.promotion_id', 'promotions.id')
                            ->where('bookings.user_id', $user->id)
                            ->where('bookings.status', 'confirmed');
                    })
                    ->orderByRaw('
                    CASE 
                        WHEN discount_type = "percentage" THEN 
                            LEAST(
                                ? * (discount_value / 100), 
                                COALESCE(max_discount_amount, ?)
                            )
                        ELSE discount_value 
                    END DESC
                ', [$orderAmount, $orderAmount])
                    ->get();

                Log::info('[DEBUG] Found general promotions:', [
                    'promotions_count' => $generalPromotions->count(),
                    'promotions' => $generalPromotions->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'code' => $p->code,
                            'name' => $p->name,
                            'discount_type' => $p->discount_type,
                            'discount_value' => $p->discount_value
                        ];
                    })
                ]);

                $bestPromotion = $generalPromotions->first();
            }

            if (!$bestPromotion) {
                Log::warning('[DEBUG] Không tìm thấy mã giảm giá phù hợp:', [
                    'user_id' => $user->id,
                    'user_rank_id' => $user->customer_rank_id,
                    'user_rank_name' => $user->customerRank?->name ?? 'Khách thường',
                    'order_amount' => $orderAmount
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Không có mã giảm giá phù hợp cho hạng ' . ($user->customerRank?->name ?? 'Khách thường')
                ]);
            }

            // Kiểm tra điều kiện giá trị mã giảm giá dựa trên discount_type
            if ($bestPromotion->discount_type === 'fixed' && (float)$bestPromotion->discount_value > $orderAmount) {
                // Chỉ áp dụng cho mã cố định: nếu giá trị mã > đơn hàng thì không cho phép
                Log::warning('[DEBUG] Auto promotion fixed value exceeds order amount:', [
                    'promotion_code' => $bestPromotion->code,
                    'promotion_value' => (float)$bestPromotion->discount_value,
                    'order_amount' => $orderAmount
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Giá trị mã giảm giá (' . number_format((float)$bestPromotion->discount_value) . '₫) lớn hơn tổng đơn hàng (' . number_format($orderAmount) . '₫).',
                    'promotion_value' => (float)$bestPromotion->discount_value,
                    'order_amount' => $orderAmount
                ]);
            }

            // Đối với mã phần trăm, kiểm tra nếu discount_value > 100%
            if ($bestPromotion->discount_type === 'percentage' && (float)$bestPromotion->discount_value > 100) {
                Log::warning('[DEBUG] Auto promotion percentage exceeds 100%:', [
                    'promotion_code' => $bestPromotion->code,
                    'promotion_percentage' => (float)$bestPromotion->discount_value
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá phần trăm không hợp lệ (>100%).',
                    'promotion_percentage' => (float)$bestPromotion->discount_value
                ]);
            }

            // Tính toán giảm giá theo đúng logic database
            $discount = 0;
            if ($bestPromotion->discount_type === 'percentage') {
                // Mã phần trăm: tính % của order amount
                $discount = $orderAmount * ((float)$bestPromotion->discount_value / 100);

                // Áp dụng max_discount_amount nếu có
                if ($bestPromotion->max_discount_amount && $discount > (float)$bestPromotion->max_discount_amount) {
                    $discount = (float)$bestPromotion->max_discount_amount;
                }
            } else {
                // Mã cố định: lấy giá trị discount_value
                $discount = (float)$bestPromotion->discount_value;
            }

            // Đảm bảo discount không vượt quá order amount
            $discount = min($discount, $orderAmount);

            // Xác định loại mã giảm giá và thông tin rank
            $promotionType = $bestPromotion->rank_id ? 'Mã giảm giá theo hạng' : 'Mã giảm giá chung';
            $userRankName = $user->customerRank?->name ?? 'Khách thường';
            $promotionRankName = $bestPromotion->rank?->name ?? 'Chung';

            // Giảm số lượng mã
            $bestPromotion->decrement('quantity');

            Log::info('[DEBUG] Áp dụng mã giảm giá tự động thành công:', [
                'user_id' => $user->id,
                'user_rank' => $userRankName,
                'promotion_id' => $bestPromotion->id,
                'promotion_code' => $bestPromotion->code,
                'promotion_name' => $bestPromotion->name,
                'promotion_rank' => $promotionRankName,
                'promotion_type' => $promotionType,
                'discount' => $discount,
                'order_amount' => $orderAmount,
                'final_amount' => $orderAmount - $discount
            ]);

            return response()->json([
                'success' => true,
                'message' => $bestPromotion->rank_id
                    ? "Áp dụng mã giảm giá hạng {$promotionRankName} thành công!"
                    : 'Áp dụng mã giảm giá chung thành công!',
                'promotion_id' => $bestPromotion->id,
                'promotion_code' => $bestPromotion->code,
                'promotion_name' => $bestPromotion->name,
                'promotion_type' => $promotionType,
                'promotion_rank' => $promotionRankName,
                'user_rank' => $userRankName,
                'discount' => $discount,
                'final_amount' => $orderAmount - $discount,
                'discount_type' => $bestPromotion->discount_type,
                'discount_value' => (float)$bestPromotion->discount_value,
                'display_text' => $this->getDiscountDisplayText($bestPromotion),
                'max_discount' => $bestPromotion->max_discount_amount ? (float)$bestPromotion->max_discount_amount : null
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('[DEBUG] Validation error in applyDiscountCodeAutomatically:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('[DEBUG] Exception in applyDiscountCodeAutomatically:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi tự động áp dụng mã giảm giá. Vui lòng thử lại.',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}