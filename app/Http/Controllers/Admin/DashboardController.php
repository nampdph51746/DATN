<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'day'); // day / month / year
        $today = Carbon::today();

        // Biểu đồ booking & doanh thu thay đổi theo filter
        $months = [];
        $bookingData = [];
        $revenueData = [];
        $monthsLabel = [];
        $yearlyRevenue = 0;
        $yearlyAmount = 0;
        if ($type === 'day') {
            // Lấy ngày từ request, mặc định là hôm nay
            $today = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::today();

            // Thống kê theo từng giờ trong ngày được chọn
            $hours = [];
            $bookingData = [];
            $revenueData = [];
            for ($h = 0; $h < 24; $h++) {
                $label = sprintf('%02d:00', $h);
                $hours[] = $label;
                $bookingData[] = Booking::whereDate('created_at', $today)
                    ->whereRaw('HOUR(created_at) = ?', [$h])
                    ->count();
                $revenueData[] = Payment::where('status', 'completed')
                    ->whereDate('paid_at', $today)
                    ->whereRaw('HOUR(paid_at) = ?', [$h])
                    ->sum('amount');
            }
            $months = $hours;
            $monthsLabel = $hours;

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

            // Lấy số ngày trong tháng hiện tại
            $daysInMonth = $now->daysInMonth;
            $months = [];
            $bookingData = [];
            $revenueData = [];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = Carbon::create($year, $month, $d, 0, 0, 0, 'Asia/Ho_Chi_Minh');
                $label = $date->format('d/m');
                $months[] = $label;

                $bookingData[] = Booking::whereDate('created_at', $date)->count();
                $revenueData[] = Payment::where('status', 'completed')
                    ->whereDate('paid_at', $date)
                    ->sum('amount');
            }

            $monthsLabel = $months;

            // Thống kê đặt vé, thanh toán, phim theo tháng đã chọn
            $totalBookings = Booking::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
            $totalRevenue = Payment::where('status', 'completed')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $month)
                ->sum('amount');
            $totalPayments = Payment::whereYear('paid_at', $year)->whereMonth('paid_at', $month)->count();
            $totalAmountPaid = Payment::where('status', 'completed')->whereYear('paid_at', $year)->whereMonth('paid_at', $month)->sum('amount');

            $weeklyRevenue = Payment::where('status', 'completed')
                ->whereBetween('paid_at', [
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek()
                ])
                ->sum('amount');
            $monthlyRevenue = $totalRevenue;
            $weeklyAmount = $weeklyRevenue;
            $monthlyAmount = $monthlyRevenue;

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

            // 12 tháng trong năm được chọn
            $months = [];
            $bookingData = [];
            $revenueData = [];
            for ($m = 1; $m <= 12; $m++) {
                $months[] = sprintf('%02d/%d', $m, $year);
                $bookingData[] = Booking::whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->count();
                $revenueData[] = Payment::where('status', 'completed')
                    ->whereYear('paid_at', $year)
                    ->whereMonth('paid_at', $m)
                    ->sum('amount');
            }
            $monthsLabel = $months;

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

            // Top 10 phim hot theo số vé bán trong năm
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
        }

        $movieStats = DB::table('movies')
            ->select(
                'movies.name as movie',
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
            })
            ->groupBy('movies.id', 'movies.name')
            ->orderByDesc('total_tickets')
            ->paginate(10);


        $selectedYear = $request->get('year', Carbon::now()->year);
        $yearlyAmount = Payment::whereYear('paid_at', $selectedYear)->sum('amount');

        return view('admin.dashboard', compact(
            'type',
            'months',
            'monthsLabel',
            'bookingData',
            'revenueData',
            'totalBookings',
            'totalRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'bookingsByStatus',
            'totalPayments',
            'totalAmountPaid',
            'weeklyAmount',
            'monthlyAmount',
            'yearlyRevenue',
            'paymentsByStatus',
            'totalMovies',
            'nowShowing',
            'upcoming',
            'ended',
            'averageDuration',
            'averageRating',
            'moviesByStatus',
            'hotMovies',
            'movieStats',
            'yearlyAmount',
        ));
    }
}