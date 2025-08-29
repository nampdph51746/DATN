<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\Movie;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'day');
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::today();

        // Try cache first
        $cacheKey = "dashboard_stats_{$date->format('Y-m-d')}_{$type}";
        if (config('app.env') !== 'local' && Cache::has($cacheKey)) {
            return view('admin.dashboard', Cache::get($cacheKey));
        }

        // Initialize data array
        $data = $this->initializeData($type, $date);
        
        // Process statistics based on type
        switch ($type) {
            case 'day':
                $this->processDailyStats($data, $date);
                break;
            case 'month':
                $this->processMonthlyStats($data, $date);
                break;
            case 'year':
                $this->processYearlyStats($data, $date);
                break;
        }

        // Get movie statistics
        $data['movieStats'] = $this->getMovieStatistics($type, $date);
        $data['hotestMovie'] = $this->getHotestMovie();

        // Cache the results
        if (config('app.env') !== 'local') {
            Cache::put($cacheKey, $data, now()->addMinutes(15));
        }

        return view('admin.dashboard', $data);
    }

    protected function initializeData($type, $date)
    {
        // Get movie counts by status
        $moviesByStatus = Movie::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get counts for different movie statuses
        $nowShowing = $moviesByStatus['SHOWING'] ?? 0;
        $upcoming = $moviesByStatus['UPCOMING'] ?? 0;
        $ended = $moviesByStatus['ENDED'] ?? 0;

        // Calculate total movies
        $totalMovies = array_sum($moviesByStatus);

        // Calculate average movie duration
        $averageDuration = Movie::avg('duration_minutes');

        // Get average rating across all movies
        $averageRating = Movie::avg('average_rating') ?? 0;

        // Get hot movies (currently showing movies with highest ticket sales)
        $hotMovies = Movie::select(
            'movies.id',
            'movies.name',
            'movies.duration_minutes',
            'movies.release_date',
            'movies.poster_url',
            'movies.description',
            'movies.status',
            'movies.average_rating',
            DB::raw('COUNT(DISTINCT t.id) as total_tickets'),
            DB::raw('COALESCE(SUM(p.amount), 0) as total_revenue'),
            DB::raw('COUNT(DISTINCT s.id) as total_showtimes'),
            DB::raw('COUNT(DISTINCT b.id) as total_bookings')
        )
            ->leftJoin('showtimes as s', 'movies.id', '=', 's.movie_id')
            ->leftJoin('tickets as t', 's.id', '=', 't.showtime_id')
            ->leftJoin('bookings as b', 't.booking_id', '=', 'b.id')
            ->leftJoin('payments as p', function($join) {
                $join->on('b.id', '=', 'p.booking_id')
                    ->where('p.status', '=', 'completed');
            })
            ->where('movies.status', '=', 'showing')
            ->groupBy([
                'movies.id',
                'movies.name',
                'movies.duration_minutes',
                'movies.release_date',
                'movies.poster_url',
                'movies.description',
                'movies.status',
                'movies.average_rating'
            ])
            ->orderByDesc('total_tickets')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Get booking status stats
        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'type' => $type,
            'date' => $date,
            'months' => [],
            'monthsLabel' => [],
            'bookingData' => [],
            'revenueData' => [],
            'weeklyRevenue' => 0,
            'monthlyRevenue' => 0,
            'yearlyRevenue' => 0,
            'weeklyAmount' => 0,
            'monthlyAmount' => 0,
            'yearlyAmount' => 0,
            'totalBookings' => 0,
            'totalRevenue' => 0,
            'totalPayments' => 0,
            'totalAmountPaid' => 0,
            'bookingStatusStats' => [],
            'paymentStatusStats' => [],
            'moviesByStatus' => $moviesByStatus,
            'nowShowing' => $nowShowing,
            'upcoming' => $upcoming,
            'ended' => $ended,
            'totalMovies' => $totalMovies,
            'averageDuration' => round($averageDuration ?? 0),
            'averageRating' => round($averageRating, 1),
            'bookingsByStatus' => $bookingsByStatus,
            'totalSeats' => 0,
            'bookedSeats' => 0,
            'occupancyRate' => 0,
            'movieStats' => null,
            'hotestMovie' => null,
            'hotMovies' => $hotMovies
        ];
    }

    protected function processDailyStats(array &$data, Carbon $date)
    {
        // Get hourly statistics
        $hourlyStats = $this->getHourlyStats($date);
        $data['months'] = $data['monthsLabel'] = $hourlyStats['hours'];
        $data['bookingData'] = $hourlyStats['bookingData'];
        $data['revenueData'] = $hourlyStats['revenueData'];

        // Get daily totals
        $dailyStats = $this->getDailyTotals($date);
        $data['totalBookings'] = $dailyStats->total_bookings;
        $data['totalPayments'] = $dailyStats->total_payments;
        $data['totalRevenue'] = $data['totalAmountPaid'] = $dailyStats->total_revenue;

        // Get time range revenues
        $timeRangeStats = $this->getTimeRangeRevenue($date);
        $data['weeklyRevenue'] = $data['weeklyAmount'] = $timeRangeStats->weekly_revenue ?? 0;
        $data['monthlyRevenue'] = $data['monthlyAmount'] = $timeRangeStats->monthly_revenue ?? 0;
        $data['yearlyRevenue'] = $data['yearlyAmount'] = $timeRangeStats->yearly_revenue ?? 0;

        // Get status statistics
        $data['bookingStatusStats'] = $this->getBookingStatusStats($date, 'day');
        $data['paymentStatusStats'] = $this->getPaymentStatusStats($date, 'day');

        // Get seat occupancy statistics
        $seatStats = $this->getSeatOccupancyStats($date, 'day');
        $data['bookedSeats'] = $seatStats->booked_seats ?? 0;
        $data['totalSeats'] = $seatStats->total_seats ?? 0;
        $data['occupancyRate'] = $data['totalSeats'] > 0 
            ? round(($data['bookedSeats'] / $data['totalSeats']) * 100, 2)
            : 0;
    }

    protected function processMonthlyStats(array &$data, Carbon $date)
    {
        $daysInMonth = $date->daysInMonth;

        // Get monthly statistics
        $monthlyStats = $this->getMonthlyStats($date->year, $date->month);

        // Process data arrays
        $bookingData = array_fill(1, $daysInMonth, 0);
        $revenueData = array_fill(1, $daysInMonth, 0);
        
        foreach ($monthlyStats as $stat) {
            if (isset($stat->day)) {
                $bookingData[$stat->day] = $stat->bookings;
                $revenueData[$stat->day] = $stat->revenue;
            }
        }

        // Set chart data
        $data['months'] = range(1, $daysInMonth);
        $data['monthsLabel'] = array_map(function($d) { return "Ngày $d"; }, $data['months']);
        $data['bookingData'] = array_values($bookingData);
        $data['revenueData'] = array_values($revenueData);

        // Calculate totals
        $data['totalRevenue'] = array_sum($revenueData);
        $data['totalAmountPaid'] = $data['totalRevenue'];
        $data['monthlyRevenue'] = $data['monthlyAmount'] = $data['totalRevenue'];

        // Get status statistics
        $data['bookingStatusStats'] = $this->getBookingStatusStats($date, 'month');
        $data['paymentStatusStats'] = $this->getPaymentStatusStats($date, 'month');

        // Get seat occupancy statistics
        $seatStats = $this->getSeatOccupancyStats($date, 'month');
        $data['bookedSeats'] = $seatStats->booked_seats ?? 0;
        $data['totalSeats'] = $seatStats->total_seats ?? 0;
        $data['occupancyRate'] = $data['totalSeats'] > 0 
            ? round(($data['bookedSeats'] / $data['totalSeats']) * 100, 2)
            : 0;
    }

    protected function processYearlyStats(array &$data, Carbon $date)
    {
        // Get yearly statistics
        $yearlyStats = $this->getYearlyStats($date->year);

        // Process data arrays
        $bookingData = array_fill(1, 12, 0);
        $revenueData = array_fill(1, 12, 0);
        
        foreach ($yearlyStats as $stat) {
            if (isset($stat->month)) {
                $bookingData[$stat->month] = $stat->bookings;
                $revenueData[$stat->month] = $stat->revenue;
            }
        }

        // Set chart data
        $data['months'] = array_map(function($m) use ($date) {
            return sprintf('%02d/%d', $m, $date->year);
        }, range(1, 12));
        $data['monthsLabel'] = $data['months'];
        $data['bookingData'] = array_values($bookingData);
        $data['revenueData'] = array_values($revenueData);

        // Calculate totals
        $data['totalRevenue'] = array_sum($revenueData);
        $data['totalAmountPaid'] = $data['totalRevenue'];
        $data['yearlyRevenue'] = $data['yearlyAmount'] = $data['totalRevenue'];

        // Get status statistics
        $data['bookingStatusStats'] = $this->getBookingStatusStats($date, 'year');
        $data['paymentStatusStats'] = $this->getPaymentStatusStats($date, 'year');

        // Get seat occupancy statistics
        $seatStats = $this->getSeatOccupancyStats($date, 'year');
        $data['bookedSeats'] = $seatStats->booked_seats ?? 0;
        $data['totalSeats'] = $seatStats->total_seats ?? 0;
        $data['occupancyRate'] = $data['totalSeats'] > 0 
            ? round(($data['bookedSeats'] / $data['totalSeats']) * 100, 2)
            : 0;
    }

    protected function getHourlyStats($date)
    {
        // Get bookings by hour
        $bookingsByHour = DB::table('bookings')
            ->whereDate('created_at', $date)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Get revenue by hour
        $revenueByHour = DB::table('payments')
            ->where('status', 'completed')
            ->whereDate('paid_at', $date)
            ->selectRaw('HOUR(paid_at) as hour, SUM(amount) as revenue')
            ->groupBy('hour')
            ->pluck('revenue', 'hour')
            ->toArray();

        $hours = array_map(function($h) {
            return sprintf('%02d:00', $h);
        }, range(0, 23));

        $bookingData = array_fill(0, 24, 0);
        $revenueData = array_fill(0, 24, 0);

        foreach ($bookingsByHour as $hour => $count) {
            $bookingData[$hour] = $count;
        }

        foreach ($revenueByHour as $hour => $revenue) {
            $revenueData[$hour] = $revenue;
        }

        return [
            'hours' => $hours,
            'bookingData' => array_values($bookingData),
            'revenueData' => array_values($revenueData)
        ];
    }

    protected function getDailyTotals($date)
    {
        return DB::select("
            SELECT 
                (SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = ?) as total_bookings,
                (SELECT COUNT(*) FROM payments WHERE DATE(paid_at) = ?) as total_payments,
                (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'COMPLETED' AND DATE(paid_at) = ?) as total_revenue
        ", [$date, $date, $date])[0];
    }

    protected function getTimeRangeRevenue($date)
    {
        return DB::select("
            SELECT 
                SUM(CASE WHEN paid_at BETWEEN ? AND ? THEN amount ELSE 0 END) as weekly_revenue,
                SUM(CASE WHEN YEAR(paid_at) = ? AND MONTH(paid_at) = ? THEN amount ELSE 0 END) as monthly_revenue,
                SUM(CASE WHEN YEAR(paid_at) = ? THEN amount ELSE 0 END) as yearly_revenue
            FROM payments 
            WHERE status = 'COMPLETED'
                AND paid_at >= ?
                AND paid_at <= ?
        ", [
            $date->copy()->startOfWeek(),
            $date->copy()->endOfWeek(),
            $date->year,
            $date->month,
            $date->year,
            $date->copy()->startOfYear(),
            $date->copy()->endOfYear()
        ])[0];
    }

    protected function getBookingStatusStats($date, $type)
    {
        $query = DB::table('bookings')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status');

        switch ($type) {
            case 'day':
                $query->whereDate('created_at', $date);
                break;
            case 'month':
                $query->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month);
                break;
            case 'year':
                $query->whereYear('created_at', $date->year);
                break;
        }

        return $query->pluck('total', 'status')->toArray();
    }

    protected function getPaymentStatusStats($date, $type)
    {
        $query = DB::table('payments')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status');

        switch ($type) {
            case 'day':
                $query->whereDate('paid_at', $date);
                break;
            case 'month':
                $query->whereYear('paid_at', $date->year)
                    ->whereMonth('paid_at', $date->month);
                break;
            case 'year':
                $query->whereYear('paid_at', $date->year);
                break;
        }

        return $query->pluck('total', 'status')->toArray();
    }

    protected function getSeatOccupancyStats($date, $type)
    {
        $query = "SELECT 
            COUNT(DISTINCT t.id) as booked_seats,
            SUM(r.capacity) as total_seats
        FROM showtimes s
        LEFT JOIN rooms r ON s.room_id = r.id
        LEFT JOIN tickets t ON s.id = t.showtime_id
        LEFT JOIN bookings b ON t.booking_id = b.id
        WHERE ";

        switch ($type) {
            case 'day':
                $query .= "DATE(s.start_time) = ?";
                $params = [$date];
                break;
            case 'month':
                $query .= "YEAR(s.start_time) = ? AND MONTH(s.start_time) = ?";
                $params = [$date->year, $date->month];
                break;
            case 'year':
                $query .= "YEAR(s.start_time) = ?";
                $params = [$date->year];
                break;
            default:
                $query .= "1=0";
                $params = [];
        }

        return DB::select($query, $params)[0];
    }

    protected function getMovieStatistics($type, $date)
    {
        $query = DB::table('movies')
            ->select(
                'movies.id',
                'movies.name',
                DB::raw('COUNT(DISTINCT t.id) as total_tickets'),
                DB::raw('COALESCE(SUM(p.amount), 0) as total_revenue'),
                DB::raw('GROUP_CONCAT(DISTINCT g.name) as genres')
            )
            ->leftJoin('movie_genres as mg', 'movies.id', '=', 'mg.movie_id')
            ->leftJoin('genres as g', 'mg.genre_id', '=', 'g.id')
            ->leftJoin('showtimes as s', 'movies.id', '=', 's.movie_id')
            ->leftJoin('tickets as t', 's.id', '=', 't.showtime_id')
            ->leftJoin('bookings as b', 't.booking_id', '=', 'b.id')
            ->leftJoin('payments as p', function($join) {
                $join->on('b.id', '=', 'p.booking_id')
                    ->where('p.status', '=', 'COMPLETED');
            });

        switch ($type) {
            case 'day':
                $query->whereDate('b.created_at', $date);
                break;
            case 'month':
                $query->whereYear('b.created_at', $date->year)
                    ->whereMonth('b.created_at', $date->month);
                break;
            case 'year':
                $query->whereYear('b.created_at', $date->year);
                break;
        }

        return $query->groupBy('movies.id', 'movies.name')
            ->orderByDesc('total_tickets')
            ->orderByDesc('total_revenue')
            ->paginate(10);
    }

    protected function getHotestMovie()
    {
        return Cache::remember('hotest_movie', 60, function() {
            return Movie::select(
                'movies.id',
                'movies.name',
                'movies.duration_minutes',
                'movies.release_date',
                'movies.poster_url',
                'movies.status',
                'movies.average_rating',
                DB::raw('COUNT(DISTINCT t.id) as total_tickets'),
                DB::raw('COALESCE(SUM(p.amount), 0) as total_revenue'),
                DB::raw('COUNT(DISTINCT s.id) as total_showtimes'),
                DB::raw('COUNT(DISTINCT b.id) as total_bookings')
            )
                ->leftJoin('showtimes as s', 'movies.id', '=', 's.movie_id')
                ->leftJoin('tickets as t', 's.id', '=', 't.showtime_id')
                ->leftJoin('bookings as b', 't.booking_id', '=', 'b.id')
                ->leftJoin('payments as p', function($join) {
                    $join->on('b.id', '=', 'p.booking_id')
                        ->where('p.status', '=', 'completed');
                })
                ->where('movies.status', '=', 'SHOWING')
                ->groupBy([
                    'movies.id',
                    'movies.name',
                    'movies.duration_minutes',
                    'movies.release_date',
                    'movies.poster_url',
                    'movies.status',
                    'movies.average_rating'
                ])
                ->orderByDesc('total_revenue')
                ->first();
        });
    }

    protected function getMonthlyStats($year, $month)
    {
        return DB::select("
            SELECT 
                DAY(b.created_at) as day,
                COUNT(DISTINCT b.id) as bookings,
                COALESCE(SUM(CASE WHEN p.status = 'completed' THEN p.amount ELSE 0 END), 0) as revenue
            FROM bookings b
            LEFT JOIN payments p ON b.id = p.booking_id
            WHERE YEAR(b.created_at) = ? 
            AND MONTH(b.created_at) = ?
            GROUP BY DAY(b.created_at)
            ORDER BY day
        ", [$year, $month]);
    }
}
