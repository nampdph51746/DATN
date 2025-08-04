<?php

namespace App\Services;

use App\Models\Showtime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ShowtimeAvailabilityService
{
    /**
     * Lọc ra các suất chiếu chưa đầy
     */
    public function filterAvailableShowtimes(Collection $showtimes): Collection
    {
        return $showtimes->filter(function (Showtime $showtime) {
            return !$this->isShowtimeFull($showtime);
        });
    }

    /**
     * Kiểm tra suất chiếu có đầy không
     */
    public function isShowtimeFull(Showtime $showtime): bool
    {
        $totalSeats = $this->getTotalSeats($showtime);
        
        // Nếu phòng chưa có ghế, coi như chưa đầy để có thể đặt vé
        if ($totalSeats === 0) {
            \Log::warning("Phòng ID {$showtime->room_id} chưa có ghế nào được cấu hình");
            return false;
        }
        
        $bookedSeats = $this->getBookedSeats($showtime);
        
        return $bookedSeats >= $totalSeats;
    }

    /**
     * Lấy số ghế tổng cộng của phòng
     */
    public function getTotalSeats(Showtime $showtime): int
    {
        // Sử dụng đúng enum SeatStatus::Available thay vì 'active'
        return $showtime->room->seats()->where('status', \App\Enums\SeatStatus::Available->value)->count();
    }

    /**
     * Lấy số ghế đã đặt
     */
    public function getBookedSeats(Showtime $showtime): int
    {
        return $showtime->showtimeSeatStates()
            ->where('status', \App\Enums\SeatStatus::Booked)
            ->count();
    }

    /**
     * Lấy số ghế còn trống
     */
    public function getAvailableSeats(Showtime $showtime): int
    {
        $totalSeats = $this->getTotalSeats($showtime);
        $bookedSeats = $this->getBookedSeats($showtime);
        
        return max(0, $totalSeats - $bookedSeats);
    }

    /**
     * Lấy tỷ lệ lấp đầy (%)
     */
    public function getOccupancyRate(Showtime $showtime): float
    {
        $totalSeats = $this->getTotalSeats($showtime);
        if ($totalSeats === 0) {
            return 0;
        }
        
        $bookedSeats = $this->getBookedSeats($showtime);
        
        return round(($bookedSeats / $totalSeats) * 100, 2);
    }

    /**
     * Lấy thông tin chi tiết về tính khả dụng của suất chiếu
     */
    public function getShowtimeAvailabilityInfo(Showtime $showtime): array
    {
        $totalSeats = $this->getTotalSeats($showtime);
        $bookedSeats = $this->getBookedSeats($showtime);
        $availableSeats = max(0, $totalSeats - $bookedSeats);
        $occupancyRate = $totalSeats > 0 ? round(($bookedSeats / $totalSeats) * 100, 2) : 0;

        return [
            'total_seats' => $totalSeats,
            'booked_seats' => $bookedSeats,
            'available_seats' => $availableSeats,
            'occupancy_rate' => $occupancyRate,
            'is_full' => $bookedSeats >= $totalSeats,
            'is_nearly_full' => $occupancyRate >= 80,
            'status_class' => $this->getStatusClass($occupancyRate, $bookedSeats >= $totalSeats),
        ];
    }

    /**
     * Lấy CSS class dựa trên tình trạng đặt chỗ
     */
    private function getStatusClass(float $occupancyRate, bool $isFull): string
    {
        if ($isFull) {
            return 'sold-out';
        } elseif ($occupancyRate >= 80) {
            return 'nearly-full';
        } elseif ($occupancyRate >= 50) {
            return 'half-full';
        } else {
            return 'available';
        }
    }

    /**
     * Tối ưu hóa: Lấy thông tin tất cả suất chiếu trong một query
     */
    public function getBulkAvailabilityInfo(array $showtimeIds): array
    {
        $results = DB::select("
            SELECT 
                s.id as showtime_id,
                COUNT(seats.id) as total_seats,
                COUNT(CASE WHEN sss.status = 'booked' THEN 1 END) as booked_seats,
                COUNT(seats.id) - COUNT(CASE WHEN sss.status = 'booked' THEN 1 END) as available_seats,
                ROUND((COUNT(CASE WHEN sss.status = 'booked' THEN 1 END) * 100.0 / COUNT(seats.id)), 2) as occupancy_rate
            FROM showtimes s
            INNER JOIN rooms r ON s.room_id = r.id
            INNER JOIN seats ON r.id = seats.room_id AND seats.status = 'available'
            LEFT JOIN showtime_seat_states sss ON s.id = sss.showtime_id AND seats.id = sss.seat_id
            WHERE s.id IN (" . implode(',', array_fill(0, count($showtimeIds), '?')) . ")
            GROUP BY s.id
        ", $showtimeIds);

        $availabilityInfo = [];
        foreach ($results as $result) {
            $isFull = $result->booked_seats >= $result->total_seats;
            $availabilityInfo[$result->showtime_id] = [
                'total_seats' => (int) $result->total_seats,
                'booked_seats' => (int) $result->booked_seats,
                'available_seats' => (int) $result->available_seats,
                'occupancy_rate' => (float) $result->occupancy_rate,
                'is_full' => $isFull,
                'is_nearly_full' => $result->occupancy_rate >= 80,
                'status_class' => $this->getStatusClass($result->occupancy_rate, $isFull),
            ];
        }

        return $availabilityInfo;
    }
}
