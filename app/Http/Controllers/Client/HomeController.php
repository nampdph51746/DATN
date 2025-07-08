<?php

namespace App\Http\Controllers\Client;

use App\Models\Room;
use App\Models\Movie;
use App\Models\Product;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Enums\MovieStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Promotion;
use App\Models\User;
use App\Enums\PointReasonType;
use App\Enums\PromotionDiscountType;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\CustomerRank;
class HomeController extends Controller
{
    public function index()
    {
        $query = request('query');

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

        Log::info('Showing Movies Count: ' . $showingMovies->count());
        Log::info('Upcoming Movies Count: ' . $upcomingMovies->count());
        Log::info('Upcoming Movies Data: ' . $upcomingMovies->toJson());

        return view('client.home', compact('showingMovies', 'upcomingMovies', 'query'));
    }

    public function show(Request $request, $id)
    {
        $movie = Movie::with(['country', 'ageLimit'])->findOrFail($id);
        $rooms = Room::all();
        $dates = collect(range(0, 14))->map(fn($i) => now()->addDays($i));
        $selectedDate = $request->input('date') ?? now()->format('Y-m-d');
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
        if ($showtimes === null || $showtimes->isEmpty()) {
            return redirect()->back()->with('error', 'Không có suất chiếu nào cho phim này.');
        }

        $dates = $this->getDates($showtimes);
        $showtimesData = $this->getShowtimesData($showtimes);

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

        return view('client.ticket_booking', compact(
            'movie',
            'dates',
            'showtimesData',
            'showtimeId',
            'seatTypes',
            'products',
            'showtime',
            'roomsData',
            'cinemas',
            'userPoints',
            'userRank',
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
        try {
            $showtimes = Showtime::where('movie_id', $movieId)
                ->where('status', 'scheduled')
                ->where('start_time', '>=', Carbon::now())
                ->with('room')
                ->get();

            Log::info('Filtered Showtimes Query for movie ID ' . $movieId . ': ' . Showtime::where('movie_id', $movieId)->toSql());
            Log::info('Filtered Showtimes for movie ID ' . $movieId . ': ' . $showtimes->toJson());

            return $showtimes;
        } catch (\Exception $e) {
            Log::error('Error fetching showtimes for movie ID ' . $movieId . ': ' . $e->getMessage());
            return collect(); // Trả về collection rỗng thay vì null
        }
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
                $room = $roomShowtimes->first()->room ?? (object)['name' => 'Unknown Room'];
                return [
                    'room_name' => $room->name,
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

        Log::info('Showing Movies: ' . $showingMovies->toJson());
        Log::info('Recent Movies: ' . $recentMovies->toJson());
        Log::info('Popular Movies: ' . $popularMovies->toJson());
        Log::info('Trend Movies: ' . $trendMovies->toJson());

        return view('client.movies', compact('showingMovies', 'recentMovies', 'popularMovies', 'trendMovies', 'query'));
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
                'code' => 'required|string|exists:promotions,code',
                'order_amount' => 'required|numeric|min:0',
            ]);

            $code = $validated['code'];
            $orderAmount = (float)$validated['order_amount'];
            $now = now();
            $user = Auth::user();

            $promotion = Promotion::where('code', $code)->first();

            // 1. Kiểm tra tính hợp lệ cơ bản
            if (!$promotion || $promotion->status !== 'active' || $promotion->quantity <= 0) {
                return response()->json(['error' => 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng. '], 404);
            }

            // 2. Kiểm tra thời gian
            if (($promotion->start_date && $now->isBefore($promotion->start_date)) || ($promotion->end_date && $now->isAfter($promotion->end_date))) {
                return response()->json(['error' => 'Mã giảm giá đã hết hạn hoặc chưa có hiệu lực. '], 400);
            }

            // 3. Kiểm tra giá trị đơn hàng tối thiểu
            if ($promotion->min_booking_value && $orderAmount < $promotion->min_booking_value) {
                return response()->json(['error' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã này. '], 400);
            }

            // 4. Kiểm tra rank của user
            if ($promotion->rank_id && (!$user || $user->customer_rank_id != $promotion->rank_id)) {
                return response()->json(['error' => 'Mã giảm giá này không áp dụng cho hạng của bạn. '], 403);
            }

            // 5. Kiểm tra giá trị mã Fixed không được lớn hơn tổng đơn hàng
            if ($promotion->discount_type->value === 'fixed' && (float)$promotion->discount_value > $orderAmount) {
                return response()->json([
                    'error' => 'Giá trị mã giảm giá (' . number_format((float)$promotion->discount_value) . '₫) lớn hơn tổng đơn hàng (' . number_format($orderAmount) . '₫).'
                ], 400);
            }

            // Tính toán số tiền giảm giá
            $discountAmount = 0;
            if ($promotion->discount_type->value === 'percentage') {
                // Mã phần trăm: tính % của order amount
                $discountAmount = ($orderAmount * (float)$promotion->discount_value) / 100;
                // Áp dụng max_discount_amount nếu có
                if ($promotion->max_discount_amount && $discountAmount > (float)$promotion->max_discount_amount) {
                    $discountAmount = (float)$promotion->max_discount_amount;
                }
            } else { // fixed
                // Mã cố định: lấy trực tiếp giá trị discount_value (đã kiểm tra điều kiện ở trên)
                $discountAmount = (float)$promotion->discount_value;
            }

            // Đảm bảo giảm giá không lớn hơn tổng đơn hàng (double check)
            $discountAmount = min($discountAmount, $orderAmount);

            Log::info('[DEBUG] Áp dụng mã giảm giá thủ công thành công:', [
                'code' => $code,
                'order_amount' => $orderAmount,
                'discount_amount' => $discountAmount,
                'promotion_rank' => $promotion->rank?->name ?? 'Chung'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công!',
                'discount' => $discountAmount,
                'discount_type' => $promotion->discount_type->value ?? $promotion->discount_type,
                'discount_value' => (float)$promotion->discount_value,
                'display_text' => $this->getDiscountDisplayText($promotion),
                'promotion_id' => $promotion->id,
                'promotion_code' => $promotion->code,
                'promotion_name' => $promotion->name,
                'promotion_rank' => $promotion->rank?->name ?? 'Chung',
                'max_discount' => $promotion->max_discount_amount ? (float)$promotion->max_discount_amount : null
            ]);
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()['code'][0] ?? 'Dữ liệu không hợp lệ. '], 422);
        } catch (\Exception $e) {
            Log::error('[ERROR] ApplyDiscountCode: ' . $e->getMessage());
            return response()->json([
                'error' => 'Đã có lỗi xảy ra, vui lòng thử lại. ',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
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

        // Kiểm tra tổng giảm giá không vượt quá 30% tổng đơn hàng
        $maxTotalDiscount = $orderAmount * 0.3;
        $totalDiscount = $currentDiscount + $pointDiscount;

        if ($totalDiscount > $maxTotalDiscount) {
            $maxPointsAllowed = floor(($maxTotalDiscount - $currentDiscount) / 1000);
            return response()->json([
                'error' => "Tổng giảm giá không được vượt quá 30% đơn hàng. Bạn chỉ có thể dùng tối đa {$maxPointsAllowed} điểm.",
                'max_points_allowed' => $maxPointsAllowed,
                'max_total_discount' => $maxTotalDiscount,
                'current_discount' => $currentDiscount
            ], 400);
        }

        if ($pointDiscount > $orderAmount) {
            return response()->json(['error' => 'Số tiền giảm từ điểm vượt quá tổng đơn hàng.'], 400);
        }

        $user->points->decrement('total_points', $pointsToUse);

        PointHistory::create([
            'user_id' => $user->id,
            'booking_id' => null,
            'points_change' => -$pointsToUse,
            'reason_type' => PointReasonType::Spent,
            'description' => "Đổi {$pointsToUse} điểm thành {$pointDiscount} VNĐ (1 điểm = 1,000₫)",
        ]);

        Log::info('[DEBUG] Đổi điểm thành công:', [
            'user_id' => $user->id,
            'pointsToUse' => $pointsToUse,
            'pointDiscount' => $pointDiscount,
            'totalDiscount' => $totalDiscount
        ]);

        return response()->json([
            'success' => true,
            'message' => "Đổi {$pointsToUse} điểm thành công!",
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

            Log::info('[DEBUG] Categorized promotions result:', [
                'user_rank_count' => count($categorizedPromotions['user_rank']),
                'general_count' => count($categorizedPromotions['general']),
                'higher_ranks_count' => count($categorizedPromotions['higher_ranks']),
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
        $promotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('quantity', '>', 0)
            ->whereNull('rank_id')
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
            ->where(function($query) use ($orderAmount) {
                $query->whereNull('min_booking_value')
                      ->orWhere('min_booking_value', '<=', $orderAmount);
            })
            ->where(function($query) use ($orderAmount) {
                // Lọc ra mã Fixed có giá trị lớn hơn order amount
                $query->where('discount_type', 'percentage')
                      ->orWhere(function($subQuery) use ($orderAmount) {
                          $subQuery->where('discount_type', 'fixed')
                                   ->where('discount_value', '<=', $orderAmount);
                      });
            });

        $bestPromotion = null;
        
        // BƯỚC 1: Ưu tiên tìm mã giảm giá dành riêng cho rank của user
        if ($user->customer_rank_id) {
            $rankPromotions = (clone $promotionQuery)
                ->where('rank_id', $user->customer_rank_id)
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
                'promotions' => $rankPromotions->map(function($p) {
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
                'promotions' => $generalPromotions->map(function($p) {
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
