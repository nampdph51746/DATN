<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    private function getDateCondition($type, $request)
    {
        $condition = '';
        switch ($type) {
            case 'year':
                $year = (int) $request->get('year', Carbon::now()->year);
                $condition = ' AND YEAR(t.created_at) = ' . $year;
                break;
            case 'month':
                $year = (int) $request->get('year', Carbon::now()->year);
                $month = (int) $request->get('month', Carbon::now()->month);
                $condition = ' AND YEAR(t.created_at) = ' . $year . ' AND MONTH(t.created_at) = ' . $month;
                break;
            case 'day':
                $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::today();
                $condition = ' AND DATE(t.created_at) = "' . $date->format('Y-m-d') . '"';
                break;
        }
        return $condition;
    }
    public function index(Request $request)
    {
        $type = $request->get('type', 'day'); // day / month / year
        $today = Carbon::today();

        // Khởi tạo biến cho các biểu đồ
        $revenueData = [];
        $revenueDates = [];
        $bookingData = [];
        $topProducts = [];
        $revenueDistribution = [];

        // Xử lý dữ liệu theo loại thời gian
        if ($type === 'day') {
            // Lấy ngày từ request, mặc định là hôm nay
            $today = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::today();

            // Thống kê doanh thu và lượt đặt vé theo giờ
            for ($h = 0; $h < 24; $h++) {
                $revenueDates[] = sprintf('%02d:00', $h);
                $revenueData[] = Payment::where('status', 'completed')
                    ->whereDate('paid_at', $today)
                    ->whereRaw('HOUR(paid_at) = ?', [$h])
                    ->sum('amount');

                $bookingData[$h] = Booking::whereDate('created_at', $today)
                    ->whereRaw('HOUR(created_at) = ?', [$h])
                    ->count();
            }

            // Top phim bán chạy trong ngày
            $topProducts = Movie::with(['genres'])
                ->select([
                    'movies.id',
                    'movies.name',
                    'movies.poster_url',
                    'movies.duration_minutes',
                    'movies.release_date',
                    'movies.description',
                    'movies.status',
                    'movies.average_rating'
                ])
                ->whereIn('movies.status', ['showing', 'ended'])
                ->addSelect([
                    DB::raw('(SELECT COUNT(t.id) 
                            FROM tickets t 
                            JOIN bookings b ON t.booking_id = b.id 
                            JOIN showtimes s ON t.showtime_id = s.id
                            WHERE b.status = "completed" 
                            AND s.movie_id = movies.id
                            AND DATE(t.created_at) = "' . $today->format('Y-m-d') . '"
                            ) as quantity'),
                    DB::raw('(SELECT COALESCE(SUM(p.amount), 0) 
                            FROM tickets t 
                            JOIN bookings b ON t.booking_id = b.id 
                            JOIN payments p ON b.id = p.booking_id
                            JOIN showtimes s ON t.showtime_id = s.id
                            WHERE b.status = "completed" 
                            AND p.status = "completed"
                            AND s.movie_id = movies.id
                            AND DATE(t.created_at) = "' . $today->format('Y-m-d') . '"
                            ) as revenue')
                ])
                ->having('quantity', '>', 0)
                ->groupBy('movies.id')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get();

            // Phân bố doanh thu
            $ticketRevenue = DB::table('payments')
                ->whereDate('paid_at', $today)
                ->where('status', 'completed')
                ->sum('amount');

            $totalRevenue = $ticketRevenue;

            if ($totalRevenue > 0) {
                $revenueDistribution = [
                    ['name' => 'Vé phim', 'value' => 100.0]
                ];
            } else {
                $revenueDistribution = [
                    ['name' => 'Vé phim', 'value' => 0]
                ];
            }

            // Thống kê đặt vé, thanh toán, phim theo ngày đã chọn
            $totalBookings = Booking::whereDate('created_at', $today)->count();

            $totalRevenue = Payment::where('status', 'completed')
                ->whereDate('paid_at', $today)
                ->sum('amount');

            $totalPayments = Payment::whereDate('paid_at', $today)->count();

            $totalAmountPaid = $totalRevenue;

            // ==== Thống kê theo tuần, tháng, năm từ ngày đã chọn ====
            $startOfWeek = $today->copy()->startOfWeek();
            $endOfWeek = $today->copy()->endOfWeek();

            // Doanh thu trong tuần
            $weeklyRevenue = Payment::where('status', 'completed')
                ->whereBetween('paid_at', [$startOfWeek, $endOfWeek])
                ->sum('amount');

            // Doanh thu trong tháng
            $monthlyRevenue = Payment::where('status', 'completed')
                ->whereMonth('paid_at', $today->month)
                ->whereYear('paid_at', $today->year)
                ->sum('amount');

            // Doanh thu trong năm
            $yearlyRevenue = Payment::where('status', 'completed')
                ->whereYear('paid_at', $today->year)
                ->sum('amount');

            // Có thể gán lại cho biến `weeklyAmount`, `monthlyAmount`, `yearlyAmount` nếu muốn phân biệt rõ
            $weeklyAmount = $weeklyRevenue;
            $monthlyAmount = $monthlyRevenue;
            $yearlyAmount = $yearlyRevenue;

            // ==== Thống kê theo trạng thái ====
            $bookingsByStatus = Booking::whereDate('created_at', $today)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')->pluck('total', 'status');

            $paymentsByStatus = Payment::whereDate('paid_at', $today)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')->pluck('total', 'status');

            // ==== Thống kê phim ====
            $totalMovies = Movie::whereDate('created_at', $today)->count();

            $nowShowing = Movie::where('status', 'showing')->whereDate('created_at', $today)->count();
            $upcoming = Movie::where('status', 'upcoming')->whereDate('created_at', $today)->count();
            $ended = Movie::where('status', 'ended')->whereDate('created_at', $today)->count();

            $averageDuration = Movie::whereDate('created_at', $today)->avg('duration_minutes');
            $averageRating = Movie::whereDate('created_at', $today)->avg('average_rating');

            $moviesByStatus = [
                'showing' => $nowShowing,
                'upcoming' => $upcoming,
                'ended' => $ended,
            ];

            // Top 10 phim hot nhất theo ngày
            $hotMovies = Movie::select('movies.*', DB::raw('COUNT(tickets.id) as total_tickets_sold'))
                ->leftJoin('showtimes', 'movies.id', '=', 'showtimes.movie_id')
                ->leftJoin('tickets', function ($join) use ($today) {
                    $join->on('showtimes.id', '=', 'tickets.showtime_id')
                        ->whereDate('tickets.created_at', $today);
                })
                ->groupBy('movies.id')
                ->orderByDesc('total_tickets_sold')
                ->limit(10)
                ->get();
        } elseif ($type === 'month') {
            $month = (int) $request->get('month', Carbon::now()->month);
            $year = (int) $request->get('year', Carbon::now()->year);
            $now = Carbon::create($year, $month, 1)->setTimezone('Asia/Ho_Chi_Minh');

            // Thống kê doanh thu theo ngày trong tháng
            $daysInMonth = $now->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = Carbon::create($year, $month, $d, 0, 0, 0, 'Asia/Ho_Chi_Minh');
                $revenueDates[] = $date->format('d/m');
                $revenueData[] = Payment::where('status', 'completed')
                    ->whereDate('paid_at', $date)
                    ->sum('amount');
            }

            // Thống kê lượt đặt vé theo giờ trong tháng
            for ($h = 0; $h < 24; $h++) {
                $bookingData[$h] = Booking::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->whereRaw('HOUR(created_at) = ?', [$h])
                    ->count();
            }

            // Top phim bán chạy trong tháng
            $topProducts = Movie::with(['genres'])
                ->select([
                    'movies.id',
                    'movies.name',
                    'movies.poster_url',
                    'movies.duration_minutes',
                    'movies.release_date',
                    'movies.description',
                    'movies.status',
                    'movies.average_rating'
                ])
                ->whereIn('movies.status', ['showing', 'ended'])
                ->addSelect([
                    DB::raw('(SELECT COUNT(t.id) 
                            FROM tickets t 
                            JOIN bookings b ON t.booking_id = b.id 
                            JOIN showtimes s ON t.showtime_id = s.id
                            WHERE b.status = "completed" 
                            AND s.movie_id = movies.id
                            AND YEAR(t.created_at) = ' . $year . '
                            AND MONTH(t.created_at) = ' . $month . '
                            ) as quantity'),
                    DB::raw('(SELECT COALESCE(SUM(p.amount), 0) 
                            FROM tickets t 
                            JOIN bookings b ON t.booking_id = b.id 
                            JOIN payments p ON b.id = p.booking_id
                            JOIN showtimes s ON t.showtime_id = s.id
                            WHERE b.status = "completed" 
                            AND p.status = "completed"
                            AND s.movie_id = movies.id
                            AND YEAR(t.created_at) = ' . $year . '
                            AND MONTH(t.created_at) = ' . $month . '
                            ) as revenue')
                ])
                ->having('quantity', '>', 0)
                ->groupBy('movies.id')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get();

            // Phân bố doanh thu trong tháng
            $ticketRevenue = DB::table('payments')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $month)
                ->where('status', 'completed')
                ->sum('amount');

            $totalRevenue = $ticketRevenue;

            if ($totalRevenue > 0) {
                $revenueDistribution = [
                    ['name' => 'Vé phim', 'value' => 100.0]
                ];
            } else {
                $revenueDistribution = [
                    ['name' => 'Vé phim', 'value' => 0]
                ];
            }

            // Thống kê đặt vé, thanh toán, phim theo tháng đã chọn
            $totalBookings = Booking::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $totalRevenue = Payment::where('status', 'completed')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $month)
                ->sum('amount');

            $weeklyRevenue = Payment::where('status', 'completed')
                ->whereBetween('paid_at', [
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek()
                ])
                ->sum('amount');
            $monthlyRevenue = $totalRevenue;

            $bookingsByStatus = Booking::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')->pluck('total', 'status');
            $paymentsByStatus = Payment::whereYear('paid_at', $year)
                ->whereMonth('paid_at', $month)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')->pluck('total', 'status');

            $totalMovies = Movie::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $nowShowing = Movie::where('status', 'showing')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $upcoming = Movie::where('status', 'upcoming')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $ended = Movie::where('status', 'ended')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $averageDuration = Movie::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->avg('duration_minutes');
            $averageRating = Movie::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->avg('average_rating');
            $moviesByStatus = [
                'showing' => $nowShowing,
                'upcoming' => $upcoming,
                'ended' => $ended,
            ];

            // Top 10 phim hot nhất theo tháng
            $hotMovies = Movie::select('movies.*', DB::raw('COUNT(tickets.id) as total_tickets_sold'))
                ->leftJoin('showtimes', 'movies.id', '=', 'showtimes.movie_id')
                ->leftJoin('tickets', function ($join) use ($year, $month) {
                    $join->on('showtimes.id', '=', 'tickets.showtime_id')
                        ->whereYear('tickets.created_at', $year)
                        ->whereMonth('tickets.created_at', $month);
                })
                ->groupBy('movies.id')
                ->orderByDesc('total_tickets_sold')
                ->limit(10)
                ->get();
        } elseif ($type === 'year') {
            $year = (int) $request->get('year', Carbon::now()->year);
            $now = Carbon::create($year, 1, 1)->setTimezone('Asia/Ho_Chi_Minh');

            // Thống kê doanh thu theo tháng trong năm
            for ($m = 1; $m <= 12; $m++) {
                $revenueDates[] = 'Tháng ' . $m;
                $revenueData[] = Payment::where('status', 'completed')
                    ->whereYear('paid_at', $year)
                    ->whereMonth('paid_at', $m)
                    ->sum('amount');
            }

            // Top 10 phim hot nhất theo năm
            $hotMovies = Movie::select('movies.*', DB::raw('COUNT(tickets.id) as total_tickets_sold'))
                ->leftJoin('showtimes', 'movies.id', '=', 'showtimes.movie_id')
                ->leftJoin('tickets', function ($join) use ($year) {
                    $join->on('showtimes.id', '=', 'tickets.showtime_id')
                        ->whereYear('tickets.created_at', $year);
                })
                ->groupBy('movies.id')
                ->orderByDesc('total_tickets_sold')
                ->limit(10)
                ->get();

            // Top phim bán chạy trong năm
            $topProducts = Movie::with(['genres'])
                ->select([
                    'movies.id',
                    'movies.name',
                    'movies.poster_url',
                    'movies.duration_minutes',
                    'movies.release_date',
                    'movies.description',
                    'movies.status',
                    'movies.average_rating'
                ])
                ->whereIn('movies.status', ['showing', 'ended'])
                ->addSelect([
                    DB::raw('(SELECT COUNT(t.id) 
                            FROM tickets t 
                            JOIN bookings b ON t.booking_id = b.id 
                            JOIN showtimes s ON t.showtime_id = s.id
                            WHERE b.status = "completed" 
                            AND s.movie_id = movies.id
                            AND YEAR(t.created_at) = ' . $year . '
                            ) as quantity'),
                    DB::raw('(SELECT COALESCE(SUM(p.amount), 0) 
                            FROM tickets t 
                            JOIN bookings b ON t.booking_id = b.id 
                            JOIN payments p ON b.id = p.booking_id
                            JOIN showtimes s ON t.showtime_id = s.id
                            WHERE b.status = "completed" 
                            AND p.status = "completed"
                            AND s.movie_id = movies.id
                            AND YEAR(t.created_at) = ' . $year . '
                            ) as revenue')
                ])
                ->having('quantity', '>', 0)
                ->groupBy('movies.id')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get();

            // Phân bố doanh thu trong năm
            $ticketRevenue = DB::table('payments')
                ->whereYear('paid_at', $year)
                ->where('status', 'completed')
                ->sum('amount');

            $totalRevenue = $ticketRevenue;

            if ($totalRevenue > 0) {
                $revenueDistribution = [
                    ['name' => 'Vé phim', 'value' => 100.0]
                ];
            } else {
                $revenueDistribution = [
                    ['name' => 'Vé phim', 'value' => 0]
                ];
            }

            // Tổng doanh thu và thanh toán theo năm được chọn
            $totalRevenue = Payment::where('status', 'completed')
                ->whereYear('paid_at', $year)
                ->sum('amount');

            $totalBookings = Booking::whereYear('created_at', $year)->count();
            $totalPayments = Payment::whereYear('paid_at', $year)->count();
            $totalAmountPaid = $totalRevenue;

            // Gán biến đồng nhất cho hiển thị view
            $yearlyRevenue = $totalRevenue;
            $yearlyAmount = $totalRevenue;

            // Doanh thu tuần hiện tại
            $weeklyRevenue = Payment::where('status', 'completed')
                ->whereBetween('paid_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->sum('amount');
            $weeklyAmount = $weeklyRevenue;

            // Doanh thu tháng hiện tại
            $monthlyRevenue = Payment::where('status', 'completed')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', Carbon::now()->month)
                ->sum('amount');
            $monthlyAmount = $monthlyRevenue;

            // Thống kê trạng thái đặt vé và thanh toán
            $bookingsByStatus = Booking::whereYear('created_at', $year)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')->pluck('total', 'status');

            $paymentsByStatus = Payment::whereYear('paid_at', $year)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')->pluck('total', 'status');

            // Thống kê phim
            $totalMovies = Movie::whereYear('created_at', $year)->count();
            $nowShowing = Movie::where('status', 'showing')->whereYear('created_at', $year)->count();
            $upcoming = Movie::where('status', 'upcoming')->whereYear('created_at', $year)->count();
            $ended = Movie::where('status', 'ended')->whereYear('created_at', $year)->count();
            $averageDuration = Movie::whereYear('created_at', $year)->avg('duration_minutes');
            $averageRating = Movie::whereYear('created_at', $year)->avg('average_rating');

            $moviesByStatus = [
                'showing' => $nowShowing,
                'upcoming' => $upcoming,
                'ended' => $ended,
            ];

            // Get current year, month from request
            $currentYear = (int) $request->get('year', Carbon::now()->year);
            $currentMonth = (int) $request->get('month', Carbon::now()->month);

            // Top 10 phim hot theo số vé bán
            $hotMovies = Movie::with(['genres'])
                ->select([
                    'movies.id',
                    'movies.name',
                    'movies.poster_url',
                    'movies.duration_minutes',
                    'movies.release_date',
                    'movies.description',
                    'movies.status'
                ])
                ->where('movies.status', 'showing')
                ->addSelect([
                    DB::raw('(SELECT COUNT(t.id) 
                            FROM tickets t 
                            JOIN bookings b ON t.booking_id = b.id 
                            JOIN showtimes s ON t.showtime_id = s.id
                            WHERE b.status = "completed" 
                            AND s.movie_id = movies.id
                            ' . $this->getDateCondition($type, $request) . '
                            ) as total_tickets'),
                    DB::raw('(SELECT COALESCE(SUM(p.amount), 0) 
                            FROM tickets t 
                            JOIN bookings b ON t.booking_id = b.id 
                            JOIN payments p ON b.id = p.booking_id
                            JOIN showtimes s ON t.showtime_id = s.id
                            WHERE b.status = "completed" 
                            AND p.status = "completed"
                            AND s.movie_id = movies.id
                            ' . $this->getDateCondition($type, $request) . '
                            ) as total_revenue')
                ])
            ->groupBy('movies.id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
        }

        $movieStats = DB::table('movies')
            ->select(
                'movies.name',
                DB::raw('GROUP_CONCAT(DISTINCT genres.name SEPARATOR ", ") as genres'),
                DB::raw('COUNT(DISTINCT tickets.id) as total_tickets'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as total_revenue')
            )
            ->leftJoin('movie_genres', 'movies.id', '=', 'movie_genres.movie_id')
            ->leftJoin('genres', 'movie_genres.genre_id', '=', 'genres.id')
            ->leftJoin('showtimes', 'movies.id', '=', 'showtimes.movie_id')
            ->leftJoin('tickets', 'showtimes.id', '=', 'tickets.showtime_id')
            ->leftJoin('bookings', 'tickets.booking_id', '=', 'bookings.id')
            ->leftJoin('payments', function ($join) {
                $join->on('bookings.id', '=', 'payments.booking_id')
                    ->where('payments.status', 'completed');
            });

        // Apply date filters based on type
        if ($type === 'day') {
            $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::today();
            $movieStats = $movieStats->whereDate('tickets.created_at', $date);
        } elseif ($type === 'month') {
            $year = (int) $request->get('year', Carbon::now()->year);
            $month = (int) $request->get('month', Carbon::now()->month);
            $movieStats = $movieStats->whereYear('tickets.created_at', $year)
                                   ->whereMonth('tickets.created_at', $month);
        } elseif ($type === 'year') {
            $year = (int) $request->get('year', Carbon::now()->year);
            $movieStats = $movieStats->whereYear('tickets.created_at', $year);
        }

        $movieStats = $movieStats->groupBy('movies.id', 'movies.name')
                                ->orderByDesc('total_revenue')
                                ->paginate(10);


        $selectedYear = $request->get('year', Carbon::now()->year);
        $yearlyAmount = Payment::whereYear('paid_at', $selectedYear)->sum('amount');

        // ===== THỐNG KÊ ĐÁNH GIÁ =====
        // Check if reviews table exists
        $hasReviewsTable = Schema::hasTable('reviews');
        
        // Default values if reviews table doesn't exist
        $totalReviews = 0;
        $approvedReviews = 0;
        $pendingReviews = 0;
        $rejectedReviews = 0;
        $ratingDistribution = collect();
        $reviewMonthlyStats = collect();
        $topReviewedMovies = collect();
        $topRatedMovies = collect();
        
        if ($hasReviewsTable) {
            // Thống kê tổng quan đánh giá
            $totalReviews = Review::count();
            $approvedReviews = Review::where('status', 'approved')->count();
            $pendingReviews = Review::where('status', 'pending')->count();
            $rejectedReviews = Review::where('status', 'rejected')->count();
            
            // Phân bố rating
            $ratingDistribution = Review::select('rating_star', DB::raw('COUNT(*) as count'))
                ->where('status', 'approved')
                ->groupBy('rating_star')
                ->orderBy('rating_star')
                ->get();
                
            // Thống kê đánh giá theo tháng (12 tháng gần nhất)
            $reviewMonthlyStats = Review::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(rating_star) as avg_rating')
            )
            ->where('status', 'approved')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
            
            // Top 5 phim có nhiều đánh giá nhất
            $topReviewedMovies = Movie::select('movies.*')
                ->withCount(['reviews' => function ($query) {
                    $query->where('status', 'approved');
                }])
                ->having('reviews_count', '>', 0)
                ->orderBy('reviews_count', 'desc')
                ->take(5)
                ->get();
                
            // Top 5 phim có rating cao nhất
            $topRatedMovies = Movie::select('movies.*')
                ->where('average_rating', '>', 0)
                ->orderBy('average_rating', 'desc')
                ->take(5)
                ->get();
        }

        // Get movie statistics with hourly ticket distribution
        $hourlyStats = DB::table('movies')
            ->select(
                'movies.id as movie_id',
                DB::raw('HOUR(s.start_time) as hour'),
                DB::raw('COUNT(DISTINCT t.id) as ticket_count')
            )
            ->leftJoin('showtimes as s', 'movies.id', '=', 's.movie_id')
            ->leftJoin('tickets as t', 's.id', '=', 't.showtime_id')
            ->whereIn('movies.status', ['showing', 'ended'])
            ->groupBy('movies.id', DB::raw('HOUR(s.start_time)'))
            ->get()
            ->groupBy('movie_id');

        $hotestMovies = Movie::select(
            'movies.*',
            DB::raw('COUNT(DISTINCT t.id) as total_tickets'),
            DB::raw('COALESCE(SUM(p.amount), 0) as total_revenue'),
            DB::raw('COUNT(DISTINCT s.id) as total_showtimes'),
            DB::raw('COUNT(DISTINCT b.id) as total_bookings'),
            DB::raw('GROUP_CONCAT(DISTINCT g.name) as genres')
        )
        ->leftJoin('movie_genres as mg', 'movies.id', '=', 'mg.movie_id')
        ->leftJoin('genres as g', 'mg.genre_id', '=', 'g.id')
        ->leftJoin('showtimes as s', 'movies.id', '=', 's.movie_id')
        ->leftJoin('tickets as t', 's.id', '=', 't.showtime_id')
        ->leftJoin('bookings as b', 't.booking_id', '=', 'b.id')
        ->leftJoin('payments as p', function($join) {
            $join->on('b.id', '=', 'p.booking_id')
                ->where('p.status', '=', 'completed');
        })
        ->whereIn('movies.status', ['showing', 'ended'])
        ->groupBy('movies.id')
        ->orderByDesc('total_revenue')
        ->get();

        // Process hourly tickets data and set hotestMovie
        foreach ($hotestMovies as $movie) {
            $hourlyData = array_fill(0, 24, 0);
            
            // Get hourly stats for this movie
            if (isset($hourlyStats[$movie->id])) {
                foreach ($hourlyStats[$movie->id] as $stat) {
                    $hour = $stat->hour;
                    if ($hour !== null) {
                        $hourlyData[$hour] = $stat->ticket_count;
                    }
                }
            }

            // Map to 8 time slots (8h-24h)
            $timeSlots = array_fill(0, 8, 0);
            for ($i = 8; $i < 24; $i++) {
                $slotIndex = floor(($i - 8) / 2);
                if ($slotIndex < 8) {
                    $timeSlots[$slotIndex] += $hourlyData[$i];
                }
            }
            $movie->hourly_tickets = $timeSlots;
        }

        $hotestMovie = $hotestMovies->first();

        return view('admin.dashboard', compact(
            'type',
            'totalBookings',
            'totalRevenue',
            'weeklyRevenue', 
            'monthlyRevenue',
            'nowShowing',
            'upcoming',
            'ended',
            'averageDuration',
            'averageRating',
            'moviesByStatus',
            'hotestMovies',
            'revenueData',
            'revenueDates',
            'bookingData',
            'topProducts',
            'revenueDistribution',
            // Review statistics
            'totalReviews',
            'approvedReviews',
            'pendingReviews', 
            'rejectedReviews',
            'ratingDistribution',
            'reviewMonthlyStats',
            'topReviewedMovies',
            'topRatedMovies'
        ));
    }
}