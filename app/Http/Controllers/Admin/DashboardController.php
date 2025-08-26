<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\MovieStatus;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $totalBookings = 0;
    protected $totalRevenue = 0;
    protected $weeklyRevenue = 0;
    protected $monthlyRevenue = 0;
    protected $yearlyRevenue = 0;

    public function index(Request $request)
    {
        $type = $request->get('type', 'day');
        $today = Carbon::today();

        // Khởi tạo biến
        $months = [];
        $bookingData = [];
        $revenueData = [];
        $monthsLabel = [];

        // Thống kê chung
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(7))->count();
        $totalUsers = User::count();
        
        // Xử lý theo type
        switch($type) {
            case 'day':
                $this->handleDayStats($request, $today, $months, $bookingData, $revenueData, $monthsLabel);
                break;
            case 'month':
                $this->handleMonthStats($request, $months, $bookingData, $revenueData, $monthsLabel);
                break;
            case 'year':
                $this->handleYearStats($request, $months, $bookingData, $revenueData, $monthsLabel);
                break;
        }

        // Thống kê phim
        $movieStats = $this->getMovieStatistics();
        
        // Thống kê đánh giá
        $reviewStats = $this->getReviewStatistics();

        // Thống kê tăng trưởng
        $growthStats = $this->getGrowthStatistics();

        $nowShowing = $movieStats['showing'] ?? 0;
        $upcoming = $movieStats['upcoming'] ?? 0;
        $pendingReviews = $reviewStats['pending'] ?? 0;

        return view('admin.dashboard', compact(
            'type',
            'months',
            'monthsLabel',
            'bookingData',
            'revenueData',
            'totalBookings',
            'totalRevenue',
            'activeUsers',
            'totalUsers',
            'movieStats',
            'reviewStats',
            'growthStats',
            'weeklyRevenue',
            'monthlyRevenue',
            'yearlyRevenue',
            'nowShowing',
            'upcoming',
            'pendingReviews'
        ));
    }

    private function handleDayStats($request, $today, &$months, &$bookingData, &$revenueData, &$monthsLabel)
    {
        $selectedDate = $request->get('date') ? Carbon::parse($request->get('date')) : $today;
        
        // Khởi tạo mảng giờ
        $hours = range(0, 23);
        $months = $hours;
        $monthsLabel = array_map(function($hour) {
            return sprintf('%02d:00', $hour);
        }, $hours);

        // Lấy dữ liệu đặt vé và doanh thu theo giờ
        foreach ($hours as $hour) {
            $startTime = $selectedDate->copy()->setHour($hour)->startOfHour();
            $endTime = $startTime->copy()->endOfHour();

            $bookings = Booking::whereBetween('created_at', [$startTime, $endTime])->count();
            $revenue = Payment::where('status', 'completed')
                ->whereBetween('paid_at', [$startTime, $endTime])
                ->sum('amount');

            $bookingData[] = $bookings;
            $revenueData[] = $revenue;
        }

        // Tổng hợp thống kê
        $this->totalBookings = array_sum($bookingData);
        $this->totalRevenue = array_sum($revenueData);
        
        // Thống kê theo tuần
        $this->weeklyRevenue = Payment::where('status', 'completed')
            ->whereBetween('paid_at', [
                $selectedDate->copy()->startOfWeek(),
                $selectedDate->copy()->endOfWeek()
            ])->sum('amount');

        // Thống kê theo tháng
        $this->monthlyRevenue = Payment::where('status', 'completed')
            ->whereYear('paid_at', $selectedDate->year)
            ->whereMonth('paid_at', $selectedDate->month)
            ->sum('amount');

        // Thống kê theo năm
        $this->yearlyRevenue = Payment::where('status', 'completed')
            ->whereYear('paid_at', $selectedDate->year)
            ->sum('amount');
    }

    private function handleMonthStats($request, &$months, &$bookingData, &$revenueData, &$monthsLabel)
    {
        $selectedDate = Carbon::create(
            $request->get('year', now()->year),
            $request->get('month', now()->month),
            1
        );

        $daysInMonth = $selectedDate->daysInMonth;
        $months = range(1, $daysInMonth);
        $monthsLabel = $months;

        foreach ($months as $day) {
            $date = $selectedDate->copy()->setDay($day);
            
            $bookings = Booking::whereDate('created_at', $date)->count();
            $revenue = Payment::where('status', 'completed')
                ->whereDate('paid_at', $date)
                ->sum('amount');

            $bookingData[] = $bookings;
            $revenueData[] = $revenue;
        }

        $this->totalBookings = array_sum($bookingData);
        $this->totalRevenue = array_sum($revenueData);
    }

    private function handleYearStats($request, &$months, &$bookingData, &$revenueData, &$monthsLabel)
    {
        $year = $request->get('year', now()->year);
        $months = range(1, 12);
        $monthsLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach ($months as $month) {
            $date = Carbon::create($year, $month, 1);
            
            $bookings = Booking::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            
            $revenue = Payment::where('status', 'completed')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $month)
                ->sum('amount');

            $bookingData[] = $bookings;
            $revenueData[] = $revenue;
        }

        $this->totalBookings = array_sum($bookingData);
        $this->totalRevenue = array_sum($revenueData);
    }

    private function getMovieStatistics()
    {
        return [
            'total' => Movie::count(),
            'showing' => Movie::where('status', MovieStatus::Showing)->count(),
            'upcoming' => Movie::where('status', MovieStatus::Upcoming)->count(),
            'ended' => Movie::where('status', MovieStatus::Ended)->count(),
            'hotMovies' => Movie::withCount('tickets')
                ->orderByDesc('tickets_count')
                ->limit(5)
                ->get()
        ];
    }

    private function getReviewStatistics()
    {
        return [
            'total' => Comment::count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'rejected' => Comment::where('status', 'rejected')->count()
        ];
    }

    private function getGrowthStatistics()
    {
        $currentMonth = now();
        $lastMonth = now()->subMonth();
        
        $currentRevenue = Payment::where('status', 'completed')
            ->whereMonth('paid_at', $currentMonth->month)
            ->sum('amount');
            
        $lastRevenue = Payment::where('status', 'completed')
            ->whereMonth('paid_at', $lastMonth->month)
            ->sum('amount');

        return [
            'revenue_growth' => $lastRevenue ? (($currentRevenue - $lastRevenue) / $lastRevenue) * 100 : 0,
            'current_revenue' => $currentRevenue,
            'last_revenue' => $lastRevenue
        ];
    }
}
