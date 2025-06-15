<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        // Thống kê doanh thu hàng tháng (12 tháng gần nhất)
        $revenueData = [];
        $monthsLabel = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabel = $date->format('M Y');
            $monthsLabel[] = $monthLabel;

            $monthlyRevenue = Booking::where('status', '!=', 'cancelled')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('final_amount');

            $revenueData[] = $monthlyRevenue;
        }
        // Thống kê payments
        $totalPayments = DB::table('payments')->count();
        $totalAmountPaid = DB::table('payments')
            ->where('status', 'completed')
            ->sum('amount');

        $paymentsByStatus = DB::table('payments')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $weeklyAmount = DB::table('payments')
            ->where('status', 'completed')
            ->whereBetween('paid_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount');

        $monthlyAmount = DB::table('payments')
            ->where('status', 'completed')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // Thống kê bookings
        $totalBookings = DB::table('bookings')->count();
        $totalRevenue = DB::table('bookings')
            ->where('status', '!=', 'cancelled')
            ->sum('final_amount');

        $bookingsByStatus = DB::table('bookings')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $weeklyRevenue = DB::table('bookings')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('final_amount');

        $monthlyRevenue = DB::table('bookings')
            ->where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('final_amount');

        // Thống kê người dùng
        $totalUsers = User::count();
        $statusCounts = [
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ];
        $verifiedEmails = User::whereNotNull('email_verified_at')->count();
        $unverifiedEmails = User::whereNull('email_verified_at')->count();
        $withAvatar = User::whereNotNull('avatar_url')->count();
        $withLogin = User::whereNotNull('last_login_at')->count();

        $totalMovies = Movie::count();
        $moviesByStatus = Movie::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $nowShowing = Movie::where('status', 'showing')->count();
        $upcoming = Movie::where('status', 'upcoming')->count();
        $ended = Movie::where('status', 'ended')->count();

        $averageDuration = Movie::whereNotNull('duration_minutes')->avg('duration_minutes');
        $averageRating = Movie::whereNotNull('average_rating')->avg('average_rating');


        // Thống kê bookings theo tháng (12 tháng gần nhất)
        $bookingData = [];
        $months = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y'); // Ví dụ: Jun 2024

            $count = Booking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $bookingData[] = $count;
        }



        $activeUsers = User::where('status', UserStatus::Active->value)->count();
        $inactiveUsers = User::where('status', '!=', UserStatus::Active->value)->count();
        $totalUsers = $activeUsers + $inactiveUsers;


        // Các thống kê khác đã có sẵn
        $totalBookings = Booking::count();
        $totalRevenue = Booking::sum('final_amount');
        $weeklyRevenue = Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('final_amount');
        $monthlyRevenue = Booking::whereMonth('created_at', now()->month)->sum('final_amount');

        $bookingsByStatus = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        // Thống kê phim hot (top 10 phim có số lượng vé bán chạy nhất)
        // Lấy top 10 phim có số lượng vé bán chạy nhất
        $hotMovies = DB::table('tickets')
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->where('movies.status', 'showing')
            ->select(
                'movies.id',
                'movies.name',
                'movies.director',
                'movies.actors',
                'movies.duration_minutes',
                'movies.release_date',
                'movies.end_date',
                'movies.description',
                'movies.poster_url',
                'movies.trailer_url',
                'movies.language',
                'movies.country_id',
                'movies.age_limit_id',
                'movies.status',
                'movies.average_rating',
                DB::raw('COUNT(tickets.id) as total_tickets_sold')
            )
            ->groupBy(
                'movies.id',
                'movies.name',
                'movies.director',
                'movies.actors',
                'movies.duration_minutes',
                'movies.release_date',
                'movies.end_date',
                'movies.description',
                'movies.poster_url',
                'movies.trailer_url',
                'movies.language',
                'movies.country_id',
                'movies.age_limit_id',
                'movies.status',
                'movies.average_rating'
            )
            ->orderByDesc('total_tickets_sold')
            ->paginate(10);

        // Trả về view với tất cả biến
        return view('admin.dashboard', [
            // Payments
            'totalPayments'    => $totalPayments,
            'totalAmountPaid'  => $totalAmountPaid,
            'paymentsByStatus' => $paymentsByStatus,
            'weeklyAmount'     => $weeklyAmount,
            'monthlyAmount'    => $monthlyAmount,

            // Bookings
            'totalBookings'    => $totalBookings,
            'totalRevenue'     => $totalRevenue,
            'bookingsByStatus' => $bookingsByStatus,
            'weeklyRevenue'    => $weeklyRevenue,
            'monthlyRevenue'   => $monthlyRevenue,

            // Users
            'totalUsers'       => $totalUsers,
            'statusCounts'     => $statusCounts,
            'verifiedEmails'   => $verifiedEmails,
            'unverifiedEmails' => $unverifiedEmails,
            'withAvatar'       => $withAvatar,
            'withLogin'        => $withLogin,

            // Movies
            'totalMovies'     => $totalMovies,
            'moviesByStatus'  => $moviesByStatus,
            'nowShowing'      => $nowShowing,
            'upcoming'        => $upcoming,
            'ended'           => $ended,
            'averageDuration' => $averageDuration,
            'averageRating'   => $averageRating,

            // Booking data for chart
            'bookingData'     => $bookingData,
            'months'          => $months,

            // Thống kê người dùng
            'totalUsers'   => $totalUsers,
            'activeUsers'  => $activeUsers,
            'inactiveUsers' => $inactiveUsers,

            // Doanh thu hàng tháng
            'revenueData'  => $revenueData,
            'monthsLabel'  => $monthsLabel,

            // Thống kê phim hot
            'hotMovies' => $hotMovies,

        ]);
    }
}
